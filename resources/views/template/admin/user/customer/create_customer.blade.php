@extends('layouts.admin.admin_layout',['title'=>'Create Customer'])
@section('content')

    @push('styles')
        {{-- Custom Style --}}
    @endpush

    <!-- Content Header (Page header) -->
    <div class="content-header">
        <x-admin.title-and-breadcrumb title="Create Customer"
                                      breadcrumbs='{"Home":"admin.dashboard","User":"","Customer":"customer.index","Create Customer":""}'/>
        <!-- /.content-header -->

        <x-admin.ui.card-form title="Customer Details" form-route="customer.store" form-id="create_customer">
            <x-slot name="input">
                <div class="mb-3">
                    <x-admin.ui.bootstrap-switch name="is_active" id="is_active" label="Status" onText="Active"
                                                 offText="Inactive"/>
                </div>
                <x-admin.ui.input label="Customer Name" type="text" name="customer_name" id="customer_name" add-class=""
                                  placeholder="Customer Name" required/>
                <x-admin.ui.input label="Email"
                                  type="email"
                                  name="email"
                                  id="email"
                                  add-class=""
                                  placeholder="Email"
                                  required/>

                <x-admin.ui.input label="Password" type="password" name="password" id="password" add-class=""
                                  placeholder="Password" required/>
            </x-slot>
            <x-slot name="button">
                <x-admin.ui.button type="submit" btn-name="Submit" name="customer_submit" id="customer_submit"/>
            </x-slot>
        </x-admin.ui.card-form>
        <!-- /.content -->
    @push('scripts')
        {{-- Custom JS --}}
    @endpush

@endsection
