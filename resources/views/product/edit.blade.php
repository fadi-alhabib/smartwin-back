@extends('layouts.app')


@section('header', 'تعديل المتجر')


@section('content')

<div class="content-body">
    <!-- row -->
    <div class="container-fluid">


        <div class="card">
            <div class="card-body">

                <form action="{{ route('product.update', $product->id) }}" method="POST">
                    @method('PATCH')
                    @csrf

                    <div class="mb-3">
                        <label for="name" class="from-label">اسم المنتج</label>
                        <input name="name" id="name" type="text" class="form-control input-default" placeholder="" value="{{ $product->name }}" required>
                    </div>

                    @error('name')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror

                    <div class="mb-3">
                        <label for="description" class="from-label">وصف المنتج</label>
                        <input name="description" id="description" type="text" class="form-control input-default " placeholder="" value="{{ $product->description }}" required>
                    </div>

                    @error('description')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror

                    <div class="mb-3">
                        <label for="price" class="from-label">سعر المنتج</label>
                        <input name="price" id="price" type="number" class="form-control input-default " placeholder="" value="{{ $product->price }}">
                    </div>

                    @error('price')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror

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