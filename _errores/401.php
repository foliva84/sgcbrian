<?php require '../includes/header_account.php'; ?>

    <div class="ex-page-content">
        <div class="container">
            <div class="row">
                <!-- 401 Error -->
                <div class="col-sm-12">
                    <div class="error-code text-danger"><i class="fa fa-shield"></i> 401</div>
                </div>
                <div class="col-sm-12" style="text-align: center;">
                    <h3 class="m-b-0">Error de seguridad</h3>
                    <p>No tiene autorización para acceder a esta página.</p>
                    <a onclick="history.back(-1)" class="btn btn-custom btn-primary waves-effect waves-light m-t-20">Volver</a>
                </div>
                <!-- END 401 Error -->
            </div>
        </div>
    </div>

<?php require '../includes/footer_account.php'; ?>