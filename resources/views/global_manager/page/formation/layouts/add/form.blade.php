<div class="row">
    <div class="col-xl-9 mx-auto">
        <h6 class="mb-0 text-uppercase">Formation</h6>
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
                    <form class="row g-3" action="{{ route('global.formation.add.post') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="col-md-12">
                            <label for="validationCustom01" class="form-label">Titre</label>
                            <input type="text" class="form-control" id="validationCustom01" name="title" required>
                        </div>
                        <div class="col-md-6">
                            <label for="validationCustom04" class="form-label">Visibilité</label>
                            <select class="form-select" id="validationCustom04" name="visibility" required>
                                <option selected disabled>Choisissez qui peut voir la formation</option>
                                <option value="ALL">Tout le monde</option>
                                <option value="ONLY_MEMBERS">Collègues uniquement</option>

                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="validationCustom04" class="form-label">Statut</label>
                            <select class="form-select" id="validationCustom04" name="status" required>
                                <option selected disabled>Choisissez le statut</option>
                                <option value="ACTIVE">Actif</option>
                                <option value="INACTIVE">Inactif</option>
                            </select>
                        </div>
                         <div class="col-md-12">
                            <label for="validationCustom006" class="form-label">Courte description (50 mots max)</label>
                            <textarea class="form-control" name="description" id="validationCustom006" required cols="30" rows="5"></textarea>
                        </div>
                        <div class="col-md-6">
                            <label for="formFile" class="form-label">Choisir l'image de couverture</label>
                            <input class="form-control" type="file" id="formFile" accept="image/*"
                                name="formation_img">
                        </div>
                        <div class="col-md-6">
                            <label for="formationFile" class="form-label">Choisir le fichier (pdf)</label>
                            <input class="form-control" type="file" id="formationFile" name="document" accept="pdf">
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
