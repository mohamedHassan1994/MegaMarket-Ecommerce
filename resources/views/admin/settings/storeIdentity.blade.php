@extends('admin.app')

@section('title', 'Store Identity')
@section('admin_page_title', 'Store Identity')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-body">

                {{-- Validation Errors --}}
                @if ($errors->any())
                    <div class="alert alert-warning alert-dismissible fade show">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                {{-- Success Message --}}
                @if (session('message'))
                    <div class="alert alert-success alert-dismissible fade show">
                        {{ session('message') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form action="{{ route('identity.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    {{-- Social Links Section --}}
                    <div class="card shadow-sm mt-4">
                        <div class="card-body">
                            <h5 class="fw-bold mb-3">Social links</h5>

                            <div class="social-links">
                                <div class="social-item">
                                    <i class="bi bi-facebook"></i>
                                    <input type="text" name="facebook" class="form-control" placeholder="https://facebook.com/" value="{{ old('facebook', $store->facebook ?? '') }}">
                                </div>
                                <div class="social-item">
                                    <i class="bi bi-instagram"></i>
                                    <input type="text" name="instagram" class="form-control" placeholder="https://instagram.com/" value="{{ old('instagram', $store->instagram ?? '') }}">
                                </div>
                                <div class="social-item">
                                    <i class="bi bi-linkedin"></i>
                                    <input type="text" name="linkedin" class="form-control" placeholder="https://linkedin.com/" value="{{ old('linkedin', $store->linkedin ?? '') }}">
                                </div>
                                <div class="social-item">
                                    <i class="bi bi-youtube"></i>
                                    <input type="text" name="youtube" class="form-control" placeholder="https://youtube.com/" value="{{ old('youtube', $store->youtube ?? '') }}">
                                </div>
                                <div class="social-item">
                                    <i class="bi bi-twitter-x"></i>
                                    <input type="text" name="twitter" class="form-control" placeholder="https://twitter.com/" value="{{ old('twitter', $store->twitter ?? '') }}">
                                </div>
                                <div class="social-item">
                                    <i class="bi bi-tiktok"></i>
                                    <input type="text" name="tiktok" class="form-control" placeholder="https://tiktok.com/" value="{{ old('tiktok', $store->tiktok ?? '') }}">
                                </div>
                                <div class="social-item">
                                    <i class="bi bi-pinterest"></i>
                                    <input type="text" name="pinterest" class="form-control" placeholder="https://pinterest.com/" value="{{ old('pinterest', $store->pinterest ?? '') }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Miscellaneous --}} 
                    <div class="card shadow-sm mt-4">
                        <div class="card-body">
                            <h5 class="fw-bold mb-3">Miscellaneous</h5>
                        </div>
                    </div>

                    {{-- Media --}}
                    <div class="card shadow-sm mt-4">
                        <div class="card-body">
                            <h5 class="fw-bold mb-3">Media</h5>

                            <div class="media-grid">
                                {{-- Website Logo --}}
                                <div class="media-item">
                                    <div class="media-preview-wrapper">
                                        <div class="media-preview" data-input="website_logo">
                                            <img id="preview-website_logo" 
                                                 src="{{ isset($store->website_logo) ? asset('storage/'.$store->website_logo) : asset('images/placeholder.png') }}" 
                                                 alt="Website Logo">
                                            <div class="upload-overlay">
                                                <i class="bi bi-cloud-upload"></i>
                                                <p>Click or drag image here</p>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="media-label">Website Logo</p>
                                    <input type="file" id="website_logo" name="website_logo" class="form-control media-input" 
                                           accept="image/*">
                                    <button type="button" class="btn btn-sm btn-outline-primary mt-2 w-100 upload-btn" 
                                            onclick="document.getElementById('website_logo').click()">
                                        <i class="bi bi-upload me-1"></i> Choose File
                                    </button>
                                </div>

                                {{-- Favicon --}}
                                <div class="media-item">
                                    <div class="media-preview-wrapper">
                                        <div class="media-preview" data-input="favicon">
                                            <img id="preview-favicon" 
                                                 src="{{ isset($store->favicon) ? asset('storage/'.$store->favicon) : asset('images/placeholder.png') }}" 
                                                 alt="Favicon">
                                            <div class="upload-overlay">
                                                <i class="bi bi-cloud-upload"></i>
                                                <p>Click or drag image here</p>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="media-label">Favicon</p>
                                    <input type="file" id="favicon" name="favicon" class="form-control media-input" 
                                           accept="image/*">
                                    <button type="button" class="btn btn-sm btn-outline-primary mt-2 w-100 upload-btn" 
                                            onclick="document.getElementById('favicon').click()">
                                        <i class="bi bi-upload me-1"></i> Choose File
                                    </button>
                                </div>

                                {{-- Footer Logo --}}
                                <div class="media-item">
                                    <div class="media-preview-wrapper">
                                        <div class="media-preview" data-input="footer_logo">
                                            <img id="preview-footer_logo" 
                                                 src="{{ isset($store->footer_logo) ? asset('storage/'.$store->footer_logo) : asset('images/placeholder.png') }}" 
                                                 alt="Footer Logo">
                                            <div class="upload-overlay">
                                                <i class="bi bi-cloud-upload"></i>
                                                <p>Click or drag image here</p>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="media-label">Footer Logo</p>
                                    <input type="file" id="footer_logo" name="footer_logo" class="form-control media-input" 
                                           accept="image/*">
                                    <button type="button" class="btn btn-sm btn-outline-primary mt-2 w-100 upload-btn" 
                                            onclick="document.getElementById('footer_logo').click()">
                                        <i class="bi bi-upload me-1"></i> Choose File
                                    </button>
                                </div>

                                {{-- Home Banner --}}
                                <div class="media-item">
                                    <div class="media-preview-wrapper">
                                        <div class="media-preview" data-input="home_banners" data-multiple="true">
                                            @if(isset($store->home_banners) && count($store->home_banners) > 0)
                                                <img id="preview-home_banners" 
                                                     src="{{ asset('storage/'.$store->home_banners[0]->image_path) }}" 
                                                     alt="Home Banner">
                                            @else
                                                <img id="preview-home_banners" 
                                                     src="{{ asset('images/placeholder.png') }}" 
                                                     alt="Home Banner">
                                            @endif
                                            <div class="upload-overlay">
                                                <i class="bi bi-cloud-upload"></i>
                                                <p>Click or drag images here</p>
                                                @if(isset($store->home_banners) && count($store->home_banners) > 0)
                                                    <small class="text-muted">{{ count($store->home_banners) }} banner(s)</small>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <p class="media-label">Home Banner (Multiple)</p>
                                    <input type="file" id="home_banners" name="home_banners[]" class="form-control media-input" 
                                           accept="image/*" multiple>
                                    
                                    {{-- Hidden inputs for deletion --}}
                                    @if(isset($store->home_banners))
                                        @foreach($store->home_banners as $banner)
                                            <input type="hidden" name="delete_banners[]" class="delete-banner-input-{{ $banner->id }}">
                                        @endforeach
                                    @endif
                                    
                                    <button type="button" class="btn btn-sm btn-outline-primary mt-2 w-100 upload-btn" 
                                            onclick="document.getElementById('home_banners').click()">
                                        <i class="bi bi-upload me-1"></i> Choose Files
                                    </button>
                                    
                                    {{-- Existing banners thumbnails --}}
                                    @if(isset($store->home_banners) && count($store->home_banners) > 0)
                                        <div class="existing-banners mt-2">
                                            <small class="text-muted d-block mb-1">Current Banners:</small>
                                            <div class="d-flex flex-wrap gap-1">
                                                @foreach($store->home_banners as $banner)
                                                    <div class="position-relative banner-thumb" data-banner-id="{{ $banner->id }}">
                                                        <img src="{{ asset('storage/'.$banner->image_path) }}" 
                                                             class="img-thumbnail" 
                                                             style="width: 40px; height: 40px; object-fit: cover;">
                                                        <button type="button" 
                                                                class="btn btn-danger btn-sm position-absolute top-0 end-0 delete-banner-btn"
                                                                data-banner-id="{{ $banner->id }}"
                                                                style="padding: 0 4px; font-size: 10px; line-height: 1;">
                                                            ×
                                                        </button>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Submit Button --}}
                    <div class="mt-4 text-start">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-save me-2"></i>Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

