@extends('global_manager.components.app')
@section('title')
    Services
@endsection
@section('page_css')
    <link href="/admin/assets/plugins/select2/css/select2.min.css" rel="stylesheet" />
    <link href="/admin/assets/plugins/select2/css/select2-bootstrap4.css" rel="stylesheet" />
@endsection
@section('main_content')
    @include('global_manager.page.service.layouts.page_header')
    @include('global_manager.page.service.layouts.body')
@endsection
@section('page_js')
    <script src="/admin/assets/plugins/select2/js/select2.min.js"></script>
    <script>
        $('.single-select').select2({
            theme: 'bootstrap4',
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            allowClear: Boolean($(this).data('allow-clear')),
        });
        $('.multiple-select-service').select2({
            theme: 'bootstrap4',
            dropdownParent: $('#addServiceUserModal'),
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: 'Choisissez le service',
            allowClear: Boolean($(this).data('allow-clear')),
        });
        $('.multiple-select-permission').select2({
            theme: 'bootstrap4',
            dropdownParent: $('#addServiceUserModal'),
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: 'Choisissez la permissions',
            allowClear: Boolean($(this).data('allow-clear')),
        });
    </script>
    @foreach ($users as $user)
        <script>
			let userId={{ $user->id }}
            $('.multiple-select-update-permission-'+userId).select2({
                theme: 'bootstrap4',
                dropdownParent: $("#updateUserPermissionModal-"+userId),
                width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
                placeholder: 'Choisissez la permissions',
                allowClear: Boolean($(this).data('allow-clear')),
            });
        </script>
    @endforeach
@endsection
