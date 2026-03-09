@extends('layouts.admin_app')
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
                        <form id="enrolForm" data-save-url="{{ url('/admin/tier/__TIER__/save') }}">
                            @csrf
                            <div class="mb-3 enrol-controls">
                                <div class="row align-items-end g-2">
                                    <div class="col col-md-4 col-lg-4 col-xl-4 col-xxl-2">
                                        <label class="form-label fw-bold mb-1 form-label" style="color: rgb(165,172,186);">User Level</label>
                                        <select id="tierSelect" class="form-select" data-filter-url="{{ url('/admin/tier/__TIER__') }}" onchange="filterTierStudents(this)">
                                            <option value=''>-- Choose a Tier --</option>
                                            @foreach ($tiers as $tier)
                                                <option value='{{ $tier->id }}'>{{$tier->name}}</option>
                                            @endforeach
                                        </select>
                                    </div> 
                                </div>
                            </div>
                            @php
                                $assignedStudents = collect($within_tier ?? []);
                                $availableStudents = collect($outside_tier ?? []);
                            @endphp
                            <div class="row flex-grow-1 align-items-stretch g-3">
                                <div class="col col-md-4 d-flex flex-column">
                                    <h4 id="leftTitle">Available Students</h4>
                                    <select class="form-select" id="selAvailable" multiple="" style="height: 50vh;min-height: 260px;max-height: 520px;">
                                        @foreach ($availableStudents as $student)
                                        <option value="{{ $student->id ?? $student['id'] ?? '' }}">{{ $student->name ?? $student['name'] ?? '' }}</option>
                                        @endforeach
                                    </select>
                                    <small class="text-muted" id="availableCount">{{ $availableStudents->count() }} Available</small>
                                </div>
                                <div class="col col-md-4 d-flex flex-column justify-content-center align-items-center gap-2" style="height: 370px;">
                                    <button class="btn btn-primary" id="btnAdd" type="button" style="padding: 6px 6px;width: 120px;" disabled>Add &gt;</button>
                                    <button class="btn btn-secondary" id="btnRemove" type="button" style="width: 120px;padding: 6px 6px;">&lt; Remove</button>
                                    <hr class="my-2" style="width: 120px;">
                                    <button class="btn btn-outline-primary" id="btnAddAll" type="button" style="padding: 6px 6px;width: 120px;">Add All&nbsp;&gt;&gt;</button>
                                    <button class="btn btn-outline-secondary" id="btnRemoveAll" type="button" style="width: 120px;padding: 6px 6px;">&lt;&lt; Remove All</button>
                                </div>
                                <div class="col col-md-4 d-flex flex-column">
                                    <h4 id="rightTitle">Assigned Students</h4>
                                    <select class="form-select" id="selEnrolled" multiple="" style="height: 50vh;min-height: 260px;max-height: 520px;">
                                        @foreach ($assignedStudents as $student)
                                        <option value="{{ $student->id ?? $student['id'] ?? '' }}">{{ $student->name ?? $student['name'] ?? '' }}</option>
                                        @endforeach</select>
                                        <small class="text-muted" id="enrolledCount">{{ $assignedStudents->count() }} Assigned</small>
                                </div>
                            </div>
                            <input class="form-control" type="hidden" id="studentIds" name="student_ids">
                            <input class="form-control" type="hidden" id="payloadMode" name="payload_mode">
                            <input class="form-control" type="hidden" id="payloadStudentId" name="payload_student_id">
                            <input class="form-control" type="hidden" id="payloadClassId" name="payload_class_id">
                            <div class="d-flex justify-content-end mt-3">
                                <button class="btn btn-success" type="submit">Save</button>
                            </div>
                        </form>
                    </div>
                    <div class="card-footer"></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function updateStudentCounts() {
            const selAvailable = document.getElementById('selAvailable');
            const selEnrolled = document.getElementById('selEnrolled');
            document.getElementById('availableCount').textContent = `${selAvailable.options.length} Available`;
            document.getElementById('enrolledCount').textContent = `${selEnrolled.options.length} Assigned`;
        }

        function updateButtonStates() {
            const selAvailable = document.getElementById('selAvailable');
            const selEnrolled = document.getElementById('selEnrolled');
            const btnAdd = document.getElementById('btnAdd');
            const btnRemove = document.getElementById('btnRemove');
            const btnAddAll = document.getElementById('btnAddAll');
            const btnRemoveAll = document.getElementById('btnRemoveAll');

            btnAdd.disabled = selAvailable.selectedOptions.length === 0;
            btnRemove.disabled = selEnrolled.selectedOptions.length === 0;
            btnAddAll.disabled = selAvailable.options.length === 0;
            btnRemoveAll.disabled = selEnrolled.options.length === 0;
        }

        function moveSelectedToAssigned() {
            const selAvailable = document.getElementById('selAvailable');
            const selEnrolled = document.getElementById('selEnrolled');
            const selected = Array.from(selAvailable.selectedOptions);

            if (selected.length === 0) return;

            const frag = document.createDocumentFragment();
            selected.forEach((option) => {
                option.selected = false;
                frag.appendChild(option);
            });

            selEnrolled.appendChild(frag);
            updateStudentCounts();
            updateButtonStates();
        }

        function moveSelectedToAvailable() {
            const selAvailable = document.getElementById('selAvailable');
            const selEnrolled = document.getElementById('selEnrolled');
            const selected = Array.from(selEnrolled.selectedOptions);

            if (selected.length === 0) return;

            const frag = document.createDocumentFragment();
            selected.forEach((option) => {
                option.selected = false;
                frag.appendChild(option);
            });

            selAvailable.appendChild(frag);
            updateStudentCounts();
            updateButtonStates();
        }

        function moveAllToAssigned() {
            const selAvailable = document.getElementById('selAvailable');
            const selEnrolled = document.getElementById('selEnrolled');

            if (selAvailable.options.length === 0) return;

            const frag = document.createDocumentFragment();
            Array.from(selAvailable.options).forEach((option) => {
                option.selected = false;
                frag.appendChild(option);
            });

            selEnrolled.appendChild(frag);
            updateStudentCounts();
            updateButtonStates();
        }

        function moveAllToAvailable() {
            const selAvailable = document.getElementById('selAvailable');
            const selEnrolled = document.getElementById('selEnrolled');

            if (selEnrolled.options.length === 0) return;

            const frag = document.createDocumentFragment();
            Array.from(selEnrolled.options).forEach((option) => {
                option.selected = false;
                frag.appendChild(option);
            });

            selAvailable.appendChild(frag);
            updateStudentCounts();
            updateButtonStates();
        }

        function renderStudentOptions(selectEl, students) {
            selectEl.innerHTML = '';
            const fragment = document.createDocumentFragment();

            students.forEach((student) => {
                const option = document.createElement('option');
                option.value = student.id ?? '';
                option.textContent = student.name ?? '';
                fragment.appendChild(option);
            });

            selectEl.appendChild(fragment);
        }

        async function filterTierStudents(selectEl) {
            const selectedTier = selectEl.value;
            const selAvailable = document.getElementById('selAvailable');
            const selEnrolled = document.getElementById('selEnrolled');
            const availableCount = document.getElementById('availableCount');
            const enrolledCount = document.getElementById('enrolledCount');

            if (!selectedTier) {
                sessionStorage.removeItem('selectedTier');
                renderStudentOptions(selAvailable, []);
                renderStudentOptions(selEnrolled, []);
                availableCount.textContent = '0 Available';
                enrolledCount.textContent = '0 Assigned';
                updateButtonStates();
                return;
            }

            sessionStorage.setItem('selectedTier', selectedTier);
            const url = selectEl.dataset.filterUrl.replace('__TIER__', encodeURIComponent(selectedTier));
            const response = await fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });

            if (!response.ok) return;

            const data = await response.json();
            const outsideTier = Array.isArray(data.outside_tier) ? data.outside_tier : [];
            const withinTier = Array.isArray(data.within_tier) ? data.within_tier : [];

            renderStudentOptions(selAvailable, outsideTier);
            renderStudentOptions(selEnrolled, withinTier);
            availableCount.textContent = `${outsideTier.length} Available`;
            enrolledCount.textContent = `${withinTier.length} Assigned`;
            updateButtonStates();
        }

        async function saveTierChanges(event) {
            event.preventDefault();

            const tierSelect = document.getElementById('tierSelect');
            const selectedTier = tierSelect.value;
            if (!selectedTier) {
                alert('Please choose a tier before saving.');
                return;
            }

            const form = document.getElementById('enrolForm');
            const tokenInput = form.querySelector('input[name="_token"]');
            const selEnrolled = document.getElementById('selEnrolled');
            const assignedIds = Array.from(selEnrolled.options)
                .map((option) => option.value)
                .filter((id) => id !== '');

            const payload = new URLSearchParams();
            payload.append('_token', tokenInput ? tokenInput.value : '');
            assignedIds.forEach((id) => payload.append('assigned_ids[]', id));

            const saveUrl = form.dataset.saveUrl.replace('__TIER__', encodeURIComponent(selectedTier));
            const response = await fetch(saveUrl, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'Content-Type': 'application/x-www-form-urlencoded;charset=UTF-8'
                },
                body: payload.toString()
            });

            if (!response.ok) {
                alert('Failed to save tier assignments.');
                return;
            }

            const result = await response.json();
            alert(result.message || 'Tier assignments saved successfully.');
            await filterTierStudents(tierSelect);
        }

        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('enrolForm');
            const tierSelect = document.getElementById('tierSelect');
            const selAvailable = document.getElementById('selAvailable');
            const selEnrolled = document.getElementById('selEnrolled');
            const btnAdd = document.getElementById('btnAdd');
            const btnRemove = document.getElementById('btnRemove');
            const btnAddAll = document.getElementById('btnAddAll');
            const btnRemoveAll = document.getElementById('btnRemoveAll');
            const savedTier = sessionStorage.getItem('selectedTier');

            selAvailable.addEventListener('change', updateButtonStates);
            selEnrolled.addEventListener('change', updateButtonStates);
            btnAdd.addEventListener('click', moveSelectedToAssigned);
            btnRemove.addEventListener('click', moveSelectedToAvailable);
            btnAddAll.addEventListener('click', moveAllToAssigned);
            btnRemoveAll.addEventListener('click', moveAllToAvailable);
            form.addEventListener('submit', saveTierChanges);
            updateButtonStates();

            if (savedTier && tierSelect.querySelector(`option[value="${savedTier}"]`)) {
                tierSelect.value = savedTier;
                filterTierStudents(tierSelect);
            }
        });
    </script>
@endpush
