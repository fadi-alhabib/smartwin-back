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
                    <h4 class="card-title">صلاحيات الادمن</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table header-border table-responsive-sm ">
                            <thead>
                                <tr>
                                    @foreach($privileges as $privilege)
                                    <th> {{ $privilege->name }} </th>
                                    @endforeach
                                    <th>اسم الأدمن</th>
                                    <th>#</th>
                                </tr>
                            </thead>
                            <tbody>

                            @foreach($users as $user)
                                
                                <tr>
                                    
                                @foreach($privileges as $privilege)

                                @php

                                $my_privilege = explode(',', $user->privilege);

                                @endphp
                                
                                <form action="{{ route('checkPrivilege') }}" method="POST" id="{{ $user->id }}">
                                    
                                    @csrf
                                    <td>
                                        <input type="hidden" name="user_id" value="{{ $user->id }}">
                                        <input type="hidden" name="privilege_id" value="{{ $privilege->id }}">
                                        <input type="text" name="status" value="true" hidden>
                                        <input type="checkbox" class="form-check-input" name="check" data-userId="{{ $user->id }}" data-privilegeId="{{ $privilege->id }}" @if(in_array($privilege->id, $my_privilege)) {{ 'checked' }} @endif>
                                        <!-- <input type="submit" value="submit" hidden> -->
                                    </td>
                                
                                </form>

                                @endforeach

                                    <td> {{ $user->first_name . ' ' . $user->last_name}} </td>
                                    <td> {{ $loop->index + 1 }} </td>
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