<?php

class UserController extends Controller { 

    function __construct(Model $model) {
        parent::__construct($model);
    }
    
    function activate() {
        $id = Request::read('id');
        $data = Request::read('data');
        $res = $this->getModel()->activateUser($id, $data);
        header('Location: index.php?op=activate&res=' . $res);
        exit();
    }
    
    function add() {
        $user = new User();
        $user->read();
        $repeatedPass = Request::read('repeatedPass');
        $res = -1;
        if ($this->isAdministrator()) {
            if(Filter::isEmail($user->getEmail()) && $user->getPass() === $repeatedPass && $repeatedPass !== '') {
                $res = $this->getModel()->addUser($user);
            }
        }
        header('Location: index.php?action=administrate&op=add&res=' . $res);
        exit();
    }
    
    function administrate() {
        if ($this->isAdministrator()) {
            $this->getModel()->setData('file', '_administrate.html');
            $url = '<li class="menu-title">Extra</li><li><a href="?action=administrate" class="waves-effect waves-primary"><i class="ti-settings"></i><span> Administrate </span></a></li>';
            $this->getModel()->setData('administrate', $url);
            $line = ' <tr class="gradeX">
                        <td>{{id}}</td>
                        <td>{{name}}</td>
                        <td>{{surnames}}</td>
                        <td>{{username}}</td>
                        <td>{{email}}</td>
                        <td>{{role}}</td>
                        <td>{{verified}}</td>
                        <td class="actions">
                            <a href="?action=showEdit&id={{id}}" class="on-default edit-row" data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit"><i class="fa fa-pencil"></i></a>
                            <a href="?action=remove&id={{id}}" class="on-default remove-row" data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete"><i class="fa fa-trash-o"></i></a>
                        </td>
                    </tr>';
            $users = $this->getModel()->getUsers();
            foreach($users as $index => $user) {
                $res = Util::renderText($line, $user->getAttributesValues());
                $all .= $res;
            }
            $this->getModel()->setData('userlines', $all);
        } else {
            $this->index();
        }
    }
    
    function changePass() {
        $user = new User();
        $user->read();
        $user->setId($this->getUser()->getId());
        $oldpass = Request::read('oldpass');
        $res = -1;
        $repeatedPass = Request::read('repeatedPass');
        if($user->getPass() === $repeatedPass) {
            $res = $this->getModel()->changeUserPass($user, $oldpass);
        }
        header('Location: index.php?op=changePass&res=' . $res);
        exit();
    }
    
    function edit() {
        $r = -1;
        $user = new User();
        $user->read();
        $user->setId($this->getUser()->getId());
        $needToLogout = $this->getModel()->hasEmailChanged($user);
        $res = $this->getModel()->editUser($user);
        if($needToLogout) {
           $this->logout();
        }
        header('Location: index.php?op=edit&res=' . $res);
        exit();
    }
    
    function editAdmin() {
        $r = -1;
        $user = new User();
        $user->read();
        $user->setId(Request::read('id'));
        //echo Util::varDump($user);
        if($this->isAdministrator()) {
            if($user->getPass() === Request::read('repeatedPass') && $user->getPass() !== '') {
                $res = $this->getModel()->editUserAdmin($user);
            }
        }
        header('Location: index.php?action=administrate&op=editAdmin&res=' . $res);
        exit();
    }
    
    function index() {
        $op = Request::read('op');
        $res = request::read('res');
        $this->getModel()->setData('msg', $op . ' ' . $res);
        if($this->isLogged()) {
            $this->getModel()->setData('file', '_dashboard.html');
            if($this->isAdministrator()) {
                $url = '<li class="menu-title">Extra</li><li><a href="?action=administrate" class="waves-effect waves-primary"><i class="ti-settings"></i><span> Administrate </span></a></li>';
                $this->getModel()->setData('administrate', $url);
            }
        } else {
            $this->getModel()->setData('file', '_pages_login_1.html');
        }
    }
    
    function login() {
        $user = new User();
        $user->read();
        $res = -2;
        if(!Filter::isEmail($user->getEmail())) {
            $user->setUsername($user->getEmail());
            $user->setEmail(null);
        }
        if((Filter::isEmail($user->getEmail()) || $user->getUsername() !== null) && ($user->getPass() !== '')) {
            $res = $this->getModel()->loginUser($user);
            if($res instanceof User) {
                $this->getSession()->login($res);
                $res = 1;
            } else {
                $this->getSession()->logout();
            }
        }
        header('Location: index.php?op=login&res=' . $res);
        exit();
    }
    
