{{-- @php
    $lesson = App\Models\Lesson::find($id);
    $lesson_pdf = asset('uploads/lesson_file/attachment/' . $lesson->attachment);
@endphp

<div class="container mt-4">
    <h2 class="mb-4">Flashcards for Lesson: {{ $lesson->title }}</h2>

    <div class="card mb-4">
        <div class="card-body">
            <p><strong>PDF:</strong> <a href="{{ $lesson_pdf }}" target="_blank">{{ $lesson->attachment }}</a></p>

            <form id="generateFlashcardsForm">
                @csrf
                <input type="hidden" name="lesson_id" id="lesson_id" value="{{ $lesson->id }}">
                <button type="submit" class="btn btn-primary mt-3">Generate Flashcards from PDF</button>
            </form>

            <pre class="mt-4 p-3 bg-light border rounded" id="jsonOutput" style="display: none;"></pre>
        </div>
    </div>
</div>

<script>
    document.getElementById('generateFlashcardsForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const lessonId = document.getElementById('lesson_id').value;
        const output = document.getElementById('jsonOutput');
        output.style.display = 'none';
        output.textContent = 'Generating flashcards...';

        fetch("{{ route('admin.ai.flashcards') }}", {
            method: "POST",
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ lesson_id: lessonId })
        })
        .then(res => res.json())
        .then(data => {
            output.style.display = 'block';
            output.textContent = JSON.stringify(data, null, 4);
        })
        .catch(err => {
            output.style.display = 'block';
            output.textContent = "Error generating flashcards:\n" + err;
            console.error(err);
        });
    });
</script> --}}
{{--

@php
    $lesson = App\Models\Lesson::find($id);
@endphp

<div class="container mt-4">
    <h2 class="mb-4">Flashcards for Lesson: {{ $lesson->title }}</h2>

    <div class="card mb-4">
        <div class="card-body">
            <form id="generateFlashcardsForm">
                @csrf
                <input type="hidden" name="lesson_id" id="lesson_id" value="{{ $lesson->id }}">
                <button type="submit" class="btn btn-primary mt-3">Generate Flashcards from PDF</button>
            </form>
        </div>
    </div>

    <div id="flashcardContainer" class="row g-3"></div>
</div>

<script>
    document.getElementById('generateFlashcardsForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const lessonId = document.getElementById('lesson_id').value;
        const container = document.getElementById('flashcardContainer');
        container.innerHTML = '<div class="text-muted">Generating flashcards...</div>';

        fetch("{{ route('admin.ai.flashcards') }}", {
            method: "POST",
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ lesson_id: lessonId })
        })
        .then(res => res.json())
        .then(data => {
            container.innerHTML = '';

            if (!data.flashcards || data.flashcards.length === 0) {
                container.innerHTML = '<div class="text-danger">No flashcards found.</div>';
                return;
            }

            data.flashcards.forEach((card, index) => {
                const cardHtml = `
                    <div class="col-md-6">
                        <div class="card h-100 shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title">Flashcard ${index + 1}</h5>
                                <p><strong>Q:</strong> ${card.question}</p>
                                <p><strong>A:</strong> ${card.answer}</p>
                            </div>
                        </div>
                    </div>
                `;
                container.insertAdjacentHTML('beforeend', cardHtml);
            });
        })
        .catch(err => {
            container.innerHTML = '<div class="text-danger">Error generating flashcards.</div>';
            console.error(err);
        });
    });
</script> --}}


@php
    $lesson = App\Models\Lesson::find($id);
    $flashcards = $lesson->flashcards ?? [];
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
    <h2 class="mb-4">Flashcards for Lesson: {{ $lesson->title }}</h2>
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
                <form id="generateFlashcardsForm">
                    @csrf
                    <input type="hidden" name="lesson_id" id="lesson_id" value="{{ $lesson->id }}">
                    <button type="submit" class="btn btn-primary mt-3">Generate Flashcards from PDF</button>
                </form>
            @else
                <button type="button" class="btn btn-secondary mt-3" disabled title="No PDF available to generate flashcards">
                    Generate Flashcards
                </button>
            @endif
        </div>
    </div>

    <form id="flashcardForm">
        <input type="hidden" id="lesson_id" name="lesson_id" value="{{ $lesson->id }}">
        <div id="flashcardContainer" class="row g-3 mb-4">
            @if (is_array($lesson->flashcards) && count($lesson->flashcards))
                @foreach ($lesson->flashcards as $index => $card)
                    <div class="col-md-6 flashcard-item">
                        <div class="card h-100 shadow-sm p-3">
                            <h5>Flashcard {{ $index + 1 }}</h5>
                            <div class="mb-2">
                                <label>Question</label>
                                <input type="text" name="questions[]" class="form-control"
                                    value="{{ $card['question'] }}">
                            </div>
                            <div class="mb-2">
                                <label>Answer</label>
                                <textarea name="answers[]" class="form-control" rows="3">{{ $card['answer'] }}</textarea>
                            </div>
                            <button type="button" class="btn btn-danger btn-sm remove-flashcard">Remove</button>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>

        <div class="d-flex gap-3 mb-4">
            <button type="button" class="btn btn-secondary" id="addFlashcardBtn">Add Flashcard</button>
            <button type="submit" class="btn btn-success">Save All Flashcards</button>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        tooltipTriggerList.forEach(function (tooltipTriggerEl) {
            new bootstrap.Tooltip(tooltipTriggerEl)
        });
    });
