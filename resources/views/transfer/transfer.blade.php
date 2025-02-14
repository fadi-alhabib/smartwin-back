@extends('layouts.app')

@section('header', 'عمليات تمت')

@section('content')
<!--**********************************
    Content body start
***********************************-->
<div class="content-body">
    <!-- row -->
    <div class="container-fluid">
        <div class="col-lg-12">
            <div class="card">
                <!-- <div class="card-header">
                    <h4 class="card-title"> التحويلات </h4>
                </div> -->
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-responsive-md">
                            <thead>
                                <tr>
                                    <th><strong> المبلغ</strong></th>
                                    <th><strong> النقاط</strong></th>
                                    <th><strong>تاريخ التحويل</strong></th>
                                    <th><strong> رقم الهاتف</strong></th>
                                    <th><strong>البلد</strong></th>
                                    <th><strong>اسم المستخدم</strong></th>
                                    <th><strong>الاسم</strong></th>
                                    <th><strong>#</strong></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($transfers as $transfer)
                                    <tr>
                                        <!-- Compute amount using points * conversion rate -->
                                        <td>{{ $transfer->points / 10 }}</td>
                                        <td>{{ $transfer->points }}</td>
                                        <td>{{ $transfer->created_at }}</td>
                                        <td>{{ $transfer->phone }}</td>
                                        <td>{{ $transfer->country }}</td>
                                        <!-- Since we don't have a dedicated username field, we use the email -->
                                        <td>{{ $transfer->email }}</td>
                                        <td>{{ $transfer->full_name }}</td>
                                        <td><strong>{{ $loop->iteration }}</strong></td>
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
