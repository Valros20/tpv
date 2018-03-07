<?php

class AjaxController extends Controller {
    
    function addTicket(){
        
    }
    function getAllTicket(){
        
    }
    
    function addTicketDetail($idTicket){
        
    }
    function getAllTicketDetailFromTicket($idTicket){
        
    }
    
    /*CLIENTS*/
    function getAllClientFromName(){
        $name = Request::read('name');
        $clients = $this->getModel()->getAllClientFromName($name);
        $this->getModel()->setData('clients', $clients);
    }
    
     function getClient(){
        $id = Request::read('id');
        $client = $this->getModel()->getClient($id);
        if($client !== null){
             $this->getModel()->setData('client', $client->getAttributesValues());
        }
    }
    
    /*PRODUCTO*/
    function getAllProductFromFamily(){
        $family = Request::read('family');
        if($family === null) {
            $family = '1';
        }
        $products = $this->getModel()->getProductsFromFamilyAjax($family);
        $this->getModel()->setData('products', $products);
        $families = $this->getModel()->getFamiliesAjax();
        $this->getModel()->setData('families', $families);
        //guarda las rutas de las imagenes
        $images = $this->getModel()->getProductImages($products);
        $this->getModel()->setData('productImages', $images); 
        
        //guarda las imagenes en base64
        //$imagesBase = $this->getModel()->getProductImagesBase64($products);
        //$this->getModel()->setData('imagesURI', $imagesBase); 
        
    }
    
    function loadImg() {
        header('Content-type: image/*');
        $file = 'productimages/' . Request::read('img');
        readfile($file);
        exit();
    }
    
    function idfamilyFromName($name) {
        $r = 0;
        switch ($name) {
            case 'Pan':
                $r = 1;
            break;
            case 'Bolleria':
                $r = 2;
            break;
            case 'Croissant':
                $r = 3;
            break;
            case 'Navidad':
                $r = 4;
            break;
            case 'Otros':
                $r = 5;
            break;
        }
        return $r;
    }
    
    /*CARRITO*/
    
    function cartToJson($cart){
        $cartArray =  array();
        foreach($cart as $line){
                $lineToJson = new Line($line->getId(), $line->getItem(), $line->getAmount());
                $product = $line->getItem();
                $productToJson = $product->getAttributesValues();
                $lineToJson->setItem($productToJson);
                $cartArray[] = $lineToJson->getAttributesValues();
            }
        return $cartArray;
    }
    
    function getCart() {
        $session = new Session(); 
        $cart = $session->get('cart');
        if($cart != null) {
            
            $this->getModel()->setData('cart', $this->cartToJson($cart->getCart()));
            
        } else {
            $cart = new Cart();
            $session->set('cart', $cart);
        }
    }
    
    function addLine(){
        $idProduct = Request::read('idFromTicket');
        $idProductImg = Request::read('idProduct');
        //$this->getModel()->setData('cart', $idProductImg);
        if($idProduct != '') {
             $product = $this->getModel()->getProduct($idProduct);
         } else {
            $product = $this->getModel()->getProduct($idProductImg);
            //$this->getModel()->setData('cart', $product->getAttributesValues());
         }
        if($product !== null){
             $session = new Session();
             $cart = $session->get('cart');
             $line = new Line($product->getId(), $product);
             $cart->addLine($line);
             $session->set('cart', $cart);
             $this->getModel()->setData('cart', $this->cartToJson($cart->getCart()));
        }
    }
    
    function saveCart() {
        $idClient = Request::read('idClient');
        $idTicket = Request::read('idTicket');
        $session = new Session();
        $idMember = $session->getUser()->getId();
        $cart = $session->get('cart')->getCart();
        if ($idMember !== null && $idClient !== '') {
            $ticket = new Ticket(null, null, $idMember, $idClient);
        } else {
            $ticket = new Ticket(null, null, $idMember, null);
        }
        if (/*$this->getModel()->getData('idTicket') === null*/$idTicket === null) {
            $idTicket = $this->getModel()->addTicket($ticket);
        } else {
            $ticket->setId($idTicket);
            $this->getModel()->editTicket($ticket);
            //$this->getModel()->setData('idTicket', $idTicket);
        }
        $this->getModel()->removeAllTicketDetailFromTicket($idTicket);
        foreach($cart as $line) {
            $idProduct = $line->getItem()->getId();
            $amount = $line->getAmount();
            $price = $line->getItem()->getPrice();
            $ticketDetail = new Ticketdetail(null, $idTicket, $idProduct, $amount, $price);
            $this->getModel()->addTicketDetail($ticketDetail);
        }
        $this->getModel()->setData('idTicket', $idTicket);
    } //Falta encontrar la forma de guardar el idTicket en el modelo y no en el HTML en un hidden + modularizar
    
    function substractLine(){
        $idProduct = Request::read('id');
        $product = $this->getModel()->getProduct($idProduct);
        if($product !== null){
             $session = new Session();
             $cart = $session->get('cart');
             $cart->substractLine(new Line($product->getId(), $product));
             $session->set('cart', $cart);
             $this->getModel()->setData('cart', $this->cartToJson($cart->getCart()));
        }
    }
    
    function substract(){
        $idProduct = Request::read('id');
        $product = $this->getModel()->getProduct($idProduct);
        if($product !== null){
             $session = new Session();
             $cart = $session->get('cart');
             $cart->remove($product->getId());
             $session->set('cart', $cart);
             $this->getModel()->setData('cart', $this->cartToJson($cart->getCart()));
        }
    }
    
    function closeCart(){
        $session = new Session();
        $session->delete('cart');
        $cart = new Cart();
        $session->set('cart', $cart);
    }
    
}