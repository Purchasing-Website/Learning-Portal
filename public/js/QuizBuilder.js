// assets/js/QuizBuilder.js (Quiz only, type fixed = "final")

const CLASS_LIST = [
  { classId: "CLS-1001", className: "Hao Lin Class A" },
  { classId: "CLS-1002", className: "Hao Lin Class B" },
  { classId: "CLS-1003", className: "English Class C" },
  { classId: "CLS-1004", className: "Math Class D" }
];

// ======== State ========
const state = {
  quiz: {
    classId: "",
    className: "",
    type: "final",          // fixed
    title: "",
    passScore: 80,
    status: "active",
    instructions: "",
    questions: []
  },
  selectedQuestionId: null,
  questionDraft: null,
};

// ======== DOM ========
const el = {
  // Quiz meta (Quiz page has these)
  classNameInput: document.getElementById("classNameInput"),
  classDropdown: document.getElementById("classDropdown"),
  classId: document.getElementById("classId"),
  quizTitle: document.getElementById("quizTitle"),
  passScore: document.getElementById("passScore"),
  status: document.getElementById("status"),
  instructions: document.getElementById("instructions"),

  // Question list
  questionList: document.getElementById("questionList"),
  btnAddQuestion: document.getElementById("btnAddQuestion"),
  questionSearch: document.getElementById("questionSearch"),
  btnClearSearch: document.getElementById("btnClearSearch"),

  // Editor
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
  toastMsg: document.getElementById("toastMsg"),
};

const bs = {
  jsonModal: new bootstrap.Modal(el.jsonModal),
  toast: new bootstrap.Toast(el.toast, { delay: 2200 })
};

// ======== Helpers ========
function uid(prefix = "Q") {
  return prefix + Math.random().toString(16).slice(2) + Date.now().toString(16);
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

// Class searchable dropdown
function renderClassDropdown(keyword="") {
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
    btn.addEventListener("click", () => {
      el.classId.value = btn.getAttribute("data-class-id");
      el.classNameInput.value = btn.getAttribute("data-class-name");
    });
  });
}

function getQuestionById(id) {
  return state.quiz.questions.find(q => q.id === id) || null;
}
function selectedIndex() {
  return state.quiz.questions.findIndex(q => q.id === state.selectedQuestionId);
}

function typeToLabel(type) {
  switch(type) {
    case "mcq_single": return "MCQ (Single)";
    case "mcq_multi": return "Multiple Correct";
    case "true_false": return "True/False";
    case "short_answer": return "Short Answer";
    default: return type || "Unknown";
  }
}
function questionHasCorrectAnswer(q) {
  if (!q) return false;
  if (q.type === "short_answer") return !!(q.correctAnswer && q.correctAnswer.trim());
  if (q.type === "true_false") return typeof q.correctIndex === "number";
  if (q.type === "mcq_single") return typeof q.correctIndex === "number";
  if (q.type === "mcq_multi") return Array.isArray(q.correctIndexes) && q.correctIndexes.length > 0;
  return false;
}

// ======== Render question list ========
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
              data-id="${escapeHtml(q.id)}">
        <div class="d-flex justify-content-between align-items-start gap-2">
          <div class="text-start">
            <div class="fw-semibold">Q${idx + 1}. ${escapeHtml(title).slice(0, 60)}${title.length>60?"…":""}</div>
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

function selectQuestion(id) {
  const q = getQuestionById(id);
  if (!q) return;

  state.selectedQuestionId = id;
  state.questionDraft = deepClone(q);
  hydrateEditorFromQuestion(q);
  showEditor(true);
  renderQuestionList();
  updateMoveButtons();
}

function hydrateEditorFromQuestion(q) {
  el.qText.value = q.text ?? "";
  el.qType.value = q.type ?? "mcq_single";
  el.qPoints.value = q.points ?? 1;
  el.qRequired.value = String(!!q.required);
  el.qExplanation.value = q.explanation ?? "";
  renderAnswerUIForType(q.type, q);
}

