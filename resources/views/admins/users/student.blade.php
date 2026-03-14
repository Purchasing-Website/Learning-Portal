@extends('layouts.admin_app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/offcanvas.css') }}">
@endpush

@section('content')
    <div class="container-fluid">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible" role="alert" style="background-color: #B4F7D2!important; border: 1px solid #0C6D38;">
                <button class="btn-close" type="button" aria-label="Close" data-bs-dismiss="alert"></button>
                <i class="icon ion-checkmark-round me-1"></i>
                <span style="color: #0C6D38 !important;">{{ session('success') }}</span>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible" role="alert">
                <button class="btn-close" type="button" aria-label="Close" data-bs-dismiss="alert"></button>
                {{ $errors->first() }}
            </div>
        @endif
        <div class="row justify-content-center" style="margin: 0px;">
            <div class="col-xl-10 col-xxl-9" style="width: 100%;">
                <div class="card shadow">
                    <div class="card-header d-flex flex-wrap justify-content-center align-items-center justify-content-sm-between gap-3" style="padding: 8px 16px;">
                        <div class="row" style="margin: 0px;width: 100%;">
                            <div class="col-xl-12 col-xxl-10" style="padding: 0px;">
                                <h1 style="margin: 0px 0px;margin-bottom: 0px;">Student Management</h1>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="bulkBar" class="bulk-bar">
                            <div class="bulk-count"><small id="selectedCount">0</small><span style="margin: 5px;">Selected Students</span></div>
                            <div class="d-flex gap-2 ms-auto"><button class="btn btn-outline-dark btn-sm" id="btnClearSelection" type="button">Clear</button><button class="btn btn-primary btn-sm" id="btnBulkEdit" type="button" data-bs-toggle="modal" data-bs-target="#BulkEditModal">Bulk Edit</button></div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="example">
                                <thead>
                                    <tr>
                                        <th class="text-center">User ID</th>
                                        <th class="text-center">Email Address</th>
                                        <th class="text-center">Full Name</th>
                                        <th class="text-center">Gender</th>
                                        <th class="text-center">Phone No.</th>
                                        <th class="text-center">Sign Up By</th>
                                        <th class="text-center">Tier</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($users as $user)
                                        <tr>
                                            <td class="text-truncate" style="max-width: 200px;">{{ $user->id }}</td>
                                            <td class="text-truncate" style="max-width: 200px;">{{ $user->email }}</td>
                                            <td class="text-center">{{ $user->name }}</td>
                                            <td class="text-center">{{ $user->gender }}</td>
                                            <td class="text-center">{{ $user->phone }}</td>
                                            <td class="text-center">{{ $user->created_at }}</td>
                                            <td class="text-center">{{ $user->tier->name ?? 'N/A' }}</td>
                                            <td class="text-center">Active</td>
                                            <td class="text-start text-center">
                                                <button class="btn btn-dark js-edit-student-password" type="button" style="width: 25px;height: 25px;padding: 3px 3px;text-align: center;margin: 0px 5px;background: rgba(242,242,242,0);border-style: none;" data-bs-target="#editstudent" data-bs-toggle="offcanvas" data-student-id="{{ $user->id }}" data-student-name="{{ $user->name }}">
                                                    <i class="material-icons text-dark" id="showAlertBtn-5" style="font-size: 19px;--bs-primary: #4e73df;--bs-primary-rgb: 78,115,223;color: rgb(255,255,255);" type="button">edit</i>
                                                </button>
                                                <button class="btn btn-dark" type="button" style="width: 25px;height: 25px;padding: 3px 3px;text-align: center;margin: 0px 3px;background: rgba(242,242,242,0);border-style: none;" data-bs-target="#DeactivateModal" data-bs-toggle="modal">
                                                    <i class="material-icons text-dark" id="showAlertBtn-6" style="font-size: 19px;--bs-primary: #4e73df;--bs-primary-rgb: 78,115,223;color: rgb(255,255,255);" type="button">do_not_disturb_alt</i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

    <div class="modal fade" role="dialog" tabindex="-1" id="DeactivateModal">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Alert!</h4><button class="btn-close" type="button" aria-label="Close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Are you confirm to deactivate this student?</p>
                </div>
                <div class="modal-footer"><button class="btn btn-primary" id="showAlertBtn-1" type="button" data-bs-target="#modal-2" data-bs-toggle="modal" data-bs-dismiss="modal" style="background: rgb(231,74,59);">Yes</button><button class="btn btn-light" type="button" data-bs-dismiss="modal" style="background: rgb(13,110,253);color: rgb(255,255,255);">No</button></div>
            </div>
        </div>
    </div>
    <div class="modal fade" role="dialog" tabindex="-1" id="BulkEditModal" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content"></div>
                </div>
                <div class="modal-header">
                    <h4 class="modal-title">Bulk Edit Selected Students</h4><button class="btn-close" type="button" aria-label="Close" data-bs-dismiss="modal"></button>
                </div>
                <div class="alert alert-warning mb-3 small" role="alert"><span>Only fields you fill in will be applied to all selected rows.</span></div>
                <div class="modal-body">
                    <p>The content of your modal.</p>
                    <div class="row">
                        <div class="col-12"><label class="form-label">Status</label><select id="bulkStatus" class="form-select">
                                <option value="" selected="">(No Change)</option>
                                <option value="Active">活跃</option>
                                <option value="Inactive">封锁</option>
                            </select></div>
                        <div class="col-12"><label class="form-label">User Level</label><select id="bulkStatus-1" class="form-select">
                                <option value="" selected="">(No Change)</option>
                                <option value="4">弟子</option>
                                <option value="3">代理</option>
                                <option value="2">会员</option>
                                <option value="0">免费用户</option>
                            </select></div>
                    </div>
                </div>
                <div class="modal-footer"><button class="btn btn-outline-dark" type="button" data-bs-dismiss="modal">Close</button><button class="btn btn-primary" id="btnApplyBulkEdit" type="button">Apply to Selected</button></div>
            </div>
        </div>
    </div>

    <div class="offcanvas offcanvas-end" tabindex="-1" id="editstudent">
        <div class="offcanvas-header">
            <h4 class="offcanvas-title" style="font-weight: bold;">Edit Student's Password</h4><button class="btn-close" type="button" aria-label="Close" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body" style="border-color: rgb(255,255,255);">
            <form class="container" id="editStudentPasswordForm" method="POST" action="{{ route('user.updateStudentPassword') }}">
                @csrf
                <input type="hidden" name="student_id" id="editStudentId">
                <div class="row" style="margin: 0px -12px;margin-top: 15px;">
                    <div class="col-md-12">
                        <p style="margin-bottom: 2px;font-weight: bold;">New Password</p><input type="password" id="editPassword" name="password" required style="width: 100%;border-radius: 5px;border-width: 0.8px;border-color: rgb(4,0,0);">
                    </div>
                </div>
                <div class="row" style="margin: 0px -12px;margin-top: 15px;">
                    <div class="col-md-12">
                        <p style="margin-bottom: 2px;font-weight: bold;">Confirm Password</p><input type="password" id="editPasswordConfirmation" name="password_confirmation" required style="width: 100%;border-radius: 5px;border-width: 0.8px;border-color: rgb(4,0,0);">
                    </div>
                </div>
                <div class="row" style="margin: 0px -12px;margin-top: 10px;">
                    <div class="col-md-12">
                        <div class="small">
                            <p style="margin-bottom: 6px;font-weight: bold;">Password must include:</p>
                            <ul style="padding-left: 18px;margin-bottom: 0;">
                                <li id="ruleMinLength" class="text-muted">[ ] At least 8 characters</li>
                                <li id="ruleUppercase" class="text-muted">[ ] At least one uppercase letter (A-Z)</li>
                                <li id="ruleNumber" class="text-muted">[ ] At least one number (0-9)</li>
                                <li id="ruleSpecial" class="text-muted">[ ] At least one special character</li>
                                <li id="ruleMatch" class="text-muted">[ ] Password and confirm password are the same</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="row" style="margin-top: 15px;">
                    <div class="col-md-12 col-lg-10 text-end" style="width: 100%;"><button class="btn btn-primary" type="submit" style="background: rgb(78,115,223);margin: 0px 10px;">Edit</button><button class="btn btn-primary" type="button" data-bs-dismiss="offcanvas" style="background: rgb(231,74,59);">Cancel</button></div>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script src="{{ asset('js/Alert.js') }}"></script>
    <script>
        document.querySelectorAll('.js-edit-student-password').forEach((button) => {
            button.addEventListener('click', () => {
                document.getElementById('editStudentId').value = button.dataset.studentId;
                document.getElementById('editPassword').value = '';
                document.getElementById('editPasswordConfirmation').value = '';
                validateStudentPassword();
            });
        });

        const passwordForm = document.getElementById('editStudentPasswordForm');
        const passwordInput = document.getElementById('editPassword');
        const passwordConfirmationInput = document.getElementById('editPasswordConfirmation');
        const ruleMinLength = document.getElementById('ruleMinLength');
        const ruleUppercase = document.getElementById('ruleUppercase');
        const ruleNumber = document.getElementById('ruleNumber');
        const ruleSpecial = document.getElementById('ruleSpecial');
        const ruleMatch = document.getElementById('ruleMatch');

        const setRuleState = (ruleElement, isMet, label) => {
            ruleElement.textContent = `${isMet ? '[✓]' : '[ ]'} ${label}`;
            ruleElement.classList.toggle('text-success', isMet);
            ruleElement.classList.toggle('text-muted', !isMet);
        };

        const validateStudentPassword = () => {
            const password = passwordInput.value;
            const passwordConfirmation = passwordConfirmationInput.value;
            const checks = {
                minLength: password.length >= 8,
                uppercase: /[A-Z]/.test(password),
                number: /[0-9]/.test(password),
                special: /[\W_]/.test(password),
                match: passwordConfirmation.length > 0 && password === passwordConfirmation,
            };

            setRuleState(ruleMinLength, checks.minLength, 'At least 8 characters');
            setRuleState(ruleUppercase, checks.uppercase, 'At least one uppercase letter (A-Z)');
            setRuleState(ruleNumber, checks.number, 'At least one number (0-9)');
            setRuleState(ruleSpecial, checks.special, 'At least one special character');
            setRuleState(ruleMatch, checks.match, 'Password and confirm password are the same');

            return Object.values(checks).every(Boolean);
        };

        passwordForm.addEventListener('submit', (event) => {
            if (!validateStudentPassword()) {
                event.preventDefault();
            }
        });

        passwordInput.addEventListener('input', validateStudentPassword);
        passwordConfirmationInput.addEventListener('input', validateStudentPassword);
    </script>
    @endpush
