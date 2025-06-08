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
                                    <th class="" tabindex="0" aria-controls="dataTable" rowspan="1" colspan="1"  style="width: 100.57px;">სტატუსი</th>
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

                                        <td class="align-middle">
                                            @if($product->reject_reason)
                                                <span class="badge bg-danger" data-bs-toggle="tooltip" data-bs-placement="top"
                                                     data-bs-title="{{$product->reject_reason}}">უარყოფილი</span>
                                            @elseif($product->is_verified)
                                                <span class="badge bg-success">დადასტურებული</span>
                                            @else
                                                <span class="badge bg-secondary">დასადასტურებელი</span>
                                            @endif
                                        </td>

                                        <td class="">
                                       <div class="d-flex justify-content-between">
                                           <div> <a href="{{url('admin/product/detail/'.$product->id)}}" class="badge bg-info cursor-pointer" >ნახვა</a></div>
                                           <div> <a href="{{url('admin/product/edit/'.$product->id)}}" class="badge ms-2 bg-warning cursor-pointer" >რედაქტირება</a></div>
                                           <div> <a href="javascript:void(0);" onclick="showProductFiles({{$product->id}}); return false;" class="badge ms-2 bg-success cursor-pointer" data-product-id="{{$product->id}}">პროდუქტის ფაილები</a></div>
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

    <!-- Include the product files modal component -->
    @include('components.product-files-modal')

    <!-- Include the product files JavaScript -->
    <script src="{{ asset('js/product-files.js') }}"></script>

    <!-- Include the modal fix JavaScript -->
    <script src="{{ asset('js/modal-fix.js') }}"></script>

    <script>
        // Add direct click handlers to the product files buttons
        document.addEventListener('DOMContentLoaded', function() {
            const fileButtons = document.querySelectorAll('[data-product-id]');
            fileButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const productId = this.getAttribute('data-product-id');
                    if (productId) {
                        // Try multiple approaches to open the modal
                        try {
                            showProductFiles(productId);

                            // Additional fallback: If after 500ms the modal is still not visible, use direct approach
                            setTimeout(function() {
                                const modal = document.getElementById('productFilesModal');
                                if (modal && !modal.classList.contains('show')) {
                                    showModal('productFilesModal');

                                    // Try to load files
                                    fetch(`/api/public/product-files/${productId}`)
                                        .then(response => response.json())
                                        .then(product => {
                                            const filesContainer = document.querySelector('.files-container');
                                            if (filesContainer && product.product_images) {
                                                filesContainer.innerHTML = '';
                                                product.product_images.forEach(file => {
                                                    filesContainer.innerHTML += `
                                                        <div class="col-sm-6 col-md-4 mb-3">
                                                            <div class="card">
                                                                <div class="card-body text-center">
                                                                    <img src="${file.url}" class="img-thumbnail" style="max-height: 150px;">
                                                                </div>
                                                                <div class="card-footer">
                                                                    <a href="${file.url}" class="btn btn-sm btn-primary w-100" target="_blank">გახსნა</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    `;
                                                });
                                            }
                                        });
                                }
                            }, 500);
                        } catch (err) {
                            console.error('Error showing product files:', err);
                        }
                    }
                });
            });
        });
    </script>

@endsection
