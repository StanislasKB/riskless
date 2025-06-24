<div class="card">
    <div class="card-body">
        <h4 class="mb-0">Cause risque</h4>
        <hr>
        <div class="row gy-3">
            <div class="col-md-10">
                <input id="todo-input" type="text" class="form-control" value="">
            </div>
            <div class="col-md-2 text-end d-grid">
                <button type="button" onclick="CreateTodo();" class="btn btn-primary">Add todo</button>
            </div>
        </div>
        <div class="form-row mt-3">
            <div class="col-12">
                <div id="todo-container">
                    <div class="pb-3 todo-item" todo-id="0">
                        <div class="input-group">

                            <div class="input-group-text">
                                <input type="checkbox" onchange="TodoChecked(0)"
                                    aria-label="Checkbox for following text input" false="">
                            </div>

                            <input type="text" readonly="" class="form-control false "
                                aria-label="Text input with checkbox" value="take out the trash">

                            <button todo-id="0" class="btn btn-outline-secondary bg-danger text-white" type="button"
                                onclick="DeleteTodo(this);" id="button-addon2 ">X</button>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
