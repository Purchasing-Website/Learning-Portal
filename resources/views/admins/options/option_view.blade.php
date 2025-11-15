@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h3>Questions for: {{ $question->question }}</h3>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <button class="btn btn-primary" id="addOptionBtn">
            <i class="bi bi-plus-circle"></i> Add Option
        </button>
    </div>

    <table class="table table-bordered table-striped">
        <thead class="table-light">
            <tr>
                <th>#</th>
                <th>Option</th>
                <th>Is Correct</th>
                <th>Active</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($options as $index => $option)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $option->option_text }}</td>
                    <td >
                        @if($option->is_correct)
                            <span class="badge bg-success">Correct</span>
                        @else
                            <span class="badge bg-secondary">False</span>
                        @endif
                    </td>
                    <td class="status-cell">
                            @if($option->is_active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-secondary">Inactive</span>
                            @endif
                    </td>
                    <td>
                        <button class="btn btn-sm btn-warning editOptionBtn" data-id="{{ $option->id }}">
                            Edit
                        </button>
                        <button class="btn btn-warning btn-sm toggleStatus" data-id="{{ $option->id }}">
                            {{ $option->is_active ? 'Deactivate' : 'Activate' }}
                        </button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">No option added yet.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    <!-- Pagination -->
        <div class="d-flex justify-content-center">
            {{ $options->links() }}
        </div>
</div>

<!-- Modal -->
<div class="modal fade" id="optionModal" tabindex="-1" aria-labelledby="questionModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form id="optionForm">
        @csrf
        <input type="hidden" id="question_id" value="{{ $question->id }}">
        <input type="hidden" id="option_id">

        <div class="modal-header">
          <h5 class="modal-title" id="optionModalLabel">Add Option</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <div class="mb-3">
            <label for="option_text" class="form-label">Option Text</label>
            <textarea class="form-control" id="option_text" rows="2" required></textarea>
          </div>

         
          <div class="mb-3">
            <label for="correctOption" class="form-check-label">is Correct</label>
            <input type="checkbox" class="form-check-input" id="correctOption"></input>
          </div>
          <div class="form-check mb-3">
            <input type="checkbox" class="form-check-input" id="is_active" checked>
            <label for="is_active" class="form-check-label">Active</label>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Save Option</button>
        </div>
      </form>
    </div>
  </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const addBtn = document.getElementById('addOptionBtn');
    const optionModal = new bootstrap.Modal(document.getElementById('optionModal'));
    const form = document.getElementById('optionForm');
    const questionId = document.getElementById('question_id').value;

    // Add Question button
    addBtn.addEventListener('click', () => {
        form.reset();
        document.getElementById('option_id').value = '';
        document.getElementById('optionModalLabel').innerText = 'Add Option';
        optionModal.show();
    });

    // Edit Question button
    document.querySelectorAll('.editOptionBtn').forEach(btn => {
        btn.addEventListener('click', async () => {
            const id = btn.dataset.id;

            try {
                const res = await fetch(`/option/${id}/edit`);
                const data = await res.json();

                document.getElementById('option_id').value = data.id;
                document.getElementById('option_text').value = data.option_text;
                document.getElementById('correctOption').checked = data.is_correct;
                document.getElementById('is_active').checked = data.is_active;

                document.getElementById('optionModalLabel').innerText = 'Edit Option';
                optionModal.show();
            } catch (error) {
                console.error('Error loading option:', error);
                alert('Failed to load option.');
            }
        });
    });

    // Save (Add or Update)
    form.addEventListener('submit', async (e) => {
        e.preventDefault();

        const id = document.getElementById('option_id').value;
        const url = id ? `/option/${id}/update` : `/option/store`;
        const formData = new FormData();
        formData.append('_token', document.querySelector('input[name="_token"]').value);
        formData.append('question_id', questionId);
        formData.append('option_text', document.getElementById('option_text').value);
        formData.append('is_correct', document.getElementById('correctOption').checked ? 1 : 0);
        formData.append('is_active', document.getElementById('is_active').checked ? 1 : 0);

        try {
            const res = await fetch(url, { 
                method: 'POST', 
                body: formData 
            });
            const data = await res.json();

            if (data.success) {
                alert(data.message);
                optionModal.hide();
                location.reload();
            } else {
                console.error('Error saving option:', data.message);
                alert('Failed to save option.');
            }
        } catch (error) {
            console.error('Error saving option:', error);
            alert('Unable to save option.');
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
                    const response = await fetch(`/option/${id}/toggle-status`, {
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