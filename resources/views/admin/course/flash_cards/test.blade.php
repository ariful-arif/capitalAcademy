@php
    $lesson = App\Models\Lesson::find($id);
    // $lesson_pdf = asset('uploads/lesson_file/attachment/' . $lesson->attachment);
@endphp

<div class="container mt-4">
    <h2 class="mb-4">Flashcards for Lesson: {{ $lesson->title }}</h2>

    {{-- <div class="card mb-4">
        <div class="card-body">
            <form id="generateFlashcardsForm">
                <input type="hidden" name="lesson_id" id="lesson_id" value="{{ $lesson->id }}">
                <p><strong>PDF:</strong> {{ $lesson_pdf }}</p>
                <button type="submit" class="btn btn-primary mt-3">Generate Flashcards from PDF</button>
            </form>
        </div>
    </div> --}}

    <div id="flashcardsContainer" class="mb-5" style="{{ $lesson->flashcards ? '' : 'display: none;' }}">
        <h3>Flashcards</h3>
        <form id="editFlashcardsForm">
            <div id="flashcardsList">
                @if (!empty($lesson->flashcards))
                    @foreach ($lesson->flashcards as $index => $flashcard)
                        <div class="flashcard-item form-group mb-3 border p-3 rounded">
                            <label>Flashcard {{ $index + 1 }}</label>
                            <input type="text" name="questions[]" class="form-control mb-2" placeholder="Question"
                                value="{{ $flashcard['question'] ?? '' }}">
                            <textarea name="answers[]" class="form-control mb-2" rows="3" placeholder="Answer">{{ $flashcard['answer'] ?? '' }}</textarea>
                            <button type="button" class="btn btn-danger btn-sm remove-flashcard">Remove</button>
                        </div>
                    @endforeach
                @endif
            </div>
            <div class="row">
                <div class="col-auto">
                    <button type="submit" class="btn btn-success mt-3">Save Changes</button>
                </div>
                <div class="col-auto">
                    <button type="button" class="btn btn-secondary mt-3" id="addFlashcardBtn">Add New
                        Flashcard</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    // document.getElementById('generateFlashcardsForm').addEventListener('submit', function(e) {
    //     e.preventDefault();

    //     const lessonId = document.getElementById('lesson_id').value;

    //     fetch("{{ route('ai.flashcards') }}", {
    //             method: "POST",
    //             headers: {
    //                 'Content-Type': 'application/json',
    //                 'X-CSRF-TOKEN': '{{ csrf_token() }}'
    //             },
    //             body: JSON.stringify({
    //                 lesson_id: lessonId
    //             })
    //         })
    //         .then(res => res.json())
    //         .then(data => {
    //             if (data.flashcards && data.flashcards.length > 0) {
    //                 const container = document.getElementById('flashcardsContainer');
    //                 const list = document.getElementById('flashcardsList');
    //                 list.innerHTML = ''; // Clear old cards

    //                 data.flashcards.forEach((fc, idx) => {
    //                     const card = document.createElement('div');
    //                     card.classList.add('flashcard-item', 'form-group', 'mb-3', 'border', 'p-3',
    //                         'rounded');
    //                     card.innerHTML = `
    //                 <label>Flashcard ${idx + 1}</label>
    //                 <input type="text" name="questions[]" class="form-control mb-2" placeholder="Question" value="${fc.question}">
    //                 <textarea name="answers[]" class="form-control mb-2" rows="3" placeholder="Answer">${fc.answer}</textarea>
    //                 <button type="button" class="btn btn-danger btn-sm remove-flashcard">Remove</button>
    //             `;
    //                     list.appendChild(card);
    //                 });

    //                 container.style.display = 'block';
    //                 alert("Flashcards generated successfully!");
    //             } else {
    //                 alert("Failed to generate flashcards or none returned.");
    //             }
    //         })
    //         .catch(err => {
    //             alert("Error generating flashcards.");
    //             console.error(err);
    //         });
    // });


</script>


<script>
    document.getElementById('addFlashcardBtn').addEventListener('click', function() {
        const index = document.querySelectorAll('.flashcard-item').length + 1;
        const flashcardDiv = document.createElement('div');
        flashcardDiv.classList.add('flashcard-item', 'form-group', 'mb-3', 'border', 'p-3', 'rounded');

        flashcardDiv.innerHTML = `
            <label>Flashcard ${index}</label>
            <input type="text" name="questions[]" class="form-control mb-2" placeholder="Question">
            <textarea name="answers[]" class="form-control mb-2" rows="3" placeholder="Answer"></textarea>
            <button type="button" class="btn btn-danger btn-sm remove-flashcard">Remove</button>
        `;

        document.getElementById('flashcardsList').appendChild(flashcardDiv);
        updateFlashcardLabels();
    });

    // Remove flashcard
    document.getElementById('flashcardsList').addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-flashcard')) {
            e.target.closest('.flashcard-item').remove();
            updateFlashcardLabels();
        }
    });

    // Update labels (e.g., Flashcard 1, 2, 3...)
    function updateFlashcardLabels() {
        const items = document.querySelectorAll('.flashcard-item');
        items.forEach((el, idx) => {
            el.querySelector('label').textContent = `Flashcard ${idx + 1}`;
        });
    }

    document.getElementById('editFlashcardsForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const questions = Array.from(document.querySelectorAll('input[name="questions[]"]'));
        const answers = Array.from(document.querySelectorAll('textarea[name="answers[]"]'));

        const flashcards = questions.map((input, index) => {
            return {
                question: input.value.trim(),
                answer: answers[index].value.trim()
            };
        });

        fetch("/save-flashcards", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                lesson_id: document.getElementById('lesson_id').value,
                flashcards: flashcards
            })
        }).then(res => res.json()).then(data => {
            alert('Flashcards saved successfully!');
        }).catch(err => {
            alert('Failed to save flashcards.');
            console.error(err);
        });
    });
</script>
