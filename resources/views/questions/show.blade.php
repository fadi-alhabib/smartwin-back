@extends('layouts.app')


@section('header', 'معلومات السؤال')


@section('content')


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
                                    <th><strong>السؤال</strong></th>
                                    
                                    <!-- <th><strong>تاريخ الإضافة</strong></th>
                                    <th><strong>السؤال</strong></th>
                                    <th><strong>#</strong></th> -->
                                </tr>
                            </thead>
                            <tbody>
                                

                                <tr>
                                    <!-- <td>
                                        <div class="d-flex">
                                            <form action="{{ route('question.destroy', $question[0]->id) }}" method="POST">
                                                @method('DELETE')
                                                @csrf

                                                <button type="submit" style="all:unset">
                                                    <a class="btn btn-danger shadow btn-xs sharp me-2"><i class="fa fa-trash"></i></a>
                                                </button>
                                            </form>
                                            <a href="{{ route('question.edit', $question[0]->id) }}" class="btn btn-primary shadow btn-xs sharp "><i class="fas fa-pencil-alt"></i></a>
                                        </div>
                                    </td> -->
                                    <!-- <td>01 August 2020</td> -->
                                    <td> {{ $question[0]->title }} </td>
                                    <td><strong>السؤال</strong></td>
                                    <!-- <td><strong>1</strong></td> -->
                                    
                                </tr>

                                @foreach($correct_answers as $answer)

                                <tr class="alert alert-success">
                                    <td> {{ $answer->title }} </td>
                                    <td><strong>اجابة صحيحة</strong></td>
                                    
                                </tr>

                                @endforeach


                                @foreach($wrong_answers as $answer)

                                <tr class="alert alert-danger">
                                    <td> {{ $answer->title }} </td>
                                    <td><strong>اجابة خاطئة</strong></td>
                                    
                                </tr>

                                @endforeach


                                <tr>
                                    <td> {{ $question[0]->created_at }} </td>
                                    <td><strong> تاريخ الإضافة</strong></td>
                                    
                                </tr>

                                
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>




@endsection