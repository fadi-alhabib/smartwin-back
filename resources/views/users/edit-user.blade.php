@extends('layouts.app')

@section('header', 'تعديل المستخدم')

@section('content')
    <!--**********************************
        Content body start
    ***********************************-->
    <div class="content-body">
        <!-- row -->
        <div class="container-fluid">

            <div class="card">
                <div class="card-body">

                    <form action="{{ route('user.update', $user->id) }}" method="POST">
                        @method('PATCH')
                        @csrf

                        <div class="mb-3">
                            <label for="full_name" class="form-label">الاسم</label>
                            <input name="full_name" id="full_name" type="text" class="form-control input-default"
                                placeholder="" value="{{ $user->full_name }}" required>
                        </div>
                        @error('full_name')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror

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
                            <label for="phone" class="form-label">رقم الهاتف</label>
                            <input name="phone" id="phone" type="text" class="form-control input-default"
                                placeholder="" value="{{ $user->phone }}" required>
                        </div>
                        @error('phone')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror

                        <div class="mb-3">
                            <label for="country" class="form-label">البلد</label>
                            <input name="country" id="country" type="text" class="form-control input-default"
                                placeholder="" value="{{ $user->country }}" required>
                        </div>
                        @error('country')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror

                        <div class="mb-3">
                            <label for="points" class="form-label">النقاط</label>
                            <input name="points" id="points" type="number" class="form-control input-default"
                                placeholder="" value="{{ $user->points }}" readonly>
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
