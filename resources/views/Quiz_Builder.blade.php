<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1"/>
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
window.QUIZ_PAYLOAD = {{ Js::from($quiz) }};
console.log({{ Js::from($quiz) }});
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
<button class="btn btn-outline-secondary" id="btnPreviewJson" type="button">
<i class="bi bi-braces"></i> Preview JSON
</button>
<button class="btn btn-primary" id="btnSaveAll" type="button">
<i class="bi bi-save"></i> Save
</button>
</div>
</div>

<div class="row g-3">
<!-- Left: Quiz meta + question list -->
<div class="col-12 col-lg-4">
<div class="qb-card p-3 h-100">
<!-- Class selection -->
<div class="d-flex align-items-end justify-content-between gap-2 mb-2">
<div class="w-100">
<label class="form-label qb-required">Class</label>
<input class="form-control" id="classNameInput" placeholder="Click to pick class" readonly/>
<input type="hidden" id="classId"/>
</div>
<div class="dropdown" id="classDropdownWrap">
<button class="btn btn-outline-secondary dropdown-toggle" id="classDropdown" data-bs-toggle="dropdown" type="button">
<i class="bi bi-chevron-down"></i>
</button>
<ul class="dropdown-menu dropdown-menu-end" style="min-width: 280px; max-height: 320px; overflow:auto;"></ul>
</div>
</div>

<div class="row g-2">
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

<hr class="my-3"/>

<!-- Question actions -->
<div class="d-flex align-items-center justify-content-between gap-2 mb-2">
<div class="fw-semibold">Questions</div>
<button class="btn btn-sm btn-outline-primary" id="btnAddQuestion" type="button">
<i class="bi bi-plus-lg"></i> Add
</button>
</div>

<div class="input-group mb-2">
<span class="input-group-text"><i class="bi bi-search"></i></span>
<input class="form-control" id="questionSearch" placeholder="Search question..."/>
<button class="btn btn-outline-secondary" id="btnClearSearch" type="button"><i class="bi bi-x-lg"></i></button>
</div>

<div class="list-group qb-list" id="questionList"></div>
</div>
</div>

<!-- Right: Editor -->
<div class="col-12 col-lg-8">
<div class="qb-card p-3 h-100">
<div id="emptyState" class="text-center py-5 qb-muted">
<div class="display-6 mb-2">📝</div>
<div>Select a question on the left or add a new one.</div>
</div>

<form id="questionForm" class="d-none">
<!-- Top actions -->
<div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
<div class="d-flex align-items-center gap-2">
<span class="badge qb-badge" id="qIndexBadge">Q1</span>
<span class="qb-muted small" id="qTypeBadge">Multiple Choice (Single)</span>
</div>

<div class="d-flex flex-wrap align-items-center gap-2">
<button type="button" class="btn btn-sm btn-outline-secondary" id="btnMoveUp">
<i class="bi bi-arrow-up"></i>
</button>
<button type="button" class="btn btn-sm btn-outline-secondary" id="btnMoveDown">
<i class="bi bi-arrow-down"></i>
</button>
<button type="button" class="btn btn-sm btn-outline-secondary" id="btnDuplicate">
<i class="bi bi-files"></i> Duplicate
</button>
<button type="button" class="btn btn-sm btn-outline-danger" id="btnDelete">
<i class="bi bi-trash"></i> Delete
</button>
</div>
</div>

<!-- Fields -->
<div class="row g-3">
<div class="col-12">
<label class="form-label qb-required">Question</label>
<textarea class="form-control" id="qText" rows="2" placeholder="Enter the question text..."></textarea>
</div>

<div class="col-12 col-lg-4">
<label class="form-label qb-required">Type</label>
<select class="form-select" id="qType">
<option value="mcq_single">Multiple Choice (Single)</option>
<option value="mcq_multi">Multiple Choice (Multiple)</option>
<option value="true_false">True / False</option>
<option value="short_answer">Short Answer</option>
</select>
</div>

<div class="col-12 col-lg-4">
<label class="form-label">Points</label>
<input class="form-control" id="qPoints" type="number" min="1" value="1"/>
</div>

<div class="col-12 col-lg-4">
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
const CLASS_LIST = [
{ classId: "CLS-1001", className: "Hao Lin Class A" },
{ classId: "CLS-1002", className: "Hao Lin Class B" },
{ classId: "CLS-1003", className: "English Class C" },
{ classId: "CLS-1004", className: "Math Class D" }
];

