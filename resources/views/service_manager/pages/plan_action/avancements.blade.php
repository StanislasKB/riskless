@extends('service_manager.components.app')
@section('title')
    Plan d'actions
@endsection
@section('page_css')
    <link href="/admin/assets/plugins/select2/css/select2.min.css" rel="stylesheet" />
    <link href="/admin/assets/plugins/select2/css/select2-bootstrap4.css" rel="stylesheet" />
@endsection
@section('main_content')
    @include('service_manager.pages.plan_action.layouts.avancement.add_avancement_modal')
    @include('service_manager.pages.plan_action.layouts.avancement.body')
@endsection
@section('page_js')
    <script>
        // $('.single-select').select2({
        //     theme: 'bootstrap4',
        //     width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
        //     placeholder: $(this).data('placeholder'),
        //     allowClear: Boolean($(this).data('allow-clear')),

        // });

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

            $('#avancement_list').DataTable({
                scrollX: true,
                language: dataTableLangFr

            });

        });
    </script>
    @endsection
