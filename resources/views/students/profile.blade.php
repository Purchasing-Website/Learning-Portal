@extends('layouts.student_app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endpush

@section('content')
<div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-end gap-3 mb-3">
      <div>
        <h1 class="lp-title">My Profile</h1>
        <p class="lp-subtitle">Update your personal details. Required fields are marked with *</p>
      </div>

      <!-- Desktop top actions -->
      <!-- Top action bar (Save/Reset) -->
		<div class="d-flex flex-column flex-sm-row align-items-stretch align-items-sm-center justify-content-between gap-2 mb-3">
		  <div class="lp-status" id="saveStatusTop"></div>

		  <div class="d-flex gap-2 justify-content-sm-end">
			<button type="button" class="btn lp-btn-outline" id="btnResetTop">
			  <i class="bi bi-arrow-counterclockwise me-1"></i>Reset
			</button>
			<button type="button" class="btn lp-btn-save" id="btnSaveTop" disabled>
			  <i class="bi bi-check2-circle me-1"></i>Save
			</button>
		  </div>
		</div>
    </div>

    <div class="row g-3 g-lg-4">
      <!-- Left: Profile form -->
      <div class="col-12">
        <div class="lp-card">
          <div class="lp-accent"></div>
          <div class="p-3 p-lg-4">

            <!-- Profile header block -->
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-3">
              <div class="d-flex align-items-center gap-3">
                <div class="lp-avatar" id="avatarInitials">HL</div>
                <div>
                  <div class="fw-black fs-5 fw-bold" id="profileNamePreview">Hao Lin</div>
                  <div class="text-secondary small" id="profileEmailPreview">haolin@example.com</div>
                  <div class="d-flex gap-2 flex-wrap mt-2">
                    <span class="lp-pill"><i class="bi bi-shield-check"></i>Student Account</span>
                    <span class="lp-pill"><i class="bi bi-person-vcard"></i>Profile Editable</span>
                  </div>
                </div>
              </div>

              <!-- Mobile quick save indicator (optional) -->
              <div class="d-lg-none">
                <div class="lp-status text-end" id="saveStatusMobile"></div>
              </div>
            </div>

            <hr class="my-3">
			
			

            <form id="profileForm" novalidate>
			<div id="saveAlert" class="alert d-none mb-3" role="alert"></div>
              <div class="row g-3">
                <!-- First Name -->
                <div class="col-12 col-md-6">
                  <label class="form-label fw-semibold lp-required" for="firstName">First Name</label>
                  <input class="form-control lp-field" id="firstName" name="firstName" type="text" placeholder="e.g. Hao" required>
                  <div class="invalid-feedback">First name is required.</div>
                </div>

                <!-- Last Name -->
                <div class="col-12 col-md-6">
                  <label class="form-label fw-semibold lp-required" for="lastName">Last Name</label>
                  <input class="form-control lp-field" id="lastName" name="lastName" type="text" placeholder="e.g. Lin" required>
                  <div class="invalid-feedback">Last name is required.</div>
                </div>

                <!-- Email -->
                <div class="col-12">
                  <label class="form-label fw-semibold lp-required" for="email">Email Address</label>
                  <input class="form-control lp-field" id="email" name="email" type="email" placeholder="name@email.com" required>
                  <div class="invalid-feedback">Please enter a valid email address.</div>
                </div>

                <!-- Birthdate (optional) -->
                <div class="col-12 col-md-6">
                  <label class="form-label fw-semibold" for="birthdate">Birthdate (Optional)</label>
                  <input class="form-control lp-field" id="birthdate" name="birthdate" type="date">
                </div>

                <!-- Gender (optional) -->
                <div class="col-12 col-md-6">
                  <label class="form-label fw-semibold" for="gender">Gender (Optional)</label>
                  <select class="form-select lp-field" id="gender" name="gender">
                    <option value="">Select…</option>
					<option value="male">Male</option>
                    <option value="female">Female</option>
                    <option value="prefer_not">Prefer not to say</option>
                  </select>
                </div>

                <!-- Phone (optional) -->
                <div class="col-12">
                  <label class="form-label fw-semibold" for="phone">Phone Number (Optional)</label>
                  <input class="form-control lp-field" id="phone" name="phone" type="tel" placeholder="e.g. +60 12-345 6789">
                  <div class="form-text text-secondary">
                    Tip: include country code (e.g. +60).
                  </div>
                </div>
              </div>

              <hr class="my-4">

              <!-- Inline info -->
              <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2">
                <div class="text-secondary small">
                  <i class="bi bi-info-circle me-1"></i>
                  Your profile helps personalize learning recommendations.
                </div>
                <div class="text-secondary small">
                  <i class="bi bi-exclamation-circle me-1"></i>
                  Required: First name, Last name, Email
                </div>
              </div>

            </form>

          </div>
        </div>
      </div>
    </div>

    <!-- Mobile bottom bar -->
    <div class="lp-bottom-bar">
      <div class="container d-flex gap-2 align-items-center">
        <button class="btn lp-btn-outline flex-grow-1" id="btnResetMobile">
          <i class="bi bi-arrow-counterclockwise me-1"></i>Reset
        </button>
        <button class="btn lp-btn-save flex-grow-1" id="btnSaveMobile" disabled>
          <i class="bi bi-check2-circle me-1"></i>Save
        </button>
      </div>
    </div>
@endsection

@push('scripts')
<script>
  // ===== Sample initial profile data (replace with server data later) =====
  const INITIAL_PROFILE = {
    firstName: "Hao",
    lastName: "Lin",
    email: "haolin@example.com",
    birthdate: "",
    gender: "",
    phone: ""
  };

  // ===== DOM =====
  const form = document.getElementById("profileForm");
  const inputs = {
    firstName: document.getElementById("firstName"),
    lastName: document.getElementById("lastName"),
    email: document.getElementById("email"),
    birthdate: document.getElementById("birthdate"),
    gender: document.getElementById("gender"),
    phone: document.getElementById("phone"),
  };

const btnSaveTop = document.getElementById("btnSaveTop");
const btnResetTop = document.getElementById("btnResetTop");
const saveStatusTop = document.getElementById("saveStatusTop");
const saveAlert = document.getElementById("saveAlert");

  //const btnSaveDesktop = document.getElementById("btnSaveDesktop");
  //const btnResetDesktop = document.getElementById("btnResetDesktop");

  //const btnSaveSide = document.getElementById("btnSaveSide");
  //const btnResetSide = document.getElementById("btnResetSide");

  //const btnSaveMobile = document.getElementById("btnSaveMobile");
  //const btnResetMobile = document.getElementById("btnResetMobile");

function showAlert(message, type){
// type: "success" | "danger"
saveAlert.className = `alert alert-${type} mb-3`;
saveAlert.textContent = message;
saveAlert.classList.remove("d-none");
}

function clearAlert(){
saveAlert.classList.add("d-none");
saveAlert.textContent = "";
}

function setStatus(msg){
saveStatusTop.textContent = msg || "";
}

  const avatarInitials = document.getElementById("avatarInitials");
  const profileNamePreview = document.getElementById("profileNamePreview");
  const profileEmailPreview = document.getElementById("profileEmailPreview");

  let lastSaved = structuredClone(INITIAL_PROFILE);

  // ===== Helpers =====

  function getCurrent(){
    return {
      firstName: inputs.firstName.value.trim(),
      lastName: inputs.lastName.value.trim(),
      email: inputs.email.value.trim(),
      birthdate: inputs.birthdate.value,
      gender: inputs.gender.value,
      phone: inputs.phone.value.trim(),
    };
  }

  function hasChanges(){
    const cur = getCurrent();
    return JSON.stringify(cur) !== JSON.stringify(lastSaved);
  }

function setButtonsEnabled(enabled){
btnSaveTop.disabled = !enabled;
}


  function loadProfile(p){
    inputs.firstName.value = p.firstName || "";
    inputs.lastName.value = p.lastName || "";
    inputs.email.value = p.email || "";
    inputs.birthdate.value = p.birthdate || "";
    inputs.gender.value = p.gender || "";
    inputs.phone.value = p.phone || "";
    validateAndToggle();
    setStatus("");
  }

  function initials(fn, ln){
    const a = (fn || "").trim().slice(0,1).toUpperCase();
    const b = (ln || "").trim().slice(0,1).toUpperCase();
    return (a + b) || "U";
  }

  function validateRequired(){
    // Bootstrap validation: first name, last name required; email required + valid
    let ok = true;

    // first name
    if(!inputs.firstName.value.trim()){
      inputs.firstName.classList.add("is-invalid");
      ok = false;
    } else {
      inputs.firstName.classList.remove("is-invalid");
    }

    // last name
    if(!inputs.lastName.value.trim()){
      inputs.lastName.classList.add("is-invalid");
      ok = false;
    } else {
      inputs.lastName.classList.remove("is-invalid");
    }

    // email
    const emailVal = inputs.email.value.trim();
    const emailValid = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailVal);
    if(!emailVal || !emailValid){
      inputs.email.classList.add("is-invalid");
      ok = false;
    } else {
      inputs.email.classList.remove("is-invalid");
    }

    return ok;
  }

  function validateAndToggle(){
    const requiredOk = validateRequired();
  
  clearAlert();
  
    // enable Save only when: required valid AND has changes
    const enableSave = requiredOk && hasChanges();
    setButtonsEnabled(enableSave);

    if(enableSave){
      setStatus("Unsaved changes", "");
    } else {
      setStatus("");
    }
  }

  // ===== Actions =====
