<div id="modal_busqueda_servicios" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal_busqueda_servicios_label" aria-hidden="true" style="display: none;">
    <div class="modal-dialog" style="width:80%;">
        <div class="modal-content">
            <div class="modal-header">
                <b>Buscar Servicios</b>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <!-- INICIO - Grilla para buscar Servicios -->
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card-box">
                            <div class="row">
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <label>Número de Caso</label>
                                        <input type="text" name="caso_numero_buscar" id="caso_numero_buscar" class="form-control" maxlength="6">
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Pagador</label>
                                        <input type="text" name="caso_pagador_v" id="caso_pagador_v" class="form-control" readonly>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <label>&nbsp;</label>
                                    <div class="form-group text-right">
                                        <button id="btn_listar_servicios" class="btn btn-primary waves-effect waves-light m-l-5"> Buscar <i class="glyphicon glyphicon-search" ></i></button>
                                    </div> 
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div id="grilla_servicios"></div>      
                <!-- FIN - Grilla para buscar Servicios -->
            </div>
        </div>
    </div> 
</div>