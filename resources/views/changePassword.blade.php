@extends('layouts.app')

@section('header', 'تغيير كلمة السر')




@section('content')



<!--**********************************
    Content body start
***********************************-->
<div class="content-body">
    <!-- row -->
    <div class="container-fluid">
        
        <div class="col-xl-12 col-lg-12">
            <div class="authincation-content">
                <div class="row no-gutters">
                    <div class="col-xl-12">
                        <div class="auth-form">
                            
                        
                            <form action="{{route('updatePassword')}}" method="post">
                                
                            @csrf

                                <div class="mb-3">
                                    <label><strong>كلمة السر القديمة</strong></label>
                                    <input type="password" class="form-control" name="old_password" value="">
                                </div>

                                @error('old_password')
                                    <div class="alert alert-danger"> {{ $message }} </div>
                                @enderror

                                <div class="mb-3">
                                    <label><strong>كلمة السر الجديدة</strong></label>
                                    <input type="password" class="form-control" name="new_password" value="">
                                </div>
                                
                                @error('new_password')
                                    <div class="alert alert-danger"> {{ $message }} </div>
                                @enderror

                                <div class="mb-3">
                                    <label><strong>تأكيد كلمة السر</strong></label>
                                    <input type="password" class="form-control" name="password_confirmation" id="password_confirmation" value="">
                                </div>
                                
                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary btn-block">حفظ</button>
                                </div>
                            </form>
                        </div>
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