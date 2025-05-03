@extends('layouts.app')
@section('content')
    @if(session()->has('message'))
        <div class="alert alert-success" id="flash_message">
            {{ session()->get('message') }}
        </div>
    @endif
    <div class="ms-2 mb-4">
        <span style="font-size: 18px">მომხმარებლები</span>
    </div>
    <div class="card ">
        <div class="card-body me-3">
            <div class="table-responsive">
                <div id="dataTableExample_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                    <div class="row dt-row">
                        <div class="col-sm-12">
                            <table id="dataTable" class="table dataTable no-footer" aria-describedby="dataTableExample_info">
                                <thead>
                                <tr>
                                    <th class="sorting sorting_asc" tabindex="0" aria-controls="dataTableExample" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Name: activate to sort column descending" style="width: 205.953px;">სახელი</th>
                                    <th class="sorting" tabindex="0" aria-controls="dataTableExample" rowspan="1" colspan="1" aria-label="Position: activate to sort column ascending" style="width: 122.906px;">გვარი</th>
                                    <th class="sorting" tabindex="0" aria-controls="dataTableExample" rowspan="1" colspan="1" aria-label="Office: activate to sort column ascending" style="width: 354.805px;">ელ.ფოსტა</th>
                                    <th class="sorting" tabindex="0" aria-controls="dataTableExample" rowspan="1" colspan="1" aria-label="Salary: activate to sort column ascending" style="width: 104.195px;">მოქმედება</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr class="odd">
                                    <td class="sorting_1">Airi Satou</td>
                                    <td>Satou</td>
                                    <td>AiriSatou@gmail.com</td>
                                    <td>
                                        <div class="d-flex justify-between text-center align-items-center">
                                            <div>
                                                <span class="badge badge-sm bg-warning cursor-pointer">ნახვა</span>
                                            </div>
                                            <div class="ms-2">
                                                <span class="badge badge-sm bg-success cursor-pointer">რედაქტირება</span>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="odd">
                                    <td class="sorting_1">Airi Satou</td>
                                    <td>Satou</td>
                                    <td>AiriSatou@gmail.com</td>
                                    <td>
                                        <div class="d-flex justify-between text-center align-items-center">
                                            <div>
                                                <span class="badge badge-sm bg-warning cursor-pointer">ნახვა</span>
                                            </div>
                                            <div class="ms-2">
                                                <span class="badge badge-sm bg-success cursor-pointer">რედაქტირება</span>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="odd">
                                    <td class="sorting_1">Airi Satou</td>
                                    <td>Satou</td>
                                    <td>AiriSatou@gmail.com</td>
                                    <td>
                                        <div class="d-flex justify-between text-center align-items-center">
                                            <div>
                                                <span class="badge badge-sm bg-warning cursor-pointer">ნახვა</span>
                                            </div>
                                            <div class="ms-2">
                                                <span class="badge badge-sm bg-success cursor-pointer">რედაქტირება</span>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="odd">
                                    <td class="sorting_1">Airi Satou</td>
                                    <td>Satou</td>
                                    <td>AiriSatou@gmail.com</td>
                                    <td>
                                        <div class="d-flex justify-between text-center align-items-center">
                                            <div>
                                                <span class="badge badge-sm bg-warning cursor-pointer">ნახვა</span>
                                            </div>
                                            <div class="ms-2">
                                                <span class="badge badge-sm bg-success cursor-pointer">რედაქტირება</span>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="odd">
                                    <td class="sorting_1">Airi Satou</td>
                                    <td>Satou</td>
                                    <td>AiriSatou@gmail.com</td>
                                    <td>
                                        <div class="d-flex justify-between text-center align-items-center">
                                            <div>
                                                <span class="badge badge-sm bg-warning cursor-pointer">ნახვა</span>
                                            </div>
                                            <div class="ms-2">
                                                <span class="badge badge-sm bg-success cursor-pointer">რედაქტირება</span>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="odd">
                                    <td class="sorting_1">Airi Satou</td>
                                    <td>Satou</td>
                                    <td>AiriSatou@gmail.com</td>
                                    <td>
                                        <div class="d-flex justify-between text-center align-items-center">
                                            <div>
                                                <span class="badge badge-sm bg-warning cursor-pointer">ნახვა</span>
                                            </div>
                                            <div class="ms-2">
                                                <span class="badge badge-sm bg-success cursor-pointer">რედაქტირება</span>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="odd">
                                    <td class="sorting_1">Airi Satou</td>
                                    <td>Satou</td>
                                    <td>AiriSatou@gmail.com</td>
                                    <td>
                                        <div class="d-flex justify-between text-center align-items-center">
                                            <div>
                                                <span class="badge badge-sm bg-warning cursor-pointer">ნახვა</span>
                                            </div>
                                            <div class="ms-2">
                                                <span class="badge badge-sm bg-success cursor-pointer">რედაქტირება</span>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="odd">
                                    <td class="sorting_1">Airi Satou</td>
                                    <td>Satou</td>
                                    <td>AiriSatou@gmail.com</td>
                                    <td>
                                        <div class="d-flex justify-between text-center align-items-center">
                                            <div>
                                                <span class="badge badge-sm bg-warning cursor-pointer">ნახვა</span>
                                            </div>
                                            <div class="ms-2">
                                                <span class="badge badge-sm bg-success cursor-pointer">რედაქტირება</span>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="odd">
                                    <td class="sorting_1">Airi Satou</td>
                                    <td>Satou</td>
                                    <td>AiriSatou@gmail.com</td>
                                    <td>
                                        <div class="d-flex justify-between text-center align-items-center">
                                            <div>
                                                <span class="badge badge-sm bg-warning cursor-pointer">ნახვა</span>
                                            </div>
                                            <div class="ms-2">
                                                <span class="badge badge-sm bg-success cursor-pointer">რედაქტირება</span>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="odd">
                                    <td class="sorting_1">Airi Satou</td>
                                    <td>Satou</td>
                                    <td>AiriSatou@gmail.com</td>
                                    <td>
                                        <div class="d-flex justify-between text-center align-items-center">
                                            <div>
                                                <span class="badge badge-sm bg-warning cursor-pointer">ნახვა</span>
                                            </div>
                                            <div class="ms-2">
                                                <span class="badge badge-sm bg-success cursor-pointer">რედაქტირება</span>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="odd">
                                    <td class="sorting_1">Airi Satou</td>
                                    <td>Satou</td>
                                    <td>AiriSatou@gmail.com</td>
                                    <td>
                                        <div class="d-flex justify-between text-center align-items-center">
                                            <div>
                                                <span class="badge badge-sm bg-warning cursor-pointer">ნახვა</span>
                                            </div>
                                            <div class="ms-2">
                                                <span class="badge badge-sm bg-success cursor-pointer">რედაქტირება</span>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="odd">
                                    <td class="sorting_1">Airi Satou</td>
                                    <td>Satou</td>
                                    <td>AiriSatou@gmail.com</td>
                                    <td>
                                        <div class="d-flex justify-between text-center align-items-center">
                                            <div>
                                                <span class="badge badge-sm bg-warning cursor-pointer">ნახვა</span>
                                            </div>
                                            <div class="ms-2">
                                                <span class="badge badge-sm bg-success cursor-pointer">რედაქტირება</span>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="odd">
                                    <td class="sorting_1">Airi Satou</td>
                                    <td>Satou</td>
                                    <td>AiriSatou@gmail.com</td>
                                    <td>
                                        <div class="d-flex justify-between text-center align-items-center">
                                            <div>
                                                <span class="badge badge-sm bg-warning cursor-pointer">ნახვა</span>
                                            </div>
                                            <div class="ms-2">
                                                <span class="badge badge-sm bg-success cursor-pointer">რედაქტირება</span>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="odd">
                                    <td class="sorting_1">Airi Satou</td>
                                    <td>Satou</td>
                                    <td>AiriSatou@gmail.com</td>
                                    <td>
                                        <div class="d-flex justify-between text-center align-items-center">
                                            <div>
                                                <span class="badge badge-sm bg-warning cursor-pointer">ნახვა</span>
                                            </div>
                                            <div class="ms-2">
                                                <span class="badge badge-sm bg-success cursor-pointer">რედაქტირება</span>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="odd">
                                    <td class="sorting_1">Airi Satou</td>
                                    <td>Satou</td>
                                    <td>AiriSatou@gmail.com</td>
                                    <td>
                                        <div class="d-flex justify-between text-center align-items-center">
                                            <div>
                                                <span class="badge badge-sm bg-warning cursor-pointer">ნახვა</span>
                                            </div>
                                            <div class="ms-2">
                                                <span class="badge badge-sm bg-success cursor-pointer">რედაქტირება</span>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="odd">
                                    <td class="sorting_1">Airi Satou</td>
                                    <td>Satou</td>
                                    <td>AiriSatou@gmail.com</td>
                                    <td>
                                        <div class="d-flex justify-between text-center align-items-center">
                                            <div>
                                                <span class="badge badge-sm bg-warning cursor-pointer">ნახვა</span>
                                            </div>
                                            <div class="ms-2">
                                                <span class="badge badge-sm bg-success cursor-pointer">რედაქტირება</span>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="odd">
                                    <td class="sorting_1">Airi Satou</td>
                                    <td>Satou</td>
                                    <td>AiriSatou@gmail.com</td>
                                    <td>
                                        <div class="d-flex justify-between text-center align-items-center">
                                            <div>
                                                <span class="badge badge-sm bg-warning cursor-pointer">ნახვა</span>
                                            </div>
                                            <div class="ms-2">
                                                <span class="badge badge-sm bg-success cursor-pointer">რედაქტირება</span>
                                            </div>
                                        </div>
                                    </td>
                                </tr>


                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection
