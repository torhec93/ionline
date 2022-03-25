<!-- Modal -->
<div class="modal fade" id="releaseitem" tabindex="-1" aria-labelledby="releaseitem" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">            
                <h5 class="modal-title" id="releaseitem">Escribir Motivo Eliminación Item</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            <form method="POST" class="form-horizontal" action="{{ route('request_forms.supply.release_item', $item) }}">
                    @csrf                    
                    <div class="form-row">
                        <fieldset class="form-group col-sm">
                            <textarea name="release_observation" class="form-control form-control-sm" rows="3" placeholder="Ingrese Motivo Eliminación Item" required></textarea>
                        </fieldset>
                    </div>

                    <button type="submit" class="btn btn-primary float-right btn-sm">Enviar solicitud</button>
                </form>
            </div>            
        </div>
    </div>
</div>