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
                        <h5 class="mb-0">Liste soumission</h5>
                    </div>
                </div>
                <hr>

                <div class="table-responsive">
                    <table id="response" class="table table-striped table-sm mb-0">
                        <thead>
                            <tr>
                                <th>Utilisateur</th>
                                <th>Quizz</th>
                                <th>Score (/20)</th>
                                <th>Date de soumission</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($responses as $response)
                                <tr>
                                    <td>
                                        <div class="font-weight-bold">{{ $response->user->username }}
                                        </div>
                                    </td>
                                    <td>
                                        {{ \Illuminate\Support\Str::limit($response->quizz->title ?? 'N/A', 30) ?? 'N/A' }}
                                    </td>
                                    <td>
                                        {{ $response->score ?? 'N/A' }}
                                    </td>
                                    <td>
                                        {{ $response->created_at->format('d/m/Y') }}
                                    </td>
                                    <td>
                                        @switch($response->status)
                                            @case('IN_PROGRESS')
                                                <span class="badge bg-warning">En attente</span>
                                            @break

                                            @case('VALIDATED')
                                                <span class="badge bg-success">Validé</span>
                                            @break

                                            @default
                                        @endswitch
                                    </td>

                                    <td>
                                        @if (!Auth::user()->hasRole('admin') && !Auth::user()->hasRole('owner') && $category->creator->id != Auth::id)
                                            -
                                        @else
                                            <a class="btn split-bg-primary" data-bs-toggle="dropdown"> <i
                                                    class='bx bx-dots-horizontal-rounded font-24 '></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-end">
                                                <a class="dropdown-item"
                                                    href="{{ Storage::url($response->document_url) }}">
                                                    Télécharger
                                                </a>
                                                @if (!$response->score)
                                                    <a class="dropdown-item" data-bs-toggle="modal"
                                                    data-bs-target="#submitScore-{{ $response->id }}"
                                                    href="#">
                                                    Noter
                                                </a>
                                                @endif
                                                
                                            </div>
                                            @include('global_manager.page.response.layouts.modal.note')
                                        @endif

                                    </td>

                                </tr>
                                @empty
                                    <tr>
                                        <td colspan="100%">
                                            <h4>Aucune categorie de risque actuellement</h4>
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
