<div class="modal fade" id="submitScore-{{ $response->id }}" tabindex="-1" aria-hidden="true">
    <form action="{{ route('global.responses.score.post',['id'=>$response->id]) }}" method="post" enctype="multipart/form-data" class="modal-dialog modal-dialog-centered">
        @csrf
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Noter</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="col-md-12 mb-3">
                    <label for="validationCustom0016" class="form-label">Entrez la note (sur 20)</label>
                    <input class="form-control" type="number" id="formationFile" name="score" min="0" max="20">

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                <button type="submit" class="btn btn-primary">Soumettre</button>
            </div>
        </div>
    </form>
</div>
