@extends('layouts.Custom_app', ['title' => 'Class'])

@section('content')
    
        
        <div class="d-flex flex-column" id="content-wrapper">
            <div id="content">
                <nav class="navbar navbar-expand bg-dark shadow mb-4 topbar static-top navbar-light">
                    <div class="container-fluid"><button class="btn btn-link d-md-none me-3 rounded-circle" id="sidebarToggleTop" type="button"><i class="fas fa-bars"></i></button>
                        <ul class="navbar-nav flex-nowrap ms-auto">
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
                                        <div class="col-12 col-md-6 col-xxl-6 d-inline-flex" style="padding: 0px;">
                                            <h1 class="d-inline-block" style="margin: 0px 0px;margin-bottom: 0px;height: 100%;padding: 5px 0px;">Lesson Management</h1>
                                            <p class="d-inline-block invisible" style="margin: 0px 10px;">course name</p>
                                        </div>
                                        <div class="col text-end d-xl-flex justify-content-xl-center align-items-xl-center justify-content-xxl-end" style="padding: 0px;width: initial;">
                                            <div class="input-group" style="margin: 15px;width: auto;"><input class="form-control" type="text" placeholder="Search" aria-label="Search" aria-describedby="button-addon2"><button class="btn btn-outline-secondary" type="button" id="button-addon2"><i class="fas fa-search"></i></button></div><button class="btn btn-primary" type="button" style="width: 100px;font-weight: bold;color: rgb(255,255,255);background: rgb(18,234,92);border-width: 0px;margin: 0px 10px;font-size: 16px;" data-bs-target="#offcanvas-1" data-bs-toggle="offcanvas">Reorder Sequence</button><button class="btn btn-primary" type="button" style="width: 100px;font-weight: bold;color: rgb(255,255,255);background: rgb(78,115,223);border-width: 0px;height: 60px;margin: 0px 10px;" data-bs-target="#offcanvas-1" data-bs-toggle="offcanvas"><i class="fas fa-plus-square" style="border-color: rgb(255,255,255);color: rgb(255,255,255);background: rgba(255,255,255,0);font-size: 18px;"></i>&nbsp; Add</button>
                                        </div>
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
                                                    <th class="text-nowrap">Status</th>
                                                    <th class="text-nowrap">Content Type</th>
                                                    <th class="text-nowrap">Duration</th>
                                                    <th class="text-nowrap text-start">Sequence Order</th>
                                                    <th class="text-nowrap text-start text-center">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($lessons as $lesson)
                                                    <tr style="max-width: 49px;">
                                                        <td class="text-truncate" style="max-width: 200px;">{{ $lesson->id }}</td>
                                                        <td class="text-truncate" style="max-width: 200px;">{{ $lesson->title }}</td>
                                                        <td class="text-break" style="max-width: 50px;">{{ Str::limit($lesson->description, 50) }}</td>
                                                        <td class="text-break" style="max-width: 50px;">{{ $lesson->class->title ?? 'N/A' }}</td>
                                                        <td class="status-cell">
                                                            @if($lesson->is_active)
                                                                <span class="badge bg-success">Active</span>
                                                            @else
                                                                <span class="badge bg-secondary">Inactive</span>
                                                            @endif
                                                        </td>
                                                        <td>{{$lesson->content_type}}</td>
                                                        <td>{{ $lesson->duration ?? 'N/A' }} hrs</td>
                                                        <td class="text-start" contenteditable="true">1</td>
                                                        <td class="text-nowrap text-start text-center">
                                                            <a class="btn btn-dark" role="button" style="width: 25px;height: 25px;padding: 3px 3px;text-align: center;margin: 0px 3px;background: rgba(242,242,242,0);border-style: none;" href="AdminOrderDetail.html">
                                                                <i class="material-icons text-dark" id="showAlertBtn" style="font-size: 19px;--bs-primary: #4e73df;--bs-primary-rgb: 78,115,223;color: rgb(255,255,255);" type="button">remove_red_eye</i>
                                                            </a>
                                                            <button class="btn btn-dark editBtn" id='editBtn' data-id="{{ $lesson->id }}" role="button" style="width: 25px;height: 25px;padding: 3px 3px;text-align: center;margin: 0px 5px;background: rgba(242,242,242,0);border-style: none;" href="AdminOrderDetail.html">
                                                                <i class="material-icons text-dark" id="showAlertBtn-5" style="font-size: 19px;--bs-primary: #4e73df;--bs-primary-rgb: 78,115,223;color: rgb(255,255,255);" type="button">edit</i>
                                                            </button>
                                                            <button class="btn btn-dark toggleStatus" data-id="{{ $lesson->id }}" role="button" style="width: 25px;height: 25px;padding: 3px 3px;text-align: center;margin: 0px 3px;background: rgba(242,242,242,0);border-style: none;" href="AdminOrderDetail.html">
                                                                <i class="material-icons text-dark" id="showAlertBtn-6" style="font-size: 19px;--bs-primary: #4e73df;--bs-primary-rgb: 78,115,223;color: rgb(255,255,255);" type="button">delete_forever</i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    {{-- <tr>
                                                        <td colspan="9" class="text-center">No lessons found.</td>
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
        </div><a class="border rounded d-inline scroll-to-top" href="#page-top"><i class="fas fa-angle-up"></i></a>
    
    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvas-1">
        <div class="offcanvas-header">
            <h4 class="offcanvas-title" style="font-weight: bold;">Add New Lesson</h4><button class="btn-close" type="button" aria-label="Close" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body" style="border-color: rgb(255,255,255);">
            <div class="container">
                <form method="POST" action="{{route('lesson.store')}}" enctype="multipart/form-data">
                    @csrf
                    <div class="row" style="padding-bottom: 10px;">
                        <div class="col-md-12">
                            <p style="margin-bottom: 2px;font-weight: bold;">Upload File</p>
                            <input type="file">
                        </div>
                    </div>
                    <div class="row" style="margin-top: 10px;">
                        <div class="col-md-12 col-xxl-12">
                            <p style="margin-bottom: 2px;font-weight: bold;">Content Type</p>
                            <div class="dropdown" style="display: block;margin-left: 0px;width: 100%;height: 30px;text-align: left;"><button class="btn btn-primary dropdown-toggle text-end d-flex justify-content-end align-items-center form-control" aria-expanded="false" data-bs-toggle="dropdown" id="PGdropdownMenuButton_1" type="button" style="background: rgb(255,255,255);color: rgb(0,0,0);width: 100%;border-color: rgb(4,0,0);height: 30px;border-radius: 5px;"></button>
                                <div class="dropdown-menu pgdropdown" style="width: 100%;">
                                    <input type="text" id="PGdropdownSearchInput" class="form-control search">
                                    <a class="dropdown-item" href="#" onclick="selectProgram(this)">Video</a>
                                    <a class="dropdown-item" href="#" onclick="selectProgram(this)">Image</a>
                                    <a class="dropdown-item" href="#" onclick="selectProgram(this)">PPT</a>
                                    <a class="dropdown-item" href="#" onclick="selectProgram(this)">PDF</a>
                                </div>
                            </div>
                            <select name="content_type" id="content_type" class="form-select @error('content_type') is-invalid @enderror">
                                <option value="">-- Select Type --</option>
                                @foreach($contentTypes as $contentType)
                                    <option value="{{ $contentType->value }}"
                                        {{ old('content_type') == $contentType->value ? 'selected' : '' }}>
                                        {{ ucfirst($contentType->name) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
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
                    <div class="row" style="margin-top: 10px;">
                        <div class="col-md-12">
                            <p style="margin-bottom: 2px;font-weight: bold;">Source URL</p><input type="text" style="width: 100%;border-radius: 5px;border-width: 0.8px;border-color: rgb(4,0,0);" placeholder=" Eg. Feng Shui">
                        </div>
                    </div>
                    <div class="row" style="margin-top: 10px;">
                        <div class="col-md-12">
                            <p style="margin-bottom: 2px;font-weight: bold;">Course Name</p>
                            <div class="dropdown" style="display: block;margin-left: 0px;width: 100%;height: 30px;text-align: left;"><button class="btn btn-primary dropdown-toggle text-end d-flex justify-content-end align-items-center form-control" aria-expanded="false" data-bs-toggle="dropdown" id="CSdropdownMenuButton" type="button" style="background: rgb(255,255,255);color: rgb(0,0,0);width: 100%;border-color: rgb(4,0,0);height: 30px;border-radius: 5px;"></button>
                                <div class="dropdown-menu csdropdown" style="width: 100%;"><input type="text" id="CSdropdownSearchInput" class="form-control search"><a class="dropdown-item course" href="#" onclick="selectCourse(this)">风水 course</a><a class="dropdown-item course" href="#" onclick="selectCourse(this)">冥想</a><a class="dropdown-item course" href="#" onclick="selectCourse(this)">财经</a></div>
                            </div>
                        </div>
                    </div>
                    <div class="row" style="margin-top: 10px;">
                        <div class="col-md-12">
                            <p style="margin-bottom: 2px;font-weight: bold;">Class Name</p>
                            <div class="dropdown" style="display: block;margin-left: 0px;width: 100%;height: 30px;text-align: left;"><button class="btn btn-primary dropdown-toggle text-end d-flex justify-content-end align-items-center form-control" aria-expanded="false" data-bs-toggle="dropdown" type="button" style="background: rgb(255,255,255);color: rgb(0,0,0);width: 100%;border-color: rgb(4,0,0);height: 30px;border-radius: 5px;"></button>
                                <div class="dropdown-menu csdropdown" style="width: 100%;">
                                    <input type="text" class="form-control search">
                                    <a class="dropdown-item" href="#" onclick="selectCourse(this)">Class A</a>
                                    <a class="dropdown-item" href="#" onclick="selectCourse(this)">Class B</a>
                                    <a class="dropdown-item" href="#" onclick="selectCourse(this)">Class C</a>
                                    <a class="dropdown-item" href="#" onclick="selectCourse(this)">Class D</a>
                                </div>
                            </div>
                            <select name="class_id" id="class_id" class="form-select" required>
                                <option value="">-- Choose a Class --</option>
                                @foreach($classes as $class)
                                    <option value="{{ $class->id }}">{{ $class->title }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row" style="margin-top: 10px;">
                        <div class="col-md-12">
                            <p style="margin-bottom: 2px;font-weight: bold;">Lesson Name</p>
                            <input type="text" name='title' id="lessonName" style="width: 100%;border-radius: 5px;border-width: 0.8px;border-color: rgb(4,0,0);" placeholder=" Eg. Feng Shui">
                        </div>
                    </div>
                    <div class="row" style="margin-top: 10px;">
                        <div class="col-md-12">
                            <p style="margin-bottom: 2px;font-weight: bold;">Duration</p>
                        </div>
                    </div>
                    <div class="col-5 d-inline-block">
                        <input class="d-inline-block" name='duration' id="lessonDuration" type="number" style="width: 50%;border-radius: 5px;">
                        <p class="d-inline-block" style="width: 30%;margin: 0px;padding: 0px 5px;">hours</p>
                    </div>
                    <div class="col-5 d-inline-block"><input class="d-inline-block" type="number" style="width: 50%;border-radius: 5px;">
                        <p class="d-inline-block" style="width: 30%;margin: 0px;padding: 0px 5px;">mins</p>
                    </div>
                    <div class="row" style="margin-top: 10px;">
                        <div class="col-md-12">
                            <p style="margin-bottom: 2px;font-weight: bold;">Description</p>
                            <textarea name='description' id="lessonDescription" style="width: 100%;height: 150px;border-radius: 5px;" placeholder="Course Description"></textarea>
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
    <div class="offcanvas offcanvas-end" tabindex="-1" id="editLessonModal">
        <div class="offcanvas-header">
            <h4 class="offcanvas-title" style="font-weight: bold;">Edit Program</h4><button class="btn-close" type="button" aria-label="Close" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body" style="border-color: rgb(255,255,255);">
            <div class="container">
                <form id="updateLessonForm">
                    @csrf
                    <input type="hidden" id="lesson_id">
                    <div class="row" style="padding-bottom: 10px;">
                        <div class="col-md-12">
                            <p style="margin-bottom: 2px;font-weight: bold;">Upload File</p>
                            <input type="file">
                        </div>
                    </div>
                    <div class="row" style="margin-top: 10px;">
                        <div class="col-md-12 col-xxl-12">
                            <p style="margin-bottom: 2px;font-weight: bold;">Content Type</p>
                            <div class="dropdown" style="display: block;margin-left: 0px;width: 100%;height: 30px;text-align: left;"><button class="btn btn-primary dropdown-toggle text-end d-flex justify-content-end align-items-center form-control" aria-expanded="false" data-bs-toggle="dropdown" id="PGdropdownMenuButton_1-1" type="button" style="background: rgb(255,255,255);color: rgb(0,0,0);width: 100%;border-color: rgb(4,0,0);height: 30px;border-radius: 5px;"></button>
                                <div class="dropdown-menu pgdropdown" style="width: 100%;">
                                    <input type="text" id="PGdropdownSearchInput-1" class="form-control search">
                                    <a class="dropdown-item" href="#" onclick="selectProgram(this)">Video</a>
                                    <a class="dropdown-item" href="#" onclick="selectProgram(this)">Image</a>
                                    <a class="dropdown-item" href="#" onclick="selectProgram(this)">PPT</a>
                                    <a class="dropdown-item" href="#" onclick="selectProgram(this)">PDF</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row" style="margin-top: 10px;">
                        <div class="col-md-12">
                            <p style="margin-bottom: 2px;font-weight: bold;">Source URL</p>
                            <input type="text" style="width: 100%;border-radius: 5px;border-width: 0.8px;border-color: rgb(4,0,0);" placeholder=" Eg. Feng Shui">
                        </div>
                    </div>
                    <div class="row" style="margin-top: 10px;">
                        <div class="col-md-12">
                            <p style="margin-bottom: 2px;font-weight: bold;">Course Name</p>
                            <div class="dropdown" style="display: block;margin-left: 0px;width: 100%;height: 30px;text-align: left;"><button class="btn btn-primary dropdown-toggle text-end d-flex justify-content-end align-items-center form-control" aria-expanded="false" data-bs-toggle="dropdown" id="CSdropdownMenuButton-1" type="button" style="background: rgb(255,255,255);color: rgb(0,0,0);width: 100%;border-color: rgb(4,0,0);height: 30px;border-radius: 5px;"></button>
                                <div class="dropdown-menu csdropdown" style="width: 100%;"><input type="text" id="CSdropdownSearchInput-1" class="form-control search"><a class="dropdown-item course" href="#" onclick="selectCourse(this)">风水 course</a><a class="dropdown-item course" href="#" onclick="selectCourse(this)">冥想</a><a class="dropdown-item course" href="#" onclick="selectCourse(this)">财经</a></div>
                            </div>
                        </div>
                    </div>
                    <div class="row" style="margin-top: 10px;">
                        <div class="col-md-12">
                            <p style="margin-bottom: 2px;font-weight: bold;">Class Name</p>
                            <div class="dropdown" style="display: block;margin-left: 0px;width: 100%;height: 30px;text-align: left;"><button class="btn btn-primary dropdown-toggle text-end d-flex justify-content-end align-items-center form-control" aria-expanded="false" data-bs-toggle="dropdown" type="button" style="background: rgb(255,255,255);color: rgb(0,0,0);width: 100%;border-color: rgb(4,0,0);height: 30px;border-radius: 5px;"></button>
                                <div class="dropdown-menu csdropdown" style="width: 100%;"><input type="text" class="form-control search"><a class="dropdown-item" href="#" onclick="selectCourse(this)">Class A</a><a class="dropdown-item" href="#" onclick="selectCourse(this)">Class B</a><a class="dropdown-item" href="#" onclick="selectCourse(this)">Class C</a><a class="dropdown-item" href="#" onclick="selectCourse(this)">Class D</a></div>
                            </div>
                        </div>
                    </div>
                    <div class="row" style="margin-top: 10px;">
                        <div class="col-md-12">
                            <p style="margin-bottom: 2px;font-weight: bold;">Lesson Name</p>
                            <input id="lesson_title" type="text" style="width: 100%;border-radius: 5px;border-width: 0.8px;border-color: rgb(4,0,0);" placeholder=" Eg. Feng Shui">
                        </div>
                    </div>
                    <div class="row" style="margin-top: 10px;">
                        <div class="col-md-12">
                            <p style="margin-bottom: 2px;font-weight: bold;">Duration</p>
                        </div>
                    </div>
                    <div class="col-5 d-inline-block">
                        <input class="d-inline-block" id="lesson_duration" type="number" style="width: 50%;border-radius: 5px;">
                        <p class="d-inline-block" style="width: 30%;margin: 0px;padding: 0px 5px;">hours</p>
                    </div>
                    <div class="col-5 d-inline-block"><input class="d-inline-block" type="number" style="width: 50%;border-radius: 5px;">
                        <p class="d-inline-block" style="width: 30%;margin: 0px;padding: 0px 5px;">mins</p>
                    </div>
                    <div class="row" style="margin-top: 10px;">
                        <div class="col-md-12">
                            <p style="margin-bottom: 2px;font-weight: bold;">Description</p>
                            <textarea id="lesson_description" style="width: 100%;height: 150px;border-radius: 5px;" placeholder="Course Description"></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-lg-10 text-end" style="width: 100%;">
                            <button class="btn btn-primary" id="btnaddinventory-1" type="submit" data-bs-dismiss="offcanvas" style="background: rgb(78,115,223);margin: 0px 10px;">Edit</button>
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
                    <p id="lessonActivation"></p>
                </div>
                <div class="modal-footer">
                    <form id="updateLessonStatus">
                        @csrf
                        <input type="hidden" id="lessonStatusId" >
                        <button class="btn btn-primary" id="showAlertBtn-7" type="submit" data-bs-target="#modal-2" data-bs-toggle="modal" data-bs-dismiss="modal" style="background: rgb(231,74,59);">Yes</button>
                        <button class="btn btn-light" type="button" data-bs-dismiss="modal" style="background: rgb(13,110,253);color: rgb(255,255,255);">No</button>
                    </form>    
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", () => {
    const editButtons = document.querySelectorAll(".editBtn");
    const modal = new bootstrap.Offcanvas(document.getElementById('editLessonModal'));

    editButtons.forEach(btn => {
        btn.addEventListener("click", async () => {
            const id = btn.dataset.id;

            const response = await fetch(`/lesson/${id}/edit`);
            const data = await response.json();

            const lesson = data.lesson;
            const contentTypes = data.content_types;
            const fileUrl = data.fileUrl;

            document.getElementById("lesson_id").value = lesson.id;
            document.getElementById("lesson_title").value = lesson.title;
            document.getElementById("lesson_description").value = lesson.description || '';
            document.getElementById("lesson_duration").value = lesson.duration || '';

            // FIX: Populate dropdown options
            // const select = document.getElementById("content_type_edit");
            // select.innerHTML = ""; // Clear existing options

            // contentTypes.forEach((type) => {
            //     const option = document.createElement("option");
            //     option.value = type.value;
            //     option.textContent = type.name;

            //     if (lesson.content_type === type.value) {
            //        option.selected = true;
            //     }
            //     select.appendChild(option);
            // });

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
        
        //console.log(res);

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
    const toggleStatusModel = new bootstrap.Modal(document.getElementById('confirmStatusModal'));

    buttons.forEach(button => {
        button.addEventListener('click', async function() {
            const id = this.dataset.id;
            const row = this.closest('tr');
            const badge = row.querySelector('.status-cell span');
            const isCurrentlyActive = this.textContent.trim() === 'Active';
            const confirmMessage = `Are you sure you want to ${isCurrentlyActive ? 'deactivate' : 'activate'} this program?`;

            document.getElementById("lessonActivation").textContent = confirmMessage;
            document.getElementById("lessonStatusId").value = id;

            toggleStatusModel.show();
        });
    });

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