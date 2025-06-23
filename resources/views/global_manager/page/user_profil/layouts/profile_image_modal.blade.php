<div class="modal fade" id="updateProfil" tabindex="-1" aria-hidden="true">
    <form action="{{ route('global.update_profil_img.post') }}" method="post" enctype="multipart/form-data" class="modal-dialog modal-dialog-centered">
        @csrf
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Photo de profil</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <div>
                   <label for="formFile" class="form-label">Choisir l'image</label>
										<input class="form-control" type="file" id="formFile" accept="image/*" name="profil_img">
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                <button type="submit" class="btn btn-primary">Changer</button>
            </div>
        </div>
    </form>
</div>
