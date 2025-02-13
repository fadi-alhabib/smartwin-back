@extends('layouts.app')


@section('header', $product->name)


@section('content')
<style>
    .grid-image {
        object-fit: fill;
        width: 100%;
        height: 90%;
    }
</style>
<div class="content-body">
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="row gy-5 container-fluid">
                            <h1 class="col-12">{{$product->price}} {{$product->name}}</h1>
                            <h3 class="col-12">{{$product->store->name}} <i class="fa fa-store"></i></h3>
                            <p class="col-12">{{$product->description}}</p>
                            <div class="row g-2 justify-content-end align-items-end container-fluid"> @auth
                                <form class="col-6" action="{{route('product.destroy',$product->id)}}" method="post" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger"><i class="fa fa-trash"></i></button>
                                </form>
                                <form action="{{route('product.edit',$product->id)}}" class="col-6" method="get" style="display: inline;">
                                    <button type="submit" class="btn btn-success"><i class="fa fa-edit"></i></button>
                                </form>
                                @endauth
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="row">
                            @foreach($images as $image)
                            <div class="col-md-6">
                                <img src="{{$image}}" alt="صورة" class="img-fluid img-thumbnail grid-image">
                            </div>
                            @endforeach
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection