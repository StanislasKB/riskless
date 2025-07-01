@extends('service_manager.components.app')
@section('title')
      Dashboard - {{ $service->name }}
@endsection
@section('page_css')

@endsection
@section('main_content')
    @include('service_manager.pages.fiche_risque.layouts.page_header')
    @include('service_manager.pages.fiche_risque.layouts.body')
@endsection
@section('page_js')

@endsection