// ======== State (UI shape) ========
const state = {
// UI quiz shape (what your builder uses)
quiz: {
quizId: null, // backend quiz id
classId: "",
className: "",
type: "final", // "final" | "kc"
title: "",
passScore: 80,
status: "active", // "active" | "inactive"
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
qIndexBadge: document.getElementById("qIndexBadge"),
qTypeBadge: document.getElementById("qTypeBadge"),

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

btnMoveUp: document.getElementById("btnMoveUp"),
btnMoveDown: document.getElementById("btnMoveDown"),
btnDuplicate: document.getElementById("btnDuplicate"),
btnDelete: document.getElementById("btnDelete"),
btnResetQuestion: document.getElementById("btnResetQuestion"),
btnApplyQuestion: document.getElementById("btnApplyQuestion"),

btnPreviewJson: document.getElementById("btnPreviewJson"),
btnSaveAll: document.getElementById("btnSaveAll"),

jsonModal: document.getElementById("jsonModal"),
jsonPreview: document.getElementById("jsonPreview"),
btnCopyJson: document.getElementById("btnCopyJson"),

toast: document.getElementById("appToast"),
toastMsg: document.getElementById("toastMsg"),
};

const bs = {
jsonModal: new bootstrap.Modal(el.jsonModal),
toast: new bootstrap.Toast(el.toast, { delay: 1600 })
};

// ======== Utils ========
function uid(prefix="id") {
// [FN] uid
return `${prefix}-${Math.random().toString(16).slice(2)}-${Date.now().toString(16)}`;
}

function deepClone(obj) {
// [FN] deepClone
return JSON.parse(JSON.stringify(obj));
}

function toast(msg) {
// [FN] toast
el.toastMsg.textContent = msg;
bs.toast.show();
}

function escapeHtml(str) {
// [FN] escapeHtml
return String(str ?? "")
.replaceAll("&", "&amp;")
.replaceAll("<", "&lt;")
.replaceAll(">", "&gt;")
.replaceAll('"', "&quot;")
.replaceAll("'", "&#039;");
}

function getCsrfToken() {
// [FN] getCsrfToken
return document.querySelector("meta[name='csrf-token']")?.getAttribute("content") || "{{ csrf_token() }}";
}

function getQuestionById(id) {
// [FN] getQuestionById
return state.quiz.questions.find(q => String(q.id) === String(id));
}

function selectedIndex() {
// [FN] selectedIndex
return state.quiz.questions.findIndex(q => String(q.id) === String(state.selectedQuestionId));
}

// ======== UI Helpers ========
function renderClassDropdown() {
// [FN] renderClassDropdown
const menu = el.classDropdown.nextElementSibling;
menu.innerHTML = CLASS_LIST.map(c => `
<li>
<button class="dropdown-item" type="button" data-id="${escapeHtml(c.classId)}" data-name="${escapeHtml(c.className)}">
<div class="fw-semibold">${escapeHtml(c.className)}</div>
<div class="small qb-muted">${escapeHtml(c.classId)}</div>
</button>
</li>
`).join("");

[...menu.querySelectorAll("[data-id]")].forEach(btn => {
btn.addEventListener("click", () => {
el.classId.value = btn.getAttribute("data-id");
el.classNameInput.value = btn.getAttribute("data-name");
toast("Class selected");
});
});
}

function syncPassScoreByQuizType() {
// [FN] syncPassScoreByQuizType
const isKC = el.quizType.value === "kc";
el.passScore.disabled = isKC;
if (isKC) el.passScore.value = "";
}

function typeToLabel(type) {
// [FN] typeToLabel
if (type === "mcq_single") return "Multiple Choice (Single)";
if (type === "mcq_multi") return "Multiple Choice (Multiple)";
if (type === "true_false") return "True / False";
if (type === "short_answer") return "Short Answer";
return "Multiple Choice";
}

function questionHasCorrectAnswer(q) {
// [FN] questionHasCorrectAnswer
if (!q) return false;

if (q.type === "short_answer") {
return !!(q.correctAnswer && String(q.correctAnswer).trim());
}

if (q.type === "mcq_single" || q.type === "true_false") {
return !!(q.correctOptionId && String(q.correctOptionId).trim());
}

if (q.type === "mcq_multi") {
return Array.isArray(q.correctOptionIds) && q.correctOptionIds.length > 0;
}

return false;
}

// ======== Question list ========
function renderQuestionList() {
// [FN] renderQuestionList
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
// [FN] showEditor
el.emptyState.classList.toggle("d-none", show);
el.questionForm.classList.toggle("d-none", !show);
}

function updateMoveButtons() {
// [FN] updateMoveButtons
const idx = selectedIndex();
el.btnMoveUp.disabled = (idx <= 0);
el.btnMoveDown.disabled = (idx < 0 || idx >= state.quiz.questions.length - 1);
el.btnDuplicate.disabled = (idx < 0);
el.btnDelete.disabled = (idx < 0);
}

function hydrateEditorFromQuestion(q) {
// [FN] hydrateEditorFromQuestion
el.qText.value = q.text || "";
el.qType.value = q.type || "mcq_single";
el.qPoints.value = q.points ?? 1;
el.qRequired.value = String(!!q.required);
el.qExplanation.value = q.explanation || "";

renderAnswerUIForType(q.type, q);
updateMoveButtons();
}

function gatherEditorToQuestionObject() {
// [FN] gatherEditorToQuestionObject
// Read current editor UI -> write into selected question object in state.
// Correct answer is captured by OPTION ID(s), not index.

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
q.correctOptionId = null;
q.correctOptionIds = [];
return;
}

// options
const rows = [...el.optionsList.querySelectorAll("[data-opt-id]")];
const options = rows.map(row => {
const id = row.getAttribute("data-opt-id");
const txt = row.querySelector("input[data-role='opt-text']")?.value ?? "";

// keep existing backend id if present in current state
const existing = (q.options || []).find(o => String(o.id) === String(id));

return {
id: String(id),
_backendId: existing?._backendId ?? null,
text: txt
};
});

q.options = options;

// Correct selection by option id
if (q.type === "mcq_single" || q.type === "true_false") {
const sel = el.optionsList.querySelector("input[name='correctSingle']:checked");
q.correctOptionId = sel ? String(sel.value) : null;
q.correctOptionIds = [];
} else if (q.type === "mcq_multi") {
const checked = [...el.optionsList.querySelectorAll("input[name='correctMulti']:checked")]
.map(x => String(x.value));
q.correctOptionIds = checked;
q.correctOptionId = null;
} else {
q.correctOptionId = null;
q.correctOptionIds = [];
}
}

