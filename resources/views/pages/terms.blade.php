@extends('layouts.app')
@section('content')
<style>

    .ck-editor__editable {
        min-height: 300px; /* Set your desired height */
        width: 100%; /* Set your desired width */
    }
    .our-services {
        margin-top:  50px;
    }
</style>
    @if(session()->has('message'))
        <div class="alert alert-success" id="flash_message">
            {{ session()->get('message') }}
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="mb-4 d-flex">
                <a style="cursor: pointer" data-bs-toggle="modal" data-bs-target="#exampleModal" class="mb-4 d-flex justify-content-start items-center text-center" id="root_plus">
                    <i class="link-icon text-success" data-feather="plus-circle"></i>
                    <span class="ms-2 mt-1" id="page_name">Add New Terms</span>
                </a>
            </div>


            <div class="table-responsive">
                <div id="dataTableExample_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                    <div class="row">
                        <div class="col-sm-12">
                            <table id="dataTable" class="table dataTable no-footer align-middle pb-4" aria-describedby="dataTableExample_info">
                                <thead>
                                <tr>
                                    <th class="sorting sorting_asc" tabindex="0" aria-controls="dataTable" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Name: activate to sort column descending" style="width: 145.57px;">ID</th>
                                    <th class="sorting" tabindex="0" aria-controls="dataTable" rowspan="1" colspan="1" aria-label="Position: activate to sort column ascending" style="width: 205.57px;">Pages</th>
                                    <th class="sorting" tabindex="0" aria-controls="dataTable" rowspan="1" colspan="1" aria-label="Office: activate to sort column ascending" style="width: 175.57px;">Created at</th>
                                    <th class="sorting" tabindex="0" aria-controls="dataTable" rowspan="1" colspan="1" aria-label="Office: activate to sort column ascending" style="width: 175.57px;">Action</th>

                                </tr>
                                </thead>
                                <tbody>



                                <tr class="odd">
                                    <td class="sorting_1">1</td>
                                    <td class="align-middle">Lorem ipsum dolor sit.</td>

                                    <td class="align-middle">Lorem ipsum dolor sit.}</td>

                                    <td class="">
                                    <span data-bs-toggle="modal" data-bs-target="#editableModal"
                                          class="badge bg-info cursor-pointer" >Edit</span>
                                    </td>
                                </tr>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                   <form action="{{url('add_terms')}}" method="post" enctype="multipart/form-data" >
                       @csrf
                       <div class="modal-content">
                           <div class="modal-header">
                               <h5 class="modal-title" id="exampleModalLabel">Add new term
                                   <span class="ms-2 badge bg-warning">All fields are required !</span>
                               </h5>
                               <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
                           </div>
                           <div class="modal-body">
                               <div class="container">
                                   <div class="row">
                                       <div class="col-12">
                                           <div class="mb-3">
                                               <label for="pages" class="form-label">Select page
                                                   <i data-bs-toggle="tooltip" data-bs-placement="top"
                                                      title="When you select a page, the specified text will appear on the selected page" class="link-icon ms-2"
                                                      style="width: 16px;" data-feather="info"></i>
                                               </label>
                                               <select class="form-select" id="pages" required name="page_name">
                                                   <option value="Dashboard">Dashboard</option>
                                                   <option value="My Farm">My Farm</option>
                                                   <option value="Offers">Offers</option>
                                                   <option value="Specialist Request">Specialist Request</option>
                                                   <option value="Find Specialist">Find Specialist</option>
                                               </select>
                                           </div>
                                           <div class="mb-3">
                                               <label for="editor" class="form-label">Generate Text</label>
                                               <textarea id="editor" name="editor_names"></textarea>
                                           </div>
                                       </div>
                                   </div>
                               </div>

                           </div>
                           <div class="modal-footer">
                               <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                               <button type="submit" class="btn btn-primary">Save changes</button>
                           </div>
                       </div>
                   </form>
                </div>
            </div>


            <div class="modal fade" id="editableModal" tabindex="-1" aria-labelledby="editableModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <form action="{{url('update_terms')}}" method="post" enctype="multipart/form-data" >
                        @csrf
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editableModalLabel">Edit term
                                    <span class="ms-2 badge bg-warning">All fields are required !</span>
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="container">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="mb-3">
                                                <label for="pages" class="form-label">Select page
                                                    <i data-bs-toggle="tooltip" data-bs-placement="top"
                                                       title="When you select a page, the specified text will appear on the selected page" class="link-icon ms-2"
                                                       style="width: 16px;" data-feather="info"></i>
                                                </label>
                                                <select class="form-select" id="pagesN" required name="page_name">
                                                    <option value="Dashboard">Dashboard</option>
                                                    <option value="My Farm">My Farm</option>
                                                    <option value="Offers">Offers</option>
                                                    <option value="Specialist Request">Specialist Request</option>
                                                    <option value="Find Specialist">Find Specialist</option>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label for="editor" class="form-label">Generate Text</label>
                                                <textarea id="editor1" name="editor_names"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" name="ck_id" id="ck_id_of">
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Save changes</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>



    <script>
        document.addEventListener('DOMContentLoaded', (event) => {
            ClassicEditor
                .create(document.querySelector('#editor'))
                .catch(error => {
                    console.error(error);
                });
        });

        let editorInstance;

        document.addEventListener('DOMContentLoaded', (event) => {
            ClassicEditor
                .create(document.querySelector('#editor1'))
                .then(editor => {
                    editorInstance = editor;
                })
                .catch(error => {
                    console.error(error);
                });
        });


        function GetModal(id) {
            $(document).ready(function () {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: "GET",
                    url: '/get_terms',
                    cache: false,
                    data: { id: id },
                    success: function (data) {
                        const selectElement = document.getElementById('pagesN');
                        const selectedIndex = Array.from(selectElement.options).findIndex(option => option.value === data.name);

                        if (selectedIndex !== -1) {
                            selectElement.selectedIndex = selectedIndex;
                        } else {
                            console.error('Option not found in the select element.');
                        }
                        if (editorInstance) {
                            editorInstance.setData(data.content);
                        } else {
                            console.error('CKEditor not initialized.');
                        }
                        document.getElementById('ck_id_of').value = data.id
                    }
                });
            });
        }
    </script>







@endsection
