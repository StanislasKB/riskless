@extends('service_manager.components.app')
@section('title')
@endsection
@section('main_content')
    @include('service_manager.pages.graphe_indicateur.layouts.page_header')
    @include('service_manager.pages.graphe_indicateur.layouts.body')
@endsection
@section('page_js')
    <script>
        const chartElement = document.querySelector("#chart-indicateur");
        const graphData = JSON.parse(chartElement.getAttribute('data-graphe'));

        const months = Object.keys(graphData.kri);

        let maxVariations = 0;
        months.forEach(month => {
            const variations = graphData.kri[month];
            if (variations.length > maxVariations) {
                maxVariations = variations.length;
            }
        });
        let kriSeries = Array.from({
            length: maxVariations
        }, (_, i) => ({
            name: `KRI variation ${i + 1}`,
            type: 'column',
            data: []
        }));
        months.forEach(month => {
            const variations = graphData.kri[month];
            for (let i = 0; i < maxVariations; i++) {
                kriSeries[i].data.push(
                    variations[i] !== undefined ?
                    variations[i] :
                    null 
                );
            }
        });
        const seuilSeries = {
            name: "Seuil d'alerte",
            type: 'line',
            data: new Array(months.length).fill(graphData.seuil)
        };

        function generateKriColors(count) {
            const base = [0, 123, 255];
            const colors = [];
            for (let i = 0; i < count; i++) {
                const factor = 1 + (i * 0.15);
                const r = Math.min(255, Math.round(base[0] * factor));
                const g = Math.min(255, Math.round(base[1] * factor));
                const b = Math.min(255, Math.round(base[2] * factor));
                colors.push(`rgb(${r}, ${g}, ${b})`);
            }
            return colors;
        }

        const colors = generateKriColors(kriSeries.length).concat('#dc3545');

        var options = {
            series: [...kriSeries, seuilSeries],
            chart: {
                height: 400,
                type: 'line',
                stacked: false,
                foreColor: '#9ba7b2',
                toolbar: {
        show: false 
    }
            },
            stroke: {
                width: kriSeries.map(() => 0).concat([2]) 
            },
            colors: colors,
            plotOptions: {
                bar: {
                    columnWidth: '20%',
                    distributed: false,
                    grouped: true,
                }
            },
            dataLabels: {
                enabled: false
            },
            xaxis: {
                categories: months
            },
            yaxis: {
                min: 0
            },
            legend: {
                position: 'top'
            },
            tooltip: {
                shared: false,
                intersect: false,
            }
        };

        var chart = new ApexCharts(chartElement, options);
        chart.render();
    </script>
@endsection
