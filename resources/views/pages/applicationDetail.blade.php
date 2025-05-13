@extends('layouts.app')
@section('content')
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
                    @if($application->is_verified ==true)
                    <div class="col-sm-4 col-12 mt-3">
                        <div class="form-group">
                            <label for="exampleInputEmail1">განცხადების სტატუსი</label>
                            <input type="text" class="form-control bg-success text-white" value="დადასტურებული" readonly>
                        </div>
                    </div>
                    @elseif($application->is_verified ==false && $application->reject_reason == null)
                            <div class="col-sm-4 col-12 mt-3">
                        <div class="form-group">
                            <label for="exampleInputEmail1">განცხადების სტატუსი</label>
                            <input type="text" class="form-control bg-warning text-white" value="არა ვერიფიცირებული" readonly>
                        </div>
                            </div>
                    @else
                                    <div class="col-sm-4 col-12 mt-3">
                        <div class="form-group">
                            <label for="exampleInputEmail1">განცხადების სტატუსი</label>
                            <input type="text" class="form-control bg-danger text-white" value="უარყოფილი" readonly>
                        </div>
                                    </div>

                    <div class="col-sm-4 col-12 mt-3">
                        <div class="form-group">
                            <label for="exampleInputEmail1">უარყოფის მიზეზი</label>
                            <input type="text" class="form-control " value="{{$application->reject_reason}}" readonly>
                        </div>
                    </div>
                    @endif


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
                    <div class="me-2">
                        <button  data-bs-toggle="modal" data-bs-target="#disagreeModal" data-userid="{{$user->id}}"
                                 data-appid="{{$application->group_id}}"
                                 class="btn btn-danger text-white ms-2">უარყოფა</button>
                    </div>
                    <div>
                        <button  data-bs-toggle="modal" data-bs-target="#confirmationModal" data-userid="{{$user->id}}"
                                 data-appid="{{$application->group_id}}" class="btn btn-success" >დადასტურება</button>
                    </div>
                </div>
            </div>

    <div class="modal fade" id="disagreeModal" tabindex="-1" aria-labelledby="disagreeModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form method="post" action="{{route('applications-reject')}}">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="disagreeModalLabel">განცხადების უარყოფა</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        <div class="container">
                            <div class="row">
                                <div class="col-12">
                                    <div>
                                        <labe for="new_password">გთხოვთ შეიყვანოთ უარყოფის მიზეზი</labe>
                                        <textarea required rows="5"  class="form-control"  placeholder="მიზეზი" name="reason"></textarea>
                                    </div>
                                    <input type="hidden" id="appID" name="appID" value="">
                                    <input type="hidden" id="userID" name="userID" value="">
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">დახურვა</button>
                        <button id="confirmReset" type="submit" class="btn btn-success">განცხადების უარყოფა</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

            <div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <form action="{{route('applications-active')}}" method="post">
                        @csrf
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="confirmationModalLabel">განცხადების დადასტურება</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">

                                <div class="container">
                                    <input type="hidden" id="suappID" name="app_id" value="">
                                    <input type="hidden" id="suuserID" name="user_id" value="">
                                    <div class="row">
                                        <div class="d-flex justify-content-center">
                                            <span class="align-items-center">ნამდვილად გსურთ განცხადების დადასტურება ?</span>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">დახურვა</button>
                                <button id="confirmReset" type="submit" class="btn btn-success">დადასტურება</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <script>
        $(function () {
            $('#disagreeModal').on('show.bs.modal', function (event) {
                let button = $(event.relatedTarget);
                let userId = button.data('userid');
                let appID = button.data('appid');
                $('#userID').val(userId);
                $('#appID').val(appID);

            });
        });

        $(function () {
            $('#confirmationModal').on('show.bs.modal', function (event) {
                let button = $(event.relatedTarget);
                let userId = button.data('userid');
                let appID = button.data('appid');
                $('#suuserID').val(userId);
                $('#suappID').val(appID);

            });
        });
    </script>
    <script>
        lightbox.option({
            'resizeDuration': 200,
            'wrapAround': true
        })
    </script>




@endsection
