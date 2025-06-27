<form id="macro-form" action="{{ route('global.add_configuration_macroprocessus.post') }}" method="POST">
    @csrf
    <div class="card">
        <div class="card-body">
            <h4 class="mb-0">Macroprocessus</h4>
            <hr>

            <div class="row gy-3">
                <div class="col-md-5">
                    <label for="macro-name">Nom</label>
                    <input id="macro-name" type="text" class="form-control" placeholder="Ex: Gestion des finances">
                </div>
                <div class="col-md-5">
                    <label for="macro-entite">Entité</label>
                    <input id="macro-entite" type="text" class="form-control" placeholder="Ex: Département Finance">
                </div>
                <div class="col-md-2 text-end d-grid">
                    <label class="invisible">Ajouter</label>
                    <button type="button" onclick="CreateMacro();" class="btn btn-primary">Ajouter</button>
                </div>
            </div>

            <div class="form-row mt-3">
                <div class="col-12">
                    <div id="macro-container"></div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary mt-3">Enregistrer</button>
        </div>
    </div>
</form>