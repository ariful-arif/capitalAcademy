<style>
    .question {
        min-height: auto !important;
    }

    .accordion-button::after {
        background-size: 14px;
    }

    .accordion-button:focus {
        box-shadow: none
    }

    .accordion-button:not(.collapsed) {
        background: #f4f7fe;
    }
</style>
<div class="accordion" id="accordionExample">
    @foreach ($results as $key => $result)
        <div class="accordion-item">
            <h2 class="accordion-header">
                <button class="accordion-button collapsed fs-14px fw-500" type="button" data-bs-toggle="collapse"
                    data-bs-target="#collapse-{{ $result->id }}" aria-expanded="false"
                    aria-controls="collapse-{{ $result->id }}">
                    {{ get_phrase('Attempt ') }}{{ ++$key }}
                    <span class="ms-5">{{ date('d M, Y H:i', strtotime($result->created_at)) }}</span>
                </button>
            </h2>
            <div id="collapse-{{ $result->id }}" class="accordion-collapse collapse"
                data-bs-parent="#accordionExample">
                <div class="accordion-body">
                    <div class="result">
                        @php
                            $submits = $result->submits ? json_decode($result->submits, true) : [];
                            $correct_answers = $result->correct_answer
                                ? json_decode($result->correct_answer, true)
                                : [];
                            $wrong_answers = $result->wrong_answer ? json_decode($result->wrong_answer, true) : [];
                            $mark_per_question = $quiz->total_mark / $questions->count();
                        @endphp

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <p>{{ get_phrase('Duration : ') }}
                                    @php $duration = explode(':', $quiz->duration); @endphp
                                    {{ $duration[0] }} {{ get_phrase('Hour') }}
                                    {{ $duration[1] }} {{ get_phrase('Minute') }}
                                    {{ $duration[1] }} {{ get_phrase('Second') }}
                                </p>
                                <p>{{ get_phrase('Total Mark : ') }}{{ $quiz->total_mark }}</p>
                                <p>{{ get_phrase('Pass Mark : ') }}{{ $quiz->pass_mark }}</p>
                            </div>
                            <div class="col-md-6">
                                <p>{{ get_phrase('Correct Answer : ') }}{{ count($correct_answers) }}</p>
                                <p>{{ get_phrase('Wrong Answer : ') }}{{ count($wrong_answers) }}</p>
                                <p>{{ get_phrase('Obtained marks') }} : {{ count($correct_answers) * $mark_per_question }}</p>
                                <p>{{ get_phrase('Result : ') }}
                                    @if (count($correct_answers)*$mark_per_question >= $quiz->pass_mark)
                                        <span class="text-success">{{ get_phrase('Passed') }}</span>
                                    @else
                                        <span class="text-danger">{{ get_phrase('Failed') }}</span>
                                    @endif
                                </p>
                            </div>
                        </div>

                        @foreach ($questions as $key => $question)
                            @php
                                $given_answer =
                                    $question->type == 'true_false'
                                        ? $question->answer
                                        : implode(', ', json_decode($question->answer, true));
                                $user_answers = array_key_exists($question->id, $submits)
                                    ? $submits[$question->id]
                                    : [];
                            @endphp

                            <div class="result-question mb-4 @if ($key > 0)  @endif">
                                <div class="mb-1 d-flex align-items-center gap-3">
                                    <span class="serial">{{ ++$key }}</span>
                                    <div>{!! $question->title !!}</div>
                                    @if (in_array($question->id, $correct_answers))
                                        <i class="fi fi-br-check text-success"></i>
                                    @elseif(in_array($question->id, $wrong_answers))
                                        <i class="fi fi-br-cross-small text-danger"></i>
                                    @endif
                                </div>

                                <div class="row {{ $question->type == 'fill_blanks' ? 'px-2' : '' }}">
                                    @if ($question->type == 'mcq')
                                        @php $options = json_decode($question->options, true) ?? []; @endphp
                                        @foreach ($options as $index => $option)
                                            @php $val = $user_answers ? array_search($option, $user_answers) : ''; @endphp
                                            <div class="col-sm-6">
                                                <input class="form-check-input" type="checkbox"
                                                    value="{{ $option }}"
                                                    @if (is_numeric($val)) checked @endif disabled>
                                                <label
                                                    class="form-check-label text-capitalize">{{ $option }}</label>
                                            </div>
                                        @endforeach
                                    @elseif($question->type == 'fill_blanks')
                                        <input type="text" class="form-control tagify" data-role="tagsinput"
                                            value="{{ json_encode($user_answers) }}" disabled>
                                    @elseif($question->type == 'true_false')
                                        <div class="col-sm-2">
                                            <input class="form-check-input" type="radio" disabled
                                                @if ($user_answers == 'true') checked @endif>
                                            <label class="form-check-label">{{ get_phrase('True') }}</label>
                                        </div>
                                        <div class="col-sm-2">
                                            <input class="form-check-input" type="radio" disabled
                                                @if ($user_answers == 'false') checked @endif>
                                            <label class="form-check-label">{{ get_phrase('False') }}</label>
                                        </div>
                                    @endif
                                    <p class="text-capitalize text-success fw-600">
                                        {{ get_phrase('Answer : ') }}{{ $given_answer }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>

<script>
    $('.result .tagify:not(.inited)').each(function(index, element) {
        var tagify = new Tagify(element, {
            placeholder: '{{ get_phrase('Enter your keywords') }}'
        });
        $(element).addClass('inited');
    });
</script>
