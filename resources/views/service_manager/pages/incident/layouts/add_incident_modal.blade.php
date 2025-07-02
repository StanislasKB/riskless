<div class="modal fade" id="addIncidentModal" tabindex="-1" aria-hidden="true">

    <form action="{{ route('service.incident.store',['uuid'=>$service->uuid ]) }}" method="post" class="modal-dialog modal-dialog-centered">
        @csrf
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nouveau incident</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <div class="mb-3">
                    <label class="form-label">Type d'incident</label>
                    <input class="form-control mb-1 @error('type') is-invalid @enderror" type="text"
                        placeholder="Type d'incident" name="type" value="{{ old('type') }}">
                    @error('type')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Libellé</label>
                    <input class="form-control mb-1 @error('libelle') is-invalid @enderror" type="text"
                        placeholder="Libellé" name="libelle" value="{{ old('libelle') }}">
                    @error('libelle')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Fréquence susceptible</label>
                    <input class="form-control mb-1 @error('frequence_susceptible') is-invalid @enderror" type="text"
                        placeholder="Fréquence susceptible" name="frequence_susceptible" value="{{ old('frequence_susceptible') }}">
                    @error('frequence_susceptible')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea name="description" placeholder="Description"
                        class="form-control mb-1 @error('description') is-invalid @enderror" id="description">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Identifié  par</label>
                    <input class="form-control mb-1 @error('identifie_par') is-invalid @enderror" type="text"
                        placeholder="Identifié par" name="identifie_par" value="{{ old('identifie_par') }}">
                    @error('identifie_par')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>


                <div class="mb-3">
                    <label class="form-label">Sélectionne le fiche de risque</label>
                    <select class="single-select form-select @error('fiche_risque_id') is-invalid @enderror"
                        id="riskFicheSelect" name="fiche_risque_id" style="z-index: 999999;">
                        <option value="">-- Sélectionnez --</option>
                        <option value="1" {{ old('account_id') == 1 ? 'selected' : '' }}>United States</option>
                        <option value="2" {{ old('account_id') == 2 ? 'selected' : '' }}>United Kingdom</option>
                        <option value="3" {{ old('account_id') == 3 ? 'selected' : '' }}>Afghanistan</option>
                        <!-- Ajoute ici tes vraies options -->
                    </select>
                    @error('fiche_risque_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                <button type="submit" class="btn btn-primary">Ajouter</button>
            </div>
        </div>
    </form>

</div>
