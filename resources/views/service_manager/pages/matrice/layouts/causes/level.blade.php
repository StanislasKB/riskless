<div class="row mt-4">

    <div class="col-12">
        <div class=" d-flex">
            <div class="card radius-15 w-100">
                <div class="card-body">
                    <div class="card-title">
                        <select name="" id="selectLevel" class="form-select">
                            <option value="level1">Niveau 1 (Catégorie Bâle II)</option>
                            <option value="level2">Niveau 2 (Sous-catégorie Bâle II)</option>
                            <option value="level3">Niveau 3</option>
                        </select>
                    </div>
                    <hr />
                    <div id="levelOne">
                        <div id="causesLevelOne" data-causes="{{ json_encode($causes_level_one) }}"></div>

                        <div class="table-responsive">
                            <table id="echelle" class="table table-bordered table-sm mb-0" border="1"
                                cellspacing="0" cellpadding="7" style="border-color: black">
                                <thead>
                                    <tr>

                                        <th colspan="2">Nombre de Niveau 1 (Catégorie Bâle II)</th>

                                    </tr>
                                    <tr>
                                        <!-- Echelles -->
                                        <th> Niveau 1 (Catégorie Bâle II)</th>
                                        <th>Total</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $total = 0;
                                    @endphp
                                    @foreach ($causes_level_one as $index => $data)
                                        @if ($data['nb'] > 0)
                                            <tr>
                                                <td>{{ $data['libelle'] }}</td>
                                                <td>{{ $data['nb'] }}</td>
                                            </tr>
                                            @php
                                                $total += $data['nb'];
                                            @endphp
                                        @endif
                                    @endforeach
                                    <tr class="fw-bold">
                                        <td>Total</td>
                                        <td>{{ $total }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div id="levelTwo" style="display: none;">
                        <div id="causesLevelTwo" data-causes="{{ json_encode($causes_level_two) }}"></div>

                        <div class="table-responsive">
                            <table id="echelle" class="table table-bordered table-sm mb-0" border="1"
                                cellspacing="0" cellpadding="7" style="border-color: black">
                                <thead>
                                    <tr>

                                        <th colspan="2">Nombre de Niveau 2 (Sous-catégorie Bâle II)</th>

                                    </tr>
                                    <tr>
                                        <!-- Echelles -->
                                        <th> Niveau 2 (Sous-catégorie Bâle II)</th>
                                        <th>Total</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $total = 0;
                                    @endphp
                                    @foreach ($causes_level_two as $index => $data)
                                        @if ($data['nb'] > 0)
                                            <tr>
                                                <td>{{ $data['libelle'] }}</td>
                                                <td>{{ $data['nb'] }}</td>
                                            </tr>
                                            @php
                                                $total += $data['nb'];
                                            @endphp
                                        @endif
                                    @endforeach
                                    <tr class="fw-bold">
                                        <td>Total</td>
                                        <td>{{ $total }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div id="levelThree" style="display: none;">
                        <div id="causesLevelThree" data-causes="{{ json_encode($causes_level_three) }}"></div>

                        <div class="table-responsive">
                            <table id="echelle" class="table table-bordered table-sm mb-0" border="1"
                                cellspacing="0" cellpadding="7" style="border-color: black">
                                <thead>
                                    <tr>

                                        <th colspan="2">Nombre de Niveau 3 </th>

                                    </tr>
                                    <tr>
                                        <!-- Echelles -->
                                        <th> Niveau 3</th>
                                        <th>Total</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $total = 0;
                                    @endphp
                                    @foreach ($causes_level_three as $index => $data)
                                        @if ($data['nb'] > 0)
                                            <tr>
                                                <td>{{ $data['libelle'] }}</td>
                                                <td>{{ $data['nb'] }}</td>
                                            </tr>
                                            @php
                                                $total += $data['nb'];
                                            @endphp
                                        @endif
                                    @endforeach
                                    <tr class="fw-bold">
                                        <td>Total</td>
                                        <td>{{ $total }}</td>
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
