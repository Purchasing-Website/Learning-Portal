document.addEventListener('DOMContentLoaded', () => {
  const stage = document.getElementById('stage');

  // ✅ Sample quizzes for testing
  const QUIZZES = {
    "qc-1": [
      {
        id: "q1",
        type: "truefalse",
        text: "A car battery is typically 12V.",
        options: ["True", "False"],
        answer: [0]
      },
      {
        id: "q2",
        type: "single",
        text: "Which terminal should be removed first when disconnecting a battery?",
        options: ["Positive (+)", "Negative (-)", "Either is fine", "Both at the same time"],
        answer: [1]
      }
    ],
    "quiz-1": [
      {
        id: "q1",
        type: "single",
        text: "Which file type can browsers render directly using an iframe most reliably?",
        options: ["PPTX", "PDF", "EXE", "PSD"],
        answer: [1]
      },
      {
        id: "q2",
        type: "multiple",
        text: "Select all content types your learning portal should support:",
        options: ["Video", "Image", "PDF", "PPTX (native browser viewer)"],
        answer: [0, 1, 2]
      },
      {
        id: "q3",
        type: "truefalse",
        text: "Single choice questions should use radio buttons.",
        options: ["True", "False"],
        answer: [0]
      }
    ]
  };

  window.addEventListener('lp:openQuiz', (e) => {
    const { quizId, quizTitle } = e.detail;
    openQuiz(quizId, quizTitle);
  });

  function openQuiz(quizId, quizTitle) {
    const questions = QUIZZES[quizId];
    if (!questions || questions.length === 0) {
      stage.innerHTML = `<div class="text-danger">Quiz not found: ${escapeHtml(quizId)}</div>`;
      return;
    }

    // Load quiz template into stage
    const tpl = document.getElementById('quizRoot');
    stage.innerHTML = '';
    stage.appendChild(tpl.cloneNode(true));
    const root = stage.querySelector('#quizRoot');
    root.classList.remove('d-none');

    const ui = {
      progress: stage.querySelector('#quizProgress'),
      qtype: stage.querySelector('#quizType'),
      qtext: stage.querySelector('#quizQuestionText'),
      options: stage.querySelector('#quizOptions'),
      err: stage.querySelector('#quizError'),
      btnPrev: stage.querySelector('#btnPrev'),
      btnNext: stage.querySelector('#btnNext'),
      btnSubmit: stage.querySelector('#btnSubmit')
    };

    let index = 0;
    const userAnswers = {}; // { qid: [indices] }

    ui.btnPrev.addEventListener('click', () => {
      if (index > 0) { index--; render(); }
    });

    ui.btnNext.addEventListener('click', () => {
      if (!validateCurrent()) return;
      if (index < questions.length - 1) { index++; render(); }
    });

    ui.btnSubmit.addEventListener('click', () => {
      if (!validateCurrent()) return;
      const result = grade(questions, userAnswers);
      stage.innerHTML = `
        <div class="text-center">
          <h4 class="mb-2">${escapeHtml(quizTitle || 'Quiz')} Completed</h4>
          <p class="mb-3">Score: <strong>${result.score}/${result.total}</strong></p>
          <button type="button" class="btn btn-outline-primary" id="btnRestart">Restart</button>
        </div>
      `;
      stage.querySelector('#btnRestart').addEventListener('click', () => openQuiz(quizId, quizTitle));
      console.log('Answers:', userAnswers);
    });

    function render() {
      ui.err.classList.add('d-none');

      const q = questions[index];
      ui.progress.textContent = `Question ${index + 1} / ${questions.length}`;
      ui.qtype.textContent = prettyType(q.type);
      ui.qtext.textContent = q.text;

      ui.options.innerHTML = '';
      const saved = userAnswers[q.id] || [];

      if (q.type === 'single' || q.type === 'truefalse') {
        q.options.forEach((label, i) => {
          ui.options.appendChild(radioRow(q.id, i, label, saved.includes(i), () => {
            userAnswers[q.id] = [i];
          }));
        });
      } else if (q.type === 'multiple') {
        q.options.forEach((label, i) => {
          ui.options.appendChild(checkRow(q.id, i, label, saved.includes(i), (checked) => {
            const cur = new Set(userAnswers[q.id] || []);
            checked ? cur.add(i) : cur.delete(i);
            userAnswers[q.id] = Array.from(cur).sort((a,b)=>a-b);
          }));
        });
      } else {
        ui.options.innerHTML = `<div class="text-danger">Unknown question type: ${escapeHtml(q.type)}</div>`;
      }

      ui.btnPrev.disabled = index === 0;

      const isLast = index === questions.length - 1;
      ui.btnNext.classList.toggle('d-none', isLast);
      ui.btnSubmit.classList.toggle('d-none', !isLast);
    }

    function validateCurrent() {
      const q = questions[index];
      const ans = userAnswers[q.id] || [];
      const ok = ans.length > 0;
      ui.err.classList.toggle('d-none', ok);
      return ok;
    }

    render();
  }

  function grade(questions, userAnswers) {
    let score = 0;
    questions.forEach(q => {
      const a = (userAnswers[q.id] || []).slice().sort((x,y)=>x-y);
      const b = (q.answer || []).slice().sort((x,y)=>x-y);
      if (a.length === b.length && a.every((v,i)=>v===b[i])) score++;
    });
    return { score, total: questions.length };
  }

  function prettyType(t) {
    if (t === 'single') return 'Single choice';
    if (t === 'multiple') return 'Multiple choice';
    if (t === 'truefalse') return 'True / False';
    return t;
  }

  function radioRow(qid, idx, label, checked, onChange) {
    const id = `opt_${qid}_${idx}`;
    const wrap = document.createElement('div');
    wrap.className = 'form-check';

    wrap.innerHTML = `
      <input class="form-check-input" type="radio" name="${escapeAttr(qid)}" id="${escapeAttr(id)}" ${checked ? 'checked' : ''}>
      <label class="form-check-label" for="${escapeAttr(id)}">${escapeHtml(label)}</label>
    `;
    wrap.querySelector('input').addEventListener('change', onChange);
    return wrap;
  }

  function checkRow(qid, idx, label, checked, onChange) {
    const id = `opt_${qid}_${idx}`;
    const wrap = document.createElement('div');
    wrap.className = 'form-check';

    wrap.innerHTML = `
      <input class="form-check-input" type="checkbox" id="${escapeAttr(id)}" ${checked ? 'checked' : ''}>
      <label class="form-check-label" for="${escapeAttr(id)}">${escapeHtml(label)}</label>
    `;
    wrap.querySelector('input').addEventListener('change', (e) => onChange(e.target.checked));
    return wrap;
  }

  function escapeHtml(s) {
    return String(s).replace(/[&<>"']/g, m => ({
      '&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'
    }[m]));
  }
  function escapeAttr(s) { return String(s).replace(/"/g, '&quot;'); }
});