function selectQuestion(id) {
// [FN] selectQuestion
// save current editor to state
if (state.selectedQuestionId) gatherEditorToQuestionObject();

state.selectedQuestionId = id;
const q = getQuestionById(id);
if (!q) return;

state.questionDraft = deepClone(q);

const idx = selectedIndex();
el.qIndexBadge.textContent = `Q${idx + 1}`;
el.qTypeBadge.textContent = typeToLabel(q.type);

hydrateEditorFromQuestion(q);
renderQuestionList();
showEditor(true);
}

// ======== Answer UI renderer ========
function renderAnswerUIForType(type, q) {
// [FN] renderAnswerUIForType
// Render options list + correct answer selector without changing the UI layout.
// Correct selection uses OPTION ID (value=opt.id).

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
{ id: q.options?.[0]?.id || uid("OPT"), _backendId: q.options?.[0]?._backendId ?? null, text: "True" },
{ id: q.options?.[1]?.id || uid("OPT"), _backendId: q.options?.[1]?._backendId ?? null, text: "False" }
];
} else {
q.options = Array.isArray(q.options) ? q.options : [];
if (q.options.length < 2) {
while (q.options.length < 2) q.options.push({ id: uid("OPT"), _backendId: null, text: "" });
}
}

if (type === "mcq_single" || type === "true_false") {
el.optionsHint.textContent = "Select exactly one correct option.";
} else {
el.optionsHint.textContent = "Select one or more correct options.";
}

const isSingle = (type === "mcq_single" || type === "true_false");

