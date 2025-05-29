@php
    $lesson = App\Models\Lesson::find($id);
    $free_responses = $lesson->free_response_question ?? [];

@endphp
<style>
    .toast-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
    }

    .toast.text-bg-success .toast-icon {
        color: #28a745;
    }

    .toast.text-bg-danger .toast-icon {
        color: #dc3545;
    }

    .custom-toast {
        background-color: #28a745;
        border-radius: 0.75rem;
        min-width: 320px;
        max-width: 400px;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.2);
    }

    .toast-icon i {
        color: #fff;
    }
</style>

<div class="container mt-4">
    <h2 class="mb-4">free_responses for Lesson: {{ $lesson->title }}</h2>
    <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 9999;">
        <div id="liveToast" class="custom-toast toast align-items-start text-white" role="alert" aria-live="assertive"
            aria-atomic="true">
            <div class="d-flex w-100">
                <div class="toast-icon p-3">
                    <i class="bi bi-patch-check-fill fs-3"></i>
                </div>
                <div class="toast-body pe-2">
                    <div class="d-flex justify-content-between align-items-start mb-1">
                        <strong id="toastTitle">Success !</strong>
                        <small class="text-white-50 ms-auto">Just Now</small>
                        <button type="button" class="btn-close btn-close-white ms-3" data-bs-dismiss="toast"
                            aria-label="Close"></button>
                    </div>
                    <div id="toastMessage">Your action was successful!</div>
                </div>
            </div>
        </div>
    </div>


    <div class="card mb-4">
        <div class="card-body">
            <p><strong>PDF:</strong>
                @if ($lesson->attachment)
                    <a href="{{ asset('uploads/lesson_file/attachment/' . $lesson->attachment) }}" target="_blank">
                        {{ $lesson->attachment }}
                    </a>
                @else
                    <span class="text-danger">No PDF is available</span>
                @endif
            </p>

            @if ($lesson->attachment)
                <form id="generatefree_responsesForm">
                    @csrf
                    <input type="hidden" name="lesson_id" id="lesson_id" value="{{ $lesson->id }}">
                    <button type="submit" class="btn btn-primary mt-3">Generate free_responses from PDF</button>
                </form>
            @else
                <button type="button" class="btn btn-secondary mt-3" disabled
                    title="No PDF available to generate free_responses">
                    Generate free_responses
                </button>
            @endif
        </div>
    </div>

    <form id="free_responseForm">
        <input type="hidden" id="lesson_id" name="lesson_id" value="{{ $lesson->id }}">
        <div id="free_responseContainer" class="row g-3 mb-4">
            @if (is_array($lesson->free_response_question) && count($lesson->free_response_question))
                @foreach ($lesson->free_response_question as $index => $card)
                    <div class="col-md-6 free_response-item">
                        <div class="card h-100 shadow-sm p-3">
                            <h5>free_response {{ $index + 1 }}</h5>
                            <div class="mb-2">
                                <label>Question</label>
                                <input type="text" name="questions[]" class="form-control"
                                    value="{{ $card['question'] }}">
                            </div>
                            <div class="mb-2">
                                <label>Answer</label>
                                <textarea name="answers[]" class="form-control" rows="3">{{ $card['answer'] }}</textarea>
                            </div>
                            <button type="button" class="btn btn-danger btn-sm remove-free_response">Remove</button>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>

        <div class="d-flex gap-3 mb-4">
            <button type="button" class="btn btn-secondary" id="addfree_responseBtn">Add free_response</button>
            <button type="submit" class="btn btn-success">Save All free_responses</button>
        </div>
    </form>
</div>

