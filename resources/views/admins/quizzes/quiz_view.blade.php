@extends('layouts.app')

@section('content')
@if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Quiz Management</h3>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#quizModal" id="createQuizBtn">
            + Create Quiz
        </button>
    </div>

    <table class="table table-bordered align-middle">
        <thead>
            <tr>
                <th>Title</th>
                <th>Type</th>
                <th>Pass Score (%)</th>
                <th>Status</th>
                <th width="150">Action</th>
            </tr>
        </thead>
        <tbody id="quizTableBody">
            @foreach ($quizzes as $quiz)
                <tr data-id="{{ $quiz->id }}">
                    <td>{{ $quiz->title }}</td>
                    <td>{{ $quiz->quizType }}</td>
                    <td>{{ $quiz->pass_score }}</td>
                    <td class="status-cell">
                        @if($quiz->is_active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-secondary">Inactive</span>
                            @endif
                    </td>
                    <td>
                        <a href="{{ route('question.index', $quiz->id) }}" class="btn btn-sm btn-primary">
                            Add Question
                        </a>
                        <button class="btn btn-sm btn-warning editQuizBtn" data-id="{{ $quiz->id }}">Edit</button>
                        <button class="btn btn-sm btn-danger toggleStatus" data-id="{{ $quiz->id }}">{{ $quiz->is_active ? 'Deactivate' : 'Activate' }}</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- QUIZ MODAL -->
<div class="modal fade" id="quizModal" tabindex="-1" aria-labelledby="quizModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="quizModalLabel">Create Quiz</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form id="quizForm">
                @csrf
                <input type="hidden" id="quiz_id">

                <div class="mb-3">
                    <label>Title</label>
                    <input type="text" id="title" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Description</label>
                    <textarea id="description" class="form-control"></textarea>
                </div>

                <div class="mb-3">
                    <label>Quiz Type</label>
                    <select id="quizType" class="form-select" required>
                        <option value="">-- Select Type --</option>
                        <option value="KnowledgeCheck">Knowledge Check</option>
                        <option value="FinalQuiz">Final Quiz</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label>Pass Score (%)</label>
                    <input type="number" id="pass_score" class="form-control" min="0" max="100" value="80">
                </div>

                <div class="mb-3">
                    <label>Source URL</label>
                    <input type="text" id="source_url" class="form-control" placeholder="Optional link or file path">
                </div>

                <div class="form-check mb-3">
                    <input type="checkbox" id="is_active" class="form-check-input" checked>
                    <label for="is_active" class="form-check-label">Active</label>
                </div>

                <div class="text-end">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
  </div>
</div>

@endsection


@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", () => {
    const quizForm = document.getElementById('quizForm');
    const quizModal = new bootstrap.Modal(document.getElementById('quizModal'));
    const createBtn = document.getElementById('createQuizBtn');
    const tableBody = document.getElementById('quizTableBody');

    // CREATE new quiz
    createBtn.addEventListener('click', () => {
        quizForm.reset();
        document.getElementById('quiz_id').value = '';
        document.getElementById('quizModalLabel').innerText = 'Create Quiz';
    });

    quizForm.addEventListener('submit', async (e) => {
    e.preventDefault();

    const id = document.getElementById('quiz_id').value;
    const url = id ? `/quiz/${id}/update` : '/admin/quiz/store';
    const method = id ? 'POST' : 'POST'; // both use POST, Laravel handles PUT via _method

    const formData = new FormData();
    formData.append('_token', document.querySelector('input[name="_token"]').value);
    formData.append('title', document.getElementById('title').value);
    formData.append('description', document.getElementById('description').value);
    formData.append('quizType', document.getElementById('quizType').value);
    formData.append('source_url', document.getElementById('source_url').value);
    formData.append('pass_score', document.getElementById('pass_score').value);
    formData.append('is_active', document.getElementById('is_active').checked ? 1 : 0);

    try {
        const response = await fetch(url, {
            method: method,
            body: formData
        });

        // try parsing JSON safely
        const data = await response.json();

        if (data.success) {
            alert(data.message || 'Quiz saved successfully!');
            window.location.reload();
        } else {
            alert('Failed to save quiz.');
            console.error(data);
        }
    } catch (error) {
        console.error('Error:', error);
        alert('An error occurred while saving quiz.');
    }
});

    // EDIT quiz
    document.querySelectorAll('.editQuizBtn').forEach(btn => {
        btn.addEventListener('click', async () => {
            const id = btn.dataset.id;
            try {
                const response = await fetch(`/quiz/${id}/edit`);
                const quiz = await response.json();

                document.getElementById('quiz_id').value = quiz.id;
                document.getElementById('title').value = quiz.title;
                document.getElementById('description').value = quiz.description || '';
                document.getElementById('quizType').value = quiz.quizType;
                document.getElementById('source_url').value = quiz.source_url || '';
                document.getElementById('pass_score').value = quiz.pass_score;
                document.getElementById('is_active').checked = quiz.is_active;

                document.getElementById('quizModalLabel').innerText = 'Edit Quiz';
                quizModal.show();
            } catch (error) {
                console.error('Error loading quiz data:', error);
                alert('Failed to load quiz details.');
            }
        });
    });

});

// Toggle Status Is Active AJAX Request using jQuery 
document.addEventListener('DOMContentLoaded', function() {
    const buttons = document.querySelectorAll('.toggleStatus');

    buttons.forEach(button => {
        button.addEventListener('click', async function() {
            const id = this.dataset.id;
            const row = this.closest('tr');
            const badge = row.querySelector('.status-cell span');
            const isCurrentlyActive = this.textContent.trim() === 'Active';
            const confirmMessage = `Are you sure you want to ${isCurrentlyActive ? 'deactivate' : 'activate'} this program?`;

            if (confirm(confirmMessage)) {
                try {
                    const response = await fetch(`/quiz/${id}/toggle-status`, {
                        method: 'PATCH',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                        },
                    });

                    const data = await response.json();

                    if (data.success) {
                        // ✅ Update button and badge dynamically
                        if (data.is_active) {
                            this.classList.remove('btn-danger');
                            this.classList.add('btn-success');
                            this.textContent = 'Deactive';
                            badge.classList.remove('bg-secondary');
                            badge.classList.add('bg-success');
                            badge.textContent = 'Activate';
                        } else {
                            this.classList.remove('btn-success');
                            this.classList.add('btn-danger');
                            this.textContent = 'Activate';
                            badge.classList.remove('bg-success');
                            badge.classList.add('bg-secondary');
                            badge.textContent = 'Inactive';
                        }
                    } else {
                        alert('Failed to update status.');
                    }
                } catch (error) {
                    console.error('Error:', error);
                }
            }
        });
    });
});

document.addEventListener("DOMContentLoaded", function() {
// Question modal instance
const questionModal = new bootstrap.Modal(document.getElementById('questionModal'));

// Add Question Button
document.querySelectorAll('.addQuestionBtn').forEach(btn => {
    btn.addEventListener('click', () => {
        const quizId = btn.dataset.id;
        document.getElementById('quiz_id_for_question').value = quizId;

        // Reset form
        document.getElementById('questionForm').reset();
        document.getElementById('question_id').value = '';

        document.getElementById('questionModalLabel').innerText = 'Add Question';
        questionModal.show();
    });
});
});
</script>
@endpush