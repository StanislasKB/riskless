<div class="row">
    <div class="col-xl-9 mx-auto">
        <h6 class="mb-0 text-uppercase">Processus</h6>
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
                    <form class="row g-3" action="{{ route('global.update_processus.post',['id'=>$processus->id]) }}" method="POST">
                        @csrf
                        <div class="col-md-4">
                            <label for="validationCustom01" class="form-label">Nom du processus</label>
                            <input type="text" class="form-control" id="validationCustom01" placeholder="Ex: Gestion des achats banque" name="name"
                                required value="{{ $processus->name }}"  >
                        </div>
                        <div class="col-md-4">
                            <label for="validationCustom02" class="form-label">Domaine</label>
                            <input type="text" class="form-control" id="validationCustom02" placeholder="Ex : Moyens Généraux" name="domaine"
                                required value="{{ $processus->domaine }}">
                        </div>
                       <div class="col-md-4">
                            <label for="validationCustom04" class="form-label">Macroprocessus</label>
                            <select class="form-select" id="validationCustom04" name="macroprocessus" required>
                                <option selected disabled >Choisissez le macroprocessus</option>
                                @foreach ($macroprocessus as $macro)
                                    <option value="{{ $macro->id }}"@if  ($processus->macroprocessus_id==$macro->id) selected @endif> {{ $macro->name }} </option>
                                @endforeach
                                
                            </select>
                        </div>
                         <div class="col-md-12">
                            <label for="validationCustom006" class="form-label">Description</label>
                            <textarea class="form-control" name="description" id="validationCustom006" required cols="30" rows="5">{{ $processus->description }}</textarea>
                        </div>
                        <div class="col-md-4">
                            <label for="validationCustom03" class="form-label">Intervenants</label>
                            <input type="text" class="form-control" id="validationCustom03" required placeholder="Ex: Métiers, RAF, DG" name="intervenant" value="{{ $processus->intervenant }}">
                        </div>
                        
                        <div class="col-md-4">
                            <label for="validationCustom05" class="form-label">Procédure</label>
                            <input type="text" class="form-control" id="validationCustom05" required name="procedure" value="{{ $processus->procedure }}">
                        </div>
                        <div class="col-md-4">
                            <label for="validationCustom005" class="form-label">Pilote</label>
                            <input type="text" class="form-control" id="validationCustom005" required name="pilote" placeholder="Ex: RAF" value="{{ $processus->pilote }}">
                        </div>
                        <div class="col-md-4">
                            <label for="validationCustom0005" class="form-label">Contôle interne</label>
                            <input type="text" class="form-control" id="validationCustom0005" required name="controle_interne" placeholder="Ex : Hiérarchique" value="{{ $processus->controle_interne }}">
                        </div>
                        <div class="col-md-4">
                            <label for="validationCustom0014" class="form-label">Périodicité</label>
                            <input type="text" class="form-control" id="validationCustom0014" required name="periodicite" value="{{ $processus->periodicite }}">
                        </div>
                        <div class="col-md-4">
                            <label for="validationCustom0012" class="form-label">Piste audit</label>
                            <input type="text" class="form-control" id="validationCustom0012" required name="piste_audit" value="{{ $processus->piste_audit }}">
                        </div>
                        <div class="col-md-4">
                            <label for="validationCustom0011" class="form-label">Indicateur performance</label>
                            <input type="text" class="form-control" id="validationCustom0011" required name="indicateur_performance" value="{{ $processus->indicateur_performance }}">
                        </div>
                        <div class="col-md-4">
                            <label for="validationCustom0016" class="form-label">Actif</label>
                            <input type="text" class="form-control" id="validationCustom0016" required name="actif" placeholder="Ex: ORION" value="{{ $processus->actif }}">
                        </div>
                        <div class="col-md-12">
                            <label for="validationCustom0006" class="form-label">Commentaire</label>
                            <textarea class="form-control" name="commentaire" id="validationCustom0006" required cols="30" rows="5">{{ $processus->commentaire }}</textarea>
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
