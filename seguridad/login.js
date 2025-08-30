
 $('#error_login').hide();
 $('#usuario').focus();

//Habilita la tecla ENTER para el login
$(document).keypress(function (e) {
    if(e.keyCode === 13){
        ingreso();
    }
});

// carga los datos a editar en el formulario
const ingreso = () => {

   
    const usuario = $('#usuario').val();
    const password = $('#password').val();

    var parametros = {
        "usuario": usuario,
        "password": password
    };

     $.ajax({
        type: 'POST',
        dataType:'JSON',
        url: 'login_cb.php',
        data: parametros,
        success:function(data){
            if(data){
                $(location).attr('href',"../dashboard/dashboard.php"); 
                
            }else{
                $('#usuario').val("");
                $('#password').val("");
                $('#error_login').show();
                $("#error_login").attr("value","OTRO TEXTO");
                $('#usuario').focus();
            }
        },
        
        error: function (xhr, ajaxOptions, thrownError) {
            
            $('#error_login').show();
            $("#error_login").attr("value","OTRO TEXTO");
            
            }
    });
};






