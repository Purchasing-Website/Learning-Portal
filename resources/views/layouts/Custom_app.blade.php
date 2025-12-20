<!DOCTYPE html>
<html lang="en">
    <head>
        <!-- @vite(['resources/js/app.js']) -->
    <!-- @vite(['resources/css/app.css', 'resources/js/app.js']) -->
    <link rel="stylesheet" href="{{ asset('css/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/alert.css') }}">
    <link rel="stylesheet" href="{{ asset('css/Form-Input.css') }}">
    <link rel="stylesheet" href="{{ asset('css/TableCell_CenteredStatus.css') }}">
    <link rel="stylesheet" href="{{ asset('css/fonts/fontawesome-all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/fonts/ionicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/fonts/material-icons.min.css') }}">

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i&amp;display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Noto+Sans:300,400,500,600,700&amp;display=swap">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.7/css/dataTables.bootstrap5.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.6.5/css/buttons.dataTables.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/css/dataTables.bootstrap4.min.css">
    {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"> --}}
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
                    <li class="nav-item">
                        <a class="nav-link" href="Dashboard.html">
                            <i class="fas fa-tachometer-alt" style="margin: 0px;width: 20px;text-align: center;"></i>
                            <span style="padding: 0px 4px;">Dashboard</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('user.index') }}">
                            <i class="fas fa-user" style="width: 20px;height: 20px;margin-right: 0px;text-align: center;"></i>
                            <span style="padding: 0px 4px;">User</span>
                        </a>
                        <a class="nav-link" href="{{ route('program.index') }}">
                            <i class="fas fa-user-graduate" style="margin-right: 0px;width: 20px;text-align: center;"></i>
                            <span style="padding: 0px 4px;">Program</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('course.index','all') }}">
                            <i class="fas fa-book-reader" style="width: 20px;height: 14px;margin: 0px;text-align: center;"></i>
                            <span style="padding: 0px 4px;">Course</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('class.index','all') }}">
                            <i class="fas fa-users" style="margin-right: 0px;width: 20px;text-align: center;"></i>
                            <span style="padding: 0px 4px;">Class</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('lesson.index','all') }}">
                            <i class="fas fa-book" style="margin-right: 0px;text-align: center;width: 20px;"></i>
                            <span style="padding: 0px 4px;">Lesson</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('quiz.index') }}">
                            <i class="fas fa-lightbulb" style="width: 20px;height: 20px;margin: 0px;text-align: center;"></i>
                            <span style="padding: 0px 4px;">Quiz</span>
                        </a>
                    </li>
                    <li class="nav-item"></li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('logout') }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icon-tabler-logout" style="width: 20px;height: 14px;text-align: center;">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <path d="M14 8v-2a2 2 0 0 0 -2 -2h-7a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h7a2 2 0 0 0 2 -2v-2"></path>
                                <path d="M9 12h12l-3 -3"></path>
                                <path d="M18 15l3 -3"></path>
                            </svg><span style="padding: 0px 4px;">Logout</span>
                        </a>
                    </li>
                </ul>
                <div class="text-center d-none d-md-inline"><button class="btn rounded-circle border-0" id="sidebarToggle" type="button"></button></div>
            </div>
        </nav>
        @yield('content')
    </div>

    {{-- <script src="{{ asset('js/bootstrap.min.js') }}"></script> --}}
    <script src="{{ asset('js/bs-init.js') }}"></script>
    <script src="{{ asset('js/updateDropdownValue.js') }}"></script>
    <script src="{{ asset('js/AddMinusItems.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.6.0/umd/popper.min.js"></script>
    <script src="{{ asset('js/Alert.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/jquery.dataTables.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.5/js/dataTables.buttons.min.js"></script>
    <script src="{{ asset('js/Class%20Searchable%20Dropdown-1.js') }}"></script>
    <script src="{{ asset('js/Class%20Searchable%20Dropdown.js') }}"></script>
    <script src="{{ asset('js/Course%20Searchable%20Dropdown.js') }}"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.flash.min.js"></script>
    <script src="{{ asset('js/Lesson%20Searchable%20Dropdown.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="{{ asset('js/sidebar.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdn.datatables.net/2.1.7/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/2.1.7/js/dataTables.bootstrap5.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.print.min.js"></script>
    <script src="{{ asset('js/Input-Image-With-Preview-input_image_preview.js') }}"></script>
    <script src="{{ asset('js/DataTable---Fully-BSS-Editable-style.js') }}"></script>
    <script src="{{ asset('js/haha.js') }}"></script>
    <script src="{{ asset('js/ProductManagement.js') }}"></script>
    <script src="{{ asset('js/File-Input---Beautiful-Input--Button-Approach-Jasny-Bootstrap-fileinput.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" defer></script>
    @stack('scripts')
</body>
</html>
