
        <div class="table-responsive">
    <table id="avancement_list" class="table table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Mois</th>
                <th>Année</th>
                <th>Statut</th>
                <th>Reste à Faire</th>
                <th>Commentaire</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($avancements as $avancement)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $avancement->mois ?? '—' }}</td>
                    <td>{{ $avancement->annee ?? '—' }}</td>
                    <td>
                        <span class="badge bg-primary">{{ $avancement->statut }}</span>
                    </td>
                    <td>{{ $avancement->reste_a_faire ?? '—' }}</td>
                    <td>{{ $avancement->commentaire ?? '—' }}</td>

                    <td>
                        <div class="d-flex gap-2">
                            @include('service_manager.pages.plan_action.layouts.avancement.update_avancement', ['avancement' => $avancement])
                            @include('service_manager.pages.plan_action.layouts.avancement.delete_avancement', ['avancement' => $avancement])
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7">
                        <h4 class="text-center">Aucun plan d'action actuellement</h4>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>



