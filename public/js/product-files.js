/**
 * Product Files Display Handler
 * 
 * This script provides functionality for displaying product files and standard files
 * in a modal with support for image lightbox and different file types.
 */

/**
 * Show product files in modal
 * 
 * @param {number} productId - The ID of the product
 * @param {string} type - The type of files to show ('product_images' or 'standard_files')
 */
function showProductFiles(productId, type = 'standard_files') {
    // Find modal element
    const modalElement = document.getElementById('productFilesModal');
    
    if (!modalElement) {
        console.error('Product files modal not found in DOM');
        return;
    }
    
    // Show loading state
    const filesContainer = document.querySelector('.files-container');
    if (!filesContainer) {
        console.error('Files container not found in DOM');
        return;
    }
    
    filesContainer.innerHTML = '<div class="col-12 text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>';
    
    // Set up modal close event handler if not already set
    if (!modalElement.dataset.closeHandlerAttached) {
        modalElement.addEventListener('hidden.bs.modal', function() {
            // When modal is hidden, make sure backdrop is removed
            const backdrops = document.querySelectorAll('.modal-backdrop');
            backdrops.forEach(backdrop => backdrop.remove());
            
            // Remove modal-open class and inline styles from body
            document.body.classList.remove('modal-open');
            document.body.style.overflow = '';
            document.body.style.paddingRight = '';
        });
        
        // Mark that we've attached the handler
        modalElement.dataset.closeHandlerAttached = 'true';
    }
    
    // Show the modal while loading - using jQuery for maximum compatibility
    if (typeof $ !== 'undefined') {
        // Using jQuery if available (which is common in Laravel projects with Bootstrap)
        $(modalElement).modal('show');
    } else if (typeof bootstrap !== 'undefined') {
        // Pure Bootstrap 5 approach
        const modal = new bootstrap.Modal(modalElement);
        modal.show();
    } else {
        // Fallback - add show classes manually
        modalElement.classList.add('show');
        modalElement.style.display = 'block';
        document.body.classList.add('modal-open');
        
        // Create backdrop if it doesn't exist
        let backdrop = document.querySelector('.modal-backdrop');
        if (!backdrop) {
            backdrop = document.createElement('div');
            backdrop.classList.add('modal-backdrop', 'fade', 'show');
            document.body.appendChild(backdrop);
        }
    }
    
    // Set modal title based on file type
    const modalTitle = document.getElementById('productFilesModalLabel');
    if (modalTitle) {
        if (type === 'product_images') {
            modalTitle.textContent = 'პროდუქტის სურათები';
        } else {
            modalTitle.textContent = 'სტანდარტის ფაილები';
        }
    }
    
    // Make an AJAX request to get the product with its files
    // Using the public endpoint which doesn't require authentication
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
            // Clear loading indicator
            filesContainer.innerHTML = '';
            
            // Get the correct files based on the type
            const files = product && product[type] ? product[type] : [];
            
            // Check if files exist and is iterable
            if (!files || (Array.isArray(files) && files.length === 0)) {
                filesContainer.innerHTML = '<div class="col-12 text-center"><p>ფაილები არ მოიძებნა</p></div>';
                return;
            }
            
            // Display each file, handling both array and object formats
            if (Array.isArray(files)) {
                // It's an array, use forEach
                files.forEach(file => {
                    if (!file || !file.url) return; // Skip invalid files
                    const fileElement = createFileElement(file);
                    filesContainer.innerHTML += fileElement;
                });
            } else if (typeof files === 'object') {
                // It's an object, iterate through values
                Object.values(files).forEach(file => {
                    if (!file || !file.url) return; // Skip invalid files
                    const fileElement = createFileElement(file);
                    filesContainer.innerHTML += fileElement;
                });
            } else {
                // Single file or unknown format
                const fileElement = createFileElement(files);
                filesContainer.innerHTML += fileElement;
            }
        })
        .catch(error => {
            console.error('Error fetching product files:', error);
            filesContainer.innerHTML = `
                <div class="col-12 text-center">
                    <div class="alert alert-danger">
                        <p>ფაილების ჩატვირთვა ვერ მოხერხდა</p>
                        <small>${error.message || 'Server error'}</small>
                        <div class="mt-3">
                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="window.location.reload()">თავიდან ცდა</button>
                        </div>
                    </div>
                </div>`;
        });
}

/**
 * Creates HTML element for a file
 * 
 * @param {Object} file - File object with url, name, mime_type, etc.
 * @returns {string} HTML string for the file element
 */
function createFileElement(file) {
    // Check if file is valid
    if (!file || !file.url) {
        return '';
    }
    
    const isImage = file.mime_type && file.mime_type.startsWith('image/');
    
    if (isImage) {
        return `
            <div class="col-sm-6 col-md-4 mb-3">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <div class="image-container position-relative" style="height: 150px; overflow: hidden;">
                            <img src="${file.url}" class="img-thumbnail h-100 cursor-pointer" 
                                 style="object-fit: cover; cursor: pointer;" 
                                 onclick="openLightbox('${file.url}', '${file.name || 'სურათი'}')">
                        </div>
                        <p class="mt-2 mb-0 text-truncate">${file.name || file.file_name || 'სურათი'}</p>
                    </div>
                    <div class="card-footer bg-transparent border-top-0">
                        <a href="${file.url}" class="btn btn-sm btn-primary w-100" target="_blank">გახსნა</a>
                    </div>
                </div>
            </div>
        `;
    } else {
        // For non-image files - show appropriate icon based on file type
        const fileIcon = getFileIcon(file.mime_type, file.custom_properties?.original_extension);
        
        return `
            <div class="col-sm-6 col-md-4 mb-3">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <div class="file-icon mb-2">
                            <i class="${fileIcon} fa-3x"></i>
                        </div>
                        <p class="mb-0 text-truncate">${file.name || file.file_name || 'ფაილი'}</p>
                    </div>
                    <div class="card-footer bg-transparent border-top-0">
                        <a href="${file.url}" class="btn btn-sm btn-primary w-100" target="_blank">გახსნა</a>
                    </div>
                </div>
            </div>
        `;
    }
}

