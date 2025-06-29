@extends('service_manager.components.app')
@section('title')
    Dashboard - {{ $service->name }}
@endsection
@section('main_content')
    @include('service_manager.pages.referentiel.layouts.fiche_risque.page_header')
    @include('service_manager.pages.referentiel.layouts.fiche_risque.form')
@endsection
@section('page_js')
    <script>
        document.getElementById('risque_a_piloter').addEventListener('change', function() {
            const createForm = document.getElementById('kri-create-choice');
            const choiceForm = document.getElementById('validationSelectKRI');
          
            if (this.value === '1') {
                createForm.style.display = 'block';
                 choiceForm.setAttribute('required', 'required');
                
            
            } else {
                
                createForm.style.display = 'none';
                 choiceForm.removeAttribute('required');
            }
        });
    </script>
    <script>
        document.getElementById('action_maitrise_risque').addEventListener('change', function() {
            const createForm = document.getElementById('pa-create-choice');
            const choiceForm = document.getElementById('validationSelectPA');
            console.log(this.value)
            if (this.value === '1') {
               createForm.style.display = 'block';
               choiceForm.setAttribute('required', 'required');
            
            } else {
                 createForm.style.display = 'none';
                choiceForm.removeAttribute('required');
                
            }
        });
    </script>
    <script>
        document.getElementById('validationSelectKRI').addEventListener('change', function() {
            const createForm = document.getElementById('kri-create-form');
            const selectField = document.getElementById('kri-select-field');

            if (this.value === 'create') {
                createForm.style.display = 'block';
                selectField.style.display = 'none';
            } else if (this.value === 'select') {
                createForm.style.display = 'none';
                selectField.style.display = 'block';
            } else {
                createForm.style.display = 'none';
                selectField.style.display = 'none';
            }
        });
    </script>
    <script>
        document.getElementById('validationSelectPA').addEventListener('change', function() {
            const createForm = document.getElementById('pa-create-form');
            const selectField = document.getElementById('pa-select-field');

            if (this.value === 'create_pa') {
                createForm.style.display = 'block';
                selectField.style.display = 'none';
            } else if (this.value === 'select_pa') {
                createForm.style.display = 'none';
                selectField.style.display = 'block';
            } else {
                createForm.style.display = 'none';
                selectField.style.display = 'none';
            }
        });
    </script>
@endsection
