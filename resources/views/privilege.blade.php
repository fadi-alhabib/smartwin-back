@extends('layouts.app')

@section('header', 'الصلاحيات')

@section('content')

    <head>
        <meta name="csrf-token" content="{{ csrf_token() }}">
    </head>

    <!--**********************************
        Content body start
    ***********************************-->
    <div class="content-body">
        <!-- row -->
        <div class="container-fluid">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">صلاحيات الأدمن</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table header-border table-responsive-sm">
                                <thead>
                                    <tr>
                                        @foreach ($privileges as $privilege)
                                            <th>{{ $privilege->name }}</th>
                                        @endforeach
                                        <th>اسم الأدمن</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($admins as $admin)
                                        <tr>
                                            @php
                                                $my_privilege = explode(',', $admin->privilege);
                                            @endphp

                                            @foreach ($privileges as $privilege)
                                                <form action="{{ route('checkPrivilege') }}" method="POST"
                                                    id="{{ $admin->id }}_{{ $privilege->id }}">
                                                    @csrf
                                                    <td>
                                                        <input type="hidden" name="admin_id" value="{{ $admin->id }}">
                                                        <input type="hidden" name="privilege_id"
                                                            value="{{ $privilege->id }}">
                                                        <input type="hidden" name="status" value="true">
                                                        <input type="checkbox" class="form-check-input" name="check"
                                                            data-adminId="{{ $admin->id }}"
                                                            data-privilegeId="{{ $privilege->id }}"
                                                            @if (in_array($privilege->id, $my_privilege)) checked @endif>
                                                    </td>
                                                </form>
                                            @endforeach

                                            <td>{{ $admin->full_name }}</td>
                                            <td><strong>{{ $loop->iteration }}</strong></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div><!-- /.table-responsive -->
                    </div><!-- /.card-body -->
                </div><!-- /.card -->
            </div>
        </div>
    </div>
    <!--**********************************
        Content body end
    ***********************************-->

@endsection
