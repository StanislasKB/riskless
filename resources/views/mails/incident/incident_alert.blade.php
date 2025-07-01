<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Alerte d'incident</title>
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
            background-color: #007bff;
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
            color: #007bff;
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
            background-color: #28a745;
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 4px;
            margin-top: 20px;
            font-size: 16px;
        }
        .btn-primary:hover {
            background-color: #218838;
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
                    <div class="email-header">
                        <h1>Nouvel Incident Signalé</h1>
                    </div>

                    {{-- Body --}}
                    <div class="email-body">
                        <p>Bonjour <strong>{{ $recipientName }}</strong>,</p>

                        <p>Un nouvel incident a été enregistré sur le service <strong>{{ $serviceName }}</strong>. Vous trouverez ci-dessous les détails :</p>

                        <h2>Détails de l’incident</h2>
                        <table class="detail-table">
                            <tr>
                                <th>Libellé</th>
                                <td>{{ $incident->libelle }}</td>
                            </tr>
                            <tr>
                                <th>Type</th>
                                <td>{{ $incident->type ?? '—' }}</td>
                            </tr>
                            <tr>
                                <th>Fréquence Susceptible</th>
                                <td>{{ $incident->frequence_susceptible ?? '—' }}</td>
                            </tr>
                            <tr>
                                <th>Identifié par</th>
                                <td>{{ $incident->identifie_par ?? '—' }}</td>
                            </tr>
                            <tr>
                                <th>Créé le</th>
                                <td>{{ $incident->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                            <tr>
                                <th>Description</th>
                                <td>{{ $incident->description ?? 'Pas de description' }}</td>
                            </tr>
                        </table>

                        <a href="{{ $actionUrl }}" class="btn-primary">Voir l’incident</a>
                    </div>

                    {{-- Footer --}}
                    <div class="email-footer">
                        <p>Vous recevez ce message car vous êtes configuré pour recevoir les alertes d’incidents.</p>
                        <p>&copy; {{ date('Y') }} {{ config('app.name') }}. Tous droits réservés.</p>
                    </div>

                </div>
            </td>
        </tr>
    </table>
</body>
</html>
