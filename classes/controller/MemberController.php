<?php

class MemberController extends Controller { 

    function __construct(Model $model) {
        parent::__construct($model);
    }
    
    function index() {
        $message = Request::read('message');
        $this->getModel()->setData('message', $message);
        if($this->isLogged()) {
            $this->getModel()->setData('file', '_dashboard.html');
            if($this->isAdministrator()) {
                $url = '<li>
                            <a href="index/templateAdministrate" class="waves-effect waves-primary">
                                <i class=" mdi mdi-food-variant"></i>
                                <span>Administrar</span>
                            </a>
                        </li>';
                $this->getModel()->setData('administrate', $url);
            }
        } else {
            $this->getModel()->setData('file', '_login.html');
        }
    }   //TERMINADO
    function login(){
        $member = new Member();
        $member->read();
        $memberDB = $this->getModel()->login($member->getLogin());
        if($memberDB != null){
            if(Util::verifyPass($member->getPassword(), $memberDB->getPassword())){
                $this->getSession()->login($memberDB);    
            }else{
                $message = 'Clave incorrecta';
            }
            
        }else{
            $message = 'El miembro no existe.';
        }
        header('Location: index?message='.$message);
    }   //TERMINADO
    function logout(){
        $session = $this->getSession();
        $session->logout();
        $session->delete('cart');
        $this->index();
    }  //TERMINADO
    function addClient(){
        if($this->getSession()->isLogged()) {
            $message = Request::read('message');
            //echo $message; exit;
            $this->getModel()->setData('message', $message);
            $client = new Client();
            $client->read();
            //echo Util::varDump($client);
            //Comprobar que sea email, numero, NIF, codigo postal
            $r = $this->getModel()->addClient($client);
            if($r > 0){
                $message = 'Añadido correctamente';
            } else if($r == 0){
                $message = 'El cliente ya existia';
            } else{
                $message = 'Error en la operación';
            }
            header('Location: templateClient?message='. $message);
            exit;
        } else {
            $this->index();
        }
    }   //FALTAN COMPROBACIONES
    function removeClient(){
        if($this->getSession()->isLogged()) {
            $id = Request::read('id');
            if($id != '') {
                $r = $this->getModel()->removeClient($id);
                if($r > 0) {
                    $message = 'Borrado correctamente.';
                } else if($r == 0){
                    $message = 'El cliente no existe.';
                } else {
                    $message = 'Error en la operación';
                }
            } else{
                $message = 'Id incorrecto.';
            }
            header('Location: templateClient?message='. $message);
            exit;
        } else {
            $this->index();
        }
    }    //FALTAN COMPROBACIONES
    function editClient(){
        if($this->getSession()->isLogged()) {
            $client = new Client();
            $client->read();
            //echo Util::varDump($client);
            $r = $this->getModel()->editClient($client);
            if($r == 1) {
                $message = 'Editado correctamente.';
            } else {
                $message = 'Error en la operación';
            }
            header('Location: templateClient?message='. $message);
            exit;
        } else {
            $this->index();
        }
    }  //FALTAN COMPROBACIONES
    function addProduct(){
        if($this->getSession()->isLogged()){
            $family = $this->getModel()->getFamily(Request::read('family'));
            if ($family !== null) {
                $params = array(
                'idfamily' => $family->getId(),
                'product' => Request::read('name'),
                'price' => Request::read('price'),
                'description' => Request::read('description')
                );
                $product = new Product();
                $product->setFromAssociative($params);
                $r = $this->getModel()->addProduct($product);
            } else {
                $r = -2;
            }
            $message = '';
            if($r > 0) {
                $message = 'Añadido correctamente';
                $upload = new FileUpload('img' , $r, 'productimages/' , 2 * 1024 * 1024, FileUpload::OVERWRITE);
                $res = $upload->upload();
            } else {
                $message = 'Error en la operación';
            }
            header('Location: templateProduct?message='. $message . '&upload=' . $res);
            exit;
            
        } else {
            $this->index();
        }
    }  //TERMINADO 
    function removeProduct(){
        if($this->getSession()->isLogged()){
            $id = Request::read('id');
            if($id != '') {
                $r = $this->getModel()->removeProduct($id);
                if($r > 0) {
                    $message = 'Borrado correctamente.';
                } else if($r == 0){
                    $message = 'El producto no existe.';
                } else {
                    $message = 'Error en la operación';
                }
            } else{
                $message = 'Id incorrecto.';
            }
            header('Location: templateProduct?message='. $message);
            exit;
        } else{
            $this->index();
        }
    }//TERMINADO 
    function editProduct(){
        if($this->getSession()->isLogged()){
            $product = new Product();
            $product->read();
            if($product !== null) {
                $r = $this->getModel()->editProduct($product);
                if ($r >= 0) {
                    if (isset($_FILES['img'])) {
                        $upload = new FileUpload('img' , $product->getId(), 'productimages/' , 2 * 1024 * 1024, FileUpload::OVERWRITE);
                        $res = $upload->upload();
                        if($res) $r = 1;
                    }
                }
            } else {
                $r = -2;
            }
            $message = '';
            if($r > 0) {    
                $message = 'Editado correctamente';
                
            } else if ($r === 0) {
                $message = 'No editado';
            } else {
                $message = 'Error en la operación';
            }
            header('Location: templateProduct?message='. $message);
            exit;
        } else{
            $this->index();
        }
    } //TERMINADO 
    function addMember(){
        if($this->getSession()->isLogged() &&
                $this->isAdministrator()){
                    $message = Request::read('message');
                    //echo $message; exit;
                    $this->getModel()->setData('message', $message);
                    $repassword = Request::read('repassword');
                    $member = new Member();
                    $member->read();
                    if(($repassword === $member->getPassword()) && ($repassword != '')){
                        $member->setPassword(Util::encrypt($repassword));
                        $r = $this->getModel()->addMember($member);
                        if($r > 0){
                            $message = 'Añadido correctamente';
                        } else if($r == 0){
                            $message = 'El miembro ya existia';
                        } else{
                            $message = 'Error en la operación';
                        }
                    } else{
                        $message = 'Las contraseñas no coinciden. O está vacía.';   
                    }
                    header('Location: templateAdministrate?message='.$message);
                    exit;
        }else{
            $this->index();
        }
    }      //TERMINADO
    function removeMember(){
        if($this->getSession()->isLogged() &&
                $this->isAdministrator()){
                    $id = Request::read('id');
                    if($id == 1){
                        $message = 'No puede eliminar al administrador.';
                        header('Location: templateAdministrate?message='.$message);
                        exit;
                    }
                    if($id != ''){
                        $r = $this->getModel()->removeMember($id);
                        if($r > 0){
                            $message = 'Borrado correctamente.';
                        } else if($r == 0){
                            $message = 'El member no existe. No toque el id, truán';
                        } else{
                            $message = 'Error en la operación';
                        }
                    } else{
                        $message = 'No puede eliminar al administrador o id incorrecto.';
                    }
                    header('Location: templateAdministrate?message='.$message);
                    exit;
        } else{
            $this->index();
        }
    }   //TERMINADO
    function editMember(){
        if($this->getSession()->isLogged() &&
                $this->isAdministrator()){
                    $member = new Member();
                    $member->read();
                    $repassword = Request::read('repassword');
                    if(($member->getPassword() === $repassword) && ($repassword != '')){
                        $member->setPassword(Util::encrypt($repassword));
                        $r = $this->getModel()->editMember($member);
                        if($r == 1){
                            $message = 'Editado correctamente.';
                        } else{
                            $message = 'Error en la operación';
                        }
                    }else{
                        $message = 'Las contraseñas no coinciden. O está vacía';
                        }
                    header('Location: templateAdministrate?message='.$message);
                    exit;
        } else{
            $this->index();
        }
    }     //TERMINADO
    function templateAdministrate(){
        /*<tr>
            <th>1</th>
            <td>NombreUsuario</td>
            <td>Pass</td>
            <td><a href="?action=editMember"><i class="ion-edit"></i></a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="?action=removeMember"><i class="ion-android-close"></i></a></td>
        </tr>*/
        if($this->getSession()->isLogged() && $this->isAdministrator()){
            $message = Request::read('message');
            $this->getModel()->setData('message', $message);
            //PINTAMOS LA PLANTILLA
            $this->getModel()->setData('file', '_administrate.html');
            $url = '<li>
                        <a href="index/templateAdministrate" class="waves-effect waves-primary">
                            <i class="mdi mdi-settings"></i>
                            <span>Administrar</span>
                        </a>
                    </li>';
            $this->getModel()->setData('administrate', $url);
            
            //paginacion
            $rows = $this->getModel()->getMemberRows();
            $page = Request::read('page');
            if($page === null) {
                $page = 1;
            }
            $pagination = new Pagination($rows, $page, 3);
            $rpp = $pagination->getRpp();
            $offset = $pagination->getOffset();
            $next = $pagination->next();
            $prev = $pagination->previous();
            
            //fin paginacion
            
            
            //PINTAMOS LINEAS
            $line = ' <tr>
                        <th>{{id}}</th>
                        <td class="text-center">{{login}}</td>
                        <td class="text-right mr-10"><a href="index/templateEditMember?id={{id}}"><i class="ion-edit"></i></a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="index/removeMember?id={{id}}"><i class="ion-android-close"></i></a></td>
                    </tr>';
            //paginacion
            $members = $this->getModel()->getPagedMember($offset, $rpp);
            
            
            
            /*$num = $page*$rpp;
            if($page == ceil(count($totalClients)/$rpp)){
                $clientsLastPage = count($totalClients) - ($rpp * ($page - 1));
                $num = ($page*$rpp) - ($rpp - $clientsLastPage);
            }*/
            
            // $this->getModel()->setData('first', ($page - 1) * $rpp + 1);
            // $this->getModel()->setData('num', $page * $rpp);
            $this->getModel()->setData('total', $rows);

            foreach($members as $index => $member) {
                $res = Util::renderText($line, $member->getAttributesValues());
                $all .= $res;
            }
            //paginacion
            $btNext = '<li class="page-item">
                            <a class="page-link" href="index/templateAdministrate?page=' . $next . '" aria-label="Next">
                                <span aria-hidden="true">&raquo;</span>
                                <span class="sr-only">Next</span>
                            </a>
                        </li>';
                       
            $btPrev = '<li class="page-item">
                            <a class="page-link" href="index/templateAdministrate?page=' . $prev . '" aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                                <span class="sr-only">Previous</span>
                            </a>
                        </li>';
                                    
            $this->getModel()->setData('next', $btNext);
            $this->getModel()->setData('prev', $btPrev);
            
            $range = $pagination->getRange();
            //echo Util::varDump($range); exit;
            $pageinfo = '';
            foreach($range as $pagenum){
                    $btPage = '<li class="page-item"><a class="page-link" href="index/templateAdministrate?page=' . $pagenum . '">' . $pagenum . '</a></li>';
                    
                    if($pagenum == $page){
                       $btPage = '<li class="page-item active"><a class="page-link" href="index/templateAdministrate?page=' . $pagenum . '">' . $pagenum . '</a></li>'; 
                    }
                    
                    $pageinfo .= $btPage;
            }
            $this->getModel()->setData('pagination', $pageinfo);
            //fin paginacion
            
            $this->getModel()->setData('memberLines', $all);
        } else{
            $this->index();
        }
    } //TERMINADO
    function templateEditMember(){
         if($this->getSession()->isLogged()){
            if($this->isAdministrator()) {
                    $id = Request::read('id');
                    if($id == 1){
                        $message = 'No puede editar al administrador.';
                        header('Location: templateAdministrate?message='.$message);
                        exit;
                    }
                $url = '<li>
                            <a href="index/templateAdministrate" class="waves-effect waves-primary">
                                <i class="mdi mdi-settings"></i>
                                <span>Administrar</span>
                            </a>
                        </li>';
                $this->getModel()->setData('administrate', $url);
                $this->getModel()->setData('file', '_edit_member.html');
                $member = $this->getModel()->getMember($id);
                if($member != null){
                    $this->getModel()->setData('id', $member->getId());
                    $this->getModel()->setData('login', $member->getLogin());
                    $this->getModel()->setData('password', $member->getPassword());
                    
                    
                }else{
                    $message = 'Error en la consulta';
                    header('Location: templateAdministrate?message='.$message);
                    exit;
                }
            }
        } else{
            $this->index();
        }
    }  //TERMINADO
    function templateDashboard(){
         if($this->getSession()->isLogged()){
            $this->getModel()->setData('file', '_dashboard.html');
            if($this->isAdministrator()) {
                $url = '<li>
                            <a href="index/templateAdministrate" class="waves-effect waves-primary">
                                <i class="mdi mdi-settings"></i>
                                <span>Administrar</span>
                            </a>
                        </li>';
                $this->getModel()->setData('administrate', $url);
            }
        } else{
            $this->index();
        }
    }
    function templateEditClient(){
         if($this->getSession()->isLogged()) {
            $id = Request::read('id');
            $this->getModel()->setData('file', '_edit_client.html');
            $client = $this->getModel()->getClient($id);
            if($client != null) {
                $this->getModel()->setData('id', $client->getId());
                $this->getModel()->setData('name', $client->getName());
                $this->getModel()->setData('surname', $client->getSurname());
                $this->getModel()->setData('tin', $client->getTin());
                $this->getModel()->setData('address', $client->getAddress());
                $this->getModel()->setData('location', $client->getLocation());
                $this->getModel()->setData('postalcode', $client->getPostalcode());
                $this->getModel()->setData('province', $client->getProvince());
                $this->getModel()->setData('email', $client->getEmail());
            } else {
                $message = 'Error en la consulta';
                header('Location: templateClient?message='.$message);
                exit;
            }
            if($this->isAdministrator()) {
                $url = '<li>
                            <a href="index/templateAdministrate" class="waves-effect waves-primary">
                                <i class="mdi mdi-settings"></i>
                                <span>Administrar</span>
                            </a>
                        </li>';
                $this->getModel()->setData('administrate', $url);
            }
        } else {
            $this->index();
        }
    }  //TERMINADO
    function templateClient(){
        if($this->getSession()->isLogged()){
            $message = Request::read('message');
            $this->getModel()->setData('message', $message);
            $this->getModel()->setData('file', '_client.html');
            
            //paginacion
            $rows = $this->getModel()->getClientRows();
            $page = Request::read('page');
            if($page === null) {
                $page = 1;
            }
            $pagination = new Pagination($rows, $page, 3);
            $rpp = $pagination->getRpp();
            $offset = $pagination->getOffset();
            $next = $pagination->next();
            $prev = $pagination->previous();
            
            //fin paginacion
            
            $line = '<tr>
                        <th>{{id}}</th>
                        <td>{{name}}</td>
                        <td>{{surname}}</td>
                        <td>{{email}}</td>
                        <td>{{tin}}</td>
                        <td>{{address}}</td>
                        <td>{{location}}</td>
                        <td>{{postalcode}}</td>
                        <td>{{province}}</td>
                        <td><a href="index/templateEditClient?id={{id}}"><i class="ion-edit"></i></a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="index/removeClient?id={{id}}"><i class="ion-android-close"></i></a></td>
                    </tr>';
            //$clients = $this->getModel()->getAllClient();
            $clients = $this->getModel()->getPagedClient($offset, $rpp);

            $this->getModel()->setData('first', ($page - 1) * $rpp + 1);
    
            /*$num = $page*$rpp;
            if($page == ceil(count($totalClients)/$rpp)){
                $clientsLastPage = count($totalClients) - ($rpp * ($page - 1));
                $num = ($page*$rpp) - ($rpp - $clientsLastPage);
            }*/
            
            $this->getModel()->setData('num', $num);
            $this->getModel()->setData('total', $rows);
            
            foreach($clients as $index => $client) {
                $res = Util::renderText($line, $client->getAttributesValues());
                $all .= $res;
            }
            
            //paginacion
            $btNext = '<li class="page-item">
                            <a class="page-link" href="index/templateClient?page=' . $next . '" aria-label="Next">
                                <span aria-hidden="true">&raquo;</span>
                                <span class="sr-only">Next</span>
                            </a>
                        </li>';
                       
            $btPrev = '<li class="page-item">
                            <a class="page-link" href="index/templateClient?page=' . $prev . '" aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                                <span class="sr-only">Previous</span>
                            </a>
                        </li>';
                                    
            $this->getModel()->setData('next', $btNext);
            $this->getModel()->setData('prev', $btPrev);
            
            $range = $pagination->getRange();
            $pageinfo = '';
            foreach($range as $pagenum){
                    $btPage = '<li class="page-item"><a class="page-link" href="index/templateClient?page=' . $pagenum . '">' . $pagenum . '</a></li>';
                    
                    if($pagenum == $page){
                       $btPage = '<li class="page-item active"><a class="page-link" href="index/templateClient?page=' . $pagenum . '">' . $pagenum . '</a></li>'; 
                    }
                    
                    $pageinfo .= $btPage;
            }
            $this->getModel()->setData('pagination', $pageinfo);
            //fin paginacion
            
            $this->getModel()->setData('clientLines', $all);
            if($this->isAdministrator()) {
                $url = '<li>
                            <a href="index/templateAdministrate" class="waves-effect waves-primary">
                                <i class="mdi mdi-settings"></i>
                                <span>Administrar</span>
                            </a>
                        </li>';
                $this->getModel()->setData('administrate', $url);
            }
        } else{
            $this->index();
        }
    }  //TERMINADO
    function templateProduct(){
         if($this->getSession()->isLogged()){
            $message = Request::read('message');
            $this->getModel()->setData('message', $message);
            $this->getModel()->setData('file', '_product.html');
            
            //paginacion
            $rows = $this->getModel()->getProductRows();
            $page = Request::read('page');
            if($page === null) {
                $page = 1;
            }
            $pagination = new Pagination($rows, $page, 2);
            $rpp = $pagination->getRpp();
            $offset = $pagination->getOffset();
            $next = $pagination->next();
            $prev = $pagination->previous();
            
            //fin paginacion
            
            $line = '<tr>
                        <th>{{id}}</th>
                        <td>{{product}}</td>
                        <td>{{price}}</td>                      
                        <td>{{description}}</td>
                        <td>{{idfamily}}</td>
                        <td><a href="index/templateEditProduct?id={{id}}"><i class="ion-edit"></i></a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="index/removeProduct?id={{id}}"><i class="ion-android-close"></i></a></td>
                    </tr>';
            $products = $this->getModel()->getPagedProduct($offset, $rpp);

            /*$num = $page*$rpp;
            if($page == ceil(count($totalClients)/$rpp)){
                $clientsLastPage = count($totalClients) - ($rpp * ($page - 1));
                $num = ($page*$rpp) - ($rpp - $clientsLastPage);
            }*/
            
            $this->getModel()->setData('first', ($page - 1) * $rpp + 1);
            $this->getModel()->setData('num', $page * $rpp);
            $this->getModel()->setData('total', $rows);
            
            foreach($products as $index => $product) {
                //traduce id de familia a nombre de familia
                $family = $this->getModel()->getFamily($product->getIdfamily());
                $product->setIdfamily($family->getFamily());
                $res = Util::renderText($line, $product->getAttributesValues());
                $all .= $res;
            }
            
            //paginacion
            $btNext = '<li class="page-item">
                            <a class="page-link" href="index/templateProduct?page=' . $next . '" aria-label="Next">
                                <span aria-hidden="true">&raquo;</span>
                                <span class="sr-only">Next</span>
                            </a>
                        </li>';
                       
            $btPrev = '<li class="page-item">
                            <a class="page-link" href="index/templateProduct?page=' . $prev . '" aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                                <span class="sr-only">Previous</span>
                            </a>
                        </li>';
                                    
            $this->getModel()->setData('next', $btNext);
            $this->getModel()->setData('prev', $btPrev);
            
            $range = $pagination->getRange();
            $pageinfo = '';
            foreach($range as $pagenum){
                    $btPage = '<li class="page-item"><a class="page-link" href="index/templateProduct?page=' . $pagenum . '">' . $pagenum . '</a></li>';
                    
                    if($pagenum == $page){
                       $btPage = '<li class="page-item active"><a class="page-link" href="index/templateProduct?page=' . $pagenum . '">' . $pagenum . '</a></li>'; 
                    }
                    
                    $pageinfo .= $btPage;
            }
            $this->getModel()->setData('pagination', $pageinfo);
            //fin paginacion
            
            $this->getModel()->setData('productLines', $all);
            
            //render families select
            $optionSelect = '<option value="{{id}}">{{family}}</option>';
            $families = $this->getModel()->getFamilies();
            $optionRendered = '';
            foreach ($families as $family) {
                $optionRendered .= Util::renderText($optionSelect, $family->getAttributesValues());
            }
            $this->getModel()->setData('optionfamily', $optionRendered);
            if($this->isAdministrator()) {
                $url = '<li>
                            <a href="index/templateAdministrate" class="waves-effect waves-primary">
                                <i class="mdi mdi-settings"></i>
                                <span>Administrar</span>
                            </a>
                        </li>';
                $this->getModel()->setData('administrate', $url);
            }
        } else{
            $this->index();
        }
    } //TERMINADO 
    function templateEditProduct() {
        if($this->getSession()->isLogged()) {
            $id = Request::read('id');
            $this->getModel()->setData('file', '_edit_product.html');
            $product = $this->getModel()->getProduct($id);
            //render families select with selected
            $optionSelect = '<option value="{{id}}" {{selected}} >{{family}}</option>';
            $families = $this->getModel()->getFamilies();
            $optionRendered = '';
            foreach ($families as $family) {
                $array = $family->getAttributesValues();
                if ($product->getIdfamily() === $family->getId()) {
                    $array['selected'] = 'selected';
                }
                $optionRendered .= Util::renderText($optionSelect, $array);
            }
            $this->getModel()->setData('optionfamily', $optionRendered);
            if($product !== null) {
                $this->getModel()->setDataFromAssociative($product->getAttributesValues());
            } else {
                $message = 'Error en la consulta';
                header('Location: index/templateProduct?message='. $message);
                exit;
            }
            if($this->isAdministrator()) {
                $url = '<li>
                            <a href="index/templateAdministrate" class="waves-effect waves-primary">
                                <i class="mdi mdi-settings"></i>
                                <span>Administrar</span>
                            </a>
                        </li>';
                $this->getModel()->setData('administrate', $url);
            }
        } else {
            $this->index();
        }
    } //TERMINADO 
    function templateTpv(){
        if($this->getSession()->isLogged()){
            /*$session = new Session();
            $cart = $session->get('cart');
            
            $product1 = new Product(1, 1, 'pan', 1.2, 'muy bueno');
            $product2 = new Product(2, 1, 'pan', 1.4, 'muy bueno');
            $products = array ($product1, $product2);
            foreach($products as $product) {
                $cart->add(new Line($product->getId(), $product, 3));
            }
            $cart = $session->set('cart');
            if($cart !== null) {
                foreach($cart as $line) {
                    $product = $line->getItem();
                    $subtotal = $line->getAmount() * $product->getPrice();
                    $ticketLine = '<tr>
                                <td>' . $line->getAmount() .
                                '<td>' . $product->getId() .
                                '<td>' . $product->getDescription() .
                                '<td>' . $product->getPrice() .
                                '<td>' . $subtotal .
                                '<td>' .
                                
                    
                    $all = ;
                }
                $this->getModel()->setData('lines', $all);
            }*/
            $this->getModel()->setData('file', '_tpv.html');
            if($this->isAdministrator()) {
                $url = '<li>
                            <a href="index/templateAdministrate" class="waves-effect waves-primary">
                                <i class="mdi mdi-settings"></i>
                                <span>Administrar</span>
                            </a>
                        </li>';
                $this->getModel()->setData('administrate', $url);
            }
        } else{
            $this->index();
        }
    }
    
}