function gatherEditorToQuestionObject() {
  const q = getQuestionById(state.selectedQuestionId);
  if (!q) return null;

  q.text = el.qText.value;
  q.type = el.qType.value;
  q.points = parseInt(el.qPoints.value || "0", 10);
  q.required = (el.qRequired.value === "true");
  q.explanation = el.qExplanation.value;

  if (q.type === "short_answer") {
    q.correctAnswer = el.shortAnswer.value || "";
    q.options = [];
    q.correctIndex = null;
    q.correctIndexes = [];
  } else {
    const optRows = [...el.optionsList.querySelectorAll("[data-opt-id]")];

    q.options = optRows.map(row => {
      const id = row.getAttribute("data-opt-id");
      const txt = row.querySelector("input[type='text']").value;
      return { id, text: txt };
    });

    if (q.type === "mcq_single" || q.type === "true_false") {
      const checked = el.optionsList.querySelector("input[type='radio']:checked");
      q.correctIndex = checked ? parseInt(checked.value, 10) : null;
      q.correctIndexes = [];
    } else if (q.type === "mcq_multi") {
      const checked = [...el.optionsList.querySelectorAll("input[type='checkbox']:checked")];
      q.correctIndexes = checked.map(c => parseInt(c.value, 10));
      q.correctIndex = null;
    }
    q.correctAnswer = "";
  }

  return q;
}

function validateSelectedQuestion() {
  const q = gatherEditorToQuestionObject();
  if (!q) return { ok:false, msg:"No question selected." };

  if (!q.text || !q.text.trim()) return { ok:false, msg:"Question text is required." };
  if (!q.type) return { ok:false, msg:"Question type is required." };

  if (q.type === "short_answer") {
    if (!q.correctAnswer || !q.correctAnswer.trim()) return { ok:false, msg:"Short answer requires a correct answer." };
    return { ok:true };
  }

  if (!Array.isArray(q.options) || q.options.length < 2) return { ok:false, msg:"Please provide at least 2 options." };

  const emptyOpt = q.options.find(o => !o.text || !o.text.trim());
  if (emptyOpt) return { ok:false, msg:"All options must have text." };

  if (q.type === "mcq_single" || q.type === "true_false") {
    if (typeof q.correctIndex !== "number") return { ok:false, msg:"Please select the correct option." };
  }
  if (q.type === "mcq_multi") {
    if (!q.correctIndexes || q.correctIndexes.length === 0) return { ok:false, msg:"Please select at least one correct option." };
  }

  return { ok:true };
}

// ======== Render answer UI ========
function renderAnswerUIForType(type, q) {
  const isShort = type === "short_answer";
  el.shortAnswerBlock.classList.toggle("d-none", !isShort);
  el.optionsBlock.classList.toggle("d-none", isShort);

  if (isShort) {
    el.shortAnswer.value = q.correctAnswer ?? "";
    return;
  }

  if (type === "true_false") {
    q.options = [
      { id: q.options?.[0]?.id || uid("OPT"), text: "True" },
      { id: q.options?.[1]?.id || uid("OPT"), text: "False" }
    ];
  } else {
    if (!Array.isArray(q.options) || q.options.length === 0) {
      q.options = [
        { id: uid("OPT"), text: "" },
        { id: uid("OPT"), text: "" }
      ];
    }
  }

  let correctControl = "radio";
  if (type === "mcq_multi") correctControl = "checkbox";

  if (type === "mcq_single") el.optionsHint.textContent = "Select ONE correct answer.";
  if (type === "mcq_multi") el.optionsHint.textContent = "Select ONE or MORE correct answers.";
  if (type === "true_false") el.optionsHint.textContent = "Select the correct answer (True/False).";

  el.optionsList.innerHTML = "";
  q.options.forEach((opt, idx) => {
    el.optionsList.appendChild(buildOptionRow(opt, idx, correctControl, q));
  });

  const tf = type === "true_false";
  el.btnAddOption.disabled = tf;

  [...el.optionsList.querySelectorAll("input[type='text']")].forEach(inp => { inp.disabled = tf; });
  [...el.optionsList.querySelectorAll(".btnDelOpt")].forEach(btn => { btn.disabled = tf; });

  bindOptionRowEvents();
}