el.optionsList.innerHTML = q.options.map((opt) => {
const text = opt.text ?? "";
const optId = String(opt.id);

const checkedSingle = isSingle && String(q.correctOptionId || "") === optId ? "checked" : "";
const checkedMulti = (!isSingle && Array.isArray(q.correctOptionIds) && q.correctOptionIds.map(String).includes(optId)) ? "checked" : "";

const selector = isSingle
? `<input class="form-check-input me-2" type="radio" name="correctSingle" value="${escapeHtml(optId)}" ${checkedSingle}>`
: `<input class="form-check-input me-2" type="checkbox" name="correctMulti" value="${escapeHtml(optId)}" ${checkedMulti}>`;

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

// sync editor -> state first
gatherEditorToQuestionObject();

const q2 = getQuestionById(state.selectedQuestionId);
if (!q2) return;

const optId = row.getAttribute("data-opt-id");
q2.options = (q2.options || []).filter(o => String(o.id) !== String(optId));

// normalize correct selections after removal
q2.correctOptionId = null;
q2.correctOptionIds = [];

renderAnswerUIForType(q2.type, q2);
toast("Option removed");
});
});
}

// ======== Actions ========
function addQuestion() {
// [FN] addQuestion
// Add a new question (client-side). New items use temp ids until saved.

// save current edits into state
if (state.selectedQuestionId) gatherEditorToQuestionObject();

const q = {
id: uid("tmpQ"), // temp id for new question (client id)
_backendId: null, // backend id (null until saved)
text: "",
type: "mcq_single",
points: 1,
required: true,
explanation: "",
options: [
{ id: uid("tmpOPT"), _backendId: null, text: "" },
{ id: uid("tmpOPT"), _backendId: null, text: "" }
],
correctOptionId: null,
correctOptionIds: [],
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
// [FN] deleteSelectedQuestion
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
// [FN] duplicateSelectedQuestion
const q = getQuestionById(state.selectedQuestionId);
if (!q) return;

gatherEditorToQuestionObject();

const copy = deepClone(q);
copy.id = uid("tmpQ");
copy._backendId = null;

copy.options = (copy.options || []).map(o => ({
id: uid("tmpOPT"),
_backendId: null,
text: o.text || ""
}));

// do not copy correct answer (force user to re-select)
copy.correctOptionId = null;
copy.correctOptionIds = [];
copy.correctAnswer = "";

const idx = selectedIndex();
state.quiz.questions.splice(idx + 1, 0, copy);

renderQuestionList();
toast("Duplicated");
}

function moveSelectedQuestion(delta) {
// [FN] moveSelectedQuestion
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
// [FN] onTypeChange
const q = getQuestionById(state.selectedQuestionId);
if (!q) return;

q.type = el.qType.value;

if (q.type === "short_answer") {
q.correctAnswer = q.correctAnswer || "";
q.options = [];
q.correctOptionId = null;
q.correctOptionIds = [];
} else {
q.correctAnswer = "";
q.correctOptionId = q.correctOptionId ? String(q.correctOptionId) : null;
q.correctOptionIds = Array.isArray(q.correctOptionIds) ? q.correctOptionIds.map(String) : [];
q.options = Array.isArray(q.options) ? q.options : [];

if (q.type === "true_false") {
q.options = [
{ id: q.options?.[0]?.id || uid("OPT"), _backendId: q.options?.[0]?._backendId ?? null, text: "True" },
{ id: q.options?.[1]?.id || uid("OPT"), _backendId: q.options?.[1]?._backendId ?? null, text: "False" }
];
} else if (q.options.length < 2) {
while (q.options.length < 2) q.options.push({ id: uid("OPT"), _backendId: null, text: "" });
}
}

// changing type can invalidate previous correct selection
q.correctOptionId = null;
q.correctOptionIds = [];

renderAnswerUIForType(q.type, q);
}

function addOption() {
// [FN] addOption
const q = getQuestionById(state.selectedQuestionId);
if (!q) return;
if (el.qType.value === "true_false") return;

gatherEditorToQuestionObject();

q.options = Array.isArray(q.options) ? q.options : [];
q.options.push({ id: uid("tmpOPT"), _backendId: null, text: "" });

renderAnswerUIForType(q.type, q);
toast("Option added");
}

function resetQuestionEditor() {
// [FN] resetQuestionEditor
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
// [FN] validateSelectedQuestion
const q = getQuestionById(state.selectedQuestionId);
if (!q) return { ok: false, msg: "No question selected." };

// Ensure latest editor changes are applied to q before validating
gatherEditorToQuestionObject();

if (!q.text || !q.text.trim()) return { ok: false, msg: "Question text is required." };

if (q.type === "short_answer") {
if (!q.correctAnswer || !q.correctAnswer.trim()) return { ok: false, msg: "Correct answer is required." };
return { ok: true };
}

if (!q.options || q.options.length < 2) return { ok: false, msg: "At least 2 options required." };
if (q.options.some(o => !o.text || !o.text.trim())) return { ok: false, msg: "Option text missing." };

if (q.type === "mcq_single" || q.type === "true_false") {
if (!q.correctOptionId) return { ok: false, msg: "Select a correct option." };
}

if (q.type === "mcq_multi") {
if (!Array.isArray(q.correctOptionIds) || q.correctOptionIds.length === 0) {
return { ok: false, msg: "Select at least one correct option." };
}
}

return { ok: true };
}

function applyQuestionChanges() {
// [FN] applyQuestionChanges
const v = validateSelectedQuestion();
if (!v.ok) { alert(v.msg); return; }

const q = getQuestionById(state.selectedQuestionId);
state.questionDraft = deepClone(q);

renderQuestionList();
toast("Applied");
}

// ======== Quiz-level ========
function gatherQuizMeta() {
// [FN] gatherQuizMeta
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
// [FN] validateQuizAll
gatherQuizMeta();

if (!state.quiz.classId) return { ok: false, msg: "Please select a class." };
if (!state.quiz.title) return { ok: false, msg: "Quiz title is required." };

if (state.quiz.type !== "kc") {
if (state.quiz.passScore === null || Number.isNaN(state.quiz.passScore)) return { ok: false, msg: "Pass score is required for Final Quiz." };
if (state.quiz.passScore < 0 || state.quiz.passScore > 100) return { ok: false, msg: "Pass score must be 0–100." };
}

if (!state.quiz.questions.length) return { ok: false, msg: "Please add at least one question." };

// validate all questions quickly
for (let i = 0; i < state.quiz.questions.length; i++) {
state.selectedQuestionId = state.quiz.questions[i].id;
const v = validateSelectedQuestion();
if (!v.ok) return { ok: false, msg: `Question ${i + 1}: ${v.msg}` };
}

return { ok: true };
}

function previewJson() {
// [FN] previewJson
gatherQuizMeta();
if (state.selectedQuestionId) gatherEditorToQuestionObject();

el.jsonPreview.textContent = JSON.stringify(state.quiz, null, 2);
bs.jsonModal.show();
}

function copyJson() {
// [FN] copyJson
const txt = el.jsonPreview.textContent || "";
navigator.clipboard.writeText(txt).then(() => toast("Copied JSON"));
}

// ======== Laravel mapping (IMPORTANT) ========
function normalizeFromLaravel(raw) {
// [FN] normalizeFromLaravel
// Convert Laravel quiz payload -> UI state shape used by this page.
// IMPORTANT: Correct answers are tracked by OPTION ID (not option index).
// Supports these backend fields (any one is fine):
// - correct_option_id (single/truefalse)
// - correct_option_ids (multi) (array OR JSON string)
// - correct_answer (short answer)
// Backward compatibility:
// - correct_index / correct_indexes (will be converted into option ids)

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
const backendType = String(q.questiontype ?? "").toLowerCase();
let uiType = "mcq_single";
if (backendType === "single") uiType = "mcq_single";
else if (backendType === "multi") uiType = "mcq_multi";
else if (backendType === "truefalse" || backendType === "true_false") uiType = "true_false";
else if (backendType === "short" || backendType === "short_answer") uiType = "short_answer";

const opts = Array.isArray(q.options) ? q.options : [];
const uiOptions = opts.map(o => ({
id: String(o.id ?? uid("tmpOPT")), // UI identifier (string)
_backendId: o.id ?? null, // numeric backend id (nullable for new)
text: o.option ?? o.option_text ?? o.text ?? ""
}));

// ---- Correct answer by option id (preferred) ----
const rawCorrectOptId = q.correct_option_id ?? q.correctOptionId ?? null;
const correctOptionId = (rawCorrectOptId != null && rawCorrectOptId !== "")
? String(rawCorrectOptId)
: null;

// Multi
let correctOptionIds = q.correct_option_ids ?? q.correctOptionIds ?? [];
if (typeof correctOptionIds === "string") {
try { correctOptionIds = JSON.parse(correctOptionIds); } catch { correctOptionIds = []; }
}
correctOptionIds = Array.isArray(correctOptionIds) ? correctOptionIds.map(String) : [];

// ---- Backward compatibility: index -> option id ----
const idxSingle = (typeof q.correct_index === "number") ? q.correct_index : null;
const idxMulti = Array.isArray(q.correct_indexes) ? q.correct_indexes : [];

let fallbackCorrectOptionId = null;
if (!correctOptionId && typeof idxSingle === "number" && uiOptions[idxSingle]) {
fallbackCorrectOptionId = String(uiOptions[idxSingle].id);
}

let fallbackCorrectOptionIds = [];
if ((!correctOptionIds || correctOptionIds.length === 0) && Array.isArray(idxMulti) && idxMulti.length) {
fallbackCorrectOptionIds = idxMulti
.map(i => uiOptions[i] ? String(uiOptions[i].id) : null)
.filter(Boolean);
}

return {
id: String(qid),
_backendId: qid,
text: q.question ?? q.text ?? "",
type: uiType,
points: q.points ?? 1,
required: (q.is_required ?? q.required ?? 1) ? true : false,
explanation: q.explanation ?? "",
options: uiOptions,

// correct answer (option-id based)
correctOptionId: correctOptionId ?? fallbackCorrectOptionId,
correctOptionIds: (correctOptionIds && correctOptionIds.length) ? correctOptionIds : fallbackCorrectOptionIds,

// short answer
correctAnswer: q.correct_answer ?? q.correctAnswer ?? ""
};
});

