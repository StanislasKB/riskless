<div class="row">
    <div class="col-12">
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

                <!--end row-->
                <div class="d-flex align-items-center">
                    <div>
                        <h5 class="mb-0">Processus</h5>
                    </div>
                </div>
                <hr>
                <div class="table-responsive">
                    <table id="processus" class="table table-striped table-sm mb-0">
                        <thead>
                            <tr>
                                <th>Entité
                                </th>
                                <th>Domaine</th>
                                <th>Macroprocessus</th>
                                <th>Processus</th>
                                <th>Intervenants</th>
                                <th>Procédure</th>
                                <th>Description</th>
                                <th>Pilote</th>
                                <th>Contrôle interne</th>
                                <th>Périodicité</th>
                                <th>Piste d'audit</th>
                                <th>Indicateur de Performance</th>
                                <th>Actif</th>
                                <th>Commentaire</th>
                                <th>Ajouté par</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($processus as $process)
                                <tr>
                                    <td>
                                        <div class="font-weight-bold">{{ $process->macroprocessus->entite }}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="font-weight-bold">{{ $process->domaine }}</div>
                                    </td>
                                    <td>
                                        <div class="font-weight-bold">{{ $process->macroprocessus->name }}</div>
                                    </td>
                                    <td>
                                        <div class="font-weight-bold">{{ $process->name }}</div>
                                    </td>
                                    <td>
                                        <div class="font-weight-bold">{{ $process->intervenant }}</div>
                                    </td>
                                    <td>
                                        <div class="font-weight-bold">{{ $process->procedure }}</div>
                                    </td>
                                    <td>
                                        <div class="font-weight-bold">{{ $process->description }}</div>
                                    </td>
                                    <td>
                                        <div class="font-weight-bold">{{ $process->pilote }}</div>
                                    </td>
                                    <td>
                                        <div class="font-weight-bold">{{ $process->controle_interne }}</div>
                                    </td>
                                    <td>
                                        <div class="font-weight-bold">{{ $process->periodicite }}</div>
                                    </td>
                                    <td>
                                        <div class="font-weight-bold">{{ $process->piste_audit }}</div>
                                    </td>
                                    <td>
                                        <div class="font-weight-bold">{{ $process->indicateur_performance }}</div>
                                    </td>
                                    <td>
                                        <div class="font-weight-bold">{{ $process->actif }}</div>
                                    </td>
                                    <td>
                                        <div class="font-weight-bold">{{ $process->commentaire }}</div>
                                    </td>
                                    <td>
                                        <div class="font-weight-bold">{{ $process->creator->username }}</div>
                                    </td>

                                    <td>
                                        @if (!Auth::user()->hasRole('admin') && !Auth::user()->hasRole('owner') && $process->creator->id != Auth::id)
                                            -
                                        @else
                                            <a class="btn split-bg-primary" data-bs-toggle="dropdown"> <i
                                                    class='bx bx-dots-horizontal-rounded font-24 '></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-end"> <a
                                                    class="dropdown-item"
                                                    href="{{ route('global.update_processus.view', ['id' => $process->id]) }}">
                                                    Modifier
                                                </a>


                                                <a class="dropdown-item"
                                                    href="{{ route('global.delete_processus.post', ['id' => $process->id]) }}">Supprimer</a>

                                            </div>
                                        @endif

                                    </td>

                                </tr>
                            @empty
                                <tr>
                                    <td colspan="100%">
                                        <h4>Aucun processus actuellement</h4>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>






            </div>
        </div>
    </div>
</div>
