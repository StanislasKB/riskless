<div class="modal fade" id="updateUserPermissionModal-{{ $user->id }}" tabindex="-1" aria-hidden="true">
    <form action="{{ route('global.update_service_user_permission.post', ['id' => $user->id]) }}" method="post"
        class="modal-dialog modal-dialog-centered">
        @csrf
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Permission</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <select class="multiple-select-update-permission-{{ $user->id }}" multiple="multiple"
                        name="permission[]">
                        <option value="create risk" @if ($user->hasPermissionTo('create risk')) selected @endif>Création</option>
                        <option value="edit risk" @if ($user->hasPermissionTo('edit risk')) selected @endif>Édition</option>
                        <option value="validate risk" @if ($user->hasPermissionTo('validate risk')) selected @endif>Validation
                        </option>
                        <option value="delete risk" @if ($user->hasPermissionTo('delete risk')) selected @endif>Suppression
                        </option>
                    </select>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                <button type="submit" class="btn btn-primary">Mettre à jour</button>
            </div>
        </div>
    </form>
</div>
