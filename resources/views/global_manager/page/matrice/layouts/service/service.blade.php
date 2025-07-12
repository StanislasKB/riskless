<div class="row mt-4">

    <div class="col-12">
        <div class=" d-flex">
            <div class="card radius-15 w-100">
                <div class="card-body">
                    <div class="card-title">
                        <select id="selectService" class="form-select">
                            @foreach ($data_service as $libelle => $data)
                                @if ($libelle !== 'total_general')
                                    <option value="{{ $data['id'] }}">{{ $libelle }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <hr />
                    <div >
                        @php
                            $i = 0;
                        @endphp
                        @foreach ($data_service as $libelle => $data)
                            @php
                                $i++;
                            @endphp
                            @if ($libelle !== 'total_general')
                                <div id="chartService-{{ $data['id'] }}" class="chart-service" style="display: none;">
                                    <div class="service-chart" data-service="{{ json_encode($data) }}"></div>
                                </div>
                            @endif
                        @endforeach

                        <div class="table-responsive">
                            <table id="echelle" class="table table-bordered table-sm mb-0" border="1"
                                cellspacing="0" cellpadding="7" style="border-color: black">
                                <thead>
                                    <tr>

                                        <th colspan="1">Nombre de Echelle de risque/ cotation risque</th>
                                        <th colspan="6">Echelle</th>

                                    </tr>
                                    <tr>
                                        <!-- Echelles -->
                                        <th> Service</th>
                                        <th> Faible</th>
                                        <th> Moyen</th>
                                        <th> Fort</th>
                                        <th> Critique</th>
                                        <th> Inacceptable</th>
                                        <th>Total</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data_service as $libelle => $data)
                                        @if ($libelle !== 'total_general')
                                            <tr>
                                                <td>{{ $libelle }}</td>
                                                <td>{{ $data['faible'] }}</td>
                                                <td>{{ $data['moyen'] }}</td>
                                                <td>{{ $data['fort'] }}</td>
                                                <td>{{ $data['critique'] }}</td>
                                                <td>{{ $data['inacceptable'] }}</td>
                                                <td>{{ $data['total'] }}</td>
                                            </tr>
                                        @endif
                                    @endforeach
                                    <tr class="fw-bold">
                                        <td>Total</td>
                                        <td>{{ $data_service['total_general']['faible'] }}</td>
                                        <td>{{ $data_service['total_general']['moyen'] }}</td>
                                        <td>{{ $data_service['total_general']['fort'] }}</td>
                                        <td>{{ $data_service['total_general']['critique'] }}</td>
                                        <td>{{ $data_service['total_general']['inacceptable'] }}</td>
                                        <td>{{ $data_service['total_general']['total'] }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
