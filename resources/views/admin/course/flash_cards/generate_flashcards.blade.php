
<div class="container mt-4">
    <h2 class="mb-4">Generate Flashcards</h2>
    <div class="card">
        <div class="card-body">
            <form id="generateFlashcardsForm">
                <div class="form-group">
                    <label for="topic">Enter Topic</label>
                    <input type="text" id="topic" name="topic" class="form-control" placeholder="Enter topic for flashcards" required>
                </div>
                <button type="button" id="generateButton" class="btn btn-primary mt-3">Generate Flashcards</button>
            </form>
        </div>
    </div>

    <div id="flashcardsContainer" class="mt-4" style="display: none;">
        <h3>Generated Flashcards</h3>
        <form id="editFlashcardsForm">
            <div id="flashcardsList">
                <!-- Flashcards will be dynamically added here -->
            </div>
            <button type="submit" class="btn btn-success mt-3">Save Changes</button>
        </form>
    </div>
</div>

<script>
    document.getElementById('generateButton').addEventListener('click', function () {
        const topic = document.getElementById('topic').value;
        if (!topic) {
            alert('Please enter a topic.');
            return;
        }

        // Simulate OpenAI API response
        const flashcards = [
            { id: 1, text: `Flashcard 1 for ${topic}` },
            { id: 2, text: `Flashcard 2 for ${topic}` },
            { id: 3, text: `Flashcard 3 for ${topic}` }
        ];

        const flashcardsContainer = document.getElementById('flashcardsContainer');
        const flashcardsList = document.getElementById('flashcardsList');
        flashcardsList.innerHTML = '';

        flashcards.forEach(card => {
            const cardDiv = document.createElement('div');
            cardDiv.classList.add('form-group', 'mb-3');
            cardDiv.innerHTML = `
                <label for="flashcard_${card.id}">Flashcard ${card.id}</label>
                <input type="text" id="flashcard_${card.id}" name="flashcards[]" class="form-control" value="${card.text}">
            `;
            flashcardsList.appendChild(cardDiv);
        });

        flashcardsContainer.style.display = 'block';
    });

    document.getElementById('editFlashcardsForm').addEventListener('submit', function (e) {
        e.preventDefault();
        const flashcards = Array.from(document.querySelectorAll('input[name="flashcards[]"]')).map(input => input.value);
        console.log('Edited Flashcards:', flashcards);
        alert('Flashcards saved successfully!');
    });
</script>

