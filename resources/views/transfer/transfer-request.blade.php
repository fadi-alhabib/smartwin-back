@extends('layouts.app')

@section('header', 'طلبات التحويل')

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
                                    <th><strong> </strong></th>
                                    <th><strong>النقاط</strong></th>
                                    <th><strong>تاريخ التحويل</strong></th>
                                    <th><strong>رقم الهاتف</strong></th>
                                    <th><strong>البلد</strong></th>
                                    <th><strong>الاسم</strong></th>
                                    <th><strong>البريد الإلكتروني</strong></th>
                                    <th><strong>#</strong></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($transfers as $transfer)
                                    <tr>
                                        <td>
                                            <form action="{{ route('transferDone', ['id' => $transfer->id]) }}" method="post">
                                                @csrf
                                                <input type="submit" class="form-control btn btn-primary" value="تم">
                                            </form>
                                        </td>
                                        <td>{{ $transfer->points }}</td>
                                        <td>{{ $transfer->created_at }}</td>
                                        <td>{{ $transfer->phone }}</td>
                                        <td>{{ $transfer->country }}</td>
                                        <td>{{ $transfer->full_name }}</td>
                                        <td>{{ $transfer->email }}</td>
                                        <td><strong>{{ $loop->index + 1 }}</strong></td>
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
