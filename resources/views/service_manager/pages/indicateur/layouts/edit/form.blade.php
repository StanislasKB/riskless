<div class="row">
    <div class="col-xl-9 mx-auto">
        <h6 class="mb-0 text-uppercase">Indicateur</h6>
        <hr>
        <div class="card">
            @if (session('success'))
                <div class="alert alert-success" role="alert">
                    {{ session('success') }}
                </div>
            @endif


            @foreach ($errors->all() as $error)
                <div class="alert alert-danger" role="alert">{{ $error }}</div>
            @endforeach
            <div class="card-body">
                <div class="p-4 border rounded">
                    <form class="row g-3"
                        action="{{ route('service.edit_indicateur.post', ['uuid' => $service->uuid,'id'=> $indicateur->id]) }}"
                        method="POST">
                        @csrf
                        <div class="col-md-6">
                            <label for="kri_departement" class="form-label">Département</label>
                            <input type="text" class="form-control" id="kri_departement" name="kri_departement" value="{{ $indicateur->departement }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="kri_libelle" class="form-label">Libellé</label>
                            <input type="text" class="form-control" id="kri_libelle" name="kri_libelle" value="{{ $indicateur->libelle  }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="kri_type" class="form-label">Type</label>
                            <input type="text" class="form-control" id="kri_type" name="kri_type" value="{{ $indicateur->type }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="kri_precision_indicateur" class="form-label">Précisions indicateur</label>
                            <input type="text" class="form-control" id="kri_precision_indicateur" value="{{ $indicateur->precision_indicateur }}"
                                name="kri_precision_indicateur" required>
                        </div>
                        <div class="col-md-6">
                            <label for="kri_source" class="form-label">Source</label>
                            <input type="text" class="form-control" id="kri_source" name="kri_source" required value="{{ $indicateur->source }}">
                        </div>
                        <div class="col-md-6">
                            <label for="kri_chemin_access" class="form-label">Chemin d'accès</label>
                            <input type="text" class="form-control" id="kri_chemin_access" name="kri_chemin_access" value="{{ $indicateur->chemin_access }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="kri_periodicite" class="form-label">Périodicité</label>
                            <input type="text" class="form-control" id="kri_periodicite" name="kri_periodicite" value="{{ $indicateur->periodicite }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="kri_type_seuil" class="form-label">Type de seuil</label>
                            <input type="text" class="form-control" id="kri_type_seuil" name="kri_type_seuil" value="{{ $indicateur->type_seuil }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="kri_seuil_alerte" class="form-label">Seuil d'alerte</label>
                            <input type="number" step="0.01" class="form-control" id="kri_seuil_alerte" name="kri_seuil_alerte" value="{{ $indicateur->seuil_alerte }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="kri_valeur_actuelle" class="form-label">Valeur actuelle</label>
                            <input  type="number" step="0.01" class="form-control" id="kri_valeur_actuelle" value="{{ $indicateur->valeur_actuelle }}"
                                name="kri_valeur_actuelle" required>
                        </div>
                        
                        <div class="col-md-12">
                            <label for="kri_commentaire" class="form-label">Commentaires</label>
                            <textarea class="form-control" name="kri_commentaire" id="kri_commentaire" cols="30" rows="5">{{ $indicateur->commentaire }}</textarea>
                        </div>
                        <div class="col-12">
                            <button class="btn btn-primary" type="submit">Enregistrer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
