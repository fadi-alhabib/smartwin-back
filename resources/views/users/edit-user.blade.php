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

                <form action="{{ route('user.update', $user[0]->id) }}" method="POST">
                    @method('PATCH')
                    @csrf

                    <div class="mb-3">
                        <label for="first_name" class="from-label">الاسم الأول</label>
                        <input name="first_name" id="first_name" type="text" class="form-control input-default " placeholder="" value="{{ $user[0]->first_name }}" required>
                    </div>

                    @error('first_name')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                    
                    <div class="mb-3">
                        <label for="last_name" class="from-label">الاسم الأخير</label>
                        <input name="last_name" id="last_name" type="text" class="form-control input-default " placeholder="" value="{{ $user[0]->last_name }}" required>
                    </div>

                    @error('last_name')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror

                    <div class="mb-3">
                        <label for="username" class="from-label">اسم المستخدم</label>
                        <input name="username" id="username" type="text" class="form-control input-default " placeholder="" value="{{ $user[0]->username }}" readonly>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="from-label">كلمة المرور </label>
                        <input name="password" id="password" type="password" class="form-control input-default " placeholder="" >
                    </div>
                    
                    @error('password')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror

                    <div class="mb-3">
                        <label for="password_confirmation" class="from-label">تأكيد كلمة المرور </label>
                        <input name="password_confirmation" id="password_confirmation" type="password" class="form-control input-default " placeholder="" >
                    </div>
                    

                    <div class="mb-3">
                        <label for="phone" class="from-label">رقم الهاتف</label>
                        <input name="phone" id="phone" type="text" class="form-control input-default " placeholder="" value="{{ $user[0]->phone }}" required>
                    </div>

                    @error('phone')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                    

                    <div class="mb-3">
                        <label for="country" class="from-label"> البلد</label>
                        <input name="country" id="country" type="text" class="form-control input-default " placeholder="" value="{{ $user[0]->country }}" required>
                    </div>

                    @error('country')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                    

                    <div class="mb-3">
                        <label for="points" class="from-label"> النقاط</label>
                        <input name="points" id="points" type="number" class="form-control input-default " placeholder="" value="{{ $user[0]->points }}" readonly>
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