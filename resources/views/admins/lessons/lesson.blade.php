@extends('layouts.app')

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
                                <h1 class="d-inline-block" style="margin: 0px 0px;margin-bottom: 0px;height: 100%;padding: 5px 0px;">Lesson Management</h1>
                                <p class="d-inline-block invisible" style="margin: 0px 10px;">course name</p>
                            </div>
                            <div class="col-12 justify-content-end align-content-center" style="padding: 0px;width: initial;"><button class="btn btn-primary" type="button" style="width: 95px;font-weight: bold;color: rgb(255,255,255);background: rgb(78,115,223);border-width: 0px;" data-bs-target="#AddLesson" data-bs-toggle="offcanvas"><i class="fas fa-plus-square" style="border-color: rgb(255,255,255);color: rgb(255,255,255);background: rgba(255,255,255,0);font-size: 18px;"></i>&nbsp; Add</button></div>
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
                                        <td class="text-nowrap text-start text-center"><button class="btn btn-dark" type="button" style="width: 25px;height: 25px;padding: 3px 3px;text-align: center;margin: 0px 3px;background: rgba(242,242,242,0);border-style: none;"><i class="material-icons text-dark" id="showAlertBtn" style="font-size: 19px;--bs-primary: #4e73df;--bs-primary-rgb: 78,115,223;color: rgb(255,255,255);" type="button">remove_red_eye</i></button><button class="btn btn-dark" type="button" style="width: 25px;height: 25px;padding: 3px 3px;text-align: center;margin: 0px 5px;background: rgba(242,242,242,0);border-style: none;" data-bs-target="#offcanvas-2" data-bs-toggle="offcanvas"><i class="material-icons text-dark" id="showAlertBtn-5" style="font-size: 19px;--bs-primary: #4e73df;--bs-primary-rgb: 78,115,223;color: rgb(255,255,255);" type="button">edit</i></button><button class="btn btn-dark" type="button" style="width: 25px;height: 25px;padding: 3px 3px;text-align: center;margin: 0px 3px;background: rgba(242,242,242,0);border-style: none;" data-bs-target="#modal-1" data-bs-toggle="modal"><i class="material-icons text-dark" id="showAlertBtn-6" style="font-size: 19px;--bs-primary: #4e73df;--bs-primary-rgb: 78,115,223;color: rgb(255,255,255);" type="button">delete_forever</i></button></td>
                                    </tr>
                                    <tr>
                                        <td class="text-truncate" style="max-width: 200px;">#PG1951798</td>
                                        <td class="text-truncate" style="max-width: 200px;">身心疗愈</td>
                                        <td class="text-break" style="max-width: 50px;">身心疗愈</td>
                                        <td class="text-break" style="max-width: 50px;">Program B</td>
                                        <td>PPT</td>
                                        <td>15 mins</td>
                                        <td class="text-start" contenteditable="true">2</td>
                                        <td class="text-nowrap text-start text-center"><button class="btn btn-dark" type="button" style="width: 25px;height: 25px;padding: 3px 3px;text-align: center;margin: 0px 3px;background: rgba(242,242,242,0);border-style: none;"><i class="material-icons text-dark" id="showAlertBtn-1" style="font-size: 19px;--bs-primary: #4e73df;--bs-primary-rgb: 78,115,223;color: rgb(255,255,255);" type="button">remove_red_eye</i></button><button class="btn btn-dark" type="button" style="width: 25px;height: 25px;padding: 3px 3px;text-align: center;margin: 0px 5px;background: rgba(242,242,242,0);border-style: none;" data-bs-target="#EditLesson" data-bs-toggle="offcanvas"><i class="material-icons text-dark" id="showAlertBtn-8" style="font-size: 19px;--bs-primary: #4e73df;--bs-primary-rgb: 78,115,223;color: rgb(255,255,255);" type="button">edit</i></button><button class="btn btn-dark" type="button" style="width: 25px;height: 25px;padding: 3px 3px;text-align: center;margin: 0px 3px;background: rgba(242,242,242,0);border-style: none;" data-bs-target="#modal-1" data-bs-toggle="modal"><i class="material-icons text-dark" id="showAlertBtn-9" style="font-size: 20px;--bs-primary: #4e73df;--bs-primary-rgb: 78,115,223;color: rgb(255,255,255);" type="button">delete_forever</i></button></td>
                                    </tr>
                                    <tr>
                                        <td class="text-truncate" style="max-width: 200px;">#PG6159375</td>
                                        <td class="text-truncate" style="max-width: 200px;">财经</td>
                                        <td class="text-break" style="max-width: 50px;">财经</td>
                                        <td class="text-break" style="max-width: 50px;">Program C</td>
                                        <td>PDF</td>
                                        <td>1.75 hrs</td>
                                        <td class="text-start" contenteditable="true">3</td>
                                        <td class="text-nowrap text-start text-center"><button class="btn btn-dark" type="button" style="width: 25px;height: 25px;padding: 3px 3px;text-align: center;margin: 0px 3px;background: rgba(242,242,242,0);border-style: none;"><i class="material-icons text-dark" id="showAlertBtn-2" style="font-size: 19px;--bs-primary: #4e73df;--bs-primary-rgb: 78,115,223;color: rgb(255,255,255);" type="button">remove_red_eye</i></button><button class="btn btn-dark" type="button" style="width: 25px;height: 25px;padding: 3px 3px;text-align: center;margin: 0px 5px;background: rgba(242,242,242,0);border-style: none;" data-bs-target="#EditLesson" data-bs-toggle="offcanvas"><i class="material-icons text-dark" id="showAlertBtn-10" style="font-size: 19px;--bs-primary: #4e73df;--bs-primary-rgb: 78,115,223;color: rgb(255,255,255);" type="button">edit</i></button><button class="btn btn-dark" type="button" style="width: 25px;height: 25px;padding: 3px 3px;text-align: center;margin: 0px 3px;background: rgba(242,242,242,0);border-style: none;" data-bs-target="#modal-1" data-bs-toggle="modal"><i class="material-icons text-dark" id="showAlertBtn-11" style="font-size: 20px;--bs-primary: #4e73df;--bs-primary-rgb: 78,115,223;color: rgb(255,255,255);" type="button">delete_forever</i></button></td>
                                    </tr>
                                    <tr>
                                        <td class="text-truncate" style="max-width: 200px;">#PG1954621</td>
                                        <td class="text-truncate" style="max-width: 200px;">自我成长</td>
                                        <td class="text-break" style="max-width: 50px;">自我成长</td>
                                        <td class="text-break" style="max-width: 50px;">Program D</td>
                                        <td>Image</td>
                                        <td>5 mins</td>
                                        <td class="text-start" contenteditable="true">4</td>
                                        <td class="text-nowrap text-start text-center"><button class="btn btn-dark" type="button" style="width: 25px;height: 25px;padding: 3px 3px;text-align: center;margin: 0px 3px;background: rgba(242,242,242,0);border-style: none;"><i class="material-icons text-dark" id="showAlertBtn-3" style="font-size: 19px;--bs-primary: #4e73df;--bs-primary-rgb: 78,115,223;color: rgb(255,255,255);" type="button">remove_red_eye</i></button><button class="btn btn-dark" type="button" style="width: 25px;height: 25px;padding: 3px 3px;text-align: center;margin: 0px 5px;background: rgba(242,242,242,0);border-style: none;" data-bs-target="#EditLesson" data-bs-toggle="offcanvas"><i class="material-icons text-dark" id="showAlertBtn-12" style="font-size: 19px;--bs-primary: #4e73df;--bs-primary-rgb: 78,115,223;color: rgb(255,255,255);" type="button">edit</i></button><button class="btn btn-dark" type="button" style="width: 25px;height: 25px;padding: 3px 3px;text-align: center;margin: 0px 3px;background: rgba(242,242,242,0);border-style: none;" data-bs-target="#modal-1" data-bs-toggle="modal"><i class="material-icons text-dark" id="showAlertBtn-13" style="font-size: 19px;--bs-primary: #4e73df;--bs-primary-rgb: 78,115,223;color: rgb(255,255,255);" type="button">delete_forever</i></button></td>
                                    </tr>
                                    <tr>
                                        <td class="text-truncate" style="max-width: 200px;">#PG1948568</td>
                                        <td class="text-truncate" style="max-width: 200px;">易数</td>
                                        <td class="text-break" style="max-width: 50px;">易数</td>
                                        <td class="text-break" style="max-width: 50px;">Program E</td>
                                        <td>Video</td>
                                        <td>1.5 hrs</td>
                                        <td class="text-start" contenteditable="true">5</td>
                                        <td class="text-nowrap text-start text-center"><button class="btn btn-dark" type="button" style="width: 25px;height: 25px;padding: 3px 3px;text-align: center;margin: 0px 3px;background: rgba(242,242,242,0);border-style: none;"><i class="material-icons text-dark" id="showAlertBtn-4" style="font-size: 19px;--bs-primary: #4e73df;--bs-primary-rgb: 78,115,223;color: rgb(255,255,255);" type="button">remove_red_eye</i></button><button class="btn btn-dark" type="button" style="width: 25px;height: 25px;padding: 3px 3px;text-align: center;margin: 0px 5px;background: rgba(242,242,242,0);border-style: none;" data-bs-target="#EditLesson" data-bs-toggle="offcanvas"><i class="material-icons text-dark" id="showAlertBtn-14" style="font-size: 19px;--bs-primary: #4e73df;--bs-primary-rgb: 78,115,223;color: rgb(255,255,255);" type="button">edit</i></button><button class="btn btn-dark" type="button" style="width: 25px;height: 25px;padding: 3px 3px;text-align: center;margin: 0px 3px;background: rgba(242,242,242,0);border-style: none;" data-bs-target="#modal-1" data-bs-toggle="modal"><i class="material-icons text-dark" id="showAlertBtn-15" style="font-size: 19px;--bs-primary: #4e73df;--bs-primary-rgb: 78,115,223;color: rgb(255,255,255);" type="button">delete_forever</i></button></td>
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
                    <p>Are you confirm to delete this lesson?</p>
                </div>
                <div class="modal-footer"><button class="btn btn-primary" id="showAlertBtn-7" type="button" data-bs-target="#modal-2" data-bs-toggle="modal" data-bs-dismiss="modal" style="background: rgb(231,74,59);">Yes</button><button class="btn btn-light" type="button" data-bs-dismiss="modal" style="background: rgb(13,110,253);color: rgb(255,255,255);">No</button></div>
            </div>
        </div>
    </div>
    <div class="offcanvas offcanvas-end lp-offcanvas" tabindex="-1" id="AddLesson" aria-labelledby="ocAddLessonLabel">
        <div class="oc-header">
            <div class="d-flex justify-content-between align-items-start gap-2">
                <div>
                    <h5 id="ocAddLessonLabel">Add New Lesson</h5>
                </div><button class="btn-close mt-1 btn-close-white" type="button" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
        </div>
        <div class="offcanvas-body p-3 p-sm-4">
            <form id="lessonForm" novalidate=""><label class="form-label">Upload File <span class="hint">(Image &amp; PDF only)</span></label><input class="form-control" type="file" accept="image/*,application/pdf" id="uploadFile">
                <div class="mt-2 hint"></div>
                <div class="divider"></div><label class="form-label mt-1">Content Type</label><select class="form-select ocform-select" id="contentType" required="">
                    <option value="imagorpdf" selected="">Image/PDF</option>
                    <option value="video">Video</option>
                </select><label class="form-label mt-3">Source URL</label><input class="form-control" type="text" id="sourceUrl" placeholder="Paste video ID">
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
                    <div class="search-dd" id="classDD"><input class="form-control form-control" type="text" autocomplete="off" disabled="" id="classInput" placeholder="Search class..." required=""><input type="hidden" id="classId">
                        <div class="invalid-msg"><span>Please select a class.</span></div>
                        <div class="dd-panel">
                            <div class="dd-search"><input class="form-control form-control" type="text" id="classSearch" placeholder="Type to filter..."></div>
                            <div class="dd-list" id="classList"></div>
                        </div>
                    </div>
                    <div class="mt-1 hint"><span>Classes will filter based on selected course.</span></div>
                </div><label class="form-label mt-3">Lesson Name</label><input class="form-control" type="text" id="lessonName" placeholder="e.g. Feng Shui" required=""><label class="form-label mt-3">Duration</label>
                <div class="duration-grid"><input class="form-control form-control" type="number" id="durHours" min="0" placeholder="0"><span class="hint">hours</span><input class="form-control form-control" type="number" id="durMins" max="59" min="0" placeholder="0"><span class="hint">mins</span></div><label class="form-label mt-3">Description</label><textarea class="form-control form-control" id="description" placeholder="Course Description" rows="4"></textarea>
                <div class="divider"></div>
                <div class="d-flex justify-content-end gap-2"><button class="btn btn-outline-light" type="button" data-bs-dismiss="offcanvas">Cancel</button><button class="btn btn-primary" id="btnAdd" type="submit">Add</button></div>
                <div class="mt-3 small" id="result" style="color:rgba(229,231,235,.8);display:none;"></div>
            </form>
        </div>
    </div>
    <div class="offcanvas offcanvas-end lp-offcanvas" tabindex="-1" id="EditLesson" aria-labelledby="ocAddLessonLabel">
        <div class="oc-header">
            <div class="d-flex justify-content-between align-items-start gap-2">
                <div>
                    <h5 id="ocAddLessonLabel-1">Edit Lesson</h5>
                </div><button class="btn-close mt-1 btn-close-white" type="button" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
        </div>
        <div class="offcanvas-body p-3 p-sm-4">
            <form id="lessonForm-1" novalidate=""><label class="form-label">Upload File <span class="hint">(Image &amp; PDF only)</span></label><input class="form-control" type="file" id="editFile" accept="image/*,application/pdf">
                <div class="mt-2 hint"></div>
                <div class="divider"></div><label class="form-label mt-1">Content Type</label><select class="form-select ocform-select" id="contentType-1" required="">
                    <option value="imagorpdf" selected="">Image/PDF</option>
                    <option value="video">Video</option>
                </select><label class="form-label mt-3">Source URL</label><input class="form-control" type="text" id="sourceUrl-1" placeholder="Paste video ID">
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
                    <div class="search-dd" id="classDD-1"><input class="form-control form-control" type="text" autocomplete="off" disabled="" id="classInput-1" placeholder="Search class..." required=""><input type="hidden" id="classId-1">
                        <div class="invalid-msg"><span>Please select a class.</span></div>
                        <div class="dd-panel">
                            <div class="dd-search"><input class="form-control form-control" type="text" id="classSearch-1" placeholder="Type to filter..."></div>
                            <div class="dd-list" id="classList-1"></div>
                        </div>
                    </div>
                    <div class="mt-1 hint"><span>Classes will filter based on selected course.</span></div>
                </div><label class="form-label mt-3">Lesson Name</label><input class="form-control" type="text" id="lessonName-1" placeholder="e.g. Feng Shui" required=""><label class="form-label mt-3">Duration</label>
                <div class="duration-grid"><input class="form-control form-control" type="number" id="durHours-1" min="0" placeholder="0"><span class="hint">hours</span><input class="form-control form-control" type="number" id="durMins-1" max="59" min="0" placeholder="0"><span class="hint">mins</span></div><label class="form-label mt-3">Description</label><textarea class="form-control form-control" id="description-1" placeholder="Course Description" rows="4"></textarea>
                <div class="divider"></div>
                <div class="d-flex justify-content-end gap-2"><button class="btn btn-outline-light" type="button" data-bs-dismiss="offcanvas">Cancel</button><button class="btn btn-primary" id="btnEdit" type="submit">Save</button></div>
                <div class="mt-3 small" id="editresult" style="color:rgba(229,231,235,.8);display:none;"></div>
            </form>
        </div>
    </div>
    
    @push('scripts')
    <script src="{{ asset('js/Alert.js') }}"></script>
    <script src="{{ asset('js/lessonoffcanvas.js') }}"></script>
    @endpush