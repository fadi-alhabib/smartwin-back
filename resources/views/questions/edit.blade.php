@extends('layouts.app')


@section('header', 'تعديل سؤال')



@section('content')


<!--**********************************
    Content body start
***********************************-->
<div class="content-body">
    <!-- row -->
    <div class="container-fluid">
        
        <div class="col-xl-12 col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="basic-form">
                        <form action="{{ route('question.update', $question[0]->id) }}" method="post">

                            @method('PATCH')

                            @csrf
                            <div class="mb-3">
                                <label for="question" style="font-size: 20px;">نص السؤال</label>
                                <textarea class="form-control" name="title" rows="4" id="question" required>{{ $question[0]->title }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label for="question" style="font-size: 20px;">الاجابة الصحيحة</label><br>
                                <textarea class="form-control text-end" name="correct_answer" rows="4" id="question" dir="rtl" style="display: inline; border-color: var(--bs-success); width: 70%;" required> {{ $correct_answer[0]->title }} </textarea>
                            </div>
                            <nav>
                                <ul class="pagination pagination-gutter">
                                    
                                    <!-- <li class="page-item"><a class="page-link" href="javascript:void(0)">-</a></li> -->
                                    <!-- <li class="page-item active"><a class="page-link" href="javascript:void(0)">-</a></li>
                                    <li class="page-item active"><a class="page-link" href="javascript:void(0)">+</a></li> -->
                                    
                                </ul>
                            </nav>
                            
                            @for($i = 0; $i < 3; $i++)
                            <div class="mb-3">
                                <label for="question" style="font-size: 20px;">الاجابة الخاطئة</label><br>
                                <textarea class="form-control text-end" name="wrong_answer_{{ $i + 1}}" rows="4" id="question" dir="rtl" style="display: inline; border-color: var(--bs-danger); width: 70%;" required>{{ $wrong_answer[$i]->title }}</textarea>
                            </div>
                            @endfor
                            <nav>
                                <ul class="pagination pagination-gutter">
                                    
                                    <!-- <li class="page-item"><a class="page-link" href="javascript:void(0)">-</a></li> -->
                                    <!-- <li class="page-item active"><a class="page-link" href="javascript:void(0)">-</a></li>
                                    <li class="page-item active"><a class="page-link" href="javascript:void(0)">+</a></li> -->
                                    
                                </ul>
                            </nav>

                            
                            <button type="submit" class="btn btn-secondary">
                                تعديل
                            </button>
                        </form>
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