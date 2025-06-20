<div class="modal fade" id="addServiceUserModal" tabindex="-1" aria-hidden="true">
    <form action="{{ route('global.add_service_user.post') }}" method="post" class="modal-dialog modal-dialog-centered">
        @csrf
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nouvel utilisateur</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <div>
                    <input class="form-control mb-3" type="text" placeholder="Nom d'utilisateur"
                        aria-label=".form-control-lg example" name="username">
                </div>
                <div>
                    <input class="form-control mb-3" type="text" placeholder="Email"
                        aria-label=".form-control-lg example" name="email">
                </div>

                <div class="mb-3">
                    <select class="multiple-select-service" multiple="multiple" name='service[]'>
                        @foreach ($services as $service)
                            <option value="{{ $service->id }}">{{ $service->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <select class="multiple-select-permission" multiple="multiple" name="permission[]">
                        <option value="create risk">Création</option>
                        <option value="edit risk">Edition</option>
                        <option value="validate risk">Validation</option>
                        <option value="delete risk">Suppression</option>
                    </select>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                <button type="submit" class="btn btn-primary">Ajouter</button>
            </div>
        </div>
    </form>
</div>
