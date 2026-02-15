@extends('layouts.admin_app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/offcanvas.css') }}">
@endpush

@section('content')
    <div class="container-fluid d-flex justify-content-center" style="width: 100%;padding: 0px 24px;">
        <div class="alert alert-success text-center d-none z-3 alert-dismissible" role="alert" id="successMessage" style="border: 1px solid #0C6D38;position: absolute;background: #98f2c0;width: 50%;"><button class="btn-close" type="button" aria-label="Close" data-bs-dismiss="alert" id="close_alert"></button><i class="icon ion-checkmark-round me-1"></i><span style="color: #0C6D38 !important;">Record Added Successfully</span></div>
        <div class="row justify-content-center" style="margin: 0px;width: 100%;">
            <div class="col-xl-10 col-xxl-9" style="width: 100%;">
                <div class="card shadow">
                    <div class="card-header d-flex flex-wrap justify-content-center align-items-center justify-content-sm-between gap-3" style="padding: 8px 16px;">
                        <div class="row" style="margin: 0px;width: 100%;">
                            <div class="col" style="padding: 0px;">
                                <h1 class="d-inline-block" style="margin: 0px 0px;margin-bottom: 0px;height: 100%;padding: 5px 0px;">Lesson Management</h1>
                                <p class="d-inline-block invisible" style="margin: 0px 10px;">course name</p>
                            </div>
                            <div class="col-12 justify-content-end align-content-center" style="padding: 0px;width: initial;"><button class="btn btn-primary" type="button" style="width: 95px;font-weight: bold;color: rgb(255,255,255);background: rgb(78,115,223);border-width: 0px;" data-bs-target="#AddLesson" data-bs-toggle="offcanvas"><i class="fas fa-plus-square" style="border-color: rgb(255,255,255);color: rgb(255,255,255);background: rgba(255,255,255,0);font-size: 18px;"></i>&nbsp; Add</button></div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive text-break">
                            <table class="table table-striped table-hover" id="example">
                                <thead>
                                    <tr>
                                        <th class="text-nowrap">Lesson ID</th>
                                        <th class="text-nowrap">Lesson Name</th>
                                        <th class="text-nowrap">Description</th>
                                        <th class="text-nowrap">Class Name</th>
                                        <th class="text-nowrap">Content Type</th>
                                        <th class="text-nowrap">Duration</th>
                                        <th class="text-nowrap text-start">Sequence Order</th>
                                        <th class="text-nowrap">Status</th>
                                        <th class="text-nowrap text-start text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($lessons as $lesson)
                                        <tr style="max-width: 49px;">
                                            <td class="text-truncate" style="max-width: 200px;">{{ $lesson->id }}</td>
                                            <td class="text-truncate" style="max-width: 200px;">{{ $lesson->title }}</td>
                                            <td class="text-break" style="max-width: 50px;">{{ Str::limit($lesson->description, 50) }}</td>
                                            <td class="text-break" style="max-width: 50px;">{{ $lesson->class->title ?? 'N/A' }}</td>
                                            
                                            <td>{{$lesson->content_type}}</td>
                                            <td>{{ $lesson->duration ?? 'N/A' }} hrs</td>
                                            <td class="text-start" contenteditable="true">
                                                <div id="sequenceWrapper">
                                                    <span class="sequenceLabel">{{ $lesson->sequence }}</span>
                                                    <input type="hidden" name="sequences[{{ $lesson->id }}][id]" value="{{ $lesson->id }}">
                                                    <input class="editSequence" name="sequences[{{ $lesson->id }}][sequence]" type="number" value="{{ $lesson->sequence }}" defaultValue="{{ $lesson->sequence }}" style="display:none;"></input>
                                                </div>
                                            </td>
                                            <td class="status-cell">
                                                @if($lesson->is_active)
                                                    <span class="badge bg-success">Active</span>
                                                @else
                                                    <span class="badge bg-secondary">Inactive</span>
                                                @endif
                                            </td>
                                            <td class="text-nowrap text-start text-center">
                                                <a class="btn btn-dark" role="button" style="width: 25px;height: 25px;padding: 3px 3px;text-align: center;margin: 0px 3px;background: rgba(242,242,242,0);border-style: none;" target="_blank" href="{{ $lesson->source_url }}">
                                                    <i class="material-icons text-dark" id="showAlertBtn" style="font-size: 19px;--bs-primary: #4e73df;--bs-primary-rgb: 78,115,223;color: rgb(255,255,255);">remove_red_eye</i>
                                                </a>
                                                <button class="btn btn-dark editBtn" data-id="{{ $lesson->id }}" role="button" style="width: 25px;height: 25px;padding: 3px 3px;text-align: center;margin: 0px 5px;background: rgba(242,242,242,0);border-style: none;">
                                                    <i class="material-icons text-dark" id="showAlertBtn-5" style="font-size: 19px;--bs-primary: #4e73df;--bs-primary-rgb: 78,115,223;color: rgb(255,255,255);">edit</i>
                                                </button>
                                                <button class="btn btn-dark toggleStatus" data-id="{{ $lesson->id }}" role="button" style="width: 25px;height: 25px;padding: 3px 3px;text-align: center;margin: 0px 3px;background: rgba(242,242,242,0);border-style: none;">
                                                    <i class="material-icons text-dark" id="showAlertBtn-6" style="font-size: 19px;--bs-primary: #4e73df;--bs-primary-rgb: 78,115,223;color: rgb(255,255,255);">delete_forever</i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer"></div>
                </div>
            </div>
        </div>
    </div>
