<div class="row">
    <div class="col-12">
        <div class="card">
            @if (session('success'))
                <div class="alert alert-success" role="alert">
                    {{ session('success') }}
                </div>
            @endif

            @error('error')
                <div class="alert alert-danger" role="alert">{{ $message }}</div>
            @enderror
            @foreach ($errors->all() as $error)
                <div class="alert alert-danger" role="alert">{{ $error }}</div>
            @endforeach
            <div class="card-body">

                <!--end row-->
                <div class="d-flex align-items-center">
                    <div>
                        <h5 class="mb-0">Utilisateurs</h5>
                    </div>
                    <div class="ms-auto"><a href="javascript:;" class="btn btn-sm btn-outline-secondary"
                            data-bs-toggle="modal" data-bs-target="#addServiceUserModal">Ajouter un
                            utilisateur</a>
                        @include('global_manager.page.users.layouts.add_user_modal')
                    </div>
                </div>
                <div class="table-responsive mt-3">
                    <table class="table table-striped table-hover table-sm mb-0">
                        <thead>
                            <tr>
                                <th>Nom<i class='bx bx-up-arrow-alt ms-2'></i>
                                </th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Statut</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($users as $user)
                                <tr>
                                    <td>
                                        <div class="font-weight-bold">{{ $user->username }}
                                            @if ($user->id==Auth::id())
                                                (Vous)
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div class="font-weight-bold">{{ $user->email }}</div>
                                    </td>
                                    <td>
                                        @if ($user->hasRole('admin'))
                                            <div class="font-weight-bold">Admin</div>
                                        @elseif($user->hasRole('viewer'))
                                            <div class="font-weight-bold">Lecteur</div>
                                        @elseif ($user->hasRole('owner'))
                                            <div class="font-weight-bold">Propriétaire</div>
                                        @elseif ($user->hasRole('service_user'))
                                            <div class="font-weight-bold">Utilisateur Service</div>
                                        @endif
                                    </td>
                                    <td>
                                        @switch($user->status)
                                            @case('ACTIVE')
                                                <span class="badge bg-success">Actif</span>
                                            @break

                                            @case('INACTIVE')
                                                <span class="badge bg-danger">Inactif</span>
                                            @break

                                            @case('DELETED')
                                                <span class="badge bg-secondary">Supprimé</span>
                                            @break

                                            @default
                                        @endswitch
                                    </td>
                                    <td>
                                        @if ($user->status != 'DELETED' && Auth::id() != $user->id && !$user->hasRole('owner'))
                                            @if (!Auth::user()->hasRole('admin') && !Auth::user()->hasRole('owner'))
                                                -
                                            @else
                                                <a class="btn split-bg-primary" data-bs-toggle="dropdown"> <i
                                                        class='bx bx-dots-horizontal-rounded font-24 '></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-end"> <a
                                                        class="dropdown-item"
                                                        href="{{ route('global.update_user_status', ['id' => $user->id]) }}">
                                                        @if ($user->status == 'ACTIVE')
                                                            Désactiver
                                                        @elseif ($user->status == 'INACTIVE')
                                                            Activer
                                                        @endif
                                                    </a>
                                                    {{-- <a class="dropdown-item" href="javascript:;" data-bs-toggle="modal"
                                                        data-bs-target="#updateUserPermissionModal-{{ $user->id }}">Modifier
                                                        Permission</a> --}}

                                                    <a class="dropdown-item"
                                                        href="{{ route('global.delete_user_status', ['id' => $user->id]) }}">Supprimer</a>

                                                </div>
                                                {{-- @include('global_manager.page.service.layouts.update_permission_modal') --}}
                                            @endif
                                        @else
                                            -
                                        @endif
                                    </td>

                                </tr>
                                @empty
                                    <h4>Aucun utilisateur global actuellement</h4>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
