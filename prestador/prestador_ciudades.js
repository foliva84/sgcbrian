//Verifica que se halla cargado la página para ejecutar las funciones.		
$().ready(function(){
      
    paises_prestador_llenar();
    ciudades_prestador_llenar();

    limpiar_controles();
});

 
$('#paises').autocomplete({  
    source: function( request, response ) {
        $.ajax({
            method: "post",
            url : 'prestador_ciudades_cb.php',
            dataType: "json",
                    data: {
                       pais_autocomplete: request.term,
                       opcion: 'paises_autocomplete'
                    },
                    success: function( data ) {
                        response( $.map( data, function( item ) {
                                var code = item.split("|");
                               return {
                                label: code[0],
                                value: code[0],
                                data : item
                                };
                        }));
                  
                    }
        });
    },
    autoFocus: true,
    minLength: 2,
    select: function( event, ui ) {
            var names = ui.item.data.split("|");						
            $('#pais_id').val(names[1]);
            
    }	
    
     
});


$('#paises_p_ciudades').autocomplete({  
    source: function( request, response ) {
        $.ajax({
            method: "post",
            url : 'prestador_ciudades_cb.php',
            dataType: "json",
                    data: {
                       pais_autocomplete: request.term,
                       opcion: 'paises_autocomplete'
                    },
                    success: function( data ) {
                        response( $.map( data, function( item ) {
                                var code = item.split("|");
                               return {
                                label: code[0],
                                value: code[0],
                                data : item
                                };
                        }));
                    }
        });
    },
    autoFocus: true,
    minLength: 2,
    select: function( event, ui ) {
            var names = ui.item.data.split("|");
            $('#paises_p_ciudades_id').val(names[1]);
            $('#ciudades').attr('readonly', false);
            $('#ciudades').attr('placeholder', 'Coloque las primeras 2 letras de la ciudad...');
    }		      	
});




$('#ciudades').autocomplete({  
    source: function( request, response ) {
        $.ajax({
            method: "post",
            url : 'prestador_ciudades_cb.php',
            dataType: "json",
                    data: {
                       ciudad_autocomplete: request.term,
                       opcion: 'ciudades_autocomplete',
                       pais_id: $('#paises_p_ciudades_id').val()
                    },
                    success: function( data ) {
                        response( $.map( data, function( item ) {
                                var code = item.split("|");
                               return {
                                label: code[0],
                                value: code[0],
                                data : item
                                };
                        }));
                    }
        });
    },
    autoFocus: true,
    minLength: 2,
    select: function( event, ui ) {
            var names = ui.item.data.split("|");						
            $('#ciudad_id').val(names[1]);	
    }		      	
});

const limpiar_controles = () => {
    
    $('#paises').val("");
    $('#pais_id').val("");
    $('#paises_p_ciudades').val("");
    $('#paises_p_ciudades_id').val("");
    $('#ciudades').val("");
    $('#ciudad_id').val("");
    $('#ciudades').attr('readonly', true);
    $('#ciudades').attr('placeholder', 'Elija primero un país');
};

const paises_prestador_llenar = () => {
    
    let prestador_id =  $("#prestador_id").val();
  
    const parametros = {
        "opcion": 'paises_prestador_llenar',
        "prestador_id": prestador_id
    };

    let miselect = $("#paises_prestador");

    $.ajax({
        type: 'POST',
        dataType:'JSON',
        url: 'prestador_ciudades_cb.php',
        data: parametros,
        success:function(data){
            for (var i=0; i<data.length; i++) {
                miselect.append('<option value="' + data[i].prestadorPais_id + '">' + data[i].pais_nombreEspanol + '</option>');
            }
        },
        error: function (xhr, ajaxOptions, thrownError) {
            $.Notification.autoHideNotify('error', 'top center', 'Error...', 'Reporte del error. ' + xhr.responseText + ' ' + ajaxOptions + ' ' + thrownError);
        }
    });
    
};

const paises_prestador_insertar = () => {
    
    let prestador_id =  $("#prestador_id").val();  
    let pais_id = $("#pais_id").val();
    
    if(!pais_id){
        $.Notification.autoHideNotify('error', 'top center', 'Error...', 'Debe seleccionar un país a su izquierda');
        return;
    }
  
    const parametros = {
        "opcion": 'paises_prestador_insertar',
        "prestador_id": prestador_id,
        "pais_id": pais_id
    };

    $.ajax({
        type: 'POST',
        dataType:'html',
        url: 'prestador_ciudades_cb.php',
        data: parametros,
        success:function(data){
            
            let resultado = data[0];
            
            console.log(resultado);
            
            if (resultado === "t") {
                 $.Notification.autoHideNotify('error', 'top center', 'Error...', 'Ya existe');
            } else {
                
                $.Notification.autoHideNotify('success', 'top center', 'Pais agregada','Se agregó el pais al prestador.');  
                $('#paises_prestador').empty();
                paises_prestador_llenar();
                
            }
            
        },
        error: function (xhr, ajaxOptions, thrownError) {
            $.Notification.autoHideNotify('error', 'top center', 'Error...', 'Reporte del error. ' + xhr.responseText + ' ' + ajaxOptions + ' ' + thrownError);
        }
    });
    
};

