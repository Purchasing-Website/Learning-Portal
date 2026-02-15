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
                            <div class="col" style="padding: 0px;width: 975px;">
                                <h1 class="d-inline-block" style="margin: 0px 0px;margin-bottom: 0px;height: 100%;padding: 5px 0px;">Class Management</h1>
                                <p class="d-inline-block invisible" style="margin: 0px 10px;">course name</p>
                            </div>
                            <div class="col-12 justify-content-end align-items-center align-content-center" style="padding: 0px;width: initial;"><button class="btn btn-primary" type="button" style="width: 95px;font-weight: bold;color: rgb(255,255,255);background: rgb(78,115,223);border-width: 0px;" data-bs-target="#AddClass" data-bs-toggle="offcanvas"><i class="fas fa-plus-square" style="border-color: rgb(255,255,255);color: rgb(255,255,255);background: rgba(255,255,255,0);font-size: 18px;"></i>&nbsp; Add</button></div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive text-break">
                            <table class="table table-striped table-hover" id="example">
                                <thead>
                                    <tr>
                                        <th class="text-nowrap">Class ID</th>
                                        <th class="text-nowrap">Class Name</th>
                                        <th class="text-nowrap">Cover Image</th>
                                        <th class="text-nowrap">Description</th>
                                        {{-- @if ($show!=='all') --}}
                                            <th class="text-nowrap">Course Name</th>
                                            {{-- <th class="text-nowrap">Program Name</th> --}}
                                        {{-- @endif --}}
                                        <th class="text-nowrap">Date Started</th>
                                        <th class="text-nowrap">Dependent Class</th>
                                        <th class="text-nowrap text-start">Total Students</th>
                                        <th class="text-nowrap">Tier</th>
                                        <th class="text-nowrap">Status</th>
                                        <th class="text-nowrap text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($show==='all')
                                        @foreach ($classes as $class)
                                            <tr style="max-width: 49px;">
                                                <td class="text-truncate" style="max-width: 200px;">{{ $class->id }}</td>
                                                <td class="text-truncate" style="max-width: 200px;">{{ $class->title }}</td>
                                                <td class="text-truncate" style="max-width: 200px;"><img class="img-fluid" width="299" height="180" style="max-width: 120px;max-height: 100px;" src="{{ asset('img/OIP.webp') }}"></td>
                                                <td class="text-break" style="max-width: 50px;">{{ $class->description }}</td>
                                                <td class="text-break" style="max-width: 50px;">{{ $class->courses->first()->title }}</td>
                                                <td>{{ $class->created_at->format('Y-m-d') }}</td>
                                                <td class="text-truncate">1</td>
                                                <td class="text-start">{{$class->enrollments_count}}</td>
                                                <td class="text-start" style="max-width: 200px;">{{$class->tier->name}}</td>
                                                <td class="status-cell">
                                                    @if($class->is_active)
                                                        <span class="badge bg-success">Active</span>
                                                    @else
                                                        <span class="badge bg-secondary">Inactive</span>
                                                    @endif
                                                </td>
                                                <td class="text-nowrap text-start text-center">
                                                    <a class="btn btn-dark" role="button" style="width: 25px;height: 25px;padding: 3px 3px;text-align: center;margin: 0px 3px;background: rgba(242,242,242,0);border-style: none;" href="{{ route('lesson.index',$class->id) }}">
                                                        <i class="material-icons text-dark" id="showAlertBtn" style="font-size: 19px;--bs-primary: #4e73df;--bs-primary-rgb: 78,115,223;color: rgb(255,255,255);" type="button">remove_red_eye</i>
                                                    </a>
                                                    <a class="btn btn-dark" role="button" style="width: 25px;height: 25px;padding: 3px 3px;text-align: center;margin: 0px 3px;background: rgba(242,242,242,0);border-style: none;" href="LessonArrange.html">
                                                        <i class="material-icons text-dark" id="showAlertBtn-16" style="font-size: 19px;--bs-primary: #4e73df;--bs-primary-rgb: 78,115,223;color: rgb(255,255,255);" type="button">low_priority</i>
                                                    </a>
                                                    <button class="btn btn-dark editBtn" id='editBtn' type="button" data-id="{{ $class->id }}" style="width: 25px;height: 25px;padding: 3px 3px;text-align: center;margin: 0px 3px;background: rgba(242,242,242,0);border-style: none;" data-bs-target="#EditClass" data-bs-toggle="offcanvas">
                                                        <i class="material-icons text-dark" id="showAlertBtn-5" style="font-size: 19px;--bs-primary: #4e73df;--bs-primary-rgb: 78,115,223;color: rgb(255,255,255);" type="button">edit</i>
                                                    </button>
                                                    <button class="btn btn-dark toggleStatus" data-id="{{ $class->id }}" type="button" style="width: 25px;height: 25px;padding: 3px 3px;text-align: center;margin: 0px 3px;background: rgba(242,242,242,0);border-style: none;" data-bs-target="#modal-1" data-bs-toggle="modal">
                                                        <i class="material-icons text-dark" id="showAlertBtn-6" style="font-size: 19px;--bs-primary: #4e73df;--bs-primary-rgb: 78,115,223;color: rgb(255,255,255);" type="button">do_not_disturb_alt</i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else    
                                        @foreach ($course->classes as $class)
                                            <tr style="max-width: 49px;">
                                                <td class="text-truncate" style="max-width: 200px;">{{ $class->id }}</td>
                                                <td class="text-truncate" style="max-width: 200px;">{{ $class->title }}</td>
                                                <td class="text-truncate" style="max-width: 200px;"><img class="img-fluid" width="299" height="180" style="max-width: 120px;max-height: 100px;" src="{{ asset('img/OIP.webp') }}"></td>
                                                <td class="text-break" style="max-width: 50px;">{{ $class->description }}</td>
                                                <td class="text-break" style="max-width: 50px;">{{ $class->courses->first()->title }}</td>
                                                <td>{{ $class->created_at->format('Y-m-d') }}</td>
                                                <td class="text-truncate">1</td>
                                                <td class="text-start">{{$class->enrollments_count}}</td>
                                                <td class="status-cell">
                                                    @if($class->is_active)
                                                        <span class="badge bg-success">Active</span>
                                                    @else
                                                        <span class="badge bg-secondary">Inactive</span>
                                                    @endif
                                                </td>
                                                <td class="text-nowrap text-start text-center">
                                                    <a class="btn btn-dark" role="button" style="width: 25px;height: 25px;padding: 3px 3px;text-align: center;margin: 0px 3px;background: rgba(242,242,242,0);border-style: none;" href="{{ route('lesson.index',$class->id) }}">
                                                        <i class="material-icons text-dark" id="showAlertBtn" style="font-size: 19px;--bs-primary: #4e73df;--bs-primary-rgb: 78,115,223;color: rgb(255,255,255);" type="button">remove_red_eye</i>
                                                    </a>
                                                    <a class="btn btn-dark" role="button" style="width: 25px;height: 25px;padding: 3px 3px;text-align: center;margin: 0px 3px;background: rgba(242,242,242,0);border-style: none;" href="LessonArrange.html">
                                                        <i class="material-icons text-dark" id="showAlertBtn-16" style="font-size: 19px;--bs-primary: #4e73df;--bs-primary-rgb: 78,115,223;color: rgb(255,255,255);" type="button">low_priority</i>
                                                    </a>
                                                    <button class="btn btn-dark editBtn" id='editBtn' type="button" data-id="{{ $class->id }}" style="width: 25px;height: 25px;padding: 3px 3px;text-align: center;margin: 0px 3px;background: rgba(242,242,242,0);border-style: none;" data-bs-target="#EditClass" data-bs-toggle="offcanvas">
                                                        <i class="material-icons text-dark" id="showAlertBtn-5" style="font-size: 19px;--bs-primary: #4e73df;--bs-primary-rgb: 78,115,223;color: rgb(255,255,255);" type="button">edit</i>
                                                    </button>
                                                    <button class="btn btn-dark toggleStatus" data-id="{{ $class->id }}" type="button" style="width: 25px;height: 25px;padding: 3px 3px;text-align: center;margin: 0px 3px;background: rgba(242,242,242,0);border-style: none;" data-bs-target="#modal-1" data-bs-toggle="modal">
                                                        <i class="material-icons text-dark" id="showAlertBtn-6" style="font-size: 19px;--bs-primary: #4e73df;--bs-primary-rgb: 78,115,223;color: rgb(255,255,255);" type="button">do_not_disturb_alt</i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                    {{-- <td class="text-nowrap text-start text-center"><a class="btn btn-dark" role="button" style="width: 25px;height: 25px;padding: 3px 3px;text-align: center;margin: 0px 3px;background: rgba(242,242,242,0);border-style: none;" href="Lesson.html"><i class="material-icons text-dark" id="showAlertBtn" style="font-size: 19px;--bs-primary: #4e73df;--bs-primary-rgb: 78,115,223;color: rgb(255,255,255);" type="button">remove_red_eye</i></a><a class="btn btn-dark" role="button" style="width: 25px;height: 25px;padding: 3px 3px;text-align: center;margin: 0px 3px;background: rgba(242,242,242,0);border-style: none;" href="LessonArrange.html"><i class="material-icons text-dark" id="showAlertBtn-16" style="font-size: 19px;--bs-primary: #4e73df;--bs-primary-rgb: 78,115,223;color: rgb(255,255,255);" type="button">low_priority</i></a><button class="btn btn-dark" type="button" style="width: 25px;height: 25px;padding: 3px 3px;text-align: center;margin: 0px 3px;background: rgba(242,242,242,0);border-style: none;" data-bs-target="#EditClass" data-bs-toggle="offcanvas"><i class="material-icons text-dark" id="showAlertBtn-5" style="font-size: 19px;--bs-primary: #4e73df;--bs-primary-rgb: 78,115,223;color: rgb(255,255,255);" type="button">edit</i></button><button class="btn btn-dark" type="button" style="width: 25px;height: 25px;padding: 3px 3px;text-align: center;margin: 0px 3px;background: rgba(242,242,242,0);border-style: none;" data-bs-target="#modal-1" data-bs-toggle="modal"><i class="material-icons text-dark" id="showAlertBtn-6" style="font-size: 19px;--bs-primary: #4e73df;--bs-primary-rgb: 78,115,223;color: rgb(255,255,255);" type="button">do_not_disturb_alt</i></button></td> --}}
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
                    <h4 class="modal-title">Delete Alert!</h4><button class="btn-close" type="button" aria-label="Close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p id="classActivation"></p>
                </div>
                <div class="modal-footer">
                    <form id="updateClassStatus">
                        @csrf
                        <input type="hidden" id="classStatusId" >
                        <button class="btn btn-primary" id="showAlertBtn-7" type="submit" data-bs-target="#modal-2" data-bs-toggle="modal" data-bs-dismiss="modal" style="background: rgb(231,74,59);">Yes</button>
                        <button class="btn btn-light" type="button" data-bs-dismiss="modal" style="background: rgb(13,110,253);color: rgb(255,255,255);">No</button>
                    </form>                
                </div>
            </div>
        </div>
    </div>
    <div class="offcanvas offcanvas-end lp-offcanvas" tabindex="-1" id="AddClass" aria-labelledby="ocAddLessonLabel">
        <div class="oc-header">
            <div class="d-flex justify-content-between align-items-start gap-2">
                <div>
                    <h5 id="ocAddLessonLabel">Add New Class</h5>
                </div><button class="btn-close mt-1 btn-close-white" type="button" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
        </div>
        <div class="offcanvas-body p-3 p-sm-4">
            <form method="POST" action="{{route('class.store')}}">
                @csrf>
                <label class="form-label">Upload Cover Image&nbsp;<span class="hint"></span></label>
                <input class="form-control" type="file" accept="image/*,application/pdf" id="uploadFile">
                <div class="mt-3">
                    <label class="form-label">Course Name</label>
                    <div class="search-dd" id="courseDD">
                        {{-- <input class="form-control form-control" type="text" autocomplete="off" id="courseInput" placeholder="Search course..." required=""> --}}
                        <input type="hidden" id="courseId">
                        <div class="invalid-msg">
                            <span>Please select a course.</span>
                        </div>
                        {{-- <div class="dd-panel">
                            <div class="dd-search">
                                <input class="form-control form-control courseSearch" type="text" id="courseSearch" placeholder="Type to filter...">
                            </div>
                            <div class="dd-list" id="courseList"></div>
                        </div> --}}
                        
                            <select name="course_id" id="cource_id" class="form-select" required>
                                <option value="">-- Choose a Course --</option>
                                @foreach($courses as $course)
                                    <option value="{{ $course->id }}">{{ $course->title }}</option>
                                @endforeach
                            </select>
        
                    </div>
                </div>
                <div class="mt-3"><label class="form-label">Class Name</label>
                    <div class="search-dd" id="classDD">
                        <input class="form-control" name='title' type="text" id="class_name" placeholder="Type the class name" required="">
                        <input type="hidden" id="classId">
                        <div class="invalid-msg">
                            <span>Please select a class.</span>
                        </div>
                    </div>
                </div>
                <div class="mt-3"><label class="form-label">User Access Level</label>
                    <div id="userLevelDD" class="search-dd">
                        <select name="tier_id" id="tier_id" class="form-select" required>
                            <option value="">-- Choose a Tier --</option>
                            @foreach($tiers as $tier)
                                <option value="{{ $tier->id }}">{{ $tier->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <label class="form-label mt-3">Description</label>
                <textarea name='description' class="form-control form-control" id="description" placeholder="Course Description" rows="4"></textarea>
                <div class="divider"></div>
                <div class="d-flex justify-content-end gap-2">
                    <button class="btn btn-outline-light" type="button" data-bs-dismiss="offcanvas">Cancel</button>
                    <button class="btn btn-primary" id="btnAdd" type="submit">Add</button>
                </div>
                <div class="mt-3 small" id="result" style="color:rgba(229,231,235,.8);display:none;"></div>
            </form>
        </div>
    </div>
    <div class="offcanvas offcanvas-end lp-offcanvas" tabindex="-1" id="editClassModal" aria-labelledby="ocAddLessonLabel">
        <div class="oc-header">
            <div class="d-flex justify-content-between align-items-start gap-2">
                <div>
                    <h5 id="ocAddLessonLabel-1">Edit Class</h5>
                </div><button class="btn-close mt-1 btn-close-white" type="button" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
        </div>
        <div class="offcanvas-body p-3 p-sm-4">
            <form id="updateClassForm">
                @csrf
                <input type="hidden" id="class_id">
                <label class="form-label">Upload Cover Image&nbsp;<span class="hint"></span></label>
                <input class="form-control" type="file" accept="image/*,application/pdf" id="uploadFile-1">
                <div class="mt-3">
                    <label class="form-label">Course Name</label>
                    <div class="search-dd" id="courseDD-1">
                        <input class="form-control form-control" type="text" autocomplete="off" id="courseInput-1" placeholder="Search course..." readonly>
                        <input type="hidden" id="courseId-1">
                        <div class="invalid-msg">
                            <span>Please select a course.</span>
                        </div>
                        <div class="dd-panel">
                            <div class="dd-search">
                                <input class="form-control form-control courseSearch" type="text" id="courseSearch-1" placeholder="Type to filter...">
                            </div>
                            <div class="dd-list" id="courseList-1"></div>
                        </div>
                    </div>
                </div>
                <div class="mt-3"><label class="form-label">Class Name</label>
                    <div class="search-dd" id="classDD-1">
                        <input class="form-control" type="text" id="class_title" placeholder="Type the class name" required=""><input type="hidden" id="classId-1">
                        <div class="invalid-msg">
                            <span>Please select a class.</span>
                        </div>
                    </div>
                </div>
                <div class="mt-3">
                    <label class="form-label">User Access Level</label>
                    <div id="userLevelDD-1" class="search-dd">
                        <select name="tier_id" id="tier_id_edit" class="form-select" required>
                            <option value="">-- Choose a Tier --</option>
                            @foreach($tiers as $tier)
                                <option value="{{ $tier->id }}">{{ $tier->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <label class="form-label mt-3">Description</label>
                <textarea class="form-control form-control" id="class_description" placeholder="Course Description" rows="4"></textarea>
                <div class="divider"></div>
                <div class="d-flex justify-content-end gap-2">
                    <button class="btn btn-outline-light" type="button" data-bs-dismiss="offcanvas">Cancel</button>
                    <button class="btn btn-primary" id="btnAdd-1" type="submit">Add</button>
                </div>
                <div class="mt-3 small" id="result-1" style="color:rgba(229,231,235,.8);display:none;"></div>
            </form>
        </div>
    </div>
    @push('scripts')
    <script src="{{ asset('js/Alert.js') }}"></script>
    <script src="{{ asset('js/class_offcanvas.js') }}"></script>
    <script>
    const tableBody = document.querySelector('#example tbody'); 

    document.addEventListener("DOMContentLoaded", () => {
        //const editButtons = document.querySelectorAll(".editBtn");
        //const modal = new bootstrap.Offcanvas(document.getElementById('editClassModal'));

        //editButtons.forEach(btn => {
            //btn.addEventListener("click", async () => {
            tableBody.addEventListener("click", async (event) => {
                const modal = new bootstrap.Offcanvas(document.getElementById('editClassModal'));

                const btn = event.target.closest(".editBtn");
            
                if (!btn) return; // Exit if something else was clicked

                const id = btn.dataset.id;

                const response = await fetch(`/class/${id}/edit`);
                const class_= await response.json();

                document.getElementById("class_id").value = class_.id;
                document.getElementById("class_title").value = class_.title;
                document.getElementById("class_description").value = class_.description || '';
                document.getElementById("tier_id_edit").value = class_.tier_id ?? '';
                const selectedCourse = class_.courses?.[0];
                document.getElementById("courseInput-1").value =
                    selectedCourse?.title || btn.closest("tr")?.children?.[4]?.innerText?.trim() || '';
                document.getElementById("courseId-1").value = selectedCourse?.id ?? '';

                modal.show();
            });
        //});

        // Update form submit
        document.getElementById("updateClassForm").addEventListener("submit", async (e) => {
            e.preventDefault();

            const id = document.getElementById("class_id").value;
            const formData = new FormData();
            formData.append('_token', document.querySelector('input[name="_token"]').value);
            formData.append('title', document.getElementById("class_title").value);
            formData.append('description', document.getElementById("class_description").value);
            formData.append('tier_id', document.getElementById("tier_id_edit").value);

            const res = await fetch(`/class/${id}/update`, {
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
        //const buttons = document.querySelectorAll('.toggleStatus');

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
                const confirmMessage = `Are you sure you want to ${isCurrentlyActive ? 'deactivate' : 'activate'} this program?`;

                document.getElementById("classActivation").textContent = confirmMessage;
                document.getElementById("classStatusId").value = id;

                toggleStatusModel.show();
            });
        //});

        // Update form submit
        document.getElementById("updateClassStatus").addEventListener("submit", async (e) => {
            e.preventDefault();
            try {
                const class_id = document.getElementById("classStatusId").value;

                const response = await fetch(`/class/${class_id}/toggle-status`, {
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

        // Assign Course to Class
        let assignModal = new bootstrap.Modal(document.getElementById('assignCourseModal'));

        document.querySelectorAll('.assignCourseBtn').forEach(btn => {
            btn.addEventListener('click', async () => {
                const classId = btn.dataset.classId;

                // Load available courses
                let res = await fetch(`/class/${classId}/load-available-courses`);
                let data = await res.json();

                // Fill dropdown
                let select = document.getElementById('courseDropdown');
                select.innerHTML = "";

                if (data.available.length === 0) {
                    select.innerHTML = `<option value="">No available courses</option>`;
                } else {
                    data.available.forEach(course => {
                        select.innerHTML += `<option value="${course.id}">${course.title}</option>`;
                    });
                }

                // Set next available sequence
                document.getElementById('classSequence').value = data.nextSequence;

                // Store class ID for saving later
                document.getElementById('saveCourseAssign').dataset.classId = classId;

                assignModal.show();
            });
        });

        document.getElementById('saveCourseAssign').addEventListener('click', async function() {
            let classId = this.dataset.classId;

            let formData = new FormData();
            formData.append('_token', '{{ csrf_token() }}');
            formData.append('course_id', document.getElementById('courseDropdown').value);
            formData.append('sequence', document.getElementById('classSequence').value);

            let res = await fetch(`/class/${classId}/assign-course`, {
                method: 'POST',
                body: formData
            });

            let data = await res.json();

            if (data.success) {
                alert('Assigned successfully!');
                assignModal.hide();
                location.reload();
            }
        });

        // Show Assigned Courses
        document.addEventListener('click', async function(e) {
            if (e.target.classList.contains('showAssignedCoursesBtn')) {
                let classId = e.target.dataset.classId;

                let res = await fetch(`/class/${classId}/assigned-courses`);
                let data = await res.json();

                let tbody = document.getElementById('assignedCoursesTableBody');
                tbody.innerHTML = "";

                if (data.data.length === 0) {
                    tbody.innerHTML = `
                        <tr>
                            <td colspan="6" class="text-center text-muted">No courses assigned yet.</td>
                        </tr>
                    `;
                } else {
                    data.data.forEach((course, index) => {
                        tbody.innerHTML += `
                            <tr>
                                <td>${index + 1}</td>
                                <td>${course.title}</td>
                                <td>${course.pivot.sequence_order ?? '-'}</td>
                                <td>
                                    <span class="badge bg-${course.pivot.is_active ? 'success' : 'secondary'}">
                                        ${course.pivot.is_active ? 'Active' : 'Inactive'}
                                    </span>
                                </td>
                                <td>${course.creator ? course.creator.name : '-'}</td>
                                <td>${course.pivot.updated_at}</td>
                                <td>
                                    <button class="btn btn-sm btn-danger unassignClassBtn" 
                                            data-class-id="${course.pivot.class_id}" 
                                            data-course-id="${course.pivot.course_id}"
                                            ${course.pivot.is_active ? '' : 'disabled'}>
                                        
                                        ${course.pivot.is_active ? 'Unassign' : 'Unassigned'}
                                    </button>
                                </td>
                            </tr>
                        `;
                    });
                }

                new bootstrap.Modal(document.getElementById('assignedCoursesModal')).show();
            }
        });

        // Unassign Class from Course
        document.addEventListener('click', async function(e) {
            if (e.target.classList.contains('unassignClassBtn')) {
                const classId = e.target.dataset.classId;
                const courseId = e.target.dataset.courseId;

                if (!confirm("Are you sure you want to unassign this class from the course?")) return;

                try {
                    const res = await fetch(`/class/${classId}/soft-unassign-course/${courseId}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    });
                    const data = await res.json();

                    if (data.success) {
                        alert('Class successfully unassigned (soft).');

                        // Update badge
                        const row = e.target.closest('tr');
                        const badge = row.querySelector('span.badge');
                        badge.textContent = 'Inactive';
                        badge.className = 'badge bg-secondary';

                        // Disable the Unassign button
                        e.target.disabled = true;
                        e.target.textContent = 'Unassigned';
                    } else {
                        alert('Failed to unassign class.');
                    }
                } catch (err) {
                    console.error(err);
                    alert('Error unassigning class.');
                }
            }
        });

    });
    </script>
    @endpush
