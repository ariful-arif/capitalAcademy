@php
    $lesson = App\Models\Lesson::find($id);
    use League\CommonMark\CommonMarkConverter;

$converter = new League\CommonMark\CommonMarkConverter();
$converted = $converter->convert($lesson->summary)->getContent();
@endphp

<style>
    .toast-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
    }

    .custom-toast {
        background-color: #28a745;
        border-radius: 0.75rem;
        min-width: 320px;
        max-width: 400px;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.2);
    }
</style>

<div class="container mt-4">
    <h2 class="mb-4">Lesson Summary Editor: {{ $lesson->title }}</h2>

    <!-- Toast Notification -->
    <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 9999;">
        <div id="liveToast" class="custom-toast toast align-items-start text-white" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex w-100">
                <div class="toast-icon p-3"><i class="bi bi-patch-check-fill fs-3"></i></div>
                <div class="toast-body pe-2">
                    <div class="d-flex justify-content-between align-items-start mb-1">
                        <strong id="toastTitle">Success!</strong>
                        <small class="text-white-50 ms-auto">Just Now</small>
                        <button type="button" class="btn-close btn-close-white ms-3" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                    <div id="toastMessage">Your action was successful!</div>
                </div>
            </div>
        </div>
    </div>

    <!-- PDF Info -->
    <div class="card mb-4">
        <div class="card-body">
            <p><strong>PDF:</strong>
                @if ($lesson->attachment)
                    <a href="{{ asset('uploads/lesson_file/attachment/' . $lesson->attachment) }}" target="_blank">{{ $lesson->attachment }}</a>
                @else
                    <span class="text-danger">No PDF is available</span>
                @endif
            </p>

            @if ($lesson->attachment)
                <form id="generateSummaryForm">
                    @csrf
                    <input type="hidden" name="lesson_id" value="{{ $lesson->id }}">
                    <button type="submit" class="btn btn-primary mt-3">Regenerate Summary from PDF</button>
                </form>
            @else
                <button type="button" class="btn btn-secondary mt-3" disabled>No PDF</button>
            @endif
        </div>
    </div>

    <!-- Summary Editor Form -->
    <!-- Summary Editor Form -->
<form id="saveSummaryForm">
    @csrf
    <input type="hidden" name="lesson_id" value="{{ $lesson->id }}">
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">📝 Edit Summary (HTML View)</h5>
        </div>
        <div class="card-body">
            <textarea id="summaryTextarea" name="summary" class="form-control" rows="12">{!! old('summary', $lesson->summary) !!}</textarea>
        </div>
    </div>

    <button type="submit" class="btn btn-success">Save Summary</button>
</form>

<!-- Rendered Preview -->
<div class="card mt-4">
    <div class="card-header bg-secondary text-white">
        <h5 class="mb-0">👁️ Summary Preview</h5>
    </div>
    <div class="card-body" id="summaryPreview">
        {!! $lesson->summary !!}
    </div>
</div>

</div>

<script>
    function showToast(message, isSuccess = true) {
        const toastEl = document.getElementById('liveToast');
        const toastMsg = document.getElementById('toastMessage');
        const toastTitle = document.getElementById('toastTitle');
        const toastIcon = document.querySelector('.toast-icon i');

        toastMsg.textContent = message;
        toastTitle.textContent = isSuccess ? 'Success!' : 'Error!';
        toastEl.style.backgroundColor = isSuccess ? '#28a745' : '#dc3545';
        toastIcon.className = isSuccess ? 'bi bi-patch-check-fill fs-3' : 'bi bi-x-octagon-fill fs-3';

        const toast = new bootstrap.Toast(toastEl);
        toast.show();
    }
    document.getElementById('summaryTextarea')?.addEventListener('input', function() {
    document.getElementById('summaryPreview').innerHTML = this.value;
});

    // Regenerate Summary from PDF
    document.getElementById('generateSummaryForm')?.addEventListener('submit', function(e) {
    e.preventDefault();

    const button = this.querySelector('button');
    const originalText = button.innerHTML;

    button.disabled = true;
    button.innerHTML = 'Generating summary... <span class="spinner-border spinner-border-sm ms-2"></span>';

    fetch("{{ route('admin.ai.summary') }}", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ lesson_id: "{{ $lesson->id }}" })
    })
    .then(response => response.json())
    .then(data => {
        if (data.summary) {
            document.getElementById('summaryTextarea').value = data.summary;
            document.getElementById('summaryPreview').innerHTML = data.summary;
            showToast("Summary generated from PDF.", true);
        } else {
            showToast("No summary generated.", false);
        }
    })
    .catch(err => {
        console.error(err);
        showToast("Error generating summary.", false);
    })
    .finally(() => {
        button.disabled = false;
        button.innerHTML = originalText;
    });
});

    // Save Summary
    document.getElementById('saveSummaryForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const summary = document.getElementById('summaryTextarea').value;

        fetch("{{ route('admin.save.summary') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                lesson_id: "{{ $lesson->id }}",
                summary: summary
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                showToast(data.message, true);
            } else {
                showToast(data.message || 'Error saving summary.', false);
            }
        })
        .catch(err => {
            console.error(err);
            showToast('Something went wrong while saving.', false);
        });
    });
</script>
