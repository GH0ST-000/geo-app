@extends('layouts.app')
@section('content')
   <div class="mt-3 mb-3">
       <span>პროდუქტის დეტალური გვერდი</span>
   </div>
    <div class="card">
        <div class="card-body">
            <div class="container">
                <div class="row">
                    <div class="col-sm-6 col-12 mb-3">
                        <div class="form-group">
                            <label for="exampleInputEmail1 mb-2">მომხმარებელი</label>
                            <input type="text" class="form-control" value=" {{$user}}" readonly>
                        </div>
                    </div>
                    <div class="col-sm-6 col-12 mb-3">
                        <div class="form-group">
                            <label for="exampleInputEmail1 mb-2">პროდუქტის დასახელება</label>
                            <input type="text" class="form-control" value="{{$product->product_name}}" readonly>
                        </div>
                    </div>
                    <div class="col-sm-6 col-12 mb-3">
                        <div class="form-group">
                            <label for="exampleInputEmail1 mb-2 ">პროდუქტის მოცულობა</label>
                            <input type="text" class="form-control" value="{{$product->packing_capacity}}" readonly>
                        </div>
                    </div>
                    <div class="col-sm-6 col-12 mb-3">
                        <div class="form-group">
                            <label for="exampleInputEmail1 mb-2">პროდუქტის მისამართი</label>
                            <input type="text" class="form-control" value="{{$product->address}}" readonly>
                        </div>
                    </div>
                    <div class="col-12 mb-3">
                        <div class="form-group">
                            <label for="exampleInputEmail1 mb-2">პროდუქტის აღწერა</label>
                            <textarea rows="10" class="form-control" readonly>{{$product->product_description}}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="mt-3 mb-3">
        <span class="badge bg-success">პროდუქტის სურათები</span>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="container">
                <div class="row">
                @if(sizeof($images) > 0 && $images[0]->product_images )
                    @foreach($images[0]->product_images as $image)
                        <div class="col-sm-6 col-12">
                            <div class="card">
                                <div class="card-body">
                                    <img style="width: 90%; height: 90%;background-size: cover"  src="{{$image['thumb']}}">
                                </div>
                            </div>
                        </div>
            @endforeach
                </div>
            </div>

            @else
                <div class="d-flex text-center justify-content-center">
                    <span>მოცემულ პროდუქტზე სურათები არ მოიძებნა</span>
                </div>
            @endif
        </div>
    </div>
@endsection