<script>
    function showToast(message, isSuccess = true) {
        const toastEl = document.getElementById('liveToast');
        const toastMsg = document.getElementById('toastMessage');
        const toastTitle = document.getElementById('toastTitle');
        const toastIcon = document.querySelector('.toast-icon i');

        toastMsg.textContent = message;
        toastTitle.textContent = isSuccess ? 'Success !' : 'Error !';
        toastEl.classList.remove('bg-danger', 'bg-success');

        toastEl.style.backgroundColor = isSuccess ? '#28a745' : '#dc3545';
        toastIcon.className = isSuccess ? 'bi bi-patch-check-fill fs-3' : 'bi bi-x-octagon-fill fs-3';

        const toast = new bootstrap.Toast(toastEl);
        toast.show();
    }

    // GENERATE free_responses FROM PDF
    document.getElementById('generatefree_responsesForm').addEventListener('submit', function (e) {
        e.preventDefault();
        const lessonId = document.getElementById('lesson_id').value;
        const container = document.getElementById('free_responseContainer');
        container.innerHTML = '<div class="text-muted">Generating free_responses...</div>';

        fetch("{{ route('admin.ai.free_responses') }}", {
            method: "POST",
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                lesson_id: lessonId
            })
        })
        .then(res => res.json())
        .then(data => {
            if (!data.free_responses || data.free_responses.length === 0) {
                container.innerHTML = '<div class="text-danger">No free_responses found.</div>';
                return;
            }

            container.innerHTML = '';
            data.free_responses.forEach((card, index) => {
                const cardDiv = document.createElement('div');
                cardDiv.classList.add('col-md-6', 'free_response-item');
                cardDiv.innerHTML = `
                    <div class="card h-100 shadow-sm p-3">
                        <h5>free_response ${index + 1}</h5>
                        <div class="mb-2">
                            <label>Question</label>
                            <input type="text" name="questions[]" class="form-control" value="${card.question}">
                        </div>
                        <div class="mb-2">
                            <label>Answer</label>
                            <textarea name="answers[]" class="form-control" rows="3">${card.answer}</textarea>
                        </div>
                        <button type="button" class="btn btn-danger btn-sm remove-free_response">Remove</button>
                    </div>
                `;
                container.appendChild(cardDiv);
            });
            showToast('Free response questions generated successfully!', true);
        })
        .catch(err => {
            container.innerHTML = '<div class="text-danger">Error generating free_responses.</div>';
            console.error(err);
            showToast('Failed to generate free_responses.', false);
        });
    });

    // ADD free_response
    document.getElementById('addfree_responseBtn').addEventListener('click', function () {
        const index = document.querySelectorAll('.free_response-item').length + 1;
        const cardDiv = document.createElement('div');
        cardDiv.classList.add('col-md-6', 'free_response-item');
        cardDiv.innerHTML = `
            <div class="card h-100 shadow-sm p-3">
                <h5>free_response ${index}</h5>
                <div class="mb-2">
                    <label>Question</label>
                    <input type="text" name="questions[]" class="form-control" value="">
                </div>
                <div class="mb-2">
                    <label>Answer</label>
                    <textarea name="answers[]" class="form-control" rows="3"></textarea>
                </div>
                <button type="button" class="btn btn-danger btn-sm remove-free_response">Remove</button>
            </div>
        `;
        document.getElementById('free_responseContainer').appendChild(cardDiv);
    });

    // REMOVE free_response
    document.getElementById('free_responseContainer').addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-free_response')) {
            e.target.closest('.free_response-item').remove();
        }
    });

    // SAVE free_responses
    document.getElementById('free_responseForm').addEventListener('submit', function (e) {
        e.preventDefault();

        const questions = Array.from(document.querySelectorAll('input[name="questions[]"]')).map(el => el.value.trim());
        const answers = Array.from(document.querySelectorAll('textarea[name="answers[]"]')).map(el => el.value.trim());

        const free_responses = questions.map((q, i) => ({
            question: q,
            answer: answers[i]
        }));

        fetch("{{ route('admin.save.free_responses') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                lesson_id: document.getElementById('lesson_id').value,
                free_responses: free_responses
            })
        })
        .then(response => {
            if (!response.ok) throw response;
            return response.json();
        })
        .then(data => {
            if (data.status === 'success') {
                showToast(data.message, true);
            } else {
                showToast(data.message || 'Something went wrong.', false);
            }
        })
        .catch(async error => {
            let message = 'Something went wrong while saving free_responses.';
            try {
                const errData = await error.json();
                message = errData.message || message;
            } catch (_) {}
            showToast(message, false);
            console.error(error);
        });
    });
</script>
