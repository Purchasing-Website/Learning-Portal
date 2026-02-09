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
                    <li class="nav-item"><a class="nav-link" href="Class.html"><i class="fas fa-users" style="margin-right: 0px;width: 20px;text-align: center;"></i><span style="padding: 0px 4px;">Class</span></a></li>
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
                    <div class="row justify-content-center" style="margin: 0px;width: 100%;">
                        <div class="col-xl-10 col-xxl-9" style="width: 100%;">
                            <div class="card shadow" style="height: 100%;">
                                <div class="card-header d-flex flex-wrap justify-content-center align-items-center justify-content-sm-between gap-3" style="padding: 8px 16px;">
                                    <div class="row" style="margin: 0px;width: 100%;">
                                        <div class="col" style="padding: 0px;width: 60%;">
                                            <div class="d-flex w-100 flex-column">
                                                <h1 class="d-inline-block" style="margin: 0px 0px;margin-bottom: 0px;height: 100%;padding: 5px 0px;">User Access Control</h1>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body d-flex flex-column">
                                    <form id="enrolForm">
                                        <div class="mb-3 enrol-controls">
                                            <div class="row align-items-end g-2">
                                                <div class="col col-md-4 col-lg-4 col-xl-4 col-xxl-2"><label class="form-label fw-bold mb-1 form-label" style="color: rgb(165,172,186);">User Level</label><select class="form-select" id="selUserLevel"></select></div>
                                            </div>
                                        </div>
                                        <div class="row flex-grow-1 align-items-stretch g-3">
                                            <div class="col col-md-4 d-flex flex-column">
                                                <h4 id="leftTitle">Available Students</h4><select class="form-select" id="selAvailable" multiple="" style="height: 50vh;min-height: 260px;max-height: 520px;"></select><small class="text-muted" id="availableCount">0 Available</small>
                                            </div>
                                            <div class="col col-md-4 d-flex flex-column justify-content-center align-items-center gap-2" style="height: 370px;"><button class="btn btn-primary" id="btnAdd" type="button" style="padding: 6px 6px;width: 120px;">Add &gt;</button><button class="btn btn-secondary" id="btnRemove" type="button" style="width: 120px;padding: 6px 6px;">&lt; Remove</button>
                                                <hr class="my-2" style="width: 120px;"><button class="btn btn-outline-primary" id="btnAddAll" type="button" style="padding: 6px 6px;width: 120px;">Add All&nbsp;&gt;&gt;</button><button class="btn btn-outline-secondary" id="btnRemoveAll" type="button" style="width: 120px;padding: 6px 6px;">&lt;&lt; Remove All</button>
                                            </div>
                                            <div class="col col-md-4 d-flex flex-column">
                                                <h4 id="rightTitle">Assigned Students</h4><select class="form-select" id="selEnrolled" multiple="" style="height: 50vh;min-height: 260px;max-height: 520px;"></select><small class="text-muted" id="enrolledCount">0 Available</small>
                                            </div>
                                        </div><input class="form-control" type="hidden" id="studentIds" name="student_ids"><input class="form-control" type="hidden" id="payloadMode" name="payload_mode"><input class="form-control" type="hidden" id="payloadStudentId" name="payload_student_id"><input class="form-control" type="hidden" id="payloadClassId" name="payload_class_id">
                                        <div class="d-flex justify-content-end mt-3"><button class="btn btn-success" type="submit">Save</button></div>
                                    </form>
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
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/bs-init.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.6.0/umd/popper.min.js"></script>
    <script src="assets/js/Alert.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdn.datatables.net/2.1.7/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.1.7/js/dataTables.bootstrap5.js"></script>
    <script src="assets/js/Input-Image-With-Preview-input_image_preview.js"></script>
    <script src="assets/js/sidebar.js"></script>
    <script src="assets/js/AssignUserLevel.js"></script>
</body>

</html>