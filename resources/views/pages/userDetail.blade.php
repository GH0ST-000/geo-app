@extends('layouts.app')
@section('content')
    <div class="ms-2 mb-4">
        <span style="font-size: 18px">მომხმარებლის დეტალური ინფორმაცია</span>
    </div>
    @if($user->profile_thumbnail_url)
        <div class="d-flex justify-content-center mb-3">
            <img alt="profile image" src="{{$user->profile_thumbnail_url}}" style="border-radius: 50%; width: 100px; height: 100px;">
        </div>
    @else
        <div class="d-flex justify-content-center mb-3">
            <img alt="profile image" src="https://geogapp.site/assets/user-D__q57DX.png" style="border-radius: 50%; width: 100px; height: 100px;">
        </div>
    @endif

    <div class="card mb-4 mt-4">
        <div class="card-body">
            <div class="d-flex justify-content-center mb-3">
            </div>
            <div class="container">
                <div class="row">
                    <div class="col-sm-6 col-12 mb-3">
                        <div class="form-group">
                            <label for="exampleInputEmail1">სახელი</label>
                            <input type="text" class="form-control" value="{{$user->first_name}}" readonly>

                        </div>
                    </div>
                    <div class="col-sm-6 col-12 mb-3">
                        <div class="form-group">
                            <label for="exampleInputEmail1">გვარი</label>
                            <input type="text" class="form-control" value="{{$user->last_name}}" readonly>
                        </div>
                    </div>
                    <div class="col-sm-6 col-12 mb-3">
                        <div class="form-group">
                            <label for="exampleInputEmail1">ელ.ფოსტა</label>
                            <input type="text" class="form-control" value="{{$user->email}}" readonly>
                        </div>
                    </div>
                    <div class="col-sm-6 col-12 mb-3">
                        <div class="form-group">
                            <label for="exampleInputEmail1">მომხმარებლის ტიპი</label>
                            <input type="text" class="form-control" value="ფერმერი" readonly>
                        </div>
                    </div>
                    <div class="col-sm-6 col-12 mb-3">
                        <div class="form-group">
                            <label for="exampleInputEmail1">მობილურის ნომერი</label>
                            @if($user->phone != 'null')

                                <input type="text" class="form-control" value="{{$user->phone}}" readonly>
                            @else
                                <input type="text" class="form-control" value="" readonly>
                            @endif
                        </div>
                    </div>

                    <div class="col-sm-6 col-12 mb-3">
                        <div class="form-group">
                            <label for="exampleInputEmail1">შექმნის თარიღი</label>
                            <input type="text" class="form-control" value="{{$user->created_at}}" readonly>
                        </div>
                    </div>
                    <div class="col-sm-6 col-12 mb-3">
                        <div class="form-group">
                            <label for="exampleInputEmail1">მომხმარებლის შესახებ</label>
                            <input type="text" class="form-control" value="{{$user->description}}" readonly>
                        </div>
                    </div>

                    <div class="col-sm-6 col-12 mb-3">
                        <div class="form-group">
                            <label for="exampleInputEmail1">პირადი ნომერი</label>
                            <input type="text" class="form-control" value="{{$user->personal_number}}" readonly>
                        </div>
                    </div>


                    <input type="hidden" id="users" value="{{$user->id}}">
                </div>

            </div>
        </div>
        <div class="container mb-3">
            <div class="row">
                <div class="col-sm-6 col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-baseline">
                                <h6 class="card-title mb-0">პროდუქტების რაოდენობა</h6>
                                <div class="dropdown mb-2">
                                    <button class="btn btn-link p-0" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-horizontal icon-lg text-muted pb-3px"><circle cx="12" cy="12" r="1"></circle><circle cx="19" cy="12" r="1"></circle><circle cx="5" cy="12" r="1"></circle></svg>
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                        <a class="dropdown-item d-flex align-items-center" href="{{url('admin/products')}}"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye icon-sm me-2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg> <span class="">ნახვა</span></a>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6 col-md-12 col-xl-5">
                                    <h3 class="mb-2">{{$product}}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 mt-sm-0 mt-2 col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-baseline ">
                                <h6 class="card-title mb-0">განაცხადის რაოდენობა</h6>
                                <div class="dropdown mb-2">
                                    <button class="btn btn-link p-0" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-horizontal icon-lg text-muted pb-3px"><circle cx="12" cy="12" r="1"></circle><circle cx="19" cy="12" r="1"></circle><circle cx="5" cy="12" r="1"></circle></svg>
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                        <a class="dropdown-item d-flex align-items-center" href="{{url('admin/applications')}}"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye icon-sm me-2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg> <span class="">ნახვა</span></a>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6 col-md-12 col-xl-5">
                                    <h3 class="mb-2">{{$application}}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
