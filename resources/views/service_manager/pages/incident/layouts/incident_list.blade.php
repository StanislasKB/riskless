<div class="table-responsive">
    <table id="incident_list" class="table table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Libellé</th>
                <th>Type</th>
                <th>Fréquence Susceptible</th>
                <th>Identifié par</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($incidents as $index => $incident)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $incident->libelle }}</td>
                    <td>{{ $incident->type ?? '—' }}</td>
                    <td>{{ $incident->frequence_susceptible ?? '—' }}</td>
                    <td>{{ $incident->identifie_par ?? '—' }}</td>
                    <td>{{ $incident->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        <!-- Bouton Modifier -->
                        <div class="d-flex">
                            @include('service_manager.pages.incident.layouts.view_action', [
                                'incident' => $incident,
                            ])
                            @include('service_manager.pages.incident.layouts.update_incident', [
                                'incident' => $incident,
                            ])
                            @include('service_manager.pages.incident.layouts.delete_incident', [
                                'incident' => $incident,
                            ])
                        </div>
                    </td>
                </tr>


            @empty
                <tr>
                    <td colspan="100%" class="text-center">
                        Aucun incident enregistré pour le moment.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
