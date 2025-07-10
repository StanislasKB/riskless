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
                        <h5 class="mb-0">Journal des actions</h5>
                    </div>
                </div>
                <hr>
                <div class="table-responsive">
                    <table id="logs" class="table table-striped table-sm mb-0">
                        <thead>
                            <tr>
                                <th>Date
                                </th>
                                <th>Utilisateur</th>
                                <th>Action</th>
                                <th>Description</th>
                                <th>IP</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($logs as $log)
                                <tr>
                                    <td>
                                        <div class="font-weight-bold">{{ $log->created_at->format('d/m/Y H:m') }}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="font-weight-bold">{{ $log->causer->username }}</div>
                                    </td>
                                    <td>
                                        {{ $log->action }}
                                    </td>
                                    <td>
                                        {{ $log->description }}
                                    </td>
                                    <td>{{ $log->properties['ip'] }}</td>
                                    {{-- <td>
                                        @if (!Auth::user()->hasRole('admin') && !Auth::user()->hasRole('owner') && $cause->creator->id != Auth::id)
                                            -
                                        @else
                                            <a class="btn split-bg-primary" data-bs-toggle="dropdown"> <i
                                                    class='bx bx-dots-horizontal-rounded font-24 '></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-end">
                                                <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                                    data-bs-target="#editRiskCause-{{ $cause->id }}">
                                                    Modifier
                                                </a>


                                                <a class="dropdown-item"
                                                    href="{{ route('global.delete_configuration_risque_cause.post', ['id' => $cause->id]) }}">Supprimer</a>

                                            </div>
                                            @include('global_manager.page.configuration.layouts.modals.edit_cause_modal')
                                        @endif

                                    </td> --}}

                                </tr>
                            @empty
                                <tr>
                                    <td colspan="100%">
                                        <h4>Aucune cause de risque actuellement</h4>
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
