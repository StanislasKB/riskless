<div class="row">
    <div class="col-12 col-lg-3">
        <div class="card">
            <div class="card-body">
                <div class="d-grid"> <a href="javascript:;" class="btn btn-primary" data-bs-toggle="modal"
                        data-bs-target="#addServiceModal">+ Ajouter un service</a>
                    @include('global_manager.page.service.layouts.add_service_modal')
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-lg-9">
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

                <div class="row mt-3">
                    @forelse ($services as $service)
                        <div class="col-12 col-lg-4">
                            <div class="card shadow-none border radius-15">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="font-30 text-primary"><i class='bx bxs-folder'></i>
                                        </div>
                                        <div class="user-groups ms-auto">
                                            {{-- <img src="/admin/assets/images/avatars/avatar-1.png" width="35"
                                            height="35" class="rounded-circle" alt="" />
                                        <img src="/admin/assets/images/avatars/avatar-2.png" width="35"
                                            height="35" class="rounded-circle" alt="" /> --}}
                                        </div>
                                        <div class="user-plus"><a href="form-file-upload.html"><i
                                                    class="bx bx-right-arrow-alt"></i></a></div>
                                    </div>
                                    <h6 class="mb-0 text-primary">{{ $service->name }}</h6>
                                    <small>15 files</small>
                                </div>
                            </div>
                        </div>
                    @empty
                        <h4>Aucun service n'a été ajouté actuellement !</h4>
                    @endforelse


                </div>
                <!--end row-->
                <div class="d-flex align-items-center">
                    <div>
                        <h5 class="mb-0">Utilisateurs</h5>
                    </div>
                    <div class="ms-auto"><a href="javascript:;" class="btn btn-sm btn-outline-secondary"
                            data-bs-toggle="modal" data-bs-target="#addServiceUserModal">Ajouter un
                            utilisateur</a>
                        @include('global_manager.page.service.layouts.add_user_modal')
                    </div>
                </div>
                <div class="table-responsive mt-3">
                    <table class="table table-striped table-hover table-sm mb-0">
                        <thead>
                            <tr>
                                <th>Nom<i class='bx bx-up-arrow-alt ms-2'></i>
                                </th>
                                <th>Service</th>
                                <th>Permissions</th>
                                <th>Statut</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($users as $user)
                                <tr>
                                    <td>
                                        <div class="font-weight-bold">{{ $user->username }}</div>
                                    </td>
                                    <td>
                                        @foreach ($user->services()->get() as $user_service)
                                            <span class="badge bg-primary"> {{ $user_service->name }}</span>
                                        @endforeach
                                    </td>
                                    <td>
                                        @foreach ($user->getDirectPermissions() as $permission)
                                            @switch($permission->name)
                                                @case('edit risk')
                                                    <span class="badge bg-secondary">Edition</span>
                                                @break

                                                @case('create risk')
                                                    <span class="badge bg-secondary">Création</span>
                                                @break

                                                @case('validate risk')
                                                    <span class="badge bg-secondary">Validation</span>
                                                @break

                                                @case('delete risk')
                                                    <span class="badge bg-secondary">Suppession</span>
                                                @break

                                                @default
                                            @endswitch
                                        @endforeach
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
                                    <td>@if ($user->status != 'DELETED')
                                        <a class="btn split-bg-primary" data-bs-toggle="dropdown"> <i
                                                class='bx bx-dots-horizontal-rounded font-24 '></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-end"> <a
                                                class="dropdown-item"
                                                href="{{ route('global.update_service_user_status', ['id' => $user->id]) }}">
                                                @if ($user->status == 'ACTIVE')
                                                    Désactiver
                                                @elseif ($user->status == 'INACTIVE')
                                                    Activer
                                                @endif
                                            </a>
                                            <a class="dropdown-item" href="javascript:;" data-bs-toggle="modal"
                                                data-bs-target="#updateUserPermissionModal-{{ $user->id }}">Modifier
                                                Permission</a>

                                            <a class="dropdown-item"
                                                href="{{ route('global.delete_service_user_status', ['id' => $user->id]) }}">Supprimer</a>
                                            
                                        </div>
                                        @include('global_manager.page.service.layouts.update_permission_modal')
                                        @else
                                        -
                                    @endif
                                    </td>

                                </tr>
                                @empty
                                    <h4>Aucun utilisateur lié à un service actuellement</h4>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
