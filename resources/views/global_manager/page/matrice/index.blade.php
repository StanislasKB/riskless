@extends('global_manager.components.app')
@section('title')
    Dashboard 
@endsection
@section('page_css')
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f5f5f5;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .chart-container {
            position: relative;
            height: 600px;
            margin: 20px 0;
        }

        .legend {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 20px;
            flex-wrap: wrap;
        }

        .legend-item {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .legend-color {
            width: 20px;
            height: 20px;
            border-radius: 3px;
            border: 1px solid #333;
        }

        .info-panel {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-top: 20px;
        }

        .risk-summary {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 15px;
            margin-top: 15px;
        }

        .risk-item {
            background: white;
            padding: 15px;
            border-radius: 6px;
            border-left: 4px solid #007bff;
        }
    </style>
@endsection
@section('main_content')
    @include('global_manager.page.matrice.layouts.page_header')
    @include('global_manager.page.matrice.layouts.body')
    @include('global_manager.page.matrice.layouts.echelles.recap')
    @include('global_manager.page.matrice.layouts.echelles.echelle')
    @include('global_manager.page.matrice.layouts.causes.level')
@endsection
@section('page_js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
    <script>
        (function() {

            const div = document.getElementById("riskMatrixChart");
            const riskData = JSON.parse(div.getAttribute("data-graphe"));

            // --- Labels ---
            // Impact du plus faible (index 0) au plus critique (index 5)
            const impactLabels = [
                ['Faible', '(≤ 10 kXOF)'],
                ['Modéré', '(10‑100 kXOF)'],
                ['Moyen', '(100 k‑1 MXOF)'],
                ['Fort', '(1‑10 MXOF)'],
                ['Majeur', '(10‑100 MXOF)'],
                ['Critique', '(> 100 MXOF)']
            ];
            const frequencyLabels = [
                ['Extrêmement rare', '(≤ 0.2/an)'],
                ['Rare', '(≤ 1/an)'],
                ['Peu fréquent', '(1‑15/an)'],
                ['Fréquent', '(16‑50/an)'],
                ['Très fréquent', '(51‑350/an)'],
                ['Permanent', '(> 350/an)']
            ];

            // --- Couleurs (0 = Faible … 5 = Critique) ---
            const matrixColors = [
                ['#4CAF50', '#4CAF50', '#4CAF50', '#4CAF50', '#FFEB3B', '#FFEB3B'], // Faible
                ['#4CAF50', '#4CAF50', '#4CAF50', '#FFEB3B', '#FF9800', '#FF9800'], // Modéré
                ['#4CAF50', '#4CAF50', '#FFEB3B', '#FF9800', '#e23535', '#e23535'], // Moyen
                ['#4CAF50', '#FFEB3B', '#FF9800', '#e23535', '#a10707', '#a10707'], // Fort
                ['#FFEB3B', '#FF9800', '#e23535', '#a10707', '#a10707', '#a10707'], // Majeur
                ['#FF9800', '#e23535', '#a10707', '#a10707', '#a10707', '#a10707'] // Critique
            ];

            // --- Mappings valeur → index (impact faible=0 … critique=5) ---
            const impactToIndex = {
                FAIBLE: 0,
                MODERE: 1,
                MOYEN: 2,
                FORT: 3,
                MAJEUR: 4,
                CRITIQUE: 5
            };
            const frequencyToIndex = {
                EXTREMEMENT_RARE: 0,
                RARE: 1,
                PEU_FREQUENT: 2,
                FREQUENT: 3,
                TRES_FREQUENT: 4,
                PERMANENT: 5
            };

            // --- Grouper pour éviter collision ---
            const buckets = {};
            for (const [sec, {
                    impact,
                    frequence
                }] of Object.entries(riskData)) {
                const i = impactToIndex[impact];
                const j = frequencyToIndex[frequence];
                const key = `${i}-${j}`;
                (buckets[key] ||= []).push(sec);
            }

            // --- construire dataPoints ---
            const dataPoints = [];
            for (const [sec, {
                    impact,
                    frequence
                }] of Object.entries(riskData)) {
                const i = impactToIndex[impact];
                const j = frequencyToIndex[frequence];
                const bucket = buckets[`${i}-${j}`];
                const idx = bucket.indexOf(sec);
                const cnt = bucket.length;
                let dx = 0,
                    dy = 0;
                if (cnt > 1) {
                    const angle = (idx / cnt) * 2 * Math.PI;
                    const r = 0.2;
                    dx = r * Math.cos(angle);
                    dy = r * Math.sin(angle);
                }
                dataPoints.push({
                    x: j + dx,
                    y: i + dy,
                    sec,
                    impact,
                    frequence
                });
            }

            // --- Plugin pour dessiner la grille + labels SEC ---
            const riskMatrixPlugin = {
                id: 'riskMatrixPlugin',
                beforeDraw: function(chart) {
                    const {
                        ctx,
                        chartArea
                    } = chart;
                    const {
                        left,
                        right,
                        top,
                        bottom
                    } = chartArea;

                    // Calculer les dimensions des cellules
                    const cellWidth = (right - left) / 6;
                    const cellHeight = (bottom - top) / 6;

                    // Dessiner le fond coloré de la matrice
                    for (let i = 0; i < 6; i++) {
                        for (let j = 0; j < 6; j++) {
                            const x = left + j * cellWidth;
                            const y = top + i * cellHeight;

                            ctx.fillStyle = matrixColors[5 - i][j]; // Inversé pour correspondre à l'affichage
                            ctx.fillRect(x, y, cellWidth, cellHeight);

                            // Ajouter une bordure légère
                            ctx.strokeStyle = '#fff';
                            ctx.lineWidth = 1;
                            ctx.strokeRect(x, y, cellWidth, cellHeight);
                        }
                    }
                },
                afterDraw: function(chart) {
                    const {
                        ctx,
                        chartArea
                    } = chart;
                    const {
                        left,
                        right,
                        top,
                        bottom
                    } = chartArea;

                    // Calculer les dimensions des cellules
                    const cellWidth = (right - left) / 6;
                    const cellHeight = (bottom - top) / 6;

                    // Dessiner les labels des secteurs et les points blancs
                    ctx.textAlign = 'center';
                    ctx.textBaseline = 'middle';

                    for (const point of dataPoints) {
                        const x = left + (point.x + 0.5) * cellWidth;
                        const y = top + (5.5 - point.y) * cellHeight; // Inversé pour correspondre à l'affichage

                        // Dessiner le label du secteur
                        ctx.fillStyle = '#000';
                        ctx.font = 'bold 11px Arial';
                        ctx.fillText(point.sec, x, y - 8); // Décalé vers le haut

                        // Dessiner le point blanc en dessous du label
                        ctx.fillStyle = '#fff';
                        ctx.beginPath();
                        ctx.arc(x, y + 8, 4, 0, 2 * Math.PI); // Point blanc de rayon 4px
                        ctx.fill();

                        // Ajouter une bordure noire au point blanc
                        ctx.strokeStyle = '#000';
                        ctx.lineWidth = 1;
                        ctx.stroke();
                    }
                }
            };

            // Enregistrer le plugin
            // Chart.register(riskMatrixPlugin);

            // --- Configuration Chart.js ---
            const config = {
                type: 'scatter',
                data: {
                    datasets: [{
                        data: dataPoints,
                        pointRadius: 0, // Masquer les points par défaut car on dessine les labels
                        pointBackgroundColor: 'transparent',
                        pointBorderColor: 'transparent',
                        pointBorderWidth: 0,
                        hoverRadius: 15,
                        hoverBackgroundColor: 'rgba(0,0,0,0.1)'
                    }]
                },
                plugins: [riskMatrixPlugin],
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        intersect: false,
                        mode: 'point'
                    },
                    scales: {
                        x: {
                            type: 'linear',
                            min: -0.5,
                            max: 5.5,
                            title: {
                                display: true,
                                text: 'Fréquence',
                                font: {
                                    size: 16,
                                    weight: 'bold',
                                    
                                },
                                padding: {
                                    top: 15
                                }
                            },
                            grid: {
                                display: false
                            },
                            ticks: {
                                values: [0.5, 1.5, 2.5, 3.5, 4.5, 5.5], // Positionner au centre des cellules
                                font: {
                                    size: 10
                                },
                                maxRotation: 0, // Empêcher la rotation des labels
                                minRotation: 0, // Forcer les labels à rester horizontaux
                                callback: function(value) {

                                    if (value == 5.5) {
                                        return '';
                                    }

                                    // Convertir la position du centre vers l'index
                                    const index = Math.floor(value);

                                    console.log('value et index:', value, index);
                                    if (index >= 0 && index < frequencyLabels.length) {
                                        return frequencyLabels[index];
                                    }
                                    return '';
                                }
                            }
                        },
                        y: {
                            type: 'linear',
                            min: -0.5,
                            max: 5.5,
                            title: {
                                display: true,
                                text: 'Impact',
                                font: {
                                    size: 16,
                                    weight: 'bold'
                                },
                                padding: {
                                    bottom: 15
                                }
                            },
                            grid: {
                                display: false
                            },
                            ticks: {
                                values: [0.5, 1.5, 2.5, 3.5, 4.5],
                                font: {
                                    size: 10
                                },
                                callback: function(value) {
                                    // Convertir la position du centre vers l'index
                                    if (value == 5.5) {
                                        return '';

                                    }

                                    const index = Math.floor(value);

                                    if (index >= 0 && index < impactLabels.length) {
                                        return impactLabels[index];
                                    }
                                    return '';
                                }
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                title: function() {
                                    return '';
                                },
                                label: function(context) {
                                    const point = context.raw;
                                    const impactLabel = impactLabels[impactToIndex[point.impact]][0];
                                    const frequenceLabel = frequencyLabels[frequencyToIndex[point.frequence]][
                                    0];
                                    return [
                                        `Secteur: ${point.sec}`,
                                        `Impact: ${impactLabel}`,
                                        `Fréquence: ${frequenceLabel}`
                                    ];
                                }
                            },
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            titleColor: '#fff',
                            bodyColor: '#fff',
                            padding: 12,
                            displayColors: false,
                            titleFont: {
                                size: 14,
                                weight: 'bold'
                            },
                            bodyFont: {
                                size: 12
                            }
                        },
                        title: {
                            display: true,
                            text: "Matrice des Risques (impact net)",
                            font: {
                                size: 24,
                                weight: 'bold'
                            },
                            padding: {
                                bottom: 30
                            }
                        }
                    }
                }
            };

            // --- Initialisation ---
            const ctx = document.getElementById('riskMatrixChart').getContext('2d');
            const chart = new Chart(ctx, config);
        })();
    </script>
    <script>
        (function() {
            const divBrut = document.getElementById("riskMatrixChartBrut");
            const riskDataBrut = JSON.parse(divBrut.getAttribute("data-graphe"));
            console.log(riskDataBrut);

            // --- Labels ---
            // Impact du plus faible (index 0) au plus critique (index 5)
            const impactLabels = [
                ['Faible', '(≤ 10 kXOF)'],
                ['Modéré', '(10‑100 kXOF)'],
                ['Moyen', '(100 k‑1 MXOF)'],
                ['Fort', '(1‑10 MXOF)'],
                ['Majeur', '(10‑100 MXOF)'],
                ['Critique', '(> 100 MXOF)']
            ];
            const frequencyLabels = [
                ['Extrêmement rare', '(≤ 0.2/an)'],
                ['Rare', '(≤ 1/an)'],
                ['Peu fréquent', '(1‑15/an)'],
                ['Fréquent', '(16‑50/an)'],
                ['Très fréquent', '(51‑350/an)'],
                ['Permanent', '(> 350/an)']
            ];

            // --- Couleurs (0 = Faible … 5 = Critique) ---
            const matrixColorsBrut = [
                ['#4CAF50', '#4CAF50', '#4CAF50', '#4CAF50', '#FFEB3B', '#FFEB3B'], // Faible
                ['#4CAF50', '#4CAF50', '#4CAF50', '#FFEB3B', '#FF9800', '#FF9800'], // Modéré
                ['#4CAF50', '#4CAF50', '#FFEB3B', '#FF9800', '#e23535', '#e23535'], // Moyen
                ['#4CAF50', '#FFEB3B', '#FF9800', '#e23535', '#DCDCDC', '#DCDCDC'], // Fort
                ['#FFEB3B', '#FF9800', '#e23535', '#DCDCDC', '#DCDCDC', '#DCDCDC'], // Majeur
                ['#FF9800', '#e23535', '#DCDCDC', '#DCDCDC', '#DCDCDC', '#DCDCDC'] // Critique
            ];

            // --- Mappings valeur → index (impact faible=0 … critique=5) ---
            const impactToIndex = {
                FAIBLE: 0,
                MODERE: 1,
                MOYEN: 2,
                FORT: 3,
                MAJEUR: 4,
                CRITIQUE: 5
            };
            const frequencyToIndex = {
                EXTREMEMENT_RARE: 0,
                RARE: 1,
                PEU_FREQUENT: 2,
                FREQUENT: 3,
                TRES_FREQUENT: 4,
                PERMANENT: 5
            };

            // --- Grouper pour éviter collision ---
            const buckets = {};
            for (const [sec, {
                    impact,
                    frequence
                }] of Object.entries(riskDataBrut)) {
                const i = impactToIndex[impact];
                const j = frequencyToIndex[frequence];
                const key = `${i}-${j}`;
                (buckets[key] ||= []).push(sec);
            }

            // --- construire dataPoints ---
            const dataPoints = [];
            for (const [sec, {
                    impact,
                    frequence
                }] of Object.entries(riskDataBrut)) {
                const i = impactToIndex[impact];
                const j = frequencyToIndex[frequence];
                const bucket = buckets[`${i}-${j}`];
                const idx = bucket.indexOf(sec);
                const cnt = bucket.length;
                let dx = 0,
                    dy = 0;
                if (cnt > 1) {
                    const angle = (idx / cnt) * 2 * Math.PI;
                    const r = 0.2;
                    dx = r * Math.cos(angle);
                    dy = r * Math.sin(angle);
                }
                dataPoints.push({
                    x: j + dx,
                    y: i + dy,
                    sec,
                    impact,
                    frequence
                });
            }

            // --- Plugin pour dessiner la grille + labels SEC ---
            const riskMatrixPluginBrut = {
                id: 'riskMatrixPluginBrut',
                beforeDraw: function(chart) {
                    const {
                        ctx,
                        chartArea
                    } = chart;
                    const {
                        left,
                        right,
                        top,
                        bottom
                    } = chartArea;

                    // Calculer les dimensions des cellules
                    const cellWidth = (right - left) / 6;
                    const cellHeight = (bottom - top) / 6;

                    // Dessiner le fond coloré de la matrice
                    for (let i = 0; i < 6; i++) {
                        for (let j = 0; j < 6; j++) {
                            const x = left + j * cellWidth;
                            const y = top + i * cellHeight;

                            ctx.fillStyle = matrixColorsBrut[5 - i][
                            j]; // Inversé pour correspondre à l'affichage
                            ctx.fillRect(x, y, cellWidth, cellHeight);

                            // Ajouter une bordure légère
                            ctx.strokeStyle = '#fff';
                            ctx.lineWidth = 1;
                            ctx.strokeRect(x, y, cellWidth, cellHeight);
                        }
                    }
                },
                afterDraw: function(chart) {
                    const {
                        ctx,
                        chartArea
                    } = chart;
                    const {
                        left,
                        right,
                        top,
                        bottom
                    } = chartArea;

                    // Calculer les dimensions des cellules
                    const cellWidth = (right - left) / 6;
                    const cellHeight = (bottom - top) / 6;

                    // Dessiner les labels des secteurs et les points blancs
                    ctx.textAlign = 'center';
                    ctx.textBaseline = 'middle';

                    for (const point of dataPoints) {
                        const x = left + (point.x + 0.5) * cellWidth;
                        const y = top + (5.5 - point.y) * cellHeight; // Inversé pour correspondre à l'affichage

                        // Dessiner le label du secteur
                        ctx.fillStyle = '#000';
                        ctx.font = 'bold 11px Arial';
                        ctx.fillText(point.sec, x, y - 8); // Décalé vers le haut

                        // Dessiner le point blanc en dessous du label
                        ctx.fillStyle = '#fff';
                        ctx.beginPath();
                        ctx.arc(x, y + 8, 4, 0, 2 * Math.PI); // Point blanc de rayon 4px
                        ctx.fill();

                        // Ajouter une bordure noire au point blanc
                        ctx.strokeStyle = '#000';
                        ctx.lineWidth = 1;
                        ctx.stroke();
                    }
                }
            };

            // Enregistrer le plugin
            // Chart.register(riskMatrixPluginBrut);

            // --- Configuration Chart.js ---
            const configBrut = {
                type: 'scatter',
                data: {
                    datasets: [{
                        data: dataPoints,
                        pointRadius: 0, // Masquer les points par défaut car on dessine les labels
                        pointBackgroundColor: 'transparent',
                        pointBorderColor: 'transparent',
                        pointBorderWidth: 0,
                        hoverRadius: 15,
                        hoverBackgroundColor: 'rgba(0,0,0,0.1)'
                    }]
                },
                plugins: [riskMatrixPluginBrut],
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        intersect: false,
                        mode: 'point'
                    },
                    scales: {
                        x: {
                            type: 'linear',
                            min: -0.5,
                            max: 5.5,
                            title: {
                                display: true,
                                text: 'Fréquence',
                                font: {
                                    size: 16,
                                    weight: 'bold'
                                },
                                padding: {
                                    top: 15
                                }
                            },
                            grid: {
                                display: false
                            },
                            ticks: {
                                values: [0.5, 1.5, 2.5, 3.5, 4.5, 5.5], // Positionner au centre des cellules
                                font: {
                                    size: 10
                                },
                                maxRotation: 0, // Empêcher la rotation des labels
                                minRotation: 0, // Forcer les labels à rester horizontaux
                                callback: function(value) {

                                    if (value == 5.5) {
                                        return '';
                                    }

                                    // Convertir la position du centre vers l'index
                                    const index = Math.floor(value);

                                    console.log('value et index:', value, index);
                                    if (index >= 0 && index < frequencyLabels.length) {
                                        return frequencyLabels[index];
                                    }
                                    return '';
                                }
                            }
                        },
                        y: {
                            type: 'linear',
                            min: -0.5,
                            max: 5.5,
                            title: {
                                display: true,
                                text: 'Impact brut',
                                font: {
                                    size: 16,
                                    weight: 'bold'
                                },
                                padding: {
                                    bottom: 15
                                }
                            },
                            grid: {
                                display: false
                            },
                            ticks: {
                                values: [0.5, 1.5, 2.5, 3.5, 4.5],
                                font: {
                                    size: 10
                                },
                                callback: function(value) {
                                    // Convertir la position du centre vers l'index
                                    if (value == 5.5) {
                                        return '';

                                    }

                                    const index = Math.floor(value);

                                    if (index >= 0 && index < impactLabels.length) {
                                        return impactLabels[index];
                                    }
                                    return '';
                                }
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                title: function() {
                                    return '';
                                },
                                label: function(context) {
                                    const point = context.raw;
                                    const impactLabel = impactLabels[impactToIndex[point.impact]][0];
                                    const frequenceLabel = frequencyLabels[frequencyToIndex[point.frequence]][
                                    0];
                                    return [
                                        `Secteur: ${point.sec}`,
                                        `Impact: ${impactLabel}`,
                                        `Fréquence: ${frequenceLabel}`
                                    ];
                                }
                            },
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            titleColor: '#fff',
                            bodyColor: '#fff',
                            padding: 12,
                            displayColors: false,
                            titleFont: {
                                size: 14,
                                weight: 'bold'
                            },
                            bodyFont: {
                                size: 12
                            }
                        },
                        title: {
                            display: true,
                            text: "Matrice des Risques (impact brut)",
                            font: {
                                size: 24,
                                weight: 'bold'
                            },
                            padding: {
                                bottom: 30
                            }
                        }
                    }
                }

            };
            // --- Initialisation ---
            const ctx = document.getElementById('riskMatrixChartBrut').getContext('2d');
            const chart = new Chart(ctx, configBrut);
        })();
    </script>

    <script>
        (function() {
            const divDmr = document.getElementById("riskDMRChart");
            const riskDataDmr = JSON.parse(divDmr.getAttribute("data-graphe"));
            console.log(riskDataDmr);

            // --- Labels ---
            // Impact du plus faible (index 0) au plus critique (index 5)
            const impactLabels = [
                ['Efficace', ''],
                ['Acceptable', ''],
                ['Conforme', ''],
                ['Insuffisant', ''],
                ['Inexistant', ''],
            ];
            const frequencyLabels = [
                ['Faible', ''],
                ['Moyen', ''],
                ['Fort', ''],
                ['Critique', ''],
                ['Inacceptable', ''],
                
            ];

            // --- Couleurs (0 = Faible … 5 = Critique) ---
            const matrixColorsDmr = [
                ['#4CAF50', '#FFEB3B', '#FF9800', '#e23535', '#DCDCDC'], // Faible
                ['#4CAF50', '#FFEB3B', '#FF9800', '#e23535', '#DCDCDC'], // Modéré
                ['#4CAF50', '#FFEB3B', '#FF9800', '#e23535', '#DCDCDC'], // Moyen
                ['#4CAF50', '#FFEB3B', '#FF9800', '#e23535', '#DCDCDC'], // Fort
                ['#4CAF50', '#FFEB3B', '#FF9800', '#e23535', '#DCDCDC'], // Majeur
            ];

            // --- Mappings valeur → index (impact faible=0 … critique=5) ---
            const impactToIndex = {
                EFFICACE: 0,
                ACCEPTABLE: 1,
                CONFORME: 2,
                INSUFFISANT: 3,
                INEXISTANT: 4,
            };
            const frequencyToIndex = {
                FAIBLE: 0,
                MOYEN: 1,
                FORT: 2,
                CRITIQUE: 3,
                INACCEPTABLE: 4,
            };

            // --- Grouper pour éviter collision ---
            const buckets = {};
            for (const [sec, {
                    impact,
                    frequence
                }] of Object.entries(riskDataDmr)) {
                const i = impactToIndex[impact];
                const j = frequencyToIndex[frequence];
                const key = `${i}-${j}`;
                (buckets[key] ||= []).push(sec);
            }

            // --- construire dataPoints ---
            const dataPoints = [];
            for (const [sec, {
                    impact,
                    frequence
                }] of Object.entries(riskDataDmr)) {
                const i = impactToIndex[impact];
                const j = frequencyToIndex[frequence];
                const bucket = buckets[`${i}-${j}`];
                const idx = bucket.indexOf(sec);
                const cnt = bucket.length;
                let dx = 0,
                    dy = 0;
                if (cnt > 1) {
                    const angle = (idx / cnt) * 2 * Math.PI;
                    const r = 0.2;
                    dx = r * Math.cos(angle);
                    dy = r * Math.sin(angle);
                }
                dataPoints.push({
                    x: j + dx,
                    y: i + dy,
                    sec,
                    impact,
                    frequence
                });
            }

            // --- Plugin pour dessiner la grille + labels SEC ---
            const riskMatrixPluginDmr = {
                id: 'riskMatrixPluginDmr',
                beforeDraw: function(chart) {
                    const {
                        ctx,
                        chartArea
                    } = chart;
                    const {
                        left,
                        right,
                        top,
                        bottom
                    } = chartArea;

                    // Calculer les dimensions des cellules
                    const cellWidth = (right - left) / 5;
                    const cellHeight = (bottom - top) / 5;

                    // Dessiner le fond coloré de la matrice
                    for (let i = 0; i < 5; i++) {
                        for (let j = 0; j < 5; j++) {
                            const x = left + j * cellWidth;
                            const y = top + i * cellHeight;

                            ctx.fillStyle = matrixColorsDmr[4 - i][
                            j]; // Inversé pour correspondre à l'affichage
                            ctx.fillRect(x, y, cellWidth, cellHeight);

                            // Ajouter une bordure légère
                            ctx.strokeStyle = '#fff';
                            ctx.lineWidth = 1;
                            ctx.strokeRect(x, y, cellWidth, cellHeight);
                        }
                    }
                },
                afterDraw: function(chart) {
                    const {
                        ctx,
                        chartArea
                    } = chart;
                    const {
                        left,
                        right,
                        top,
                        bottom
                    } = chartArea;

                    // Calculer les dimensions des cellules
                    const cellWidth = (right - left) / 5;
                    const cellHeight = (bottom - top) / 5;

                    // Dessiner les labels des secteurs et les points blancs
                    ctx.textAlign = 'center';
                    ctx.textBaseline = 'middle';

                    for (const point of dataPoints) {
                        const x = left + (point.x + 0.5) * cellWidth;
                        const y = top + (4.5 - point.y) * cellHeight; // Inversé pour correspondre à l'affichage

                        // Dessiner le label du secteur
                        ctx.fillStyle = '#000';
                        ctx.font = 'bold 11px Arial';
                        ctx.fillText(point.sec, x, y - 8); // Décalé vers le haut

                        // Dessiner le point blanc en dessous du label
                        ctx.fillStyle = '#fff';
                        ctx.beginPath();
                        ctx.arc(x, y + 8, 4, 0, 2 * Math.PI); // Point blanc de rayon 4px
                        ctx.fill();

                        // Ajouter une bordure noire au point blanc
                        ctx.strokeStyle = '#000';
                        ctx.lineWidth = 1;
                        ctx.stroke();
                    }
                }
            };

            // Enregistrer le plugin
            // Chart.register(riskMatrixPluginBrut);

            // --- Configuration Chart.js ---
            const configBrut = {
                type: 'scatter',
                data: {
                    datasets: [{
                        data: dataPoints,
                        pointRadius: 0, // Masquer les points par défaut car on dessine les labels
                        pointBackgroundColor: 'transparent',
                        pointBorderColor: 'transparent',
                        pointBorderWidth: 0,
                        hoverRadius: 15,
                        hoverBackgroundColor: 'rgba(0,0,0,0.1)'
                    }]
                },
                plugins: [riskMatrixPluginDmr],
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        intersect: false,
                        mode: 'point'
                    },
                    scales: {
                        x: {
                            type: 'linear',
                            min: -0.55,
                            max: 4.5,
                            title: {
                                display: true,
                                text: 'Cotation moyenne',
                                font: {
                                    size: 16,
                                    weight: 'bold'
                                },
                                padding: {
                                    top: 15
                                }
                            },
                            grid: {
                                display: false
                            },
                            ticks: {
                                values: [0.5, 1.5, 2.5, 3.5, 4.5], // Positionner au centre des cellules
                                font: {
                                    size: 10
                                },
                                maxRotation: 0, // Empêcher la rotation des labels
                                minRotation: 0, // Forcer les labels à rester horizontaux
                                callback: function(value) {

                                    if (value == 4.5) {
                                        return '';
                                    }

                                    // Convertir la position du centre vers l'index
                                    const index = Math.floor(value);

                                    console.log('value et index:', value, index);
                                    if (index >= 0 && index < frequencyLabels.length) {
                                        return frequencyLabels[index];
                                    }
                                    return '';
                                }
                            }
                        },
                        y: {
                            type: 'linear',
                            min: -0.55,
                            max: 4.5,
                            title: {
                                display: true,
                                text: 'Appréciation',
                                font: {
                                    size: 16,
                                    weight: 'bold'
                                },
                                padding: {
                                    bottom: 15
                                }
                            },
                            grid: {
                                display: false
                            },
                            ticks: {
                                values: [0.5, 1.5, 2.5, 3.5, 4.5],
                                font: {
                                    size: 10
                                },
                                callback: function(value) {
                                    // Convertir la position du centre vers l'index
                                    if (value == 4.5) {
                                        return '';

                                    }

                                    const index = Math.floor(value);

                                    if (index >= 0 && index < impactLabels.length) {
                                        return impactLabels[index];
                                    }
                                    return '';
                                }
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                title: function() {
                                    return '';
                                },
                                label: function(context) {
                                    const point = context.raw;
                                    const impactLabel = impactLabels[impactToIndex[point.impact]][0];
                                    const frequenceLabel = frequencyLabels[frequencyToIndex[point.frequence]][
                                    0];
                                    return [
                                        `Secteur: ${point.sec}`,
                                        `Appréciation: ${impactLabel}`,
                                        `Cotation: ${frequenceLabel}`
                                    ];
                                }
                            },
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            titleColor: '#fff',
                            bodyColor: '#fff',
                            padding: 12,
                            displayColors: false,
                            titleFont: {
                                size: 14,
                                weight: 'bold'
                            },
                            bodyFont: {
                                size: 12
                            }
                        },
                        title: {
                            display: true,
                            text: "Dispositif de contrôle et de maîtrise des risques (% cotation moyenne)",
                            font: {
                                size: 24,
                                weight: 'bold'
                            },
                            padding: {
                                bottom: 30
                            }
                        }
                    }
                }

            };
            // --- Initialisation ---
            const ctx = document.getElementById('riskDMRChart').getContext('2d');
            const chart = new Chart(ctx, configBrut);
        })();
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const chartElement = document.querySelector("#causesLevelOne");
            const rawData = chartElement.getAttribute("data-causes");

            if (!rawData) return;

            const causes = JSON.parse(rawData);

            const labels = [];
            const series = [];

            Object.keys(causes).forEach((key) => {
                labels.push(causes[key].libelle);
                series.push(causes[key].nb);
            });

            function generateColors(n) {
                const colors = [];
                for (let i = 0; i < n; i++) {
                    const hue = Math.round((360 / n) * i);
                    colors.push(`hsl(${hue}, 70%, 50%)`);
                }
                return colors;
            }

            const options = {
                series: series,
                chart: {
                    height: 360,
                    type: 'pie',
                },
                legend: {
                    position: 'bottom',
                    show: false,
                },
                plotOptions: {
                    pie: {
                        customScale: 0.9,
                        donut: {
                            size: '80%'
                        }
                    }
                },
                colors: generateColors(series.length),
                dataLabels: {
                    enabled: true
                },
                labels: labels,
                responsive: [{
                    breakpoint: 480,
                    options: {
                        chart: {
                            height: 300
                        },
                        legend: {
                            position: 'bottom'
                        }
                    }
                }]
            };

            const chart = new ApexCharts(chartElement, options);
            chart.render();
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const chartElement = document.querySelector("#causesLevelTwo");
            const rawData = chartElement.getAttribute("data-causes");

            if (!rawData) return;

            const causes = JSON.parse(rawData);

            const labels = [];
            const series = [];

            Object.keys(causes).forEach((key) => {
                labels.push(causes[key].libelle);
                series.push(causes[key].nb);
            });

            function generateColors(n) {
                const colors = [];
                for (let i = 0; i < n; i++) {
                    const hue = Math.round((360 / n) * i);
                    colors.push(`hsl(${hue}, 70%, 50%)`);
                }
                return colors;
            }

            const options = {
                series: series,
                chart: {
                    height: 360,
                    type: 'pie',
                },
                legend: {
                    position: 'bottom',
                    show: false,
                },
                plotOptions: {
                    pie: {
                        customScale: 0.9,
                        donut: {
                            size: '80%'
                        }
                    }
                },
                colors: generateColors(series.length),
                dataLabels: {
                    enabled: true
                },
                labels: labels,
                responsive: [{
                    breakpoint: 480,
                    options: {
                        chart: {
                            height: 300
                        },
                        legend: {
                            position: 'bottom'
                        }
                    }
                }]
            };

            const chart = new ApexCharts(chartElement, options);
            chart.render();
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const chartElement = document.querySelector("#causesLevelThree");
            const rawData = chartElement.getAttribute("data-causes");

            if (!rawData) return;

            const causes = JSON.parse(rawData);

            const labels = [];
            const series = [];

            Object.keys(causes).forEach((key) => {
                labels.push(causes[key].libelle);
                series.push(causes[key].nb);
            });

            function generateColors(n) {
                const colors = [];
                for (let i = 0; i < n; i++) {
                    const hue = Math.round((360 / n) * i);
                    colors.push(`hsl(${hue}, 70%, 50%)`);
                }
                return colors;
            }

            const options = {
                series: series,
                chart: {
                    height: 360,
                    type: 'pie',
                },
                legend: {
                    position: 'bottom',
                    show: false,
                },
                plotOptions: {
                    pie: {
                        customScale: 0.9,
                        donut: {
                            size: '80%'
                        }
                    }
                },
                colors: generateColors(series.length),
                dataLabels: {
                    enabled: true
                },
                labels: labels,
                responsive: [{
                    breakpoint: 480,
                    options: {
                        chart: {
                            height: 300
                        },
                        legend: {
                            position: 'bottom'
                        }
                    }
                }]
            };

            const chart = new ApexCharts(chartElement, options);
            chart.render();
        });
    </script>
    <script>
        document.getElementById('selectLevel').addEventListener('change', function() {
            const levelOne = document.getElementById('levelOne');
            const levelTwo = document.getElementById('levelTwo');
            const levelThree = document.getElementById('levelThree');

            if (this.value === 'level1') {
                levelOne.style.display = 'block';
                levelThree.style.display = 'none';
                levelTwo.style.display = 'none';
            } else if (this.value === 'level2') {
                levelOne.style.display = 'none';
                levelThree.style.display = 'none';
                levelTwo.style.display = 'block';

            } else if (this.value === 'level3') {
                levelOne.style.display = 'none';
                levelThree.style.display = 'block';
                levelTwo.style.display = 'none';
            }

        });
    </script>
@endsection
