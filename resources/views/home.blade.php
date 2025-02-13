@extends('layouts.app')

@section('header', 'لوحة التحكم')

@section('content')
    <!--**********************************
        Content body start
    ***********************************-->
    <div class="content-body">
        <!-- row -->
        <div class="container-fluid">
            <div class="row invoice-card-row">
                <!-- Your dashboard cards remain unchanged -->
                <div class="col-xl-3 col-xxl-3 col-sm-6">
                    <div class="card bg-warning invoice-card">
                        <div class="card-body d-flex justify-content-end">
                            <div>
                                <h2 class="text-white text-end invoice-num">2478</h2>
                                <span class="text-white text-end fs-18">Total Invoices</span>
                            </div>
                            <div class="icon me-3">
                                <!-- SVG icon here -->
                            </div>
                        </div>
                    </div>
                </div>
                <!-- ... other dashboard cards ... -->
            </div>
            
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">المنضمين حديثا</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table header-border table-responsive-sm ">
                                <thead>
                                    <tr>
                                        <th>تاريخ الانضمام</th>
                                        <th>الحالة</th>
                                        <th>البلد</th>
                                        <th>النقاط</th>
                                        <th>اسم المستخدم</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($users as $user)
                                        <tr>
                                            <td>
                                                <span class="text-muted">
                                                    {{ $user->created_at->format('Y-m-d') }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($user->is_active)
                                                    <span class="badge badge-success">Active</span>
                                                @else
                                                    <span class="badge badge-danger">Inactive</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="javascript:void();">
                                                    {{ $user->country ?? 'N/A' }}
                                                </a>
                                            </td>
                                            <td>{{ $user->points }}</td>
                                            <td>{{ $user->full_name }}</td>
                                            <td>{{ $loop->iteration }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
    
        </div>
    </div>
    <!--**********************************
        Content body end
    ***********************************-->
@endsection
