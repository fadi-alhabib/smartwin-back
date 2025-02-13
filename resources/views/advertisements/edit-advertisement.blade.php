@extends('layouts.app')


@section('header', 'تعديل إعلان')


@section('content')

<!--**********************************
    Content body start
***********************************-->
<div class="content-body">
    <!-- row -->
    <div class="container-fluid">
        
        
        <div class="card">
            <div class="card-body">


                <form action="{{ route('advertisement.update', $ad[0]->id) }}" method="POST" enctype="multipart/form-data">
                    @method('PATCH')
                    @csrf

                    <div class="mb-3">
                        <label for="title" class="from-label">العنوان</label>
                        <input type="text" name="title" id="title" class="form-control input-default " placeholder="" value="{{ $ad[0]->title }}">
                    </div>

                    @error('title')
                        <div class="alert alert-danger"> {{ $message }} </div>
                    @enderror

                    <div class="mb-3">
                        <label for="file" class="form-label">اختر ملف</label>
                        <div class="form-file">
                            <input type="file" name="file" id="file" class="form-file-input form-control">
                        </div>
                    </div>

                    @error('file')
                        <div class="alert alert-danger"> {{ $message }} </div>
                    @enderror

                    <div class="mb-3">
                        <label for="from_date" class="from-label">ابتداء من</label>
                        <input type="date" name="from_date" id="from_date" class="form-control input-default " placeholder="" value="{{ $ad[0]->from_date }}">
                    </div>

                    @error('from_date')
                        <div class="alert alert-danger"> {{ $message }} </div>
                    @enderror

                    <div class="mb-3">
                        <label for="to_date" class="from-label">انتهاء بـ</label>
                        <input type="date" name="to_date" id="to_date" class="form-control input-default " placeholder="" value="{{ $ad[0]->to_date }}">
                    </div>

                    @error('to_date')
                        <div class="alert alert-danger"> {{ $message }} </div>
                    @enderror

                    <div class="mb-3">
                        <label for="" class="from-label"> : اعلان خاص بـ </label><br>
                        <label for="home_ad" class="mx-3">

                            @php

                            $home = '';

                            $not_home = '';

                            if($ad[0]->home_ad)
                            {
                                $home = 'checked';
                            }
                            else
                            {
                                $not_home = 'checked';
                            }

                            @endphp

                            <span class="mx-1">
                                الصفحة الرئيسية
                            </span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-house-fill" viewBox="0 0 16 16">
                                <path d="M8.707 1.5a1 1 0 0 0-1.414 0L.646 8.146a.5.5 0 0 0 .708.708L8 2.207l6.646 6.647a.5.5 0 0 0 .708-.708L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293L8.707 1.5Z"/>
                                <path d="m8 3.293 6 6V13.5a1.5 1.5 0 0 1-1.5 1.5h-9A1.5 1.5 0 0 1 2 13.5V9.293l6-6Z"/>
                            </svg>
                        </label>
                        <input type="radio" name="home_ad" id="home_ad" class="form-check-input" value="1" {{ $home }}><br>
                        <label for="not_home_ad" class="mx-3">
                            <span class="mx-1 ">
                                الأسئلة
                            </span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-question-circle-fill" viewBox="0 0 16 16">
                                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM5.496 6.033h.825c.138 0 .248-.113.266-.25.09-.656.54-1.134 1.342-1.134.686 0 1.314.343 1.314 1.168 0 .635-.374.927-.965 1.371-.673.489-1.206 1.06-1.168 1.987l.003.217a.25.25 0 0 0 .25.246h.811a.25.25 0 0 0 .25-.25v-.105c0-.718.273-.927 1.01-1.486.609-.463 1.244-.977 1.244-2.056 0-1.511-1.276-2.241-2.673-2.241-1.267 0-2.655.59-2.75 2.286a.237.237 0 0 0 .241.247zm2.325 6.443c.61 0 1.029-.394 1.029-.927 0-.552-.42-.94-1.029-.94-.584 0-1.009.388-1.009.94 0 .533.425.927 1.01.927z"/>
                            </svg>
                        </label>
                        <input type="radio" name="home_ad" id="not_home_ad" class="form-check-input" value="0" {{ $not_home }}>
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
