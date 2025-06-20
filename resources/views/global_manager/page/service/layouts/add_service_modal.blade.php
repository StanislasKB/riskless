<div class="modal fade" id="addServiceModal" tabindex="-1" aria-hidden="true">
    <form action="{{ route('global.add_service.post') }}" method="post" class="modal-dialog modal-dialog-centered">
        @csrf
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nouveau service</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input class="form-control form-control-lg mb-3" type="text" placeholder="Nom du service"
                    aria-label=".form-control-lg example" name="name">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                <button type="submit" class="btn btn-primary">Créer</button>
            </div>
        </div>
    </form>
</div>
