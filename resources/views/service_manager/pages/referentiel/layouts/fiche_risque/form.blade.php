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
                    <form class="row g-3" action="{{ route('service.add_fiche_risque.post', ['uuid' => $service->uuid]) }}" method="POST">
                        @csrf
                        <div class="col-md-4">
                            <label for="part_index" class="form-label">Partie fixe index</label>
                            <input type="text" class="form-control" id="part_index" placeholder="Ex: SEC"
                                name="part_index" required>
                        </div>
                        <div class="col-md-4">
                            <label for="entite" class="form-label">Entité</label>
                            <input type="text" class="form-control" id="entite" name="entite" required>
                        </div>
                        <div class="col-md-4">
                            <label for="departement" class="form-label">Département</label>
                            <input type="text" class="form-control" id="departement" name="departement" required>
                        </div>
                        <div class="col-md-4">
                            <label for="entretiens" class="form-label">Entretien avec</label>
                            <input type="text" class="form-control" id="entretiens" name="entretiens" required>
                        </div>
                        <div class="col-md-4">
                            <label for="version" class="form-label">Version</label>
                            <input type="text" class="form-control" id="version" name="version" required>
                        </div>
                        <div class="col-md-4">
                            <label for="ref_supp" class="form-label">Référence supplémentaire</label>
                            <input type="text" class="form-control" id="ref_supp" name="ref_supp">
                        </div>
                        <h5>Risque métier</h5>
                        <div class="col-md-6">
                            <label for="libelle_risk" class="form-label">Libellé</label>
                            <input type="text" class="form-control" id="validationCustom02" name="libelle_risk" required>
                        </div>
                        <div class="col-md-6">
                            <label for="category" class="form-label">Catégorie du risque</label>
                            <select class="form-select" id="category" name="category" required>
                                <option selected disabled>Choisissez la catégorie</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"> {{ $category->libelle }} </option>
                                @endforeach

                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="macroprocessus" class="form-label">Macroprocessus</label>
                            <select class="form-select" id="macroprocessus" name="macroprocessus" required>
                                <option selected disabled>Choisissez le macroprocessus</option>
                                @foreach ($macroprocessus as $macro)
                                    <option value="{{ $macro->id }}"> {{ $macro->name }} </option>
                                @endforeach

                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="processus" class="form-label">Processus</label>
                            <select class="form-select" id="processus" name="processus" required>
                                <option selected disabled>Choisissez le processus</option>
                                @foreach ($processus as $macro)
                                    <option value="{{ $macro->id }}"> {{ $macro->name }} </option>
                                @endforeach

                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="identified_by" class="form-label">Identifié par </label>
                            <input type="text" class="form-control" id="identified_by" name="identified_by" required>
                        </div>
                        <div class="col-md-12">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" name="description" id="description" required cols="30" rows="5"></textarea>
                        </div>
                        <h5>Causes</h5>
                        <div class="col-md-6">
                            <label for="cause_level_1" class="form-label">Niveau 1 (catégorie Bâle II)</label>
                            <select class="form-select" id="cause_level_1" name="cause_level_1" required>
                                <option selected disabled>Choisissez la cause de niveau 1</option>
                                @foreach ($causes as $cause)
                                @if ($cause->level==1)
                                    <option value="{{ $cause->id }}"> {{ $cause->libelle }} </option>
                                @endif
                                @endforeach

                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="cause_level_2" class="form-label">Niveau 2 (catégorie Bâle II)</label>
                            <select class="form-select" id="cause_level_2" name="cause_level_2" required>
                                <option selected disabled>Choisissez la cause de niveau 2</option>
                                @foreach ($causes as $cause)
                                @if ($cause->level==2)
                                    <option value="{{ $cause->id }}"> {{ $cause->libelle }} </option>
                                @endif
                                @endforeach

                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="cause_level_3" class="form-label">Niveau 3</label>
                            <select class="form-select" id="cause_level_3" name="cause_level_3" required>
                                 <option selected disabled>Choisissez la cause de niveau 3</option>
                                @foreach ($causes as $cause)
                                @if ($cause->level==3)
                                    <option value="{{ $cause->id }}"> {{ $cause->libelle }} </option>
                                @endif
                                @endforeach

                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="frequence" class="form-label">Fréquence de survenance de la
                                cause</label>
                            <select class="form-select" id="frequence" name="frequence" required>
                                <option selected disabled>Choisissez la fréquence</option>
                                <option value="EXTREMEMENT_RARE">Extrêmement rare : moins d'une fois tous les 5 ans
                                </option>
                                <option value="RARE">Rare : moins d'une fois par an</option>
                                <option value="PEU_FREQUENT">Peu fréquent : quelques fois par an (entre 1 et 15 fois
                                    par an)</option>
                                <option value="FREQUENT">Fréquent : quelques fois par mois (entre 16 et 50 fois par an)
                                </option>
                                <option value="TRES_FREQUENT">Très fréquent : quelques fois par semaine (entre 51 et
                                    350 fois par an)</option>
                                <option value="PERMANENT"> </option>

                            </select>
                        </div>
                        <h5>Description de l'impact</h5>
                        <div class="col-md-6">
                            <label for="brut_impact_value" class="form-label">Risque perte brute</label>
                            <input type="number" min="0" class="form-control" id="brut_impact_value"
                                required name="brut_impact_value">
                        </div>

                       
                        <div class="col-md-6">
                            <label for="manque_a_gagner" class="form-label">Manque à gagner</label>
                            <select class="form-select" id="manque_a_gagner" name="manque_a_gagner" required>
                                <option value="">-- Choisir une option --</option>
                                <option value="1">Oui</option>
                                <option value="0">Non</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="consequence_reglementaire" class="form-label">Conséquences réglémentaires</label>
                            <select class="form-select" id="consequence_reglementaire" name="consequence_reglementaire" required>
                                <option value="">-- Choisir une option --</option>
                                <option value="1">Oui</option>
                                <option value="0">Non</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="consequence_juridique" class="form-label">Conséquences juridiques</label>
                            <select class="form-select" id="consequence_juridique" name="consequence_juridique" required>
                                <option value="1">Oui</option>
                                <option value="0">Non</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="consequence_humaine" class="form-label">Conséquences humaines et
                                sociales</label>
                            <select class="form-select" id="consequence_humaine" name="consequence_humaine" required>
                                <option value="1">Oui</option>
                                <option value="0">Non</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="interruption_processus" class="form-label">Interruption de processus</label>
                            <select class="form-select" id="interruption_processus" name="interruption_processus" required>
                                <option value="">-- Choisir une option --</option>
                                <option value="1">Oui</option>
                                <option value="0">Non</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="risque_image" class="form-label">Risque d'image</label>
                            <select class="form-select" id="risque_image" name="risque_image" required>
                                <option value="">-- Choisir une option --</option>
                                <option value="1">Oui</option>
                                <option value="0">Non</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="insatisfaction_client" class="form-label">Insatisfaction client</label>
                            <select class="form-select" id="insatisfaction_client" name="insatisfaction_client" required>
                                <option value="">-- Choisir une option --</option>
                                <option value="1">Oui</option>
                                <option value="0">Non</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="impact_risque_credit" class="form-label">Impact risque de crédit</label>
                            <select class="form-select" id="impact_risque_credit" name="impact_risque_credit" required>
                                <option value="">-- Choisir une option --</option>
                                <option value="1">Oui</option>
                                <option value="0">Non</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="impact_risque_marche" class="form-label">Impact risque de marché</label>
                            <select class="form-select" id="impact_risque_marche" name="impact_risque_marche" required>
                                <option value="">-- Choisir une option --</option>
                                <option value="1">Oui</option>
                                <option value="0">Non</option>
                            </select>
                        </div>
                        <h5>Dispositif de maîtrise des risques</h5>
                        <div class="col-md-12">
                            <label for="description_DMR" class="form-label">Description du dispositif de maîtrise
                                des risques</label>
                            <textarea class="form-control" name="description_DMR" id="description_DMR" required cols="30" rows="5"></textarea>
                        </div>
                        <div class="col-md-6">
                            <label for="appreciation_DMR" class="form-label">Appréciation globale du dispositif de
                                maîtrise des risques</label>
                            <select class="form-select" id="appreciation_DMR" name="appreciation_DMR" required>
                                <option selected disabled>Choisissez l'appréciation</option>
                                <option value="INEXISTANT">Inexistant (moins de 25% de pertes évitées)</option>
                                <option value="INSUFFISANT">Insuffisant (de 25% à 50% de pertes évitées)</option>
                                <option value="ACCEPTABLE">Acceptable (de 50% à 75% de pertes évitées)</option>
                                <option value="CONFORME">Conforme (de 75% à 90% de pertes évitées)</option>
                                <option value="EFFICACE">Efficace (plus de 90% de pertes évitées)</option>
                            </select>
                        </div>

                        <h5>Indicateurs</h5>
                        <div class="col-md-6">
                            <label for="indicateur_exposition" class="form-label">Indicateurs d'exposition</label>
                            <input type="number" min="0" class="form-control" id="indicateur_exposition"
                                required name="indicateur_exposition">
                        </div>
                        <div class="col-md-6">
                            <label for="indicateur_risque_survenu" class="form-label">Indicateurs de risque survenu</label>
                            <input type="number" min="0" class="form-control" id="indicateur_risque_survenu"
                                required name="indicateur_risque_survenu">
                        </div>
                        <div class="col-md-6">
                            <label for="indicateur_risque_avere" class="form-label">Indicateurs de risque avéré</label>
                            <input type="number" min="0" class="form-control" id="indicateur_risque_avere"
                                required name="indicateur_risque_avere">
                        </div>
                        <div class="col-md-6">
                            <label for="indicateur_risque_evite" class="form-label">Indicateurs de risque évité</label>
                            <input type="number" min="0" class="form-control" id="indicateur_risque_evite"
                                required name="indicateur_risque_evite">
                        </div>
                        <div class="col-md-6">
                            <label for="risque_a_piloter" class="form-label">Risque à piloter</label>
                            <select class="form-select" id="risque_a_piloter" name="risque_a_piloter" required>
                                <option value="">-- Choisir une option --</option>
                                <option value="1">Oui</option>
                                <option value="0">Non</option>
                            </select>
                        </div>
                        <div class="col-md-6" id="kri-create-choice" style="display: none;">
                            <label for="validationSelectKRI" class="form-label">Indicateur risque (KRI)</label>
                            <select class="form-select" id="validationSelectKRI" name="kri_choice">
                                <option value="">-- Choisir une option --</option>
                                <option value="create">Créer un nouveau</option>
                                <option value="select">Sélectionner un indicateur risque</option>
                            </select>
                        </div>
                        <div id="kri-create-form" style="display: none;" class="mt-3 col-md-12">
                            <div class="row">
                                <h6>KRI</h6>
                                <div class="col-md-6">
                                    <label for="kri_departement" class="form-label">Département</label>
                                    <input type="text" class="form-control" id="kri_departement"
                                        name="kri_departement">
                                </div>
                                <div class="col-md-6">
                                    <label for="kri_libelle" class="form-label">Libellé</label>
                                    <input type="text" class="form-control" id="kri_libelle"
                                        name="kri_libelle">
                                </div>
                                <div class="col-md-6">
                                    <label for="kri_type" class="form-label">Type</label>
                                    <input type="text" class="form-control" id="kri_type"
                                        name="kri_type">
                                </div>
                                <div class="col-md-6">
                                    <label for="kri_precision_indicateur" class="form-label">Précisions indicateur</label>
                                    <input type="text" class="form-control" id="kri_precision_indicateur"
                                        name="kri_precision_indicateur">
                                </div>
                                <div class="col-md-6">
                                    <label for="kri_source" class="form-label">Source</label>
                                    <input type="text" class="form-control" id="kri_source"
                                        name="kri_source">
                                </div>
                                <div class="col-md-6">
                                    <label for="kri_chemin_access" class="form-label">Chemin d'accès</label>
                                    <input type="text" class="form-control" id="kri_chemin_access"
                                        name="kri_chemin_access">
                                </div>
                                <div class="col-md-6">
                                    <label for="kri_periodicite" class="form-label">Périodicité</label>
                                    <input type="text" class="form-control" id="kri_periodicite"
                                        name="kri_periodicite">
                                </div>
                                <div class="col-md-6">
                                    <label for="kri_type_seuil" class="form-label">Type de seuil</label>
                                    <input type="text" class="form-control" id="kri_type_seuil"
                                        name="kri_type_seuil">
                                </div>
                                <div class="col-md-6">
                                    <label for="kri_seuil_alerte" class="form-label">Seuil d'alerte</label>
                                    <input type="text" class="form-control" id="kri_seuil_alerte"
                                        name="kri_seuil_alerte">
                                </div>
                                <div class="col-md-6">
                                    <label for="kri_valeur_actuelle" class="form-label">Valeur actuelle</label>
                                    <input type="text" class="form-control" id="kri_valeur_actuelle"
                                        name="kri_valeur_actuelle">
                                </div>
                                <div class="col-md-12">
                                    <label for="kri_commentaire" class="form-label">Commentaires</label>
                                    <textarea class="form-control" name="kri_commentaire" id="kri_commentaire" cols="30" rows="5"></textarea>
                                </div>
                            </div>
                        </div>


                        <div id="kri-select-field" style="display: none;" class="mt-3">
                            <label for="existingKRI" class="form-label">Choisir un indicateur (KRI) existant</label>
                            <select class="form-select" id="existingKRI" name="kri_existing">
                                <option value="kri1">Indicateur 1</option>
                                <option value="kri2">Indicateur 2</option>
                            </select>
                        </div>

                        <h5>Plan de réductions des risques</h5>

                        <div class="col-md-6">
                            <label for="action_maitrise_risque" class="form-label">Action de maîtrise de risques</label>
                            <select class="form-select" id="action_maitrise_risque" name="action_maitrise_risque" required>
                                <option value="">-- Choisir une option --</option>
                                <option value="1">Oui</option>
                                <option value="0">Non</option>
                            </select>
                        </div>
                        <div class="col-md-6" id="pa-create-choice" style="display: none;">
                            <label for="validationSelectPA" class="form-label">Plan d'action</label>
                            <select class="form-select" id="validationSelectPA" name="pa_choice" required>
                                <option value="">-- Choisir une option --</option>
                                <option value="create_pa">Créer un nouveau</option>
                                <option value="select_pa">Sélectionner un plan d'action</option>
                            </select>
                        </div>
                        <div id="pa-create-form" style="display: none;" class="mt-3 col-md-12">
                            <div class="row">
                                <h6>Plan d'action</h6>
                                <div class="col-md-6">
                                    <label for="pa_type" class="form-label">Type de plan d'action</label>
                                    <select class="form-select" id="pa_type" name="pa_type">
                                         <option selected disabled>Choisissez le type</option>
                                        <option value="Atténuation">Atténuation</option>
                                        <option value="Prévention">Prévention</option>
                                        <option value="Atténuation et Prévention"> Atténuation et Prévention</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="pa_priorite" class="form-label">Priorité</label>
                                    <select class="form-select" id="pa_priorite" name="pa_priorite">
                                         <option selected disabled>Choisissez le niveau de priorité</option>
                                        <option value="Forte">Forte</option>
                                        <option value="Moyenne">Moyenne</option>
                                        <option value="Faible">Faible</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="pa_responsable" class="form-label">Responsable</label>
                                    <input type="text" class="form-control" id="pa_responsable"
                                        name="pa_responsable">
                                </div>
                                 <div class="col-md-6">
                                    <label for="pa_statut" class="form-label">Statut</label>
                                    <select class="form-select" id="pa_statut" name="pa_statut">
                                         <option selected disabled>Choisissez le statut</option>
                                        <option value="A_LANCER">A lancer</option>
                                        <option value="PLANIFIER">Planifié</option>
                                        <option value="EN_COURS">En cours</option>
                                        <option value="TERMINER">Terminé</option>
                                        <option value="PAUSE">En pause</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="pa_date_debut" class="form-label">Date de début prévue</label>
                                    <input type="date" class="form-control" id="pa_date_debut"
                                        name="pa_date_debut">
                                </div>
                                <div class="col-md-6">
                                    <label for="pa_date_fin" class="form-label">Date de fin prévue</label>
                                    <input type="date" class="form-control" id="pa_date_fin"
                                        name="pa_date_fin">
                                </div>
                                
                                <div class="col-md-12">
                                    <label for="pa_description" class="form-label">Description</label>
                                    <textarea class="form-control" name="pa_description" id="pa_description" cols="30" rows="5"></textarea>
                                </div>
                            </div>
                        </div>


                        <div id="pa-select-field" style="display: none;" class="mt-3">
                            <label for="existingPA" class="form-label">Choisir un plan d'action existant</label>
                            <select class="form-select" id="existingPA" name="pa_existing">
                                <option value="kri1">Indicateur 1</option>
                                <option value="kri2">Indicateur 2</option>
                            </select>
                        </div>

                        <div class="col-md-12">
                            <label for="other_informations" class="form-label">Autres informations du risque</label>
                            <textarea class="form-control" name="other_informations" id="other_informations" cols="30" rows="5"></textarea>
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
