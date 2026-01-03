@extends('layouts.Custom_app', ['title' => 'Class'])

@section('content')
    <div class="d-flex flex-column" id="content-wrapper">
        <div id="content">
            <nav class="navbar navbar-expand bg-dark shadow mb-4 topbar static-top navbar-light">
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
                                        <h1 style="margin: 0px 0px;margin-bottom: 0px;width: auto;height: 100%;padding: 5px 0px;">Program Management</h1>
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
                                                <th class="text-nowrap">Program ID</th>
                                                <th class="text-nowrap">Program Name</th>
                                                <th class="text-nowrap">Image</th>
                                                <th class="text-nowrap">Description</th>
                                                <th class="text-nowrap">Date Started</th>
                                                <th class="text-nowrap text-start">Total Students</th>
                                                <th class="text-nowrap">Status</th>
                                                <th class="text-nowrap text-center">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($programs as $program)
                                                <tr style="max-width: 49px;">
                                                    <td class="text-truncate" style="max-width: 200px;">{{ $program->id }}</td>
                                                    <td class="text-truncate" style="max-width: 200px;">{{ $program->title }}</td>
                                                    <td class="text-truncate" style="max-width: 200px;"><img class="img-fluid" width="299" height="180" src="assets/img/OIP.webp" style="max-width: 120px;max-height: 100px;"></td>
                                                    <td style="max-width: 50px;">{{ Str::limit($program->description, 50) }}</td>
                                                    <td>{{ $program->created_by ?? 'N/A' }}</td>
                                                    <td class="text-start">{{$program->students_count}}</td>
                                                    <td class="status-cell">
                                                        @if($program->is_active)
                                                            <span class="badge bg-success">Active</span>
                                                        @else
                                                            <span class="badge bg-secondary">Inactive</span>
                                                        @endif
                                                        <div class="custom__checkbox-wrap">
                                                            <input type="checkbox" id="checkBox-id" class="d-none checkbox">
                                                        </div>
                                                    </td>
                                                    <td class="text-nowrap text-start text-center">
                                                        <a class="btn btn-dark" role="button" style="width: 25px;height: 25px;padding: 3px 3px;text-align: center;margin: 0px 3px;background: rgba(242,242,242,0);border-style: none;" href="{{route('course.index',$program->id)}}">
                                                            <i class="material-icons text-dark" id="showAlertBtn" style="font-size: 19px;--bs-primary: #4e73df;--bs-primary-rgb: 78,115,223;color: rgb(255,255,255);" type="button">remove_red_eye</i>
                                                        </a>
                                                        <button id='editBtn' data-id="{{ $program->id }}" class="btn btn-dark editBtn" role="button" style="width: 25px;height: 25px;padding: 3px 3px;text-align: center;margin: 0px 5px;background: rgba(242,242,242,0);border-style: none;">
                                                            <i class="material-icons text-dark" id="showAlertBtn-5" style="font-size: 19px;--bs-primary: #4e73df;--bs-primary-rgb: 78,115,223;color: rgb(255,255,255);" type="button">edit</i>
                                                        </button>
                                                        <button class="btn btn-dark toggleStatus" data-id="{{ $program->id }}" role="button" style="width: 25px;height: 25px;padding: 3px 3px;text-align: center;margin: 0px 3px;background: rgba(242,242,242,0);border-style: none;">
                                                            <i class="material-icons text-dark" id="showAlertBtn-6" style="font-size: 19px;--bs-primary: #4e73df;--bs-primary-rgb: 78,115,223;color: rgb(255,255,255);" type="button">do_not_disturb_alt</i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="8" class="text-center">No programs found.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                    <!-- Pagination -->
                                    {{-- <div class="d-flex justify-content-center">
                                        {{ $programs->links() }}
                                    </div> --}}
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
            <h5 class="offcanvas-title">Add Program</h5><button class="btn-close" type="button" aria-label="Close" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body" style="border-color: rgb(255,255,255);">
            <div class="container">
                 <div class="row" style="margin-bottom: 10px;">
                    <div class="col-md-12">
                        <p style="margin-bottom: 2px;">Program Image</p>
                        <div class="position-relative rounded vPreviewImage" id="someId" style="width: 96px;height: 96px;background: url('assets/img/input_image_preview/upload_image.png') center / cover no-repeat;" input-data-index="0"><button class="btn position-sticky d-none close vClearPreviewImage" type="button"><span class="bg-white pl-2 pr-2" aria-hidden="true">&times;</span></button><input type="file" class="vInputImage" style="width: 96px;height: 96px;opacity: 0;cursor: pointer;" accept="image/*"></div>
                    </div>
                </div>
                <form method="POST" action="{{route('program.store')}}">
                    @csrf

                    <div class="row" style="margin-bottom: 10px;">
                        <div class="col-md-12">
                            <p style="margin-bottom: 2px;" for="programName" >Program Name</p>
                            <input type="text" style="width: 100%;" name='title' id="programName"  value="{{ old('title') }}" placeholder="Eg. Feng Shui">
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row" style="margin-bottom: 10px;">
                        <div class="col-md-12">
                            <p style="margin-bottom: 2px;" for="programDescription">Description</p>
                            <textarea style="width: 100%;" name='description' id="programDescription" placeholder="Program Description"></textarea>
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
    <div class="offcanvas offcanvas-end" tabindex="-1" id="editProgramModal">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title">Edit Program</h5><button class="btn-close" type="button" aria-label="Close" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body" style="border-color: rgb(255,255,255);">
            <div class="container">
                <form id="updateProgramForm">
                    @csrf
                    <input type="hidden" id="program_id">
                    <div class="row" style="margin-bottom: 10px;">
                        <div class="col-md-12">
                            <p style="margin-bottom: 2px;">Program Image</p>
                            <div class="position-relative rounded vPreviewImage" id="someId" style="width: 96px;height: 96px;background: url('assets/img/input_image_preview/upload_image.png') center / cover no-repeat;" input-data-index="0"><button class="btn position-sticky d-none close vClearPreviewImage" type="button"><span class="bg-white pl-2 pr-2" aria-hidden="true">&times;</span></button><input type="file" class="vInputImage" style="width: 96px;height: 96px;opacity: 0;cursor: pointer;" accept="image/*"></div>
                        </div>
                    </div>
                    <div class="row" style="margin-bottom: 10px;">
                        <div class="col-md-12">
                            <p style="margin-bottom: 2px;">Program Name</p>
                            <input type="text" style="width: 100%;" placeholder="Eg. Feng Shui" id="program_name">
                        </div>
                    </div>
                    <div class="row" style="margin-bottom: 10px;">
                        <div class="col-md-12">
                            <p style="margin-bottom: 2px;">Description</p>
                            <textarea style="width: 100%;" placeholder="Program Description" id="program_description">

                            </textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-lg-10 text-end" style="width: 100%;">
                            <button class="btn btn-primary" type="submit" data-bs-dismiss="offcanvas" style="background: rgb(78,115,223);margin: 0px 10px;">Edit</button>
                            <button class="btn btn-primary" type="button" data-bs-dismiss="offcanvas" style="background: rgb(231,74,59);">Cancel</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" role="dialog" tabindex="-1" id="confirmStatusModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Delete Alert!</h4><button class="btn-close" type="button" aria-label="Close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p id="programActivation"></p>
                </div>
                <div class="modal-footer">
                    <form id="updateProgramStatus">
                        @csrf
                        <input type="hidden" id="programStatusId" >
                        <button class="btn btn-primary" id="showAlertBtn-7" type="submit" data-bs-target="#modal-2" data-bs-toggle="modal" data-bs-dismiss="modal" style="background: rgb(231,74,59);">Yes</button>
                    </form>
                    <button class="btn btn-light" type="button" data-bs-dismiss="modal" style="background: rgb(13,110,253);color: rgb(255,255,255);">No</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", () => {
    const editButtons = document.querySelectorAll(".editBtn");
    const modal = new bootstrap.Offcanvas(document.getElementById('editProgramModal'));

    editButtons.forEach(btn => {
        btn.addEventListener("click", async () => {
            const id = btn.dataset.id;

            const response = await fetch(`/program/${id}/edit`);
            const program = await response.json();

            document.getElementById("program_id").value = program.id;
            document.getElementById("program_name").value = program.title;
            document.getElementById("program_description").value = program.description || '';
            //document.getElementById("program_duration").value = program.duration || '';

            modal.show();
        });
    });

    // Update form submit
    document.getElementById("updateProgramForm").addEventListener("submit", async (e) => {
        e.preventDefault();

        const id = document.getElementById("program_id").value;
        const formData = new FormData();
        formData.append('_token', document.querySelector('input[name="_token"]').value);
        formData.append('title', document.getElementById("program_name").value);
        formData.append('description', document.getElementById("program_description").value);
        //formData.append('duration', document.getElementById("program_duration").value);

        const res = await fetch(`/program/${id}/update`, {
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
    const toggleStatusModel = new bootstrap.Modal(document.getElementById('confirmStatusModal'));

    buttons.forEach(button => {
        button.addEventListener('click', async function() {
            const id = this.dataset.id;
            const row = this.closest('tr');
            const badge = row.querySelector('.status-cell span');
            const isCurrentlyActive = this.textContent.trim() === 'Active';
            const confirmMessage = `Are you sure you want to ${isCurrentlyActive ? 'deactivate' : 'activate'} this program?`;

            document.getElementById("programActivation").textContent = confirmMessage;
            document.getElementById("programStatusId").value = id;

            toggleStatusModel.show();
        });
    });

    // Update form submit
    document.getElementById("updateProgramStatus").addEventListener("submit", async (e) => {
        e.preventDefault();
        try {
            const program_id = document.getElementById("programStatusId").value;

            const response = await fetch(`/program/${program_id}/toggle-status`, {
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
</script>
@endpush