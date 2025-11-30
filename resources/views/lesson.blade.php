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
                                                    <th class="text-nowrap">Content Type</th>
                                                    <th class="text-nowrap">Duration</th>
                                                    <th class="text-nowrap text-start">Sequence Order</th>
                                                    <th class="text-nowrap text-start text-center">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr style="max-width: 49px;">
                                                    <td class="text-truncate" style="max-width: 200px;">#PG4897591</td>
                                                    <td class="text-truncate" style="max-width: 200px;">风水</td>
                                                    <td class="text-break" style="max-width: 50px;">test</td>
                                                    <td class="text-break" style="max-width: 50px;">Program A</td>
                                                    <td>Video</td>
                                                    <td>2 hrs</td>
                                                    <td class="text-start" contenteditable="true">1</td>
                                                    <td class="text-nowrap text-start text-center"><a class="btn btn-dark" role="button" style="width: 25px;height: 25px;padding: 3px 3px;text-align: center;margin: 0px 3px;background: rgba(242,242,242,0);border-style: none;" href="AdminOrderDetail.html"><i class="material-icons text-dark" id="showAlertBtn" style="font-size: 19px;--bs-primary: #4e73df;--bs-primary-rgb: 78,115,223;color: rgb(255,255,255);" type="button">remove_red_eye</i></a><a class="btn btn-dark" role="button" style="width: 25px;height: 25px;padding: 3px 3px;text-align: center;margin: 0px 5px;background: rgba(242,242,242,0);border-style: none;" href="AdminOrderDetail.html" data-bs-target="#offcanvas-2" data-bs-toggle="offcanvas"><i class="material-icons text-dark" id="showAlertBtn-5" style="font-size: 19px;--bs-primary: #4e73df;--bs-primary-rgb: 78,115,223;color: rgb(255,255,255);" type="button">edit</i></a><a class="btn btn-dark" role="button" style="width: 25px;height: 25px;padding: 3px 3px;text-align: center;margin: 0px 3px;background: rgba(242,242,242,0);border-style: none;" href="AdminOrderDetail.html" data-bs-target="#modal-1" data-bs-toggle="modal"><i class="material-icons text-dark" id="showAlertBtn-6" style="font-size: 19px;--bs-primary: #4e73df;--bs-primary-rgb: 78,115,223;color: rgb(255,255,255);" type="button">delete_forever</i></a></td>
                                                </tr>
                                                <tr>
                                                    <td class="text-truncate" style="max-width: 200px;">#PG1951798</td>
                                                    <td class="text-truncate" style="max-width: 200px;">身心疗愈</td>
                                                    <td class="text-break" style="max-width: 50px;">身心疗愈</td>
                                                    <td class="text-break" style="max-width: 50px;">Program B</td>
                                                    <td>PPT</td>
                                                    <td>15 mins</td>
                                                    <td class="text-start" contenteditable="true">2</td>
                                                    <td class="text-nowrap text-start text-center"><a class="btn btn-dark" role="button" style="width: 25px;height: 25px;padding: 3px 3px;text-align: center;margin: 0px 3px;background: rgba(242,242,242,0);border-style: none;" href="AdminOrderDetail.html"><i class="material-icons text-dark" id="showAlertBtn-1" style="font-size: 19px;--bs-primary: #4e73df;--bs-primary-rgb: 78,115,223;color: rgb(255,255,255);" type="button">remove_red_eye</i></a><a class="btn btn-dark" role="button" style="width: 25px;height: 25px;padding: 3px 3px;text-align: center;margin: 0px 5px;background: rgba(242,242,242,0);border-style: none;" href="AdminOrderDetail.html" data-bs-target="#offcanvas-2" data-bs-toggle="offcanvas"><i class="material-icons text-dark" id="showAlertBtn-8" style="font-size: 19px;--bs-primary: #4e73df;--bs-primary-rgb: 78,115,223;color: rgb(255,255,255);" type="button">edit</i></a><a class="btn btn-dark" role="button" style="width: 25px;height: 25px;padding: 3px 3px;text-align: center;margin: 0px 3px;background: rgba(242,242,242,0);border-style: none;" href="AdminOrderDetail.html" data-bs-target="#modal-1" data-bs-toggle="modal"><i class="material-icons text-dark" id="showAlertBtn-9" style="font-size: 20px;--bs-primary: #4e73df;--bs-primary-rgb: 78,115,223;color: rgb(255,255,255);" type="button">delete_forever</i></a></td>
                                                </tr>
                                                <tr>
                                                    <td class="text-truncate" style="max-width: 200px;">#PG6159375</td>
                                                    <td class="text-truncate" style="max-width: 200px;">财经</td>
                                                    <td class="text-break" style="max-width: 50px;">财经</td>
                                                    <td class="text-break" style="max-width: 50px;">Program C</td>
                                                    <td>PDF</td>
                                                    <td>1.75 hrs</td>
                                                    <td class="text-start" contenteditable="true">3</td>
                                                    <td class="text-nowrap text-start text-center"><a class="btn btn-dark" role="button" style="width: 25px;height: 25px;padding: 3px 3px;text-align: center;margin: 0px 3px;background: rgba(242,242,242,0);border-style: none;" href="AdminOrderDetail.html"><i class="material-icons text-dark" id="showAlertBtn-2" style="font-size: 19px;--bs-primary: #4e73df;--bs-primary-rgb: 78,115,223;color: rgb(255,255,255);" type="button">remove_red_eye</i></a><a class="btn btn-dark" role="button" style="width: 25px;height: 25px;padding: 3px 3px;text-align: center;margin: 0px 5px;background: rgba(242,242,242,0);border-style: none;" href="AdminOrderDetail.html" data-bs-target="#offcanvas-2" data-bs-toggle="offcanvas"><i class="material-icons text-dark" id="showAlertBtn-10" style="font-size: 19px;--bs-primary: #4e73df;--bs-primary-rgb: 78,115,223;color: rgb(255,255,255);" type="button">edit</i></a><a class="btn btn-dark" role="button" style="width: 25px;height: 25px;padding: 3px 3px;text-align: center;margin: 0px 3px;background: rgba(242,242,242,0);border-style: none;" href="AdminOrderDetail.html" data-bs-target="#modal-1" data-bs-toggle="modal"><i class="material-icons text-dark" id="showAlertBtn-11" style="font-size: 20px;--bs-primary: #4e73df;--bs-primary-rgb: 78,115,223;color: rgb(255,255,255);" type="button">delete_forever</i></a></td>
                                                </tr>
                                                <tr>
                                                    <td class="text-truncate" style="max-width: 200px;">#PG1954621</td>
                                                    <td class="text-truncate" style="max-width: 200px;">自我成长</td>
                                                    <td class="text-break" style="max-width: 50px;">自我成长</td>
                                                    <td class="text-break" style="max-width: 50px;">Program D</td>
                                                    <td>Image</td>
                                                    <td>5 mins</td>
                                                    <td class="text-start" contenteditable="true">4</td>
                                                    <td class="text-nowrap text-start text-center"><a class="btn btn-dark" role="button" style="width: 25px;height: 25px;padding: 3px 3px;text-align: center;margin: 0px 3px;background: rgba(242,242,242,0);border-style: none;" href="AdminOrderDetail.html"><i class="material-icons text-dark" id="showAlertBtn-3" style="font-size: 19px;--bs-primary: #4e73df;--bs-primary-rgb: 78,115,223;color: rgb(255,255,255);" type="button">remove_red_eye</i></a><a class="btn btn-dark" role="button" style="width: 25px;height: 25px;padding: 3px 3px;text-align: center;margin: 0px 5px;background: rgba(242,242,242,0);border-style: none;" href="AdminOrderDetail.html" data-bs-target="#offcanvas-2" data-bs-toggle="offcanvas"><i class="material-icons text-dark" id="showAlertBtn-12" style="font-size: 19px;--bs-primary: #4e73df;--bs-primary-rgb: 78,115,223;color: rgb(255,255,255);" type="button">edit</i></a><a class="btn btn-dark" role="button" style="width: 25px;height: 25px;padding: 3px 3px;text-align: center;margin: 0px 3px;background: rgba(242,242,242,0);border-style: none;" href="AdminOrderDetail.html" data-bs-target="#modal-1" data-bs-toggle="modal"><i class="material-icons text-dark" id="showAlertBtn-13" style="font-size: 19px;--bs-primary: #4e73df;--bs-primary-rgb: 78,115,223;color: rgb(255,255,255);" type="button">delete_forever</i></a></td>
                                                </tr>
                                                <tr>
                                                    <td class="text-truncate" style="max-width: 200px;">#PG1948568</td>
                                                    <td class="text-truncate" style="max-width: 200px;">易数</td>
                                                    <td class="text-break" style="max-width: 50px;">易数</td>
                                                    <td class="text-break" style="max-width: 50px;">Program E</td>
                                                    <td>Video</td>
                                                    <td>1.5 hrs</td>
                                                    <td class="text-start" contenteditable="true">5</td>
                                                    <td class="text-nowrap text-start text-center"><a class="btn btn-dark" role="button" style="width: 25px;height: 25px;padding: 3px 3px;text-align: center;margin: 0px 3px;background: rgba(242,242,242,0);border-style: none;" href="AdminOrderDetail.html"><i class="material-icons text-dark" id="showAlertBtn-4" style="font-size: 19px;--bs-primary: #4e73df;--bs-primary-rgb: 78,115,223;color: rgb(255,255,255);" type="button">remove_red_eye</i></a><a class="btn btn-dark" role="button" style="width: 25px;height: 25px;padding: 3px 3px;text-align: center;margin: 0px 5px;background: rgba(242,242,242,0);border-style: none;" href="AdminOrderDetail.html" data-bs-target="#offcanvas-2" data-bs-toggle="offcanvas"><i class="material-icons text-dark" id="showAlertBtn-14" style="font-size: 19px;--bs-primary: #4e73df;--bs-primary-rgb: 78,115,223;color: rgb(255,255,255);" type="button">edit</i></a><a class="btn btn-dark" role="button" style="width: 25px;height: 25px;padding: 3px 3px;text-align: center;margin: 0px 3px;background: rgba(242,242,242,0);border-style: none;" href="AdminOrderDetail.html" data-bs-target="#modal-1" data-bs-toggle="modal"><i class="material-icons text-dark" id="showAlertBtn-15" style="font-size: 19px;--bs-primary: #4e73df;--bs-primary-rgb: 78,115,223;color: rgb(255,255,255);" type="button">delete_forever</i></a></td>
                                                </tr>
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
                <div class="row" style="padding-bottom: 10px;">
                    <div class="col-md-12">
                        <p style="margin-bottom: 2px;font-weight: bold;">Upload File</p><input type="file">
                    </div>
                </div>
                <div class="row" style="margin-top: 10px;">
                    <div class="col-md-12 col-xxl-12">
                        <p style="margin-bottom: 2px;font-weight: bold;">Content Type</p>
                        <div class="dropdown" style="display: block;margin-left: 0px;width: 100%;height: 30px;text-align: left;"><button class="btn btn-primary dropdown-toggle text-end d-flex justify-content-end align-items-center form-control" aria-expanded="false" data-bs-toggle="dropdown" id="PGdropdownMenuButton_1" type="button" style="background: rgb(255,255,255);color: rgb(0,0,0);width: 100%;border-color: rgb(4,0,0);height: 30px;border-radius: 5px;"></button>
                            <div class="dropdown-menu pgdropdown" style="width: 100%;"><input type="text" id="PGdropdownSearchInput" class="form-control search"><a class="dropdown-item" href="#" onclick="selectProgram(this)">Video</a><a class="dropdown-item" href="#" onclick="selectProgram(this)">Image</a><a class="dropdown-item" href="#" onclick="selectProgram(this)">PPT</a><a class="dropdown-item" href="#" onclick="selectProgram(this)">PDF</a></div>
                        </div>
                    </div>
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
                            <div class="dropdown-menu csdropdown" style="width: 100%;"><input type="text" class="form-control search"><a class="dropdown-item" href="#" onclick="selectCourse(this)">Class A</a><a class="dropdown-item" href="#" onclick="selectCourse(this)">Class B</a><a class="dropdown-item" href="#" onclick="selectCourse(this)">Class C</a><a class="dropdown-item" href="#" onclick="selectCourse(this)">Class D</a></div>
                        </div>
                    </div>
                </div>
                <div class="row" style="margin-top: 10px;">
                    <div class="col-md-12">
                        <p style="margin-bottom: 2px;font-weight: bold;">Lesson Name</p><input type="text" style="width: 100%;border-radius: 5px;border-width: 0.8px;border-color: rgb(4,0,0);" placeholder=" Eg. Feng Shui">
                    </div>
                </div>
                <div class="row" style="margin-top: 10px;">
                    <div class="col-md-12">
                        <p style="margin-bottom: 2px;font-weight: bold;">Duration</p>
                    </div>
                </div>
                <div class="col-5 d-inline-block"><input class="d-inline-block" type="number" style="width: 50%;border-radius: 5px;">
                    <p class="d-inline-block" style="width: 30%;margin: 0px;padding: 0px 5px;">hours</p>
                </div>
                <div class="col-5 d-inline-block"><input class="d-inline-block" type="number" style="width: 50%;border-radius: 5px;">
                    <p class="d-inline-block" style="width: 30%;margin: 0px;padding: 0px 5px;">mins</p>
                </div>
                <div class="row" style="margin-top: 10px;">
                    <div class="col-md-12">
                        <p style="margin-bottom: 2px;font-weight: bold;">Description</p><textarea style="width: 100%;height: 150px;border-radius: 5px;" placeholder="Course Description"></textarea>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 col-lg-10 text-end" style="width: 100%;"><button class="btn btn-primary" id="btnaddinventory" type="button" data-bs-dismiss="offcanvas" style="background: rgb(78,115,223);margin: 0px 10px;">Add</button><button class="btn btn-primary" type="button" data-bs-dismiss="offcanvas" style="background: rgb(231,74,59);">Cancel</button></div>
                </div>
            </div>
        </div>
    </div>
    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvas-2">
        <div class="offcanvas-header">
            <h4 class="offcanvas-title" style="font-weight: bold;">Edit Program</h4><button class="btn-close" type="button" aria-label="Close" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body" style="border-color: rgb(255,255,255);">
            <div class="container">
                <div class="row" style="padding-bottom: 10px;">
                    <div class="col-md-12">
                        <p style="margin-bottom: 2px;font-weight: bold;">Upload File</p><input type="file">
                    </div>
                </div>
                <div class="row" style="margin-top: 10px;">
                    <div class="col-md-12 col-xxl-12">
                        <p style="margin-bottom: 2px;font-weight: bold;">Content Type</p>
                        <div class="dropdown" style="display: block;margin-left: 0px;width: 100%;height: 30px;text-align: left;"><button class="btn btn-primary dropdown-toggle text-end d-flex justify-content-end align-items-center form-control" aria-expanded="false" data-bs-toggle="dropdown" id="PGdropdownMenuButton_1-1" type="button" style="background: rgb(255,255,255);color: rgb(0,0,0);width: 100%;border-color: rgb(4,0,0);height: 30px;border-radius: 5px;"></button>
                            <div class="dropdown-menu pgdropdown" style="width: 100%;"><input type="text" id="PGdropdownSearchInput-1" class="form-control search"><a class="dropdown-item" href="#" onclick="selectProgram(this)">Video</a><a class="dropdown-item" href="#" onclick="selectProgram(this)">Image</a><a class="dropdown-item" href="#" onclick="selectProgram(this)">PPT</a><a class="dropdown-item" href="#" onclick="selectProgram(this)">PDF</a></div>
                        </div>
                    </div>
                </div>
                <div class="row" style="margin-top: 10px;">
                    <div class="col-md-12">
                        <p style="margin-bottom: 2px;font-weight: bold;">Source URL</p><input type="text" style="width: 100%;border-radius: 5px;border-width: 0.8px;border-color: rgb(4,0,0);" placeholder=" Eg. Feng Shui">
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
                        <p style="margin-bottom: 2px;font-weight: bold;">Lesson Name</p><input type="text" style="width: 100%;border-radius: 5px;border-width: 0.8px;border-color: rgb(4,0,0);" placeholder=" Eg. Feng Shui">
                    </div>
                </div>
                <div class="row" style="margin-top: 10px;">
                    <div class="col-md-12">
                        <p style="margin-bottom: 2px;font-weight: bold;">Duration</p>
                    </div>
                </div>
                <div class="col-5 d-inline-block"><input class="d-inline-block" type="number" style="width: 50%;border-radius: 5px;">
                    <p class="d-inline-block" style="width: 30%;margin: 0px;padding: 0px 5px;">hours</p>
                </div>
                <div class="col-5 d-inline-block"><input class="d-inline-block" type="number" style="width: 50%;border-radius: 5px;">
                    <p class="d-inline-block" style="width: 30%;margin: 0px;padding: 0px 5px;">mins</p>
                </div>
                <div class="row" style="margin-top: 10px;">
                    <div class="col-md-12">
                        <p style="margin-bottom: 2px;font-weight: bold;">Description</p><textarea style="width: 100%;height: 150px;border-radius: 5px;" placeholder="Course Description"></textarea>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 col-lg-10 text-end" style="width: 100%;"><button class="btn btn-primary" id="btnaddinventory-1" type="button" data-bs-dismiss="offcanvas" style="background: rgb(78,115,223);margin: 0px 10px;">Edit</button><button class="btn btn-primary" type="button" data-bs-dismiss="offcanvas" style="background: rgb(231,74,59);">Cancel</button></div>
                </div>
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
                    <p>Are you confirm to deactivate this program?</p>
                </div>
                <div class="modal-footer"><button class="btn btn-primary" id="showAlertBtn-7" type="button" data-bs-target="#modal-2" data-bs-toggle="modal" data-bs-dismiss="modal" style="background: rgb(231,74,59);">Yes</button><button class="btn btn-light" type="button" data-bs-dismiss="modal" style="background: rgb(13,110,253);color: rgb(255,255,255);">No</button></div>
            </div>
        </div>
    </div>
@endsection