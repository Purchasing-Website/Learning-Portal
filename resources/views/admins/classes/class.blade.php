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
                                        <td class="text-truncate" style="max-width: 200px;"><img class="img-fluid" width="299" height="180" style="max-width: 120px;max-height: 100px;" src="{{ asset('img/OIP.webp') }}"></td>
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
                                        <td class="text-truncate" style="max-width: 200px;"><img class="img-fluid" width="299" height="180" style="max-width: 120px;max-height: 100px;" src="{{ asset('img/OIP.webp') }}"></td>
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
                                        <td class="text-truncate" style="max-width: 200px;"><img class="img-fluid" width="299" height="180" style="max-width: 120px;max-height: 100px;" src="{{ asset('img/OIP.webp') }}"></td>
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
                                        <td class="text-truncate" style="max-width: 200px;"><img class="img-fluid" width="299" height="180" style="max-width: 120px;max-height: 100px;" src="{{ asset('img/OIP.webp') }}"></td>
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
                                        <td class="text-truncate" style="max-width: 200px;"><img class="img-fluid" width="299" height="180" style="max-width: 120px;max-height: 100px;" src="{{ asset('img/OIP.webp') }}"></td>
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
@endsection
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
    @push('scripts')
    <script src="{{ asset('js/Alert.js') }}"></script>
    <script src="{{ asset('js/class_offcanvas.js') }}"></script>
    @endpush