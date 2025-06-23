@extends('global_manager.components.app')
@section('title')
    Profil
@endsection
@section('page_css')
@endsection
@section('main_content')
    @include('global_manager.page.user_profil.layouts.page_header')
    @include('global_manager.page.user_profil.layouts.body')
@endsection
@section('page_js')
    <script>
    document.addEventListener("DOMContentLoaded", function () {
        const savedTab = localStorage.getItem("activeTab");

        if (savedTab) {
            // 1. Retirer les classes actives sur les tab-panes
            document.querySelectorAll(".tab-pane").forEach(pane => {
                pane.classList.remove("show", "active");
            });

            // 2. Activer le bon contenu
            const activePane = document.getElementById(savedTab);
            if (activePane) {
                activePane.classList.add("show", "active");
            }

            // 3. Retirer les classes actives des onglets
            document.querySelectorAll(".nav-link").forEach(link => {
                link.classList.remove("active");
            });

            // 4. Activer le bon onglet
            const activeTabLink = document.querySelector(`.nav-link[href="#${savedTab}"]`);
            if (activeTabLink) {
                activeTabLink.classList.add("active");
            }
        }

        // Gestion du clic sur les onglets
        const tabs = document.querySelectorAll(".nav-link");

        tabs.forEach(tab => {
            tab.addEventListener("click", function (event) {
                // On laisse le comportement par défaut du Bootstrap tab (pas besoin de `preventDefault`)
                const targetId = this.getAttribute("href").substring(1);
                localStorage.setItem("activeTab", targetId);
            });
        });
    });
</script>
@endsection