/**
 * Open image in lightbox modal
 * 
 * @param {string} imageUrl - URL of the image to show
 * @param {string} title - Title for the lightbox modal
 */
function openLightbox(imageUrl, title) {
    const lightboxModal = document.getElementById('imageLightboxModal');
    const lightboxImage = document.getElementById('lightboxImage');
    const lightboxTitle = document.getElementById('imageLightboxModalLabel');
    
    if (!lightboxModal || !lightboxImage) {
        // If modal is not found, open image in a new tab as fallback
        window.open(imageUrl, '_blank');
        return;
    }
    
    // Set up modal close event handler if not already set
    if (!lightboxModal.dataset.closeHandlerAttached) {
        lightboxModal.addEventListener('hidden.bs.modal', function() {
            // When modal is hidden, make sure backdrop is removed
            const backdrops = document.querySelectorAll('.modal-backdrop');
            backdrops.forEach(backdrop => backdrop.remove());
            
            // Remove modal-open class and inline styles from body
            document.body.classList.remove('modal-open');
            document.body.style.overflow = '';
            document.body.style.paddingRight = '';
        });
        
        // Mark that we've attached the handler
        lightboxModal.dataset.closeHandlerAttached = 'true';
    }
    
    lightboxImage.src = imageUrl;
    if (lightboxTitle) {
        lightboxTitle.textContent = title || 'სურათის ნახვა';
    }
    
    // Show the lightbox modal
    if (typeof $ !== 'undefined') {
        $(lightboxModal).modal('show');
    } else if (typeof bootstrap !== 'undefined') {
        const modal = new bootstrap.Modal(lightboxModal);
        modal.show();
    } else {
        lightboxModal.classList.add('show');
        lightboxModal.style.display = 'block';
        document.body.classList.add('modal-open');
    }
}

/**
 * Get appropriate Font Awesome icon for file type
 * 
 * @param {string} mimeType - File's MIME type
 * @param {string} extension - File extension
 * @returns {string} Font Awesome icon class
 */
function getFileIcon(mimeType, extension) {
    if (!mimeType && !extension) return 'fas fa-file';
    
    // Default to extension if mime type is not available
    if (!mimeType && extension) {
        switch(extension.toLowerCase()) {
            case 'pdf': return 'fas fa-file-pdf';
            case 'doc':
            case 'docx': return 'fas fa-file-word';
            case 'xls':
            case 'xlsx': return 'fas fa-file-excel';
            case 'ppt':
            case 'pptx': return 'fas fa-file-powerpoint';
            case 'zip':
            case 'rar':
            case 'tar':
            case 'gz': return 'fas fa-file-archive';
            case 'txt': return 'fas fa-file-alt';
            case 'mp3':
            case 'wav':
            case 'ogg': return 'fas fa-file-audio';
            case 'mp4':
            case 'avi':
            case 'mov': return 'fas fa-file-video';
            default: return 'fas fa-file';
        }
    }
    
    // Use mime type for better accuracy
    if (mimeType.startsWith('image/')) return 'fas fa-file-image';
    if (mimeType.startsWith('video/')) return 'fas fa-file-video';
    if (mimeType.startsWith('audio/')) return 'fas fa-file-audio';
    if (mimeType === 'application/pdf') return 'fas fa-file-pdf';
    if (mimeType.includes('spreadsheet') || mimeType.includes('excel') || mimeType.includes('csv')) return 'fas fa-file-excel';
    if (mimeType.includes('word') || mimeType.includes('document')) return 'fas fa-file-word';
    if (mimeType.includes('presentation') || mimeType.includes('powerpoint')) return 'fas fa-file-powerpoint';
    if (mimeType.includes('compressed') || mimeType.includes('zip') || mimeType.includes('archive')) return 'fas fa-file-archive';
    if (mimeType.startsWith('text/')) return 'fas fa-file-alt';
    
    return 'fas fa-file';
}

// Add global event handler for modal hidden events
document.addEventListener('DOMContentLoaded', function() {
    // General backdrop cleanup - run this on page load and periodically
    function cleanupBackdrops() {
        // If no modals are visible but we have backdrops, remove them
        const visibleModals = document.querySelectorAll('.modal.show');
        const backdrops = document.querySelectorAll('.modal-backdrop');
        
        if (visibleModals.length === 0 && backdrops.length > 0) {
            backdrops.forEach(backdrop => backdrop.remove());
            document.body.classList.remove('modal-open');
            document.body.style.overflow = '';
            document.body.style.paddingRight = '';
        }
    }
    
    // Run cleanup on page load
    cleanupBackdrops();
    
    // Run cleanup periodically (every 2 seconds)
    setInterval(cleanupBackdrops, 2000);
}); 