return ui;
}

function snapshotOriginalIdsFromLaravel(raw) {
// [FN] snapshotOriginalIdsFromLaravel
state.original.questionIds = new Set();
state.original.optionIdsByQuestion = new Map();

const questions = Array.isArray(raw.questions) ? raw.questions : [];
questions.forEach(q => {
if (q.id != null) state.original.questionIds.add(String(q.id));
const opts = Array.isArray(q.options) ? q.options : [];
const set = new Set(opts.filter(o => o.id != null).map(o => String(o.id)));
state.original.optionIdsByQuestion.set(String(q.id), set);
});
}

// ======== Build payload to backend (create/update/delete) ========
function buildLaravelSavePayload() {
// [FN] buildLaravelSavePayload
// Build ONE payload for create/update/delete.
// Correct answer is sent using OPTION ID(s).
//
// Recommended backend handling:
// - For existing options: use `id` (numeric) normally.
// - For new options: `id` will be null, but `client_id` is provided (temp uuid).
// - Correct fields:
// * correct_option_id (existing option numeric id OR null)
// * correct_client_option_id (temp uuid for newly-created correct option)
// * correct_option_ids / correct_client_option_ids for multi
//
// This allows you to save correct answers in the same request even when options are newly created.

gatherQuizMeta();
if (state.selectedQuestionId) gatherEditorToQuestionObject();

// figure out deleted question ids (existing only)
const currentExistingQIds = new Set(
state.quiz.questions
.map(q => q._backendId)
.filter(id => id != null)
.map(id => String(id))
);

const deletedQuestionIds = [...state.original.questionIds].filter(oldId => !currentExistingQIds.has(oldId));

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
if (q.type === "mcq_single") questiontype = "single";
else if (q.type === "mcq_multi") questiontype = "multi";
else if (q.type === "true_false") questiontype = "truefalse";
else if (q.type === "short_answer") questiontype = "short";

// Resolve correct option(s)
const optionsArr = Array.isArray(q.options) ? q.options : [];

const findOptByClientId = (clientId) => optionsArr.find(o => String(o.id) === String(clientId));

// single / truefalse
const selectedClientId = (q.type === "mcq_single" || q.type === "true_false") ? (q.correctOptionId ?? null) : null;
const selectedOpt = selectedClientId ? findOptByClientId(selectedClientId) : null;
const correctOptionId = selectedOpt && selectedOpt._backendId != null ? selectedOpt._backendId : null;
const correctClientOptionId = selectedOpt && selectedOpt._backendId == null ? String(selectedOpt.id) : null;

// multi
const selectedClientIds = (q.type === "mcq_multi" && Array.isArray(q.correctOptionIds)) ? q.correctOptionIds : [];
const correctOptionIds = [];
const correctClientOptionIds = [];

selectedClientIds.forEach(cid => {
const opt = findOptByClientId(cid);
if (!opt) return;
if (opt._backendId != null) correctOptionIds.push(opt._backendId);
else correctClientOptionIds.push(String(opt.id));
});

// Backward compatibility fields (optional): index(es)
const correct_index = (selectedOpt)
? optionsArr.findIndex(o => String(o.id) === String(selectedOpt.id))
: null;

const correct_indexes = (q.type === "mcq_multi")
? selectedClientIds
.map(cid => optionsArr.findIndex(o => String(o.id) === String(cid)))
.filter(i => i >= 0)
: [];

return {
// identify question
id: backendQuestionId, // null => create new
client_id: backendQuestionId ? null : String(q.id), // temp id for new question
sequence_no: idx + 1,

question: q.text,
questiontype,
points: q.points ?? 1,
is_active: 1,
explanation: q.explanation ?? "",

// correct fields (OPTION ID based)
correct_option_id: correctOptionId,
correct_client_option_id: correctClientOptionId,
correct_option_ids: correctOptionIds,
correct_client_option_ids: correctClientOptionIds,

// short answer
correct_answer: q.correctAnswer ?? "",

// options
options: (optionsArr).map((o, optIdx) => ({
id: o._backendId ?? null, // null => create new option
client_id: (o._backendId == null) ? String(o.id) : null, // temp option id for mapping on backend
option: o.text,
sequence_no: optIdx + 1,
is_active: 1
})),

// optional backward compatibility for existing DB schema
correct_index,
correct_indexes,

deleted_option_ids: deletedOptionIds
};
});

