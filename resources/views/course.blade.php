@extends('layouts.Custom_app', ['title' => 'Class'])

@section('content')
    <div class="d-flex flex-column" id="content-wrapper">
        <div id="content">
            <nav class="navbar navbar-expand bg-dark shadow mb-4 topbar static-top navbar-light">
                <div class="container-fluid"><button class="btn btn-link d-md-none me-3 rounded-circle" id="sidebarToggleTop" type="button"><i class="fas fa-bars"></i></button>
                    <ul class="navbar-nav flex-nowrap ms-auto">
                        <li class="nav-item dropdown show d-sm-none no-arrow"><a class="dropdown-toggle nav-link" aria-expanded="true" data-bs-toggle="dropdown" href="#"><i class="fas fa-search"></i></a>
                            <div class="dropdown-menu show p-3 dropdown-menu-end animated--grow-in" data-bs-popper="none" aria-labelledby="searchDropdown">
                                <form class="w-100 me-auto navbar-search">
                                    <div class="input-group"><input class="bg-light form-control border-0 small" type="text" placeholder="Search for ...">
                                        <div class="input-group-append"><button class="btn btn-primary py-0" type="button"><i class="fas fa-search"></i></button></div>
                                    </div>
                                </form>
                            </div>
                        </li>
                        <li class="nav-item mx-1 dropdown no-arrow"></li>
                        <li class="nav-item mx-1 dropdown no-arrow">
                            <div class="shadow dropdown-list dropdown-menu dropdown-menu-end" aria-labelledby="alertsDropdown"></div>
                        </li>
                        <div class="d-none d-sm-block topbar-divider"></div>
                        <li class="nav-item dropdown no-arrow">
                            <div class="nav-item dropdown no-arrow"><a class="dropdown-toggle nav-link" aria-expanded="false" data-bs-toggle="dropdown" href="#"><span class="d-none d-lg-inline me-2 text-gray-600 small">Khye Shen</span><i class="far fa-user d-xl-flex justify-content-xl-center align-items-xl-center" style="font-size: 28px;width: 32px;height: 32px;"></i></a>
                                <div class="dropdown-menu shadow dropdown-menu-end animated--grow-in"><a class="dropdown-item" href="#"><i class="fas fa-user me-2 fa-sm fa-fw text-gray-400"></i>&nbsp;Profile</a>
                                    <div class="dropdown-divider"></div><a class="dropdown-item" href="#"><i class="fas fa-sign-out-alt me-2 fa-sm fa-fw text-gray-400"></i>&nbsp;Logout</a>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>
            <div class="container-fluid d-flex justify-content-center" style="width: 100%;padding: 0px 24px;">
                <div class="alert alert-success text-center d-none z-3 alert-dismissible" role="alert" id="successMessage" style="border: 1px solid #0C6D38;position: absolute;background: #98f2c0;width: 50%;"><button class="btn-close" type="button" aria-label="Close" data-bs-dismiss="alert" id="close_alert"></button><i class="icon ion-checkmark-round me-1"></i><span style="color: #0C6D38 !important;">Record Added Successfully</span></div>
                <div class="row justify-content-center" style="margin: 0px;width: 100%;">
                    <div class="col-xl-10 col-xxl-9" style="width: 100%;">
                        <div class="card shadow">
                            <div class="card-header d-flex flex-wrap justify-content-center align-items-center justify-content-sm-between gap-3" style="padding: 8px 16px;">
                                <div class="row" style="margin: 0px;width: 100%;">
                                    <div class="col-xl-10 col-xxl-10" style="padding: 0px;width: 60%;">
                                        <h1 class="d-inline-block" style="margin: 0px 0px;margin-bottom: 0px;width: auto;height: 100%;padding: 5px 0px;">Course Management</h1>
                                        <p class="d-inline-block invisible" style="margin: 0px 10px;">Paragraph</p>
                                    </div>
                                    <div class="col col-xxl-2 text-end d-xl-flex justify-content-xl-center align-items-xl-center justify-content-xxl-end" style="padding: 0px;width: 40%;">
                                        <div class="input-group" style="margin: 15px;width: 50%;"><input class="form-control" type="text" placeholder="Search" aria-label="Search" aria-describedby="button-addon2"><button class="btn btn-outline-secondary" type="button" id="button-addon2"><i class="fas fa-search"></i></button></div><button class="btn btn-primary" type="button" style="width: 95px;font-weight: bold;color: rgb(255,255,255);background: rgb(78,115,223);border-width: 0px;" data-bs-target="#offcanvas-1" data-bs-toggle="offcanvas"><i class="fas fa-plus-square" style="border-color: rgb(255,255,255);color: rgb(255,255,255);background: rgba(255,255,255,0);font-size: 18px;"></i>&nbsp; Add</button>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive text-break">
                                    <table class="table table-striped table-hover" id="example">
                                        <thead>
                                            <tr>
                                                <th class="text-nowrap">Course ID</th>
                                                <th class="text-nowrap">Course Name</th>
                                                <th class="text-nowrap">Image</th>
                                                <th class="text-nowrap">Description</th>
                                                <th class="text-nowrap">Program Name</th>
                                                <th class="text-nowrap">Date Started</th>
                                                <th class="text-nowrap text-start">Total Students</th>
                                                <th class="text-nowrap">Status</th>
                                                <th class="text-nowrap text-center">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($courses as $course)
                                                <tr style="max-width: 49px;">
                                                    <td class="text-truncate" style="max-width: 200px;">{{ $course->id }}</td>
                                                    <td class="text-truncate" style="max-width: 200px;">{{ $course->title }}</td>
                                                    <td class="text-truncate" style="max-width: 200px;"><img class="img-fluid" width="299" height="180" style="max-width: 120px;max-height: 100px;" src="assets/img/OIP.webp"></td>
                                                    <td class="text-break" style="max-width: 50px;">{{ Str::limit($course->description, 50) }}</td>
                                                    <td class="text-break" style="max-width: 50px;">{{ $course->program->title ?? 'N/A' }}</td>
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
                                                        <a class="btn btn-dark" role="button" style="width: 25px;height: 25px;padding: 3px 3px;text-align: center;margin: 0px 3px;background: rgba(242,242,242,0);border-style: none;" href="{{ route('class.index',$course->id) }}">
                                                            <i class="material-icons text-dark" id="showAlertBtn" style="font-size: 19px;--bs-primary: #4e73df;--bs-primary-rgb: 78,115,223;color: rgb(255,255,255);" type="button">remove_red_eye</i>
                                                        </a>
                                                        <button class="btn btn-dark editBtn" id='editBtn' role="button" data-id="{{ $course->id }}" style="width: 25px;height: 25px;padding: 3px 3px;text-align: center;margin: 0px 5px;background: rgba(242,242,242,0);border-style: none;">
                                                            <i class="material-icons text-dark" id="showAlertBtn-5" style="font-size: 19px;--bs-primary: #4e73df;--bs-primary-rgb: 78,115,223;color: rgb(255,255,255);" type="button">edit</i>
                                                        </button>
                                                        <button class="btn btn-dark toggleStatus" data-id="{{ $course->id }}" role="button" style="width: 25px;height: 25px;padding: 3px 3px;text-align: center;margin: 0px 3px;background: rgba(242,242,242,0);border-style: none;">
                                                            <i class="material-icons text-dark" id="showAlertBtn-6" style="font-size: 19px;--bs-primary: #4e73df;--bs-primary-rgb: 78,115,223;color: rgb(255,255,255);" type="button">do_not_disturb_alt</i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            @empty
                                                {{-- <tr>
                                                    <td colspan="9" class="text-center">No courses found.</td>
                                                </tr> --}}
                                            @endforelse
                                            
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="card-footer"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <footer class="bg-white sticky-footer">
            <div class="container my-auto">
                <div class="text-center my-auto copyright"><span>Hao Lin© Brand 2025</span></div>
            </div>
        </footer>
    </div>
    <a class="border rounded d-inline scroll-to-top" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>
    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvas-1">
        <div class="offcanvas-header">
            <h4 class="offcanvas-title" style="font-weight: bold;">Add New Course</h4><button class="btn-close" type="button" aria-label="Close" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body" style="border-color: rgb(255,255,255);">
            <div class="container">
                <form method="POST" action="{{route('course.store')}}">
                @csrf
                    <div class="row" style="padding-bottom: 10px;">
                        <div class="col-md-12">
                            <p style="margin-bottom: 2px;font-weight: bold;">Cover Image</p>
                            <div class="position-relative rounded vPreviewImage" id="someId" style="width: 96px;height: 96px;background: url('assets/img/input_image_preview/upload_image.png') center / cover no-repeat;" input-data-index="0"><button class="btn position-sticky d-none close vClearPreviewImage" type="button"><span class="bg-white pl-2 pr-2" aria-hidden="true">&times;</span></button><input type="file" class="vInputImage" style="width: 96px;height: 96px;opacity: 0;cursor: pointer;" accept="image/*"></div>
                        </div>
                    </div>
                    <div class="row" style="margin-top: 10px;">
                        <div class="col-md-12 col-xxl-12">
                            <p style="margin-bottom: 2px;font-weight: bold;">Program Name</p>
                            <div class="dropdown" style="display: block;margin-left: 0px;width: 100%;height: 30px;text-align: left;"><button class="btn btn-primary dropdown-toggle text-end d-flex justify-content-end align-items-center form-control" aria-expanded="false" data-bs-toggle="dropdown" id="PGdropdownMenuButton_1" type="button" style="background: rgb(255,255,255);color: rgb(0,0,0);width: 100%;border-color: rgb(4,0,0);height: 30px;border-radius: 5px;"></button>
                                <div class="dropdown-menu pgdropdown" style="width: 100%;">
                                    <input type="text" id="PGdropdownSearchInput" class="form-control search">
                                    <a class="dropdown-item program" href="#" onclick="selectProgram(this)">风水</a>
                                    <a class="dropdown-item program" href="#" onclick="selectProgram(this)">冥想</a>
                                    <a class="dropdown-item program" href="#" onclick="selectProgram(this)">财经</a>
                                </div>
                            </div>
                            <select class="form-select" name="program_id" id="programSelect" required>
                                <option value="" disabled selected>Select a program</option>
                                @foreach($programs as $program)
                                    <option value="{{ $program->id }}">{{ $program->title }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row" style="margin-top: 10px;">
                        <div class="col-md-12">
                            <p style="margin-bottom: 2px;font-weight: bold;">Course Name</p>
                            <input type="text" name='title' id="courseName" style="width: 100%;border-radius: 5px;border-width: 0.8px;border-color: rgb(0,0,0);" placeholder="Eg. Feng Shui">
                        </div>
                    </div>
                    <div class="row" style="margin-top: 10px;">
                        <div class="col-md-12">
                            <p style="margin-bottom: 2px;font-weight: bold;">Description</p>
                            <textarea name='description' id="courseDescription" style="width: 100%;height: 150px;border-radius: 5px;" placeholder="Course Description"></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-lg-10 text-end" style="width: 100%;">
                            <button class="btn btn-primary" id="btnaddinventory" type="submit" data-bs-dismiss="offcanvas" style="background: rgb(78,115,223);margin: 0px 10px;">Add</button>
                            <button class="btn btn-primary" type="button" data-bs-dismiss="offcanvas" style="background: rgb(231,74,59);">Cancel</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="offcanvas offcanvas-end" tabindex="-1" id="editCourseModal">
        <div class="offcanvas-header">
            <h4 class="offcanvas-title" style="font-weight: bold;">Edit Program</h4><button class="btn-close" type="button" aria-label="Close" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body" style="border-color: rgb(255,255,255);">
            <div class="container">
                <form id="updateCourseForm">
                @csrf
                    <input type="hidden" id="course_id" readonly>
                    <div class="row" style="padding-bottom: 10px;">
                        <div class="col-md-12">
                            <p style="margin-bottom: 2px;font-weight: bold;">Cover Image</p>
                            <div class="position-relative rounded vPreviewImage" id="someId" style="width: 96px;height: 96px;background: url('assets/img/input_image_preview/upload_image.png') center / cover no-repeat;" input-data-index="0"><button class="btn position-sticky d-none close vClearPreviewImage" type="button"><span class="bg-white pl-2 pr-2" aria-hidden="true">&times;</span></button><input type="file" class="vInputImage" style="width: 120px;height: 100px;opacity: 0;cursor: pointer;" accept="image/*"></div>
                        </div>
                    </div>
                    <div class="row" style="margin: 0px -12px;margin-top: 15px;">
                        <div class="col-md-12">
                            <p style="margin-bottom: 2px;font-weight: bold;">Program Name</p>
                            <div class="dropdown" style="display: block;margin-left: 0px;width: 100%;height: 30px;text-align: left;border-color: rgb(0,4,8);"><button class="btn btn-primary dropdown-toggle text-end d-flex justify-content-end align-items-center form-control" aria-expanded="false" data-bs-toggle="dropdown" id="E_PGdropdownMenuButton" type="button" style="background: rgb(255,255,255);color: rgb(0,0,0);width: 100%;height: 30px;border-radius: 5px;border-color: rgb(0,0,0);"></button>
                                <div class="dropdown-menu e_pgdropdown" style="width: 100%;">
                                    <input type="text" id="E_PGdropdownSearchInput" class="form-control search">
                                    <a class="dropdown-item e_program" href="#" onclick="selectProgram(this)">风水</a>
                                    <a class="dropdown-item e_program" href="#" onclick="selectProgram(this)">冥想</a>
                                    <a class="dropdown-item e_program" href="#" onclick="selectProgram(this)">财经</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row" style="margin-top: 15px;">
                        <div class="col-md-12">
                            <p style="margin-bottom: 2px;font-weight: bold;">Course Name</p>
                            <input type="text" id="course_title" style="width: 100%;border-radius: 5px;border-width: 0.8px;border-color: rgb(0,0,0);" placeholder="Eg. Feng Shui">
                        </div>
                    </div>
                    <div class="row" style="margin-top: 15px;">
                        <div class="col-md-12">
                            <p style="margin-bottom: 2px;font-weight: bold;">Description</p>
                            <textarea id="course_description" style="width: 100%;height: 130px;border-radius: 5px;" placeholder="Program Description"></textarea>
                        </div>
                    </div>
                    <div class="row" style="margin-top: 15px;">
                        <div class="col-md-12 col-lg-10 text-end" style="width: 100%;">
                            <button class="btn btn-primary" type="submit" data-bs-dismiss="offcanvas" style="background: rgb(78,115,223);margin: 0px 10px;">Edit</button>
                            <button class="btn btn-primary" type="button" data-bs-dismiss="offcanvas" style="background: rgb(231,74,59);">Cancel</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" role="dialog" tabindex="-1" id="confirmStatusModal">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Delete Alert!</h4><button class="btn-close" type="button" aria-label="Close" data-bs-dismiss="modal"></button>
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
@endsection

@push('scripts')
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