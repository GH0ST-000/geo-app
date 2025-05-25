@extends('layouts.app')
@section('content')

    <div class="mb-3">
        <span>პროდუქტი</span>
    </div>
    @if(session()->has('message'))
        <div class="alert alert-success" id="flash_message">
            {{ session()->get('message') }}
        </div>
    @endif
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <div id="dataTableExample_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                    <div class="row">
                        <div class="col-sm-12">
                            <table id="dataTable" class="table dataTable no-footer align-middle pb-4" aria-describedby="dataTableExample_info">
                                <thead>
                                <tr>
                                    <th class="" tabindex="0" aria-controls="dataTable" rowspan="1" colspan="1"  style="width: 145.57px;">#</th>
                                    <th class="" tabindex="0" aria-controls="dataTable" rowspan="1" colspan="1"  style="width: 205.57px;">მომხმარებელი</th>
                                    <th class="" tabindex="0" aria-controls="dataTable" rowspan="1" colspan="1"  style="width: 205.57px;">პროდუქტის სახელი</th>
                                    <th class="" tabindex="0" aria-controls="dataTable" rowspan="1" colspan="1"  style="width: 175.57px;">მისამართი</th>
                                    <th class="" tabindex="0" aria-controls="dataTable" rowspan="1" colspan="1"  style="width: 175.57px;">მოცულობა</th>
                                    <th class="" tabindex="0" aria-controls="dataTable" rowspan="1" colspan="1"  style="width: 175.57px;">ქმედება</th>

                                </tr>
                                </thead>
                                <tbody>


                                @foreach($products as $product)
                                    <tr >
                                        <td >{{$product->id}}</td>
                                        <td >{{
                                            \App\Models\User::where('id',$product->user_id)->pluck('first_name')[0]
                                        }}</td>
                                        <td class="align-middle">{{$product->product_name}}</td>

                                        <td class="align-middle">{{$product->address}}</td>
                                        <td class="align-middle" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="{{$product->product_description}}" >
                                            {{ Str::limit($product->product_description, 20, '...') }}
                                        </td>

                                        <td class="">
                                       <div class="d-flex justify-content-between">
                                           <div> <a href="{{url('admin/product/detail/'.$product->id)}}" class="badge bg-info cursor-pointer" >ნახვა</a></div>
                                           <div> <a href="{{url('admin/product/edit/'.$product->id)}}" class="badge ms-2 bg-warning cursor-pointer" >რედაქტირება</a></div>
                                           <div> <a data-bs-toggle="modal" data-bs-target="#confirmationModal"  class="badge ms-2 bg-success cursor-pointer" >განცხადების ფაილები</a></div>
                                       </div>
                                        </td>
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


    <div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form action="{{route('applications-active')}}" method="post">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="confirmationModalLabel">განცხადების ფაილები</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        <div class="container">
                            <input type="hidden" id="suappID" name="app_id" value="">
                            <input type="hidden" id="suuserID" name="user_id" value="">
                            <div class="row">

                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">დახურვა</button>
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">უარყოფა</button>
                        <button id="confirmReset" type="submit" class="btn btn-success">დადასტურება</button>
                    </div>
                </div>
            </form>
        </div>
    </div>


@endsection
