<div class="card radius-15 w-100">
    <div class="card-body">
        <div class="card-title">
            <div class="d-flex">
                <h5 class="mb-0 h2">Plan d'actions</h5>
                <button class="btn btn-primary ms-auto" data-bs-toggle="modal" data-bs-target="#addActionModal">Ajouter une
                    action</button>
            </div>

        </div>
        <hr />
        @include('service_manager.pages.plan_action.layouts.plan_action_list')
    </div>
</div>
