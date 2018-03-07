<?php

class UserModel extends Model {
    
    function activateUser($id, $sha1IdEmail) {
        $manager = new UserManager($this->getDataBase());
        $dbUser = $manager->getUser($id);
        $res = -1;
        if($dbUser !== null) {
            $sha1 = sha1($dbUser->getId() . $dbUser->getEmail());
            if($sha1IdEmail === $sha1) {
                $dbUser->setVerified(1);
                $res = $manager->setVerified($dbUser);
            }
        }
        return $res;
    }
    
    function addUser($user) {
        $manager = new UserManager($this->getDataBase());
        $res = $manager->addUserAdmin($user);
        return $res;
    }
    
    function changeUserPass(User $user, $oldpass) {
        $manager = new UserManager($this->getDataBase());
        $dbUser = $manager->getUser($user->getId());
        $res = -1;
        if($dbUser !== null && Util::verifyPass($oldpass, $dbUser->getPass())) {
            $dbUser->setPass($user->getPass());
            $res = $manager->editPass($dbUser);
        }
        return $res;
    }
    
    function editUser(User $user) {
        $manager = new UserManager($this->getDataBase());
        $res = -1;
        if($dbUser !== null) {
            if($dbUser->getRole() === 'administrator' || $dbUser->getRole() === 'advanced') {
                $res = $manager->editUserPlus($user);
            } else {
                if($dbUser->getEmail !== $user->getEmail()) {
                    $link = '<a href="https://dwes-valros20.c9users.io/usermanagement/index.php?route=index&action=activate&id=' . $user->getId() . '&data=' . sha1($user->getId().$user->getEmail()). '">activate</a>';
                    $res2 = Util::sendEmail ($user->getEmail(), Constants::APPNAME, 'User activation link: ' . $link);
                }
                $res = $manager->editUser($user);
            }
        }
        return $res;
    }
    
    function editUserAdmin(User $user) {
        $manager = new UserManager($this->getDataBase());
        $res = $manager->editUserAdmin($user);
        return $res;
    }
    
    function hasEmailChanged(User $user) {
        $res = false;
        $manager = new UserManager($this->getDataBase());
        $dbUser = $manager->getUser($user->getId());
        if($dbUser->getEmail() !== $user->getEmail()) {
            $res = true;
        }
        return $res;
    }
    
    function loginUser(User $user) {
        $res = -1;
        $manager = new UserManager($this->getDataBase());
        if($user->getEmail() !== null) {
            $dbUser = $manager->getUserFromEmail($user->getEmail());
        } else {
            $dbUser = $manager->getUserFromUsername($user->getUsername());
        }
            if($dbUser === null) {
                $res = -1;
            } else {
                $res = Util::verifyPass($user->getPass(), $dbUser->getPass());
                if($res && $dbUser->getVerified() === '1') {
                    $res = $dbUser;
                } else {
                    $res = 0;
                }
            }
        return $res;
    }
    
    function recoverUserPass(User $user) {
        $res = -1;
        $manager = new UserManager($this->getDataBase());
        $dbUser = $manager->getUserFromEmail($user->getEmail());
        if(dbUser !== null) {
            $link = '<a href="https://dwes-valros20.c9users.io/usermanagement/index.php?route=index&action=showPass&id=' . $dbUser->getId() . '&data=' . sha1($dbUser->getId().$dbUser->getEmail()). '">Recover Pass</a>';
            $res = Util::sendEmail ($dbUser->getEmail(), Constants::APPNAME, 'Recover password link: ' . $link);
        }
        return $res;
    }
    
    function registerUser(User $user) {
        $manager = new UserManager($this->getDataBase());
        $res = $manager->addUser($user);
        if($res > 0) {
            $link = '<a href="https://dwes-valros20.c9users.io/usermanagement/index.php?route=index&action=activate&id=' . $res . '&data=' . sha1($res.$user->getEmail()). '">activate</a>';
            $res2 = Util::sendEmail ($user->getEmail(), Constants::APPNAME, 'User activation link: ' . $link);
        }
        return $res;
    }
    
    function removeUser(User $user) {
        $res = -1;
        $manager = new UserManager($this->getDataBase());
        if($user->getRole() === 'administrator' && $manager->countAdmins() <= 1) {
            $res = $manager->removeUser($user->getId());
        }
        return $res;
    }

    function getUser($id) {
        $manager = new UserManager($this->getDataBase());
        return $manager->getUser($id);
    }
    
    function getUsers() {
        $manager = new UserManager($this->getDataBase());
        return $manager->getAll();
    }
    
    function getUsersJson() {
        $usuarios = $this->getUsers();
    }
    
    function countClient(){
        $manager = new ManageUsuario($this->getDataBase());
        return $manager->countClient();
    }
    
    function countProduct(){
        $manager = new ManageUsuario($this->getDataBase());
        return $manager->countProduct();
    }
    
    function countMember(){
        $manager = new ManageUsuario($this->getDataBase());
        return $manager->countMember();
    }
    
}