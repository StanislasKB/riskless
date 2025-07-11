<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            @if (session('success'))
                <div class="alert alert-success" role="alert">
                    {{ session('success') }}
                </div>
            @endif


            @foreach ($errors->all() as $error)
                <div class="alert alert-danger" role="alert">{{ $error }}</div>
            @endforeach
            <div class="card-body">

                <!--end row-->
                
                <div class="table-responsive">
                    <table id="recap" class="table table-bordered table-sm mb-0" border="1" cellspacing="0"
                        cellpadding="7" style="border-color: black">
                        <thead>
                            <tr>
                                <th rowspan="2">Index</th>
                                <th rowspan="2">Contrôle</th>
                                <th colspan="6">Echelles</th>
                                <th colspan="7">Impact moyen</th>
                                <th colspan="7">Impact maximum</th>
                                <th colspan="7">Dispositif de contrôle</th>
                            </tr>
                            <tr>
                                <!-- Echelles -->
                                <th>Echelle fréquence</th>
                                <th>Echelle Impact</th>
                                <th>Echelle Impact maximum</th>
                                <th>Echelle Cotation</th>
                                <th>Echelle Cotation max</th>
                                <th>Echelle Contrôle</th>
                                <!-- Impact moyen -->
                                <th>Concaténation des échelles</th>
                                <th>nb total</th>
                                <th>nb avant</th>
                                <th>nb lignes</th>
                                <th>Ligne (1 à 3)</th>
                                <th>nb colonnes</th>
                                <th>Colonne (1 à x)</th>
                                <!-- Impact maximum -->
                                <th>Concaténation des échelles</th>
                                <th>nb total</th>
                                <th>nb avant</th>
                                <th>nb lignes</th>
                                <th>Ligne (1 à 3)</th>
                                <th>nb colonnes</th>
                                <th>Colonne (1 à x)</th>
                                <!-- Dispositif de contrôle -->
                                <th>Concaténation des échelles</th>
                                <th>nb total</th>
                                <th>nb avant</th>
                                <th>nb lignes</th>
                                <th>Ligne (1 à 3)</th>
                                <th>nb colonnes</th>
                                <th>Colonne (1 à x)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data_table as $index => $data)
                                <tr>
                                    <td>{{ $index }}</td>
                                    <td>
                                        @php
                                            $ctrl_score = $data['echelles']['echelle_cotation_controle'];
                                            $label = match (true) {
                                                $ctrl_score == 0.5 => 'Efficace (plus de 90% de pertes évitées)',
                                                $ctrl_score == 1.5 => 'Conforme (de 75% à 90% de pertes évitées)',
                                                $ctrl_score == 2.5 => 'Acceptable (de 50% à 75% de pertes évitées)',
                                                $ctrl_score == 3.5 => 'Insuffisant (de 25% à 50% de pertes évitées)',
                                                $ctrl_score == 5.5 => 'Inexistant (moins de 25% de pertes évitées)',
                                                default => 'Inconnu',
                                            };
                                        @endphp
                                        {{ $label }}
                                    </td>

                                    {{-- Echelles --}}
                                    <td>{{ $data['echelles']['echelle_frequence'] }}</td>
                                    <td>{{ $data['echelles']['echelle_impact_net'] }}</td>
                                    <td>{{ $data['echelles']['echelle_impact_brut'] }}</td>
                                    <td>{{ $data['echelles']['echelle_cotation_net'] }}</td>
                                    <td>{{ $data['echelles']['echelle_cotation_brut'] }}</td>
                                    <td>{{ number_format($data['echelles']['echelle_cotation_controle'], 2, ',', ' ') }}
                                    </td>

                                    {{-- Impact moyen (impact_net) --}}
                                    <td>{{ $data['impact_net']['concatenation'] }}</td>
                                    <td>{{ $data['impact_net']['nb_total'] }}</td>
                                    <td>{{ $data['impact_net']['nb_avant'] }}</td>
                                    <td>{{ $data['impact_net']['nb_lignes'] }}</td>
                                    <td>{{ $data['impact_net']['ligne_un_trois'] }}</td>
                                    <td>{{ $data['impact_net']['nb_colonne'] }}</td>
                                    <td>{{ $data['impact_net']['colonne_un_x'] }}</td>

                                    {{-- Impact maximum (impact_brut) --}}
                                    <td>{{ $data['impact_brut']['concatenation'] }}</td>
                                    <td>{{ $data['impact_brut']['nb_total'] }}</td>
                                    <td>{{ $data['impact_brut']['nb_avant'] }}</td>
                                    <td>{{ $data['impact_brut']['nb_lignes'] }}</td>
                                    <td>{{ $data['impact_brut']['ligne_un_trois'] }}</td>
                                    <td>{{ $data['impact_brut']['nb_colonne'] }}</td>
                                    <td>{{ $data['impact_brut']['colonne_un_x'] }}</td>

                                    {{-- Dispositif de contrôle --}}
                                    <td>{{ $data['controle']['concatenation'] }}</td>
                                    <td>{{ $data['controle']['nb_total'] }}</td>
                                    <td>{{ $data['controle']['nb_avant'] }}</td>
                                    <td>{{ $data['controle']['nb_lignes'] }}</td>
                                    <td>{{ $data['controle']['ligne_un_trois'] }}</td>
                                    <td>{{ $data['controle']['nb_colonne'] }}</td>
                                    <td>{{ $data['controle']['colonne_un_x'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>


                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
