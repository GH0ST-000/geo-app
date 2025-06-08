@extends('layouts.app')
@section('content')
   <div class="mt-3 mb-3">
       <span>პროდუქტის დეტალური გვერდი</span>
   </div>
    <div class="card">
        <div class="card-body">
            <div class="container">
                <div class="row">
                    <div class="col-sm-6 col-12 mb-3">
                        <div class="form-group">
                            <label for="exampleInputEmail1 mb-2">მომხმარებელი</label>
                            <input type="text" class="form-control" value=" {{$user}}" readonly>
                        </div>
                    </div>
                    <div class="col-sm-6 col-12 mb-3">
                        <div class="form-group">
                            <label for="exampleInputEmail1 mb-2">პროდუქტის დასახელება</label>
                            <input type="text" class="form-control" value="{{$product->product_name}}" readonly>
                        </div>
                    </div>
                    <div class="col-sm-6 col-12 mb-3">
                        <div class="form-group">
                            <label for="exampleInputEmail1 mb-2 ">პროდუქტის მოცულობა</label>
                            <input type="text" class="form-control" value="{{$product->packing_capacity}}" readonly>
                        </div>
                    </div>
                    <div class="col-sm-6 col-12 mb-3">
                        <div class="form-group">
                            <label for="exampleInputEmail1 mb-2">პროდუქტის მისამართი</label>
                            <input type="text" class="form-control" value="{{$product->address}}" readonly>
                        </div>
                    </div>
                    <div class="col-12 mb-3">
                        <div class="form-group">
                            <label for="exampleInputEmail1 mb-2">პროდუქტის აღწერა</label>
                            <textarea rows="10" class="form-control" readonly>{{$product->product_description}}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-3 mb-3 d-flex justify-content-between align-items-center">
        <span class="badge bg-success">პროდუქტის ფაილები</span>
        <div>
            <button type="button" class="btn btn-primary btn-sm" onclick="showProductFiles({{ $product->id }}, 'product_images'); return false;" data-product-id="{{ $product->id }}" data-file-type="product_images">
                <i class="fas fa-images"></i> ნახეთ სურათები
            </button>
            @if($product->standard)
            <button type="button" class="btn btn-info btn-sm" onclick="showProductFiles({{ $product->id }}, 'standard_files'); return false;" data-product-id="{{ $product->id }}" data-file-type="standard_files">
                <i class="fas fa-file-alt"></i> ნახეთ სტანდარტის ფაილები
            </button>
            @endif
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="container">
                <div class="row">
                @if(sizeof($images) > 0 && $images[0]->product_images )
                    @foreach($images[0]->product_images as $image)
                        <div class="col-sm-6 col-12 mb-3">
                            <div class="card">
                                <a class="card-body" href="{{$image['thumb']}}" data-lightbox="gallery" data-title="image">
                                    <img style="width: 90%; height: 90%;background-size: cover"  src="{{$image['thumb']}}">
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @else
                <div class="d-flex text-center justify-content-center">
                    <span>მოცემულ პროდუქტზე სურათები არ მოიძებნა</span>
                </div>
            @endif
        </div>
    </div>

    <!-- Include the product files modal component -->
    @include('components.product-files-modal')

    <!-- Include the product files JavaScript -->
    <script src="{{ asset('js/product-files.js') }}"></script>
    
    <!-- Include the modal fix JavaScript -->
    <script src="{{ asset('js/modal-fix.js') }}"></script>
   
   <!-- Add product approval/rejection actions -->
   <div class="mt-3 mb-3 card">
       <div class="card-body">
           <div class="container">
               <div class="row">
                   <div class="col-12">
                       @if($product->reject_reason)
                           <div class="alert alert-danger">
                               <h5><i class="fas fa-exclamation-triangle"></i> პროდუქტი უარყოფილია</h5>
                               <p><strong>მიზეზი:</strong> {{ $product->reject_reason }}</p>
                           </div>
                       @elseif($product->is_verified)
                           <div class="alert alert-success">
                               <h5><i class="fas fa-check-circle"></i> პროდუქტი დადასტურებულია</h5>
                           </div>
                       @else
                           <div class="alert alert-warning">
                               <h5><i class="fas fa-clock"></i> პროდუქტი ელოდება დადასტურებას</h5>
                           </div>
                       @endif
                   </div>
               </div>
               
               <div class="row mt-3">
                   <div class="col-12">
                       <div class="d-flex justify-content-end">
                           <div class="me-2">
                               <button type="button" class="btn btn-danger text-white" data-bs-toggle="modal" data-bs-target="#rejectProductModal">
                                   <i class="fas fa-times-circle"></i> უარყოფა
                               </button>
                           </div>
                           <div>
                               <button type="button" class="btn btn-success" onclick="document.getElementById('directApproveForm').submit()">
                                   <i class="fas fa-check-circle"></i> დადასტურება
                               </button>
                           </div>
                           <!-- Direct Form Submission (Hidden) -->
                           <form id="directApproveForm" action="{{ route('product.approve') }}" method="POST" style="display: none;">
                               @csrf
                               <input type="hidden" name="product_id" value="{{ $product->id }}">
                           </form>
                       </div>
                   </div>
               </div>
           </div>
       </div>
   </div>

   <!-- Reject Product Modal -->
   <div class="modal fade" id="rejectProductModal" tabindex="-1" aria-labelledby="rejectProductModalLabel" aria-hidden="true">
       <div class="modal-dialog">
           <form method="post" action="{{ route('product.reject') }}" id="rejectProductForm">
               @csrf
               <div class="modal-content">
                   <div class="modal-header">
                       <h5 class="modal-title" id="rejectProductModalLabel">პროდუქტის უარყოფა</h5>
                       <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                   </div>
                   <div class="modal-body">
                       <div class="container">
                           <div class="row">
                               <div class="col-12">
                                   <div>
                                       <label for="reject_reason">გთხოვთ შეიყვანოთ უარყოფის მიზეზი</label>
                                       <textarea required rows="5" class="form-control" placeholder="მიზეზი" name="reject_reason"></textarea>
                                   </div>
                                   <input type="hidden" name="product_id" value="{{ $product->id }}">
                               </div>
                           </div>
                       </div>
                   </div>
                   <div class="modal-footer">
                       <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">დახურვა</button>
                       <button type="submit" class="btn btn-danger">პროდუქტის უარყოფა</button>
                   </div>
               </div>
           </form>
       </div>
   </div>
   
   <script>
       lightbox.option({
           'resizeDuration': 200,
           'wrapAround': true
       });
       
       // Add direct click handlers to the file buttons
       document.addEventListener('DOMContentLoaded', function() {
           // File buttons handlers
           const fileButtons = document.querySelectorAll('[data-product-id][data-file-type]');
           fileButtons.forEach(button => {
               button.addEventListener('click', function(e) {
                   e.preventDefault();
                   const productId = this.getAttribute('data-product-id');
                   const fileType = this.getAttribute('data-file-type');
                   
                   if (productId && fileType) {
                       // Try multiple approaches to open the modal
                       try {
                           showProductFiles(productId, fileType);
                           
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
                                           if (filesContainer && product[fileType]) {
                                               // Set modal title
                                               const modalTitle = document.getElementById('productFilesModalLabel');
                                               if (modalTitle) {
                                                   modalTitle.textContent = fileType === 'product_images' ? 
                                                       'პროდუქტის სურათები' : 'სტანდარტის ფაილები';
                                               }
                                               
                                               // Clear container
                                               filesContainer.innerHTML = '';
                                               
                                               product[fileType].forEach(file => {
                                                   const isImage = file.mime_type && file.mime_type.startsWith('image/');
                                                   filesContainer.innerHTML += `
                                                       <div class="col-sm-6 col-md-4 mb-3">
                                                           <div class="card">
                                                               <div class="card-body text-center">
                                                                   ${isImage ? 
                                                                       `<img src="${file.url}" class="img-thumbnail" style="max-height: 150px;">` : 
                                                                       `<i class="fas fa-file fa-3x"></i>`
                                                                   }
                                                                   <p class="mt-2 mb-0 text-truncate">${file.name || file.file_name || 'ფაილი'}</p>
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
