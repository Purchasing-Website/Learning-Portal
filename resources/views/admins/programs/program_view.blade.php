@extends('layouts.app')

@section('content')
<div class="container">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    {{-- @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif --}}
    <div class="row justify-content-center">
        <div class="col-md-8">

            {{-- form create program --}}
            <form method="POST" action="{{route('program.store')}}">
                @csrf
                <div class="mb-3">
                    <label for="programName" class="form-label">Program Name</label>
                    <input type="text" class="form-control @error('title') is-invalid @enderror" name='title' id="programName"  value="{{ old('title') }}" placeholder="Enter program name">
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="programDescription" class="form-label">Program Description</label>
                    <textarea class="form-control" name='description' id="programDescription" rows="3" placeholder="Enter program description"></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Create Program</button>
            </form> 
            
        </div>
            <table class="table table-bordered table-striped align-middle">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Created By</th>
                    <th>Status</th>
                    <th>Created At</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($programs as $program)
                    <tr>
                        <td>{{ $loop->iteration + ($programs->currentPage() - 1) * $programs->perPage() }}</td>
                        <td>{{ $program->title }}</td>
                        <td>{{ Str::limit($program->description, 50) }}</td>
                        <td>{{ $program->created_by ?? 'N/A' }}</td>
                        <td class="status-cell">
                            @if($program->is_active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </td>
                        <td>{{ $program->created_at->format('Y-m-d') }}</td>
                        <td>
                            <button class="btn btn-sm btn-primary editBtn" id='editBtn'
                                    data-id="{{ $program->id }}">
                                Edit
                            </button>
                        
                            <button class="btn btn-warning btn-sm toggleStatus" data-id="{{ $program->id }}">
                            {{ $program->is_active ? 'Deactivate' : 'Activate' }}
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">No programs found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Pagination -->
        <div class="d-flex justify-content-center">
            {{ $programs->links() }}
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="editProgramModal" tabindex="-1">
  <div class="modal-dialog">
    <form id="updateProgramForm">
      @csrf
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Edit Program</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
            <input type="hidden" id="program_id">

            <div class="mb-3">
                <label for="program_name" class="form-label">Program Name</label>
                <input type="text" class="form-control" id="program_name" required>
            </div>

            <div class="mb-3">
                <label for="program_description" class="form-label">Description</label>
                <textarea class="form-control" id="program_description"></textarea>
            </div>

            <div class="mb-3">
                <label for="program_duration" class="form-label">Duration</label>
                <input type="text" class="form-control" id="program_duration">
            </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success">Update</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="confirmStatusModal" tabindex="-1" aria-labelledby="confirmStatusLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="confirmStatusLabel">Confirm Action</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p id="confirmMessage">Are you sure you want to change this program’s status?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary" id="confirmToggleBtn">Yes, Confirm</button>
      </div>
    </div>
  </div>
</div>


@endsection

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", () => {
    const editButtons = document.querySelectorAll(".editBtn");
    const modal = new bootstrap.Modal(document.getElementById('editProgramModal'));

    editButtons.forEach(btn => {
        btn.addEventListener("click", async () => {
            const id = btn.dataset.id;

            const response = await fetch(`/program/${id}/edit`);
            const program = await response.json();

            document.getElementById("program_id").value = program.id;
            document.getElementById("program_name").value = program.title;
            document.getElementById("program_description").value = program.description || '';
            document.getElementById("program_duration").value = program.duration || '';

            modal.show();
        });
    });

    // Update form submit
    document.getElementById("updateProgramForm").addEventListener("submit", async (e) => {
        e.preventDefault();

        const id = document.getElementById("program_id").value;
        const formData = new FormData();
        formData.append('_token', document.querySelector('input[name="_token"]').value);
        formData.append('title', document.getElementById("program_name").value);
        formData.append('description', document.getElementById("program_description").value);
        formData.append('duration', document.getElementById("program_duration").value);

        const res = await fetch(`/program/${id}/update`, {
            method: 'POST',
            body: formData
        });

        const data = await res.json();

        if (data.success) {
            alert(data.message);
            modal.hide();
            location.reload(); // reload to refresh table
        } else {
            alert("Something went wrong!");
        }
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
                    const response = await fetch(`/program/${id}/toggle-status`, {
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