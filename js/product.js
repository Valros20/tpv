(function(){
    $('form').submit(validaForm);
    
    function validaForm(event){
        var nombre=validaNombre();
        var precio=validaPrecio();
        var descripcion=validaDescripcion();
        var imagen=validaImagen($('#img'));
        if(!nombre||!precio||!descripcion||!imagen){
            event.preventDefault();
        } 
    }
    
    function checkLenght(string, min, max) {
        var res = false;
        if(string.length >= min && string.length <= max) {
            res = true;
        }
        return res;
    }
    
    function validaNombre(){
        var name = $('#name').val();
        var res = false;
        var regExp = /^[a-zA-Z0-9 ]+$/;
        if (regExp.test(name) && checkLenght(name, 1, 15)) {
            res = true;
            $('#spanNombre').text('*');
        } else if (!regExp.test(name)) {
            if (name == '') {
                $('#spanNombre').text(' Nombre obligatorio');
            } else {
                $('#spanNombre').text(' El nombre solo puede contener letras');
            }
        } else if (!checkLenght(name, 1, 15)) {
            $('#spanNombre').text(' El nombre debe tener entre 1 y 15 caracteres');
        }
        return res;
    }
    
    function validaPrecio(){
        var price = $('#price').val();
        var res = false;
        var regExp = /^[0-9]+([.][0-9]{1,2})?$/;
        if(regExp.test(price)){
            res=true;
            $('#spanPrecio').text('*');
        }else $('#spanPrecio').text(' Precio no válido');
        return res;
    }
    
    function validaDescripcion(){
        var description = $('#description').val();
        var res=false;
        if(checkLenght(description,15,140)){
            res=true;
            $('#spanDescripcion').text('');
        }else $('#spanDescripcion').text(' Descripcion no válida');
        return res;
    }
    
    function validaImagen(obj){
        if(!obj[0].files[0]){
            $('#spanImagen').text('Debe introducir una imagen');
            return false;
        }
        
        var uploadFile = obj[0].files[0];
    
        if (!(/\.(jpg|jpeg|png|gif)$/i).test(uploadFile.name)) {
            $('#spanImagen').text('El archivo no es una imagen');
        }
        else {
            var img = new Image();
            if (uploadFile.size > 8000000){
                $('#spanImagen').text('El peso de la imagen no puede exceder los 8MB');
                return false;
            }
            else {
                $('#spanImagen').text('');
                return true;
            }
            img.src = URL.createObjectURL(uploadFile);
        }
    }
}());