<!-- 
    Created on : 11/04/2017, 23:10:00
    Author     : ArgenCode
-->

<!-- INICIO - Ventana modal para confirmar eliminación de EMAIL -->
<form id="formulario_eliminar_email" method="POST" action="">
    <div id="ventana_modal_borrado_email" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false">
        <div class="modal-dialog">
            <div class="modal-content">    
                <div class="modal-body">
                    <input type="hidden" id="id_email_eliminar" name="id_eliminar" value="0" />
                    <h4>¿Está seguro que desea eliminar el Email?</h4>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">No</button>
                    <button type="submit" class="btn btn-primary waves-effect waves-light" >Sí</button>
                </div>
            </div>
        </div>
    </div>
</form> 
<!-- FIN - Ventana modal para confirmar eliminación de EMAIL -->

<!-- INICIO - Ventana modal para confirmar eliminación de TELEFONO -->
<form id="formulario_eliminar_telefono" method="POST" action="">
    <div id="ventana_modal_borrado_telefono" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false">
        <div class="modal-dialog">
            <div class="modal-content">    
                <div class="modal-body">
                    <input type="hidden" id="id_telefono_eliminar" name="id_eliminar" value="0" />
                    <h4>¿Está seguro que desea eliminar el Teléfono?</h4>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">No</button>
                    <button type="submit" class="btn btn-primary waves-effect waves-light" >Sí</button>
                </div>
            </div>
        </div>
    </div>
</form> 
<!-- FIN - Ventana modal para confirmar eliminación de TELEFONO -->