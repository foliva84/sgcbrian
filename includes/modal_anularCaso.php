<!-- 
    Created on : 11/06/2017, 14:11:00
    Author     : ArgenCode
-->

<!-- INICIO - Ventana modal para ANULAR un CASO -->
<form id="formulario_anular" method="POST" action="">
    <div id="ventana_modal_anular" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false" data-keyboard="false" data-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-content">    
                <div class="modal-body">
                    <input type="hidden" id="caso_id_b" name="caso_id_b" value="0" />
                    <h4>¿Está seguro que desea ANULAR el caso?</h4>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">No</button>
                    <button type="submit" class="btn btn-primary waves-effect waves-light" >Sí, anular</button>
                </div>
            </div>
        </div>
    </div>
</form>
<!-- FIN - Ventana modal para ANULAR un CASO -->

<!-- INICIO - Ventana modal para REHABILITAR un CASO -->
<form id="formulario_rehabilita" method="POST" action="">
    <div id="ventana_modal_rehabilita" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false" data-keyboard="false" data-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-content">    
                <div class="modal-body">
                    <input type="hidden" id="caso_id_a" name="caso_id_a" value="0" />
                    <h4>¿Está seguro que desea rehabilitar el caso?</h4>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">No</button>
                    <button type="submit" class="btn btn-primary waves-effect waves-light" >Sí, rehabilitar</button>
                </div>
            </div>
        </div>
    </div>
</form> 
<!-- FIN - Ventana modal para REHABILITAR un CASO -->