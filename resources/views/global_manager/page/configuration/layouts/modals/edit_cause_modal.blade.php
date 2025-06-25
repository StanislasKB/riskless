<div class="modal fade" id="editRiskCause-{{ $cause->id }}" tabindex="-1" aria-hidden="true">
    <form action="{{ route('global.update_configuration_risque_cause.post',['id'=>$cause->id]) }}" method="post" class="modal-dialog modal-dialog-centered">
        @csrf
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Modification</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="col-md-12 mb-3">
                    <label for="validationCustom0016" class="form-label">Libellé</label>
                    <input type="text" class="form-control" id="validationCustom0016" required name="libelle" required
                        value="{{ $cause->libelle }}">
                </div>
                 <div class="col-md-12 mb-3">
                    <select class="form-select"  name="niveau" required>
                        <option value="1" @if ($cause->level==1)   selected @endif>Niveau 1</option>
                        <option value="2"@if ($cause->level==2)   selected @endif>Niveau 2</option>
                        <option value="3"@if ($cause->level==3)   selected @endif>Niveau 3</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                <button type="submit" class="btn btn-primary">Créer</button>
            </div>
        </div>
    </form>
</div>
