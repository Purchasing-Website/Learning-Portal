<!DOCTYPE html>
<html data-bs-theme="light" lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Dashboard - Brand</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i&amp;display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Noto+Sans:300,400,500,600,700&amp;display=swap">
    <link rel="stylesheet" href="assets/fonts/fontawesome-all.min.css">
    <link rel="stylesheet" href="assets/fonts/ionicons.min.css">
    <link rel="stylesheet" href="assets/fonts/material-icons.min.css">
    <link rel="stylesheet" href="assets/css/bss-overrides.css">
    <link rel="stylesheet" href="assets/css/alert.css">
    <link rel="stylesheet" href="assets/css/Form-Input.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.7/css/dataTables.bootstrap5.css">
    <link rel="stylesheet" href="assets/css/Checkbox-Input.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.6.5/css/buttons.dataTables.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="assets/css/iframe.css">
    <link rel="stylesheet" href="assets/css/LessonArrangement.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/offcanvas.css">
    <link rel="stylesheet" href="assets/css/input.css">
    <link rel="stylesheet" href="assets/css/student.css">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
</head>

<body id="page-top">
    <div id="wrapper">
        <nav class="navbar bg-dark align-items-start p-0 sidebar sidebar-dark accordion bg-gradient-primary navbar-dark" style="--bs-primary: #131318;--bs-primary-rgb: 19,19,24;color: rgb(24,24,25);background: var(--bs-black);">
            <div class="container-fluid d-flex flex-column p-0"><a class="navbar-brand d-flex justify-content-center align-items-center m-0 sidebar-brand" href="#">
                    <div class="sidebar-brand-icon rotate-n-15"><i class="fas fa-laugh-wink"></i></div>
                    <div class="mx-3 sidebar-brand-text"><span>hao lin</span></div>
                </a>
                <hr class="my-0 sidebar-divider">
                <ul class="navbar-nav text-light" id="accordionSidebar">
                    <li class="nav-item"><a class="nav-link" href="Dashboard.html"><i class="fas fa-tachometer-alt" style="width: 20px;height: 14px;margin: 0px;text-align: center;"></i><span style="padding: 0px 4px;">Dashboard</span></a></li>
                    <li class="nav-item"><a class="nav-link" href="Student%20List.html"><i class="fas fa-user" style="width: 20px;height: 20px;margin-right: 0px;text-align: center;"></i><span style="padding: 0px 4px;">Student</span></a></li>
                    <li class="nav-item"><a class="nav-link" href="Program.html"><i class="fas fa-user-graduate" style="margin-right: 0px;width: 20px;text-align: center;"></i><span style="padding: 0px 4px;">Program</span></a></li>
                    <li class="nav-item"><a class="nav-link" href="Course.html"><i class="fas fa-book-reader" style="width: 20px;height: 14px;margin: 0px;text-align: center;"></i><span style="padding: 0px 4px;">Course</span></a></li>
                    <li class="nav-item"><a class="nav-link active" href="Class.html"><i class="fas fa-users" style="margin-right: 0px;width: 20px;text-align: center;"></i><span style="padding: 0px 4px;">Class</span></a></li>
                    <li class="nav-item"><a class="nav-link" href="Lesson.html"><i class="fas fa-book" style="margin-right: 0px;text-align: center;width: 20px;"></i><span style="padding: 0px 4px;">Lesson</span></a></li>
                    <li class="nav-item"><a class="nav-link" href="Quiz.html"><i class="fas fa-brain" style="width: 20px;height: 20px;margin: 0px;text-align: center;"></i><span style="padding: 0px 4px;">Quiz</span></a></li>
                    <li class="nav-item"><a class="nav-link" href="Login.html"><svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icon-tabler-logout" style="width: 20px;height: 14px;text-align: center;">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <path d="M14 8v-2a2 2 0 0 0 -2 -2h-7a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h7a2 2 0 0 0 2 -2v-2"></path>
                                <path d="M9 12h12l-3 -3"></path>
                                <path d="M18 15l3 -3"></path>
                            </svg><span style="padding: 0px 4px;">Logout</span></a></li>
                </ul>
                <div class="text-center d-none d-md-inline"><button class="btn rounded-circle border-0" id="sidebarToggle" type="button"></button></div>
            </div>
        </nav>
        <div class="d-flex flex-column" id="content-wrapper">
            <div id="content">
                <nav class="navbar navbar-expand bg-dark shadow mb-4 topbar static-top navbar-light" style="margin: 0px 0px;">
                    <div class="container-fluid"><button class="btn btn-link d-md-none me-3 rounded-circle" id="sidebarToggleTop" type="button"><i class="fas fa-bars"></i></button>
                        <ul class="navbar-nav flex-nowrap ms-auto">
                            <li class="nav-item dropdown d-sm-none no-arrow"><a class="dropdown-toggle nav-link" aria-expanded="false" data-bs-toggle="dropdown" href="#"><i class="fas fa-search"></i></a>
                                <div class="dropdown-menu p-3 dropdown-menu-end animated--grow-in" aria-labelledby="searchDropdown">
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
                            <li class="nav-item dropdown no-arrow">
                                <div class="nav-item dropdown no-arrow"><a class="dropdown-toggle nav-link" aria-expanded="false" data-bs-toggle="dropdown" href="#"><span class="d-none d-lg-inline me-2 text-gray-600 small">Khye Shen</span><i class="far fa-user d-xl-flex justify-content-xl-center align-items-xl-center" style="font-size: 28px;width: 32px;height: 32px;"></i></a>
                                    <div class="dropdown-menu shadow dropdown-menu-end animated--grow-in"><a class="dropdown-item" href="ChangePassword.html"><i class="fab fa-expeditedssl me-2 fa-sm fa-fw text-gray-400"></i>&nbsp;Change Password</a>
                                        <div class="dropdown-divider"></div><a class="dropdown-item" href="Login.html"><i class="fas fa-sign-out-alt me-2 fa-sm fa-fw text-gray-400"></i>&nbsp;Logout</a>
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
                                                    <th class="text-nowrap">Course Name</th>
                                                    <th class="text-nowrap">Date Started</th>
                                                    <th class="text-nowrap">Dependent Class</th>
                                                    <th class="text-nowrap text-start">Total Students</th>
                                                    <th class="text-nowrap">Status</th>
                                                    <th class="text-nowrap text-center">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr style="max-width: 49px;">
                                                    <td class="text-truncate" style="max-width: 200px;">#PG4897591</td>
                                                    <td class="text-truncate" style="max-width: 200px;">风水</td>
                                                    <td class="text-truncate" style="max-width: 200px;"><img class="img-fluid" width="299" height="180" style="max-width: 120px;max-height: 100px;" src="assets/img/OIP.webp"></td>
                                                    <td class="text-break" style="max-width: 50px;">test</td>
                                                    <td class="text-break" style="max-width: 50px;">Program A</td>
                                                    <td>6/9/2024</td>
                                                    <td class="text-truncate">1</td>
                                                    <td class="text-start">10</td>
                                                    <td>Active<div class="custom__checkbox-wrap"><input type="checkbox" id="checkBox-id" class="d-none checkbox"></div>
                                                    </td>
                                                    <td class="text-nowrap text-start text-center"><a class="btn btn-dark" role="button" style="width: 25px;height: 25px;padding: 3px 3px;text-align: center;margin: 0px 3px;background: rgba(242,242,242,0);border-style: none;" href="Lesson.html"><i class="material-icons text-dark" id="showAlertBtn" style="font-size: 19px;--bs-primary: #4e73df;--bs-primary-rgb: 78,115,223;color: rgb(255,255,255);" type="button">remove_red_eye</i></a><a class="btn btn-dark" role="button" style="width: 25px;height: 25px;padding: 3px 3px;text-align: center;margin: 0px 3px;background: rgba(242,242,242,0);border-style: none;" href="LessonArrange.html"><i class="material-icons text-dark" id="showAlertBtn-16" style="font-size: 19px;--bs-primary: #4e73df;--bs-primary-rgb: 78,115,223;color: rgb(255,255,255);" type="button">low_priority</i></a><button class="btn btn-dark" type="button" style="width: 25px;height: 25px;padding: 3px 3px;text-align: center;margin: 0px 3px;background: rgba(242,242,242,0);border-style: none;" data-bs-target="#EditClass" data-bs-toggle="offcanvas"><i class="material-icons text-dark" id="showAlertBtn-5" style="font-size: 19px;--bs-primary: #4e73df;--bs-primary-rgb: 78,115,223;color: rgb(255,255,255);" type="button">edit</i></button><button class="btn btn-dark" type="button" style="width: 25px;height: 25px;padding: 3px 3px;text-align: center;margin: 0px 3px;background: rgba(242,242,242,0);border-style: none;" data-bs-target="#modal-1" data-bs-toggle="modal"><i class="material-icons text-dark" id="showAlertBtn-6" style="font-size: 19px;--bs-primary: #4e73df;--bs-primary-rgb: 78,115,223;color: rgb(255,255,255);" type="button">do_not_disturb_alt</i></button></td>
                                                </tr>
                                                <tr>
                                                    <td class="text-truncate" style="max-width: 200px;">#PG1951798</td>
                                                    <td class="text-truncate" style="max-width: 200px;">身心疗愈</td>
                                                    <td class="text-truncate" style="max-width: 200px;"><img class="img-fluid" width="299" height="180" style="max-width: 120px;max-height: 100px;" src="assets/img/OIP.webp"></td>
                                                    <td class="text-break" style="max-width: 50px;">身心疗愈</td>
                                                    <td class="text-break" style="max-width: 50px;">Program B</td>
                                                    <td>6/9/2024</td>
                                                    <td class="text-truncate">2</td>
                                                    <td class="text-start">10</td>
                                                    <td>Active</td>
                                                    <td class="text-nowrap text-start text-center"><a class="btn btn-dark" role="button" style="width: 25px;height: 25px;padding: 3px 3px;text-align: center;margin: 0px 3px;background: rgba(242,242,242,0);border-style: none;" href="Lesson.html"><i class="material-icons text-dark" id="showAlertBtn-1" style="font-size: 19px;--bs-primary: #4e73df;--bs-primary-rgb: 78,115,223;color: rgb(255,255,255);" type="button">remove_red_eye</i></a><a class="btn btn-dark" role="button" style="width: 25px;height: 25px;padding: 3px 3px;text-align: center;margin: 0px 3px;background: rgba(242,242,242,0);border-style: none;" href="LessonArrange.html"><i class="material-icons text-dark" id="showAlertBtn-17" style="font-size: 19px;--bs-primary: #4e73df;--bs-primary-rgb: 78,115,223;color: rgb(255,255,255);" type="button">low_priority</i></a><button class="btn btn-dark" type="button" style="width: 25px;height: 25px;padding: 3px 3px;text-align: center;margin: 0px 5px;background: rgba(242,242,242,0);border-style: none;" data-bs-target="#EditClass" data-bs-toggle="offcanvas"><i class="material-icons text-dark" id="showAlertBtn-8" style="font-size: 19px;--bs-primary: #4e73df;--bs-primary-rgb: 78,115,223;color: rgb(255,255,255);" type="button">edit</i></button><button class="btn btn-dark" type="button" style="width: 25px;height: 25px;padding: 3px 3px;text-align: center;margin: 0px 3px;background: rgba(242,242,242,0);border-style: none;" data-bs-target="#modal-1" data-bs-toggle="modal"><i class="material-icons text-dark" id="showAlertBtn-9" style="font-size: 19px;--bs-primary: #4e73df;--bs-primary-rgb: 78,115,223;color: rgb(255,255,255);" type="button">do_not_disturb_alt</i></button></td>
                                                </tr>
                                                <tr>
                                                    <td class="text-truncate" style="max-width: 200px;">#PG6159375</td>
                                                    <td class="text-truncate" style="max-width: 200px;">财经</td>
                                                    <td class="text-truncate" style="max-width: 200px;"><img class="img-fluid" width="299" height="180" style="max-width: 120px;max-height: 100px;" src="assets/img/OIP.webp"></td>
                                                    <td class="text-break" style="max-width: 50px;">财经</td>
                                                    <td class="text-break" style="max-width: 50px;">Program C</td>
                                                    <td>6/9/2024</td>
                                                    <td class="text-truncate">3</td>
                                                    <td class="text-start">10</td>
                                                    <td>Active</td>
                                                    <td class="text-nowrap text-start text-center"><a class="btn btn-dark" role="button" style="width: 25px;height: 25px;padding: 3px 3px;text-align: center;margin: 0px 3px;background: rgba(242,242,242,0);border-style: none;" href="Lesson.html"><i class="material-icons text-dark" id="showAlertBtn-2" style="font-size: 19px;--bs-primary: #4e73df;--bs-primary-rgb: 78,115,223;color: rgb(255,255,255);" type="button">remove_red_eye</i></a><a class="btn btn-dark" role="button" style="width: 25px;height: 25px;padding: 3px 3px;text-align: center;margin: 0px 3px;background: rgba(242,242,242,0);border-style: none;" href="LessonArrange.html"><i class="material-icons text-dark" id="showAlertBtn-18" style="font-size: 19px;--bs-primary: #4e73df;--bs-primary-rgb: 78,115,223;color: rgb(255,255,255);" type="button">low_priority</i></a><button class="btn btn-dark" type="button" style="width: 25px;height: 25px;padding: 3px 3px;text-align: center;margin: 0px 5px;background: rgba(242,242,242,0);border-style: none;" data-bs-target="#EditClass" data-bs-toggle="offcanvas"><i class="material-icons text-dark" id="showAlertBtn-10" style="font-size: 19px;--bs-primary: #4e73df;--bs-primary-rgb: 78,115,223;color: rgb(255,255,255);" type="button">edit</i></button><button class="btn btn-dark" type="button" style="width: 25px;height: 25px;padding: 3px 3px;text-align: center;margin: 0px 3px;background: rgba(242,242,242,0);border-style: none;" data-bs-target="#modal-1" data-bs-toggle="modal"><i class="material-icons text-dark" id="showAlertBtn-11" style="font-size: 19px;--bs-primary: #4e73df;--bs-primary-rgb: 78,115,223;color: rgb(255,255,255);" type="button">do_not_disturb_alt</i></button></td>
                                                </tr>
                                                <tr>
                                                    <td class="text-truncate" style="max-width: 200px;">#PG1954621</td>
                                                    <td class="text-truncate" style="max-width: 200px;">自我成长</td>
                                                    <td class="text-truncate" style="max-width: 200px;"><img class="img-fluid" width="299" height="180" style="max-width: 120px;max-height: 100px;" src="assets/img/OIP.webp"></td>
                                                    <td class="text-break" style="max-width: 50px;">自我成长</td>
                                                    <td class="text-break" style="max-width: 50px;">Program D</td>
                                                    <td>6/9/2024</td>
                                                    <td class="text-truncate">4</td>
                                                    <td class="text-start">15</td>
                                                    <td>Active</td>
                                                    <td class="text-nowrap text-start text-center"><a class="btn btn-dark" role="button" style="width: 25px;height: 25px;padding: 3px 3px;text-align: center;margin: 0px 3px;background: rgba(242,242,242,0);border-style: none;" href="Lesson.html"><i class="material-icons text-dark" id="showAlertBtn-3" style="font-size: 19px;--bs-primary: #4e73df;--bs-primary-rgb: 78,115,223;color: rgb(255,255,255);" type="button">remove_red_eye</i></a><a class="btn btn-dark" role="button" style="width: 25px;height: 25px;padding: 3px 3px;text-align: center;margin: 0px 3px;background: rgba(242,242,242,0);border-style: none;" href="LessonArrange.html"><i class="material-icons text-dark" id="showAlertBtn-19" style="font-size: 19px;--bs-primary: #4e73df;--bs-primary-rgb: 78,115,223;color: rgb(255,255,255);" type="button">low_priority</i></a><button class="btn btn-dark" type="button" style="width: 25px;height: 25px;padding: 3px 3px;text-align: center;margin: 0px 5px;background: rgba(242,242,242,0);border-style: none;" data-bs-target="#EditClass" data-bs-toggle="offcanvas"><i class="material-icons text-dark" id="showAlertBtn-12" style="font-size: 19px;--bs-primary: #4e73df;--bs-primary-rgb: 78,115,223;color: rgb(255,255,255);" type="button">edit</i></button><button class="btn btn-dark" type="button" style="width: 25px;height: 25px;padding: 3px 3px;text-align: center;margin: 0px 3px;background: rgba(242,242,242,0);border-style: none;" data-bs-target="#modal-1" data-bs-toggle="modal"><i class="material-icons text-dark" id="showAlertBtn-13" style="font-size: 19px;--bs-primary: #4e73df;--bs-primary-rgb: 78,115,223;color: rgb(255,255,255);" type="button">do_not_disturb_alt</i></button></td>
                                                </tr>
                                                <tr>
                                                    <td class="text-truncate" style="max-width: 200px;">#PG1948568</td>
                                                    <td class="text-truncate" style="max-width: 200px;">易数</td>
                                                    <td class="text-truncate" style="max-width: 200px;"><img class="img-fluid" width="299" height="180" style="max-width: 120px;max-height: 100px;" src="assets/img/OIP.webp"></td>
                                                    <td class="text-break" style="max-width: 50px;">易数</td>
                                                    <td class="text-break" style="max-width: 50px;">Program E</td>
                                                    <td>6/9/2024</td>
                                                    <td class="text-truncate">4</td>
                                                    <td class="text-start">15</td>
                                                    <td>Active</td>
                                                    <td class="text-nowrap text-start text-center"><a class="btn btn-dark" role="button" style="width: 25px;height: 25px;padding: 3px 3px;text-align: center;margin: 0px 3px;background: rgba(242,242,242,0);border-style: none;" href="Lesson.html"><i class="material-icons text-dark" id="showAlertBtn-4" style="font-size: 19px;--bs-primary: #4e73df;--bs-primary-rgb: 78,115,223;color: rgb(255,255,255);" type="button">remove_red_eye</i></a><a class="btn btn-dark" role="button" style="width: 25px;height: 25px;padding: 3px 3px;text-align: center;margin: 0px 3px;background: rgba(242,242,242,0);border-style: none;" href="LessonArrange.html"><i class="material-icons text-dark" id="showAlertBtn-20" style="font-size: 19px;--bs-primary: #4e73df;--bs-primary-rgb: 78,115,223;color: rgb(255,255,255);" type="button">low_priority</i></a><button class="btn btn-dark" type="button" style="width: 25px;height: 25px;padding: 3px 3px;text-align: center;margin: 0px 5px;background: rgba(242,242,242,0);border-style: none;" data-bs-target="#EditClass" data-bs-toggle="offcanvas"><i class="material-icons text-dark" id="showAlertBtn-14" style="font-size: 19px;--bs-primary: #4e73df;--bs-primary-rgb: 78,115,223;color: rgb(255,255,255);" type="button">edit</i></button><button class="btn btn-dark" type="button" style="width: 25px;height: 25px;padding: 3px 3px;text-align: center;margin: 0px 3px;background: rgba(242,242,242,0);border-style: none;" data-bs-target="#modal-1" data-bs-toggle="modal"><i class="material-icons text-dark" id="showAlertBtn-15" style="font-size: 19px;--bs-primary: #4e73df;--bs-primary-rgb: 78,115,223;color: rgb(255,255,255);" type="button">do_not_disturb_alt</i></button></td>
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
        </div>
    </div>
    <div class="modal fade" role="dialog" tabindex="-1" id="modal-1">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Delete Alert!</h4><button class="btn-close" type="button" aria-label="Close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Are you confirm to deactivate this class?</p>
                </div>
                <div class="modal-footer"><button class="btn btn-primary" id="showAlertBtn-7" type="button" data-bs-target="#modal-2" data-bs-toggle="modal" data-bs-dismiss="modal" style="background: rgb(231,74,59);">Yes</button><button class="btn btn-light" type="button" data-bs-dismiss="modal" style="background: rgb(13,110,253);color: rgb(255,255,255);">No</button></div>
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
            <form id="classForm" novalidate=""><label class="form-label">Upload Cover Image&nbsp;<span class="hint"></span></label><input class="form-control" type="file" accept="image/*,application/pdf" id="uploadFile">
                <div class="mt-3"><label class="form-label">Course Name</label>
                    <div class="search-dd" id="courseDD"><input class="form-control form-control" type="text" autocomplete="off" id="courseInput" placeholder="Search course..." required=""><input type="hidden" id="courseId">
                        <div class="invalid-msg"><span>Please select a course.</span></div>
                        <div class="dd-panel">
                            <div class="dd-search"><input class="form-control form-control courseSearch" type="text" id="courseSearch" placeholder="Type to filter..."></div>
                            <div class="dd-list" id="courseList"></div>
                        </div>
                    </div>
                </div>
                <div class="mt-3"><label class="form-label">Class Name</label>
                    <div class="search-dd" id="classDD"><input class="form-control" type="text" id="class_name" placeholder="Type the class name" required=""><input type="hidden" id="classId">
                        <div class="invalid-msg"><span>Please select a class.</span></div>
                    </div>
                </div>
                <div class="mt-3"><label class="form-label">User Access Level</label>
                    <div id="userLevelDD" class="search-dd"><input class="form-control form-control" type="text" id="userLevelInput" autocomplete="off" placeholder="Search user level..." required=""><input type="hidden" id="userLevelId">
                        <div class="invalid-msg"><span>Please select a course.</span></div>
                        <div class="dd-panel">
                            <div class="dd-search"><input class="form-control form-control courseSearch" type="text" id="userLevelSearch" placeholder="Type to filter..."></div>
                            <div id="userLevelList" class="dd-list"></div>
                        </div>
                    </div>
                </div><label class="form-label mt-3">Description</label><textarea class="form-control form-control" id="description" placeholder="Course Description" rows="4"></textarea>
                <div class="divider"></div>
                <div class="d-flex justify-content-end gap-2"><button class="btn btn-outline-light" type="button" data-bs-dismiss="offcanvas">Cancel</button><button class="btn btn-primary" id="btnAdd" type="submit">Add</button></div>
                <div class="mt-3 small" id="result" style="color:rgba(229,231,235,.8);display:none;"></div>
            </form>
        </div>
    </div>
    <div class="offcanvas offcanvas-end lp-offcanvas" tabindex="-1" id="EditClass" aria-labelledby="ocAddLessonLabel">
        <div class="oc-header">
            <div class="d-flex justify-content-between align-items-start gap-2">
                <div>
                    <h5 id="ocAddLessonLabel-1">Edit Class</h5>
                </div><button class="btn-close mt-1 btn-close-white" type="button" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
        </div>
        <div class="offcanvas-body p-3 p-sm-4">
            <form id="classForm-1" novalidate=""><label class="form-label">Upload Cover Image&nbsp;<span class="hint"></span></label><input class="form-control" type="file" accept="image/*,application/pdf" id="uploadFile-1">
                <div class="mt-3"><label class="form-label">Course Name</label>
                    <div class="search-dd" id="courseDD-1"><input class="form-control form-control" type="text" autocomplete="off" id="courseInput-1" placeholder="Search course..." required=""><input type="hidden" id="courseId-1">
                        <div class="invalid-msg"><span>Please select a course.</span></div>
                        <div class="dd-panel">
                            <div class="dd-search"><input class="form-control form-control courseSearch" type="text" id="courseSearch-1" placeholder="Type to filter..."></div>
                            <div class="dd-list" id="courseList-1"></div>
                        </div>
                    </div>
                </div>
                <div class="mt-3"><label class="form-label">Class Name</label>
                    <div class="search-dd" id="classDD-1"><input class="form-control" type="text" id="class_name-1" placeholder="Type the class name" required=""><input type="hidden" id="classId-1">
                        <div class="invalid-msg"><span>Please select a class.</span></div>
                    </div>
                </div>
                <div class="mt-3"><label class="form-label">User Access Level</label>
                    <div id="userLevelDD-1" class="search-dd"><input class="form-control form-control" type="text" id="userLevelInput-1" autocomplete="off" placeholder="Search user level..." required=""><input type="hidden" id="userLevelId-1">
                        <div class="invalid-msg"><span>Please select a course.</span></div>
                        <div class="dd-panel">
                            <div class="dd-search"><input class="form-control form-control courseSearch" type="text" id="userLevelSearch-1" placeholder="Type to filter..."></div>
                            <div id="userLevelList-1" class="dd-list"></div>
                        </div>
                    </div>
                </div><label class="form-label mt-3">Description</label><textarea class="form-control form-control" id="description-1" placeholder="Course Description" rows="4"></textarea>
                <div class="divider"></div>
                <div class="d-flex justify-content-end gap-2"><button class="btn btn-outline-light" type="button" data-bs-dismiss="offcanvas">Cancel</button><button class="btn btn-primary" id="btnAdd-1" type="submit">Add</button></div>
                <div class="mt-3 small" id="result-1" style="color:rgba(229,231,235,.8);display:none;"></div>
            </form>
        </div>
    </div>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/bs-init.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.6.0/umd/popper.min.js"></script>
    <script src="assets/js/Alert.js"></script>
    <script src="assets/js/Class%20Searchable%20Dropdown.js"></script>
    <script src="assets/js/Course%20Searchable%20Dropdown.js"></script>
    <script src="assets/js/Lesson%20Searchable%20Dropdown.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdn.datatables.net/2.1.7/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.1.7/js/dataTables.bootstrap5.js"></script>
    <script src="assets/js/Input-Image-With-Preview-input_image_preview.js"></script>
    <script src="assets/js/QueryTable.js"></script>
    <script src="assets/js/sidebar.js"></script>
    <script src="assets/js/class_offcanvas.js"></script>
</body>

</html>