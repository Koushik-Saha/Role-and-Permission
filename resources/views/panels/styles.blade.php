        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat:300,400,500,600">
        <link rel="stylesheet" href="{{ asset(mix('vendors/css/vendors.min.css')) }}">
        <link rel="stylesheet" href="{{ asset(mix('vendors/css/ui/prism.min.css')) }}">
        {{-- Vendor Styles --}}
        @yield('vendor-style')
        {{-- Theme Styles --}}
        <link rel="stylesheet" href="{{ asset(mix('css/bootstrap.css')) }}">
        <link rel="stylesheet" href="{{ asset(mix('css/bootstrap-extended.css')) }}">
        <link rel="stylesheet" href="{{ asset(mix('css/colors.css')) }}">
        <link rel="stylesheet" href="{{ asset(mix('css/components.css')) }}">
        <link rel="stylesheet" href="{{ asset(mix('css/themes/dark-layout.css')) }}">
        <link rel="stylesheet" href="{{ asset(mix('css/themes/semi-dark-layout.css')) }}">

        <!-- Customized css files -->

        <!-- Select vendor css files -->
        <link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/select/select2.min.css')) }}">

        <!-- DatePicker vendor css files -->
        <link rel="stylesheet" href="{{ asset(mix('vendors/css/pickers/pickadate/pickadate.css')) }}">

        <!-- Number Input vendor css files -->
        <link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/spinner/jquery.bootstrap-touchspin.css')) }}">

        {{-- Validation Page Css files --}}
        <link rel="stylesheet" href="{{ asset(mix('css/plugins/forms/validation/form-validation.css')) }}">

        <!-- Toastr vendor css files -->
        <link rel="stylesheet" href="{{ asset(mix('vendors/css/extensions/toastr.css')) }}">

        {{-- Datatable vendor css files --}}
        <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/datatables.min.css')) }}">

        {{--    switchery Toggle--}}
        <link rel="stylesheet" href="{{ asset('css/plugins/switchery/switchery.min.css') }}">


        {{--    Toastr Notifications--}}
        <link rel="stylesheet" href="{{ asset('css/plugins/toastr/toastr.min.css') }}">

{{-- {!! Helper::applClasses() !!} --}}
@php
use App\Helpers\Helper;$configData = Helper::applClasses();
@endphp

{{-- Layout Styles works when don't use customizer --}}

@if($configData['theme'] == 'dark-layout')
        <link rel="stylesheet" href="{{ asset(mix('css/themes/dark-layout.css')) }}">
@endif
@if($configData['theme'] == 'semi-dark-layout')
        <link rel="stylesheet" href="{{ asset(mix('css/themes/semi-dark-layout.css')) }}">
@endif
{{-- Page Styles --}}
@if($configData['mainLayoutType'] === 'horizontal')
        <link rel="stylesheet" href="{{ asset(mix('css/core/menu/menu-types/horizontal-menu.css')) }}">
@endif
        <!-- Customized css files -->

        <link rel="stylesheet" href="{{ asset(mix('css/core/menu/menu-types/vertical-menu.css')) }}">
        <link rel="stylesheet" href="{{ asset(mix('css/core/colors/palette-gradient.css')) }}">

        <!-- Toastr Page css files -->
        <link rel="stylesheet" href="{{ asset(mix('css/plugins/extensions/toastr.css')) }}">

        {{-- Page Styles --}}
        @yield('page-style')
{{-- Laravel Style --}}
        <link rel="stylesheet" href="{{ asset(mix('css/custom-laravel.css')) }}">
{{-- Custom RTL Styles --}}
@if($configData['direction'] === 'rtl')
        <link rel="stylesheet" href="{{ asset(mix('css/custom-rtl.css')) }}">
@endif
