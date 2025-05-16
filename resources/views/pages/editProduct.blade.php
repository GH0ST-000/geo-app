@extends('layouts.app')
@section('content')
    @if(session()->has('message'))
        <div class="alert alert-success" id="flash_message">
            {{ session()->get('message') }}
        </div>
    @endif
    <!-- CSRF Token for JavaScript -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="mt-3 mb-3">
        <span>პროდუქტის დეტალური გვერდი</span>
    </div>
    <div class="card">
        <div class="card-body">
            <form id="productUpdateForm" action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data" onsubmit="return validateForm(event)">
                @csrf
                @method('PUT')
                <div class="container">
                    <div class="row">
                        <div class="col-sm-6 col-12 mb-3">
                            <div class="form-group">
                                <label for="product_name" class="mb-2">პროდუქტის დასახელება</label>
                                <input type="text" class="form-control" id="product_name" name="product_name" value="{{$product->product_name}}">
                            </div>
                        </div>
                        <div class="col-sm-6 col-12 mb-3">
                            <div class="form-group">
                                <label for="packing_capacity" class="mb-2">პროდუქტის მოცულობა</label>
                                <input type="text" class="form-control" id="packing_capacity" name="packing_capacity" value="{{$product->packing_capacity}}">
                            </div>
                        </div>
                        <div class="col-sm-6 col-12 mb-3">
                            <div class="form-group">
                                <label for="address" class="mb-2">პროდუქტის მისამართი</label>
                                <input type="text" class="form-control" id="address" name="address" value="{{$product->address}}">
                            </div>
                        </div>
                        <div class="col-12 mb-3">
                            <div class="form-group">
                                <label for="product_description" class="mb-2">პროდუქტის აღწერა</label>
                                <textarea rows="10" class="form-control" id="product_description" name="product_description">{{$product->product_description}}</textarea>
                            </div>
                        </div>

                        <!-- Enhanced Image Upload Section -->
                        <div class="col-12 mb-3">
                            <div class="form-group">
                                <label for="product_images" class="mb-2">პროდუქტის სურათები</label>

                                <!-- Drag & Drop Area -->
                                <div id="dropzone" class="dropzone-area p-5 mb-3 text-center border border-2 rounded" style="border-style: dashed !important; background-color: #f8f9fa; transition: all 0.3s ease;">
                                    <div class="dropzone-content">
                                        <i class="fas fa-cloud-upload-alt mb-3" style="font-size: 3rem; color: #6c757d;"></i>
                                        <h5>ჩააგდეთ სურათი აქ</h5>
                                        <p class="text-muted">ან</p>
                                        <div class="btn btn-primary position-relative overflow-hidden">
                                            ატვირთეთ სურათები
                                            <input type="file" id="product_images" name="product_images[]" multiple accept="image/*" class="position-absolute" style="opacity: 0; top: 0; left: 0; width: 100%; height: 100%; cursor: pointer;" onclick="event.stopPropagation()">
                                        </div>
                                        <p class="mt-2 text-muted small">მაქსიმალური ზომა: 20MB | გამოსახულების ფორმატები: JPG, PNG, GIF</p>
                                    </div>

                                    <!-- Upload Progress -->
                                    <div class="progress mt-3 d-none" style="height: 10px;">
                                        <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 0%"></div>
                                    </div>
                                </div>

                                <!-- Image Preview Section with improved styling -->
                                <div class="mt-3">
                                    <h5 class="mb-3">სურათების პრევიუ</h5>
                                    <div class="row" id="image-preview-container">
                                        <!-- New images preview will appear here -->
                                        @if(sizeof($images) > 0 && $images[0]->product_images)
                                            @foreach($images[0]->product_images as $key => $image)
                                                <div class="col-sm-4 col-6 mb-3 preview-item" data-image-id="{{ $image['id'] }}">
                                                    <div class="card shadow-sm">
                                                        <div class="card-body p-2 position-relative">
                                                            <div class="position-absolute delete-button-container" style="top: 5px; right: 5px; z-index: 10;">
                                                                <button type="button" class="btn delete-image" data-media-id="{{ $image['id'] }}">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x" viewBox="0 0 16 16">
                                                                        <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708"/>
                                                                    </svg>
                                                                </button>
                                                            </div>
                                                            <div class="image-container" style="height: 150px; overflow: hidden; cursor: pointer;">
                                                                <a href="{{$image['medium']}}" data-lightbox="product-gallery" data-title="პროდუქტის სურათი">
                                                                    <img class="img-fluid w-100 h-100" src="{{$image['thumb']}}" alt="Product Image" style="object-fit: cover;">
                                                                </a>
                                                            </div>
                                                            <!-- Hidden input to track deleted images -->
                                                            <input type="hidden" name="existing_images[]" value="{{ $image['id'] }}">
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Form Action Buttons -->
                        <div class="col-12 mt-4">
                            <div class="d-flex justify-content-between">
                                <button type="button" id="deleteProductBtn" class="btn btn-danger invisible">
                                    <i class="fas fa-trash-alt me-2"></i>წაშლა
                                </button>
                                <div>
                                    <a href="{{ route('products-detail', $product->id) }}" class="btn btn-secondary invisible me-2">გაუქმება</a>
                                    <button type="submit" class="btn btn-success" id="updateButton">განახლება</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <!-- Delete Product Form -->
            <form id="deleteProductForm" action="{{ route('products.destroy', $product->id) }}" method="POST" style="display: none;">
                @csrf
                @method('DELETE')
            </form>
        </div>
    </div>

    <!-- Confirmation Modal -->
    <div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmationModalLabel">დადასტურება</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="confirmationModalBody">
                    დარწმუნებული ხართ?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">გაუქმება</button>
                    <button type="button" class="btn btn-danger" id="confirmActionBtn">დიახ, წაშალე</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced Script for Image Upload with Drag & Drop -->
    <script>
        // Form validation function to ensure the form is submitted properly
        function validateForm(event) {
            // Return true to allow form submission
            return true;
        }

        document.addEventListener('DOMContentLoaded', function() {
            const fileInput = document.getElementById('product_images');
            const dropzone = document.getElementById('dropzone');
            const previewContainer = document.getElementById('image-preview-container');
            const progressBar = dropzone.querySelector('.progress-bar');
            const progressContainer = dropzone.querySelector('.progress');
            const productUpdateForm = document.getElementById('productUpdateForm');
            const deleteProductBtn = document.getElementById('deleteProductBtn');
            const deleteProductForm = document.getElementById('deleteProductForm');
            const confirmationModal = new bootstrap.Modal(document.getElementById('confirmationModal'));
            const confirmActionBtn = document.getElementById('confirmActionBtn');
            const confirmationModalBody = document.getElementById('confirmationModalBody');
            const updateButton = document.getElementById('updateButton');

            // Prevent update button from triggering file input
            updateButton.addEventListener('click', function(e) {
                e.stopPropagation(); // Prevent event from bubbling up
                productUpdateForm.submit(); // Explicitly submit the form
            });

            // Array to track images to delete
            const imagesToDelete = [];

            // Counter for new images (to create unique IDs)
            let newImageCounter = 0;

            // Highlight dropzone when dragging files over it
            ['dragenter', 'dragover'].forEach(eventName => {
                dropzone.addEventListener(eventName, highlight, false);
            });

            ['dragleave', 'drop'].forEach(eventName => {
                dropzone.addEventListener(eventName, unhighlight, false);
            });

            function highlight() {
                dropzone.classList.add('border-primary');
                dropzone.style.backgroundColor = '#e8f4ff';
            }

            function unhighlight() {
                dropzone.classList.remove('border-primary');
                dropzone.style.backgroundColor = '#f8f9fa';
            }

            // Handle dropped files
            dropzone.addEventListener('drop', handleDrop, false);

            function handleDrop(e) {
                e.preventDefault();
                e.stopPropagation();

                const dt = e.dataTransfer;
                const files = dt.files;

                handleFiles(files);
            }

            // Handle files from input or drop
            fileInput.addEventListener('change', function() {
                handleFiles(this.files);
            });

            function handleFiles(files) {
                if (files.length === 0) return;

                // Show progress
                progressContainer.classList.remove('d-none');

                // Simulate upload progress
                let progress = 0;
                const intervalId = setInterval(() => {
                    progress += 5;
                    progressBar.style.width = `${Math.min(progress, 100)}%`;

                    if (progress >= 100) {
                        clearInterval(intervalId);
                        setTimeout(() => {
                            progressContainer.classList.add('d-none');
                            progressBar.style.width = '0%';
                        }, 1000);
                    }
                }, 100);

                Array.from(files).forEach(file => {
                    // Only process image files
                    if (!file.type.match('image.*')) {
                        return;
                    }

                    // Check file size (max 20MB)
                    if (file.size > 20 * 1024 * 1024) {
                        alert(`ფაილი "${file.name}" ზედმეტად დიდია. მაქსიმალური ზომა არის 20MB.`);
                        return;
                    }

                    previewFile(file);
                });
            }

            function previewFile(file) {
                const reader = new FileReader();
                const uniqueId = `new-image-${newImageCounter++}`;

                // Create preview elements
                const previewCol = document.createElement('div');
                previewCol.className = 'col-sm-4 col-6 mb-3 preview-item';
                previewCol.dataset.file = file.name;
                previewCol.dataset.id = uniqueId;

                const card = document.createElement('div');
                card.className = 'card shadow-sm';

                const cardBody = document.createElement('div');
                cardBody.className = 'card-body p-2 position-relative';

                const deleteButtonContainer = document.createElement('div');
                deleteButtonContainer.className = 'position-absolute delete-button-container';
                deleteButtonContainer.style = 'top: 5px; right: 5px; z-index: 10;';

                const deleteBtn = document.createElement('button');
                deleteBtn.className = 'btn delete-image';
                deleteBtn.type = 'button';
                deleteBtn.innerHTML = `
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x" viewBox="0 0 16 16">
                        <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708"/>
                    </svg>
                `;

                const imageContainer = document.createElement('div');
                imageContainer.className = 'image-container';
                imageContainer.style = 'height: 150px; overflow: hidden; cursor: pointer;';

                // Create lightbox link
                const lightboxLink = document.createElement('a');
                lightboxLink.setAttribute('data-lightbox', 'product-gallery');
                lightboxLink.setAttribute('data-title', 'პროდუქტის სურათი');

                const img = document.createElement('img');
                img.className = 'img-fluid w-100 h-100';
                img.style = 'object-fit: cover;';
                img.alt = 'Product Image';

                // Set up delete functionality
                deleteBtn.addEventListener('click', function() {
                    // Show confirmation modal
                    confirmationModalBody.textContent = 'დარწმუნებული ხართ, რომ გსურთ ამ სურათის წაშლა?';
                    confirmActionBtn.textContent = 'დიახ, წაშალე';

                    // Set up confirmation action
                    const originalActionHandler = confirmActionBtn.onclick;
                    confirmActionBtn.onclick = function() {
                        // Add animation before removing
                        previewCol.style.transition = 'all 0.3s ease';
                        previewCol.style.opacity = '0';
                        previewCol.style.transform = 'scale(0.8)';

                        setTimeout(() => {
                            previewCol.remove();
                        }, 300);

                        confirmationModal.hide();
                        // Restore original handler
                        confirmActionBtn.onclick = originalActionHandler;
                    };

                    confirmationModal.show();
                });

                // Read the file and set image src
                reader.onload = function(e) {
                    img.src = e.target.result;
                    lightboxLink.href = e.target.result;
                };

                reader.readAsDataURL(file);

                // Append elements
                lightboxLink.appendChild(img);
                imageContainer.appendChild(lightboxLink);
                deleteButtonContainer.appendChild(deleteBtn);
                cardBody.appendChild(deleteButtonContainer);
                cardBody.appendChild(imageContainer);
                card.appendChild(cardBody);
                previewCol.appendChild(card);
                previewContainer.appendChild(previewCol);

                // Add animation effect
                setTimeout(() => {
                    previewCol.style.transition = 'all 0.3s ease';
                    previewCol.style.opacity = '1';
                }, 10);
            }

            // Handle Delete for existing images
            document.querySelectorAll('.delete-image[data-media-id]').forEach(button => {
                button.addEventListener('click', function() {
                    const mediaId = this.getAttribute('data-media-id');
                    const previewItem = this.closest('.preview-item');

                    if (mediaId && previewItem) {
                        // Show confirmation modal
                        confirmationModalBody.textContent = 'დარწმუნებული ხართ, რომ გსურთ ამ სურათის წაშლა?';
                        confirmActionBtn.textContent = 'დიახ, წაშალე';

                        // Set up confirmation action
                        const originalActionHandler = confirmActionBtn.onclick;
                        confirmActionBtn.onclick = function() {
                            // Create a dedicated form for immediate image deletion
                            const deleteImageForm = document.createElement('form');
                            deleteImageForm.method = 'POST';
                            deleteImageForm.action = "{{ route('product.image.delete', ':id') }}".replace(':id', mediaId);
                            deleteImageForm.style.display = 'none';

                            // Add CSRF token
                            const csrfToken = document.createElement('input');
                            csrfToken.type = 'hidden';
                            csrfToken.name = '_token';
                            csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                            deleteImageForm.appendChild(csrfToken);

                            // Add method override for DELETE
                            const methodInput = document.createElement('input');
                            methodInput.type = 'hidden';
                            methodInput.name = '_method';
                            methodInput.value = 'DELETE';
                            deleteImageForm.appendChild(methodInput);

                            // Add to document and submit
                            document.body.appendChild(deleteImageForm);

                            // Show loading state
                            previewItem.style.opacity = '0.5';

                            // Submit the form
                            deleteImageForm.submit();

                            // Clean up
                            confirmationModal.hide();
                            confirmActionBtn.onclick = originalActionHandler;
                        };

                        confirmationModal.show();
                    }
                });
            });

            // Handle product delete button
            deleteProductBtn.addEventListener('click', function() {
                // Show confirmation modal
                confirmationModalBody.textContent = 'დარწმუნებული ხართ, რომ გსურთ პროდუქტის წაშლა? ეს მოქმედება ვერ იქნება გაუქმებული.';
                confirmActionBtn.textContent = 'დიახ, წაშალე პროდუქტი';

                // Set up confirmation action
                const originalActionHandler = confirmActionBtn.onclick;
                confirmActionBtn.onclick = function() {
                    // Show page-wide loader
                    showPageLoader();

                    // Submit the delete form
                    deleteProductForm.submit();

                    // Hide modal
                    confirmationModal.hide();

                    // Restore original handler
                    confirmActionBtn.onclick = originalActionHandler;
                };

                confirmationModal.show();
            });

            // Function to show page-wide loader
            function showPageLoader() {
                // Create overlay
                const overlay = document.createElement('div');
                overlay.className = 'page-loader-overlay';
                overlay.innerHTML = `
                    <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <div class="mt-3 text-white">პროდუქტი იშლება...</div>
                `;
                document.body.appendChild(overlay);
            }

            // Make preview container sortable (optional, if you want to allow reordering)
            if (typeof jQuery !== 'undefined' && typeof jQuery.ui !== 'undefined') {
                $("#image-preview-container").sortable({
                    placeholder: "sort-placeholder",
                    cursor: "move"
                });
            }
        });
    </script>

    <!-- Lightbox configuration -->
    <script>
        lightbox.option({
            'resizeDuration': 200,
            'wrapAround': true,
            'alwaysShowNavOnTouchDevices': true,
            'fitImagesInViewport': true
        });
    </script>

    <!-- Add some custom CSS -->
    <style>
        .hidden-file-input {
            cursor: pointer;
        }

        .dropzone-area {
            cursor: pointer;
            min-height: 200px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .dropzone-area:hover {
            background-color: #e9ecef !important;
        }

        .preview-item {
            opacity: 0.9;
            transition: all 0.2s ease;
        }

        .preview-item:hover {
            opacity: 1;
            transform: translateY(-5px);
        }

        .delete-image {
            background-color: #dc3545;
            color: white;
            border-radius: 50%;
            width: 28px;
            height: 28px;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
            border: none;
            transition: all 0.2s ease;
        }

        .delete-image:hover {
            transform: scale(1.1);
            background-color: #c82333;
        }

        .delete-image svg {
            width: 16px;
            height: 16px;
        }

        .sort-placeholder {
            border: 2px dashed #ccc;
            height: 150px;
            margin-bottom: 1rem;
        }

        /* Improve lightbox cursor */
        .image-container a {
            display: block;
            width: 100%;
            height: 100%;
        }

        /* Page loader overlay */
        .page-loader-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.7);
            z-index: 9999;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            backdrop-filter: blur(3px);
        }
    </style>
@endsection
