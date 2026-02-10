@extends('layouts.app')
@section('content')
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
@endsection

@push('scripts')
    <script src="{{ asset('js/Alert.js') }}"></script>
    <script src="{{ asset('js/AssignUserLevel.js') }}"></script>
@endpush