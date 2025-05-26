<h4 class="title mt-4 mb-3">{{ get_phrase('Certification Enroll & Honor Pledge') }}</h4>
<form action="{{ route('admin.website.settings.update') }}" method="post" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="type" value="certification_data">

    @php
        $enroll = json_decode(get_frontend_settings('certificate_enroll'), true);
        $honor = json_decode(get_frontend_settings('certification_honor'), true);
    @endphp

    {{-- Enroll Section --}}
    <div class="border p-3 mb-4 rounded">
        <h5 class="mb-3">{{ get_phrase('Enroll in Certification') }}</h5>

        @foreach (['title', 'subtitle_1', 'subtitle_2', 'subtitle_3'] as $field)
            <div class="fpb-7 mb-3">
                <label class="form-label ol-form-label">{{ get_phrase(ucwords(str_replace('_', ' ', $field))) }}</label>
                <input type="text" class="form-control ol-form-control" name="enroll[{{ $field }}]"
                    value="{{ $enroll[$field] ?? '' }}">
            </div>
        @endforeach
    </div>

    {{-- Honor Pledge Section --}}
    <div class="border p-3 rounded">
        <h5 class="mb-3">{{ get_phrase('Honor Pledge') }}</h5>

        @foreach (['title', 'subtitle_1', 'subtitle_2'] as $field)
            <div class="fpb-7 mb-3">
                <label class="form-label ol-form-label">{{ get_phrase(ucwords(str_replace('_', ' ', $field))) }}</label>
                <input type="text" class="form-control ol-form-control" name="honor[{{ $field }}]"
                    value="{{ $honor[$field] ?? '' }}">
            </div>
        @endforeach

        <label class="form-label ol-form-label mt-3">{{ get_phrase('Honor Pledge Info Points') }}</label>
        <div id="infofaq_area">
            @php $infos = $honor['info'] ?? []; @endphp

            @forelse ($infos as $key => $info)
                <div class="d-flex mt-2">
                    <div class="flex-grow-1 pe-3">
                        <input type="text" class="form-control ol-form-control" name="honor[info][]"
                            value="{{ $info }}">
                    </div>
                    <div>
                        @if ($key == 0)
                            <button type="button" class="btn ol-btn-light ol-icon-btn" onclick="appendinfofaq()"
                                data-bs-toggle="tooltip" title="{{ get_phrase('Add new') }}">
                                <i class="fi-rr-plus-small"></i>
                            </button>
                        @else
                            <button type="button" class="btn ol-btn-light ol-icon-btn" onclick="removeinfofaq(this)"
                                data-bs-toggle="tooltip" title="{{ get_phrase('Remove') }}">
                                <i class="fi-rr-minus-small"></i>
                            </button>
                        @endif
                    </div>
                </div>
            @empty
                <div class="d-flex mt-2">
                    <div class="flex-grow-1 pe-3">
                        <input type="text" class="form-control ol-form-control" name="honor[info][]"
                            placeholder="{{ get_phrase('Add Info') }}">
                    </div>
                    <div>
                        <button type="button" class="btn ol-btn-light ol-icon-btn" onclick="appendinfofaq()"
                            data-bs-toggle="tooltip" title="{{ get_phrase('Add new') }}">
                            <i class="fi-rr-plus-small"></i>
                        </button>
                    </div>
                </div>
            @endforelse

            {{-- Hidden template --}}
            <template id="blank_infofaq_template">
                <div class="d-flex mt-2">
                    <div class="flex-grow-1 pe-3">
                        <input type="text" class="form-control ol-form-control" name="honor[info][]"
                            placeholder="{{ get_phrase('Add Info') }}">
                    </div>
                    <div>
                        <button type="button" class="btn ol-btn-light ol-icon-btn" onclick="removeinfofaq(this)"
                            data-bs-toggle="tooltip" title="{{ get_phrase('Remove') }}">
                            <i class="fi-rr-minus-small"></i>
                        </button>
                    </div>
                </div>
            </template>

        </div>
    </div>

    <div class="fpb-7 mt-4">
        <button type="submit" class="btn ol-btn-primary">{{ get_phrase('Save changes') }}</button>
    </div>
</form>

<script>
    function appendinfofaq() {
        const template = document.getElementById('blank_infofaq_template');
        const clone = template.content.cloneNode(true);
        document.getElementById('infofaq_area').appendChild(clone);
    }


    function removeinfofaq(el) {
        el.closest('.d-flex').remove();
    }
</script>
