<div class="card">

    <div class="card-body">
        <h4 class="mb-0">Détails risque - {{ $fiche_risque->index }}</h4>
        <hr>


        <div class="row gy-3">
            <h5>Risque métier</h5>
            <div class="col-md-4">
                <span class="mb-4 fw-bold">Index</span>
                <div class="mt-2">{{ $fiche_risque->index }}</div>
            </div>
            <div class="col-md-4">
                <span class="mb-4 fw-bold">Ref supp</span>
                <div class="mt-2">{{ $fiche_risque->ref_supp ?? '-' }}</div>
            </div>
            <div class="col-md-4">
                <span class="mb-4 fw-bold">Version</span>
                <div class="mt-2">{{ $fiche_risque->version }}</div>
            </div>
            <div class="col-md-4">
                <span class="mb-4 fw-bold">Mise à jour</span>
                <div class="mt-2">{{ $fiche_risque->updated_at->format('d/m/Y') }}</div>
            </div>
            <div class="col-md-4">
                <span class="mb-4 fw-bold">Libellé du risque</span>
                <div class="mt-2">{{ $fiche_risque->libelle_risk }}</div>
            </div>

            <div class="col-md-4">
                <span class="mb-4 fw-bold">Macroprocessus</span>
                <div class="mt-2">{{ $fiche_risque->macroprocessus->name }}</div>
            </div>
            <div class="col-md-4">
                <span class="mb-4 fw-bold">Processus</span>
                <div class="mt-2">{{ $fiche_risque->processus->name }}</div>
            </div>
            <div class="col-md-4">
                <span class="mb-4 fw-bold">Identifié par </span>
                <div class="mt-2">{{ $fiche_risque->identified_by ?? '-' }}</div>
            </div>
            <div class="col-md-4">
                <span class="mb-4 fw-bold">Catégorie</span>
                <div class="mt-2">{{ $fiche_risque->category->libelle ?? '-' }}</div>
            </div>
            <div class="col-md-12">
                <span class="mb-4 fw-bold">Description</span>
                <div class="mt-2">{{ $fiche_risque->description }}</div>
            </div>
            <hr>
            <h5>Métier</h5>

            <div class="col-md-4">
                <span class="mb-4 fw-bold">Entité </span>
                <div class="mt-2">{{ $fiche_risque->entite }}</div>
            </div>
            <div class="col-md-4">
                <span class="mb-4 fw-bold">Département </span>
                <div class="mt-2">{{ $fiche_risque->departement }}</div>
            </div>
            <div class="col-md-4">
                <span class="mb-4 fw-bold">Service </span>
                <div class="mt-2">{{ $service->name }}</div>
            </div>
            <hr>
            <h5>Validation</h5>
            <div class="col-md-4">
                <span class="mb-4 fw-bold">Rédigée par </span>
                <div class="mt-2">{{ $fiche_risque->creator->username }}</div>
            </div>
            <div class="col-md-4">
                <span class="mb-4 fw-bold">Validée par </span>
                <div class="mt-2">{{ $fiche_risque->validator->username ?? 'En attente' }}</div>
            </div>
            <div class="col-md-4">
                <span class="mb-4 fw-bold">Entretiens avec </span>
                <div class="mt-2">{{ $fiche_risque->entretiens }}</div>
            </div>
            <hr>
            <h5>Causes</h5>
            <div class="col-md-4">
                <span class="mb-4 fw-bold">Niveau 1 (Catégorie Bâle II) </span>
                <div class="mt-2">{{ $fiche_risque->cause_level_one->libelle }}</div>
            </div>
            <div class="col-md-4">
                <span class="mb-4 fw-bold">Niveau 2 (Sous-Catégorie Bâle II) </span>
                <div class="mt-2">{{ $fiche_risque->cause_level_two->libelle }}</div>
            </div>
            <div class="col-md-4">
                <span class="mb-4 fw-bold">Niveau 3 </span>
                <div class="mt-2">{{ $fiche_risque->cause_level_three->libelle }}</div>
            </div>
            <div class="col-md-4">
                <span class="mb-4 fw-bold">Fréquence </span>
                <div class="mt-2">
                    @switch($fiche_risque->frequence)
                        @case('EXTREMEMENT_RARE')
                            Extrêmement rare : moins d'une fois tous les 5 ans
                        @break

                        @case('RARE')
                            Rare : moins d'une fois par an
                        @break

                        @case('PEU_FREQUENT')
                            Peu fréquent : quelques fois par an (entre 1 et 15 fois
                            par an)
                        @break

                        @case('FREQUENT')
                            Fréquent : quelques fois par mois (entre 16 et 50 fois par an)
                        @break

                        @case('TRES_FREQUENT')
                            Très fréquent : quelques fois par semaine (entre 51 et
                            350 fois par an)
                        @break

                        @case('PERMANENT')
                            Permanent : quelques fois par jour (plus de 351 fois par an)
                        @break

                        @default
                    @endswitch
                </div>
            </div>
            <hr>
            <h5>Cotation</h5>
            <div class="col-md-4">
                <span class="mb-4 fw-bold">Perte nette moyenne </span>
                <div class="mt-2">{{ $fiche_risque->net_impact_value }}</div>
            </div>

            <div class="col-md-4">
                <span class="mb-4 fw-bold">Perte nette moyenne categorie</span>
                <div class="mt-2">
                    @switch($fiche_risque->net_impact)
                        @case('FAIBLE')
                            Faible : moins de 10 kXOF
                        @break

                        @case('FORT')
                            Fort : de 1 MXOF à 10 MXOF
                        @break

                        @case('MODERE')
                            Modéré : de 10 kXOF à 100 kXOF
                        @break

                        @case('MOYEN')
                            Moyen : de 100 kXOF à 1 MXOF
                        @break

                        @case('MAJEUR')
                            Majeur : de 10 MXOF à 100 MXOF
                        @break

                        @case('CRITIQUE')
                            Critique : Plus de 100 MXOF
                        @break

                        @default
                    @endswitch
                </div>
            </div>
            <div class="col-md-4">
                <span class="mb-4 fw-bold">Perte nette maximum </span>
                <div class="mt-2">{{ $fiche_risque->brut_impact_value }}</div>
            </div>
            <div class="col-md-4">
                <span class="mb-4 fw-bold">Perte nette maximum categorie</span>
                <div class="mt-2">
                    @switch($fiche_risque->brut_impact)
                        @case('FAIBLE')
                            Faible : moins de 10 kXOF
                        @break

                        @case('FORT')
                            Fort : de 1 MXOF à 10 MXOF
                        @break

                        @case('MODERE')
                            Modéré : de 10 kXOF à 100 kXOF
                        @break

                        @case('MOYEN')
                            Moyen : de 100 kXOF à 1 MXOF
                        @break

                        @case('MAJEUR')
                            Majeur : de 10 MXOF à 100 MXOF
                        @break

                        @case('CRITIQUE')
                            Critique : Plus de 100 MXOF
                        @break

                        @default
                    @endswitch
                </div>
            </div>
            <div class="col-md-4">
                <span class="mb-4 fw-bold">Manque à gagner </span>
                <div class="mt-2">{{ $fiche_risque->manque_a_gagner ? 'Oui' : 'Non' }}</div>
            </div>
            <div class="col-md-4">
                <span class="mb-4 fw-bold">Conséquences réglementaires </span>
                <div class="mt-2">{{ $fiche_risque->consequence_reglementaire ? 'Oui' : 'Non' }}</div>
            </div>
            <div class="col-md-4">
                <span class="mb-4 fw-bold">Conséquences juridiques </span>
                <div class="mt-2">{{ $fiche_risque->consequence_juridique ? 'Oui' : 'Non' }}</div>
            </div>
            <div class="col-md-4">
                <span class="mb-4 fw-bold">Conséquences humaines et sociales </span>
                <div class="mt-2">{{ $fiche_risque->consequence_humaine ? 'Oui' : 'Non' }}</div>
            </div>
            <div class="col-md-4">
                <span class="mb-4 fw-bold">Interruption de processus </span>
                <div class="mt-2">{{ $fiche_risque->interruption_processus ? 'Oui' : 'Non' }}</div>
            </div>
            <div class="col-md-4">
                <span class="mb-4 fw-bold">Risque d'image </span>
                <div class="mt-2">{{ $fiche_risque->risque_image ? 'Oui' : 'Non' }}</div>
            </div>
            <div class="col-md-4">
                <span class="mb-4 fw-bold">Insatisfaction Client </span>
                <div class="mt-2">{{ $fiche_risque->insatisfaction_client ? 'Oui' : 'Non' }}</div>
            </div>
            <div class="col-md-4">
                <span class="mb-4 fw-bold">Impact risque de crédit </span>
                <div class="mt-2">{{ $fiche_risque->impact_risque_credit ? 'Oui' : 'Non' }}</div>
            </div>
            <div class="col-md-4">
                <span class="mb-4 fw-bold">Impact risque de marché </span>
                <div class="mt-2">{{ $fiche_risque->impact_risque_marche ? 'Oui' : 'Non' }}</div>
            </div>
            <div class="col-md-4">
                <span class="mb-4 fw-bold">Echelle de risque/ cotation risque </span>
                <div class="mt-2">
                    @switch($fiche_risque->net_cotation)
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
                    @endswitch
                </div>
            </div>

            <hr>
            <h5>Dispositif de contrôle</h5>
            <div class="col-md-8">
                <span class="mb-4 fw-bold">Description</span>
                <div class="mt-2">{{ $fiche_risque->description_DMR }}</div>
            </div>
            <div class="col-md-4">
                <span class="mb-4 fw-bold">Appréciation</span>
                <div class="mt-2">
                    @switch($fiche_risque->appreciation_DMR)
                        @case('INEXISTANT')
                            Inexistant (moins de 25% de pertes évitées)
                        @break

                        @case('INSUFFISANT')
                            Insuffisant (de 25% à 50% de pertes évitées)
                        @break

                        @case('ACCEPTABLE')
                            Acceptable (de 50% à 75% de pertes évitées)
                        @break

                        @case('CONFORME')
                            Conforme (de 75% à 90% de pertes évitées)
                        @break

                        @case('EFFICACE')
                            Efficace (plus de 90% de pertes évitées)
                        @break

                        @default
                    @endswitch
                </div>
            </div>
            <hr>
            <h5>Indicateurs</h5>
            <div class="col-md-4">
                <span class="mb-4 fw-bold">Indicateur(s) de mesure du risque</span>
                <div class="mt-2">{{ $fiche_risque->risque_a_piloter ? 'Oui' : 'Non' }}</div>
            </div>
            @if ($fiche_risque->risque_a_piloter)
                <div class="col-md-4">
                    <span class="mb-4 fw-bold">KRI</span>
                    <div class="mt-2">
                        @foreach ($fiche_risque->indicateurs()->get() as $ind)
                            {{ $ind->index }}
                        @endforeach
                    </div>
                </div>
            @endif
            <div class="col-md-4">
                <span class="mb-4 fw-bold">Indicateurs d'exposition</span>
                <div class="mt-2">{{ $fiche_risque->indicateur_exposition ?? '-' }}</div>
            </div>
            <div class="col-md-4">
                <span class="mb-4 fw-bold">Indicateurs de risque survenu</span>
                <div class="mt-2">{{ $fiche_risque->indicateur_risque_survenu ?? '-' }}</div>
            </div>
            <div class="col-md-4">
                <span class="mb-4 fw-bold">Indicateurs de risque avéré</span>
                <div class="mt-2">{{ $fiche_risque->indicateur_risque_avere?? '-' }}</div>
            </div>
            <div class="col-md-4">
                <span class="mb-4 fw-bold">Indicateurs de risque évité</span>
                <div class="mt-2">{{ $fiche_risque->indicateur_risque_evite ?? '-' }}</div>
            </div>

             <hr>
            <h5>Plan de réduction des risques</h5>
            <div class="col-md-4">
                <span class="mb-4 fw-bold">Action(s) de maîtrise du risque</span>
                <div class="mt-2">{{ $fiche_risque->action_maitrise_risque ? 'Oui' : 'Non' }}</div>
            </div>
            @if ($fiche_risque->action_maitrise_risque)
                <div class="col-md-4">
                    <span class="mb-4 fw-bold">Plan d'actions</span>
                    <div class="mt-2">
                        @foreach ($fiche_risque->plan_actions()->get() as $pa)
                            {{ $pa->index }}
                        @endforeach
                    </div>
                </div>
            @endif

        </div>

    </div>



</div>
</div>
