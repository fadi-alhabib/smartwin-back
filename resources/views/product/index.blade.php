@extends('layouts.app')


@section('header', 'المنتجات')


@section('content')

<script>
    document.addEventListener("DOMContentLoaded", function() {
        var elements = document.getElementsByClassName('truncate-text');
        var charactersToShow = 15;
        for (var i = 0; i < elements.length; i++) {
            var descriptionElement = elements[i];
            var textContent = descriptionElement.textContent.trim();
            var truncatedText = textContent.slice(0, charactersToShow);

            if (textContent.length > charactersToShow) {
                truncatedText += '...';
            }
            descriptionElement.textContent = truncatedText;
        }
    });
</script>
<style>
    .checked {
        color: orange;
    }
</style>
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
                                    <th><strong>عدد التقييمات</strong></th>
                                    <th><strong>التقييم</strong></th>
                                    <th><strong>المتجر</strong></th>
                                    <th><strong>الوصف</strong></th>
                                    <th><strong>السعر</strong></th>
                                    <th><strong>الاسم</strong></th>
                                    <th><strong>الصورة</strong></th>
                                    <th><strong>#</strong></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($products as $product)
                                <tr>
                                    <td>
                                        @auth
                                        <form action="{{route('product.destroy',$product->id)}}" method="post" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger"><i class="fa fa-trash"></i></button>
                                        </form>
                                        <a class="btn btn-primary" href="{{route('product.edit',$product->id)}}"><i class="fa fa-edit"></i></a>
                                        <a class="btn btn-secondary" href="{{ route('product.show', $product->id) }}"><i class="fa fa-eye"></i></a>
                                        @endauth
                                    </td>
                                    <td><strong>{{ $product->ratings_count }}</strong></td>
                                    <td>
                                        @for($i=0;$i<(int)(5 - $product->rating);$i++) <span class="fa fa-star "></span>@endfor
                                            @for ($i= 0;$i<(int)($product->rating);$i++) <span class="fa fa-star checked"></span>@endfor
                                    </td>
                                    <td><strong>{{ $product->store->name }}</strong></td>
                                    <td class="truncate-text">
                                        <strong>{{ $product->description }}</strong>
                                    </td>
                                    <td><strong>{{ $product->price }}</strong></td>
                                    <td><strong>{{ $product->name }}</strong></td>
                                    <td><img src="{{$product->image}}" alt="صورة المتجر" width="100" height="80"></td>
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