    function logout() {
        $session = $this->getSession();
        $session->logout();
        header('Location: index.php');
        exit();
    }
    
    function recoverPass() {
        $user = new User();
        $user->read();
        $res = -1;
        if(Filter::isEmail($user->getEmail())){
            $res = $this->getModel()->recoverUserPass($user);
        }
        header('Location: index.php?op=recoverPass&res=' . $res);
        exit();
    }
    
    function register() {
        $user = new User();
        $user->read();
        $repeatedPass = Request::read('repeatedPass');
        $res = -1;
        if(Filter::isEmail($user->getEmail()) && $user->getPass() === $repeatedPass && $repeatedPass !== '') {
            $res = $this->getModel()->registerUser($user);
        }
        header('Location: index.php?op=register&res=' . $res);
        exit();
    }
    
    function remove() {
        $user = new User();
        $user->read();
        $res = -1;
        if($this->isAdministrator()) {
            $res = $this->getModel()->removeUser($user);
        }
        header('Location: index.php?action=administrate&op=register&res=' . $res);
        exit();
    }
    
    function showAccount() {
        $this->getModel()->setData('file', '_account.html');
        if($this->isAdministrator()) {
            $url = '<li class="menu-title">Extra</li><li><a href="?action=administrate" class="waves-effect waves-primary"><i class="ti-settings"></i><span> Administrate </span></a></li>';
            $this->getModel()->setData('administrate', $url);
        }
    }
    
    function showAdd() {
        if($this->isAdministrator()) {
            $this->getModel()->setData('file', '_add.html');
            $url = '<li class="menu-title">Extra</li><li><a href="?action=administrate" class="waves-effect waves-primary"><i class="ti-settings"></i><span> Administrate </span></a></li>';
            $this->getModel()->setData('administrate', $url);
        }
    }
    
    function showAvatar() {
        if($this->isLogged()) {
            header('Content-type: image/*');
            $file = '../../avatars/' . $this->getUser()->getId();
            if(!file_exists($file)) {
                $file = '../../avatars/0';
            }
            readfile($file);
            exit();
        } else {
            $this->index();
        }
    }
    
    function showEdit() {
        $user = $this->getModel()->getUser(Request::read('id'));
        if($this->isAdministrator()) {
            $this->getModel()->setData('file', '_edit.html');
            $this->getModel()->setData('id', $user->getId());
            $this->getModel()->setData('email', $user->getEmail());
            $this->getModel()->setData('username', $user->getUsername());
            $this->getModel()->setData('name', $user->getName());
            $this->getModel()->setData('surnames', $user->getSurnames());
            $this->getModel()->setData('role', $user->getRole());
            $this->getModel()->setData('verified', $user->getVerified());
            $url = '<li class="menu-title">Extra</li><li><a href="?action=administrate&id={{id}}" class="waves-effect waves-primary"><i class="ti-settings"></i><span> Administrate </span></a></li>';
            $this->getModel()->setData('administrate', $url);
        }
    }
    
    function showPass() {
        $this->getModel()->setData('file', '_change_pass.html');
    }
    
    function showRecover() {
        $this->getModel()->setData('file', '_recover_pass.html');
    }
    
    function showRegister() {
        $this->getModel()->setData('file', '_register.html');
    }
    
    function showUploadAvatar() {
        $this->getModel()->setData('file', '_upload_avatar.html');
        if($this->isAdministrator()) {
             $url = '<li class="menu-title">Extra</li><li><a href="?action=administrate" class="waves-effect waves-primary"><i class="ti-settings"></i><span> Administrate </span></a></li>';
            $this->getModel()->setData('administrate', $url);
        }
    }
    
    function uploadAvatar() {
        $res = -1;
        if($this->isLogged()) {
            $fileuploader = new FileUpload('avatar', $this->getUser()->getId(), '../../avatars', 2 * 1024 * 1024, FileUpload::OVERWRITE);
            if($fileuploader->upload()) {
                $res = 1;
            }
            header('Location: index.php?op=uploadAvatar&res=' . $res);
            exit();
        } else {
            $this->index();
        }
    }
    
}