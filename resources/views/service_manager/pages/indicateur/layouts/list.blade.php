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
            <h4 class="mb-0">Liste des indicateurs</h4>
        </div>
        <hr />
        <div class="table-responsive">
            <table id="indicateur_list" class="table table-striped">
                <thead>
                    <tr>
                        <th>Index</th>
                        <th>Libellé</th>
                        <th>Département</th>
                        <th>Seuil</th>
                        <th>Valeur actuelle</th>
                        <th>Tendance</th>
                        <th>Statut</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($indicateurs as $indicateur)
                        <tr>
                            <td>{{ $indicateur->index }}</td>
                            <td>{{ $indicateur->libelle }}</td>
                            <td>{{ $indicateur->departement }}</td>
                            <td>
                                <{{ $indicateur->seuil_alerte }}% </td>
                            <td>{{ $indicateur->valeur_actuelle }}%</td>
                            <td>
                                @if ($indicateur->valeur_actuelle > $indicateur->seuil_alerte)
                                    <span>↗</span>
                                @else
                                    <span>↘</span>
                                @endif
                            </td>
                            <td>
                                @if ($indicateur->valeur_actuelle > $indicateur->seuil_alerte)
                                    <span class="badge bg-danger">Critique</span>
                                @else
                                    <span class="badge bg-success">Normal</span>
                                @endif
                            </td>
                            <td>
                                @if (
                                    !Auth::user()->hasRole('admin') &&
                                        !Auth::user()->hasRole('owner') &&
                                        $indicateur->creator->account->id != Auth::user()->account->id)
                                    -
                                @else
                                    <div class="dropdown dropstart position-static">
                                        <a class="btn split-bg-primary" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="bx bx-dots-horizontal-rounded font-24"></i>
                                        </a>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item"
                                                    href="{{ route('service.detail_indicateur.view', ['id' => $indicateur->id, 'uuid' => $service->uuid]) }}">Détails</a>
                                            </li>
                                            @if (Auth::user()->hasRole('admin') || Auth::user()->hasRole('owner') || $indicateur->creator->id == Auth::id)
                                                <li><a class="dropdown-item"
                                                        href="{{ route('service.edit_indicateur.view', ['id' => $indicateur->id, 'uuid' => $service->uuid]) }}">Modifier</a>
                                                </li>
                                                <li><a class="dropdown-item text-danger"
                                                        href="{{ route('service.delete_indicateur.view', ['id' => $indicateur->id, 'uuid' => $service->uuid]) }}">Supprimer</a>
                                                </li>
                                            @endif

                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>
                                            @if (Auth::user()->hasRole('admin') || Auth::user()->hasRole('owner') || Auth::user()->can('validate risk'))
                                                <li><a class="dropdown-item" data-bs-toggle="modal"
                                                        data-bs-target="#evolution-{{ $indicateur->id }}"
                                                        href="#">Ajouter</a>
                                                </li>
                                                <li><a class="dropdown-item"
                                                        href="{{ route('service.graphe_indicateur.view',['id' => $indicateur->id, 'uuid' => $service->uuid]) }}">Graphe</a>
                                                </li>
                                            @endif
                                        </ul>
                                    </div>
                                    @include('service_manager.pages.indicateur.layouts.evolution.evolution_modal')
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
