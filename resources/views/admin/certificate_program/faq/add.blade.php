<form class="ajaxForm" action="{{route('admin.certificate_faq.store', $certificate_program_id)}}" method="post">
    @csrf

    <div class="mb-3">
        <label class="form-label ol-form-label" for="question">{{ get_phrase('Question') }}</label>
        <input class="form-control ol-form-control" type="text" id="question" name="question" required>
    </div>

    <div class="mb-3">
        <label class="form-label ol-form-label" for="answer">{{ get_phrase('Answer') }}</label>
        <textarea class="form-control ol-form-control" id="answer" name="answer" rows="4" required></textarea>
    </div>
    <div class="mb-3">
        <button type="submit" class="btn ol-btn-primary">{{ get_phrase('Add FAQ') }}</button>
    </div>
</form>


@include('admin.init')