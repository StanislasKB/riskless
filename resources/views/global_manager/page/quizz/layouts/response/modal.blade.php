<div class="modal fade" id="submitResponse-{{ $quizz->id }}" tabindex="-1" aria-hidden="true">
    <form action="{{ route('global.responses.add.post',['id'=>$quizz->id]) }}" method="post" enctype="multipart/form-data" class="modal-dialog modal-dialog-centered">
        @csrf
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Modification</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="col-md-12 mb-3">
                    <label for="validationCustom0016" class="form-label">Choisissez le fichier de réponse</label>
                    <input class="form-control" type="file" id="formationFile" name="document" accept="pdf">

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                <button type="submit" class="btn btn-primary">Soumettre</button>
            </div>
        </div>
    </form>
</div>
