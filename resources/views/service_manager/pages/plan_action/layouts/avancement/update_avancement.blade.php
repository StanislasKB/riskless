<button class="btn btn-warning ms-auto" data-bs-toggle="modal"
    data-bs-target="#editActionModal{{ $plan->id }}">Modifier</button>

<div class="modal fade" id="editActionModal{{ $plan->id }}" tabindex="-1" aria-hidden="true">
    <form action="{{ route('service.plan-actions.avancements.update', ['uuid' => $service->uuid, 'planId' => $plan->id, 'avancementId' => $avancement->id]) }}" method="POST"
        class="modal-dialog modal-dialog-centered modal-lg">
        @csrf
        @method('PUT')
        <div class="modal-content shadow">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title">Modifier le plan d'action</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Statut</label>
                    <select name="statut" class="form-select @error('statut') is-invalid @enderror">
                        @php
                            $statuses = ['A_LANCER', 'PLANIFIER', 'EN_COURS', 'TERMINER', 'ANNULER', 'PAUSE'];
                        @endphp
                        @foreach ($statuses as $status)
                            <option value="{{ $status }}"
                                {{ old('statut', $avancement->statut) === $status ? 'selected' : '' }}>
                                {{ ucfirst(strtolower(str_replace('_', ' ', $status))) }}
                            </option>
                        @endforeach
                    </select>
                    @error('statut')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Reste à faire</label>
                    <input class="form-control mb-1 @error('reste_a_faire') is-invalid @enderror" type="number"
                        placeholder="Reste à faire" name="reste_a_faire" value="{{ old('reste_a_faire',$avancement->reste_a_faire)  }}">
                    @error('reste_a_faire')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>


                <div class="mb-3">
                    <label class="form-label">Commentaire</label>
                    <textarea name="commentaire" placeholder="Commentaire"
                        class="form-control mb-1 @error('commentaire') is-invalid @enderror" id="commentaire">{{ old('commentaire',$avancement->reste_a_faire) }}</textarea>
                    @error('commentaire')
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
