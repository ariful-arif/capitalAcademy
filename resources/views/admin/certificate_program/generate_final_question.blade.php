@php
    $certificate = App\Models\CertificateProgram::find($id);
    $mcqs = $certificate->final_question ?? [];

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
    <h2 class="mb-4">Final Exam Question for certificate: {{ $certificate->title }}</h2>
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
                @if ($certificate->final_pdf)
                    <a href="{{ asset( $certificate->final_pdf) }}" target="_blank">
                        Click here to view PDF
                    </a>
                @else
                    <span class="text-danger">No PDF is available</span>
                @endif
            </p>

            @if ($certificate->final_pdf)
                <form id="generatemcqsForm">
                    @csrf
                    <input type="hidden" name="certificate_id" id="certificate_id" value="{{ $certificate->id }}">
                    <button type="submit" class="btn btn-primary mt-3">Generate Mcq question from PDF</button>
                </form>
            @else
                <button type="button" class="btn btn-secondary mt-3" disabled
                    title="No PDF available to generate mcqs">
                    Generate mcq question
                </button>
            @endif
        </div>
    </div>

    <form id="mcqForm">
        <input type="hidden" id="certificate_id" name="certificate_id" value="{{ $certificate->id }}">
        <div id="mcqContainer" class="row g-3 mb-4">
            @if (is_array($certificate->final_question) && count($certificate->final_question))
                @foreach ($certificate->final_question as $index => $mcq)
                    <div class="col-md-6 mcq-item">
                        <div class="card h-100 shadow-sm p-3">
                            <h5>MCQ {{ $index + 1 }}</h5>
                            <div class="mb-2">
                                <label>Question</label>
                                <input type="text" name="questions[]" class="form-control"
                                    value="{{ $mcq['question'] ?? '' }}">
                            </div>
                            @foreach (['A', 'B', 'C', 'D'] as $opt)
                                <div class="mb-2">
                                    <label>Option {{ $opt }}</label>
                                    <input type="text" name="options[{{ $index }}][{{ $opt }}]"
                                        class="form-control" value="{{ $mcq['options'][$opt] ?? '' }}">
                                </div>
                            @endforeach
                            <div class="mb-2">
                                <label>Correct Answer</label>
                                <select name="correct_answers[]" class="form-control">
                                    @foreach (['A', 'B', 'C', 'D'] as $opt)
                                        <option value="{{ $opt }}"
                                            {{ ($mcq['correct_answer'] ?? '') === $opt ? 'selected' : '' }}>
                                            {{ $opt }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="button" class="btn btn-danger btn-sm remove-mcq">Remove</button>
                        </div>
                    </div>
                @endforeach
            @endif

        </div>

        <div class="d-flex gap-3 mb-4">
            <button type="button" class="btn btn-secondary" id="addmcqBtn">Add mcq</button>
            <button type="submit" class="btn btn-success">Save All mcqs</button>
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

    // GENERATE mcqs FROM PDF
    document.getElementById('generatemcqsForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const certificateId = document.getElementById('certificate_id').value;
        const container = document.getElementById('mcqContainer');
        container.innerHTML = '<div class="text-muted">Generating mcq questions...</div>';

        fetch("{{ route('admin.ai.final_questions') }}", {
                method: "POST",
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    certificate_id: certificateId
                })
            })
            .then(res => res.json())

            .then(data => {
                if (!data.status) {
        const errorMsg = data.message || 'Error generating mcqs.';
        const details = data.error || (data.errors ? JSON.stringify(data.errors) : null) || '';
        container.innerHTML = `<div class="text-danger">${errorMsg}${details ? `<br><small>${details}</small>` : ''}</div>`;
        showToast(errorMsg, false);
        return;
    }

    if (!data.mcqs || data.mcqs.length === 0) {
        container.innerHTML = '<div class="text-danger">No MCQs returned.</div>';
        showToast('No MCQs generated.', false);
        return;
    }

                container.innerHTML = '';
                data.mcqs.forEach((mcq, index) => {
                    const cardDiv = document.createElement('div');
                    cardDiv.classList.add('col-md-6', 'mcq-item');
                    cardDiv.innerHTML = `
        <div class="card h-100 shadow-sm p-3">
            <h5>MCQ ${index + 1}</h5>
            <div class="mb-2">
                <label>Question</label>
                <input type="text" name="questions[]" class="form-control" value="${mcq.question}">
            </div>
            ${['A', 'B', 'C', 'D'].map(letter => `
                <div class="mb-2">
                    <label>Option ${letter}</label>
                    <input type="text" name="options[${index}][${letter}]" class="form-control"
                        value="${mcq.options[letter] || ''}">
                </div>
            `).join('')}
            <div class="mb-2">
                <label>Correct Answer</label>
                <select name="correct_answers[]" class="form-control">
                    ${['A', 'B', 'C', 'D'].map(letter => `
                        <option value="${letter}" ${mcq.correct_answer === letter ? 'selected' : ''}>${letter}</option>
                    `).join('')}
                </select>
            </div>
            <button type="button" class="btn btn-danger btn-sm remove-mcq">Remove</button>
        </div>
    `;
                    container.appendChild(cardDiv);
                });

                showToast('Free response questions generated successfully!', true);
            })
            .catch(err => {
                container.innerHTML = '<div class="text-danger">Error generating mcqs.</div>';
                console.error(err);
                showToast('Failed to generate mcqs.', false);
            });
    });


    // ADD MCQ
    document.getElementById('addmcqBtn').addEventListener('click', function() {
        const index = document.querySelectorAll('.mcq-item').length;
        const cardDiv = document.createElement('div');
        cardDiv.classList.add('col-md-6', 'mcq-item');
        cardDiv.innerHTML = `
        <div class="card h-100 shadow-sm p-3">
            <h5>MCQ ${index + 1}</h5>
            <div class="mb-2">
                <label>Question</label>
                <input type="text" name="questions[]" class="form-control" required>
            </div>
            ${['A', 'B', 'C', 'D'].map(letter => `
                <div class="mb-2">
                    <label>Option ${letter}</label>
                    <input type="text" name="options[${index}][${letter}]" class="form-control" required>
                </div>
            `).join('')}
            <div class="mb-2">
                <label>Correct Answer</label>
                <select name="correct_answers[]" class="form-control" required>
                    <option value="">Select correct option</option>
                    <option value="A">Option A</option>
                    <option value="B">Option B</option>
                    <option value="C">Option C</option>
                    <option value="D">Option D</option>
                </select>
            </div>
            <button type="button" class="btn btn-danger btn-sm remove-mcq">Remove</button>
        </div>
    `;
        document.getElementById('mcqContainer').appendChild(cardDiv);
    });

    // REMOVE mcq
    document.getElementById('mcqContainer').addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-mcq')) {
            e.target.closest('.mcq-item').remove();
        }
    });

    // SAVE mcqs
    document.getElementById('mcqForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const questionEls = document.querySelectorAll('input[name="questions[]"]');
        const correctAnsEls = document.querySelectorAll('select[name="correct_answers[]"]');
        const optionsEls = document.querySelectorAll('[name^="options["]');

        const mcqs = Array.from(questionEls).map((el, index) => {
            const options = {};
            ['A', 'B', 'C', 'D'].forEach(letter => {
                const optInput = document.querySelector(
                    `input[name="options[${index}][${letter}]"]`);
                options[letter] = optInput ? optInput.value.trim() : '';
            });

            return {
                question: el.value.trim(),
                options,
                correct_answer: correctAnsEls[index]?.value || ''
            };
        });

        fetch("{{ route('admin.save.final_questions') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    certificate_id: document.getElementById('certificate_id').value,
                    mcqs: mcqs
                })
            })
            .then(res => res.ok ? res.json() : Promise.reject(res))
            .then(data => {
                if (data.status === 'success') {
                    showToast(data.message, true);
                } else {
                    showToast(data.message || 'Failed to save.', false);
                }
            })
            .catch(async err => {
                let msg = 'Failed to save mcqs.';
                try {
                    const res = await err.json();
                    msg = res.message || msg;
                } catch (_) {}
                showToast(msg, false);
            });
    });
</script>
