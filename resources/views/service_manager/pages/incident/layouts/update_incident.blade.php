
<button class="btn btn-warning ms-auto" data-bs-toggle="modal" data-bs-target="#editIncidentModal{{ $incident->id }}">Modifier</button>
<div class="modal fade" id="editIncidentModal{{ $incident->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('service.incident.update',['uuid'=>$service->uuid,'incidentId'=>$incident->id ]) }}" method="POST" class="modal-content">
            @csrf
            @method('PUT')
            <div class="modal-header bg-warning">
                <h5 class="modal-title">Modifier l'incident</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>

            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Type d'incident</label>
                    <input class="form-control @error('type') is-invalid @enderror" type="text" name="type"
                        value="{{ old('type', $incident->type) }}">
                    @error('type')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Libellé</label>
                    <input class="form-control @error('libelle') is-invalid @enderror" type="text" name="libelle"
                        value="{{ old('libelle', $incident->libelle) }}">
                    @error('libelle')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Fréquence susceptible</label>
                    <input class="form-control @error('frequence_susceptible') is-invalid @enderror" type="text"
                        name="frequence_susceptible" value="{{ old('frequence_susceptible', $incident->frequence_susceptible) }}">
                    @error('frequence_susceptible')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" name="description">{{ old('description', $incident->description) }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Identifié par</label>
                    <input class="form-control @error('identifie_par') is-invalid @enderror" type="text" name="identifie_par"
                        value="{{ old('identifie_par', $incident->identifie_par) }}">
                    @error('identifie_par')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Fiche de risque</label>
                    <select name="fiche_risque_id" class="form-select @error('fiche_risque_id') is-invalid @enderror">
                        <option value="">-- Sélectionnez --</option>
                        @foreach($fichesRisque as $fiche)
                            <option value="{{ $fiche->id }}"
                                {{ old('fiche_risque_id', $incident->fiche_risque_id) == $fiche->id ? 'selected' : '' }}>
                                {{ $fiche->libelle_risk }}
                            </option>
                        @endforeach
                    </select>
                    @error('fiche_risque_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="submit" class="btn btn-warning">Enregistrer</button>
            </div>
        </form>
    </div>
</div>