function buildOptionRow(opt, idx, controlType, q) {
  const row = document.createElement("div");
  row.className = "qb-option-row";
  row.setAttribute("data-opt-id", opt.id);

  const isCorrect =
    (q.type === "mcq_single" || q.type === "true_false")
      ? (q.correctIndex === idx)
      : (q.type === "mcq_multi" && Array.isArray(q.correctIndexes) && q.correctIndexes.includes(idx));

  row.innerHTML = `
    <div class="d-flex align-items-start gap-2">
      <div class="pt-1">
        <input class="form-check-input"
               type="${controlType}"
               name="correctChoice"
               value="${idx}"
               ${isCorrect ? "checked" : ""}>
      </div>

      <div class="flex-grow-1">
        <div class="d-flex align-items-center justify-content-between">
          <div class="small qb-muted">Option ${idx + 1}</div>
          <div class="d-flex gap-1">
            <button type="button" class="btn btn-sm btn-outline-secondary btnMoveOptUp" title="Move up">
              <i class="bi bi-arrow-up"></i>
            </button>
            <button type="button" class="btn btn-sm btn-outline-secondary btnMoveOptDown" title="Move down">
              <i class="bi bi-arrow-down"></i>
            </button>
            <button type="button" class="btn btn-sm btn-outline-danger btnDelOpt" title="Delete">
              <i class="bi bi-x-lg"></i>
            </button>
          </div>
        </div>
        <input class="form-control mt-1" type="text" value="${escapeHtml(opt.text ?? "")}" placeholder="Option text...">
      </div>
    </div>
  `;
  return row;
}

function bindOptionRowEvents() {
  const rows = [...el.optionsList.querySelectorAll(".qb-option-row")];

  rows.forEach((row) => {
    row.querySelector(".btnDelOpt").addEventListener("click", () => {
      const currentRows = [...el.optionsList.querySelectorAll(".qb-option-row")];
      if (currentRows.length <= 2) { toast("MCQ requires at least 2 options."); return; }
      row.remove();
      reindexOptionsPreserveCorrect();
    });

    row.querySelector(".btnMoveOptUp").addEventListener("click", () => {
      const prev = row.previousElementSibling;
      if (!prev) return;
      el.optionsList.insertBefore(row, prev);
      reindexOptionsPreserveCorrect();
    });

    row.querySelector(".btnMoveOptDown").addEventListener("click", () => {
      const next = row.nextElementSibling;
      if (!next) return;
      el.optionsList.insertBefore(next, row);
      reindexOptionsPreserveCorrect();
    });
  });
}

function reindexOptionsPreserveCorrect() {
  const type = el.qType.value;
  const rows = [...el.optionsList.querySelectorAll(".qb-option-row")];

  let correctOptIds = [];
  if (type === "mcq_multi") {
    correctOptIds = rows
      .filter(r => r.querySelector("input[type='checkbox']").checked)
      .map(r => r.getAttribute("data-opt-id"));
  } else {
    const checkedRow = rows.find(r => r.querySelector("input[type='radio']").checked);
    correctOptIds = checkedRow ? [checkedRow.getAttribute("data-opt-id")] : [];
  }

  const q = getQuestionById(state.selectedQuestionId);
  if (!q) return;

  q.options = rows.map(r => ({
    id: r.getAttribute("data-opt-id"),
    text: r.querySelector("input[type='text']").value
  }));

  if (type === "mcq_multi") {
    q.correctIndexes = q.options
      .map((o, i) => (correctOptIds.includes(o.id) ? i : -1))
      .filter(i => i >= 0);
    q.correctIndex = null;
  } else {
    const idx = q.options.findIndex(o => o.id === correctOptIds[0]);
    q.correctIndex = (idx >= 0 ? idx : null);
    q.correctIndexes = [];
  }

  renderAnswerUIForType(type, q);
}

