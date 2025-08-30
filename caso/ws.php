<!-- INICIO - Modal WS  -->
<div id="modal-ws" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-wsLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog" style="width:80%;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <!-- Inicio - de búsqueda de vouchers  -->
            <div class="modal-body"> 
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card-box-form">
                            <div class="row">
                                <h4 class="m-t-0 header-title"><b>Buscar Voucher</b></h4>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="voucher_number">Número de voucher</label>
                                        <input type="text" name="voucher_number" class="form-control" id="voucher_number" maxlength="20">
                                    </div>
                                </div>
                                <div class="col-sm-6">        
                                    <div class="form-group">
                                        <label for="sistema_emision">Sistema de emisión</label>
                                        <select name="sistema_emision" class="form-control" id="sistema_emision"></select>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="passenger_first_name">Nombre</label>
                                        <input type="text" name="passenger_first_name" class="form-control" id="passenger_first_name" maxlength="30">
                                    </div>
                                </div>
                                <div class="col-sm-3">        
                                    <div class="form-group">
                                        <label for="passenger_last_name">Apellido</label>
                                        <input name="passenger_last_name" class="form-control" id="passenger_last_name" maxlength="30">
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="passenger_document_number">Número de Documento</label>
                                        <input type="text" name="passenger_document_number" class="form-control" id="passenger_document_number" maxlength="25">
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <label>&nbsp;</label>
                                    <div class="form-group text-right">
                                        <button id="btn_WS_alta_caso" onclick="javascript:buscar_voucher(1);" class="btn btn-primary waves-effect waves-light m-l-5 hidden"> Buscar <i class="glyphicon glyphicon-search" ></i></button>
                                        <button id="btn_WS_modificar_caso" onclick="javascript:buscar_voucher(2);" class="btn btn-primary waves-effect waves-light m-l-5 hidden"> Buscar <i class="glyphicon glyphicon-search" ></i></button>
                                    </div> 
                                </div>
                            </div>
                        </div>
                    </div>
                </div>  
                <div class="panel panel-default users-content">
                    <div class="panel-body" id="panel_formulario_voucher">        
                        <div id="formulario_voucher"></div>      
                    </div> 

                    <div class="panel-body" id="panel_grilla_producto">        
                        <div id="grilla_producto"></div>      
                    </div> 

                    <div class="panel-body" id="panel_grilla_voucher">        
                        <div id="grilla_voucher"></div>      
                    </div>        
                </div>
            </div>
            <!-- Fin -  de búsqueda de vouchers   -->
        </div>
    </div>
</div>
<!-- FIN - Modal WS  -->