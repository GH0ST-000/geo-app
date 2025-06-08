/**
 * Modal Fix - Ensures Bootstrap modals work correctly
 * 
 * This script provides a fallback mechanism for opening Bootstrap modals
 * when the standard Bootstrap JavaScript or jQuery methods fail.
 */

document.addEventListener('DOMContentLoaded', function() {
    // Fix for modal backdrop not being removed
    document.addEventListener('hidden.bs.modal', function (event) {
        // When any modal is hidden, make sure backdrop is removed
        const backdrops = document.querySelectorAll('.modal-backdrop');
        backdrops.forEach(backdrop => backdrop.remove());
        
        // Remove modal-open class from body
        document.body.classList.remove('modal-open');
        document.body.style.overflow = '';
        document.body.style.paddingRight = '';
    });
    
    // Register global showModal function for emergency use
    window.showModal = function(modalId) {
        const modalElement = document.getElementById(modalId);
        
        if (!modalElement) {
            console.error(`Modal with ID ${modalId} not found`);
            return;
        }
        
        try {
            // Try Bootstrap 5 native approach first
            if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                const bsModal = new bootstrap.Modal(modalElement);
                bsModal.show();
                return;
            }
            
            // Try jQuery approach next
            if (typeof $ !== 'undefined') {
                $(modalElement).modal('show');
                return;
            }
            
            // Manual approach as last resort
            modalElement.classList.add('show');
            modalElement.style.display = 'block';
            document.body.classList.add('modal-open');
            
            // Create backdrop if needed
            let backdrop = document.querySelector('.modal-backdrop');
            if (!backdrop) {
                backdrop = document.createElement('div');
                backdrop.classList.add('modal-backdrop', 'fade', 'show');
                document.body.appendChild(backdrop);
            }
            
            // Setup close handlers for this modal
            const closeButtons = modalElement.querySelectorAll('[data-bs-dismiss="modal"]');
            closeButtons.forEach(button => {
                button.addEventListener('click', function() {
                    closeModal(modalId);
                });
            });
            
            // Close when clicking backdrop
            backdrop.addEventListener('click', function() {
                closeModal(modalId);
            });
            
            // Close on ESC key
            document.addEventListener('keydown', function(event) {
                if (event.key === 'Escape') {
                    closeModal(modalId);
                }
            });
            
        } catch (error) {
            console.error('Error showing modal:', error);
        }
    };
    
    // Helper function to close modal manually
    function closeModal(modalId) {
        const modalElement = document.getElementById(modalId);
        
        if (!modalElement) {
            return;
        }
        
        modalElement.classList.remove('show');
        modalElement.style.display = 'none';
        
        // Remove backdrop
        const backdrops = document.querySelectorAll('.modal-backdrop');
        backdrops.forEach(backdrop => backdrop.remove());
        
        // Remove modal-open class
        document.body.classList.remove('modal-open');
        document.body.style.overflow = '';
        document.body.style.paddingRight = '';
    }
    
    // Add a handler to all close buttons to ensure backdrop cleanup
    document.querySelectorAll('[data-bs-dismiss="modal"]').forEach(button => {
        button.addEventListener('click', function() {
            // Force cleanup of backdrops and body styles
            setTimeout(() => {
                const backdrops = document.querySelectorAll('.modal-backdrop');
                backdrops.forEach(backdrop => backdrop.remove());
                
                document.body.classList.remove('modal-open');
                document.body.style.overflow = '';
                document.body.style.paddingRight = '';
            }, 300);
        });
    });
    
    // Safe forEach for handling non-array values
    function safeForEach(items, callback) {
        if (!items) return;
        
        // If items is already an array, use native forEach
        if (Array.isArray(items)) {
            items.forEach(callback);
            return;
        }
        
        // If items is an object, iterate through its values
        if (typeof items === 'object') {
            Object.values(items).forEach(callback);
            return;
        }
        
        // If it's a single item, treat it as a one-element array
        if (items) {
            callback(items, 0);
        }
    }
    
    // Override the default showProductFiles function if it exists
    const originalShowProductFiles = window.showProductFiles;
    
    if (typeof originalShowProductFiles === 'function') {
        window.showProductFiles = function(productId, type = 'standard_files') {
            try {
                // Show the modal first (most important)
                window.showModal('productFilesModal');
                
                // Show loading indicator
                const filesContainer = document.querySelector('.files-container');
                if (filesContainer) {
                    filesContainer.innerHTML = '<div class="col-12 text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>';
                }
                
                // Set modal title based on file type
                const modalTitle = document.getElementById('productFilesModalLabel');
                if (modalTitle) {
                    modalTitle.textContent = type === 'product_images' ? 
                        'პროდუქტის სურათები' : 'სტანდარტის ფაილები';
                }
                
                // Try to load the files
                fetch(`/api/public/product-files/${productId}`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! Status: ${response.status}`);
                        }
                        
                        const contentType = response.headers.get("content-type");
                        if (!contentType || !contentType.includes("application/json")) {
                            throw new Error("Response is not JSON, likely a redirect to login page.");
                        }
                        
                        return response.json();
                    })
                    .then(product => {
                        if (!filesContainer) return;
                        
                        // Check if product exists and has files
                        const files = product && product[type] ? product[type] : [];
                        
                        if (!files || (Array.isArray(files) && files.length === 0)) {
                            filesContainer.innerHTML = '<div class="col-12 text-center"><p>ფაილები არ მოიძებნა</p></div>';
                            return;
                        }
                        
                        // Clear container
                        filesContainer.innerHTML = '';
                        
                        // Use our safe forEach method
                        safeForEach(files, file => {
                            if (!file || !file.url) return; // Skip invalid files
                            
                            const isImage = file.mime_type && file.mime_type.startsWith('image/');
                            const html = `
                                <div class="col-sm-6 col-md-4 mb-3">
                                    <div class="card h-100">
                                        <div class="card-body text-center">
                                            ${isImage ? 
                                                `<img src="${file.url}" class="img-thumbnail" style="max-height: 150px;">` : 
                                                `<i class="fas fa-file fa-3x"></i>`
                                            }
                                            <p class="mt-2 mb-0 text-truncate">${file.name || file.file_name || 'ფაილი'}</p>
                                        </div>
                                        <div class="card-footer bg-transparent border-top-0">
                                            <a href="${file.url}" class="btn btn-sm btn-primary w-100" target="_blank">გახსნა</a>
                                        </div>
                                    </div>
                                </div>
                            `;
                            filesContainer.innerHTML += html;
                        });
                    })
                    .catch(error => {
                        console.error('Error fetching product files:', error);
                        if (filesContainer) {
                            filesContainer.innerHTML = `
                                <div class="col-12 text-center">
                                    <div class="alert alert-danger">
                                        <p>ფაილების ჩატვირთვა ვერ მოხერხდა</p>
                                        <small>${error.message || 'Server error'}</small>
                                    </div>
                                </div>`;
                        }
                    });
            } catch (error) {
                console.error('Error in showProductFiles:', error);
                // Show a basic error message in the modal
                const filesContainer = document.querySelector('.files-container');
                if (filesContainer) {
                    filesContainer.innerHTML = `
                        <div class="col-12 text-center">
                            <div class="alert alert-danger">
                                <p>დაფიქსირდა შეცდომა</p>
                                <small>${error.message || 'Unknown error'}</small>
                            </div>
                        </div>`;
                }
            }
        };
    }
}); 