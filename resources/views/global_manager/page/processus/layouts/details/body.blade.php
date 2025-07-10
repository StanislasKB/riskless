<div class="card">

    <div class="card-body">
        <h4 class="mb-0">Détails processus - {{ $processus->name}}</h4>
        <hr>


        <div class="row gy-3">
            <div class="col-md-4">
                <span class="mb-4 fw-bold">Processus</span>
                <div class="mt-2">{{ $processus->name }}</div>
            </div>
            <div class="col-md-4">
                <span class="mb-4 fw-bold">Domaine</span>
                <div class="mt-2">{{ $processus->domaine ?? '-' }}</div>
            </div>
            <div class="col-md-4">
                <span class="mb-4 fw-bold">Macroprocessus</span>
                <div class="mt-2">{{ $processus->macroprocessus->name }}</div>
            </div>
            <div class="col-md-4">
                <span class="mb-4 fw-bold">Intervenant</span>
                <div class="mt-2">{{ $processus->intervenant }}</div>
            </div>
            <div class="col-md-4">
                <span class="mb-4 fw-bold">Procédure</span>
                <div class="mt-2">{{ $processus->procedure }}</div>
            </div>

            <div class="col-md-4">
                <span class="mb-4 fw-bold">Pilote</span>
                <div class="mt-2">{{ $processus->pilote }}</div>
            </div>
            <div class="col-md-4">
                <span class="mb-4 fw-bold">Contrôle interne </span>
                <div class="mt-2">{{ $processus->controle_interne }}</div>
            </div>
            <div class="col-md-4">
                <span class="mb-4 fw-bold">Périodicité </span>
                <div class="mt-2">{{ $processus->periodicite ?? '-' }}</div>
            </div>
            <div class="col-md-4">
                <span class="mb-4 fw-bold">Piste d'audit</span>
                <div class="mt-2">{{ $processus->piste_audit ?? '-' }}</div>
            </div>
            <div class="col-md-4">
                <span class="mb-4 fw-bold">Indicateur performance</span>
                <div class="mt-2">{{ $processus->indicateur_performance ?? '-' }}</div>
            </div>
            <div class="col-md-4">
                <span class="mb-4 fw-bold">Actif</span>
                <div class="mt-2">{{ $processus->actif ?? '-' }}</div>
            </div>
            <div class="col-md-12">
                <span class="mb-4 fw-bold">Description</span>
                <div class="mt-2">{{ $processus->description }}</div>
            </div>
        </div>
    </div>
</div>
</div>
