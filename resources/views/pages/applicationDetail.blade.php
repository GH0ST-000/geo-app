@extends('layouts.app')
@section('content')
{{--    @dd($imageFiles)--}}
    <div class="mb-3 mt-3">
        <span>განცხადების დეტალური გვერდი</span>
    </div>
<div class="card">
    <div class="card-body">
        <div class="container">
            <div class="row">
                <div class="col-sm-4 col-12">
                    <div class="form-group">
                        <label for="exampleInputEmail1">მომხმარებელი</label>
                        <input type="text" class="form-control" value="{{$user->first_name . ' '. $user->last_name}}" readonly>
                    </div>
                </div>
                <div class="col-sm-4 col-12">
                    <div class="form-group">
                        <label for="exampleInputEmail1">განცხადების მოდული</label>
                        <input type="text" class="form-control" value="{{$standard}}" readonly>
                    </div>
                </div>
                <div class="col-sm-4 col-12">
                    <div class="form-group">
                        <label for="exampleInputEmail1">შექმნის დრო</label>
                        <input type="text" class="form-control" value="{{$application->created_at}}" readonly>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

    <div class="mb-3 mt-3">
        <span>განცხადების სურათები</span>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="container">
                <div class="row">
                    @if(sizeof($imageFiles) > 0 )
                        @foreach($imageFiles as $image)
                            <div class="col-sm-3 col-12">
                                <div class="card">
                                    <a class="card-body" href="{{$image->file_url}}" data-lightbox="gallery" data-title="image">
                                        <img style="width: 90%; height: 90%;background-size: cover"  src="{{$image->file_url}}">
                                    </a>
                                </div>
                            </div>
                        @endforeach
                </div>
            </div>

            @else
                <div class="d-flex text-center justify-content-center">
                    <span class="text-danger">მოცემულ განაცხადზე სურათები არ მოიძებნა</span>
                </div>
            @endif
        </div>
    </div>
    <script>
        lightbox.option({
            'resizeDuration': 200,
            'wrapAround': true
        })
    </script>

@endsection
