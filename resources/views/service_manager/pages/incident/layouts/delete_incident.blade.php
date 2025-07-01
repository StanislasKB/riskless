<button class="btn btn-danger ms-auto" data-bs-toggle="modal" data-bs-target="#deleteIncidentModal{{ $incident->id }}">Delete</button>
<div class="modal fade" id="deleteIncidentModal{{ $incident->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('service.incidents.delete',['uuid'=>$service->uuid,'incidentId'=>$incident->id ]) }}" method="POST" class="modal-content">
            @csrf
            @method('DELETE')

            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Supprimer l'incident</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>

            <div class="modal-body">
                <p>Êtes-vous sûr de vouloir supprimer cet incident ? Cette action est irréversible.</p>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="submit" class="btn btn-danger">Oui, supprimer</button>
            </div>
        </form>
    </div>
</div>
