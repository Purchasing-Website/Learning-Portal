@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h3>Questions for: {{ $quiz->title }}</h3>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <button class="btn btn-primary" id="addQuestionBtn">
            <i class="bi bi-plus-circle"></i> Add Question
        </button>
    </div>

    <table class="table table-bordered table-striped">
        <thead class="table-light">
            <tr>
                <th>#</th>
                <th>Question</th>
                <th>Type</th>
                <th>Active</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($questions as $index => $question)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $question->question }}</td>
                    <td>{{ ucfirst($question->questiontype) }}</td>
                    <td class="status-cell">
                            @if($question->is_active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </td>
                    <td>
                        <a class="btn btn-sm" href="{{ route('option.index', $question->id) }}">
                            Add Options
                        </a>
                        <button class="btn btn-sm btn-warning editQuestionBtn" data-id="{{ $question->id }}">
                            Edit
                        </button>
                        <button class="btn btn-warning btn-sm toggleStatus" data-id="{{ $question->id }}">
                            {{ $question->is_active ? 'Deactivate' : 'Activate' }}
                        </button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">No questions added yet.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    <!-- Pagination -->
        <div class="d-flex justify-content-center">
            {{ $questions->links() }}
        </div>
</div>

<!-- Modal -->
<div class="modal fade" id="questionModal" tabindex="-1" aria-labelledby="questionModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form id="questionForm">
        @csrf
        <input type="hidden" id="quiz_id" value="{{ $quiz->id }}">
        <input type="hidden" id="question_id">

        <div class="modal-header">
          <h5 class="modal-title" id="questionModalLabel">Add Question</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <div class="mb-3">
            <label for="question_text" class="form-label">Question Text</label>
            <textarea class="form-control" id="question_text" rows="2" required></textarea>
          </div>

          <div class="mb-3">
            <label for="question_type" class="form-label">Question Type</label>
            <select class="form-select" id="question_type" required>
              <option value="">Select type</option>
              @foreach ( $questionTypes as $questionType )
                <option value="{{ $questionType->value }}">{{ ucfirst(str_replace('_', ' ', $questionType->value)) }}</option>
              @endforeach
            </select>
          </div>
          <div class="mb-3">
            <label for="question_point" class="form-label">Question Point</label>
            <input type="integer" class="form-control" id="question_point" rows="2" required></input>
          </div>
          <div class="form-check mb-3">
            <input type="checkbox" class="form-check-input" id="is_active" checked>
            <label for="is_active" class="form-check-label">Active</label>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Save Question</button>
        </div>
      </form>
    </div>
  </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const addBtn = document.getElementById('addQuestionBtn');
    const questionModal = new bootstrap.Modal(document.getElementById('questionModal'));
    const form = document.getElementById('questionForm');
    const quizId = document.getElementById('quiz_id').value;

    // Add Question button
    addBtn.addEventListener('click', () => {
        form.reset();
        document.getElementById('question_id').value = '';
        document.getElementById('questionModalLabel').innerText = 'Add Question';
        questionModal.show();
    });

    // Edit Question button
    document.querySelectorAll('.editQuestionBtn').forEach(btn => {
        btn.addEventListener('click', async () => {
            const id = btn.dataset.id;

            try {
                const res = await fetch(`/question/${id}/edit`);
                const data = await res.json();

                document.getElementById('question_id').value = data.id;
                document.getElementById('question_text').value = data.question;
                document.getElementById('question_type').value = data.questiontype;
                document.getElementById('question_point').value = data.points;
                document.getElementById('is_active').checked = data.is_active;

                document.getElementById('questionModalLabel').innerText = 'Edit Question';
                questionModal.show();
            } catch (error) {
                console.error('Error loading question:', error);
                alert('Failed to load question.');
            }
        });
    });

    // Save (Add or Update)
    form.addEventListener('submit', async (e) => {
        e.preventDefault();

        const id = document.getElementById('question_id').value;
        const url = id ? `/question/${id}/update` : `/question/store`;
        const formData = new FormData();
        formData.append('_token', document.querySelector('input[name="_token"]').value);
        formData.append('quiz_id', quizId);
        formData.append('question', document.getElementById('question_text').value);
        formData.append('questiontype', document.getElementById('question_type').value);
        formData.append('points', document.getElementById('question_point').value);
        formData.append('is_active', document.getElementById('is_active').checked ? 1 : 0);

        try {
            const res = await fetch(url, { 
                method: 'POST', 
                body: formData 
            });
            const data = await res.json();

            if (data.success) {
                alert(data.message);
                questionModal.hide();
                location.reload();
            } else {
                console.error('Error saving question:', data.message);
                alert('Failed to save question.');
            }
        } catch (error) {
            console.error('Error saving question:', error);
            alert('Unable to save question.');
        }
    });

    // // Delete question
    // document.querySelectorAll('.deleteQuestionBtn').forEach(btn => {
    //     btn.addEventListener('click', async () => {
    //         if (!confirm('Are you sure you want to delete this question?')) return;

    //         const id = btn.dataset.id;

    //         try {
    //             const res = await fetch(`/admin/question/${id}/delete`, {
    //                 method: 'DELETE',
    //                 headers: {
    //                     'X-CSRF-TOKEN': '{{ csrf_token() }}'
    //                 }
    //             });

    //             const data = await res.json();

    //             if (data.success) {
    //                 alert(data.message);
    //                 location.reload();
    //             } else {
    //                 alert('Failed to delete question.');
    //             }
    //         } catch (error) {
    //             console.error('Error deleting question:', error);
    //             alert('Unable to delete question.');
    //         }
    //     });
    // });
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
                    const response = await fetch(`/question/${id}/toggle-status`, {
                        method: 'PATCH',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
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
</script>
@endpush