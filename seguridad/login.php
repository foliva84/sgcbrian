<?php require '../includes/header_account.php'; ?>

<div class="wrapper-page">

    <div class="text-center">
        <span><img src="../assets/images/logo_coris.png"/></span>
    </div>

    <div class="form-horizontal m-t-20" >
        
        <div class="form-group center-block">
            <span id="error_login" class="label label-pink" style="margin-left: 70px;">Nombre de usuario o contraseña incorrecto.</span>
        </div>
        
        <div class="form-group">
            <div class="col-xs-12">
                <input class="form-control" type="text" id="usuario" placeholder="Usuario">
                <i class="md md-account-circle form-control-feedback l-h-34"></i>
            </div>
        </div>

        <div class="form-group">
            <div class="col-xs-12">
                <input class="form-control" type="password" id="password" placeholder="Contraseña">
                <i class="md md-vpn-key form-control-feedback l-h-34"></i>
            </div>
        </div>

        <div class="form-group text-right m-t-20">
            <div class="col-xs-12">
                <button onclick="javascript:ingreso();" class="btn btn-success waves-effect w-md waves-light m-b-5">Ingresar</button>
            </div>
        </div>
        
    </div>    

</div>

<?php require '../includes/footer_account.php'; ?>
<script src="login.js"></script> 