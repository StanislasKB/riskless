<div class="modal fade" id="editRiskCategory-{{ $category->id }}" tabindex="-1" aria-hidden="true">
    <form action="{{ route('global.update_configuration_risque_category.post',['id'=>$category->id]) }}" method="post" class="modal-dialog modal-dialog-centered">
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
                        value="{{ $category->libelle }}">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                <button type="submit" class="btn btn-primary">Modifier</button>
            </div>
        </div>
    </form>
</div>