</script>

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


    // Generate flashcards via AI
    document.getElementById('generateFlashcardsForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const lessonId = document.getElementById('lesson_id').value;
        const container = document.getElementById('flashcardContainer');
        container.innerHTML = '<div class="text-muted">Generating flashcards...</div>';

        fetch("{{ route('admin.ai.flashcards') }}", {
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
                if (!data.flashcards || data.flashcards.length === 0) {
                    container.innerHTML = '<div class="text-danger">No flashcards found.</div>';
                    return;
                }

                container.innerHTML = ''; // Clear old cards
                data.flashcards.forEach((card, index) => {
                    const cardDiv = document.createElement('div');
                    cardDiv.classList.add('col-md-6', 'flashcard-item');
                    cardDiv.innerHTML = `
                    <div class="card h-100 shadow-sm p-3">
                        <h5>Flashcard ${index + 1}</h5>
                        <div class="mb-2">
                            <label>Question</label>
                            <input type="text" name="questions[]" class="form-control" value="${card.question}">
                        </div>
                        <div class="mb-2">
                            <label>Answer</label>
                            <textarea name="answers[]" class="form-control" rows="3">${card.answer}</textarea>
                        </div>
                        <button type="button" class="btn btn-danger btn-sm remove-flashcard">Remove</button>
                    </div>
                `;
                    container.appendChild(cardDiv);
                });
            })
            .catch(err => {
                container.innerHTML = '<div class="text-danger">Error generating flashcards.</div>';
                console.error(err);
            });
    });

    // Add new flashcard
    document.getElementById('addFlashcardBtn').addEventListener('click', function() {
        const index = document.querySelectorAll('.flashcard-item').length + 1;
        const cardDiv = document.createElement('div');
        cardDiv.classList.add('col-md-6', 'flashcard-item');
        cardDiv.innerHTML = `
            <div class="card h-100 shadow-sm p-3">
                <h5>Flashcard ${index}</h5>
                <div class="mb-2">
                    <label>Question</label>
                    <input type="text" name="questions[]" class="form-control" value="">
                </div>
                <div class="mb-2">
                    <label>Answer</label>
                    <textarea name="answers[]" class="form-control" rows="3"></textarea>
                </div>
                <button type="button" class="btn btn-danger btn-sm remove-flashcard">Remove</button>
            </div>
        `;
        document.getElementById('flashcardContainer').appendChild(cardDiv);
    });

    // Remove flashcard
    document.getElementById('flashcardContainer').addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-flashcard')) {
            e.target.closest('.flashcard-item').remove();
        }
    });

    // Save flashcards
    document.getElementById('flashcardForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const questions = Array.from(document.querySelectorAll('input[name="questions[]"]')).map(el => el.value
            .trim());
        const answers = Array.from(document.querySelectorAll('textarea[name="answers[]"]')).map(el => el.value
            .trim());

        const flashcards = questions.map((q, i) => ({
            question: q,
            answer: answers[i]
        }));

        fetch("{{ route('admin.save.flashcards') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    lesson_id: document.getElementById('lesson_id').value,
                    flashcards: flashcards // must be an array of {question, answer}
                })
            })

            // .then(res => res.json())
            // .then(data => {
            //     alert('Flashcards saved successfully!');
            // })
            // .catch(err => {
            //     alert('Failed to save flashcards.');
            //     console.error(err);
            // });
            .then(response => {
                // Ensure it's a valid JSON
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
                let message = 'Something went wrong while saving flashcards.';
                try {
                    const errData = await error.json();
                    message = errData.message || message;
                } catch (_) {}
                showToast(message, false);
                console.error(error);
            });
    });
</script>
