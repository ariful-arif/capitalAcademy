@php
    $forms = [
        [
            'key' => 'instructor_graduated_form',
            'label' => 'Instructor Graduated Form',
            'setting' => json_decode(get_frontend_settings('instructor_graduated_form'), true)
        ],
        [
            'key' => 'certified_professionals',
            'label' => 'Certified Professionals Form',
            'setting' => json_decode(get_frontend_settings('certified_professionals'), true)
        ],
        [
            'key' => 'work_experience',
            'label' => 'Work Experience Form',
            'setting' => json_decode(get_frontend_settings('work_experience'), true)
        ]
    ];
@endphp
<style>
    .form-section-card {
        border: 1px solid #dee2e6;
        border-radius: 12px;
        padding: 30px;
        background-color: #fff;
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        margin-bottom: 40px;
    }

    .thumbnail-upload-area {
        border: 2px dashed #dee2e6;
        padding: 20px;
        border-radius: 10px;
        background-color: #f8f9fa;
    }

    .thumb-item img {
        border-radius: 6px;
    }

    .form-section-title {
        font-size: 1.25rem;
        font-weight: 600;
        margin-bottom: 20px;
        color: #343a40;
    }
</style>

@foreach ($forms as $index => $form)
    <div class="card mb-5 mt-3 shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">{{ get_phrase($form['label']) }}</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.website.settings.update') }}" method="post" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="type" value="{{ $form['key'] }}">

                <!-- Title Input -->
                <div class="mb-3">
                    <label class="form-label" for="title_{{ $index }}">
                        {{ get_phrase('Title') }} <span class="text-danger">*</span>
                    </label>
                    <input type="text" name="title" id="title_{{ $index }}" class="form-control"
                        value="{{ $form['setting']['title'] ?? '' }}" required>
                </div>

                <!-- Thumbnail Input -->
                <div class="mb-3">
                    <label class="form-label" for="thumbnail_{{ $index }}">
                        {{ get_phrase('Thumbnail') }} <span class="text-danger">*</span>
                    </label>

                    <!-- Preview Images -->
                    <div id="thumbnail-preview-{{ $index }}" class="d-flex flex-wrap gap-3 mb-3">
                        @if (!empty($form['setting']['thumbnail']) && is_array($form['setting']['thumbnail']))
                            @foreach ($form['setting']['thumbnail'] as $thumb)
                                <div class="position-relative thumb-item" data-thumb-path="{{ $thumb }}">
                                    <img src="{{ asset($thumb) }}" alt="Thumbnail" class="img-fluid border"
                                        style="width: 200px; height: 150px; object-fit: contain; background-color: gainsboro;">
                                    <button type="button"
                                        class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1 remove-thumb-btn">
                                        &times;
                                    </button>
                                </div>
                            @endforeach
                        @endif
                    </div>

                    <input type="hidden" name="removed_thumbnails" id="removed_thumbnails_{{ $index }}" value="[]">

                    <input type="file" name="thumbnail[]" id="thumbnail_{{ $index }}"
                        class="form-control" accept="image/*" multiple>
                </div>

                <!-- Submit -->
                <div class="text-end">
                    <button type="submit" class="btn btn-primary">{{ get_phrase('Update Settings') }}</button>
                </div>
            </form>
        </div>
    </div>

    <!-- JS per form -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const removedThumbnails = [];
            const previewContainer = document.getElementById('thumbnail-preview-{{ $index }}');
            const removedInput = document.getElementById('removed_thumbnails_{{ $index }}');
            const fileInput = document.getElementById('thumbnail_{{ $index }}');

            previewContainer.addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-thumb-btn')) {
                    const item = e.target.closest('.thumb-item');
                    const path = item.getAttribute('data-thumb-path');
                    if (path) {
                        removedThumbnails.push(path);
                        removedInput.value = JSON.stringify(removedThumbnails);
                    }
                    item.remove();
                }
            });

            fileInput.addEventListener('change', function() {
                const files = Array.from(fileInput.files);

                files.forEach(file => {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.className = 'img-fluid border';
                        img.style = 'width: 200px; height: 150px; object-fit: cover;';

                        const wrapper = document.createElement('div');
                        wrapper.className = 'position-relative thumb-item';
                        wrapper.appendChild(img);

                        const btn = document.createElement('button');
                        btn.type = 'button';
                        btn.className = 'btn btn-sm btn-danger position-absolute top-0 end-0 m-1 remove-thumb-btn';
                        btn.textContent = '×';

                        wrapper.appendChild(btn);
                        previewContainer.appendChild(wrapper);
                    };
                    reader.readAsDataURL(file);
                });
            });
        });
    </script>
@endforeach
