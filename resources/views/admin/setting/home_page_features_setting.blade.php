<h4 class="title mt-4 mb-3">{{ get_phrase('Home page features') }}</h4>
<form action="{{ route('admin.website.settings.update') }}" method="post" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="type" value="features">
    <div class="row">
        <div class="col-md-8">
            <div id = "features_area">
                @php
                    $featureses = count(json_decode(get_frontend_settings('features'), true)) > 0 ? json_decode(get_frontend_settings('features'), true) : [['title' => '', 'description' => '', 'image' => '']];
                @endphp
                @foreach ($featureses as $key => $features)
                    <div class="d-flex mt-2">
                        <div class="flex-grow-1 px-2 mb-3">
                            <div class="fpb-7 mb-3">
                                <label class="form-label ol-form-label">{{ get_phrase('Title') }}</label>
                                <input type="text" class="form-control ol-form-control" name="titles[]" placeholder="{{ get_phrase('Title') }}" value="{{ $features['title'] }}">
                            </div>
                            <div class="fpb-7 mb-3">
                                <label class="form-label ol-form-label">{{ get_phrase('Description') }}</label>
                                <textarea name="descriptions[]" class="form-control ol-form-control" placeholder="{{ get_phrase('Description') }}">{{ $features['description'] }}</textarea>
                            </div>

                            <div class="fpb-7 mb-3">
                                <label class="form-label ol-form-label">{{ get_phrase('Logo') }}</label>
                                @if (!empty($features['logo']))
                                <img src="{{ asset($features['logo']) }}" alt="logo"
                                    class="img-fluid mb-3"
                                    style="width: 50px; height: 50px; border: 1px solid black; color: black;">
                            @endif
                                <div class="custom-file">
                                    <input name="previous_images[]" type="hidden" value="{{ $features['logo'] }}">
                                    <input type="file" class="form-control ol-form-control" name="images[]" onchange="" accept="image/*">
                                </div>
                            </div>
                        </div>

                        @if ($key == 0)
                            <div class="pt-4">
                                <button type="button" class="btn ol-btn-light ol-icon-btn mt-2" name="button" data-bs-toggle="tooltip" title="{{ get_phrase('Add new') }}" onclick="appendfeatures()"> <i class="fi-rr-plus-small"></i>
                                </button>
                            </div>
                        @else
                            <div class="pt-4">
                                <button type="button" class="btn ol-btn-light ol-icon-btn mt-2" name="button" data-bs-toggle="tooltip" title="{{ get_phrase('Remove') }}" onclick="removefeatures(this)">
                                    <i class="fi-rr-minus-small"></i> </button>
                            </div>
                        @endif
                    </div>
                @endforeach

                <div id = "blank_features_field">
                    <div class="d-flex mt-2 border-top pt-2">
                        <div class="flex-grow-1 px-2 mb-3">
                            <div class="fpb-7 mb-3">
                                <label class="form-label ol-form-label">{{ get_phrase('Title') }}</label>
                                <input type="text" class="form-control ol-form-control" name="titles[]" placeholder="{{ get_phrase('Title') }}">
                            </div>
                            <div class="fpb-7 mb-3">
                                <label class="form-label ol-form-label">{{ get_phrase('Description') }}</label>
                                <textarea name="descriptions[]" class="form-control ol-form-control" placeholder="{{ get_phrase('Description') }}"></textarea>
                            </div>

                            <div class="fpb-7 mb-3">
                                <label class="form-label ol-form-label">{{ get_phrase('Image') }}</label>
                                <div class="custom-file">
                                    <input name="previous_images[]" type="hidden" value="">
                                    <input type="file" class="form-control ol-form-control" name="images[]" onchange="" accept="image/*">
                                </div>
                            </div>
                        </div>
                        <div class="pt-4">
                            <button type="button" class="btn ol-btn-light ol-icon-btn mt-2" name="button" data-bs-toggle="tooltip" title="{{ get_phrase('Remove') }}" onclick="removefeatures(this)">
                                <i class="fi-rr-minus-small"></i> </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="fpb-7 mb-2 flex-grow-1 px-2">
                <button type="submit" class="btn ol-btn-primary">{{ get_phrase('Save changes') }}</button>
            </div>
        </div>
    </div>
</form>
