@extends('layouts.app')
@section('content')

    <div class="mb-3">
        <span>პროდუქტი</span>
    </div>
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
                                        <a href="{{url('admin/product/detail/'.$product->id)}}" class="badge bg-info cursor-pointer" >ნახვა</a></td>
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