function doSave(){
clearAlert();

const requiredOk = validateRequired();
if(!requiredOk){
  showAlert("Please fix required fields before saving.", "danger");
  return;
}

const payload = getCurrent();

// ===== Demo: simulate saving (80% success) =====
const isSuccess = Math.random() > 0.2;

if(isSuccess){
  lastSaved = structuredClone(payload);
  setButtonsEnabled(false);
  setStatus("");
  showAlert("Saved successfully ✓", "success");
} else {
  showAlert("Save failed. Please try again.", "danger");
}

// ===== Real Laravel example (replace demo above later) =====
// fetch("/student/profile", {
//   method: "POST",
//   headers: {
//     "Content-Type": "application/json",
//     "X-CSRF-TOKEN": "YOUR_TOKEN"
//   },
//   body: JSON.stringify(payload)
// })
// .then(async res => {
//   if(!res.ok) throw new Error("HTTP " + res.status);
//   lastSaved = structuredClone(payload);
//   setButtonsEnabled(false);
//   setStatus("");
//   showAlert("Saved successfully ✓", "success");
// })
// .catch(err => {
//   showAlert("Save failed: " + err.message, "danger");
// });
}


  function doReset(){
  clearAlert();
    loadProfile(lastSaved);
    setStatus("Reverted to last saved.", "");
  showAlert("Changes reverted.", "success");
  }

  // ===== Events =====
  document.addEventListener("DOMContentLoaded", () => {
    loadProfile(lastSaved);
  
  Object.values(inputs).forEach(el => {
  el.addEventListener("input", validateAndToggle);
  el.addEventListener("change", validateAndToggle);
  });

    btnSaveTop.addEventListener("click", doSave);
  btnResetTop.addEventListener("click", doReset);

  });
</script>

@endpush
