<form id="todo-form" action="{{ route('global.add_configuration_risque_cause.post') }}" method="POST">
    @csrf
    <div class="card">
        @if (session('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
        @endif
        <div class="card-body">
            <h4 class="mb-0">Causes de risque</h4>
            <hr>
            <label for="" class="mb-2">Libellé du risque</label>

            <div class="row gy-3">
                <div class="col-md-8">
                    <input id="todo-input" type="text" class="form-control" placeholder="Ex: Atteinte à la vie privée de la clientèle">
                </div>
                <div class="col-md-2">
                    <select id="todo-select" class="form-select">
                        <option value="1">Niveau 1</option>
                        <option value="2">Niveau 2</option>
                        <option value="3">Niveau 3</option>
                    </select>
                </div>
                <div class="col-md-2 text-end d-grid">
                    <button type="button" onclick="CreateTodo();" class="btn btn-primary">Ajouter</button>
                </div>
            </div>

            <div class="form-row mt-3">
                <div class="col-12">
                    <div id="todo-container"></div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary mt-3">Enregistrer</button>
        </div>
    </div>
</form>