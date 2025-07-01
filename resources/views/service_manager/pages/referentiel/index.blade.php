@extends('service_manager.components.app')
@section('title')
    Dashboard - {{ $service->name }}
@endsection
@section('page_css')
<style>
    .dataTables_wrapper .dataTables_scrollBody {
    overflow: visible !important; /* ou at least overflow-y: visible */
}

table.dataTable tbody td {
    overflow: visible;
}

</style>
@endsection
@section('main_content')
    @include('service_manager.pages.referentiel.layouts.page_header')
    @include('service_manager.pages.referentiel.layouts.risk_list')
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
           
            $('#risk_list').DataTable({
                language: dataTableLangFr,
                  columnDefs: [
        { targets: 0, width: "80px" },   // Index
        { targets: 1, width: "150px" },  // Libellé
        { targets: 2, width: "250px" },  // Description
        { targets: 3, width: "150px" },  // Département
        { targets: 4, width: "120px" },  // Statut
        { targets: 5, width: "60px" }    // Actions
    ]

                
            });

        });
    </script>
@endsection
