<div class="modal fade" id="evolution-{{ $indicateur->id }}" tabindex="-1" aria-hidden="true">
    <form action="{{ route('service.evolution_indicateur.post',  ['id' => $indicateur->id, 'uuid' => $service->uuid]) }}" method="post"
        class="modal-dialog modal-dialog-centered">
        @csrf
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ajouter une valeur</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="col-md-12 mb-3">
                    <label for="valeur" class="form-label">Valeur</label>
                    <input type="number" step="0.01" class="form-control" id="valeur" required
                        name="valeur_actuelle">
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="mois" class="form-label">Mois associé</label>
                        <input type="text" class="form-control" id="mois" disabled
                            value="{{ ucfirst(Carbon\Carbon::now()->locale('fr')->isoFormat('MMMM')) }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="annee" class="form-label">Année associée</label>
                        <input type="text" class="form-control" id="annee"
                            value="{{ Carbon\Carbon::now()->year }}" disabled>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                <button type="submit" class="btn btn-primary">Ajouter</button>
            </div>
        </div>
    </form>
</div>
