<!-- 
    Created on : 11/02/2017, 23:10:00
    Author     : ArgenCode
-->

<!-- INICIO - Ventana modal para desactivar registros -->
<form id="formulario_baja" method="POST" action="">
    <div id="ventana_modal_borrado" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false">
        <div class="modal-dialog">
            <div class="modal-content">    
                <div class="modal-body">
                    <input type="hidden" id="cliente_id_b" name="cliente_id_b" value="0" />
                    <h4>¿Está seguro que desea deshabilitar este registro?</h4>
                    <hr>
                    <h4>Nota:</h4>
                    <p>Los registros nunca son eliminados completamente, los mismos son desactivados.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">No</button>
                    <button type="submit" class="btn btn-primary waves-effect waves-light" >Sí, deshabilitar</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</form> 
<!-- FIN - Ventana modal para desactivar registros -->

<!-- INICIO - Ventana modal para activar registros -->
<form id="formulario_habilita" method="POST" action="">
    <div id="ventana_modal_habilita" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false">
        <div class="modal-dialog">
            <div class="modal-content">    
                <div class="modal-body">
                    <input type="hidden" id="cliente_id_a" name="cliente_id_a" value="0" />
                    <h4>¿Está seguro que desea volver a habilitar el registro?</h4>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">No</button>
                    <button type="submit" class="btn btn-primary waves-effect waves-light" >Sí, habilitar</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</form> 
<!-- FIN - Ventana modal para activar registros -->

