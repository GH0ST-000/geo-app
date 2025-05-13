@extends('layouts.app')
@section('content')
    @if(session()->has('message'))
        <div class="alert alert-success" id="flash_message">
            {{ session()->get('message') }}
        </div>
    @endif
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
                                    <th class="sorting" tabindex="0" aria-controls="dataTable" rowspan="1" colspan="1"  style="width: 205.57px;">მომხმარებელი</th>
                                    <th class="sorting" tabindex="0" aria-controls="dataTable" rowspan="1" colspan="1"  style="width: 205.57px;">სტანდარტის ტიპი</th>
                                    <th class="sorting" tabindex="0" aria-controls="dataTable" rowspan="1" colspan="1"  style="width: 205.57px;">სტატუსი</th>
                                    <th class="sorting" tabindex="0" aria-controls="dataTable" rowspan="1" colspan="1"  style="width: 175.57px;">შექმნის დრო</th>
                                    <th class="sorting" tabindex="0" aria-controls="dataTable" rowspan="1" colspan="1"  style="width: 175.57px;">ქმედება</th>

                                </tr>
                                </thead>
                                <tbody>


                                @foreach($applications as $application)
                                    <tr >
                                        <td class="">{{$application['id']}}</td>
                                        <td class="">{{$application['fullName']}}</td>
                                        <td class="align-middle">{{$application['standard']}}</td>
                                        @if($application['is_verified'])
                                            <td class="align-middle"><span class="badge bg-success">ვერიფიცირებული</span></td>
                                        @elseif($application['reject_reason'] && $application['is_verified'] == false)
                                            <td class="align-middle"><span class="badge bg-danger">უარყოფილი</span></td>

                                        @else
                                            <td class="align-middle"><span class="badge bg-warning">არა ვერიფიცირებული</span></td>

                                        @endif

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
