
        <div class="table-responsive">
    <table id="plan_list" class="table table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Index</th>
                <th>Type</th>
                <th>Priorité</th>
                <th>Responsable</th>
                <th>Date début</th>
                <th>Date fin</th>
                <th>Statut</th>
                <th>Progression</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($plans as $plan)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $plan->index ?? '—' }}</td>
                    <td>{{ $plan->type ?? '—' }}</td>
                    <td>{{ $plan->priorite ?? '—' }}</td>
                    <td>{{ $plan->responsable ?? '—' }}</td>
                    <td>{{ $plan->date_debut_prevue ? \Carbon\Carbon::parse($plan->date_debut_prevue)->format('d/m/Y H:i') : '—' }}</td>
                    <td>{{ $plan->date_fin_prevue ? \Carbon\Carbon::parse($plan->date_fin_prevue)->format('d/m/Y H:i') : '—' }}</td>
                    <td>
                        <span class="badge bg-primary">{{ $plan->statut }}</span>
                    </td>
                    <td>
                        <div class="progress" style="height: 20px;">
                            <div class="progress-bar" role="progressbar"
                                 style="width: {{ $plan->progression }}%;"
                                 aria-valuenow="{{ $plan->progression }}"
                                 aria-valuemin="0" aria-valuemax="100">
                                {{ $plan->progression }}%
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="d-flex">
                            @include('service_manager.pages.plan_action.layouts.view_action', ['plan' => $plan])
                            @include('service_manager.pages.plan_action.layouts.update_plan_action', ['plan' => $plan])
                            @include('service_manager.pages.plan_action.layouts.delete_plan_action', ['plan' => $plan])
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="10">
                        <h4 class="text-center">Aucun plan d'action actuellement</h4>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>



