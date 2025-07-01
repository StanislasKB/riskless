<div class="card radius-15 w-100">
    <div class="card-body">
        <div class="card-title">
            <div class="d-flex">
                <h5 class="mb-0 h2">Incidents</h5>
                <button class="btn btn-primary ms-auto" data-bs-toggle="modal" data-bs-target="#addIncidentModal">Ajouter un incident</button>
            </div>

        </div>
        <hr />
        @include('service_manager.pages.incident.layouts.incident_list')
    </div>
</div>