// Quiz-level payload
return {
id: state.quiz.quizId ?? null,
class_id: state.quiz.classId,
class_name: state.quiz.className,
quizType: state.quiz.type === "final" ? "FinalQuiz" : "KnowledgeCheck",
pass_score: state.quiz.passScore,
is_active: state.quiz.status === "active" ? 1 : 0,
title: state.quiz.title,
description: state.quiz.instructions,

questions: questionsPayload,
deleted_question_ids: deletedQuestionIds
};
}

// ======== Backend save ========
async function saveAll() {
// [FN] saveAll
const v = validateQuizAll();
if (!v.ok) { alert(v.msg); return; }

const payload = buildLaravelSavePayload();

try {
const res = await fetch(API_SAVE_URL(payload.id || "new"), {
method: "PUT", // one request for create/update/delete
headers: {
"Content-Type": "application/json",
"Accept": "application/json",
"X-CSRF-TOKEN": getCsrfToken()
},
body: JSON.stringify(payload)
});

if (!res.ok) {
const txt = await res.text();
throw new Error(`Save failed: ${res.status}\n${txt}`);
}

const saved = await res.json();

// refresh UI + snapshot after save (so deletions work next time)
snapshotOriginalIdsFromLaravel(saved);
state.quiz = normalizeFromLaravel(saved);

hydrateMeta();
renderQuestionList();
showEditor(false);

toast("Saved to backend");
} catch (e) {
console.error(e);
alert(e.message || "Save failed.");
}
}

