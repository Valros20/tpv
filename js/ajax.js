(function () {
/*global $*/
    
    /*CARRITO*/
    $('#save').on('click', saveCart);
    function addEventsForTicket(){
        $('.plus').on('click', addLineFromPlus);
        $('.less').on('click', substractLine);
        $('.rmv').on('click', substract);
        $('#btAjax').on('click', closeCart);
    }
    (function (){
        $.ajax(
          {
            url: 'ajax/getCart',
            type: 'post',
            dataType: 'json',
            data: {
                // route: 'ajax',
                // action: 'getCart',
            }
          } 
        ).done(
            function(json){
                var cart = json.cart;
                for(index in cart){
                    createNodeLine(cart[index]);
                }
                addEventsForTicket();
                sumaPrecios();
                if($('#idTicket').val() !== '') {
                    $('#save').text('Editar Ticket');
                }
                $('.counter').counterUp({
                    delay: 100,
                    time: 1200
                });
            }
        ).fail(
            function(){
                alert('error al cargar el carrito.');
            }
        ).always(
            function() {
                
            }
        );
     })();   //obtiene el carrito al recargar, autoejecutable.
    function createNodeLine(line){
        var subtotal = line.amount * line.item.price;
        $node = '<tr>' +
                        '<td class="cantidad">'+line.amount+'</td>' +
                        '<td id="idProduct">'+line.item.id+'</td>'+
                        '<td>'+line.item.description+'</td>'+
                        '<td class="precio">'+line.item.price+'</td>'+
                        '<td class="subtotal">'+subtotal.toFixed(2) +'</td>'+
                        '<td><i class="mdi mdi-plus-box plus"></i><i class="mdi mdi-minus-box less"></i><i class="mdi mdi-bitbucket rmv"></i></td>'+
                    '</tr>';
        $('#ticket').append($node);
    }
    function deleteAllLines(){
        $('#ticket').children('tr').remove();
    }
    function addLineFromPlus(id){
        if(typeof id == 'object'){
            var idFromTicket = $(this).closest('tr').children('#idProduct').text();
            id = '';
        }
        $.ajax(
            {
                url: 'ajax/addLine',
                type: 'post',
                dataType: 'json',
                data: {
                    //route: 'ajax',
                    //action: 'addLine',
                    idFromTicket: idFromTicket,
                    idProduct: id
                    }
                }
            ).done(
                function(json){
                var cart = json.cart;
                deleteAllLines();
                for(index in cart){
                    createNodeLine(cart[index]);
                }
                addEventsForTicket();
                sumaPrecios();
            }
            ).fail(
                function(){
                    alert('Ups error al actualizar el carrito');
                }
            ).always(
                function() {
                    
                }
            );
    }
    function substractLine(){
        $.ajax(
            {
                url: 'ajax/substractLine',
                type: 'post',
                dataType: 'json',
                data: {
                    //route: 'ajax',
                    //action: 'substractLine',
                    id: $(this).closest('tr').children('#idProduct').text()
                }
            }
            ).done(
                function(json){
                var cart = json.cart;
                deleteAllLines();
                for(index in cart){
                    createNodeLine(cart[index]);
                }
                addEventsForTicket();
                sumaPrecios();
            }
            ).fail(
                function(){
                    alert('Ups error al actualizar el carrito');    
                }
            ).always(
                function() {
                    
                }
            );
    }
    function substract(){
        $.ajax(
            {
                url: 'ajax/substract',
                type: 'post',
                dataType: 'json',
                data: {
                    //route: 'ajax',
                    //action: 'substract',
                    id: $(this).closest('tr').children('#idProduct').text()
                }
            }
            ).done(
                function(json){
                var cart = json.cart;
                deleteAllLines();
                for(index in cart){
                    createNodeLine(cart[index]);
                }
                addEventsForTicket();
                sumaPrecios();
            }
            ).fail(
                function(){
                    alert('Ups error al actualizar el carrito');    
                }
            ).always(
                function() {
                    
                }
            );
    }
    function closeCart(){
        $.ajax(
               {
                    url: 'ajax/closeCart',
                    type: 'post',
                    dataType: 'json',
                    data: {
                        //route: 'ajax',
                        //action: 'closeCart'
                    }
               } 
            ).done(
                function(){
                    $("#ticket tr").remove();
                    $('#idTicket').val('');
                    sumaPrecios();
                    $('#save').text('Guardar Ticket');
                }
            ).fail(
            );
    }
    function saveCart() {
        $.ajax(
        {
            url: 'ajax/saveCart',
            type: 'post',
            dataType: 'json',
            data: 
            {
                //route: 'ajax',
                //action: 'saveCart',
                idClient: $('.data-client p').attr('id'),
                idTicket:  $('#idTicket').val()
            }
        } 
        ).done(
            function(json) {
                if($('#idTicket').val() !== '' || json.idTicket !== null) {
                    $('#save').text('Editar Ticket');
                }
                $('#idTicket').val(json.idTicket);
                alert('Ticket guardado');
            }
        ).fail(
            function(){
                alert('error al salvar');
            }
        );
    } //Falta hacer que se actualice correctamente a 'Editar Ticket'
    
    /*CLIENTES*/
    $('#name').keyup(search);
    function search(){
            $.ajax(
               {
                    url: 'ajax/getAllClientFromName',
                    type: 'post',
                    dataType: 'json',
                    data: {
                        // route: 'ajax',
                        // action: 'getAllClientFromName',
                        name: $('#name').val()
                    }
               } 
            ).done(
                function(json) {
                    var clients = json.clients;
                    deleteNodeClient();
                for(indice in clients){
                    var client = clients[indice];
                    createNodeClient(client);
                }
                $('#search-btn').unbind('click');
                $('tr.pointer').click(bindClient);
                    //pintarLinea(json.productos);
                }
            ).fail(
                function() {
                    alert('error');
                }
            ).always(
                function(){
                    //alert('siempre');
                }
            );
        }
    function deleteNodeClient(){
        $('#clients').children('tr').remove();
    }
    function createNodeClient(client){
        $nodeClient = '<tr id="'+client.id+'" class="pointer">'+
        '<td>'+client.name+'</td>'+
        '<td>'+client.surname+'</td>'+
        '<td>'+client.tin+'</td>'+'</tr>';
        
        $('#clients').append($nodeClient);
    }
    $('#remove-btn').on('click', removeContentModel);
    function removeContentModel(){
        $('#clientForTicket').val('');
        $('.chosen-client').removeClass('chosen-client');
        var text = 'No hay cliente asignado';
        fillDataClient(text);
    }
    function fillDataClient(text){
        $('.data-client').children('p').remove();
        $node = '<p id=""> '+text+' </p>';
        $('.data-client').append($node);
    }
    function bindClient(){
        $('tr.pointer').removeClass("chosen-client");
        $(this).addClass("chosen-client");
        var idClient = $(this).attr('id');
        save(idClient);
    }
    function save(id){
         $.ajax(
               {
                    url: 'ajax/getClient',
                    type: 'post',
                    dataType: 'json',
                    data: {
                        //route: 'ajax',
                        //action: 'getClient',
                        id: id
                    }
               } 
            ).done(
                function(json) {
                    var client = json.client;
                    $('.data-client').children('p').remove();
                    $node = '<p id="'+client.id+'">Cliente: '+client.name+' '+client.surname+'</p>';
                    $('.data-client').append($node);
                }
            ).fail(
                function() {
                   var text = 'Error al asignar el cliente';
                   fillDataClient(text);
                }
            );
    }
    
    function sumaPrecios(){
        var suma = 0;
        $(".subtotal").each(function(){
            suma+=parseFloat($(this).text());
        })
        $(".counter").text(suma.toFixed(2));
    }
    
    
    // $('tr.pointer').click(unBindClient);
    // function unBindClient(){
    //     if($(this).hasClass('chosen-client')){
    //           var text = 'No hay cliente asignado';
    //           $(this).removeClass('chosen-client');
    //           fillDataClient(text);
    //     }
    // }
    
    /*PRODUCTOS*/
    
    $('.product-container').on('click', function(){
        var id = $(this).children('img').attr('id');
        if(id !== undefined){
            addLineFromPlus(id);
        }
    });
    
    $('.family-selector').on('click', loadProducts);
    
    function loadProducts() {
        $.ajax(
            {
                url: 'ajax/getAllProductFromFamily',
                type: 'get',
                dataType: 'json',
                data: {
                    family: this.dataset.family
                }
            }     
        ).done(
            function(json) {
                productsJson(json);
            }
        ).fail(
            function(jqXHR, textStatus, errorThrown) {
                console.log(jqXHR);
                //console.log(textStatus);
                //console.log(errorThrown);
                alert('error de producto');
            }
        );
    }
    
    function productsJson(json) {
        var products = json.products;
        //createRows(products.length);
        var imgDivs = $(".product-container");
        imgDivs.empty();
        for (var index in products) {
            var product = products[index];
            var imgContainer = imgDivs[index];
            createImage(product, imgContainer, json.productImages); 
        }
    }
    function createImage(product, imgContainer, productImages) {
        var imgNode = $('<img>',    
            {
                'class': 'img-fluid rounded pointer height_imgs', 
                'id': product.id,
                //forma antigua
                //'src': '?route=ajax&action=loadImg&img=' + product.id
                //ruta de las imagenes
                'src': productImages[product.id]
                //imagen en base64
                //'src': imagesURI[product.id]
               
            }
        );
        imgContainer.append(imgNode[0]);
    }
    
    (function () {
        $.ajax(
            {
                url: 'ajax/getAllProductFromFamily',
                type: 'post',
                dataType: 'json',
                data: {
                    // route: 'ajax',
                    // action: 'getAllProductFromFamily',
                    family: '1'
                }
            }     
        ).done(
            function(json) {
                productsJson(json);
                var buttons = $('.btnFamilyGroup button');
                var families = json.families;
                buttons.each(function(index) {
                    console.log(buttons[index]);
                    console.log(buttons[index].dataset.family);
                    for (var j in families) {
                        if ( buttons.eq(index).attr('data-family') === families[j].id) {
                            buttons[index].textContent = families[j].family;
                        }
                        // NO FUNCIONA BIEN EL dataset.family
                        // if ( buttons[index].getAttribute('data-family') === families[j].id) {
                        //     buttons[index].textContent = families[j].family;
                         //} //CAMBIAR PORQUE DA ERROR AUNQUE FUNCIONE
                    }
                });
            }
        ).fail(
            function() {
                    alert('error');
                }
        ).always(function(){});
    })();
    
    function createRows(productsLength, columnsNumber = 4) {
        var bootstrapGrid = 12 / columnsNumber;
        var row = $('<div>', {'class':'row height_imgs'});
        var col = $('<div>', {'class':'col-lg-'+bootstrapGrid+' col-md-'+bootstrapGrid+' col-sm-'+bootstrapGrid});
        var i = 0;
        while (i < columnsNumber) {
            row.append(col);
            i++;
        }
        var rows = productsLength;
    }
    
})();