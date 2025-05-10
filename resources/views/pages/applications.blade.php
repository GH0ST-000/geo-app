@extends('layouts.app')
@section('content')
    <div class="mb-3 mt-3">
        <span>განცხადებები</span>
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
                                    <th class="sorting sorting_asc" tabindex="0" aria-controls="dataTable" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Name: activate to sort column descending" style="width: 145.57px;">#</th>
                                    <th class="sorting" tabindex="0" aria-controls="dataTable" rowspan="1" colspan="1" aria-label="Position: activate to sort column ascending" style="width: 205.57px;">მომხმარებელი</th>
                                    <th class="sorting" tabindex="0" aria-controls="dataTable" rowspan="1" colspan="1" aria-label="Position: activate to sort column ascending" style="width: 205.57px;">სტანდარტის ტიპი</th>
                                    <th class="sorting" tabindex="0" aria-controls="dataTable" rowspan="1" colspan="1" aria-label="Office: activate to sort column ascending" style="width: 175.57px;">შექმნის დრო</th>
                                    <th class="sorting" tabindex="0" aria-controls="dataTable" rowspan="1" colspan="1" aria-label="Office: activate to sort column ascending" style="width: 175.57px;">ქმედება</th>

                                </tr>
                                </thead>
                                <tbody>


                                @foreach($applications as $application)
                                    <tr class="odd">
                                        <td class="sorting_1">{{$application['id']}}</td>
                                        <td class="sorting_1">{{$application['fullName']}}</td>
                                        <td class="align-middle">{{$application['standard']}}</td>

                                        <td class="align-middle">{{$application['created_at']}}</td>

                                        <td class="">
                                    <a href="{{route('applications-detail', $application['id'])}}"
                                          class="badge bg-info cursor-pointer" >ნახვა</a>
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


@endsection