// ======== Hydrate meta UI ========
function hydrateMeta() {
// [FN] hydrateMeta
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

// ======== Init ========
function initial() {
// [FN] initial
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
el.classNameInput.addEventListener("focus", () => renderClassDropdown());
el.questionSearch.addEventListener("input", renderQuestionList);
el.btnClearSearch.addEventListener("click", () => { el.questionSearch.value=""; renderQuestionList(); });

el.btnAddQuestion.addEventListener("click", addQuestion);

el.qType.addEventListener("change", () => {
onTypeChange();
el.qTypeBadge.textContent = typeToLabel(el.qType.value);
});

el.btnAddOption.addEventListener("click", addOption);
el.btnResetQuestion.addEventListener("click", resetQuestionEditor);
el.btnApplyQuestion.addEventListener("click", applyQuestionChanges);

el.btnDelete.addEventListener("click", deleteSelectedQuestion);
el.btnDuplicate.addEventListener("click", duplicateSelectedQuestion);
el.btnMoveUp.addEventListener("click", () => moveSelectedQuestion(-1));
el.btnMoveDown.addEventListener("click", () => moveSelectedQuestion(1));

el.btnPreviewJson.addEventListener("click", previewJson);
el.btnCopyJson.addEventListener("click", copyJson);
el.btnSaveAll.addEventListener("click", saveAll);

// ===== Run =====
initial();
</script>
</body>
</html>