<a href="#" data-bs-toggle="tooltip" title="{{ get_phrase('Mcq Quiz') }}"
    onclick="ajaxModal('{{ route('modal', ['admin.course.flash_cards.generate_mcq_quiz', 'id' => $id]) }}', '{{ get_phrase('Mcq Quiz') }}', 'modal-xl')"
    class="btn btn-outline-gray-small">{{ get_phrase('Mcq Quiz') }}
</a>

<a href="#" data-bs-toggle="tooltip" title="{{ get_phrase('Free response quiz') }}"
    onclick="ajaxModal('{{ route('modal', ['admin.course.flash_cards.generate_free_response_quiz', 'id' => $id]) }}', '{{ get_phrase('Free response quiz') }}', 'modal-xl')"
    class="btn btn-outline-gray-small">{{ get_phrase('Free response quiz') }}
</a>
