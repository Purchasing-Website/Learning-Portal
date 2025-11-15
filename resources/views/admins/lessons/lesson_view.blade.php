@extends('layouts.app')

@section('content')
<div class="container">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="row justify-content-center">
        <div class="col-md-8">

            {{-- form create lesson --}}
            <form method="POST" action="{{route('lesson.store')}}" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label for="lessonName" class="form-label">Lesson Name</label>
                    <input type="text" class="form-control" name='title' id="lessonName" placeholder="Enter lesson name" required>
                </div>
                <div class="mb-3">
                    <label for="lessonDescription" class="form-label">Lesson Description</label>
                    <textarea class="form-control" name='description' id="lessonDescription" rows="3" placeholder="Enter lesson description"></textarea>
                </div>
               {{-- Select Class --}}
                <div class="mb-3">
                    <label for="class_id" class="form-label">Select Class</label>
                    <select name="class_id" id="class_id" class="form-select" required>
                        <option value="">-- Choose a Class --</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}">{{ $class->title }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="lessonDuration" class="form-label">Lesson Duration</label>
                    <input type="text" class="form-control" name='duration' id="lessonDuration" placeholder="Enter lesson duration">
                </div>
                <div class="mb-3" >
                    <label for="content_type" class="form-label">Content Type</label>
                    <select name="content_type"
                            id="content_type"
                            class="form-select @error('content_type') is-invalid @enderror">
                        <option value="">-- Select Type --</option>
                        @foreach($contentTypes as $contentType)
                            <option value="{{ $contentType->value }}"
                                {{ old('content_type') == $contentType->value ? 'selected' : '' }}>
                                {{ ucfirst($contentType->name) }}
                            </option>
                        @endforeach
                    </select>
                    @error('content_type')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div id="document_upload" class="mb-3" style="display:none;">
                    <label>Upload File</label>
                    <input type="file" name="file" accept=".pdf,.doc,.docx,.ppt,.pptx">
                    @error('file') <div class="text-danger">{{ $message }}</div> @enderror
                </div>
                <div class="mb-3" id="video_url" style="display:none;">
                    <label for="source_url" class="form-label">Source URL</label>
                    <input type="url" class="form-control @error('source_url') is-invalid @enderror" name='source_url' id="source_url" placeholder="Enter source URL">
                    @error('soure_url')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div>
                    <label>Background Image (optional)</label>
                    <input type="file" name="image" accept="image/*">
                    @error('image') <div class="text-danger">{{ $message }}</div> @enderror
                </div>
                <button type="submit" class="btn btn-primary">Create Lesson</button>
            </form> 
            
        </div>
            <table class="table table-bordered table-striped align-middle">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Class</th>
                    <th>Created By</th>
                    <th>Status</th>
                    <th>Created At</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($lessons as $lesson)
                    <tr>
                        <td>{{ $loop->iteration + ($lessons->currentPage() - 1) * $lessons->perPage() }}</td>
                        <td>{{ $lesson->title }}</td>
                        <td>{{ Str::limit($lesson->description, 50) }}</td>
                        <td>{{ $lesson->class->title ?? 'N/A' }}</td>
                        <td>{{ $lesson->created_by ?? 'N/A' }}</td>
                        <td class="status-cell">
                            @if($lesson->is_active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </td>
                        <td>{{ $lesson->created_at->format('Y-m-d') }}</td>
                        <td>
                            <button class="btn btn-sm btn-primary editBtn" id='editBtn'
                                    data-id="{{ $lesson->id }}">
                                Edit
                            </button>
                        
                            <button class="btn btn-warning btn-sm toggleStatus" data-id="{{ $lesson->id }}">
                            {{ $lesson->is_active ? 'Deactivate' : 'Activate' }}
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">No lessons found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Pagination -->
        <div class="d-flex justify-content-center">
            {{ $lessons->links() }}
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="editLessonModal" tabindex="-1">
  <div class="modal-dialog">
    <form id="updateLessonForm">
      @csrf
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Edit Lesson</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
            <input type="hidden" id="lesson_id">

            <div class="mb-3">
                <label for="lesson_title" class="form-label">Lesson Name</label>
                <input type="text" class="form-control" id="lesson_title" required>
            </div>

            <div class="mb-3">
                <label for="lesson_description" class="form-label">Description</label>
                <textarea class="form-control" id="lesson_description"></textarea>
            </div>

            <div class="mb-3">
                <label for="lesson_duration" class="form-label">Duration</label>
                <input type="text" class="form-control" id="lesson_duration">
            </div>
             <div class="mb-3">
                <label for="content_type">Content Type</label>
                <select id="content_type_edit" name="content_type" class="form-select"></select>
            </div>

            <div id="document_upload_edit" class="mb-3" style="display:none;">
                <div id="current_file_preview" class="mb-2"></div>
                <label for="file_upload">Upload File</label>
                <input type="file" id="file_upload" name="file_upload" class="form-control">
            </div>

            <div id="video_url_edit" class="mb-3" style="display:none;">
                <div id="current_video_preview" class="mb-2"></div>
                <label for="video_url_update">Video URL</label>
                <input type="url" id="video_url_update" name="source_url" class="form-control">
                
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
        <p id="confirmMessage">Are you sure you want to change this Lesson’s status?</p>
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
    const modal = new bootstrap.Modal(document.getElementById('editLessonModal'));

    editButtons.forEach(btn => {
        btn.addEventListener("click", async () => {
            const id = btn.dataset.id;

            const response = await fetch(`/lesson/${id}/edit`);
            const data = await response.json();

            const lesson = data.lesson;
            const contentTypes = data.content_types;
            const fileUrl = data.fileUrl;

            console.log(fileUrl);

            document.getElementById("lesson_id").value = lesson.id;
            document.getElementById("lesson_title").value = lesson.title;
            document.getElementById("lesson_description").value = lesson.description || '';
            document.getElementById("lesson_duration").value = lesson.duration || '';

            // FIX: Populate dropdown options
            const select = document.getElementById("content_type_edit");
            select.innerHTML = ""; // Clear existing options

            contentTypes.forEach((type) => {
                const option = document.createElement("option");
                option.value = type.value;
                option.textContent = type.name;

                if (lesson.content_type === type.value) {
                   option.selected = true;
                }
                select.appendChild(option);
            });

            // Show file name or video URL depending on content type
            const fileGroup = document.getElementById("document_upload_edit");
            const videoGroup = document.getElementById("video_url_edit");
            const videoInput = document.getElementById("video_url_update");
            const filePreview = document.getElementById("current_file_preview");
            const videoPreview = document.getElementById("current_video_preview");

            if (lesson.content_type === "Document") {
                fileGroup.style.display = "block";
                videoGroup.style.display = "none";
                videoPreview.innerHTML = '';
                // Show current file if exists
                if (fileUrl) {
                    filePreview.innerHTML = `
                        <p>Current File:</p>
                        <a href="${fileUrl}" target="_blank" class="btn btn-sm btn-outline-primary">
                            View Current Document
                        </a>
                    `;
                } else {
                    filePreview.innerHTML = `<p class="text-muted">No file uploaded yet.</p>`;
                }
            } else if (lesson.content_type === "Video") {
                fileGroup.style.display = "none";
                videoGroup.style.display = "block";
                videoInput.value = lesson.source_url;
                filePreview.innerHTML = '';
                // Show current file if exists
                if (lesson.source_url) {
                    videoPreview.innerHTML = `
                        <p>Current Video:</p>
                        <a href="${lesson.source_url}" target="_blank" class="btn btn-sm btn-outline-primary">
                            View Current Video
                        </a>
                        
                    `;
                } else {
                    videoPreview.innerHTML = `<p class="text-muted">No Video give yet.</p>`;
                }
            } else {
                fileGroup.style.display = "none";
                videoGroup.style.display = "none";
            }

            modal.show();
        });
    });

    // Update form submit
    document.getElementById("updateLessonForm").addEventListener("submit", async (e) => {
        e.preventDefault();

        const id = document.getElementById("lesson_id").value;
        const formData = new FormData();
        formData.append('_token', document.querySelector('input[name="_token"]').value);
        formData.append('title', document.getElementById("lesson_title").value);
        formData.append('description', document.getElementById("lesson_description").value);
        formData.append('duration', document.getElementById("lesson_duration").value);

        const res = await fetch(`/lesson/${id}/update`, {
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
                    const response = await fetch(`/lesson/${id}/toggle-status`, {
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

const typeSelect = document.getElementById('content_type');
const fileInput = document.getElementById('document_upload');
const videoInput = document.getElementById('video_url');

typeSelect.addEventListener('change', function () {
    const type = this.value;

    if (type === 'Document') {
        fileInput.style.display = 'block';
        videoInput.style.display = 'none';
        fileInput.required = true;
        videoInput.required = false;
    } else if (type === 'Video') {
        videoInput.style.display = 'block';
        fileInput.style.display = 'none';
        videoInput.required = true;
        fileInput.required = false;
    }
    else {
        fileInput.style.display = 'none';
        videoInput.style.display = 'none';
        fileInput.required = false;
        videoInput.required = false;
    }
});

const typeSelectEdit = document.getElementById('content_type_edit');
const fileInputEdit = document.getElementById('document_upload_edit');
const videoInputEdit = document.getElementById('video_url_edit');

typeSelectEdit.addEventListener('change', function () {
    const typeEdit = this.value;

    if (typeEdit === 'Document') {
        fileInputEdit.style.display = 'block';
        videoInputEdit.style.display = 'none';
        fileInputEdit.required = true;
        videoInputEdit.required = false;
    } else if (typeEdit === 'Video') {
        videoInputEdit.style.display = 'block';
        fileInputEdit.style.display = 'none';
        videoInputEdit.required = true;
        fileInputEdit.required = false;
    }
    else {
        fileInputEdit.style.display = 'none';
        videoInputEdit.style.display = 'none';
        fileInputEdit.required = false;
        videoInputEdit.required = false;
    }
});

</script>
@endpush