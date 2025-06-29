<div class="row">
    <div class="col-12">
        <div class="card">

            <div class="card-body">

                <!--end row-->
                <div class="d-flex align-items-center">
                    <div>
                        <h5 class="mb-0">Macroprocessus</h5>
                    </div>
                </div>

                <hr>
                <div class="table-responsive">
                    <table id="macroprocessus" class="table table-striped table-sm mb-0">
                        <thead>
                            <tr>
                                <th>Nom
                                </th>
                                <th>Entité</th>
                                <th>Ajouté par</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($macroprocessus as $macro)
                                <tr>
                                    <td>
                                        <div class="font-weight-bold">{{ $macro->name }}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="font-weight-bold">{{ $macro->entite }}</div>
                                    </td>
                                    <td>
                                        {{ $macro->creator->username }}
                                    </td>

                                    <td>
                                        @if (!Auth::user()->hasRole('admin') && !Auth::user()->hasRole('owner') && $macro->creator->id != Auth::id)
                                            -
                                        @else
                                            <a class="btn split-bg-primary" data-bs-toggle="dropdown"> <i
                                                    class='bx bx-dots-horizontal-rounded font-24 '></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-end"> <a
                                                    class="dropdown-item" href="#"data-bs-toggle="modal"
                                                    data-bs-target="#editMacro-{{ $macro->id }}">
                                                    Modifier
                                                </a>


                                                <a class="dropdown-item"
                                                    href="{{ route('global.delete_configuration_macroprocessus.post', ['id' => $macro->id]) }}">Supprimer</a>

                                            </div>
                                            @include('global_manager.page.configuration.layouts.modals.edit_macro_modal')
                                        @endif

                                    </td>

                                </tr>
                            @empty
                                <tr>
                                    <td colspan="100%">
                                        <h4>Aucun utilisateur global actuellement</h4>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>





                <div class="table-responsive mt-3">
                    <table class="table table-striped table-hover table-sm mb-0">

                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
