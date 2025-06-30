@extends('service_manager.components.app')
@section('title')
    Dashboard - {{ $service->name }}
@endsection
@section('page_css')
    <link href="/admin/assets/plugins/select2/css/select2.min.css" rel="stylesheet" />
    <link href="/admin/assets/plugins/select2/css/select2-bootstrap4.css" rel="stylesheet" />
@endsection
@section('main_content')
    @include('service_manager.pages.referentiel.layouts.edit.page_header')
    @include('service_manager.pages.referentiel.layouts.edit.form')
@endsection
@section('page_js')
    <script src="/admin/assets/plugins/select2/js/select2.min.js"></script>

    <script>
        function handleRisqueAPiloterChange() {
            const createForm = document.getElementById('kri-create-choice');
            const choiceForm = document.getElementById('validationSelectKRI');
            const value = document.getElementById('risque_a_piloter').value;

            if (value === '1') {
                createForm.style.display = 'block';
            } else {
                createForm.style.display = 'none';
            }
        }

        document.getElementById('risque_a_piloter').addEventListener('change', handleRisqueAPiloterChange);

        window.addEventListener('DOMContentLoaded', handleRisqueAPiloterChange);
    </script>

    <script>
        function handleActionMaitriseRisqueChange() {
            const createForm = document.getElementById('pa-create-choice');
            const choiceForm = document.getElementById('validationSelectPA');
            const value = document.getElementById('action_maitrise_risque').value;

            if (value === '1') {
                createForm.style.display = 'block';
            } else {
                createForm.style.display = 'none';
            }
        }

        document.getElementById('action_maitrise_risque').addEventListener('change', handleActionMaitriseRisqueChange);

        window.addEventListener('DOMContentLoaded', handleActionMaitriseRisqueChange);
    </script>

    <script>
       
        $('.multiple-select-kri').select2({
            theme: 'bootstrap4',
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: 'Choisissez le KRI',
            allowClear: Boolean($(this).data('allow-clear')),
        });
        $('.multiple-select-pa').select2({
            theme: 'bootstrap4',
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: 'Choisissez le plan d\'action',
            allowClear: Boolean($(this).data('allow-clear')),
        });
    </script>
@endsection
