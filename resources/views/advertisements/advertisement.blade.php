@extends('layouts.app')


@section('header', 'الإعلانات')


@section('content')

<head>
<link href="{{ asset('css/style_2.css') }}" rel="stylesheet">
</head>

<!--**********************************
    Content body start
***********************************-->
<div class="content-body">
    <!-- row -->
    <div class="container-fluid">
        
        <div class="row">

        @foreach($ads as $ad)

            
            <div class="col-xl-6">
                <div class="card">
                    @if($ad->is_img)

                    <img class="card-img-top img-fluid" src="{{ asset($ad->path) }}" alt="Card image cap">
                    
                    @else

                    <video controls>
                        <source src="{{ asset($ad->path) }}">
                        <p>
                            Your browser doesn't support HTML5 video. Here is
                            a <a href="myVideo.mp4">link to the video</a> instead.
                        </p>
                    </video>

                    @endif

                    <div class="card-header">
                        <h5 class="card-title"> {{ $ad->title}} </h5>
                    </div>
                    <div class="card-body">
                        
                        <div class="d-flex justify-content-end mb-4">
                            <div class="to-date">
                                @if($ad->home_ad) 
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-house-fill" viewBox="0 0 16 16">
                                    <path d="M8.707 1.5a1 1 0 0 0-1.414 0L.646 8.146a.5.5 0 0 0 .708.708L8 2.207l6.646 6.647a.5.5 0 0 0 .708-.708L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293L8.707 1.5Z"/>
                                    <path d="m8 3.293 6 6V13.5a1.5 1.5 0 0 1-1.5 1.5h-9A1.5 1.5 0 0 1 2 13.5V9.293l6-6Z"/>
                                </svg>
                                @else
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-question-circle-fill" viewBox="0 0 16 16">
                                    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM5.496 6.033h.825c.138 0 .248-.113.266-.25.09-.656.54-1.134 1.342-1.134.686 0 1.314.343 1.314 1.168 0 .635-.374.927-.965 1.371-.673.489-1.206 1.06-1.168 1.987l.003.217a.25.25 0 0 0 .25.246h.811a.25.25 0 0 0 .25-.25v-.105c0-.718.273-.927 1.01-1.486.609-.463 1.244-.977 1.244-2.056 0-1.511-1.276-2.241-2.673-2.241-1.267 0-2.655.59-2.75 2.286a.237.237 0 0 0 .241.247zm2.325 6.443c.61 0 1.029-.394 1.029-.927 0-.552-.42-.94-1.029-.94-.584 0-1.009.388-1.009.94 0 .533.425.927 1.01.927z"/>
                                </svg>
                                @endif

                                <span class="mx-2">
                                    {{ 'اعلان خاص ' }} {{  $ad->home_ad ? 'بالصفحة الرئيسية' : 'بصفحة الأسئلة'  }} 
                                </span>
                                    
                            
                            </div>
                        </div>
                        
                        <!-- ///////////////////////// -->
                        <form action="{{ route('advertisementStatus') }}" method="POST" id="advertisementStatus_{{ $ad->id }}">
                        @csrf 

                            <input type="hidden" name="id" value="{{ $ad->id }}">

                            <!-- <input type="hidden" name="is_active" value="0"> -->

                        @if($ad->is_active) 
                            <!-- <span class="badge badge-success" data-user="{{ $ad->id }}">active</span>  -->

                            <input type="submit" class="badge badge-success" style="display:block" value="active">
                        
                        </form>
                        @else 
                        
                        <form action="{{ route('advertisementStatus') }}" method="POST" id="advertisementStatus{{ $ad->id }}">

                        @csrf 

                            <input type="hidden" name="id" value="{{ $ad->id }}">

                            <!-- <input type="hidden" name="is_active" value="1"> -->

                        
                            <!-- <span class="badge badge-danger" data-user="{{ $ad->id }}">inactive</span>  -->

                            
                            <input type="submit" class="badge badge-danger" style="display:block" value="inactive">
                        </form>
                        @endif 

                        <!-- //////////////////////// -->

                        <div class="d-flex justify-content-around">
                            <div class="to-date"> {{ date('Y-m-d', strtotime($ad->to_date))  }} </div>
                            <div class="from-date"> {{ date('Y-m-d', strtotime($ad->from_date))  }} </div>
                        </div>
                        
                        <br>

                        <div class="d-flex">
                        <form action="{{ route('advertisement.destroy', $ad->id) }}" method="POST">
                            @method('DELETE')
                            @csrf

                            <button type="submit" style="all:unset">
                                <a class="btn btn-danger shadow btn-xs sharp me-2"><i class="fa fa-trash"></i></a>
                            </button>
                        </form>
                        <a href="{{ route('advertisement.edit', $ad->id) }}" class="btn btn-primary shadow btn-xs sharp "><i class="fas fa-pencil-alt"></i></a>
                        </div>
                    </div>
                </div>
            </div>

        @endforeach

            
        </div>
    </div>
</div>
<!--**********************************
    Content body end
***********************************-->


@endsection