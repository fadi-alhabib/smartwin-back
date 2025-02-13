@extends('layouts.app')


@section('header', 'المستخدمين')

@section('content')


<head>
    <link rel="stylesheet" href="{{ asset('css/style_2.css') }}">
</head>

<!--**********************************
    Content body start
***********************************-->
<div class="content-body">
    <!-- row -->
    <div class="container-fluid">
        
        
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-responsive-md">
                            <thead>
                                <tr>
                                    <th><strong></strong></th>
                                    
                                    <th><strong>تاريخ الانضمام</strong></th>
                                    <th><strong>الحالة</strong></th>
                                    <th><strong>النقاط</strong></th>
                                    <th><strong> رقم الهاتف</strong></th>
                                    <th><strong>البلد</strong></th>
                                    <th><strong>الاسم</strong></th>
                                    <th><strong>#</strong></th>
                                </tr>
                            </thead>
                            <tbody>

                            @foreach($users as $user)

                                <tr>
                                    <td>
                                        <div class="d-flex">
                                        <form action="{{ route('user.destroy', $user->id) }}" method="POST">
                                                @method('DELETE')
                                                @csrf

                                                <button type="submit" style="all:unset">
                                                    <a class="btn btn-danger shadow btn-xs sharp me-2"><i class="fa fa-trash"></i></a>
                                                </button>
                                            </form>
                                            <a href="{{ route('user.edit', $user->id) }}" class="btn btn-primary shadow btn-xs sharp "><i class="fas fa-pencil-alt"></i></a>
                                        </div>
                                    </td>
                                    
                                    <!-- <td>example@example.com	</td> -->
                                    <td> {{ date('Y-m-d', strtotime($user->created_at)) }} </td>
                                    <td> 
                                        <form action="{{ route('userStatus') }}" method="POST" id="userStatus_{{ $user->id }}">
                                            @csrf 

                                            <input type="hidden" name="user_id" value="{{ $user->id }}">

                                            <input type="hidden" name="status" value="0">

                                            @if($user->is_active) 
                                            <span class="badge badge-success" data-user="{{ $user->id }}">active</span> 
                                        
                                        </form>
                                            @else 
                                        
                                        <form action="{{ route('userStatus') }}" method="POST" id="userStatus_{{ $user->id }}">

                                            @csrf 

                                            <input type="hidden" name="user_id" value="{{ $user->id }}">

                                            <input type="hidden" name="status" value="1">

                                        
                                            <span class="badge badge-danger" data-user="{{ $user->id }}">inactive</span> 

                                        </form>
                                            @endif 


                                    </td>
                                    <td> {{ $user->points }} </td>
                                    <td> {{ $user->phone }} </td>
                                    <td> {{ $user->country }} </td>
                                    <td> {{ $user->first_name . ' ' . $user->last_name }} </td>
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



<script>
    
</script>



@endsection