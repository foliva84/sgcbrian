cliente_productos//Verifica que se halla cargado la página para ejecutar las funciones.		
$().ready(function(){
      
    cliente_productos_llenar();
    productos_llenar();
    
});


$("#productos").change(function () {    
    $("#producto_id").val("");
});


const productos_llenar = () => {
    
    var cliente_id =  $("#cliente_id").val();
  
    const parametros = {
        "opcion": 'productos_llenar',
        "cliente_id": cliente_id
    };

    let miselect = $("#productos");

    $.ajax({
        type: 'POST',
        dataType:'JSON',
        url: 'cliente_producto_cb.php',
        data: parametros,
        success:function(data){
            for (var i=0; i<data.length; i++) {
                miselect.append('<option value="' + data[i].product_id_interno + '">' + data[i].product_name + '</option>');
            }
        },
        error: function (xhr, ajaxOptions, thrownError) {
            $.Notification.autoHideNotify('error', 'top center', 'Error...', 'Reporte del error. ' + xhr.responseText + ' ' + ajaxOptions + ' ' + thrownError);
        }
    });
    
};


const cliente_productos_llenar = () => {
    
    var cliente_id =  $("#cliente_id").val();
  
  
    const parametros = {
        "opcion": 'cliente_productos_llenar',
        "cliente_id": cliente_id
    };

    let miselect = $("#cliente_productos");

    $.ajax({
        type: 'POST',
        dataType:'JSON',
        url: 'cliente_producto_cb.php',
        data: parametros,
        success:function(data){
            for (var i=0; i<data.length; i++) {
                miselect.append('<option value="' + data[i].clienteProduct_id + '">' + data[i].product_name + '</option>');
            }
        },
        error: function (xhr, ajaxOptions, thrownError) {
            $.Notification.autoHideNotify('error', 'top center', 'Error...', 'Reporte del error. ' + xhr.responseText + ' ' + ajaxOptions + ' ' + thrownError);
        }
    });
    
};


const cliente_productos_insertar = () => {
    
    let cliente_id = $("#cliente_id").val();
    let producto_id = $("#productos option:selected").val();
    
    if(!producto_id){
        $.Notification.autoHideNotify('error', 'top center', 'Error...', 'Debe seleccionar un producto a su izquierda');
        return;
    }
  
    const parametros = {
        "opcion": 'cliente_productos_insertar',
        "cliente_id": cliente_id,
        "producto_id": producto_id
    };

    $.ajax({
        type: 'POST',
        dataType:'html',
        url: 'cliente_producto_cb.php',
        data: parametros,
        success:function(data){
            
            let resultado = data[0];
            
            console.log(resultado);
            
            if (resultado === "t") {
                 $.Notification.autoHideNotify('error', 'top center', 'Error...', 'Ya existe');
            } else {
                
                $.Notification.autoHideNotify('success', 'top center', 'Producto agregado','Se agregó el producto al cliente.');  
                $('#cliente_productos').empty();
                cliente_productos_llenar();
            }
            
        },
        error: function (xhr, ajaxOptions, thrownError) {
            $.Notification.autoHideNotify('error', 'top center', 'Error...', 'Reporte del error. ' + xhr.responseText + ' ' + ajaxOptions + ' ' + thrownError);
        }
    });
    
};


const cliente_productos_eliminar = () => {
    
    let clienteProducto_id = $("#cliente_productos option:selected").val();
    
    if (!clienteProducto_id){
        $.Notification.autoHideNotify('error', 'top center', 'Seleccione un producto', 'para eliminar.');
        return;
    }  
    
    const parametros = {
        "opcion": 'cliente_productos_eliminar',
        "clienteProducto_id": clienteProducto_id
    };

    $.ajax({
        type: 'POST',
        dataType:'html',
        url: 'cliente_producto_cb.php',
        data: parametros,
        success:function(){
            $.Notification.autoHideNotify('success', 'top center', 'Producto eliminado','Se eliminó el producto del cliente.');  
            $('#cliente_productos').empty();
            cliente_productos_llenar();
        },
        error: function (xhr, ajaxOptions, thrownError) {
            $.Notification.autoHideNotify('error', 'top right', 'Error...', 'Reporte del error. ' + xhr.responseText + ' ' + ajaxOptions + ' ' + thrownError);
        }
    });
    
};