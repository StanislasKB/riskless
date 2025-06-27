@extends('global_manager.components.app')
@section('title')
    Ajout
@endsection
@section('page_css')
@endsection
@section('main_content')
    @include('global_manager.page.configuration.layouts.add.page_header')
    @include('global_manager.page.configuration.layouts.add.risk_cause')
    @include('global_manager.page.configuration.layouts.add.risk_category')
    @include('global_manager.page.configuration.layouts.add.macroprocessus')
@endsection
@section('page_js')
    
<script>
    let todos = [];

    function CreateTodo() {
        const input = document.getElementById("todo-input");
        const select = document.getElementById("todo-select");

        const text = input.value.trim();
        const niveau = select.value;

        if (!text) return;

        const id = todos.length;

        const todo = {
            id: id,
            text: text,
            niveau: niveau
        };

        todos.push(todo);

        input.value = "";
        select.selectedIndex = 0;

        RenderAllTodos();
    }

    function DeleteTodo(button) {
        const deleteID = parseInt(button.getAttribute("todo-id"));
        todos = todos.filter(todo => todo.id !== deleteID);
        RenderAllTodos();
    }

    function RenderAllTodos() {
        const container = document.getElementById("todo-container");
        container.innerHTML = "";

        for (let todo of todos) {
            const html = `
                <div class="pb-3 todo-item" todo-id="${todo.id}">
                    <div class="input-group">
                        <input type="text" readonly class="form-control" value="${todo.text}">
                        <input type="hidden" name="causes[]" value="${todo.text}">
                        
                        <select class="form-select" disabled>
                            <option${todo.niveau === '1' ? ' selected' : ''}>Niveau 1</option>
                            <option${todo.niveau === '2' ? ' selected' : ''}>Niveau 2</option>
                            <option${todo.niveau === '3' ? ' selected' : ''}>Niveau 3</option>
                        </select>
                        <input type="hidden" name="niveaux[]" value="${todo.niveau}">

                        <button todo-id="${todo.id}" class="btn btn-outline-secondary bg-danger text-white" type="button" onclick="DeleteTodo(this);">X</button>
                    </div>
                </div>
            `;
            const wrapper = document.createElement("div");
            wrapper.innerHTML = html;
            container.appendChild(wrapper.firstElementChild);
        }
    }
</script>

<script>
    let categories = [];  // Liste des catégories
    let currentCategory = {
        text: "",
        id: 0
    };

    // Gestion de l'entrée dans le champ
    document.getElementById("category-input").oninput = function (e) {
        currentCategory.text = e.target.value;
    };

    // Fonction pour afficher tous les éléments
    function RenderAllCategories() {
        const container = document.getElementById("category-container");
        container.innerHTML = "";

        for (let cat of categories) {
            const categoryHTML = `
                <div class="pb-3 category-item" category-id="${cat.id}">
                    <div class="input-group">
                        <input type="text" readonly class="form-control" value="${cat.text}">
                        <input type="hidden" name="categories[]" value="${cat.text}">
                        <button category-id="${cat.id}" class="btn btn-outline-secondary bg-danger text-white" type="button" onclick="DeleteCategory(this);">X</button>
                    </div>
                </div>
            `;
            const wrapper = document.createElement("div");
            wrapper.innerHTML = categoryHTML;
            container.appendChild(wrapper.firstElementChild);
        }
    }

    function CreateCategory() {
        if (!currentCategory.text.trim()) return;

        const newCategory = {
            text: currentCategory.text.trim(),
            id: categories.length
        };

        categories.push(newCategory);
        currentCategory.text = "";
        document.getElementById("category-input").value = "";
        RenderAllCategories();
    }

    function DeleteCategory(button) {
        const deleteID = parseInt(button.getAttribute("category-id"));
        categories = categories.filter(cat => cat.id !== deleteID);
        RenderAllCategories();
    }
</script>
<script>
    let macroprocessus = [];
    let currentMacro = {
        name: "",
        entite: "",
        id: 0
    };

    document.getElementById("macro-name").oninput = function (e) {
        currentMacro.name = e.target.value;
    };
    document.getElementById("macro-entite").oninput = function (e) {
        currentMacro.entite = e.target.value;
    };

    function RenderAllMacroprocessus() {
        const container = document.getElementById("macro-container");
        container.innerHTML = "";

        for (let macro of macroprocessus) {
            const macroHTML = `
                <div class="pb-3 macro-item" macro-id="${macro.id}">
                    <div class="input-group mb-2">
                        <input type="text" readonly class="form-control" value="${macro.name}" placeholder="Nom">
                        <input type="hidden" name="names[]" value="${macro.name}">

                        <input type="text" readonly class="form-control" value="${macro.entite}" placeholder="Entité">
                        <input type="hidden" name="entites[]" value="${macro.entite}">

                        <button macro-id="${macro.id}" class="btn btn-outline-secondary bg-danger text-white" type="button" onclick="DeleteMacro(this);">X</button>
                    </div>
                </div>
            `;
            const wrapper = document.createElement("div");
            wrapper.innerHTML = macroHTML;
            container.appendChild(wrapper.firstElementChild);
        }
    }

    function CreateMacro() {
        const name = currentMacro.name.trim();
        const entite = currentMacro.entite.trim();

        if (!name || !entite) return;

        const newMacro = {
            id: macroprocessus.length,
            name: name,
            entite: entite
        };

        macroprocessus.push(newMacro);
        currentMacro = { name: "", entite: "", id: 0 };
        document.getElementById("macro-name").value = "";
        document.getElementById("macro-entite").value = "";
        RenderAllMacroprocessus();
    }

    function DeleteMacro(button) {
        const deleteID = parseInt(button.getAttribute("macro-id"));
        macroprocessus = macroprocessus.filter(macro => macro.id !== deleteID);
        RenderAllMacroprocessus();
    }
</script>
@endsection
