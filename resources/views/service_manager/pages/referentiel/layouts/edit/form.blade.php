<div class="row">
    <div class="col-xl-9 mx-auto">
        <h6 class="mb-0 text-uppercase">Fiche risque</h6>
        <hr>
        <div class="card">
            @if (session('success'))
                <div class="alert alert-success" role="alert">
                    {{ session('success') }}
                </div>
            @endif


            @foreach ($errors->all() as $error)
                <div class="alert alert-danger" role="alert">{{ $error }}</div>
            @endforeach
            <div class="card-body">
                <div class="p-4 border rounded">
                    <form class="row g-3"
                        action="{{ route('service.edit_fiche_risque.post', ['id' => $fiche_risque->id, 'uuid' => $service->uuid]) }}"
                        method="POST">
                        @csrf
                        <div class="col-md-4">
                            <label for="part_index" class="form-label">Partie fixe index</label>
                            <input type="text" class="form-control" id="part_index" placeholder="Ex: SEC"
                                name="index" value="{{ $fiche_risque->index }}" required>
                        </div>
                        <div class="col-md-4">
                            <label for="entite" class="form-label">Entité</label>
                            <input type="text" class="form-control" id="entite"
                                value="{{ $fiche_risque->entite }}" name="entite" required>
                        </div>
                        <div class="col-md-4">
                            <label for="departement" class="form-label">Département</label>
                            <input type="text" class="form-control" id="departement" name="departement"
                                value="{{ $fiche_risque->departement }}" required>
                        </div>
                        <div class="col-md-4">
                            <label for="entretiens" class="form-label">Entretien avec</label>
                            <input type="text" class="form-control" id="entretiens"
                                value="{{ $fiche_risque->entretiens }}" name="entretiens" required>
                        </div>
                        <div class="col-md-4">
                            <label for="version" class="form-label">Version</label>
                            <input type="text" class="form-control" id="version"
                                value="{{ $fiche_risque->version }}" name="version" required>
                        </div>
                        <div class="col-md-4">
                            <label for="ref_supp" class="form-label">Référence supplémentaire</label>
                            <input type="text" class="form-control" id="ref_supp" name="ref_supp"
                                value="{{ $fiche_risque->ref_supp }}">
                        </div>
                        <h5>Risque métier</h5>
                        <div class="col-md-6">
                            <label for="libelle_risk" class="form-label">Libellé</label>
                            <input type="text" class="form-control" id="validationCustom02" name="libelle_risk"
                                required value="{{ $fiche_risque->libelle_risk }}">
                        </div>
                        <div class="col-md-6">
                            <label for="category" class="form-label">Catégorie du risque</label>
                            <select class="form-select" id="category" name="category" required>
                                <option selected disabled>Choisissez la catégorie</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"
                                        @if ($category->id == $fiche_risque->category_id) selected @endif> {{ $category->libelle }}
                                    </option>
                                @endforeach

                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="macroprocessus" class="form-label">Macroprocessus</label>
                            <select class="form-select" id="macroprocessus" name="macroprocessus" required>
                                <option selected disabled>Choisissez le macroprocessus</option>
                                @foreach ($macroprocessus as $macro)
                                    <option value="{{ $macro->id }}"
                                        @if ($macro->id == $fiche_risque->macroprocessus_id) selected @endif> {{ $macro->name }}
                                    </option>
                                @endforeach

                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="processus" class="form-label">Processus</label>
                            <select class="form-select" id="processus" name="processus" required>
                                <option selected disabled>Choisissez le processus</option>
                                @foreach ($processus as $macro)
                                    <option value="{{ $macro->id }}"
                                        @if ($macro->id == $fiche_risque->processus_id) selected @endif> {{ $macro->name }}
                                    </option>
                                @endforeach

                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="identified_by" class="form-label">Identifié par </label>
                            <input type="text" class="form-control" id="identified_by" name="identified_by"
                                value="{{ $fiche_risque->identified_by }}" required>
                        </div>
                        <div class="col-md-12">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" name="description" id="description" required cols="30" rows="5">{{ $fiche_risque->description }}</textarea>
                        </div>
                        <h5>Causes</h5>
                        <div class="col-md-6">
                            <label for="cause_level_1" class="form-label">Niveau 1 (catégorie Bâle II)</label>
                            <select class="form-select" id="cause_level_1" name="cause_level_1" required>
                                <option selected disabled>Choisissez la cause de niveau 1</option>
                                @foreach ($causes as $cause)
                                    @if ($cause->level == 1)
                                        <option value="{{ $cause->id }}"
                                            @if ($cause->id == json_decode($fiche_risque->risk_cause, true)['level_1']) selected @endif> {{ $cause->libelle }}
                                        </option>
                                    @endif
                                @endforeach

                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="cause_level_2" class="form-label">Niveau 2 (catégorie Bâle II)</label>
                            <select class="form-select" id="cause_level_2" name="cause_level_2" required>
                                <option selected disabled>Choisissez la cause de niveau 2</option>
                                @foreach ($causes as $cause)
                                    @if ($cause->level == 2)
                                        <option value="{{ $cause->id }}"
                                            @if ($cause->id == json_decode($fiche_risque->risk_cause, true)['level_2']) selected @endif> {{ $cause->libelle }}
                                        </option>
                                    @endif
                                @endforeach

                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="cause_level_3" class="form-label">Niveau 3</label>
                            <select class="form-select" id="cause_level_3" name="cause_level_3" required>
                                <option selected disabled>Choisissez la cause de niveau 3</option>
                                @foreach ($causes as $cause)
                                    @if ($cause->level == 3)
                                        <option value="{{ $cause->id }}"
                                            @if ($cause->id == json_decode($fiche_risque->risk_cause, true)['level_3']) selected @endif> {{ $cause->libelle }}
                                        </option>
                                    @endif
                                @endforeach

                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="frequence" class="form-label">Fréquence de survenance de la
                                cause</label>
                            <select class="form-select" id="frequence" name="frequence" required>
                                <option selected disabled>Choisissez la fréquence</option>
                                <option value="EXTREMEMENT_RARE" @if ('EXTREMEMENT_RARE' == $fiche_risque->frequence) selected @endif>
                                    Extrêmement rare : moins d'une fois tous les 5 ans
                                </option>
                                <option value="RARE" @if ('RARE' == $fiche_risque->frequence) selected @endif>Rare : moins
                                    d'une fois par an</option>
                                <option value="PEU_FREQUENT" @if ('PEU_FREQUENT' == $fiche_risque->frequence) selected @endif>Peu
                                    fréquent : quelques fois par an (entre 1 et 15 fois
                                    par an)</option>
                                <option value="FREQUENT" @if ('FREQUENT' == $fiche_risque->frequence) selected @endif>Fréquent :
                                    quelques fois par mois (entre 16 et 50 fois par an)
                                </option>
                                <option value="TRES_FREQUENT" @if ('TRES_FREQUENT' == $fiche_risque->frequence) selected @endif>Très
                                    fréquent : quelques fois par semaine (entre 51 et
                                    350 fois par an)</option>
                                <option value="PERMANENT" @if ('PERMANENT' == $fiche_risque->frequence) selected @endif>
                                    Permanent : quelques fois par jour (plus de 351 fois par an)
                                </option>

                            </select>
                        </div>
                        <h5>Description de l'impact</h5>
                        <div class="col-md-6">
                            <label for="brut_impact_value" class="form-label">Risque perte brute</label>
                            <input type="number" min="0" class="form-control" id="brut_impact_value"
                                required name="brut_impact_value" value="{{ $fiche_risque->brut_impact_value }}">
                        </div>


                        <div class="col-md-6">
                            <label for="manque_a_gagner" class="form-label">Manque à gagner</label>
                            <select class="form-select" id="manque_a_gagner" name="manque_a_gagner" required>
                                <option value="">-- Choisir une option --</option>
                                <option value="1"@if ($fiche_risque->manque_a_gagner) selected @endif>Oui</option>
                                <option value="0" @if (!$fiche_risque->manque_a_gagner) selected @endif>Non</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="consequence_reglementaire" class="form-label">Conséquences
                                réglémentaires</label>
                            <select class="form-select" id="consequence_reglementaire"
                                name="consequence_reglementaire" required>
                                <option value="">-- Choisir une option --</option>
                                <option value="1" @if ($fiche_risque->consequence_reglementaire) selected @endif>Oui</option>
                                <option value="0" @if (!$fiche_risque->consequence_reglementaire) selected @endif>Non</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="consequence_juridique" class="form-label">Conséquences juridiques</label>
                            <select class="form-select" id="consequence_juridique" name="consequence_juridique"
                                required>
                                <option value="1" @if ($fiche_risque->consequence_juridique) selected @endif>Oui</option>
                                <option value="0" @if (!$fiche_risque->consequence_juridique) selected @endif>Non</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="consequence_humaine" class="form-label">Conséquences humaines et
                                sociales</label>
                            <select class="form-select" id="consequence_humaine" name="consequence_humaine" required>
                                <option value="1" @if ($fiche_risque->consequence_humaine) selected @endif>Oui</option>
                                <option value="0" @if (!$fiche_risque->consequence_humaine) selected @endif>Non</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="interruption_processus" class="form-label">Interruption de processus</label>
                            <select class="form-select" id="interruption_processus" name="interruption_processus"
                                required>
                                <option value="">-- Choisir une option --</option>
                                <option value="1" @if ($fiche_risque->interruption_processus) selected @endif>Oui</option>
                                <option value="0" @if (!$fiche_risque->interruption_processus) selected @endif>Non</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="risque_image" class="form-label">Risque d'image</label>
                            <select class="form-select" id="risque_image" name="risque_image" required>
                                <option value="">-- Choisir une option --</option>
                                <option value="1" @if ($fiche_risque->risque_image) selected @endif>Oui</option>
                                <option value="0" @if (!$fiche_risque->risque_image) selected @endif>Non</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="insatisfaction_client" class="form-label">Insatisfaction client</label>
                            <select class="form-select" id="insatisfaction_client" name="insatisfaction_client"
                                required>
                                <option value="">-- Choisir une option --</option>
                                <option value="1" @if ($fiche_risque->insatisfaction_client) selected @endif>Oui</option>
                                <option value="0" @if (!$fiche_risque->insatisfaction_client) selected @endif>Non</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="impact_risque_credit" class="form-label">Impact risque de crédit</label>
                            <select class="form-select" id="impact_risque_credit" name="impact_risque_credit"
                                required>
                                <option value="">-- Choisir une option --</option>
                                <option value="1" @if ($fiche_risque->impact_risque_credit) selected @endif>Oui</option>
                                <option value="0" @if (!$fiche_risque->impact_risque_credit) selected @endif>Non</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="impact_risque_marche" class="form-label">Impact risque de marché</label>
                            <select class="form-select" id="impact_risque_marche" name="impact_risque_marche"
                                required>
                                <option value="">-- Choisir une option --</option>
                                <option value="1" @if ($fiche_risque->impact_risque_marche) selected @endif>Oui</option>
                                <option value="0" @if (!$fiche_risque->impact_risque_marche) selected @endif>Non</option>
                            </select>
                        </div>
                        <h5>Dispositif de maîtrise des risques</h5>
                        <div class="col-md-12">
                            <label for="description_DMR" class="form-label">Description du dispositif de maîtrise
                                des risques</label>
                            <textarea class="form-control" name="description_DMR" id="description_DMR" required cols="30" rows="5">{{ $fiche_risque->description_DMR }}</textarea>
                        </div>
                        <div class="col-md-6">
                            <label for="appreciation_DMR" class="form-label">Appréciation globale du dispositif de
                                maîtrise des risques</label>
                            <select class="form-select" id="appreciation_DMR" name="appreciation_DMR" required>
                                <option selected disabled>Choisissez l'appréciation</option>
                                <option value="INEXISTANT" @if ('INEXISTANT' == $fiche_risque->appreciation_DMR) selected @endif>
                                    Inexistant (moins de 25% de pertes évitées)</option>
                                <option value="INSUFFISANT" @if ('INSUFFISANT' == $fiche_risque->appreciation_DMR) selected @endif>
                                    Insuffisant (de 25% à 50% de pertes évitées)</option>
                                <option value="ACCEPTABLE" @if ('ACCEPTABLE' == $fiche_risque->appreciation_DMR) selected @endif>
                                    Acceptable (de 50% à 75% de pertes évitées)</option>
                                <option value="CONFORME" @if ('CONFORME' == $fiche_risque->appreciation_DMR) selected @endif>Conforme
                                    (de 75% à 90% de pertes évitées)</option>
                                <option value="EFFICACE" @if ('EFFICACE' == $fiche_risque->appreciation_DMR) selected @endif>Efficace
                                    (plus de 90% de pertes évitées)</option>
                            </select>
                        </div>

                        <h5>Indicateurs</h5>
                        <div class="col-md-6">
                            <label for="indicateur_exposition" class="form-label">Indicateurs d'exposition</label>
                            <input type="number" min="0" class="form-control" id="indicateur_exposition"
                                required name="indicateur_exposition"
                                value="{{ $fiche_risque->indicateur_exposition }}">
                        </div>
                        <div class="col-md-6">
                            <label for="indicateur_risque_survenu" class="form-label">Indicateurs de risque
                                survenu</label>
                            <input type="number" min="0" class="form-control" id="indicateur_risque_survenu"
                                required name="indicateur_risque_survenu"
                                value="{{ $fiche_risque->indicateur_risque_survenu }}">
                        </div>
                        <div class="col-md-6">
                            <label for="indicateur_risque_avere" class="form-label">Indicateurs de risque
                                avéré</label>
                            <input type="number" min="0" class="form-control" id="indicateur_risque_avere"
                                required name="indicateur_risque_avere"
                                value="{{ $fiche_risque->indicateur_risque_avere }}">
                        </div>
                        <div class="col-md-6">
                            <label for="indicateur_risque_evite" class="form-label">Indicateurs de risque
                                évité</label>
                            <input type="number" min="0" class="form-control" id="indicateur_risque_evite"
                                required name="indicateur_risque_evite"
                                value="{{ $fiche_risque->indicateur_risque_evite }}">
                        </div>
                        <div class="col-md-6">
                            <label for="risque_a_piloter" class="form-label">Risque à piloter</label>
                            <select class="form-select" id="risque_a_piloter" name="risque_a_piloter" required>
                                <option value="">-- Choisir une option --</option>
                                <option value="1" @if ($fiche_risque->risque_a_piloter) selected @endif>Oui</option>
                                <option value="0" @if (!$fiche_risque->risque_a_piloter) selected @endif>Non</option>
                            </select>
                        </div>

                        <div class="col-md-6" id="kri-create-choice" style="display: none;">
                            <label for="validationSelectKRI" class="form-label">Indicateur risque (KRI)</label>
                            <select class="multiple-select-kri" multiple="multiple" id="validationSelectKRI"
                                name="indicateur[]">

                                 @php
                                    $selectedIds  = $fiche_risque
                                        ->indicateurs()
                                        ->pluck('indicateurs.id')
                                        ->toArray();
                                    $availableIds = $service->indicateursDisponibles(
                                        $fiche_risque->id,
                                    );
                                @endphp

                                @foreach ($availableIds as $kri)
                                    <option value="{{ $kri->id }}"
                                        @if (in_array($kri->id, $selectedIds)) selected @endif>
                                          {{ $kri->libelle }}
                                    </option>
                                @endforeach
                            </select>
                        </div>




                        <h5>Plan de réductions des risques</h5>

                        <div class="col-md-6">
                            <label for="action_maitrise_risque" class="form-label">Action de maîtrise de
                                risques</label>
                            <select class="form-select" id="action_maitrise_risque" name="action_maitrise_risque"
                                required>
                                <option value="">-- Choisir une option --</option>
                                <option value="1" @if ($fiche_risque->action_maitrise_risque) selected @endif>Oui</option>
                                <option value="0" @if (!$fiche_risque->action_maitrise_risque) selected @endif>Non</option>
                            </select>
                        </div>

                        <div class="col-md-6" id="pa-create-choice" style="display: none;">
                            <label for="validationSelectPA" class="form-label">Plan d'action</label>
                            <select class="multiple-select-pa" multiple="multiple" id="validationSelectPA"
                                name="pa[]">
                                @php
                                    $selectedPlanActionIds = $fiche_risque
                                        ->plan_actions()
                                        ->pluck('plan_actions.id')
                                        ->toArray();
                                    $availablePlanActions = $service->planActionsDisponibles(
                                        $fiche_risque->id,
                                    );
                                @endphp

                                @foreach ($availablePlanActions as $pa)
                                    <option value="{{ $pa->id }}"
                                        @if (in_array($pa->id, $selectedPlanActionIds)) selected @endif>
                                        {{ \Illuminate\Support\Str::limit($pa->description, 50) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-12">
                            <label for="other_informations" class="form-label">Autres informations du risque</label>
                            <textarea class="form-control" name="other_informations" id="other_informations" cols="30" rows="5">{{ $fiche_risque->other_informations }}</textarea>
                        </div>
                        <div class="col-12">
                            <button class="btn btn-primary" type="submit">Enregistrer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