const paises_prestador_eliminar = () => {
    
    let prestadorPais_id = $("#paises_prestador option:selected").val();
    
    if (!prestadorPais_id){
        $.Notification.autoHideNotify('error', 'top center', 'Seleccione un país', 'para eliminar.');
        return;
    }  
      
    const parametros = {
        "opcion": 'paises_prestador_eliminar',
        "prestadorPais_id": prestadorPais_id
    };

    $.ajax({
        type: 'POST',
        dataType:'html',
        url: 'prestador_ciudades_cb.php',
        data: parametros,
        success:function(){
            $.Notification.autoHideNotify('success', 'top center', 'Pais eliminado','Se eliminó el país del prestador.');  
            $('#paises_prestador').empty();
            paises_prestador_llenar();
        },
        error: function (xhr, ajaxOptions, thrownError) {
            $.Notification.autoHideNotify('error', 'top right', 'Error...', 'Reporte del error. ' + xhr.responseText + ' ' + ajaxOptions + ' ' + thrownError);
        }
    });
    
};


//--------------------------- sección ciudades-----------------------------------

const ciudades_prestador_llenar = () => {
    
    let prestador_id =  $("#prestador_id").val();
  
    const parametros = {
        "opcion": 'ciudades_prestador_llenar',
        "prestador_id": prestador_id
    };

    let miselect = $("#ciudades_prestador");

    $.ajax({
        type: 'POST',
        dataType:'JSON',
        url: 'prestador_ciudades_cb.php',
        data: parametros,
        success:function(data){
            for (var i=0; i<data.length; i++) {
                miselect.append('<option value="' + data[i].prestadorCiudad_id + '">' + data[i].ciudad_nombre + '</option>');
            }
        },
        error: function (xhr, ajaxOptions, thrownError) {
            $.Notification.autoHideNotify('error', 'top center', 'Error...', 'Reporte del error. ' + xhr.responseText + ' ' + ajaxOptions + ' ' + thrownError);
        }
    });
    
};

const ciudades_prestador_insertar = () => {
    
    let prestador_id =  $("#prestador_id").val();
    let ciudad_id = $("#ciudad_id").val();
    
    if(!ciudad_id){
        $.Notification.autoHideNotify('error', 'top center', 'Error...', 'Debe seleccionar una ciudad a su izquierda');
        return;
    }
  
    const parametros = {
        "opcion": 'ciudades_prestador_insertar',
        "prestador_id": prestador_id,
        "ciudad_id": ciudad_id
    };

    $.ajax({
        type: 'POST',
        dataType:'html',
        url: 'prestador_ciudades_cb.php',
        data: parametros,
        success:function(data){
            
            let resultado = data[0];
            
            console.log(resultado);
            
            if (resultado === "t") {
                 $.Notification.autoHideNotify('error', 'top center', 'Error...', 'Ya existe');
            } else {
                
                $.Notification.autoHideNotify('success', 'top center', 'Ciudad agregada','Se agregó la ciudad al prestador.');  
                $('#ciudades_prestador').empty();
                ciudades_prestador_llenar();
                limpiar_controles();
            }
            
        },
        error: function (xhr, ajaxOptions, thrownError) {
            $.Notification.autoHideNotify('error', 'top center', 'Error...', 'Reporte del error. ' + xhr.responseText + ' ' + ajaxOptions + ' ' + thrownError);
        }
    });
    
};



const ciudades_prestador_eliminar = () => {
    
    let prestadorCiudad_id = $("#ciudades_prestador option:selected").val();
    
    if (!prestadorCiudad_id){
        $.Notification.autoHideNotify('error', 'top center', 'Seleccione una ciudad', 'para eliminar.');
        return;
    }  
      
    const parametros = {
        "opcion": 'ciudades_prestador_eliminar',
        "prestadorCiudad_id": prestadorCiudad_id
    };

    $.ajax({
        type: 'POST',
        dataType:'html',
        url: 'prestador_ciudades_cb.php',
        data: parametros,
        success:function(){
            $.Notification.autoHideNotify('success', 'top center', 'Ciudad eliminada','Se eliminó la ciudad del prestador.');  
            $('#ciudades_prestador').empty();
            ciudades_prestador_llenar();
        },
        error: function (xhr, ajaxOptions, thrownError) {
            $.Notification.autoHideNotify('error', 'top right', 'Error...', 'Reporte del error. ' + xhr.responseText + ' ' + ajaxOptions + ' ' + thrownError);
        }
    });
    
};




