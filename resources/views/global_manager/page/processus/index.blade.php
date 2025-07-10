@extends('global_manager.components.app')
@section('title')
    Processus
@endsection
@section('page_css')
@endsection
@section('main_content')
    @include('global_manager.page.processus.layouts.page_header')
    @include('global_manager.page.processus.layouts.list_processus')
@endsection
@section('page_js')
    <script>
        $(document).ready(function() {
            const dataTableLangFr = {
                "decimal": "",
                "emptyTable": "Aucune donnée disponible dans le tableau",
                "info": "Affichage de _START_ à _END_ sur _TOTAL_ entrées",
                "infoEmpty": "Affichage de 0 à 0 sur 0 entrée",
                "infoFiltered": "(filtré à partir de _MAX_ entrées au total)",
                "infoPostFix": "",
                "thousands": ",",
                "lengthMenu": "Afficher _MENU_ entrées",
                "loadingRecords": "Chargement...",
                "processing": "Traitement...",
                "search": "Rechercher :",
                "zeroRecords": "Aucun enregistrement correspondant trouvé",
                "paginate": {
                    "first": "Premier",
                    "last": "Dernier",
                    "next": "Suivant",
                    "previous": "Précédent"
                },
                "aria": {
                    "sortAscending": ": activer pour trier la colonne par ordre croissant",
                    "sortDescending": ": activer pour trier la colonne par ordre décroissant"
                }
            };

            $('#processus').DataTable({
                language: dataTableLangFr
            });
        });
    </script>
@endsection