// ======== Questions actions ========
function addQuestion() {
  const q = {
    id: uid("Q"),
    text: "",
    type: "mcq_single",
    points: 1,
    required: true,
    explanation: "",
    options: [
      { id: uid("OPT"), text: "" },
      { id: uid("OPT"), text: "" }
    ],
    correctIndex: null,
    correctIndexes: [],
    correctAnswer: ""
  };

  state.quiz.questions.push(q);
  state.selectedQuestionId = q.id;
  state.questionDraft = deepClone(q);

  renderQuestionList();
  selectQuestion(q.id);
  toast("Question added");
}

function deleteSelectedQuestion() {
  const idx = selectedIndex();
  if (idx < 0) return;

  const ok = confirm("Delete this question? This cannot be undone.");
  if (!ok) return;

  state.quiz.questions.splice(idx, 1);

  const next = state.quiz.questions[idx] || state.quiz.questions[idx - 1] || null;
  state.selectedQuestionId = next ? next.id : null;

  renderQuestionList();
  if (next) selectQuestion(next.id);
  else showEditor(false);

  toast("Question deleted");
}

function duplicateSelectedQuestion() {
  const q = getQuestionById(state.selectedQuestionId);
  if (!q) return;

  const copy = deepClone(q);
  copy.id = uid("Q");
  copy.text = (copy.text || "").trim() ? (copy.text + " (Copy)") : "(Copy)";
  if (Array.isArray(copy.options)) copy.options = copy.options.map(o => ({ ...o, id: uid("OPT") }));

  state.quiz.questions.splice(selectedIndex() + 1, 0, copy);
  renderQuestionList();
  selectQuestion(copy.id);
  toast("Duplicated");
}

function moveSelectedQuestion(dir) {
  const idx = selectedIndex();
  if (idx < 0) return;
  const newIdx = idx + dir;
  if (newIdx < 0 || newIdx >= state.quiz.questions.length) return;

  const arr = state.quiz.questions;
  [arr[idx], arr[newIdx]] = [arr[newIdx], arr[idx]];
  renderQuestionList();
  updateMoveButtons();
}

function updateMoveButtons() {
  const idx = selectedIndex();
  el.btnMoveUp.disabled = (idx <= 0);
  el.btnMoveDown.disabled = (idx < 0 || idx >= state.quiz.questions.length - 1);
  el.btnDuplicate.disabled = (idx < 0);
  el.btnDelete.disabled = (idx < 0);
}

// ======== Editor events ========
function onTypeChange() {
  const q = getQuestionById(state.selectedQuestionId);
  if (!q) return;

  q.type = el.qType.value;

  if (q.type === "short_answer") {
    q.correctAnswer = q.correctAnswer || "";
  } else {
    q.correctAnswer = "";
    q.correctIndex = (typeof q.correctIndex === "number") ? q.correctIndex : null;
    q.correctIndexes = Array.isArray(q.correctIndexes) ? q.correctIndexes : [];
  }

  renderAnswerUIForType(q.type, q);
}

