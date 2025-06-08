<!-- Include the modal fix CSS -->
<link rel="stylesheet" href="{{ asset('css/modal-fix.css') }}">

<!-- Product Files Modal Component -->
<div class="modal fade" id="productFilesModal" tabindex="-1" aria-labelledby="productFilesModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="productFilesModalLabel">პროდუქტის ფაილები</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="container">
                    <div class="row files-container">
                        <!-- Files will be loaded here via JavaScript -->
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">დახურვა</button>
            </div>
        </div>
    </div>
</div>

<!-- Image Lightbox Modal for viewing images -->
<div class="modal fade" id="imageLightboxModal" tabindex="-1" aria-labelledby="imageLightboxModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imageLightboxModalLabel">სურათის ნახვა</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center p-0">
                <img src="" id="lightboxImage" class="img-fluid" alt="Full size image">
            </div>
        </div>
    </div>
</div>

<!-- JavaScript for handling product files display and lightbox functionality -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Function to load product files into modal
        window.showProductFiles = function(productId, files) {
            const filesContainer = document.querySelector('.files-container');
            filesContainer.innerHTML = ''; // Clear previous content
            
            if (!files || files.length === 0) {
                filesContainer.innerHTML = '<div class="col-12 text-center"><p>ფაილები არ მოიძებნა</p></div>';
                return;
            }
            
            // Process each file
            files.forEach(file => {
                let fileElement = '';
                const isImage = file.mime_type && file.mime_type.startsWith('image/');
                
                if (isImage) {
                    // For images - show thumbnail with lightbox option
                    fileElement = `
                        <div class="col-sm-6 col-md-4 mb-3">
                            <div class="card h-100">
                                <div class="card-body text-center">
                                    <div class="image-container position-relative" style="height: 150px; overflow: hidden;">
                                        <img src="${file.url}" class="img-thumbnail h-100 cursor-pointer" 
                                             style="object-fit: cover;" 
                                             data-bs-toggle="modal" 
                                             data-bs-target="#imageLightboxModal" 
                                             data-src="${file.url}" 
                                             onclick="openLightbox('${file.url}', '${file.name || 'სურათი'}')">
                                    </div>
                                    <p class="mt-2 mb-0 text-truncate">${file.name || 'სურათი'}</p>
                                </div>
                                <div class="card-footer bg-transparent border-top-0">
                                    <a href="${file.url}" class="btn btn-sm btn-primary w-100" target="_blank">გახსნა</a>
                                </div>
                            </div>
                        </div>
                    `;
                } else {
                    // For non-image files - show appropriate icon based on file type
                    let fileIcon = getFileIcon(file.mime_type, file.original_extension);
                    
                    fileElement = `
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
                
                filesContainer.innerHTML += fileElement;
            });
            
            // Show the modal
            const modal = new bootstrap.Modal(document.getElementById('productFilesModal'));
            modal.show();
        };
        
        // Function to determine appropriate icon for file type
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
        
        // Function to open image in lightbox
        window.openLightbox = function(imageUrl, title) {
            const lightboxImage = document.getElementById('lightboxImage');
            const lightboxTitle = document.getElementById('imageLightboxModalLabel');
            
            lightboxImage.src = imageUrl;
            lightboxTitle.textContent = title || 'სურათის ნახვა';
        };

        // Make sure we load the modals into the Bootstrap modal system
        const productFilesModal = document.getElementById('productFilesModal');
        const imageLightboxModal = document.getElementById('imageLightboxModal');
        
        if (typeof bootstrap !== 'undefined') {
            // Initialize modals using Bootstrap 5 native JavaScript
            if (productFilesModal) {
                new bootstrap.Modal(productFilesModal);
            }
            
            if (imageLightboxModal) {
                new bootstrap.Modal(imageLightboxModal);
            }
        } else if (typeof $ !== 'undefined') {
            // Initialize modals using jQuery
            try {
                $(productFilesModal).modal({show: false});
                $(imageLightboxModal).modal({show: false});
            } catch (e) {
                console.error('Error initializing modals with jQuery:', e);
            }
        }
    });
</script> 