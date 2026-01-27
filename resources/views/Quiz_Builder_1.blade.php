<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Quiz Builder</title>

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="{{ asset('css/bootstrap/css/bootstrap.min.css') }}">

  <!-- Bootstrap Icons (optional but recommended) -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"/>

  <style>
    :root { --panel-radius: 14px; }

    body { background: #f7f8fb; }
    .qb-shell { max-width: 1320px; }
    .qb-card {
      border: 1px solid rgba(15,23,42,.08);
      border-radius: var(--panel-radius);
      box-shadow: 0 6px 24px rgba(15,23,42,.06);
      background: #fff;
    }
    .qb-list {
      max-height: calc(100vh - 210px);
      overflow: auto;
    }
    .qb-list .list-group-item {
      border-left: 0; border-right: 0;
      cursor: pointer;
    }
    .qb-list .list-group-item.active {
      background: rgba(37,99,235,.10);
      color: #0f172a;
      border-color: rgba(37,99,235,.25);
    }
    .qb-badge {
      font-size: .75rem;
      border: 1px solid rgba(15,23,42,.10);
      background: rgba(15,23,42,.04);
      color: rgba(15,23,42,.75);
    }
    .qb-muted { color: rgba(15,23,42,.65); }
    .qb-option-row {
      border: 1px solid rgba(15,23,42,.10);
      border-radius: 12px;
      padding: 10px;
      background: rgba(15,23,42,.02);
    }
    .qb-sticky-actions {
      position: sticky;
      bottom: 0;
      background: linear-gradient(to top, #fff 65%, rgba(255,255,255,.65));
      padding-top: 12px;
      margin-top: 14px;
    }
    .qb-required::after { content:" *"; color:#dc3545; }
    .qb-code {
      font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
      font-size: .86rem;
      white-space: pre-wrap;
      background: #0b1220;
      color: #dbeafe;
      border-radius: 12px;
      padding: 12px;
    }
  </style>

  <script>
    // Laravel payload (quiz + questions + options)
    //window.QUIZ_PAYLOAD = {};
    let questionTypes = @json($questionTypes);

  </script>

</head>

<body>
  {{-- <form > --}}
  <div class="container-fluid py-4 qb-shell">
    <!-- Header -->
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
      <div>
        <h2 class="mb-1">Quiz Builder</h2>
        <div class="qb-muted">Add & edit questions and answer options in one page.</div>
      </div>

      <div class="d-flex align-items-center gap-2">
        <button class="btn btn-outline-secondary" id="btnPreviewJson">
          <i class="bi bi-braces"></i> Preview JSON
        </button>
        <button class="btn btn-primary" id="btnSaveAll">
          <i class="bi bi-cloud-arrow-up"></i> Save
        </button>
      </div>
    </div>

    <!-- Quiz meta -->
    <div class="qb-card p-3 mb-3">
      <div class="row g-3">
        <div class="col-12 col-lg-3">
		  <label class="form-label qb-required">Class Name</label>

		  <div class="dropdown">
			<input class="form-control"
				   id="classNameInput"
				   type="text"
				   placeholder="Search class..."
				   autocomplete="off"
				   data-bs-toggle="dropdown"
				   aria-expanded="false">

			<ul class="dropdown-menu w-100" id="classDropdown" style="max-height: 260px; overflow:auto;">
			  <!-- items injected by JS -->
			</ul>
		  </div>

		  <!-- store selected class id -->
		  <input type="hidden" id="classId">
		</div>

        <div class="col-12 col-lg-3">
          <label class="form-label qb-required">Quiz Type</label>
          <select class="form-select" id="quizType">
            <option value="final">Final Quiz</option>
            <option value="kc">Knowledge Check</option>
          </select>
        </div>
        <div class="col-12 col-lg-3">
          <label class="form-label ">Pass Score (%)</label>
          <input class="form-control" id="passScore" type="number" min="0" max="100" value="80"/>
        </div>
        <div class="col-12 col-lg-3">
          <label class="form-label">Status</label>
          <select class="form-select" id="status">
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
          </select>
        </div>

        <div class="col-12">
          <label class="form-label qb-required">Title</label>
          <input class="form-control" id="quizTitle" placeholder="e.g. Lesson 1 Knowledge Check"/>
        </div>
        <div class="col-12">
          <label class="form-label">Instructions</label>
          <textarea class="form-control" id="instructions" rows="2"
                    placeholder="Optional instructions shown to students..."></textarea>
        </div>
      </div>
    </div>

    <!-- Main: left list + right editor -->
    <div class="row g-3">
      <!-- Left: question list -->
      <div class="col-12 col-lg-4">
        <div class="qb-card p-3">
          <div class="d-flex align-items-center justify-content-between mb-2">
            <div class="fw-semibold">Questions</div>
            <button class="btn btn-sm btn-success" id="btnAddQuestion" type="button">
              <i class="bi bi-plus-lg"></i> Add
            </button>
          </div>

          <div class="d-flex gap-2 mb-3">
            <input class="form-control form-control-sm" id="questionSearch" placeholder="Search question...">
            <button class="btn btn-sm btn-outline-secondary" id="btnClearSearch">
              <i class="bi bi-x-lg"></i>
            </button>
          </div>

          <div class="list-group qb-list" id="questionList"></div>

          <div class="mt-3 qb-muted small">
            Tip: Use <b>Duplicate</b> for similar questions. Use <b>Up/Down</b> to reorder.
          </div>
        </div>
      </div>

      <!-- Right: editor -->
      <div class="col-12 col-lg-8">
        <div class="qb-card p-3" id="editorCard">
          <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-2">
            <div class="fw-semibold">Question Editor</div>

            <div class="d-flex flex-wrap gap-2">
              <button class="btn btn-sm btn-outline-secondary" id="btnMoveUp" title="Move up">
                <i class="bi bi-arrow-up"></i>
              </button>
              <button class="btn btn-sm btn-outline-secondary" id="btnMoveDown" title="Move down">
                <i class="bi bi-arrow-down"></i>
              </button>
              <button class="btn btn-sm btn-outline-primary" id="btnDuplicate" title="Duplicate">
                <i class="bi bi-copy"></i> Duplicate
              </button>
              <button class="btn btn-sm btn-outline-danger" id="btnDelete" title="Delete">
                <i class="bi bi-trash"></i> Delete
              </button>
            </div>
          </div>

          <div id="emptyState" class="text-center py-5 qb-muted">
            <div class="fs-1 mb-2"><i class="bi bi-ui-checks"></i></div>
            <div class="fw-semibold mb-1">No question selected</div>
            <div class="small">Click a question on the left or press “Add”.</div>
          </div>

          <form id="questionForm" class="d-none">
            <div class="row g-3">
              <div class="col-12">
                <label class="form-label qb-required">Question Text</label>
                <textarea class="form-control" id="qText" rows="3" placeholder="Enter question..."></textarea>
              </div>

              <div class="col-12 col-md-4">
                <label class="form-label qb-required">Question Type</label>
                <select class="form-select" id="qType">
                  @foreach ($questionTypes as $type)
                    <option value="{{ $type['value'] }}">{{ $type['label'] }}</option>
                  @endforeach
                </select>
              </div>

              <div class="col-12 col-md-4">
                <label class="form-label">Points</label>
                <input class="form-control" id="qPoints" type="number" min="0" step="1" value="1"/>
              </div>

              <div class="col-12 col-md-4">
                <label class="form-label">Required</label>
                <select class="form-select" id="qRequired">
                  <option value="true">Yes</option>
                  <option value="false">No</option>
                </select>
              </div>

              <!-- Options area -->
              <div class="col-12" id="optionsBlock">
                <div class="d-flex align-items-center justify-content-between">
                  <label class="form-label qb-required mb-0">Answer Options</label>
                  <button type="button" class="btn btn-sm btn-outline-success" id="btnAddOption">
                    <i class="bi bi-plus-lg"></i> Add Option
                  </button>
                </div>
                <div class="qb-muted small mb-2" id="optionsHint"></div>

                <div class="d-grid gap-2" id="optionsList"></div>
              </div>

              <!-- Short answer block -->
              <div class="col-12 d-none" id="shortAnswerBlock">
                <label class="form-label qb-required">Correct Answer</label>
                <input class="form-control" id="shortAnswer" placeholder="Expected answer (exact match or keywords)"/>
                <div class="qb-muted small mt-1">
                  You can implement exact match / keyword match in your grading logic later.
                </div>
              </div>

              <!-- Explanation -->
              <div class="col-12">
                <label class="form-label">Explanation (Optional)</label>
                <textarea class="form-control" id="qExplanation" rows="2"
                          placeholder="Shown after answering (if you want)."></textarea>
              </div>
            </div>

            <div class="qb-sticky-actions d-flex flex-wrap justify-content-end gap-2">
              <button type="button" class="btn btn-outline-secondary" id="btnResetQuestion">
                Reset Question
              </button>
              <button type="button" class="btn btn-primary" id="btnApplyQuestion">
                Apply Changes
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- JSON Preview Modal -->
    <div class="modal fade" id="jsonModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Quiz JSON</h5>
            <button class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="qb-code" id="jsonPreview"></div>
          </div>
          <div class="modal-footer">
            <button class="btn btn-outline-secondary" id="btnCopyJson">
              <i class="bi bi-clipboard"></i> Copy
            </button>
            <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Toast -->
    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1080">
      <div id="appToast" class="toast align-items-center text-bg-dark border-0" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
          <div class="toast-body" id="toastMsg">Saved</div>
          <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
      </div>
    </div>
  </div>
{{-- </form> --}}

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>


  <script>
/*****************************************************************
 * QUIZ BUILDER - External JS (Laravel-friendly)
 * - Load from window.QUIZ_PAYLOAD (Laravel)
 * - Save = create new + update existing + delete removed (1 request)
 *****************************************************************/

// ======== Config ========
const API_SAVE_URL = (quizId) => `/api/quizzes/${encodeURIComponent(quizId)}`; // <-- change to your route

// ===== Sample class list (replace with fetch from backend later) =====
// const CLASS_LIST = [
//   { classId: "CLS-1001", className: "Hao Lin Class A" },
//   { classId: "CLS-1002", className: "Hao Lin Class B" },
//   { classId: "CLS-1003", className: "English Class C" },
//   { classId: "CLS-1004", className: "Math Class D" }
// ];

const CLASS_LIST = @json($classes);

let questionTypeMap = {};

// Convert array to map
questionTypes.forEach(qt => {
    questionTypeMap[qt.value] = qt.label;
});

// ======== State (UI shape) ========
const Initialstate = {
  // UI quiz shape (what your builder uses)
  quiz: {
    quizId: null,         // backend quiz id
    classId: "",
    className: "",
    type: "final",        // "final" | "kc"
    title: "",
    passScore: 80,
    status: "active",     // "active" | "inactive"
    instructions: "",
    questions: []
  },

  // Snapshot of original backend ids for delete detection
  original: {
    questionIds: new Set(),
    optionIdsByQuestion: new Map() // qid -> Set(optionIds)
  },

  selectedQuestionId: null,
  questionDraft: null
};

let state = {
  // UI quiz shape (what your builder uses)
  quiz: {
    quizId: null,         // backend quiz id
    classId: "",
    className: "",
    type: "final",        // "final" | "kc"
    title: "",
    passScore: 80,
    status: "active",     // "active" | "inactive"
    instructions: "",
    questions: []
  },

  // Snapshot of original backend ids for delete detection
  original: {
    questionIds: new Set(),
    optionIdsByQuestion: new Map() // qid -> Set(optionIds)
  },

  selectedQuestionId: null,
  questionDraft: null
};

// ======== DOM ========
const el = {
  classNameInput: document.getElementById("classNameInput"),
  classDropdown: document.getElementById("classDropdown"),
  classId: document.getElementById("classId"),

  quizType: document.getElementById("quizType"),
  quizTitle: document.getElementById("quizTitle"),
  passScore: document.getElementById("passScore"),
  status: document.getElementById("status"),
  instructions: document.getElementById("instructions"),

  questionList: document.getElementById("questionList"),
  btnAddQuestion: document.getElementById("btnAddQuestion"),
  questionSearch: document.getElementById("questionSearch"),
  btnClearSearch: document.getElementById("btnClearSearch"),

  emptyState: document.getElementById("emptyState"),
  questionForm: document.getElementById("questionForm"),
  btnMoveUp: document.getElementById("btnMoveUp"),
  btnMoveDown: document.getElementById("btnMoveDown"),
  btnDuplicate: document.getElementById("btnDuplicate"),
  btnDelete: document.getElementById("btnDelete"),

  qText: document.getElementById("qText"),
  qType: document.getElementById("qType"),
  qPoints: document.getElementById("qPoints"),
  qRequired: document.getElementById("qRequired"),
  qExplanation: document.getElementById("qExplanation"),

  optionsBlock: document.getElementById("optionsBlock"),
  optionsHint: document.getElementById("optionsHint"),
  optionsList: document.getElementById("optionsList"),
  btnAddOption: document.getElementById("btnAddOption"),

  shortAnswerBlock: document.getElementById("shortAnswerBlock"),
  shortAnswer: document.getElementById("shortAnswer"),

  btnResetQuestion: document.getElementById("btnResetQuestion"),
  btnApplyQuestion: document.getElementById("btnApplyQuestion"),

  btnSaveAll: document.getElementById("btnSaveAll"),
  btnPreviewJson: document.getElementById("btnPreviewJson"),

  jsonModal: document.getElementById("jsonModal"),
  jsonPreview: document.getElementById("jsonPreview"),
  btnCopyJson: document.getElementById("btnCopyJson"),

  toast: document.getElementById("appToast"),
  toastMsg: document.getElementById("toastMsg")
};

const bs = {
  jsonModal: new bootstrap.Modal(el.jsonModal),
  toast: new bootstrap.Toast(el.toast, { delay: 2200 })
};

// ======== Helpers ========
function uid(prefix = "tmp") {
  return `${prefix}_${Math.random().toString(16).slice(2)}_${Date.now().toString(16)}`;
}

function deepClone(obj) {
  return JSON.parse(JSON.stringify(obj));
}

function toast(msg) {
  el.toastMsg.textContent = msg;
  bs.toast.show();
}

function escapeHtml(str = "") {
  return String(str).replace(/[&<>"']/g, s => ({
    "&":"&amp;","<":"&lt;",">":"&gt;",'"':"&quot;","'":"&#039;"
  }[s]));
}

function getCsrfToken() {
  const meta = document.querySelector('meta[name="csrf-token"]');
  return meta ? meta.getAttribute("content") : "";
}

function getQuestionById(id) {
  return state.quiz.questions.find(q => q.id === id) || null;
}

function selectedIndex() {
  return state.quiz.questions.findIndex(q => q.id === state.selectedQuestionId);
}

// ======== Class searchable dropdown ========
function renderClassDropdown(keyword = "") {
  const k = keyword.trim().toLowerCase();

  const filtered = CLASS_LIST.filter(c =>
    c.className.toLowerCase().includes(k) ||
    c.classId.toLowerCase().includes(k)
  ).slice(0, 30);

  el.classDropdown.innerHTML = filtered.length
    ? filtered.map(c => `
      <li>
        <button type="button" class="dropdown-item"
                data-class-id="${escapeHtml(c.classId)}"
                data-class-name="${escapeHtml(c.className)}">
          <div class="fw-semibold">${escapeHtml(c.className)}</div>
          <div class="small text-muted">${escapeHtml(c.classId)}</div>
        </button>
      </li>
    `).join("")
    : `<li><span class="dropdown-item-text text-muted">No matching class</span></li>`;

  [...el.classDropdown.querySelectorAll("[data-class-id]")].forEach(btn => {
    btn.addEventListener("click", (e) => {
      getQuiz(e);
      el.classId.value = btn.getAttribute("data-class-id");
      el.classNameInput.value = btn.getAttribute("data-class-name");
    });
  });
}

function syncPassScoreByQuizType() {
  const isKC = el.quizType.value === "kc";
  el.passScore.disabled = isKC;

  if (isKC) {
    el.passScore.value = "";
  } else {
    if (el.passScore.value === "") el.passScore.value = "80";
  }
}

// ======== Render list ========
function typeToLabel(type) {
  // switch (type) {
  //   case "mcq_single": return "MCQ (Single)";
  //   case "mcq_multi": return "Multiple Correct";
  //   case "true_false": return "True/False";
  //   case "short_answer": return "Short Answer";
  //   default: return type || "Unknown";
  // }

  return questionTypeMap[type] ?? type ?? "Unknown";

  
 
}

function questionHasCorrectAnswer(q) {
  if (!q) return false;
  if (q.type === "short_answer") return !!(q.correctAnswer && q.correctAnswer.trim());
  if (q.type === "true_false") return typeof q.correctIndex === "number";
  if (q.type === "single") return typeof q.correctIndex === "number";
  if (q.type === "multiple") return Array.isArray(q.correctIndexes) && q.correctIndexes.length > 0;
  return false;
}

function renderQuestionList() {
  const keyword = (el.questionSearch.value || "").trim().toLowerCase();

  const items = state.quiz.questions
    .map((q, idx) => ({ q, idx }))
    .filter(({ q }) => {
      if (!keyword) return true;
      const t = (q.text || "").toLowerCase();
      return t.includes(keyword) || (q.type || "").includes(keyword);
    });

  el.questionList.innerHTML = items.map(({ q, idx }) => {
    const active = q.id === state.selectedQuestionId ? "active" : "";
    const title = q.text?.trim() ? q.text.trim() : "(Untitled question)";
    const typeLabel = typeToLabel(q.type);
    const hasCorrect = questionHasCorrectAnswer(q);

    return `
      <button type="button"
              class="list-group-item list-group-item-action ${active}"
              data-id="${escapeHtml(String(q.id))}">
        <div class="d-flex justify-content-between align-items-start gap-2">
          <div class="text-start">
            <div class="fw-semibold">Q${idx + 1}. ${escapeHtml(title).slice(0, 60)}${title.length > 60 ? "…" : ""}</div>
            <div class="small qb-muted">${escapeHtml(typeLabel)} • ${q.points ?? 1} pt</div>
          </div>
          <div class="d-flex flex-column align-items-end gap-1">
            <span class="badge qb-badge">${hasCorrect ? "OK" : "Missing answer"}</span>
          </div>
        </div>
      </button>
    `;
  }).join("");

  [...el.questionList.querySelectorAll("[data-id]")].forEach(btn => {
    btn.addEventListener("click", () => selectQuestion(btn.getAttribute("data-id")));
  });
}

// ======== Editor lifecycle ========
function showEditor(show) {
  el.emptyState.classList.toggle("d-none", show);
  el.questionForm.classList.toggle("d-none", !show);
}

function updateMoveButtons() {
  const idx = selectedIndex();
  el.btnMoveUp.disabled = (idx <= 0);
  el.btnMoveDown.disabled = (idx < 0 || idx >= state.quiz.questions.length - 1);
  el.btnDuplicate.disabled = (idx < 0);
  el.btnDelete.disabled = (idx < 0);
}

function hydrateEditorFromQuestion(q) {
  el.qText.value = q.text || "";
  el.qType.value = q.type || "single";
  el.qPoints.value = q.points ?? 1;
  el.qRequired.value = String(!!q.required);
  el.qExplanation.value = q.explanation || "";

  renderAnswerUIForType(q.type, q);
  updateMoveButtons();
}

function gatherEditorToQuestionObject() {
  const q = getQuestionById(state.selectedQuestionId);
  if (!q) return;

  q.text = el.qText.value || "";
  q.type = el.qType.value;
  q.points = parseInt(el.qPoints.value || "1", 10);
  q.required = (el.qRequired.value === "true");
  q.explanation = el.qExplanation.value || "";

  if (q.type === "short_answer") {
    q.correctAnswer = el.shortAnswer.value || "";
    q.options = [];
    q.correctIndex = null;
    q.correctIndexes = [];
    return;
  }

  // options
  const rows = [...el.optionsList.querySelectorAll("[data-opt-id]")];
  const options = rows.map(row => {
    const id = row.getAttribute("data-opt-id");
    const txt = row.querySelector("input[data-role='opt-text']")?.value ?? "";
    return { id, text: txt };
  });

  q.options = options;

  if (q.type === "single" || q.type === "true_false") {
    const sel = el.optionsList.querySelector("input[name='correctSingle']:checked");
    q.correctIndex = sel ? parseInt(sel.value, 10) : null;
    q.correctIndexes = [];
  } else if (q.type === "multiple") {
    const checked = [...el.optionsList.querySelectorAll("input[name='correctMulti']:checked")]
      .map(x => parseInt(x.value, 10));
    q.correctIndexes = checked;
    q.correctIndex = null;
  } else {
    q.correctIndex = null;
    q.correctIndexes = [];
  }

  q.correctAnswer = "";
}

function selectQuestion(id) {
  const q = getQuestionById(id);
  if (!q) return;

  state.selectedQuestionId = id;
  state.questionDraft = deepClone(q);
  hydrateEditorFromQuestion(q);
  showEditor(true);
  renderQuestionList();
}

// ======== Answer UI ========
function renderAnswerUIForType(type, q) {
  const isShort = type === "short_answer";
  const isTF = type === "true_false";

  el.optionsBlock.classList.toggle("d-none", isShort);
  el.shortAnswerBlock.classList.toggle("d-none", !isShort);

  if (isShort) {
    el.shortAnswer.value = q.correctAnswer || "";
    return;
  }

  // True/False forces 2 options
  if (isTF) {
    q.options = [
      { id: q.options?.[0]?.id || uid("OPT"), text: "True" },
      { id: q.options?.[1]?.id || uid("OPT"), text: "False" }
    ];
  } else {
    q.options = Array.isArray(q.options) ? q.options : [];
    if (q.options.length < 2) {
      while (q.options.length < 2) q.options.push({ id: uid("OPT"), text: "" });
    }
  }

  if (type === "single" || type === "true_false") {
    el.optionsHint.textContent = "Select exactly one correct option.";
  } else {
    el.optionsHint.textContent = "Select one or more correct options.";
  }

  el.optionsList.innerHTML = q.options.map((opt, idx) => {
    const text = opt.text ?? opt.option_text ?? "";
    const optId = String(opt.id);

    const isSingle = (type === "single" || type === "true_false");
    const checkedSingle = isSingle && (q.correctIndex === idx) ? "checked" : "";
    const checkedMulti = (!isSingle && Array.isArray(q.correctIndexes) && q.correctIndexes.includes(idx)) ? "checked" : "";

    const selector = isSingle
      ? `<input class="form-check-input me-2" type="radio" name="correctSingle" value="${idx}" ${checkedSingle}>`
      : `<input class="form-check-input me-2" type="checkbox" name="correctMulti" value="${idx}" ${checkedMulti}>`;

    const disableText = isTF ? "disabled" : "";

    return `
      <div class="qb-option-row d-flex align-items-center gap-2" data-opt-id="${escapeHtml(optId)}">
        <div class="form-check m-0">
          ${selector}
        </div>

        <input class="form-control" data-role="opt-text" value="${escapeHtml(text)}" ${disableText} placeholder="Option text">

        ${isTF ? "" : `
          <button type="button" class="btn btn-sm btn-outline-danger" data-role="opt-del" title="Remove option">
            <i class="bi bi-trash"></i>
          </button>
        `}
      </div>
    `;
  }).join("");

  // bind delete option
  [...el.optionsList.querySelectorAll("[data-role='opt-del']")].forEach(btn => {
    btn.addEventListener("click", () => {
      const row = btn.closest("[data-opt-id]");
      if (!row) return;

      gatherEditorToQuestionObject();

      const q2 = getQuestionById(state.selectedQuestionId);
      if (!q2) return;

      const optId = row.getAttribute("data-opt-id");
      q2.options = q2.options.filter(o => String(o.id) !== String(optId));

      // normalize correct selections after removal
      q2.correctIndex = null;
      q2.correctIndexes = [];

      renderAnswerUIForType(q2.type, q2);
      toast("Option removed");
    });
  });
}

// ======== Actions ========
function addQuestion() {
  // save current edits into state
  if (state.selectedQuestionId) gatherEditorToQuestionObject();

  const q = {
    id: uid("tmpQ"),      // temp id for new question
    _backendId: null,
    text: "",
    type: "single",
    points: 1,
    required: true,
    explanation: "",
    options: [
      { id: uid("tmpOPT"), option_text: "" },
      { id: uid("tmpOPT"), option_text: "" }
    ],
    correctIndex: null,
    correctIndexes: [],
    correctAnswer: ""
  };

  state.quiz.questions.push(q);
  state.selectedQuestionId = q.id;
  state.questionDraft = deepClone(q);

  renderQuestionList();
  hydrateEditorFromQuestion(q);
  showEditor(true);
  toast("Question added");
}

function deleteSelectedQuestion() {
  const idx = selectedIndex();
  if (idx < 0) return;

  state.quiz.questions.splice(idx, 1);

  state.selectedQuestionId = null;
  state.questionDraft = null;

  renderQuestionList();
  showEditor(false);
  updateMoveButtons();
  toast("Question removed");
}

function duplicateSelectedQuestion() {
  const q = getQuestionById(state.selectedQuestionId);
  if (!q) return;

  gatherEditorToQuestionObject();

  const copy = deepClone(q);
  copy.id = uid("tmpQ");
  copy.options = (copy.options || []).map(o => ({ id: uid("tmpOPT"), text: o.text || "" }));

  copy.correctIndex = null;
  copy.correctIndexes = [];
  copy.correctAnswer = "";

  const idx = selectedIndex();
  state.quiz.questions.splice(idx + 1, 0, copy);

  renderQuestionList();
  toast("Duplicated");
}

function moveSelectedQuestion(delta) {
  const idx = selectedIndex();
  if (idx < 0) return;

  const newIdx = idx + delta;
  if (newIdx < 0 || newIdx >= state.quiz.questions.length) return;

  const [item] = state.quiz.questions.splice(idx, 1);
  state.quiz.questions.splice(newIdx, 0, item);

  renderQuestionList();
  updateMoveButtons();
}

function onTypeChange() {
  const q = getQuestionById(state.selectedQuestionId);
  if (!q) return;

  q.type = el.qType.value;

  if (q.type === "short_answer") {
    q.correctAnswer = q.correctAnswer || "";
    q.options = [];
    q.correctIndex = null;
    q.correctIndexes = [];
  } else {
    q.correctAnswer = "";
    q.correctIndex = (typeof q.correctIndex === "number") ? q.correctIndex : null;
    q.correctIndexes = Array.isArray(q.correctIndexes) ? q.correctIndexes : [];
    q.options = Array.isArray(q.options) ? q.options : [];
    if (q.type === "true_false") {
      q.options = [
        { id: q.options?.[0]?.id || uid("OPT"), text: "True" },
        { id: q.options?.[1]?.id || uid("OPT"), text: "False" }
      ];
    } else if (q.options.length < 2) {
      while (q.options.length < 2) q.options.push({ id: uid("OPT"), option_text: "" });
    }
  }

  renderAnswerUIForType(q.type, q);
}

function addOption() {
  const q = getQuestionById(state.selectedQuestionId);
  if (!q) return;
  if (el.qType.value === "true_false") return;

  gatherEditorToQuestionObject();

  q.options.push({ id: uid("tmpOPT"), option_text: "" });
  renderAnswerUIForType(q.type, q);
  toast("Option added");
}

function resetQuestionEditor() {
  const q = getQuestionById(state.selectedQuestionId);
  if (!q || !state.questionDraft) return;

  const idx = selectedIndex();
  state.quiz.questions[idx] = deepClone(state.questionDraft);

  const updated = state.quiz.questions[idx];
  hydrateEditorFromQuestion(updated);
  renderQuestionList();
  toast("Question reset");
}

function validateSelectedQuestion() {
  const q = getQuestionById(state.selectedQuestionId);
  return validateQuestionObject(q);
}

function validateQuestionObject(q) {
  if (!q) return { ok: false, msg: "No question selected." };

  if (!q.text || !q.text.trim()) return { ok: false, msg: "Question text is required." };

  if (q.type === "short_answer") {
    if (!q.correctAnswer || !q.correctAnswer.trim()) {
      return { ok: false, msg: "Correct answer is required." };
    }
    return { ok: true };
  }

  if (!q.options || q.options.length < 2) return { ok: false, msg: "At least 2 options required." };
  if (q.options.some(o => !o.text || !o.text.trim())) return { ok: false, msg: "Option text missing." };

  // Keep your current schema: type + correctIndex/correctIndexes
  if (q.type === "single" || q.type === "true_false") {
    if (typeof q.correctIndex !== "number") return { ok: false, msg: "Select a correct option." };
  }

  if (q.type === "multiple") {
    if (!Array.isArray(q.correctIndexes) || q.correctIndexes.length === 0) {
      return { ok: false, msg: "Select at least one correct option." };
    }
  }

  return { ok: true };
}

function applyQuestionChanges() {
  const v = validateSelectedQuestion();
  if (!v.ok) { alert(v.msg); return; }

  const q = getQuestionById(state.selectedQuestionId);
  state.questionDraft = deepClone(q);

  renderQuestionList();
  toast("Applied");
}

// ======== Quiz-level ========
function gatherQuizMeta() {
  state.quiz.classId = el.classId.value.trim();
  state.quiz.className = el.classNameInput.value.trim();

  state.quiz.type = el.quizType.value; // "final" or "kc"
  state.quiz.title = el.quizTitle.value.trim();

  const isKC = el.quizType.value === "kc";
  state.quiz.passScore = isKC ? null : parseInt(el.passScore.value || "0", 10);

  state.quiz.status = el.status.value;
  state.quiz.instructions = el.instructions.value || "";
}

function validateQuizAll() {
  gatherQuizMeta();

  if (!state.quiz.classId) return { ok: false, msg: "Please select a class." };
  if (!state.quiz.title) return { ok: false, msg: "Quiz title is required." };

  if (state.quiz.type !== "kc") {
    if (state.quiz.passScore === null || Number.isNaN(state.quiz.passScore)) {
      return { ok: false, msg: "Pass score is required for Final Quiz." };
    }
    if (state.quiz.passScore < 0 || state.quiz.passScore > 100) {
      return { ok: false, msg: "Pass score must be 0–100." };
    }
  }

  if (!state.quiz.questions.length) return { ok: false, msg: "Please add at least one question." };

  // ✅ IMPORTANT:
  // Sync editor -> state ONLY ONCE for the currently opened question
  // so the latest edits are included, but no other questions get overwritten.
  const currentSelectedId = state.selectedQuestionId;
  if (currentSelectedId) {
    gatherEditorToQuestionObject();
  }

  // ✅ Validate questions WITHOUT changing selectedQuestionId
  for (let i = 0; i < state.quiz.questions.length; i++) {
    const q = state.quiz.questions[i];
    const v = validateQuestionObject(q);
    if (!v.ok) return { ok: false, msg: `Question ${i + 1}: ${v.msg}` };
  }

  // restore selection (optional but nice)
  state.selectedQuestionId = currentSelectedId;

  return { ok: true };
}

function previewJson() {
  gatherQuizMeta();
  if (state.selectedQuestionId) gatherEditorToQuestionObject();

  el.jsonPreview.textContent = JSON.stringify(state.quiz, null, 2);
  bs.jsonModal.show();
}

function copyJson() {
  const txt = el.jsonPreview.textContent || "";
  navigator.clipboard.writeText(txt).then(() => toast("Copied JSON"));
}

// ======== Laravel mapping (IMPORTANT) ========
function normalizeFromLaravel(raw) {
  // raw is what Laravel returns ($quiz).
  // Based on your sample: quizType, pass_score, is_active, questions[], questiontype, question, options[]
  const quizId = raw.id ?? raw.quiz_id ?? null;

  const ui = {
    quizId,
    classId: raw.class_id ?? raw.classId ?? "",
    className: raw.class_name ?? raw.className ?? "",
    type: (raw.quizType === "FinalQuiz" || raw.type === "final") ? "final" : "kc",
    title: raw.title ?? "",
    passScore: raw.pass_score != null ? parseInt(String(raw.pass_score), 10) : (raw.passScore ?? 80),
    status: (raw.is_active === 1 || raw.status === "active") ? "active" : "inactive",
    instructions: raw.description ?? raw.instructions ?? "",
    questions: []
  };

  const questions = Array.isArray(raw.questions) ? raw.questions : [];
  ui.questions = questions.map(q => {
    const qid = q.id ?? uid("tmpQ");

    // Map backend questiontype -> UI q.type
    // backend: "single" | "multi" | "truefalse" | "short"
    // UI: "mcq_single" | "mcq_multi" | "true_false" | "short_answer"
    const backendType = (q.questiontype ?? "").toLowerCase();
    let uiType = "single";
    if (backendType === "single") uiType = "single";
    else if (backendType === "multiple") uiType = "multiple";
    else if (backendType === "truefalse" || backendType === "true_false") uiType = "true_false";
    else if (backendType === "shortanswer" || backendType === "short_answer") uiType = "short_answer";

    const opts = Array.isArray(q.options) ? q.options : [];

    let correctIndex = null;
    let correctIndexes = [];

    opts.forEach((o, index) => {
      if (o.is_correct === 1 || o.is_correct === true) {
        correctIndexes.push(index);
        if (correctIndex === null) correctIndex = index;
      }
    });


    return {
      id: String(qid), // store as string for consistent DOM usage
      _backendId: qid, // keep numeric id for saving
      text: q.question ?? q.text ?? "",
      type: uiType,
      points: q.points ?? 1,
      required: (q.is_required ?? q.required ?? 1) ? true : false,
      explanation: q.explanation ?? "",

      options: opts.map(o => ({
        id: String(o.id ?? uid("tmpOPT")),
        _backendId: o.id ?? null,
        option_text: o.option ?? o.option_text ?? ""
      })),

      // correct answer fields (if your backend has them)
      correctIndex,
      correctIndexes,
      correctAnswer: q.correct_answer ?? ""
    };
  });

  return ui;
}

function snapshotOriginalIdsFromLaravel(raw) {
  state.original.questionIds = new Set();
  state.original.optionIdsByQuestion = new Map();

  const questions = Array.isArray(raw.questions) ? raw.questions : [];
  questions.forEach(q => {
    if (q.id != null) state.original.questionIds.add(String(q.id));

    const set = new Set();
    const opts = Array.isArray(q.options) ? q.options : [];
    opts.forEach(o => {
      if (o.id != null) set.add(String(o.id));
    });

    if (q.id != null) state.original.optionIdsByQuestion.set(String(q.id), set);
  });
}

// Build payload for Laravel controller to create/update/delete in one request
function buildLaravelSavePayload() {
  gatherQuizMeta();
  //if (state.selectedQuestionId) gatherEditorToQuestionObject();

  // figure out deleted question ids (existing only)
  // const currentExistingQIds = new Set(
  //   state.quiz.questions
  //     .map(q => q._backendId)
  //     .filter(id => id != null)
  //     .map(id => String(id))
  // );

  //const deletedQuestionIds = [...state.original.questionIds].filter(oldId => !currentExistingQIds.has(oldId));

  // Build questions payload
  const questionsPayload = state.quiz.questions.map((q, idx) => {
    const backendQuestionId = q._backendId ?? null;

    // deleted options detection per existing question
    let deletedOptionIds = [];
    if (backendQuestionId != null) {
      const oldSet = state.original.optionIdsByQuestion.get(String(backendQuestionId)) || new Set();
      const curSet = new Set(
        (q.options || [])
          .map(o => o._backendId)
          .filter(id => id != null)
          .map(id => String(id))
      );
      deletedOptionIds = [...oldSet].filter(oldOptId => !curSet.has(oldOptId));
    }

    // map UI type -> backend questiontype
    let questiontype = "single";
    if (q.type === "single") questiontype = "single";
    else if (q.type === "multiple") questiontype = "multiple";
    else if (q.type === "true_false") questiontype = "true_false";
    else if (q.type === "short_answer") questiontype = "shortanswer";

    // return {
    //   id: backendQuestionId,         // null => create new
    //   sequence_no: idx + 1,
    //   question: q.text,
    //   questiontype,
    //   points: q.points ?? 1,
    //   is_active: 1,
    //   explanation: q.explanation ?? "",

    //   // correct fields (only send what you actually store)
    //   correct_index: q.correctIndex ?? null,
    //   correct_indexes: q.correctIndexes ?? [],
    //   correct_answer: q.correctAnswer ?? "",

    //   options: (q.options || []).map((o, optIdx) => ({
    //     id: o._backendId ?? null,     // null => create new option
    //     option: o.text,
    //     sequence_no: optIdx + 1,
    //     is_active: 1
    //   })),

    //   deleted_option_ids: deletedOptionIds
    // };

    let ppp = {
      id: state.quiz._backendId ?? null,
      question: q.text,
      questiontype,
      points: q.points ?? 1,
      // ... quiz meta ...
      questions: state.quiz.questions.map((q, idx) => {
        return {
          // ONLY send the database ID. If it's null, Laravel creates a new one.
          id: q._backendId ?? null, 
          sequence_no: idx + 1,
          question: q.text,
          questiontype: q.type,
          points: q.points,
          explanation: q.explanation,
          correct_index: q.correctIndex,
          correct_indexes: q.correctIndexes || [],
          options: (q.options || []).map((o, optIdx) => ({
            id: o._backendId ?? null,
            option: o.text || o.option_text, // Handle both key names
            sequence_no: optIdx + 1
          })),
          deleted_option_ids: q.deleted_option_ids || []
        };
      }),
      deleted_question_ids: state.deleted_question_ids || []
    };

    console.log(ppp);

    return {
      id: state.quiz._backendId ?? null,
      question: q.text,
      questiontype,
      points: q.points ?? 1,
      // ... quiz meta ...
      questions: state.quiz.questions.map((q, idx) => {
        return {
          // ONLY send the database ID. If it's null, Laravel creates a new one.
          id: q._backendId ?? null, 
          sequence_no: idx + 1,
          question: q.text,
          questiontype: q.type,
          points: q.points,
          explanation: q.explanation,
          correct_index: q.correctIndex,
          correct_indexes: q.correctIndexes || [],
          options: (q.options || []).map((o, optIdx) => ({
            id: o._backendId ?? null,
            option: o.text || o.option_text, // Handle both key names
            sequence_no: optIdx + 1
          })),
          deleted_option_ids: q.deleted_option_ids || []
        };
      }),
      deleted_question_ids: state.deleted_question_ids || []
    };
  });

  // Quiz-level payload (adjust keys to match your controller)
  return {
    id: state.quiz.quizId,
    class_id: state.quiz.classId,
    title: state.quiz.title,
    description: state.quiz.instructions,
    quizType: state.quiz.type === "final" ? "FinalQuiz" : "KnowledgeCheck",
    pass_score: state.quiz.passScore,
    is_active: state.quiz.status === "active" ? 1 : 0,

    questions: questionsPayload,
    deleted_question_ids: deletedQuestionIds
  };
}

// ======== Backend save ========
async function saveAll() {
  gatherQuizMeta()
  const v = validateQuizAll();
  if (!v.ok) { alert(v.msg); return; }

  const classId = document.getElementById("classId").value;
  const className = document.getElementById("classNameInput").value;
  console.log('save' + state); 
  console.log(state);

  //const payload = buildLaravelSavePayload();
  state.quiz.type = "final" ? "FinalQuiz" : "KnowledgeCheck";
  const payload = state.quiz;
  const csrfToken = document.head.querySelector('meta[name="csrf-token"]').content;
  console.log(payload);
  try {
    //const res = await fetch(API_SAVE_URL(payload.id || "new"), {
    const res = await fetch('/admin/SaveQuiz', {
      method: "put", // one request for create/update/delete
      headers: {
        "Content-Type": "application/json",
        "Accept": "application/json",
        "X-CSRF-TOKEN": csrfToken
      },
      body: JSON.stringify(payload)
    });

    if (!res.ok) {
      const txt = await res.text();
      throw new Error(`Save failed: ${res.status}\n${txt}`);
    }

    const saved = await res.json();

    console.log(saved);
    // refresh UI + snapshot after save (so deletions work next time)
    snapshotOriginalIdsFromLaravel(saved.data);
    state.quiz = normalizeFromLaravel(saved.data);

    hydrateMeta();
    renderQuestionList();
    showEditor(false);

    toast("Saved to backend");
  } catch (e) {
    console.error(e);
    alert(e.message || "Save failed.");
  }

    el.classId.value = classId;
    el.classNameInput.value = className;
}

/**
 * Triggered by the 'click' event listener on #classDropdown
 */
async function getQuiz(e) {
    // e is the Event object passed automatically by the listener
    const btn = e.target.closest('.dropdown-item');
    
    // Safety check: only proceed if a dropdown item (or its children) was clicked
    if (!btn) return;

    // Retrieve data from attributes
    const classId = btn.getAttribute('data-class-id');
    const className = btn.getAttribute('data-class-name');

    console.log(`Fetching data for: ${className} (ID: ${classId})`);

    try {
        // Change this URL to your actual Laravel endpoint
        const response = await fetch(`/admin/quiz-builder/${classId}`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const data = await response.json();
        
        // Success logic: process your Laravel data here
        console.log('Data retrieved:', data);

        if(data.quiz){
          snapshotOriginalIdsFromLaravel(data.quiz);
          state.quiz = normalizeFromLaravel(data.quiz);
        } else {
          state = Initialstate;
        }

        hydrateMeta();
        renderQuestionList();
        showEditor(false);

        el.classId.value = classId;
        el.classNameInput.value = className;

    } catch (error) {
        console.error('Fetch error:', error);
    }
}

// ======== Hydrate meta UI ========
function hydrateMeta() {
  el.quizType.value = state.quiz.type || "final";
  el.quizTitle.value = state.quiz.title || "";
  el.passScore.value = state.quiz.passScore ?? 80;
  el.status.value = state.quiz.status || "active";
  el.instructions.value = state.quiz.instructions || "";

  // class fields
  el.classId.value = state.quiz.classId || "";
  el.classNameInput.value = state.quiz.className || "";

  syncPassScoreByQuizType();
}

// ======== Initial load ========
function loadInitial() {
  if (window.QUIZ_PAYLOAD) {
    snapshotOriginalIdsFromLaravel(window.QUIZ_PAYLOAD);
    state.quiz = normalizeFromLaravel(window.QUIZ_PAYLOAD);

    hydrateMeta();
    renderQuestionList();
    showEditor(false);
    return;
  }

  // fallback if no payload
  hydrateMeta();
  renderQuestionList();
  showEditor(false);
}

// ======== Bind events ========
el.quizType.addEventListener("change", syncPassScoreByQuizType);
el.classNameInput.addEventListener("focus", () => renderClassDropdown(el.classNameInput.value));
el.classNameInput.addEventListener("input", () => renderClassDropdown(el.classNameInput.value));

el.btnAddQuestion.addEventListener("click", addQuestion);
el.btnDelete.addEventListener("click", deleteSelectedQuestion);
el.btnDuplicate.addEventListener("click", duplicateSelectedQuestion);
el.btnMoveUp.addEventListener("click", () => moveSelectedQuestion(-1));
el.btnMoveDown.addEventListener("click", () => moveSelectedQuestion(1));

el.qType.addEventListener("change", onTypeChange);
el.btnAddOption.addEventListener("click", addOption);
el.btnResetQuestion.addEventListener("click", resetQuestionEditor);
el.btnApplyQuestion.addEventListener("click", applyQuestionChanges);

el.btnSaveAll.addEventListener("click", saveAll);
el.btnPreviewJson.addEventListener("click", previewJson);
el.btnCopyJson.addEventListener("click", copyJson);

el.questionSearch.addEventListener("input", renderQuestionList);
el.btnClearSearch.addEventListener("click", () => {
  el.questionSearch.value = "";
  renderQuestionList();
});

el.qText.addEventListener("input", () => renderQuestionList());

// Boot
loadInitial();

  </script>
</body>
</html>