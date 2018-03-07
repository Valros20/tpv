<?php

class MemberModel extends Model{
    
    /* ---------------------MIEMBROS--------------------- */
    
    function login($login){
        $manager = new MemberManager($this->getDataBase());
        $member = $manager->getMemberFromLogin($login);
        return $member;
        
    }
    function addMember($member){
        $manager = new MemberManager($this->getDataBase());
        return $manager->addMember($member);
    }
    function editMember($member){
        $manager = new MemberManager($this->getDataBase());
        return $manager->editMember($member);
    }
    function removeMember($id){
        $manager = new MemberManager($this->getDataBase());
        return $manager->removeMember($id);
    }
    function getMember($id){
        $manager = new MemberManager($this->getDataBase());
        return $manager->getMember($id);
    }
    function getAllMember(){
        $manager = new MemberManager($this->getDataBase());
        return $manager->getAllMember();
    }
    function getMemberRows(){
        $manager = new MemberManager($this->getDataBase());
        return $manager->countMember();
    }
    function getPagedMember($offset, $rpp){
        $manager = new MemberManager($this->getDataBase());
        return $manager->getPagedMember($offset, $rpp);
    }
    /*function uploadAvatarMember() {
        if($this->isLogged()) {
            //$input, $name = null, $target = '.', $size = 0, 
            //$policy = FileUpload::RENOMBRAR
            $upload = new FileUpload('avatar', $this->getUser()->getId(), '../../images', 2 * 1024 * 1024, FileUpload::SOBREESCRIBIR);
            $r = $upload->upload();
            if($r){
                $mensaje = 'Subida correctamente.';
            } else{
                $mensaje = 'Error en la subida.';
            }
            header('Location: index.php?action=administratemensaje='.$mensaje);
        } else {
            $this->index();
        }
    }*/
    
    
    /* ---------------------CLIENTE--------------------- */
    
    function addClient($client){
        $manager = new ClientManager($this->getDataBase());
        return $manager->addClient($client);
    }
    function editClient($client){
        $manager = new ClientManager($this->getDataBase());
        return $manager->editClient($client);
    }
    function getAllClient(){
        $manager = new ClientManager($this->getDataBase());
        return $manager->getAll();
    }
    function getClient($id){
        $manager = new ClientManager($this->getDataBase());
        return $manager->getClient($id);
    }
    function getClientRows(){
        $manager = new ClientManager($this->getDataBase());
        return $manager->countClient();
    }
    function getPagedClient($offset, $rpp){
        $manager = new ClientManager($this->getDataBase());
        return $manager->getPagedClient($offset, $rpp);
    }
    function removeClient($id){
        $manager = new ClientManager($this->getDataBase());
        return $manager->removeClient($id);
    }
    function countClient(){
        $manager = new ClientManager($this->getDataBase());
        return $manager->countClient();
    }
    function getAllClientFromName($name){
        $manager = new ClientManager($this->getDataBase());
        $clients = $manager->getAllClientFromName($name);
        $array = array();
        foreach($clients as $client) {
            $array[] = $client->getAttributesValues();
        }
        return $array;
    }
    
    /* ---------------------PRODUCTOS--------------------- */
    
    function addProduct($product) {
        $manager = new ProductManager($this->getDataBase());
        return $manager->addProduct($product);
    }
    function getProductRows() {
        $manager = new ProductManager($this->getDataBase());
        return $manager->countProduct();
    }
    function getPagedProduct($offset, $rpp) {
        $manager = new ProductManager($this->getDataBase());
        return $manager->getPagedProduct($offset, $rpp);
    }
    function getAllProduct() {
        $manager = new ProductManager($this->getDataBase());
        return $manager->getAll();
    }
    function getProduct($id){
        $manager = new ProductManager($this->getDataBase());
        return $manager->getProduct($id);
    }
    function editProduct($product) {
        $manager = new ProductManager($this->getDataBase());
        return $manager->editProduct($product);
    }
    function removeProduct($id) {
        $manager = new ProductManager($this->getDataBase());
        return $manager->removeProduct($id);
    }
    function getProductsFromFamily($idfamily){
        $manager = new ProductManager($this->getDataBase());
        return $manager->getProductsFromFamily($idfamily);
    }
    
    // ajax
    function getProductsFromFamilyAjax($idfamily){
        $manager = new ProductManager($this->getDataBase());
        $products = $manager->getProductsFromFamily($idfamily);
        $array = array();
        foreach ($products as $product) {
            $array[] = $product->getAttributesValues();
        }
        return $array;
    }
    
    function getProductImages($products) {
        $array = array();
        foreach ($products as $product) {
            $file = 'productimages/' . $product['id'];
            $array[$product['id']] = $file;
        }
        return $array;
    }
    
    function getProductImagesBase64($products) {
        $array = array();
        foreach ($products as $product) {
            $file = '../images/products/' . $product['id'] . '.jpg';
            $type = pathinfo($file, PATHINFO_EXTENSION);
            $data = file_get_contents($file);
            $array[$product['id']] = 'data:image/' . $type . ';base64,' . base64_encode($data);
        }
        return $array;
    }
    
    /* ---------------------FAMILIA--------------------- */
    
    function getFamily($id) {
        $manager = new FamilyManager($this->getDataBase());
        return $manager->getFamily($id);
    }
    function getFamilyFromName($name) {
        $manager = new FamilyManager($this->getDataBase());
        return $manager->getFromName($name);
    }
    function getFamilies() {
        $manager = new FamilyManager($this->getDataBase());
        return $manager->getAll();
    }
    //ajax
    function getFamiliesAjax() {
        $manager = new FamilyManager($this->getDataBase());
        $families = $manager->getAll();
        $array = array();
        foreach ($families as $family) {
            $array[] = $family->getAttributesValues();
        }
        return $array;
    }
    
     /* ---------------------TIQUET--------------------- */
     
     function addTicket($ticket){
        $manager = new TicketManager($this->getDataBase());
        return $manager->addTicket($ticket);
     }
     function editTicket($ticket){
        $manager = new TicketManager($this->getDataBase());
        return $manager->editTicket($ticket);
     }
     function getAllTicket(){
         $manager = new TicketManager($this->getDataBase());
        return $manager->getAllTicket();
     }
    
    function getTicket($id) {
        $manager = new TicketManager($this->getDataBase());
        return $manager->getTicket($id);
    }
     
     /* ---------------------TIQUET DETAILS--------------------- */
     
     function addTicketDetail($ticketDetail){
        $manager = new TicketDetailManager($this->getDataBase());
        return $manager->addTicketDetail($ticketDetail);
     }
     function editTicketDetail($ticketDetail){
        $manager = new TicketDetailManager($this->getDataBase());
        return $manager->editTicketDetail($ticketDetail);
     }
     function getAllTicketDetailFromTicket($idTicket){
        $manager = new TicketDetailManager($this->getDataBase());
        return $manager->getAllTicketDetail();
     }
     function removeAllTicketDetailFromTicket($idTicket){
        $total = 0;
        $manager = new TicketDetailManager($this->getDataBase());
        $ticketDetails = $manager->getAllTicketDetailFromTicket($idTicket);
        foreach($ticketDetails as $ticketDetail) {
            $res = $manager->removeTicketDetail($ticketDetail->getId());
            $total += $res;
        }
        return $total;
     }
}