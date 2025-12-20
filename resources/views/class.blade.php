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
                                        <h1 class="d-inline-block" style="margin: 0px 0px;margin-bottom: 0px;height: 100%;padding: 5px 0px;">Class Management</h1>
                                        <p class="d-inline-block invisible" style="margin: 0px 10px;">course name</p>
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
                                                <th class="text-nowrap">Class ID</th>
                                                <th class="text-nowrap">Class Name</th>
                                                <th class="text-nowrap">Cover Image</th>
                                                <th class="text-nowrap">Description</th>

                                                @if ($show!=='all')
                                                    <th class="text-nowrap">Course Name</th>
                                                    <th class="text-nowrap">Program Name</th>
                                                @endif

                                                <th class="text-nowrap">Date Started</th>
                                                <th class="text-nowrap">Dependent Class</th>
                                                <th class="text-nowrap text-start">Total Students</th>
                                                <th class="text-nowrap">Status</th>
                                                <th class="text-nowrap text-center">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            @if ($show==='all')
                                                @forelse ($classes as $class)
                                                    <tr style="max-width: 49px;">
                                                        <td class="text-truncate" style="max-width: 200px;">{{ $class->id }}</td>
                                                        <td class="text-truncate" style="max-width: 200px;">{{ $class->title }}</td>
                                                        <td class="text-truncate" style="max-width: 200px;">
                                                            <img class="img-fluid" width="299" height="180" style="max-width: 120px;max-height: 100px;" src="assets/img/OIP.webp">
                                                        </td>
                                                        <td class="text-break" style="max-width: 50px;">{{ $class->description }}</td>
                                                        <td>{{ $class->created_at->format('Y-m-d') }}</td>
                                                        <td class="text-truncate">1</td>
                                                        <td class="text-start">10</td>
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
                                                            <button class="btn btn-dark editBtn" id='editBtn' data-id="{{ $class->id }}" role="button" style="width: 25px;height: 25px;padding: 3px 3px;text-align: center;margin: 0px 5px;background: rgba(242,242,242,0);border-style: none;" href="AdminOrderDetail.html" data-bs-target="#offcanvas-2" data-bs-toggle="offcanvas">
                                                                <i class="material-icons text-dark" id="showAlertBtn-5" style="font-size: 19px;--bs-primary: #4e73df;--bs-primary-rgb: 78,115,223;color: rgb(255,255,255);" type="button">edit</i>
                                                            </button>
                                                            <button class="btn btn-dark toggleStatus" data-id="{{ $class->id }}" role="button" style="width: 25px;height: 25px;padding: 3px 3px;text-align: center;margin: 0px 3px;background: rgba(242,242,242,0);border-style: none;" href="AdminOrderDetail.html" data-bs-target="#modal-1" data-bs-toggle="modal">
                                                                <i class="material-icons text-dark" id="showAlertBtn-6" style="font-size: 19px;--bs-primary: #4e73df;--bs-primary-rgb: 78,115,223;color: rgb(255,255,255);" type="button">do_not_disturb_alt</i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    {{-- <tr>
                                                        <td colspan="11" class="text-center">No classes found.</td>
                                                    </tr> --}}
                                                @endforelse
                                            @else
                                                @forelse ($course->classes as $class)
                                                    <tr style="max-width: 49px;">
                                                        <td class="text-truncate" style="max-width: 200px;">{{ $class->id }}</td>
                                                        <td class="text-truncate" style="max-width: 200px;">{{ $class->title }}</td>
                                                        <td class="text-truncate" style="max-width: 200px;">
                                                            <img class="img-fluid" width="299" height="180" style="max-width: 120px;max-height: 100px;" src="assets/img/OIP.webp">
                                                        </td>
                                                        <td class="text-break" style="max-width: 50px;">{{ $class->description }}</td>
                                                        <td class="text-break" style="max-width: 50px;">{{ $course->title }}</td>
                                                        <td class="text-break" style="max-width: 50px;">{{ $course->program->title }}</td>
                                                        <td>{{ $class->created_at->format('Y-m-d') }}</td>
                                                        <td class="text-truncate">1</td>
                                                        <td class="text-start">10</td>
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
                                                            <button class="btn btn-dark editBtn" id='editBtn' data-id="{{ $class->id }}" role="button" style="width: 25px;height: 25px;padding: 3px 3px;text-align: center;margin: 0px 5px;background: rgba(242,242,242,0);border-style: none;" href="AdminOrderDetail.html" data-bs-target="#offcanvas-2" data-bs-toggle="offcanvas">
                                                                <i class="material-icons text-dark" id="showAlertBtn-5" style="font-size: 19px;--bs-primary: #4e73df;--bs-primary-rgb: 78,115,223;color: rgb(255,255,255);" type="button">edit</i>
                                                            </button>
                                                            <button class="btn btn-dark toggleStatus" data-id="{{ $class->id }}" role="button" style="width: 25px;height: 25px;padding: 3px 3px;text-align: center;margin: 0px 3px;background: rgba(242,242,242,0);border-style: none;" href="AdminOrderDetail.html" data-bs-target="#modal-1" data-bs-toggle="modal">
                                                                <i class="material-icons text-dark" id="showAlertBtn-6" style="font-size: 19px;--bs-primary: #4e73df;--bs-primary-rgb: 78,115,223;color: rgb(255,255,255);" type="button">do_not_disturb_alt</i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    {{-- <tr>
                                                        <td colspan="11" class="text-center">No classes found.</td>
                                                    </tr> --}}
                                                @endforelse
                                            @endif
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
            <h4 class="offcanvas-title" style="font-weight: bold;">Add New Course</h4><button class="btn-close" type="button" aria-label="Close" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body" style="border-color: rgb(255,255,255);">
            <div class="container">
                <form method="POST" action="{{route('class.store')}}">
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
                                <div class="dropdown-menu pgdropdown" style="width: 100%;"><input type="text" id="PGdropdownSearchInput" class="form-control search"><a class="dropdown-item program" href="#" onclick="selectProgram(this)">风水</a><a class="dropdown-item program" href="#" onclick="selectProgram(this)">冥想</a><a class="dropdown-item program" href="#" onclick="selectProgram(this)">财经</a></div>
                            </div>
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
                            <input type="text" name='title' id="className" style="width: 100%;border-radius: 5px;border-width: 0.8px;border-color: rgb(4,0,0);" placeholder="Eg. Feng Shui">
                        </div>
                    </div>
                    <div class="row" style="margin-top: 10px;">
                        <div class="col-md-12">
                            <p style="margin-bottom: 2px;font-weight: bold;">Dependent Class</p>
                            <div class="dropdown" style="display: block;margin-left: 0px;width: 100%;height: 30px;text-align: left;"><button class="btn btn-primary dropdown-toggle text-end d-flex justify-content-end align-items-center form-control" aria-expanded="false" data-bs-toggle="dropdown" type="button" style="background: rgb(255,255,255);color: rgb(0,0,0);width: 100%;border-color: rgb(4,0,0);height: 30px;border-radius: 5px;"></button>
                                <div class="dropdown-menu csdropdown" style="width: 100%;"><input type="text" class="form-control search"><a class="dropdown-item" href="#" onclick="selectCourse(this)">Class A</a><a class="dropdown-item" href="#" onclick="selectCourse(this)">Class B</a><a class="dropdown-item" href="#" onclick="selectCourse(this)">Class C</a><a class="dropdown-item" href="#" onclick="selectCourse(this)">Class D</a></div>
                            </div>
                        </div>
                    </div>
                    <div class="row" style="margin-top: 10px;">
                        <div class="col-md-12">
                            <p style="margin-bottom: 2px;font-weight: bold;">Description</p>
                            <textarea name='description' id="classDescription" style="width: 100%;height: 150px;border-radius: 5px;" placeholder="Course Description"></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-lg-10 text-end" style="width: 100%;">
                            <button class="btn btn-primary" id="btnaddinventory" type="submit" data-bs-dismiss="offcanvas" style="background: rgb(78,115,223);margin: 0px 10px;">Add</button>
                            <button class="btn btn-primary" type="button" data-bs-dismiss="offcanvas" style="background: rgb(231,74,59);">Cancel</button></div>
                    </div>
                </form>    
            </div>
        </div>
    </div>
    <div class="offcanvas offcanvas-end" tabindex="-1" id="editClassModal">
        <div class="offcanvas-header">
            <h4 class="offcanvas-title" style="font-weight: bold;">Edit Class</h4><button class="btn-close" type="button" aria-label="Close" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body" style="border-color: rgb(255,255,255);">
            <div class="container">
                <form id="updateClassForm">
                    @csrf
                    <input type="hidden" id="class_id">
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
                                <div class="dropdown-menu e_pgdropdown" style="width: 100%;"><input type="text" id="E_PGdropdownSearchInput" class="form-control search"><a class="dropdown-item e_program" href="#" onclick="selectProgram(this)">风水</a><a class="dropdown-item e_program" href="#" onclick="selectProgram(this)">冥想</a><a class="dropdown-item e_program" href="#" onclick="selectProgram(this)">财经</a></div>
                            </div>
                        </div>
                    </div>
                    <div class="row" style="margin: 0px -12px;margin-top: 15px;">
                        <div class="col-md-12">
                            <p style="margin-bottom: 2px;font-weight: bold;">Course Name</p>
                            <div class="dropdown" style="display: block;margin-left: 0px;width: 100%;height: 30px;text-align: left;border-color: rgb(0,4,8);"><button class="btn btn-primary dropdown-toggle text-end d-flex justify-content-end align-items-center form-control" aria-expanded="false" data-bs-toggle="dropdown" id="E_CSdropdownMenuButton" type="button" style="background: rgb(255,255,255);color: rgb(0,0,0);width: 100%;height: 30px;border-radius: 5px;border-color: rgb(0,0,0);"></button>
                                <div class="dropdown-menu e_csdropdown" style="width: 100%;"><input type="text" id="E_CSdropdownSearchInput" class="form-control search"><a class="dropdown-item e_course" href="#" onclick="selectCourse(this)">风水</a><a class="dropdown-item e_course" href="#" onclick="selecCourse(this)">冥想</a><a class="dropdown-item e_course" href="#" onclick="selecCourse(this)">财经</a></div>
                            </div>
                        </div>
                    </div>
                    <div class="row" style="margin-top: 15px;">
                        <div class="col-md-12">
                            <p style="margin-bottom: 2px;font-weight: bold;">Class Name</p>
                            <input type="text" id="class_title" style="width: 100%;border-radius: 5px;border-width: 0.8px;border-color: rgb(4,0,0);" placeholder="Eg. Feng Shui">
                        </div>
                    </div>
                    <div class="row" style="margin-top: 10px;">
                        <div class="col-md-12">
                            <p style="margin-bottom: 2px;font-weight: bold;">Dependent Class</p>
                            <div class="dropdown" style="display: block;margin-left: 0px;width: 100%;height: 30px;text-align: left;"><button class="btn btn-primary dropdown-toggle text-end d-flex justify-content-end align-items-center form-control" aria-expanded="false" data-bs-toggle="dropdown" type="button" style="background: rgb(255,255,255);color: rgb(0,0,0);width: 100%;border-color: rgb(4,0,0);height: 30px;border-radius: 5px;"></button>
                                <div class="dropdown-menu csdropdown" style="width: 100%;"><input type="text" class="form-control search"><a class="dropdown-item" href="#" onclick="selectCourse(this)">Class A</a><a class="dropdown-item" href="#" onclick="selectCourse(this)">Class B</a><a class="dropdown-item" href="#" onclick="selectCourse(this)">Class C</a><a class="dropdown-item" href="#" onclick="selectCourse(this)">Class D</a></div>
                            </div>
                        </div>
                    </div>
                    <div class="row" style="margin-top: 15px;">
                        <div class="col-md-12">
                            <p style="margin-bottom: 2px;font-weight: bold;">Description</p>
                            <textarea id="class_description" style="width: 100%;height: 130px;border-radius: 5px;" placeholder="Program Description"></textarea>
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
    <div class="modal fade" role="dialog" tabindex="-1" id="modal-1">
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
@endsection
@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", () => {
    const editButtons = document.querySelectorAll(".editBtn");
    const modal = new bootstrap.Offcanvas(document.getElementById('editClassModal'));

    editButtons.forEach(btn => {
        btn.addEventListener("click", async () => {
            const id = btn.dataset.id;

            const response = await fetch(`/class/${id}/edit`);
            const class_= await response.json();

            console.log(class_);

            document.getElementById("class_id").value = class_.id;
            document.getElementById("class_title").value = class_.title;
            document.getElementById("class_description").value = class_.description || '';

            modal.show();
        });
    });

    // Update form submit
    document.getElementById("updateClassForm").addEventListener("submit", async (e) => {
        e.preventDefault();

        const id = document.getElementById("class_id").value;
        const formData = new FormData();
        formData.append('_token', document.querySelector('input[name="_token"]').value);
        formData.append('title', document.getElementById("class_title").value);
        formData.append('description', document.getElementById("class_description").value);

        const res = await fetch(`/class/${id}/update`, {
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

            document.getElementById("classActivation").textContent = confirmMessage;
            document.getElementById("classStatusId").value = id;

        });
    });

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
    
