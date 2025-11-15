@extends('layouts.app')

@section('content')
<div class="container mt-4">
    
    <h2>Class Enrollment: {{ $class->title }}</h2>
    <p>{{ $class->description }}</p>

    <div class="d-flex justify-content-between align-items-center my-3">
        <h4>Enrolled Students</h4>
        <button class="btn btn-primary" id="addStudentBtn" data-class-id="{{ $class->id }}">
            Add / Remove Students
        </button>
    </div>

    <table class="table table-bordered">
        <thead class="table-light">
            <tr>
                <th>#</th>
                <th>Student Name</th>
                <th>Email</th>
                <th>Status</th>
                <th>Progress (%)</th>
                <th>Enrolled At</th>
            </tr>
        </thead>
        <tbody>
            @forelse($class->students as $index => $student)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $student->name }}</td>
                    <td>{{ $student->email }}</td>
                    <td>
                        @php
                            $status = $student->pivot->status;
                            $badgeClass = match($status) {
                                'active' => 'success',
                                'completed' => 'primary',
                                'dropped' => 'secondary',
                                default => 'dark', // fallback for unexpected values
                            };
                        @endphp
                        <span class="badge bg-{{ $badgeClass }}">
                            {{ ucfirst($status) }}
                        </span>
                    </td>
                    <td>{{ $student->pivot->progress }}%</td>
                    <td>{{ \Carbon\Carbon::parse($student->pivot->enrolled_at)->format('Y-m-d') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center text-muted">No students enrolled yet.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- <div class="d-flex justify-content-center">
            {{ $students->links() }}
        </div> --}}
</div>


<!-- Add / Remove Students Modal -->
<div class="modal fade" id="studentModal" tabindex="-1" aria-labelledby="studentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form id="studentForm">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="studentModalLabel">Manage Students for {{ $class->name }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>Select</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Currently Enrolled</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($students as $student)
                                <tr>
                                    <td>
                                        <input type="checkbox"
                                               name="student_ids[]"
                                               value="{{ $student->id }}"
                                               {{ optional($class->students->firstWhere('id', $student->id))->pivot?->status === 'active' ? 'checked' : ''  }}>
                                    </td>
                                    <td>{{ $student->name }}</td>
                                    <td>{{ $student->email }}</td>
                                    <td>
                                        {{-- @php
                                            $statusEach = $student->pivot->status;
                                            $badgeClassEach = match($statusEach) {
                                                'active' => 'success',
                                                'completed' => 'primary',
                                                'dropped' => 'secondary',
                                                default => 'dark', // fallback for unexpected values
                                            };
                                        @endphp --}}
                                        @if($class->students->contains($student->id))
                                            <span class="badge bg-success">Yes</span>
                                            {{-- <span class="badge bg-{{ $badgeClassEach }}">
                                                {{ ucfirst($statusEach) }}
                                            </span> --}}
                                        @else
                                            <span class="badge bg-secondary">No</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const modal = new bootstrap.Modal(document.getElementById('studentModal'));
    const addBtn = document.getElementById('addStudentBtn');
    const form = document.getElementById('studentForm');
    const classId = addBtn.dataset.classId;

    // Show modal
    addBtn.addEventListener('click', () => modal.show());

    // Save enrollment changes
    form.addEventListener('submit', async (e) => {
        e.preventDefault();

        const formData = new FormData(form);
        formData.append('_token', '{{ csrf_token() }}');

        try {
            const res = await fetch(`/admin/enrollments/class/${classId}/update`, {
                method: 'POST',
                body: formData
            });

            const data = await res.json();
            if (data.success) {
                alert('Enrollments updated successfully!');
                modal.hide();
                location.reload();
            } else {
                alert('Failed to update enrollments.');
            }
        } catch (err) {
            console.error('Error:', err);
            alert('Something went wrong while saving.');
        }
    });
});
</script>
@endpush

