@extends('service_manager.components.app')
@section('title')
    Plan d'actions
@endsection
@section('page_css')

@endsection
@section('main_content')
    @include('service_manager.pages.plan_action.layouts.add_action_modal')
    @include('service_manager.pages.plan_action.layouts.header')
    @include('service_manager.pages.plan_action.layouts.body')
@endsection
@section('page_js')

@endsection
