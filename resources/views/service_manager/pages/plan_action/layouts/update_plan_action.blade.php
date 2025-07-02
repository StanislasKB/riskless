
<button class="btn btn-warning ms-auto" data-bs-toggle="modal" data-bs-target="#editActionModal{{ $plan->id }}">Modifier</button>

<div class="modal fade" id="editActionModal{{ $plan->id }}" tabindex="-1" aria-hidden="true">
    <form action="{{ route('service.plan-actions.update',['uuid'=>$service->uuid,'planId'=>$plan->id ]) }}" method="POST" class="modal-dialog modal-dialog-centered modal-lg">
        @csrf
        @method('PUT') <!-- méthode PUT pour update -->
        <div class="modal-content shadow">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title">Modifier le plan d'action</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">

                <div class="mb-3">
                    <label class="form-label">Type d'action</label>
                    <input type="text" class="form-control @error('type') is-invalid @enderror"
                           name="type" value="{{ old('type', $plan->type) }}">
                    @error('type')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Priorité</label>
                    <input type="text" class="form-control @error('priorite') is-invalid @enderror"
                           name="priorite" value="{{ old('priorite', $plan->priorite) }}">
                    @error('priorite')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Responsable</label>
                    <input type="text" class="form-control @error('responsable') is-invalid @enderror"
                           name="responsable" value="{{ old('responsable', $plan->responsable) }}">
                    @error('responsable')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="4">{{ old('description', $plan->description) }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Date de début prévue</label>
                        <input type="datetime-local" class="form-control @error('date_debut_prevue') is-invalid @enderror"
                               name="date_debut_prevue"
                               value="{{ old('date_debut_prevue', \Carbon\Carbon::parse($plan->date_debut_prevue)->format('Y-m-d\TH:i')) }}">
                        @error('date_debut_prevue')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Date de fin prévue</label>
                        <input type="datetime-local" class="form-control @error('date_fin_prevue') is-invalid @enderror"
                               name="date_fin_prevue"
                               value="{{ old('date_fin_prevue', \Carbon\Carbon::parse($plan->date_fin_prevue)->format('Y-m-d\TH:i')) }}">
                        @error('date_fin_prevue')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Statut</label>
                    <select name="statut" class="form-select @error('statut') is-invalid @enderror">
                        @php
                            $statuses = ['A_LANCER','PLANIFIER','EN_COURS','TERMINER','ANNULER','PAUSE'];
                        @endphp
                        @foreach($statuses as $status)
                            <option value="{{ $status }}" {{ old('statut', $plan->statut) === $status ? 'selected' : '' }}>
                                {{ ucfirst(strtolower(str_replace('_', ' ', $status))) }}
                            </option>
                        @endforeach
                    </select>
                    @error('statut')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Progression (%)</label>
                    <input type="number" class="form-control @error('progression') is-invalid @enderror"
                           name="progression" value="{{ old('progression', $plan->progression) }}" min="0" max="100">
                    @error('progression')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="submit" class="btn btn-success">Enregistrer les modifications</button>
            </div>
        </div>
    </form>
</div>
