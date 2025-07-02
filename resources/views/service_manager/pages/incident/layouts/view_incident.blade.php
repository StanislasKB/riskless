
<button class="btn btn-primary ms-auto" data-bs-toggle="modal" data-bs-target="#viewIncidentModal{{ $incident->id }}">Voir</button>
<div class="modal fade" id="viewIncidentModal{{ $incident->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Détails de l'incident</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
                <dl class="row mb-0">
                    <dt class="col-sm-4">Type d'incident :</dt>
                    <dd class="col-sm-8">{{ $incident->type ?? '—' }}</dd>

                    <dt class="col-sm-4">Libellé :</dt>
                    <dd class="col-sm-8">{{ $incident->libelle }}</dd>

                    <dt class="col-sm-4">Fréquence susceptible :</dt>
                    <dd class="col-sm-8">{{ $incident->frequence_susceptible ?? '—' }}</dd>

                    <dt class="col-sm-4">Description :</dt>
                    <dd class="col-sm-8">{{ $incident->description ?? '—' }}</dd>

                    <dt class="col-sm-4">Identifié par :</dt>
                    <dd class="col-sm-8">{{ $incident->identifie_par ?? '—' }}</dd>

                    <dt class="col-sm-4">Fiche de risque liée :</dt>
                    <dd class="col-sm-8">
                        {{ optional($incident->ficheRisque)->libelle_risk ?? 'Non liée' }}
                    </dd>

                    <dt class="col-sm-4">Créé le :</dt>
                    <dd class="col-sm-8">{{ $incident->created_at->format('d/m/Y H:i') }}</dd>
                </dl>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>