{{-- Enhanced Upload Script --}}
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle single image uploads (website_logo, favicon, footer_logo)
    const mediaPreviewBoxes = document.querySelectorAll('.media-preview:not([data-multiple="true"])');
    
    mediaPreviewBoxes.forEach(box => {
        const inputId = box.getAttribute('data-input');
        const fileInput = document.getElementById(inputId);
        const preview = document.getElementById('preview-' + inputId);
        const overlay = box.querySelector('.upload-overlay');
        
        box.addEventListener('click', function(e) {
            e.stopPropagation();
            fileInput.click();
        });
        
        fileInput.addEventListener('change', function(e) {
            handleSingleFile(this.files, preview, overlay, inputId);
        });
        
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            box.addEventListener(eventName, preventDefaults, false);
            document.body.addEventListener(eventName, preventDefaults, false);
        });
        
        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }
        
        ['dragenter', 'dragover'].forEach(eventName => {
            box.addEventListener(eventName, function() {
                box.classList.add('drag-active');
            }, false);
        });
        
        ['dragleave', 'drop'].forEach(eventName => {
            box.addEventListener(eventName, function() {
                box.classList.remove('drag-active');
            }, false);
        });
        
        box.addEventListener('drop', function(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            
            if (files.length > 0) {
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(files[0]);
                fileInput.files = dataTransfer.files;
                handleSingleFile(files, preview, overlay, inputId);
            }
        });
    });
    
    function handleSingleFile(files, preview, overlay, inputId) {
        const file = files[0];
        
        if (file && file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                overlay.style.opacity = '0';
                
                const uploadBtn = document.querySelector(`[onclick*="${inputId}"]`);
                if (uploadBtn) {
                    uploadBtn.innerHTML = `<i class="bi bi-check-circle me-1"></i> ${file.name}`;
                    uploadBtn.classList.remove('btn-outline-primary');
                    uploadBtn.classList.add('btn-success');
                }
            };
            reader.readAsDataURL(file);
        } else if (file) {
            alert('Please select a valid image file.');
            preview.src = "{{ asset('images/placeholder.png') }}";
            overlay.style.opacity = '1';
        }
    }

    // Handle multiple banner uploads
    const bannersBox = document.querySelector('.media-preview[data-multiple="true"]');
    const bannersInput = document.getElementById('home_banners');
    const bannersPreview = document.getElementById('preview-home_banners');
    const bannersOverlay = bannersBox.querySelector('.upload-overlay');
    
    bannersBox.addEventListener('click', function(e) {
        e.stopPropagation();
        bannersInput.click();
    });
    
    bannersInput.addEventListener('change', function(e) {
        handleMultipleFiles(this.files);
    });
    
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        bannersBox.addEventListener(eventName, preventDefaults, false);
    });
    
    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }
    
    ['dragenter', 'dragover'].forEach(eventName => {
        bannersBox.addEventListener(eventName, function() {
            bannersBox.classList.add('drag-active');
        }, false);
    });
    
    ['dragleave', 'drop'].forEach(eventName => {
        bannersBox.addEventListener(eventName, function() {
            bannersBox.classList.remove('drag-active');
        }, false);
    });
    
    bannersBox.addEventListener('drop', function(e) {
        const dt = e.dataTransfer;
        const files = dt.files;
        
        if (files.length > 0) {
            bannersInput.files = files;
            handleMultipleFiles(files);
        }
    });
    
    function handleMultipleFiles(files) {
        const fileCount = files.length;
        
        if (fileCount > 0 && files[0].type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function(e) {
                bannersPreview.src = e.target.result;
                bannersOverlay.style.opacity = '0';
                
                const uploadBtn = document.querySelector('[onclick*="home_banners"]');
                if (uploadBtn) {
                    uploadBtn.innerHTML = `<i class="bi bi-check-circle me-1"></i> ${fileCount} file(s) selected`;
                    uploadBtn.classList.remove('btn-outline-primary');
                    uploadBtn.classList.add('btn-success');
                }
            };
            reader.readAsDataURL(files[0]);
        }
    }

    // Handle delete banner buttons
    document.querySelectorAll('.delete-banner-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            const bannerId = this.getAttribute('data-banner-id');
            
            if (confirm('Delete this banner?')) {
                const deleteInput = document.querySelector(`.delete-banner-input-${bannerId}`);
                if (deleteInput) {
                    deleteInput.value = bannerId;
                }
                
                this.closest('.banner-thumb').style.display = 'none';
            }
        });
    });
});
</script>
@endpush