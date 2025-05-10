@extends('layouts.app')
@section('content')
{{--    @dd($otherFiles)--}}
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
                                        <div style="width: 100%; padding-top: 100%; position: relative;">
                                            <img style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover;" src="{{$image->file_url}}">
                                        </div>
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


    <div class="mb-3 mt-3">
        <span>სხვა ფაილები</span>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="container">
                <div class="row">
                    @if(sizeof($otherFiles) > 0 )
                        @foreach($otherFiles as $otherFile)
                            <div class="col-sm-3 col-12">
                                <div class="card">
                                    <a class="card-body" href="{{$otherFile->file_url}}" target="_blank">
                                       <div class="d-flex justify-content-between align-items-center"></div>
                                        <span style="" class=" text-success">{{$otherFile->file_name}}</span>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                </div>
            </div>

            @else
                <div class="d-flex text-center justify-content-center">
                    <span class="text-danger">მოცემულ განაცხადზე ფაილები არ მოიძებნა</span>
                </div>
            @endif
        </div>
    </div>
<div class="mt-3 mb-3 card">
    <div class="card-body">
        <div class="d-flex justify-content-end">
            <button class="btn btn-success">დადასურება</button>
        </div>
    </div>
</div>
    <script>
        lightbox.option({
            'resizeDuration': 200,
            'wrapAround': true
        })
    </script>

@endsection
