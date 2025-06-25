<div class="row">
    <div class="col-12">
        <div class="card">
           
            <div class="card-body">

                <!--end row-->
                <div class="d-flex align-items-center">
                    <div>
                        <h5 class="mb-0">Categories de risque</h5>
                    </div>
                </div>
                <div class="table-responsive mt-3">
                    <table class="table table-striped table-hover table-sm mb-0">
                        <thead>
                            <tr>
                                <th>Libellé
                                </th>
                                <th>Ajouté par</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($categories as $category)
                                <tr>
                                    <td>
                                        <div class="font-weight-bold">{{ $category->libelle }}
                                        </div>
                                    </td>
                                    <td>
                                        {{ $category->creator->username }}
                                    </td>
                                    
                                    <td>
                                            @if (!Auth::user()->hasRole('admin') && !Auth::user()->hasRole('owner') && $category->creator->id!=Auth::id )
                                                -
                                            @else
                                                <a class="btn split-bg-primary" data-bs-toggle="dropdown"> <i
                                                        class='bx bx-dots-horizontal-rounded font-24 '></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-end"> <a
                                                        class="dropdown-item"
                                                        href="">
                                                        Modifier
                                                    </a>

                                                    <a class="dropdown-item"
                                                        href="">Supprimer</a>

                                                </div>
                                                {{-- @include('global_manager.page.service.layouts.update_permission_modal') --}}
                                            @endif
                                        
                                    </td>

                                </tr>
                                @empty
                                    <h4>Aucune categorie de risque actuellement</h4>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
