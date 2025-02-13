@extends('layouts.app')


@section('header', 'المتاجر')


@section('content')

<div class="content-body">
    <div class="container-fluid">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-responsive-md">
                            <thead>
                                <tr>
                                    <th><strong>العمليات</strong></th>
                                    <th><strong>الحالة</strong></th>
                                    <th><strong>عدد التقييمات</strong></th>
                                    <th><strong>التقييم</strong></th>
                                    <th><strong>البلد</strong></th>
                                    <th><strong>العنوان</strong></th>
                                    <th><strong>النقاط</strong></th>
                                    <th><strong>الرقم</strong></th>
                                    <th><strong>النوع</strong></th>
                                    <th><strong>الاسم</strong></th>
                                    <th><strong>الصورة</strong></th>
                                    <th><strong>#</strong></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($stores as $store)
                                <tr>
                                    <td>
                                        @auth
                                        <form action="{{route('store.destroy',$store->id)}}" method="post" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger"><i class="fa fa-trash"></i></button>
                                        </form>
                                        <a class="btn btn-primary" href="{{route('store.edit',$store->id)}}"><i class="fa fa-edit"></i></a>
                                        @endauth

                                    </td>
                                    <td><span class="badge {{ $store->is_active === 0 ? 'badge-danger' : 'badge-success'}}">{{ $store->is_active===0 ?"غير مفعل":"مفعل"}}</span></td>
                                    <td><strong>{{ $store->ratings_count }}</strong></td>
                                    <td>
                                        @for($i=0;$i<(int)(5 - $store->rating);$i++) <span class="fa fa-star "></span>@endfor
                                            @for ($i= 0;$i<(int)($store->rating);$i++) <span class="fa fa-star checked"></span>@endfor
                                    </td>
                                    <td><strong>{{ $store->country }}</strong></td>
                                    <td><strong>{{ $store->address }}</strong></td>
                                    <td><strong>{{ $store->points }}</strong></td>
                                    <td><strong>{{ $store->phone }}</strong></td>
                                    <td><strong>{{ $store->type }}</strong></td>
                                    <td><strong>{{ $store->name }}</strong></td>
                                    <td><img src="{{$store->image}}" alt="صورة المتجر" width="100" height="80"></td>
                                    <td><strong> {{ $loop->index + 1 }} </strong></td>

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
@endsection