function addOption() {
  const q = getQuestionById(state.selectedQuestionId);
  if (!q) return;
  if (el.qType.value === "true_false") return;

  gatherEditorToQuestionObject();
  q.options.push({ id: uid("OPT"), text: "" });
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

function applyQuestionChanges() {
  const v = validateSelectedQuestion();
  if (!v.ok) { alert(v.msg); return; }

  const q = getQuestionById(state.selectedQuestionId);
  state.questionDraft = deepClone(q);

  renderQuestionList();
  toast("Applied");
}

// ======== Quiz-level Save / Load (Quiz only) ========
function gatherQuizMeta() {
  state.quiz.classId = el.classId.value.trim();
  state.quiz.className = el.classNameInput.value.trim();
  state.quiz.type = "final"; // fixed
  state.quiz.title = el.quizTitle.value.trim();
  state.quiz.passScore = parseInt(el.passScore.value || "0", 10);
  state.quiz.status = el.status.value;
  state.quiz.instructions = el.instructions.value || "";
}

function validateQuizAll() {
  gatherQuizMeta();

  if (!state.quiz.classId) return { ok:false, msg:"Please select a class." };
  if (!state.quiz.title) return { ok:false, msg:"Quiz title is required." };

  if (Number.isNaN(state.quiz.passScore)) return { ok:false, msg:"Pass score is required." };
  if (state.quiz.passScore < 0 || state.quiz.passScore > 100) return { ok:false, msg:"Pass score must be 0–100." };

  if (!state.quiz.questions.length) return { ok:false, msg:"Please add at least one question." };

  for (let i = 0; i < state.quiz.questions.length; i++) {
    const q = state.quiz.questions[i];
    if (!q.text || !q.text.trim()) return { ok:false, msg:`Question ${i+1}: text is required.` };

    if (q.type === "short_answer") {
      if (!q.correctAnswer || !q.correctAnswer.trim()) return { ok:false, msg:`Question ${i+1}: correct answer required.` };
    } else {
      if (!q.options || q.options.length < 2) return { ok:false, msg:`Question ${i+1}: at least 2 options required.` };
      if (q.options.some(o => !o.text || !o.text.trim())) return { ok:false, msg:`Question ${i+1}: option text missing.` };

      if (q.type === "mcq_single" || q.type === "true_false") {
        if (typeof q.correctIndex !== "number") return { ok:false, msg:`Question ${i+1}: select correct option.` };
      }
      if (q.type === "mcq_multi") {
        if (!q.correctIndexes || q.correctIndexes.length === 0) return { ok:false, msg:`Question ${i+1}: select at least one correct option.` };
      }
    }
  }
  return { ok:true };
}

function getPayload() {
  gatherQuizMeta();
  if (state.selectedQuestionId) gatherEditorToQuestionObject();
  return deepClone(state.quiz);
}

async function saveAll() {
  const v = validateQuizAll();
  if (!v.ok) { alert(v.msg); return; }

  const payload = getPayload();

  try {
    localStorage.setItem(
      "QUIZ_BUILDER_" + payload.classId,   // type fixed, no need in key
      JSON.stringify(payload)
    );
    toast("Saved to localStorage (demo)");
  } catch (e) {
    console.error(e);
    alert("Save failed.");
  }
}

function previewJson() {
  const payload = getPayload();
  el.jsonPreview.textContent = JSON.stringify(payload, null, 2);
  bs.jsonModal.show();
}

function copyJson() {
  const txt = el.jsonPreview.textContent || "";
  navigator.clipboard.writeText(txt).then(() => toast("Copied JSON"));
}

function hydrateMeta() {
  // Quiz only
  el.quizTitle.value = state.quiz.title || "";
  el.passScore.value = (state.quiz.passScore ?? 80);
  el.status.value = state.quiz.status || "active";
  el.instructions.value = state.quiz.instructions || "";
}

async function loadInitial() {
  if (window.QUIZ_PAYLOAD) {
    state.quiz = deepClone(window.QUIZ_PAYLOAD);
    state.quiz.type = "final";
    hydrateMeta();
    renderQuestionList();
    showEditor(false);
    return;
  }

  const params = new URLSearchParams(window.location.search);
  const classId = params.get("classId");

  if (classId) {
    const key = "QUIZ_BUILDER_" + classId;
    const local = localStorage.getItem(key);

    if (local) {
      state.quiz = JSON.parse(local);
      state.quiz.type = "final";
      hydrateMeta();
      renderQuestionList();
      showEditor(false);
      toast("Loaded from localStorage");
      return;
    }
  }

  hydrateMeta();
  renderQuestionList();
  showEditor(false);
}

// ======== Event bindings ========
// Class searchable dropdown
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

// Live update list title while typing
el.qText.addEventListener("input", () => renderQuestionList());

loadInitial();
