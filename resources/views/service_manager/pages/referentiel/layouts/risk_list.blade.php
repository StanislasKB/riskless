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
                                @if (!Auth::user()->hasRole('admin') && !Auth::user()->hasRole('owner') && $fiche_risque->creator->account->id != Auth::user()->account->id)
                                    -
                                @else
                                    <div class="dropdown dropstart position-static">
                                        <a class="btn split-bg-primary" data-bs-toggle="dropdown"
                                            aria-expanded="false">
                                            <i class="bx bx-dots-horizontal-rounded font-24"></i>
                                        </a>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="{{ route('service.detail_fiche_risque.view', ['id' => $fiche_risque->id,'uuid'=>$service->uuid]) }}">Détails</a></li>
                                            @if (Auth::user()->hasRole('admin') || Auth::user()->hasRole('owner') || $fiche_risque->creator->id == Auth::id)
                                            <li><a class="dropdown-item" href="{{ route('service.edit_fiche_risque.view', ['id' => $fiche_risque->id,'uuid'=>$service->uuid]) }}">Modifier</a></li>
                                            <li><a class="dropdown-item text-danger" href="{{ route('service.delete_fiche_risque.view',['id' => $fiche_risque->id,'uuid'=>$service->uuid]) }}">Supprimer</a></li>
                                            @endif
                                            
                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>
                                            @if (Auth::user()->hasRole('admin') || Auth::user()->hasRole('owner') || Auth::user()->can('validate risk'))
                                            <li><a class="dropdown-item" href="{{ route('service.validate_fiche_risque.get', ['id' => $fiche_risque->id,'uuid'=>$service->uuid]) }}">Valider</a></li>
                                            @endif
                                        </ul>
                                    </div>
                                @endif

                            </td>
                        </tr>
                    @empty
                    @endforelse

                </tbody>
            </table>
        </div>
    </div>
</div>
