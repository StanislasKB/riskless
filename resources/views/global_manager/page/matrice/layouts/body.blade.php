<div class="container">
    <div class="chart-container">
        <canvas id="riskMatrixChart" data-graphe='{{ $data_net }}'></canvas>
    </div>
      <div class="legend">
        <div class="legend-item">
            <div class="legend-color" style="background-color: #4CAF50;"></div>
            <span>Faible</span>
        </div>
        <div class="legend-item">
            <div class="legend-color" style="background-color: #FFEB3B;"></div>
            <span>Moyen</span>
        </div>
        <div class="legend-item">
            <div class="legend-color" style="background-color: #FF9800;"></div>
            <span>Fort</span>
        </div>
        <div class="legend-item">
            <div class="legend-color" style="background-color: #e23535;"></div>
            <span>Critique</span>
        </div>
        <div class="legend-item">
            <div class="legend-color" style="background-color: #a10707;"></div>
            <span>Inacceptable</span>
        </div>
    </div>
    <div class="chart-container">
        <canvas id="riskMatrixChartBrut" data-graphe='{{ $data_brut }}'></canvas>
    </div>

    <div class="legend">
        <div class="legend-item">
            <div class="legend-color" style="background-color: #4CAF50;"></div>
            <span>Faible</span>
        </div>
        <div class="legend-item">
            <div class="legend-color" style="background-color: #FFEB3B;"></div>
            <span>Moyen</span>
        </div>
        <div class="legend-item">
            <div class="legend-color" style="background-color: #FF9800;"></div>
            <span>Fort</span>
        </div>
        <div class="legend-item">
            <div class="legend-color" style="background-color: #e23535;"></div>
            <span>Critique</span>
        </div>
        <div class="legend-item">
            <div class="legend-color" style="background-color: #DCDCDC;"></div>
            <span>Inacceptable</span>
        </div>
    </div>
      <div class="chart-container">
        <canvas id="riskDMRChart" data-graphe='{{ $data_dmr }}'></canvas>
    </div>

    {{-- <div class="info-panel">
        <h3>Analyse des Risques</h3>
        <div class="risk-summary" id="riskSummary"></div>
    </div> --}}
</div>
