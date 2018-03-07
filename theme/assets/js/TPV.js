$(document).ready(function(){
    $("#btAjax").click(function(){
        $("#ticket tr").remove();
        actualiza($(this));
        
    })
    $(".rmv").click(function(e){
        $(this).parent().remove();
        actualiza($(this));
    })
    $(".plus").click(function(){
        $(this).parent().find(".cantidad").text(parseInt($(this).parent().find(".cantidad").text())+1);
        actualiza($(this));
    })
    $(".less").click(function(){
        if($(this).parent().find(".cantidad").text()>1){
            $(this).parent().find(".cantidad").text(parseInt($(this).parent().find(".cantidad").text())-1);
        }else if($(this).parent().find(".cantidad").text()<=1){
            $(this).parent().remove();
        }
        actualiza($(this));
    })
    
    function actualiza(evento){
        var cantidad=parseInt(evento.parent().find(".cantidad").text());
        var precio=parseFloat(evento.parent().find(".precio").text());
        evento.parent().find(".subtotal").text((cantidad*precio).toFixed(2));
        var precios=$(".subtotal");
        $(".counter").text(sumaPrecios(precios));
    }
    
    function sumaPrecios(precios){
        var suma=0;
        $(".subtotal").each(function(){
            suma=suma+parseFloat($(this).text());
        })
        $(".counter").text(suma);
    }
});