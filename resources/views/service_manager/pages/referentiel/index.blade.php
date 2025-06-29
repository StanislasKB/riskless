@extends('service_manager.components.app')
@section('title')
    Dashboard - {{ $service->name }}
@endsection
@section('page_css')

@endsection
@section('main_content')
    @include('service_manager.pages.referentiel.layouts.page_header')
    @include('service_manager.pages.referentiel.layouts.risk_list')
@endsection
@section('page_js')
   
   <script>
		$(document).ready(function () {
			//Default data table
			$('#example').DataTable();
			
		});
	</script>
@endsection
