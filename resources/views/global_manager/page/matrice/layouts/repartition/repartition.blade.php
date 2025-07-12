<div class="row mt-4">

    <div class="col-12">
        <div class=" d-flex">
            <div class="card radius-15 w-100">
                <div class="card-body">

                    <div class="table-responsive">
                        <table id="echelle" class="table table-bordered table-sm mb-0" border="1" cellspacing="0"
                            cellpadding="7" style="border-color: black">
                            <thead>
                                <tr>

                                    <th colspan="3">Répartition Impact Moyen</th>
                                    <th colspan="3">Répartition Impact Maximum</th>

                                </tr>
                                
                            </thead>
                            <tbody>
                                @php
                                    $total = 0;
                                @endphp
                                @foreach ($repartition as $index => $data)
                                    
                                        <tr>
                                            <td class="fw-bold">{{ Str::ucfirst($index) }}</td>
                                            <td>{{ $data['pourcentage_moyen'] }}%</td>
                                            <td>{{ $data['nb_moyen'] }}</td>
                                            <td class="fw-bold">{{ Str::ucfirst($index)  }}</td>
                                            <td>{{ $data['pourcentage_max'] }}%</td>
                                            <td>{{ $data['nb_max'] }}</td>
                                            
                                        </tr>
                                       
                                @endforeach
                                
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>

    </div>
</div>
