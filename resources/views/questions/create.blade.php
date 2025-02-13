@extends('layouts.app')


@section('header', 'إضافة سؤال')


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
                        <form action="{{ route('question.store') }}" method="post">

                            @csrf

                            <div class="mb-3">
                                <label for="question" style="font-size: 20px;">نص السؤال</label>
                                <textarea name="title" class="form-control" rows="4" id="question" required></textarea>
                            </div>
                            <div class="mb-3 correct_section">
                                <label for="question" style="font-size: 20px;">الاجابة الصحيحة</label><br>
                                <input type="hidden" id="correct_answer">
                                <textarea class="form-control text-end answer" name="correct_answer" rows="4" id="correct_1" dir="rtl" style="display: inline; border-color: var(--bs-success); width: 70%;" required></textarea>
                            </div>
                            <nav>
                                <ul class="pagination pagination-gutter">
                                    
                                    <!-- <li class="page-item"><a class="page-link" href="javascript:void(0)">-</a></li> -->
                                    <!-- <li class="page-item active"><a class="page-link tool_btn disabled" id="mines_correct" href="javascript:void(0)">-</a></li>
                                    <li class="page-item active"><a class="page-link tool_btn" id="plus_correct" href="javascript:void(0)">+</a></li> -->
                                    
                                </ul>
                            </nav>
                            <div class="mb-3 wrong_section">
                                <label for="question" style="font-size: 20px;">الاجابة الخاطئة</label><br>
                                <input type="hidden" id="wrong_answer">
                                <textarea class="form-control text-end answer" name="wrong_answer_1" rows="4" id="wrong_1" dir="rtl" style="display: inline; border-color: var(--bs-danger); width: 70%;" required></textarea>
                            </div>
                            <div class="mb-3 wrong_section">
                                <label for="question" style="font-size: 20px;">الاجابة الخاطئة</label><br>
                                <input type="hidden" id="wrong_answer">
                                <textarea class="form-control text-end answer" name="wrong_answer_2" rows="4" id="wrong_2" dir="rtl" style="display: inline; border-color: var(--bs-danger); width: 70%;" required></textarea>
                            </div>
                            <div class="mb-3 wrong_section">
                                <label for="question" style="font-size: 20px;">الاجابة الخاطئة</label><br>
                                <input type="hidden" id="wrong_answer">
                                <textarea class="form-control text-end answer" name="wrong_answer_3" rows="4" id="wrong_3" dir="rtl" style="display: inline; border-color: var(--bs-danger); width: 70%;" required></textarea>
                            </div>
                            <nav>
                                <ul class="pagination pagination-gutter">
                                    
                                    <!-- <li class="page-item"><a class="page-link" href="javascript:void(0)">-</a></li> -->
                                    <!-- <li class="page-item active"><a class="page-link tool_btn disabled" id="mines_wrong" href="javascript:void(0)" aria-disabled="true">-</a></li>
                                    <li class="page-item active"><a class="page-link tool_btn" id="plus_wrong" href="javascript:void(0)">+</a></li> -->
                                    
                                </ul>
                            </nav>

                            
                            <input type="submit" class="btn btn-secondary" value="إضافة">
                                <!-- إضافة
                            </button> -->
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