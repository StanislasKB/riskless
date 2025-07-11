<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Matrice d'Évaluation des Risques</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
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
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
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
</head>
<body>
    <div class="container">
        <div class="chart-container">
            <canvas id="riskMatrixChart"></canvas>
        </div>

        <div class="legend">
            <div class="legend-item">
                <div class="legend-color" style="background-color: #4CAF50;"></div>
                <span>Risque Acceptable</span>
            </div>
            <div class="legend-item">
                <div class="legend-color" style="background-color: #FFEB3B;"></div>
                <span>Risque Modéré</span>
            </div>
            <div class="legend-item">
                <div class="legend-color" style="background-color: #FF9800;"></div>
                <span>Risque Élevé</span>
            </div>
            <div class="legend-item">
                <div class="legend-color" style="background-color: #FF5722;"></div>
                <span>Risque Critique</span>
            </div>
            <div class="legend-item">
                <div class="legend-color" style="background-color: #D32F2F;"></div>
                <span>Risque Inacceptable</span>
            </div>
        </div>

        <div class="info-panel">
            <h3>Analyse des Risques</h3>
            <div class="risk-summary" id="riskSummary"></div>
        </div>
    </div>

    <script>
        // --- Données complètes ---
        const riskData = {
            "SEC1": {
                "impact": "FAIBLE",
                "frequence": "EXTREMEMENT_RARE"
            },
            "SEC2": {
                "impact": "FAIBLE",
                "frequence": "PEU_FREQUENT"
            },
            "SEC3": {
                "impact": "FAIBLE",
                "frequence": "EXTREMEMENT_RARE"
            },
            "SEC4": {
                "impact": "MODERE",
                "frequence": "RARE"
            },
            "SEC5": {
                "impact": "MOYEN",
                "frequence": "FREQUENT"
            },
            "SEC6": {
                "impact": "FORT",
                "frequence": "TRES_FREQUENT"
            },
            "SEC7": {
                "impact": "MAJEUR",
                "frequence": "RARE"
            },
            "SEC8": {
                "impact": "CRITIQUE",
                "frequence": "PEU_FREQUENT"
            },
            "SEC9": {
                "impact": "MODERE",
                "frequence": "PEU_FREQUENT"
            },
            "SEC10": {
                "impact": "FAIBLE",
                "frequence": "PERMANENT"
            },
            "SEC11": {
                "impact": "MOYEN",
                "frequence": "TRES_FREQUENT"
            },
            "SEC12": {
                "impact": "CRITIQUE",
                "frequence": "RARE"
            }
        };

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
            ['#4CAF50', '#4CAF50', '#FFEB3B', '#FF9800', '#FF5722', '#D32F2F'], // Moyen
            ['#4CAF50', '#FFEB3B', '#FF9800', '#FF5722', '#D32F2F', '#D32F2F'], // Fort
            ['#FFEB3B', '#FF9800', '#FF5722', '#D32F2F', '#D32F2F', '#D32F2F'], // Majeur
            ['#FF9800', '#FF5722', '#D32F2F', '#D32F2F', '#D32F2F', '#D32F2F']  // Critique
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
        for (const [sec, { impact, frequence }] of Object.entries(riskData)) {
            const i = impactToIndex[impact];
            const j = frequencyToIndex[frequence];
            const key = `${i}-${j}`;
            (buckets[key] ||= []).push(sec);
        }

        // --- Construire dataPoints ---
        const dataPoints = [];
        for (const [sec, { impact, frequence }] of Object.entries(riskData)) {
            const i = impactToIndex[impact];
            const j = frequencyToIndex[frequence];
            const bucket = buckets[`${i}-${j}`];
            const idx = bucket.indexOf(sec);
            const cnt = bucket.length;
            let dx = 0, dy = 0;
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
                const { ctx, chartArea } = chart;
                const { left, right, top, bottom } = chartArea;

                // Calculer les dimensions des cellules
                const cellWidth = (right - left) / 6;
                const cellHeight = (bottom - top) / 6;

                // Dessiner le fond coloré de la matrice
                for (let i = 0; i < 6; i++) {
                    for (let j = 0; j < 6; j++) {
                        const x = left + j * cellWidth;
                        const y = top + i * cellHeight;

                        ctx.fillStyle = matrixColors[5-i][j]; // Inversé pour correspondre à l'affichage
                        ctx.fillRect(x, y, cellWidth, cellHeight);

                        // Ajouter une bordure légère
                        ctx.strokeStyle = '#fff';
                        ctx.lineWidth = 1;
                        ctx.strokeRect(x, y, cellWidth, cellHeight);
                    }
                }
            },
            afterDraw: function(chart) {
                const { ctx, chartArea } = chart;
                const { left, right, top, bottom } = chartArea;

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
        Chart.register(riskMatrixPlugin);

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

                                console.log('value et index:', value,index);
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
                                if (value==5.5) {
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
                                const frequenceLabel = frequencyLabels[frequencyToIndex[point.frequence]][0];
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
                        text: "Matrice d'Évaluation des Risques",
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

        // --- Générer le résumé des risques ---
        function generateRiskSummary() {
            const summary = document.getElementById('riskSummary');
            const riskLevels = {
                'Critique': [],
                'Majeur': [],
                'Élevé': [],
                'Modéré': [],
                'Acceptable': []
            };

            // Classifier les risques
            for (const [sec, { impact, frequence }] of Object.entries(riskData)) {
                const i = impactToIndex[impact];
                const j = frequencyToIndex[frequence];
                const color = matrixColors[i][j];

                let level;
                if (color === '#D32F2F') level = 'Critique';
                else if (color === '#FF5722') level = 'Majeur';
                else if (color === '#FF9800') level = 'Élevé';
                else if (color === '#FFEB3B') level = 'Modéré';
                else level = 'Acceptable';

                riskLevels[level].push({
                    sec,
                    impact: impactLabels[i][0],
                    frequence: frequencyLabels[j][0]
                });
            }

            // Afficher le résumé
            for (const [level, risks] of Object.entries(riskLevels)) {
                if (risks.length > 0) {
                    const div = document.createElement('div');
                    div.className = 'risk-item';
                    div.innerHTML = `
                        <h4>${level} (${risks.length} risque${risks.length > 1 ? 's' : ''})</h4>
                        <ul>
                            ${risks.map(risk => `<li><strong>${risk.sec}</strong>: ${risk.impact} / ${risk.frequence}</li>`).join('')}
                        </ul>
                    `;
                    summary.appendChild(div);
                }
            }
        }

        // Générer le résumé au chargement
        generateRiskSummary();
    </script>
</body>
</html>
