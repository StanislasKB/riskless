@extends('service_manager.components.app')
@section('title')
      Dashboard - {{ $service->name }}
@endsection
@section('page_css')

@endsection
@section('main_content')
    @include('service_manager.pages.matrice.layouts.page_header')
    @include('service_manager.pages.matrice.layouts.body')
@endsection
@section('page_js')


 <script>
    // Couleurs des zones de risque (y: impact, x: fréquence)
    const zoneColors = [
      ['#00FF00', '#00FF00', '#ADFF2F', '#FFFF00', '#FFA500', '#FFD700'],
      ['#00FF00', '#00FF00', '#ADFF2F', '#FFFF00', '#FFA500', '#FFD700'],
      ['#00FF00', '#ADFF2F', '#FFFF00', '#FFA500', '#FF4500', '#FF0000'],
      ['#ADFF2F', '#FFFF00', '#FFA500', '#FF4500', '#FF0000', '#8B0000'],
      ['#FFFF00', '#FFA500', '#FF4500', '#FF0000', '#8B0000', '#8B0000'],
      ['#FFA500', '#FF4500', '#FF0000', '#8B0000', '#8B0000', '#8B0000']
    ];

    const backgroundRects = [];

    // Crée des annotations par case
    for (let y = 0; y < 6; y++) {
      for (let x = 0; x < 6; x++) {
        backgroundRects.push({
          x: x,
          x2: x + 1,
          y: y,
          y2: y + 1,
          fillColor: zoneColors[y][x],
          opacity: 0.5
        });
      }
    }

    const options = {
      chart: {
        type: 'scatter',
        height: 600,
        zoom: { enabled: false },
        toolbar: { show: false }
      },
      series: [{
        name: 'Risques',
        data: [
          { x: 0, y: 2, name: 'SEC4' },
          { x: 0, y: 3, name: 'SEC3' },
          { x: 0, y: 1, name: 'SEC6' },
          { x: 0, y: 1, name: 'SEC7' },
          { x: 0, y: 2, name: 'SEC5' },
          { x: 1, y: 1, name: 'SEC4' },
          { x: 2, y: 1, name: 'SEC2' },
          { x: 2, y: 1, name: 'SEC5' },
          { x: 1, y: 3, name: 'SEC8' }
        ]
      }],
      xaxis: {
        min: 0,
        max: 6,
        tickAmount: 6,
        labels: {
          formatter: function (val) {
            const freqs = [
              "Extrêmement rare",
              "Rare",
              "Peu fréquent",
              "Fréquent",
              "Très fréquent",
              "Permanent"
            ];
            return freqs[val] || "";
          },
          style: { fontSize: "11px", whiteSpace: "normal" }
        },
        title: {
          text: 'Fréquence',
          style: { fontWeight: 'bold' }
        }
      },
      yaxis: {
        min: 0,
        max: 6,
        tickAmount: 6,
        labels: {
          formatter: function (val) {
            const impacts = [
              "Faible",
              "Modéré",
              "Moyen",
              "Fort",
              "Majeur",
              "Critique"
            ];
            return impacts[val] || "";
          },
          style: { fontSize: "12px" }
        },
        title: {
          text: 'Impact net',
          style: { fontWeight: 'bold' }
        }
      },
      annotations: {
        points: [],
        xaxis: [],
        yaxis: [],
        // 👉 Ici on dessine les rectangles
        // ApexCharts ne propose pas `rectangles` directement, donc hack avec SVG custom
        // On les ajoute via l'API bas-niveau après le rendu
      },
      markers: {
        size: 8,
        hover: { sizeOffset: 4 }
      },
      dataLabels: {
        enabled: true,
        formatter: function (val, opt) {
          return opt.w.config.series[0].data[opt.dataPointIndex].name;
        },
        offsetY: -10
      }
    };

    const chart = new ApexCharts(document.querySelector("#chart"), options);
    chart.render().then(() => {
      // Hack SVG pour ajouter les rectangles
      const svg = document.querySelector("#chart svg");
      backgroundRects.forEach(rect => {
        const xScale = 100; // approx
        const yScale = 100; // approx

        const rectEl = document.createElementNS("http://www.w3.org/2000/svg", "rect");
        rectEl.setAttribute("x", 80 + rect.x * xScale);
        rectEl.setAttribute("y", 30 + (5 - rect.y) * yScale);
        rectEl.setAttribute("width", xScale);
        rectEl.setAttribute("height", yScale);
        rectEl.setAttribute("fill", rect.fillColor);
        rectEl.setAttribute("fill-opacity", rect.opacity);

        svg.insertBefore(rectEl, svg.firstChild); // insère en fond
      });
    });
  </script>

@endsection

