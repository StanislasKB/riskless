
<button class="btn btn-primary ms-auto" data-bs-toggle="modal" data-bs-target="#viewActionModal{{ $plan->id }}">Voir</button>
<div class="modal fade" id="viewActionModal{{ $plan->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Détail du plan d'action</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <div class="mb-3">
                    <label class="form-label">Type d'action</label>
                    <input type="text" class="form-control" value="{{ $plan->type ?? '—' }}" readonly>
                </div>

                <div class="mb-3">
                    <label class="form-label">Priorité</label>
                    <input type="text" class="form-control" value="{{ $plan->priorite ?? '—' }}" readonly>
                </div>

                <div class="mb-3">
                    <label class="form-label">Responsable</label>
                    <input type="text" class="form-control" value="{{ $plan->responsable ?? '—' }}" readonly>
                </div>

                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea class="form-control" rows="4" readonly>{{ $plan->description ?? '—' }}</textarea>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Date de début prévue</label>
                        <input type="text" class="form-control"
                               value="{{ $plan->date_debut_prevue ? \Carbon\Carbon::parse($plan->date_debut_prevue)->format('d/m/Y H:i') : '—' }}"
                               readonly>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Date de fin prévue</label>
                        <input type="text" class="form-control"
                               value="{{ $plan->date_fin_prevue ? \Carbon\Carbon::parse($plan->date_fin_prevue)->format('d/m/Y H:i') : '—' }}"
                               readonly>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Fiche de risque liée</label>
                    <input type="text" class="form-control"
                           value="{{ optional($plan->ficheRisque)->libelle_risk ?? '—' }}"
                           readonly>
                </div>

                <div class="mb-3">
                    <label class="form-label">Statut</label>
                    <span class="form-control bg-light">{{ $plan->statut ?? '—' }}</span>
                </div>

                <div class="mb-3">
                    <label class="form-label">Progression</label>
                    <div class="progress">
                        <div class="progress-bar" role="progressbar"
                            style="width: {{ $plan->progression ?? 0 }}%;"
                            aria-valuenow="{{ $plan->progression ?? 0 }}"
                            aria-valuemin="0" aria-valuemax="100">
                            {{ $plan->progression ?? 0 }}%
                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>
