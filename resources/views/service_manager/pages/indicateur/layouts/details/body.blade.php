<div class="card">

    <div class="card-body">
        <h4 class="mb-0">Détails indicateur {{ $indicateur->index }}</h4>
        <hr>


        <div class="row gy-3">
            <h5>Risque</h5>
            <div class="col-md-4">
                <span class="mb-4 fw-bold">Index</span>
                <div class="mt-2">{{ $indicateur->fiche_risques()->first()->index ?? 'NA'}}</div>
            </div>
            <div class="col-md-4">
                <span class="mb-4 fw-bold">Libellé risque</span>
                <div class="mt-2">{{ $indicateur->fiche_risques()->first()->libelle_risk ?? 'NA' }}</div>
            </div>
            <div class="col-md-4">
                <span class="mb-4 fw-bold">Echelle Cotation du risque</span>
                <div class="mt-2">@switch($indicateur->fiche_risques()->first()->echelle_risque )
                        @case('FAIBLE')
                            Faible
                        @break

                        @case('FORT')
                            Fort
                        @break

                        @case('MOYEN')
                            Moyen
                        @break

                        @case('CRITIQUE')
                            Critique
                        @break

                        @case('INACCEPTABLE')
                            Inacceptable
                        @break

                        @default
                          NA
                    @endswitch
                    
                    </div>
            </div>
            
            <hr>
            <h5>Indicateur</h5>

            <div class="col-md-4">
                <span class="mb-4 fw-bold">Index </span>
                <div class="mt-2">{{ $indicateur->index }}</div>
            </div>
            <div class="col-md-4">
                <span class="mb-4 fw-bold">Département </span>
                <div class="mt-2">{{ $indicateur->departement }}</div>
            </div>
            <div class="col-md-4">
                <span class="mb-4 fw-bold">Libellé </span>
                <div class="mt-2">{{ $indicateur->libelle ?? 'NA' }}</div>
            </div>
            <div class="col-md-4">
                <span class="mb-4 fw-bold">Type </span>
                <div class="mt-2">{{ $indicateur->type ?? 'NA' }}</div>
            </div>
            <div class="col-md-4">
                <span class="mb-4 fw-bold">Précisions indicateur</span>
                <div class="mt-2">{{ $indicateur->precision_indicateur ?? 'NA' }}</div>
            </div>
            <div class="col-md-4">
                <span class="mb-4 fw-bold">Source </span>
                <div class="mt-2">{{ $indicateur->source ?? 'NA' }}</div>
            </div>
            <div class="col-md-4">
                <span class="mb-4 fw-bold">Chemin d'accès </span>
                <div class="mt-2">{{ $indicateur->chemin_access ?? 'NA' }}</div>
            </div>
            <div class="col-md-4">
                <span class="mb-4 fw-bold">Périodicité </span>
                <div class="mt-2">{{ $indicateur->periodicite ?? 'NA' }}</div>
            </div>
            <div class="col-md-4">
                <span class="mb-4 fw-bold">Type de seuil</span>
                <div class="mt-2">{{ $indicateur->type_seuil ?? 'NA' }}</div>
            </div>
            <div class="col-md-4">
                <span class="mb-4 fw-bold">Seuil d'alerte</span>
                <div class="mt-2">{{ $indicateur->seuil_alerte ?? 'NA' }}</div>
            </div>
            <div class="col-md-4">
                <span class="mb-4 fw-bold">Valeur actuelle</span>
                <div class="mt-2">{{ $indicateur->valeur_actuelle ?? 'NA' }}</div>
            </div>
            <div class="col-md-4">
                <span class="mb-4 fw-bold">Date de la dernière valeur</span>
                <div class="mt-2">{{ Carbon\Carbon::parse($indicateur->date_maj_valeur)->format('d/m/Y') ?? 'NA' }}</div>
            </div>
            <div class="col-md-12">
                <span class="mb-4 fw-bold">Commentaire</span>
                <div class="mt-2">{{ $indicateur->commentaire ?? 'NA' }}</div>
            </div>
        </div>

    </div>



</div>
</div>
