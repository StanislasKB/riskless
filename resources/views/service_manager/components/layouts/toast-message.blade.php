<script>
    // Configuration globale de Toastr
    toastr.options = {
        "closeButton": true,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "timeOut": 5000
    };

    // Messages de succès
    @if(Session::has('success'))
        toastr.success("{{ Session::get('success') }}");
    @endif

    // Messages d'erreur (validation Laravel)
    @if($errors->any()))
        @foreach ($errors->all() as $error)
            toastr.error("{{ $error }}");
        @endforeach
    @endif

    // Messages d'erreur personnalisés
    @if(Session::has('error'))
        toastr.error("{{ Session::get('error') }}");
    @endif
</script>
