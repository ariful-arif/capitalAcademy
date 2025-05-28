<div class="certificate_program_faq_form">
    @if (isset($id))
        @include('admin.certificate_program.faq.edit')
    @elseif(isset($certificate_program_id))
        @include('admin.certificate_program.faq.add')
    @endif
</div>

<h5 class="mb-3 mt-4">{{ get_phrase('FAQ List') }}</h5>
<div class="accordion" id="accordionExample1">
    @foreach (App\Models\CertificateProgramFaq::where('certificate_program_id', $certificate_program_id ?? '')->orderBy('id', 'desc')->get() as $key => $faq)
        @php
            if (isset($id)) {
                $opened_id = $id;
            } elseif ($key == 0) {
                $opened_id = $faq->id;
            }
        @endphp

        <div class="accordion-item">
            <h2 class="accordion-header">
                <button class="accordion-button @if ($opened_id != $faq->id) collapsed @endif" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $faq->id }}" aria-expanded="@if ($opened_id != $faq->id) false @else true @endif" aria-controls="collapse{{ $faq->id }}">
                    {{ $faq->title }}
                </button>
            </h2>
            <div id="collapse{{ $faq->id }}" class="accordion-collapse collapse @if ($opened_id == $faq->id) show @endif" data-bs-parent="#accordionExample1">
                <div class="accordion-body">
                    {{ $faq->description }}

                    <hr>
                    <div class="d-flex justify-content-end">
                        <a href="#" onclick="loadView('{{ route('view', ['path' => 'admin.certificate_program.faq.edit', 'id' => $faq->id]) }}', '.certificate_program_faq_form')" class="btn btn-sm btn-primary me-2">{{ get_phrase('Edit') }}</a>
                        <a href="#" onclick="confirmModal('{{ route('admin.certificate_faq.delete', ['id' => $faq->id]) }}', true)" class="btn btn-sm btn-primary me-2">{{ get_phrase('Delete') }}</a>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
