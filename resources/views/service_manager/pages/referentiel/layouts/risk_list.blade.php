<div class="card">
    <div class="card-body">
        <div class="card-title">
            <h4 class="mb-0">Liste des risques</h4>
        </div>
        <hr />
        <div class="table-responsive">
            <table id="risk_list" class="table table-striped">
                <thead>
                    <tr>
                        <th>Index</th>
                        <th>Libellé</th>
                        <th>Description</th>
                        <th>Département</th>
                        <th>Statut</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($fiche_risques as $fiche_risque)
                        <tr>
                            <td>{{ $fiche_risque->index }}</td>
                            <td>{{ $fiche_risque->libelle_risk }}</td>
                            <td>{{ \Illuminate\Support\Str::limit($fiche_risque->description, 50) }}</td>
                            <td>{{ $fiche_risque->departement }}</td>
                            <td>
                                @if ($fiche_risque->is_validated)
                                    <span class="badge bg-success">Validé</span>
                                @else
                                    <span class="badge bg-warning">En attente</span>
                                @endif


                            <td>
                                @if (!Auth::user()->hasRole('admin') && !Auth::user()->hasRole('owner') && $fiche_risque->creator->id != Auth::id)
                                    -
                                @else
                                    <a class="btn split-bg-primary" data-bs-toggle="dropdown"> <i
                                            class='bx bx-dots-horizontal-rounded font-24 '></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-end"> 
                                         <a class="dropdown-item"
                                            href="{{ route('global.delete_processus.post', ['id' => $fiche_risque->id]) }}">Détails</a>
                                         <a class="dropdown-item"
                                            href="{{ route('global.delete_processus.post', ['id' => $fiche_risque->id]) }}">Valider</a>
                                        
                                        <a class="dropdown-item"
                                            href="{{ route('global.update_processus.view', ['id' => $fiche_risque->id]) }}">
                                            Modifier
                                        </a>

                                         <div class="dropdown-divider"></div>
                                        <a class="dropdown-item"
                                            href="{{ route('global.delete_processus.post', ['id' => $fiche_risque->id]) }}">Supprimer</a>

                                    </div>
                                @endif

                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="100%">
                                <h4>Aucun risque actuellement</h4>
                            </td>
                        </tr>
                    @endforelse


                </tbody>
            </table>
        </div>
    </div>
</div>
