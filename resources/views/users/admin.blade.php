@extends('layouts.app')


@section('header', 'الأدمن')

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
                    <h4 class="card-title"> الأسئلة </h4>
                </div> -->
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-responsive-md">
                            <thead>
                                <tr>
                                    <th><strong></strong></th>
                                    
                                    <th><strong>تاريخ الانضمام</strong></th>
                                    <th><strong> رقم الهاتف</strong></th>
                                    <th><strong>اسم المستخدم</strong></th>
                                    <th><strong>الاسم</strong></th>
                                    <th><strong>#</strong></th>
                                </tr>
                            </thead>
                            <tbody>

                            @foreach($admins as $admin)

                                <tr>
                                    <td>
                                        <div class="d-flex">
                                        <form action="{{ route('admin.destroy', $admin->id) }}" method="POST">
                                                @method('DELETE')
                                                @csrf

                                                <button type="submit" style="all:unset">
                                                    <a class="btn btn-danger shadow btn-xs sharp me-2"><i class="fa fa-trash"></i></a>
                                                </button>
                                            </form>
                                            <a href="{{ route('admin.edit', $admin->id) }}" class="btn btn-primary shadow btn-xs sharp "><i class="fas fa-pencil-alt"></i></a>
                                        </div>
                                    </td>
                                    
                                    <!-- <td>example@example.com	</td> -->
                                    <td> {{ date('Y-m-d', strtotime($admin->created_at)) }} </td>
                                    <td> {{ $admin->phone }} </td>
                                    <td> {{ $admin->username }} </td>
                                    <td> {{ $admin->first_name . ' ' . $admin->last_name}} </td>
                                    <td><strong> {{ $loop->index + 1}} </strong></td>
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