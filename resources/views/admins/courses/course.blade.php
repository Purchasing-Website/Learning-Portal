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
                                <h1 class="d-inline-block" style="margin: 0px 0px;margin-bottom: 0px;width: auto;height: 100%;padding: 5px 0px;">Course Management</h1>
                                <p class="d-inline-block invisible" style="margin: 0px 10px;">Paragraph</p>
                            </div>
                            <div class="col-12 align-items-center align-content-center justify-content-xl-center justify-content-xxl-end" style="padding: 0px;width: initial;"><button class="btn btn-primary" type="button" style="width: 95px;font-weight: bold;color: rgb(255,255,255);background: rgb(78,115,223);border-width: 0px;" data-bs-target="#AddCourse" data-bs-toggle="offcanvas"><i class="fas fa-plus-square" style="border-color: rgb(255,255,255);color: rgb(255,255,255);background: rgba(255,255,255,0);font-size: 18px;"></i>&nbsp; Add</button></div>
                        </div>
                    </div>
                    <div class="card-body" style="padding: 0px;width: initial;">
                        <div class="table-responsive text-break">
                            <table class="table table-striped table-hover" id="example">
                                <thead>
                                    <tr>
                                        <th class="text-nowrap">Course ID</th>
                                        <th class="text-nowrap">Course Name</th>
                                        <th class="text-nowrap">Image</th>
                                        <th class="text-nowrap">Description</th>
                                        <th class="text-nowrap">Date Started</th>
                                        <th class="text-nowrap text-start">Total Students</th>
                                        <th class="text-nowrap">Status</th>
                                        <th class="text-nowrap text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ( $courses as $course)
                                        <tr style="max-width: 49px;">
                                        <td class="text-truncate" style="max-width: 200px;">{{ $course->id }}</td>
                                        <td class="text-truncate" style="max-width: 200px;">{{ $course->title }}</td>
                                        <td class="text-truncate" style="max-width: 200px;"><img class="img-fluid" width="299" height="180" style="max-width: 120px;max-height: 100px;" src="assets/img/OIP.webp"></td>
                                        <td class="text-break" style="max-width: 50px;">{{ $course->description }}</td>
                                        <td>{{ $course->created_at->format('Y-m-d') }}</td>
                                        <td class="text-start">{{$course->students_count}}</td>
                                        <td class="status-cell">
                                            @if($course->is_active)
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-secondary">Inactive</span>
                                            @endif
                                        </td>
                                        <td class="text-nowrap text-start text-center">
                                            <a class="btn btn-dark" role="button" style="width: 25px;height: 25px;padding: 3px 3px;text-align: center;margin: 0px 3px;background: rgba(242,242,242,0);border-style: none;" href="Class.html">
                                                <i class="material-icons text-dark" id="showAlertBtn" style="font-size: 19px;--bs-primary: #4e73df;--bs-primary-rgb: 78,115,223;color: rgb(255,255,255);" type="button">remove_red_eye</i>
                                            </a>
                                                <button class="btn btn-dark editBtn" id="editBtn" data-id="{{ $course->id }}" type="button" style="width: 25px;height: 25px;padding: 3px 3px;text-align: center;margin: 0px 5px;background: rgba(242,242,242,0);border-style: none;" data-bs-target="#EditCourse" data-bs-toggle="offcanvas"><i class="material-icons text-dark" id="showAlertBtn-5" style="font-size: 19px;--bs-primary: #4e73df;--bs-primary-rgb: 78,115,223;color: rgb(255,255,255);" type="button">edit</i>
                                                </button>
                                                <button class="btn btn-dark toggleStatus" data-id="{{ $course->id }}" type="button" style="width: 25px;height: 25px;padding: 3px 3px;text-align: center;margin: 0px 3px;background: rgba(242,242,242,0);border-style: none;" data-bs-target="#modal-1" data-bs-toggle="modal"><i class="material-icons text-dark" id="showAlertBtn-6" style="font-size: 19px;--bs-primary: #4e73df;--bs-primary-rgb: 78,115,223;color: rgb(255,255,255);" type="button">do_not_disturb_alt</i>
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
                    <p id="courseActivation"></p>
                </div>
                <div class="modal-footer">
                    <form id="updateCourseStatus">
                        @csrf
                        <input type="hidden" id="courseStatusId">
                        <button class="btn btn-primary" id="showAlertBtn-7" type="submit" data-bs-target="#modal-2" data-bs-toggle="modal" data-bs-dismiss="modal" style="background: rgb(231,74,59);">Yes</button>
                    </form>
                    <button class="btn btn-light" type="button" data-bs-dismiss="modal" style="background: rgb(13,110,253);color: rgb(255,255,255);">No</button>
                </div>
            </div>
        </div>
    </div>
    <div class="offcanvas offcanvas-end lp-offcanvas" tabindex="-1" id="AddCourse" aria-labelledby="ocAddLessonLabel">
        <div class="oc-header">
            <div class="d-flex justify-content-between align-items-start gap-2">
                <div>
                    <h5 id="ocAddLessonLabel">Add New Course</h5>
                </div><button class="btn-close mt-1 btn-close-white" type="button" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
        </div>
        <div class="offcanvas-body p-3 p-sm-4">
            <form method="POST" action="{{route('course.store')}}">
                @csrf
                <label class="form-label">Upload Cover Image&nbsp;<span class="hint"></span></label>
                <input class="form-control" type="file" accept="image/*,application/pdf" id="uploadFile">
                <div class="mt-3">
                    <label class="form-label">Course Name</label>
                    <div class="search-dd">
                        <input class="form-control form-control" name='title' type="text" id="courseInput" autocomplete="off" placeholder="Search course..." required="">
                        <input type="hidden" id="courseId" readonly></div>
                </div>
                <label class="form-label mt-3">Description</label>
                <textarea class="form-control form-control" name='description' id="description" placeholder="Course Description" rows="4"></textarea>
                <div class="divider"></div>
                <div class="d-flex justify-content-end gap-2">
                    <button class="btn btn-outline-light" type="button" data-bs-dismiss="offcanvas">Cancel</button>
                    <button class="btn btn-primary" id="btnAdd" type="submit">Add</button></div>
                <div class="mt-3 small" id="result" style="color:rgba(229,231,235,.8);display:none;"></div>
            </form>
        </div>
    </div>
    <div class="offcanvas offcanvas-end lp-offcanvas" tabindex="-1" id="editCourseModal" aria-labelledby="ocAddLessonLabel">
        <div class="oc-header">
            <div class="d-flex justify-content-between align-items-start gap-2">
                <div>
                    <h5 id="ocAddLessonLabel-1">Edit Course</h5>
                </div>
                <button class="btn-close mt-1 btn-close-white" type="button" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
        </div>
        <div class="offcanvas-body p-3 p-sm-4">
            <form id="updateCourseForm">
                @csrf
                <input type="hidden" id="course_id" readonly>
                <label class="form-label">Upload Cover Image&nbsp;<span class="hint"></span></label>
                <input class="form-control" type="file" accept="image/*,application/pdf" id="uploadFile-1">
                <div class="mt-3">
                    <label class="form-label">Course Name</label>
                    <div class="search-dd">
                        <input id="course_title" class="form-control form-control" type="text" autocomplete="off" placeholder="Search course..." required="">
                        <input type="hidden" id="courseId-1">
                    </div>
                </div>
                <label class="form-label mt-3">Description</label>
                <textarea class="form-control form-control" id="course_description" placeholder="Course Description" rows="4"></textarea>
                <div class="divider"></div>
                <div class="d-flex justify-content-end gap-2">
                    <button class="btn btn-outline-light" type="button" data-bs-dismiss="offcanvas">Cancel</button>
                    <button class="btn btn-primary" id="btnAdd-1" type="submit">Save</button>
                </div>
                <div class="mt-3 small" id="result-1" style="color:rgba(229,231,235,.8);display:none;"></div>
            </form>
        </div>
    </div>
    @push('scripts')
    <script src="{{ asset('js/Alert.js') }}"></script>
    <script src="{{ asset('js/course_offcanvas.js') }}"></script>
    <script>

    const tableBody = document.querySelector('#example tbody'); 

    document.addEventListener("DOMContentLoaded", () => {
        //const editButtons = document.querySelectorAll(".editBtn");
        //const modal = new bootstrap.Offcanvas(document.getElementById('editCourseModal'));

        //editButtons.forEach(btn => {
            //btn.addEventListener("click", async () => {
            tableBody.addEventListener("click", async (event) => {
                const modal = new bootstrap.Offcanvas(document.getElementById('editCourseModal'));

                const btn = event.target.closest(".editBtn");
            
                if (!btn) return; // Exit if something else was clicked

                const id = btn.dataset.id;

                const response = await fetch(`/course/${id}/edit`);
                const course = await response.json();

                document.getElementById("course_id").value = course.id;
                document.getElementById("course_title").value = course.title;
                document.getElementById("course_description").value = course.description || '';
                //document.getElementById("course_duration").value = course.duration || '';

                modal.show();
            });
        //});

        // Update form submit
        document.getElementById("updateCourseForm").addEventListener("submit", async (e) => {
            e.preventDefault();

            const id = document.getElementById("course_id").value;
            const formData = new FormData();
            formData.append('_token', document.querySelector('input[name="_token"]').value);
            formData.append('title', document.getElementById("course_title").value);
            formData.append('description', document.getElementById("course_description").value);
            //formData.append('duration', document.getElementById("course_duration").value);

            const res = await fetch(`/course/${id}/update`, {
                method: 'POST',
                body: formData
            });

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
        const buttons = document.querySelectorAll('.toggleStatus');
        const toggleStatusModel = new bootstrap.Modal(document.getElementById('confirmStatusModal'));

        //buttons.forEach(button => {
            //button.addEventListener('click', async function() {
                tableBody.addEventListener("click", async (event) => {
                const toggleStatusModel = new bootstrap.Modal(document.getElementById('confirmStatusModal'));

                const btn = event.target.closest(".toggleStatus");
            
                if (!btn) return; // Exit if something else was clicked

                const id = btn.dataset.id;
                const row = btn.closest('tr');
                const badge = row.querySelector('.status-cell span');
                const isCurrentlyActive = badge.textContent.trim() === 'Active';
                const confirmMessage = `Are you sure you want to ${isCurrentlyActive ? 'deactivate' : 'activate'} this course?`;

                document.getElementById("courseActivation").textContent = confirmMessage;
                document.getElementById("courseStatusId").value = id;

                toggleStatusModel.show();
            });
        //});

        document.getElementById("updateCourseStatus").addEventListener("submit", async (e) => {
            e.preventDefault();
            try {
                const course_id = document.getElementById("courseStatusId").value;

                const response = await fetch(`/course/${course_id}/toggle-status`, {
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
    </script>
    @endpush