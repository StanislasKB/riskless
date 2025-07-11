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

                
                <div class="table-responsive">
                    <table id="echelle" class="table table-bordered table-sm mb-0" border="1" cellspacing="0"
                        cellpadding="7" style="border-color: black">
                        <thead>
                            <tr>
                                <th rowspan="2">Index</th>
                                <th colspan="2">Echelle (moyen)</th>
                                <th colspan="2">Echelle(maximum)</th>
                                <th colspan="2">Echelle contrôle</th>
                            </tr>
                            <tr>
                                <!-- Echelles -->
                                <th>Fréquence</th>
                                <th>Impact</th>
                                
                                <!-- Impact moyen -->
                                <th>Fréquence</th>
                                <th>Impact</th>
                             
                                <!-- Impact maximum -->
                                <th>Cotation</th>
                                <th>Controle</th>
                               
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data_table as $index => $data)
                                <tr>
                                    <td>{{ $index }}</td>

                                    {{-- Echelles --}}
                                    <td>{{ $data['echelle_net']['freq'] }}</td>
                                    <td>{{ $data['echelle_net']['impact'] }}</td>

                                    <td>{{ $data['echelle_brut']['freq'] }}</td>
                                    <td>{{ $data['echelle_brut']['impact'] }}</td>

                                    <td>{{ $data['echelle_controle']['cotation'] }}</td>
                                    <td>{{ $data['echelle_controle']['controle'] }}</td>
                                    
                                </tr>
                            @endforeach
                        </tbody>


                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