@endsection

    <div class="modal fade" role="dialog" tabindex="-1" id="confirmStatusModal">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Delete Alert!</h4>
                    <button class="btn-close" type="button" aria-label="Close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p id="lessonActivation"></p>
                </div>
                <div class="modal-footer">
                    <form id="updateLessonStatus">
                        @csrf
                        <input type="hidden" id="lessonStatusId" readonly>
                        <button class="btn btn-primary" id="showAlertBtn-7" type="submit" data-bs-target="#modal-2" data-bs-toggle="modal" data-bs-dismiss="modal" style="background: rgb(231,74,59);">Yes</button>
                        <button class="btn btn-light" type="button" data-bs-dismiss="modal" style="background: rgb(13,110,253);color: rgb(255,255,255);">No</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="offcanvas offcanvas-end lp-offcanvas" tabindex="-1" id="AddLesson" aria-labelledby="ocAddLessonLabel">
        <div class="oc-header">
            <div class="d-flex justify-content-between align-items-start gap-2">
                <div>
                    <h5 id="ocAddLessonLabel">Add New Lesson</h5>
                </div><button class="btn-close mt-1 btn-close-white" type="button" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
        </div>
        <div class="offcanvas-body p-3 p-sm-4">
            <form method="POST" action="{{route('lesson.store')}}" enctype="multipart/form-data">
                @csrf
                <label class="form-label">Upload File <span class="hint">(Image &amp; PDF only)</span></label><input class="form-control" type="file" accept="image/*,application/pdf" id="uploadFile">
                <div class="mt-2 hint"></div>
                <div class="divider"></div>
                <label class="form-label mt-1">Content Type</label>
                <select name="content_type" id="content_type" class="form-select @error('content_type') is-invalid @enderror">
                    <option value="">-- Select Type --</option>
                    @foreach($contentTypes as $contentType)
                        <option value="{{ $contentType->value }}"
                            {{ old('content_type') == $contentType->value ? 'selected' : '' }}>
                            {{ ucfirst($contentType->name) }}
                        </option>
                    @endforeach
                </select>
                <label class="form-label mt-3">Source URL</label>
                <input class="form-control" name='source_url' type="text" id="sourceUrl" placeholder="Paste video ID">
                <div class="mt-3"><label class="form-label">Class Name</label>
                    
                    <select name="class_id" id="class_id" class="form-select" required>
                        <option value="">-- Choose a Class --</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}">{{ $class->title }}</option>
                        @endforeach
                    </select>
                      
                    <div class="mt-1 hint">
                        <span>Classes will filter based on selected course.</span>
                    </div>
                </div>
                <label class="form-label mt-3">Lesson Name</label>
                <input class="form-control" name='title' type="text" id="lessonName" placeholder="e.g. Feng Shui" required="">
                <label class="form-label mt-3">Duration</label>
                <div class="duration-grid">
                    <input name='duration' class="form-control form-control" type="number" id="durHours" min="0" placeholder="0">
                    <span class="hint">hours</span>
                    <input class="form-control form-control" type="number" id="durMins" max="59" min="0" placeholder="0">
                    <span class="hint">mins</span>
                </div>
                <label class="form-label mt-3">Description</label>
                <textarea class="form-control form-control" name='description' id="description" placeholder="Course Description" rows="4"></textarea>
                <div class="divider"></div>
                <div class="d-flex justify-content-end gap-2">
                    <button class="btn btn-outline-light" type="button" data-bs-dismiss="offcanvas">Cancel</button>
                    <button class="btn btn-primary" id="btnAdd" type="submit">Add</button>
                </div>
                <div class="mt-3 small" id="result" style="color:rgba(229,231,235,.8);display:none;"></div>
            </form>
        </div>
    </div>
    <div class="offcanvas offcanvas-end lp-offcanvas" tabindex="-1" id="editLessonModal" aria-labelledby="ocAddLessonLabel">
        <div class="oc-header">
            <div class="d-flex justify-content-between align-items-start gap-2">
                <div>
                    <h5 id="ocAddLessonLabel-1">Edit Lesson</h5>
                </div><button class="btn-close mt-1 btn-close-white" type="button" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
        </div>
        <div class="offcanvas-body p-3 p-sm-4">
            <form id="updateLessonForm">
                @csrf
                <input type="hidden" id="lesson_id">
                <label class="form-label">Upload File <span class="hint">(Image &amp; PDF only)</span></label>
                <input class="form-control" type="file" id="editFile" accept="image/*,application/pdf">
                <div class="mt-2 hint"></div>
                <div class="divider"></div>
                <label class="form-label mt-1">Content Type</label>
                <select id="content_type_edit" name="content_type" class="form-select"></select>
                <label class="form-label mt-3">Source URL</label>
                <input class="form-control" type="text" id="source_url_Edit" placeholder="Paste video ID">
                <div class="mt-3">
                    <label class="form-label">Class Name</label>
                    <div class="search-dd" id="classDD-1">
                        <input class="form-control form-control" type="text" autocomplete="off" disabled="" id='class_name' placeholder="Search class..." required="" readonnly>
                        <input type="hidden" id="classId-1">
                        <div class="invalid-msg">
                            <span>Please select a class.</span>
                        </div>
                        <div class="dd-panel">
                            <div class="dd-search">
                                <input class="form-control form-control" type="text" id="classSearch-1" placeholder="Type to filter..."></div>
                            <div class="dd-list" id="classList-1"></div>
                        </div>
                    </div>
                    <div class="mt-1 hint">
                        <span>Classes will filter based on selected course.</span>
                    </div>
                </div>
                <label class="form-label mt-3">Lesson Name</label>
                <input class="form-control" type="text" id="lesson_title" placeholder="e.g. Feng Shui" required="">
                <label class="form-label mt-3">Duration</label>
                <div class="duration-grid">
                    <input class="form-control form-control" type="number" id="lesson_duration" min="0" placeholder="0">
                    <span class="hint">hours</span>
                    <input class="form-control form-control" type="number" id="durMins-1" max="59" min="0" placeholder="0">
                    <span class="hint">mins</span>
                </div>
                <label class="form-label mt-3">Description</label>
                <textarea class="form-control form-control" id="lesson_description" placeholder="Course Description" rows="4"></textarea>
                <div class="divider"></div>
                <div class="d-flex justify-content-end gap-2">
                    <button class="btn btn-outline-light" type="button" data-bs-dismiss="offcanvas">Cancel</button>
                    <button class="btn btn-primary" id="btnEdit" type="submit">Save</button>
                </div>
                <div class="mt-3 small" id="editresult" style="color:rgba(229,231,235,.8);display:none;"></div>
            </form>
        </div>
    </div>
    
    @push('scripts')
    <script src="{{ asset('js/Alert.js') }}"></script>
    <script src="{{ asset('js/lessonoffcanvas.js') }}"></script>
    <script>

    $('#exampleppp').DataTable({
        "order": []
    });
    const tableBody = document.querySelector('#example tbody'); 

    document.addEventListener("DOMContentLoaded", () => {
        //const tableBody = document.querySelector('#example tbody'); 

        //editButtons.forEach(btn => {
            //btn.addEventListener("click", async () => {
            tableBody.addEventListener("click", async (event) => {
                const modal = new bootstrap.Offcanvas(document.getElementById('editLessonModal'));

                const btn = event.target.closest(".editBtn");
            
                if (!btn) return; // Exit if something else was clicked

                event.preventDefault(); 
                event.stopPropagation();

                const id = btn.dataset.id;

                const response = await fetch(`/lesson/${id}/edit`);
                const data = await response.json();

                const lesson = data.lesson;
                const classtitle = data.class_title;
                const contentTypes = data.content_types;
                const fileUrl = data.fileUrl;

                document.getElementById("lesson_id").value = lesson.id;
                document.getElementById("lesson_title").value = lesson.title;
                document.getElementById("lesson_description").value = lesson.description || '';
                document.getElementById("lesson_duration").value = lesson.duration || '';
                document.getElementById("source_url_Edit").value = lesson.source_url || '';
                document.getElementById("class_name").value = classtitle || '';

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

                // // Show file name or video URL depending on content type
                // const fileGroup = document.getElementById("document_upload_edit");
                // const videoGroup = document.getElementById("video_url_edit");
                // const videoInput = document.getElementById("video_url_update");
                // const filePreview = document.getElementById("current_file_preview");
                // const videoPreview = document.getElementById("current_video_preview");

                // if (lesson.content_type === "Document") {
                //     fileGroup.style.display = "block";
                //     videoGroup.style.display = "none";
                //     videoPreview.innerHTML = '';
                //     // Show current file if exists
                //     if (fileUrl) {
                //         filePreview.innerHTML = `
                //             <p>Current File:</p>
                //             <a href="${fileUrl}" target="_blank" class="btn btn-sm btn-outline-primary">
                //                 View Current Document
                //             </a>
                //         `;
                //     } else {
                //         filePreview.innerHTML = `<p class="text-muted">No file uploaded yet.</p>`;
                //     }
                // } else if (lesson.content_type === "Video") {
                //     fileGroup.style.display = "none";
                //     videoGroup.style.display = "block";
                //     videoInput.value = lesson.source_url;
                //     filePreview.innerHTML = '';
                //     // Show current file if exists
                //     if (lesson.source_url) {
                //         videoPreview.innerHTML = `
                //             <p>Current Video:</p>
                //             <a href="${lesson.source_url}" target="_blank" class="btn btn-sm btn-outline-primary">
                //                 View Current Video
                //             </a>
                            
                //         `;
                //     } else {
                //         videoPreview.innerHTML = `<p class="text-muted">No Video give yet.</p>`;
                //     }
                // } else {
                //     fileGroup.style.display = "none";
                //     videoGroup.style.display = "none";
                // }

                modal.show();
            });
        //});

        // Update form submit
        document.getElementById("updateLessonForm").addEventListener("submit", async (e) => {
            e.preventDefault();

            const id = document.getElementById("lesson_id").value;
            const formData = new FormData();
            formData.append('_token', document.querySelector('input[name="_token"]').value);
            formData.append('title', document.getElementById("lesson_title").value);
            formData.append('description', document.getElementById("lesson_description").value);
            formData.append('duration', document.getElementById("lesson_duration").value);
            formData.append('content_type', document.getElementById("content_type_edit").value);
            formData.append('source_url', document.getElementById("source_url_Edit").value);

            const res = await fetch(`/lesson/${id}/update`, {
                method: 'POST',
                body: formData
            });
            
            //console.log(res);

            const data = await res.json();

            if (data.success) {
                location.reload(); // reload to refresh table
                alert(data.message);
                modal.hide();
            } else {
                alert("Something went wrong!");
            }
        });
    });

    // Toggle Status Is Active AJAX Request using jQuery 
    document.addEventListener('DOMContentLoaded', function() {
        //const buttons = document.querySelectorAll('.toggleStatus');
        

        //buttons.forEach(button => {
            //button.addEventListener('click', async function() {
            tableBody.addEventListener("click", async (event) => {
                const toggleStatusModel = new bootstrap.Modal(document.getElementById('confirmStatusModal'));

                const btn = event.target.closest(".toggleStatus");
            
                if (!btn) return; // Exit if something else was clicked

                event.preventDefault(); 
                event.stopPropagation();

                const id = btn.dataset.id;
                const row = btn.closest('tr');
                const badge = row.querySelector('.status-cell span');
                const isCurrentlyActive = badge.textContent.trim() === 'Active';
                const confirmMessage = `Are you sure you want to ${isCurrentlyActive ? 'deactivate' : 'activate'} this program?`;

                document.getElementById("lessonActivation").textContent = confirmMessage;
                document.getElementById("lessonStatusId").value = id;

                toggleStatusModel.show();
            });
        //});

        document.getElementById("updateLessonStatus").addEventListener("submit", async (e) => {
            e.preventDefault();
            try {
                const lesson_id = document.getElementById("lessonStatusId").value;

                const response = await fetch(`/lesson/${lesson_id}/toggle-status`, {
                    method: 'PATCH',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                    },
                });

                location.reload();
            } catch (error) {
                console.error('Error:', error);
            }
        });   

    });

    // const typeSelect = document.getElementById('content_type');
    // const fileInput = document.getElementById('document_upload');
    // const videoInput = document.getElementById('video_url');

    // typeSelect.addEventListener('change', function () {
    //     const type = this.value;

    //     if (type === 'Document') {
    //         fileInput.style.display = 'block';
    //         videoInput.style.display = 'none';
    //         fileInput.required = true;
    //         videoInput.required = false;
    //     } else if (type === 'Video') {
    //         videoInput.style.display = 'block';
    //         fileInput.style.display = 'none';
    //         videoInput.required = true;
    //         fileInput.required = false;
    //     }
    //     else {
    //         fileInput.style.display = 'none';
    //         videoInput.style.display = 'none';
    //         fileInput.required = false;
    //         videoInput.required = false;
    //     }
    // });

    // const sequenceBtn = document.getElementById("reorderSequence");
    // const sequenceWrapper = document.getElementById('wrapper');
    // const form = document.getElementById('updateSequence');

    // // Combined Logic: Toggle UI or Save Data
    // sequenceBtn.addEventListener("click", async function() {
    //     const isEditing = sequenceWrapper.classList.contains('is-editing');
    //     const sequenceLabels = document.querySelectorAll(".sequenceLabel");
    //     const editSequences = document.querySelectorAll(".editSequence");

    //     if (!isEditing) {
    //         // --- SWITCH TO EDIT MODE ---
    //         editSequences.forEach(el => el.style.display = 'block');
    //         sequenceLabels.forEach(el => el.style.display = 'none');
    //         sequenceWrapper.classList.add('is-editing');
    //         sequenceBtn.textContent = "Save Changes"; // UI feedback
    //     } 
    //     else {
    //         // --- SAVE DATA & SWITCH TO VIEW MODE ---
    //         const formData = new FormData(form);

            
    //         const response = await fetch('/lesson/sequenceUpdate', {
    //             method: 'POST',
    //             headers: {
    //                 'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
    //                 'Accept': 'application/json'
    //             },
    //             body: formData
    //         });

    //         const data = await response.json(); // Fixed "res" to "response"

    //         if (data.success) {
    //             // Reset UI on success
    //             editSequences.forEach(el => el.style.display = 'none');
    //             sequenceLabels.forEach(el => el.style.display = 'block');
    //             sequenceWrapper.classList.remove('is-editing');
    //             sequenceBtn.textContent = "Edit Sequence";
                
    //             alert('Updated successfully!');
    //             location.reload(); 
    //         } else {
    //             let errorMessages = Object.values(data.message).flat().join('\n');
    //             alert('Update failed. Reverting changes...');
    //             alert(errorMessages);
    //             // Optional: Reset inputs to default if save fails
    //             editSequences.forEach(el => el.value = el.defaultValue);
    //         }
    //     }
    // });
</script>
@endpush