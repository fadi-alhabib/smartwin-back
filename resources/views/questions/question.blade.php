@extends('layouts.app')

@section('header', 'الأسئلة')

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
                                        <th><strong>الصورة</strong></th>
                                        <th><strong>الإجراءات</strong></th>
                                        <th><strong>تاريخ الإضافة</strong></th>
                                        <th><strong>الحالة</strong></th>
                                        <th><strong>السؤال</strong></th>
                                        <th><strong>#</strong></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($questions as $question)
                                        <tr>
                                            <!-- Image Column -->
                                            <td>
                                                @if ($question->image)
                                                    <img src="{{ asset($question->image) }}" alt="Question Image"
                                                        style="width:50px; height:50px;">
                                                @endif
                                            </td>

                                            <!-- Actions Column -->
                                            <td>
                                                <div class="d-flex">
                                                    <form action="{{ route('question.destroy', $question->id) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" style="all:unset">
                                                            <a class="btn btn-danger shadow btn-xs sharp me-2">
                                                                <i class="fa fa-trash"></i>
                                                            </a>
                                                        </button>
                                                    </form>
                                                    <a href="{{ route('question.edit', $question->id) }}"
                                                        class="btn btn-primary shadow btn-xs sharp">
                                                        <i class="fas fa-pencil-alt"></i>
                                                    </a>
                                                </div>
                                            </td>

                                            <!-- Date Column -->
                                            <td> {{ date('Y-m-d', strtotime($question->created_at)) }} </td>

                                            <!-- Status Column -->
                                            <td>
                                                @if ($question->status)
                                                    <form action="{{ route('questionStatus') }}" method="POST"
                                                        id="questionStatus_{{ $question->id }}">
                                                        @csrf
                                                        <input type="hidden" name="question_id"
                                                            value="{{ $question->id }}">
                                                        <input type="hidden" name="status" value="0">
                                                        <span class="badge badge-success"
                                                            data-question="{{ $question->id }}">active</span>
                                                    </form>
                                                @else
                                                    <form action="{{ route('questionStatus') }}" method="POST"
                                                        id="questionStatus_{{ $question->id }}">
                                                        @csrf
                                                        <input type="hidden" name="question_id"
                                                            value="{{ $question->id }}">
                                                        <input type="hidden" name="status" value="1">
                                                        <span class="badge badge-danger"
                                                            data-question="{{ $question->id }}">inactive</span>
                                                    </form>
                                                @endif
                                            </td>

                                            <!-- Question Title Column -->
                                            <td> {{ $question->title }} </td>

                                            <!-- Index Column -->
                                            <td><strong>{{ $loop->iteration }}</strong></td>
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
