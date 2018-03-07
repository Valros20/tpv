(function(){
    $('form').submit(validaForm);
    
    function validaForm(event){
        var nombre=validaNombre();
        var apellidos=validaApellidos();
        var cif=validaCIF();
        var direccion=validaDireccion();
        var poblacion=validaPoblacion();
        var cp=validaCodigoPostal();
        var provincia=validaProvincia();
        var email=validaEmail();
        if(!nombre||!apellidos||!cif||!direccion||!poblacion||!cp||!provincia||!email){
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
        var regExp = /^[a-zA-Z ]+$/;
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
    
    function validaApellidos(){
        var surnames = $('#surnames').val();
        var res = false;
        var regExp = /^[a-zA-Z ]+$/;
        if (regExp.test(surnames) && checkLenght(surnames, 1, 30)) {
            res = true;
            $('#spanApellidos').text('*');
        } else if (!regExp.test(surnames)) {
            if (surnames == '') {
                $('#spanApellidos').text(' Apellidos obligatorios');
            } else {
                $('#spanApellidos').text(' Los apellidos solo pueden contener letras');
            }
        } else if (!checkLenght(surnames, 1, 15)) {
            $('#spanApellidos').text(' Los apellidos deben tener entre 1 y 30 caracteres');
        }
        return res;
    }
    
    function validaCIF(){
        var tin = $('#tin').val();
        var res = false;
        var CIF_REGEX = /^([ABCDEFGHJKLMNPQRSUVW])(\d{7})([0-9A-J])$/;
        if(CIF_REGEX.test(tin)){
            res=true;
            $('#spanCIF').text('*');
        }else $('#spanCIF').text(' CIF no válido');
        return res;
    }
    
    function validaDireccion(){
        var address = $('#address').val();
        var res=false;
        if(checkLenght(address,10,100)){
            res=true;
            $('#spanDireccion').text('*');
        }else $('#spanDireccion').text(' Dirección no válida');
        return res;
    }
    
    function validaPoblacion(){
        var location = $('#location').val();
        var res=false;
        var regExp = /^[a-zA-Z ]+$/;
        if (regExp.test(location)) {
            res = true;
            $('#spanNombre').text('*');
        } else if(checkLenght(location,1,30)){
            res=true;
            $('#spanPoblacion').text('*');
        }else $('#spanPoblacion').text(' Población no válida');
        return res;
    }
    
    function validaCodigoPostal(){
        var cp = $('#postalcode').val();
        var res = false;
        if(cp.length == 5 && parseInt(cp) >= 1000 && parseInt(cp) <= 52999){
            res=true;
            $('#spanCP').text('*');
        }else $('#spanCP').text(' CP no válido');
        return res;
    }
    
    function validaProvincia(){
        var province = $('#province').val();
        var res=false;
        var regExp = /^[a-zA-Z ]+$/;
        if (regExp.test(province)) {
            res = true;
            $('#spanNombre').text('*');
        }else if(checkLenght(province,1,30)){
            res=true;
            $('#spanProvincia').text('*');
        }else $('#spanProvincia').text(' Provincia no válida');
        return res;
    }
    
    function validaEmail(){
        var email = $('#email').val();
        var res = false;
        var Email_REGEX = /^[_a-z0-9-]+(.[_a-z0-9-]+)*@[a-z0-9-]+(.[a-z0-9-]+)*(.[a-z]{2,4})$/;
        if(Email_REGEX.test(email)){
            res=true;
            $('#spanEmail').text('*');
        }else $('#spanEmail').text(' Email no válido');
        return res;
    }
}());