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
                <hr>

                <div class="table-responsive">
                    <table id="risk_category" class="table table-striped table-sm mb-0">
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
                                        @if (!Auth::user()->hasRole('admin') && !Auth::user()->hasRole('owner') && $category->creator->id != Auth::id)
                                            -
                                        @else
                                            <a class="btn split-bg-primary" data-bs-toggle="dropdown"> <i
                                                    class='bx bx-dots-horizontal-rounded font-24 '></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-end"> <a
                                                    class="dropdown-item" data-bs-toggle="modal"
                                                    data-bs-target="#editRiskCategory-{{ $category->id }}"
                                                    href="#">
                                                    Modifier
                                                </a>

                                                <a class="dropdown-item"
                                                    href="{{ route('global.delete_configuration_risque_category.post', ['id' => $category->id]) }}">Supprimer</a>

                                            </div>
                                            @include('global_manager.page.configuration.layouts.modals.edit_category_modal')
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
