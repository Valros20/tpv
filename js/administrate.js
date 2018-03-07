(function(){
    $('form').submit(validaForm);
    
    function validaForm(event){
        var login=validaLogin();
        var password=validaPassword();
        var repassword=validaRePassword();
        if(!login||!password||!repassword){
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
    
    function validaLogin(){
        var login=$('#login').val();
        var res=false;
        var regExp = /^[a-zA-Z0-9]+$/;
        if (regExp.test(login) && checkLenght(login, 3, 10)) {
            res = true;
            $('#spanLogin').text('*');
        } else if(!regExp.test(login)){
            $('#spanLogin').text(' El usuario sólo puede contener letras y números');
        } else if(!checkLenght(login, 3, 10)){
            $('#spanLogin').text(' La longitud debe estar entre 3 y 10');
        }
        return res;
    }
    
    function validaPassword(){
        var password=$('#password').val();
        var res=false;
        var regExp = /^[A-Za-z0-9_-]+$/;
        if (regExp.test(password) && checkLenght(password, 3, 15)) {
            res = true;
            $('#spanPassword').text('*');
        } else if(!regExp.test(password)){
            $('#spanPassword').text(' La contraseña sólo puede contener letras, números, guiones y guiones bajos');
        } else if(!checkLenght(password, 3, 10)){
            $('#spanPassword').text(' La longitud debe estar entre 3 y 15');
        }
        return res;
    }
    
    function validaRePassword(){
        var password=$('#password').val();
        var repassword=$('#repassword').val();
        var res=false;
        if(password==repassword){
            res=true;
            $('#spanRePassword').text('*');
        }else{
            $('#spanRePassword').text(' Las contraseñas no coinciden');
        }
        return res;
    }
}());