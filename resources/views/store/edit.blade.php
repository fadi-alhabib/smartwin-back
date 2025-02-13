@extends('layouts.app')


@section('header', 'تعديل المتجر')


@section('content')

<div class="content-body">
    <!-- row -->
    <div class="container-fluid">


        <div class="card">
            <div class="card-body">

                <form action="{{ route('store.update', $store->id) }}" method="POST">
                    @method('PATCH')
                    @csrf

                    <div class="mb-3">
                        <label for="name" class="from-label">اسم المتجر</label>
                        <input name="name" id="name" type="text" class="form-control input-default " placeholder="" value="{{ $store->name }}" required>
                    </div>

                    @error('name')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror

                    <div class="mb-3">
                        <label for="type" class="from-label">نوع المتجر</label>
                        <input name="type" id="type" type="text" class="form-control input-default " placeholder="" value="{{ $store->type }}" required>
                    </div>

                    @error('type')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror

                    <div class="mb-3">
                        <label for="address" class="from-label">عنوان المتجر</label>
                        <input name="address" id="address" type="text" class="form-control input-default " placeholder="" value="{{ $store->address }}" readonly>
                    </div>

                    @error('address')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror

                    <div class="mb-3">
                        <label for="phone" class="from-label">رقم الهاتف</label>
                        <input name="phone" id="phone" type="number" class="form-control input-default " placeholder="" value="{{ $store->phone }}" required>
                    </div>

                    @error('phone')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror

                    <div class="mb-3">
                        <label for="country" class="from-label"> البلد</label>
                        <input name="country" id="country" type="text" class="form-control input-default " placeholder="" value="{{ $store->country }}" required>
                    </div>

                    @error('country')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror

                    <div class="mb-3">
                        <label for="points" class="from-label"> النقاط</label>
                        <input name="points" id="points" type="number" class="form-control input-default " value="{{ $store->points }}" min="0">
                    </div>

                    @error('points')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror

                    <div class="mb-3">
                        <label for="is_active" class="form-label">الحالة</label>
                        <input name="is_active" type="checkbox" @if($store->is_active == 1) checked @endif>
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