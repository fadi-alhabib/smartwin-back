@extends('layouts.app')

@section('header', 'النقاط')


@section('content')



<!--**********************************
    Content body start
***********************************-->
<div class="content-body">
    <!-- row -->
    <div class="container-fluid">
        
        
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title"> تبديل النقاط</h4>
                </div>
                <div class="card-body">

                    <form action="{{ route('updatePoint') }}" method="post">
                        
                        @csrf


                        <div class="mb-3">
                            <label for="" class="from-label"> ألف نقطة مقابل</label>
                            <input type="text" class="form-control input-default" name="equal" placeholder="">
                        </div>

                        <div class="mb-3">
                            <input type="submit" class="form-control btn btn-primary" value="تعديل">
                        </div>

                    </form>

                </div>
            </div>
        </div>


    </div>
</div>
<!--**********************************
    Content body end
***********************************-->


@endsection