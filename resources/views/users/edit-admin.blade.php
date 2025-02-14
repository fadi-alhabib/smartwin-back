@extends('layouts.app')

@section('header', 'تعديل أدمن')

@section('content')
    <!--**********************************
        Content body start
    ***********************************-->
    <div class="content-body">
        <!-- row -->
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.update', $admin->id) }}" method="POST">
                        @method('PATCH')
                        @csrf

                        <div class="mb-3">
                            <label for="full_name" class="form-label">الاسم</label>
                            <input name="full_name" id="full_name" type="text" class="form-control input-default"
                                placeholder="" value="{{ $admin->full_name }}" required>
                        </div>
                        @error('full_name')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror

                        <div class="mb-3">
                            <label for="username" class="form-label">اسم المستخدم</label>
                            <input name="username" id="username" type="text" class="form-control input-default"
                                placeholder="" value="{{ $admin->username }}" readonly>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">كلمة المرور</label>
                            <input name="password" id="password" type="password" class="form-control input-default"
                                placeholder="">
                        </div>
                        @error('password')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror

                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">تأكيد كلمة المرور</label>
                            <input name="password_confirmation" id="password_confirmation" type="password"
                                class="form-control input-default" placeholder="">
                        </div>

                        <div class="mb-3">
                            <input type="submit" class="form-control btn btn-primary" value="تعديل">
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
    <!--**********************************
        Content body end
    ***********************************-->
@endsection
