<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Alerte Plan d'Action</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        /* Reset */
        body, table, td, a { -webkit-text-size-adjust:100%; -ms-text-size-adjust:100%; }
        table, td { mso-table-lspace:0pt; mso-table-rspace:0pt; }
        img { -ms-interpolation-mode:bicubic; }
        body { margin:0; padding:0; width:100% !important; height:100% !important; }

        /* Container */
        .email-container {
            max-width: 600px;
            margin: auto;
            background-color: #ffffff;
            border: 1px solid #e5e5e5;
            border-radius: 8px;
            overflow: hidden;
            font-family: Arial, sans-serif;
            color: #333333;
        }

        /* Header */
        .email-header {
            background-color: #28a745;
            color: #ffffff;
            padding: 20px;
            text-align: center;
        }
        .email-header h1 {
            margin: 0;
            font-size: 24px;
        }

        /* Body */
        .email-body {
            padding: 20px;
        }
        .email-body h2 {
            color: #28a745;
            font-size: 20px;
            margin-top: 0;
        }
        .detail-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        .detail-table th, .detail-table td {
            padding: 8px 12px;
            border: 1px solid #dddddd;
            text-align: left;
            font-size: 14px;
        }
        .detail-table th {
            background-color: #f5f5f5;
        }

        /* Footer */
        .email-footer {
            background-color: #f5f5f5;
            padding: 15px 20px;
            text-align: center;
            font-size: 12px;
            color: #777777;
        }

        /* Button */
        .btn-primary {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 4px;
            margin-top: 20px;
            font-size: 16px;
        }
        .btn-primary:hover {
            background-color: #0056b3;
        }

        @media screen and (max-width: 480px) {
            .email-body, .email-footer {
                padding: 15px !important;
            }
            .detail-table th, .detail-table td {
                padding: 6px 8px !important;
                font-size: 12px !important;
            }
        }
    </style>
</head>
<body>
     <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td align="center" bgcolor="#f0f0f0" style="padding: 30px 0;">
                <div class="email-container">

                    {{-- Header --}}
                    @php
                        $isLate = $planAction->date_fin_prevue
                                  && now()->gt($planAction->date_fin_prevue)
                                  && $planAction->progression < 100;
                    @endphp
                    <div class="email-header" style="background-color: {{ $isLate ? '#dc3545' : '#28a745' }};">
                        <h1>
                            {{ $isLate
                                ? 'Retard de mise en œuvre du plan d’action'
                                : 'Nouvelle action planifiée' }}
                        </h1>
                    </div>

                    {{-- Body --}}
                    <div class="email-body">
                        <p>Bonjour <strong>{{ $recipientName }}</strong>,</p>

                        @if($isLate)
                            <p>
                                Le plan d’action <strong>#{{ $planAction->index }}</strong> prévu jusqu’au
                                <strong>{{ $planAction->date_fin_prevue->format('d/m/Y H:i') }}</strong>
                                n’est pas encore terminé (progression à {{ $planAction->progression }}%).
                                Merci de <strong>prendre les mesures nécessaires</strong> pour respecter les délais.
                            </p>
                        @else
                            <p>
                                Une nouvelle action a été planifiée sur le service
                                <strong>{{ $serviceName }}</strong>. Détails ci-dessous :
                            </p>
                        @endif

                        <h2>Détails du plan d’action</h2>
                        <table class="detail-table">
                            <tr>
                                <th>Index</th>
                                <td>{{ $planAction->index ?? '—' }}</td>
                            </tr>
                            <tr>
                                <th>Type</th>
                                <td>{{ $planAction->type ?? '—' }}</td>
                            </tr>
                            <tr>
                                <th>Priorité</th>
                                <td>{{ $planAction->priorite ?? '—' }}</td>
                            </tr>
                            <tr>
                                <th>Responsable</th>
                                <td>{{ $planAction->responsable ?? '—' }}</td>
                            </tr>
                            <tr>
                                <th>Date début prévue</th>
                                <td>{{ $planAction->date_debut_prevue?->format('d/m/Y H:i') ?? '—' }}</td>
                            </tr>
                            <tr>
                                <th>Date fin prévue</th>
                                <td>{{ $planAction->date_fin_prevue?->format('d/m/Y H:i') ?? '—' }}</td>
                            </tr>
                            <tr>
                                <th>Statut</th>
                                <td>{{ ucfirst(strtolower(str_replace('_',' ',$planAction->statut))) }}</td>
                            </tr>
                            <tr>
                                <th>Progression</th>
                                <td>{{ $planAction->progression ?? 0 }}%</td>
                            </tr>
                            <tr>
                                <th>Description</th>
                                <td>{{ $planAction->description ?? 'Pas de description' }}</td>
                            </tr>
                        </table>

                        <a href="{{ $actionUrl }}" class="btn-primary">
                            {{ $isLate ? 'Voir et mettre à jour' : 'Voir le plan d’action' }}
                        </a>
                    </div>

                    {{-- Footer --}}
                    <div class="email-footer">
                        <p>Vous recevez ce message car vous suivez les alertes de plan d’action.</p>
                        <p>&copy; {{ date('Y') }} {{ config('app.name') }}. Tous droits réservés.</p>
                    </div>

                </div>
            </td>
        </tr>
    </table>
</body>
</html>
