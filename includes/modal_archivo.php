<!-- 
    Created on : 11/02/2017, 23:10:00
    Author     : ArgenCode
-->

<!-- INICIO - Ventana modal para eliminar archivos -->
<form id="formulario_baja_archivo" method="POST" action="">
    <div id="ventana_modal_borrado_archivo" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false">
        <div class="modal-dialog">
            <div class="modal-content">    
                <div class="modal-body">
                    <input type="hidden" id="archivo_id_modal_baja" name="archivo_id_modal_baja" value="0" />
                    <h4>¿Está seguro que eliminar el archivo?</h4>
                    <hr>
                    <h4>Nota:</h4>
                    <p>Los archivos son eliminados permanentemente y no pueden ser recuperados.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">No</button>
                    <button type="submit" class="btn btn-primary waves-effect waves-light" >Sí, eliminar</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</form> 
<!-- FIN - Ventana modal para desactivar registros -->


