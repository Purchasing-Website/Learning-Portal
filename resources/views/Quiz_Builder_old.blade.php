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

          <div class="list-group qb-list" id="questionList">
            
          </div>
          
          {{-- <div id="questions">
@foreach($quiz->questions as $qIndex => $question)
    <div class="question" data-question="{{ $qIndex }}">

        <!-- EXISTING QUESTION -->
        <input type="text"
            name="questions[{{ $qIndex }}][question]"
            value="{{ $question->question }}">

        <!-- Hidden ID for UPDATE -->
        <input type="hidden"
            name="questions[{{ $qIndex }}][id]"
            value="{{ $question->id }}">

        <div id="options-{{ $qIndex }}">
            @foreach($question->options as $oIndex => $option)

                <!-- EXISTING OPTION -->
                <input type="text"
                    name="questions[{{ $qIndex }}][options][{{ $oIndex }}][text]"
                    value="{{ $option->option_text }}">

                <input type="checkbox"
                    name="questions[{{ $qIndex }}][options][{{ $oIndex }}][is_correct]"
                    {{ $option->is_correct ? 'checked' : '' }}>

                <!-- Hidden OPTION ID -->
                <input type="hidden"
                    name="questions[{{ $qIndex }}][options][{{ $oIndex }}][id]"
                    value="{{ $option->id }}">

            @endforeach
        </div>

        <button type="button" onclick="addOption({{ $qIndex }})">
            Add Option
        </button>
    </div>
@endforeach
</div> --}}


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
                  <option value="mcq_single">MCQ (Single Correct)</option>
                  <option value="mcq_multi">Multiple Correct</option>
                  <option value="true_false">True / False</option>
                  <option value="short_answer">Short Answer</option>
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
     * QUIZ BUILDER - UI + BUSINESS LOGIC (single page)
     *****************************************************************/

    // OPTIONAL:
    // If you render server-side, you can inject existing quiz JSON into:
    // window.QUIZ_PAYLOAD = {...};
    // Or we will try to fetch by ?quizId=PGxxxxx
    // window.QUIZ_PAYLOAD = null;

	// ===== Sample class list (replace with fetch from backend later) =====
	const CLASS_LIST = [
	  { classId: "CLS-1001", className: "Hao Lin Class A" },
	  { classId: "CLS-1002", className: "Hao Lin Class B" },
	  { classId: "CLS-1003", className: "English Class C" },
	  { classId: "CLS-1004", className: "Math Class D" }
	];

    // ======== State ========
    // let state = {
		// quiz: {
		//   classId: "",
		//   className: "",
		//   type: "final",
		//   title: "",
		//   passScore: 80,
		//   status: "active",
		//   instructions: "",
		//   questions: []
		// },
    //   selectedQuestionId: null,
    //   questionDraft: null, // used for reset/apply
    // };

    let state = {{ Js::from($quiz) }};
    console.log({{ Js::from($quiz) }});
    //console.log(state);

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
      toastMsg: document.getElementById("toastMsg"),
    };

    const bs = {
      jsonModal: new bootstrap.Modal(el.jsonModal),
      toast: new bootstrap.Toast(el.toast, { delay: 2200 })
    };

    // ======== Helpers ========
	//class name searchable dropdown
	function renderClassDropdown(keyword="") {
	  const k = keyword.trim().toLowerCase();

	  const filtered = CLASS_LIST.filter(c =>
		c.className.toLowerCase().includes(k) ||
		c.classId.toLowerCase().includes(k)
	  ).slice(0, 30); // limit

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

	  // bind click
	  [...el.classDropdown.querySelectorAll("[data-class-id]")].forEach(btn => {
		btn.addEventListener("click", () => {
		  el.classId.value = btn.getAttribute("data-class-id");
		  el.classNameInput.value = btn.getAttribute("data-class-name");
		});
	  });
	}
	
	function syncPassScoreByQuizType() {
	  const isKC = el.quizType.value === "kc";

	  // Disable for Knowledge Check
	  el.passScore.disabled = isKC;

	  // Optional: clear value when KC to avoid confusion
	  if (isKC) {
		el.passScore.value = "";   // empty means "not used"
	  } else {
		// If user switches back to Final Quiz and it's empty, set default
		if (el.passScore.value === "") el.passScore.value = "80";
	  }
	}


    function uid(prefix="Q") {
      return prefix + Math.random().toString(16).slice(2) + Date.now().toString(16);
    }

    function deepClone(obj) {
      return JSON.parse(JSON.stringify(obj));
    }

    function toast(msg) {
      el.toastMsg.textContent = msg;
      bs.toast.show();
    }

    function escapeHtml(str="") {
      return String(str).replace(/[&<>"']/g, s => ({
        "&":"&amp;","<":"&lt;",">":"&gt;",'"':"&quot;","'":"&#039;"
      }[s]));
    }

    function getQuestionById(id) {
      return state.quiz.questions.find(q => q.id === id) || null;
    }

    function selectedIndex() {
      return state.quiz.questions.findIndex(q => q.id === state.selectedQuestionId);
    }

    // ======== Render question list ========
    function renderQuestionList() {
      const keyword = (el.questionSearch.value || "").trim().toLowerCase();

      const items = state.questions
        .map((q, idx) => ({ q, idx }))
        .filter(({ q }) => {
          if (!keyword) return true;
          const t = (q.text || "").toLowerCase();
          return t.includes(keyword) || (q.type || "").includes(keyword);
        });

      el.questionList.innerHTML = items.map(({ q, idx }) => {
        const active = q.id === state.selectedQuestionId ? "active" : "";
        const title = q.question?.trim() ? q.question.trim() : "(Untitled question)";
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

      // Bind clicks
      [...el.questionList.querySelectorAll("[data-id]")].forEach(btn => {
        btn.addEventListener("click", () => selectQuestion(btn.getAttribute("data-id")));
      });
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

    // ======== Editor lifecycle ========
    function showEditor(show) {
      el.emptyState.classList.toggle("d-none", show);
      el.questionForm.classList.toggle("d-none", !show);
    }

    function selectQuestion(id) {
      const q = getQuestionById(id);
      if (!q) return;

      state.selectedQuestionId = id;
      state.questionDraft = deepClone(q); // for reset
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

      // render type-specific blocks
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
        // Options-based types
        const optRows = [...el.optionsList.querySelectorAll("[data-opt-id]")];

        q.options = optRows.map(row => {
          const id = row.getAttribute("data-opt-id");
          const txt = row.querySelector("input[type='text']").value;
          return { id, text: txt };
        });

        // correct flags
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

      // Options types
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

    // ======== Render answer UI per type ========
    function renderAnswerUIForType(type, q) {
      // show/hide blocks
      const isShort = type === "short_answer";
      el.shortAnswerBlock.classList.toggle("d-none", !isShort);
      el.optionsBlock.classList.toggle("d-none", isShort);

      if (isShort) {
        el.shortAnswer.value = q.correctAnswer ?? "";
        return;
      }

      // For true/false, auto-enforce 2 options
      if (type === "true_false") {
        q.options = [
          { id: q.options?.[0]?.id || uid("OPT"), text: "True" },
          { id: q.options?.[1]?.id || uid("OPT"), text: "False" }
        ];
      } else {
        // Ensure options exists for MCQ types
        if (!Array.isArray(q.options) || q.options.length === 0) {
          q.options = [
            { id: uid("OPT"), text: "" },
            { id: uid("OPT"), text: "" }
          ];
        }
      }

      // Hint + input type
      let correctControl = "radio";
      if (type === "mcq_multi") correctControl = "checkbox";

      if (type === "mcq_single") el.optionsHint.textContent = "Select ONE correct answer.";
      if (type === "mcq_multi") el.optionsHint.textContent = "Select ONE or MORE correct answers.";
      if (type === "true_false") el.optionsHint.textContent = "Select the correct answer (True/False).";

      el.optionsList.innerHTML = "";
      q.options.forEach((opt, idx) => {
        el.optionsList.appendChild(buildOptionRow(opt, idx, correctControl, q));
      });

      // For True/False, disable editing + remove add/delete
      const tf = type === "true_false";
      el.btnAddOption.disabled = tf;

      [...el.optionsList.querySelectorAll("input[type='text']")].forEach(inp => {
        inp.disabled = tf;
      });

      [...el.optionsList.querySelectorAll(".btnDelOpt")].forEach(btn => {
        btn.disabled = tf;
      });

      // wire option actions (delete / move)
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

      rows.forEach((row, idx) => {
        row.querySelector(".btnDelOpt").addEventListener("click", () => {
          // Keep at least 2 options
          if (rows.length <= 2) { toast("MCQ requires at least 2 options."); return; }
          row.remove();
          // Re-render to re-index values & preserve correct selection
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
      // capture current checked values by opt-id
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

      // rebuild UI based on new order
      const q = getQuestionById(state.selectedQuestionId);
      if (!q) return;

      q.options = rows.map(r => ({
        id: r.getAttribute("data-opt-id"),
        text: r.querySelector("input[type='text']").value
      }));

      // map correct selection back
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

    // ======== Actions: questions ========
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

      state.questions.push(q);
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

      // select next
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
      // regenerate option ids
      if (Array.isArray(copy.options)) {
        copy.options = copy.options.map(o => ({ ...o, id: uid("OPT") }));
      }
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

      // Update q.type immediately and render correct UI
      q.type = el.qType.value;

      // Normalize correct fields
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

      // Gather current UI options into q, then add
      gatherEditorToQuestionObject();

      q.options.push({ id: uid("OPT"), text: "" });
      renderAnswerUIForType(q.type, q);
      toast("Option added");
    }

    function resetQuestionEditor() {
      const q = getQuestionById(state.selectedQuestionId);
      if (!q || !state.questionDraft) return;

      // revert to draft snapshot
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

      // Save snapshot as new draft
      const q = getQuestionById(state.selectedQuestionId);
      state.questionDraft = deepClone(q);

      renderQuestionList();
      toast("Applied");
    }

    // ======== Quiz-level Save / Load ========
	function gatherQuizMeta() {
	  state.quiz.classId = el.classId.value.trim();
	  state.quiz.className = el.classNameInput.value.trim();

	  state.quiz.type = el.quizType.value;
	  state.quiz.title = el.quizTitle.value.trim();
	  const isKC = el.quizType.value === "kc";
	  state.quiz.passScore = isKC
		  ? null
		  : parseInt(el.passScore.value || "0", 10);
	  state.quiz.status = el.status.value;
	  state.quiz.instructions = el.instructions.value || "";
	}


    function validateQuizAll() {
      gatherQuizMeta();
	  if (!state.quiz.classId) return { ok:false, msg:"Please select a class." };
	  if (!state.quiz.title) return { ok:false, msg:"Quiz title is required." };
      if (!state.quiz.title) return { ok:false, msg:"Quiz title is required." };
      if (state.quiz.type !== "kc") {
		  if (state.quiz.passScore === null || Number.isNaN(state.quiz.passScore))
			return { ok:false, msg:"Pass score is required for Final Quiz." };

		  if (state.quiz.passScore < 0 || state.quiz.passScore > 100)
			return { ok:false, msg:"Pass score must be 0–100." };
		}


      if (!state.quiz.questions.length) return { ok:false, msg:"Please add at least one question." };

      // Validate every question
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
      // Make sure editor changes are gathered if user didn't press Apply
      if (state.selectedQuestionId) gatherEditorToQuestionObject();

      return deepClone(state.quiz);
    }

    async function saveAll() {
	  const v = validateQuizAll();
	  if (!v.ok) { alert(v.msg); return; }

	  const payload = getPayload();

	  try {
		localStorage.setItem(
		  "QUIZ_BUILDER_" + payload.classId + "_" + payload.type,
		  JSON.stringify(payload)
		);
		toast("Saved to localStorage (demo)");
	  } catch (e) {
		console.error(e);
		alert("Save failed.");
	  }
	}


    async function saveToBackend(payload) {
	  const url = "/api/quizzes?classId=" + encodeURIComponent(payload.classId) +
				  "&type=" + encodeURIComponent(payload.type);

	  const res = await fetch(url, {
		method: "PUT",
		headers: { "Content-Type": "application/json" },
		body: JSON.stringify(payload)
	  });

	  if (!res.ok) throw new Error("Save failed: " + res.status);
	  toast("Saved to backend");
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

    async function loadInitial() {
      // 1) If injected by server:
      if (window.QUIZ_PAYLOAD) {
        state.quiz = deepClone(window.QUIZ_PAYLOAD);
        hydrateMeta();
        renderQuestionList();
        showEditor(false);
        return;
      }

      // 2) Try URL param
      const params = new URLSearchParams(window.location.search);
      const classId = params.get("classId");
		const type = params.get("type") || "final";

		if (classId) {
		  const key = "QUIZ_BUILDER_" + classId + "_" + type;
		  const local = localStorage.getItem(key);

		  if (local) {
			state.quiz = JSON.parse(local);
			hydrateMeta();
			renderQuestionList();
			showEditor(false);
			toast("Loaded from localStorage");
			return;
		  }
		}

      // default empty quiz
      hydrateMeta();
      renderQuestionList();
      showEditor(false);
    }

	async function fetchQuizFromBackend(classId, type) {
	  const url = "/api/quizzes?classId=" + encodeURIComponent(classId) +
				  "&type=" + encodeURIComponent(type);

	  const res = await fetch(url, { headers: { "Accept": "application/json" }});
	  if (!res.ok) throw new Error("Load failed: " + res.status);

	  state.quiz = await res.json();
	  hydrateMeta();
	  renderQuestionList();
	  showEditor(false);
	  toast("Loaded from backend");
	}


    function hydrateMeta() {
      el.quizType.value = state.quizType || "final";
      el.quizTitle.value = state.title || "";
      el.passScore.value = state.pass_score ?? 80;
      el.status.value = state.is_active || "active";
      //el.instructions.value = state.quiz.instructions || "";
	  
	  syncPassScoreByQuizType();
    }

    // ======== Event bindings ========
	// Class searchable dropdown events
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

    // If user edits question text, reflect live in list
    el.qText.addEventListener("input", () => renderQuestionList());

    // On load
    loadInitial();

//     let questionIndex = {{ $quiz->questions->count() }};

//     let optionIndex = {};

//     function addQuestion() {
//     const qIndex = questionIndex++;

//     document.getElementById('questions').insertAdjacentHTML('beforeend', `
//         <div class="question">

//             <input type="text"
//                 name="questions[${qIndex}][question]">

//             <div id="options-${qIndex}"></div>

//             <button type="button" onclick="addOption(${qIndex})">
//                 Add Option
//             </button>
//         </div>
//     `);
// }

// function addOption(questionIndex) {

//     if (!optionIndex[questionIndex]) {
//         optionIndex[questionIndex] = 0;
//     }

//     const oIndex = optionIndex[questionIndex]++;

//     document.getElementById(`options-${questionIndex}`)
//         .insertAdjacentHTML('beforeend', `
//             <div class="option">
//                 <input type="text"
//                     name="questions[${questionIndex}][options][${oIndex}][text]">

//                 <input type="checkbox"
//                     name="questions[${questionIndex}][options][${oIndex}][is_correct]">
//             </div>
//         `);
// }





  </script>
</body>
</html>
