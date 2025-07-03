@extends('service_manager.components.app')
@section('title')
    Plan d'actions
@endsection
@section('page_css')
    <link href="/admin/assets/plugins/select2/css/select2.min.css" rel="stylesheet" />
    <link href="/admin/assets/plugins/select2/css/select2-bootstrap4.css" rel="stylesheet" />
@endsection
@section('main_content')
    @include('service_manager.pages.plan_action.layouts.add_action_modal')
    @include('service_manager.pages.plan_action.layouts.header')
    @include('service_manager.pages.plan_action.layouts.body')
    @include('service_manager.pages.plan_action.layouts.graphe')
@endsection
@section('page_js')
    <script src="/admin/assets/plugins/select2/js/select2.min.js"></script>
    <script>
        // $('.single-select').select2({
        //     theme: 'bootstrap4',
        //     width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
        //     placeholder: $(this).data('placeholder'),
        //     allowClear: Boolean($(this).data('allow-clear')),

        // });

        $('#addActionModal').on('shown.bs.modal', function() {
            const selects = $(this).find('.single-select');

            selects.select2({
                theme: 'bootstrap4',
                width: '100%',
                placeholder: function() {
                    return $(this).data('placeholder');
                },
                allowClear: function() {
                    return Boolean($(this).data('allow-clear'));
                }
            });

            selects.on('select2:open', function() {
                // Correction du z-index pour qu'il s'affiche bien
                $('.select2-container--open').css('z-index', 1060);

                // Corrige le champ de recherche non cliquable (focus bloqué)
                setTimeout(function() {
                    document.querySelector('.select2-container--open .select2-search__field')
                        ?.focus();
                }, 0);
            });
        });


        $(document).ready(function() {
            const dataTableLangFr = {
                "sProcessing": "Traitement en cours...",
                "sSearch": "Rechercher&nbsp;:",
                "sLengthMenu": "Afficher _MENU_ éléments",
                "sInfo": "Affichage de l'élément _START_ à _END_ sur _TOTAL_ éléments",
                "sInfoEmpty": "Affichage de l'élément 0 à 0 sur 0 élément",
                "sInfoFiltered": "(filtré de _MAX_ éléments au total)",
                "sInfoPostFix": "",
                "sLoadingRecords": "Chargement en cours...",
                "sZeroRecords": "Aucun élément à afficher",
                "sEmptyTable": "Aucune donnée disponible dans le tableau",
                "oPaginate": {
                    "sFirst": "Premier",
                    "sPrevious": "Précédent",
                    "sNext": "Suivant",
                    "sLast": "Dernier"
                },
                "oAria": {
                    "sSortAscending": ": activer pour trier la colonne par ordre croissant",
                    "sSortDescending": ": activer pour trier la colonne par ordre décroissant"
                },
                "select": {
                    "rows": {
                        "_": "%d lignes sélectionnées",
                        "0": "Aucune ligne sélectionnée",
                        "1": "1 ligne sélectionnée"
                    }
                }
            };

            $('#plan_list').DataTable({
                scrollX: true,
                language: dataTableLangFr

            });

        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // --- Données complètes ---
    const riskData = {
        "SEC1":  { "impact": "FAIBLE",    "frequence": "EXTREMEMENT_RARE" },
        "SEC2":  { "impact": "FAIBLE",    "frequence": "PEU_FREQUENT"      },
        "SEC3":  { "impact": "FAIBLE",    "frequence": "EXTREMEMENT_RARE" },
        "SEC4":  { "impact": "MODERE",    "frequence": "RARE"              },
        "SEC5":  { "impact": "MOYEN",     "frequence": "FREQUENT"          },
        "SEC6":  { "impact": "FORT",      "frequence": "TRES_FREQUENT"     },
        "SEC7":  { "impact": "MAJEUR",    "frequence": "RARE"              },
        "SEC8":  { "impact": "CRITIQUE",  "frequence": "PEU_FREQUENT"      },
        "SEC9":  { "impact": "MODERE",    "frequence": "PEU_FREQUENT"      },
        "SEC10": { "impact": "FAIBLE",    "frequence": "PERMANENT"         },
        "SEC11": { "impact": "MOYEN",     "frequence": "TRES_FREQUENT"     },
        "SEC12": { "impact": "CRITIQUE",  "frequence": "RARE"              }
    };

    // --- Labels ---
    // Impact du plus faible (index 0) au plus critique (index 5)
    const impactLabels = [
        ['Faible',   '(≤ 10 kXOF)'],
        ['Modéré',   '(10‑100 kXOF)'],
        ['Moyen',    '(100 k‑1 MXOF)'],
        ['Fort',     '(1‑10 MXOF)'],
        ['Majeur',   '(10‑100 MXOF)'],
        ['Critique', '(> 100 MXOF)']
    ];
    const frequencyLabels = [
        ['Extrêmement rare', '(≤ 0.2/an)'],
        ['Rare',             '(≤ 1/an)'],
        ['Peu fréquent',     '(1‑15/an)'],
        ['Fréquent',         '(16‑50/an)'],
        ['Très fréquent',    '(51‑350/an)'],
        ['Permanent',        '(> 350/an)']
    ];

    // --- Couleurs (0 = Critique … 5 = Faible) ---
    const matrixColors = [
        ['#FF9800','#FF5722','#D32F2F','#D32F2F','#D32F2F','#D32F2F'], // Critique
        ['#FFEB3B','#FF9800','#FF5722','#D32F2F','#D32F2F','#D32F2F'], // Majeur
        ['#4CAF50','#FFEB3B','#FF9800','#FF5722','#D32F2D','#D32F2F'], // Fort
        ['#4CAF50','#4CAF50','#FFEB3B','#FF9800','#FF5722','#D32F2F'], // Moyen
        ['#4CAF50','#4CAF50','#4CAF50','#FFEB3B','#FF9800','#FF9800'], // Modéré
        ['#4CAF50','#4CAF50','#4CAF50','#4CAF50','#FFEB3B','#FFEB3B']  // Faible
    ];

    // --- Mappings valeur → index (impact faible=0 … critique=5) ---
    const impactToIndex = {
        FAIBLE:   0,
        MODERE:   1,
        MOYEN:    2,
        FORT:     3,
        MAJEUR:   4,
        CRITIQUE: 5
    };
    const frequencyToIndex = {
        EXTREMEMENT_RARE: 0,
        RARE:             1,
        PEU_FREQUENT:     2,
        FREQUENT:         3,
        TRES_FREQUENT:    4,
        PERMANENT:        5
    };

    // --- Grouper pour éviter collision ---
    const buckets = {};
    for (const [sec, {impact, frequence}] of Object.entries(riskData)) {
        const i = impactToIndex[impact];
        const j = frequencyToIndex[frequence];
        const key = `${i}-${j}`;
        (buckets[key] ||= []).push(sec);
    }

    // --- Construire dataPoints ---
    const dataPoints = [];
    for (const [sec, {impact, frequence}] of Object.entries(riskData)) {
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
        dataPoints.push({ x: j + dx, y: i + dy, sec, impact, frequence });
    }

    // --- Plugin pour dessiner la grille + labels SEC ---
    const riskMatrixPlugin = {
        id: 'riskMatrix',
        beforeDraw(chart) {
            const {ctx, scales: {x: xs, y: ys}} = chart;
            const cellW = xs.width  / frequencyLabels.length;
            const cellH = ys.height / impactLabels.length;
            const bottom = ys.bottom;

            for (let row = 0; row < impactLabels.length; row++) {
                // Faible (row=0) en bas → y = bottom - (row+1)*cellH
                const y = bottom - (row + 1) * cellH;
                for (let col = 0; col < frequencyLabels.length; col++) {
                    const x = xs.left + col * cellW;
                    // Pour récupérer la couleur correcte : Critique=matrixColors[0]
                    // mais ici row=0 veut dire Faible, donc on fait mapping inverse :
                    const colorRow = matrixColors.length - 1 - row;
                    ctx.fillStyle = matrixColors[colorRow][col];
                    ctx.fillRect(x, y, cellW, cellH);
                    ctx.strokeStyle = 'rgba(255,255,255,0.7)';
                    ctx.strokeRect(x, y, cellW, cellH);
                }
            }
        },
        afterDraw(chart) {
            const {ctx, scales: {x: xs, y: ys}} = chart;
            ctx.save();
            ctx.font = 'bold 10px Arial';
            ctx.fillStyle = '#222';
            ctx.textAlign = 'center';
            ctx.textBaseline = 'bottom';
            for (const pt of chart.data.datasets[0].data) {
                const px = xs.getPixelForValue(pt.x);
                const py = ys.getPixelForValue(pt.y);
                ctx.fillText(pt.sec, px, py - 6);
            }
            ctx.restore();
        }
    };
    Chart.register(riskMatrixPlugin);

    // --- Configuration Chart.js ---
    const config = {
        type: 'scatter',
        data: { datasets: [{ data: dataPoints, pointRadius: 6, pointBackgroundColor: 'white', pointBorderColor: '#222', pointBorderWidth: 2, hoverRadius: 8 }] },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: {
                    type: 'category',
                    labels: frequencyLabels,
                    offset: true,
                    title: { display: true, text: 'Fréquence', font: { size: 14, weight: 'bold' }, padding: { top: 10 } },
                    grid: { display: false },
                    ticks: { font: { size: 11 }, callback: (_v,i) => frequencyLabels[i] }
                },
                y: {
                    type: 'category',
                    labels: impactLabels,
                    offset: true,
                    grid: { display: false },
                    title: { display: true, text: 'Impact net', font: { size: 14, weight: 'bold' }, padding: { bottom: 10 } },
                    ticks: { font: { size: 11 }, callback: (_v,i) => impactLabels[i] }
                }
            },
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: { label: ctx => [`Risque: ${ctx.raw.sec}`, `Impact: ${ctx.raw.impact}`, `Fréquence: ${ctx.raw.frequence.replace(/_/g,' ')}`] },
                    padding: 10, displayColors: false
                },
                title: { display: true, text: "Matrice d’Évaluation des Risques", font: { size: 20, weight: 'bold' }, padding: { bottom: 20 } }
            }
        },
        plugins: [riskMatrixPlugin]
    };

    // --- Initialisation ---
    const ctx = document.getElementById('riskMatrixChart').getContext('2d');
    new Chart(ctx, config);
</script>







@endsection
