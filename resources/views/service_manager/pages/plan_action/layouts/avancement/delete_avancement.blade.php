<button class="btn btn-danger ms-auto" data-bs-toggle="modal" data-bs-target="#deleteActionModal{{ $plan->id }}">Delete</button>
<div class="modal fade" id="deleteActionModal{{ $plan->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('service.plan-actions.avancements.delete',['uuid'=>$service->uuid,'planId'=>$plan->id, 'avancementId' => $avancement->id]) }}" method="POST" class="modal-content">
            @csrf
            @method('DELETE')

            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Supprimer l'avancement </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>

            <div class="modal-body">
                <p>Êtes-vous sûr de vouloir supprimer cet avancement?</p>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="submit" class="btn btn-danger">Oui, supprimer</button>
            </div>
        </form>
    </div>
</div>
