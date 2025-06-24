<form id="category-form" action="{{ route('global.add_configuration_risque_category.post') }}" method="POST">
    @csrf

    <div class="card">
        <div class="card-body">
            <h4 class="mb-0">Catégorie de risque</h4>
            <hr>
            <label for="" class="mb-2">Libellé</label>
            <div class="row gy-3">
                <div class="col-md-10">
                    
                    <input id="category-input" type="text" class="form-control" placeholder="Ex: Financier">
                </div>
                <div class="col-md-2 text-end d-grid">
                    <button type="button" onclick="CreateCategory();" class="btn btn-primary">Ajouter</button>
                </div>
            </div>
            <div class="form-row mt-3">
                <div class="col-12">
                    <div id="category-container"></div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary mt-3">Enregistrer</button>
        </div>
    </div>
</form>