<div class="modal fade" id="addActionModal" tabindex="-1" aria-hidden="true">

    <form action="{{ route('service.plan-actions.store',['uuid'=>$service->uuid ]) }}" method="post" class="modal-dialog modal-dialog-centered">
        @csrf
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nouveau plan d'action</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <div class="mb-3">
                    <label class="form-label">Type d'action</label>
                    <input class="form-control mb-1 @error('type') is-invalid @enderror" type="text"
                        placeholder="Type d'action" name="type" value="{{ old('type') }}">
                    @error('type')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Priorité</label>
                    <input class="form-control mb-1 @error('priorite') is-invalid @enderror" type="text"
                        placeholder="Priorité" name="priorite" value="{{ old('priorite') }}">
                    @error('priorite')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Responsable</label>
                    <input class="form-control mb-1 @error('responsable') is-invalid @enderror" type="text"
                        placeholder="Responsable" name="responsable" value="{{ old('responsable') }}">
                    @error('responsable')
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
                    <label class="form-label">Date de début prévue</label>
                    <input class="form-control mb-1 @error('date_debut_prevue') is-invalid @enderror"
                        type="datetime-local" placeholder="Date de début prévue" name="date_debut_prevue"
                        value="{{ old('date_debut_prevue') }}">
                    @error('date_debut_prevue')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Date de fin prévue</label>
                    <input class="form-control mb-1 @error('date_fin_prevue') is-invalid @enderror"
                        type="datetime-local" placeholder="Date de fin prévue" name="date_fin_prevue"
                        value="{{ old('date_fin_prevue') }}">
                    @error('date_fin_prevue')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                 <div class="mb-3">
                    <label class="form-label">Statut</label>
                    <select name="statut" id="" class="form-select mb-1 @error('statut') is-invalid @enderror">
                        <option value="A_LANCER" {{ old('statut') == 'A_LANCER' ? 'selected' : '' }}>À lancer</option>
                        <option value="PLANIFIER" {{ old('statut') == 'PLANIFIER' ? 'selected' : '' }}>Planifier</option>
                    </select>
                    @error('statut')
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
