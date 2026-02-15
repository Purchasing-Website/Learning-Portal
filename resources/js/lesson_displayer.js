document.addEventListener('DOMContentLoaded', async () => {
  const lessonNav = document.getElementById('lessonNav');
  const stage = document.getElementById('stage');
  const lessonTitle = document.getElementById('lessonTitle');

  if (!lessonNav || !stage || !lessonTitle) return;

  let items = [];
  let currentId = null;   
  try {
    const res = await fetch('assets/js/lessons.json', { cache: 'no-store' });
    items = await res.json();
  } catch (e) {
    lessonNav.innerHTML = `<li class="nav-item px-3 text-danger small">Failed to load lessons</li>`;
    return;
  }

  // Build sidebar links
    lessonNav.innerHTML = items.map(it => `
      <li class="nav-item">
        <a class="nav-link lp-nav-link d-flex align-items-center justify-content-between" style="border-bottom: 1px solid rgba(255,255,255,0.15);"
           href="#${encodeURIComponent(it.id)}" data-id="${escapeAttr(it.id)}">
          <span>${escapeHtml(it.title)}</span>
          <span class="lp-done-icon ms-2" data-done-for="${escapeAttr(it.id)}" style="display:none;">
            <i class="fas fa-check-circle text-success"></i>
          </span>
        </a>
      </li>
    `).join('');

    //Hide next button in last lesson
    function updateNextButtonVisibility(currentId) {
  const btn = document.getElementById('btnNextLesson');
  if (!btn) return;

  const idx = items.findIndex(x => x.id === currentId);

  if (idx >= 0 && idx < items.length - 1) {
    btn.style.display = '';      // ✅ restore inline-block from CSS
  } else {
    btn.style.display = 'none';
  }
}//Hide next button in last lesson  
    
  // ===== Next Lesson Button wiring (put here) =====
    const btnNextLesson = document.getElementById('btnNextLesson');
    if (btnNextLesson) {
      btnNextLesson.addEventListener('click', () => {
        if (!currentId) return;

        // mark current as completed only if it's a lesson
        const curItem = items.find(x => x.id === currentId);
        if (curItem?.kind === 'lesson') setCompleted(currentId, true);

        const idx = items.findIndex(x => x.id === currentId);
        if (idx < 0 || idx >= items.length - 1) return;

        const next = items[idx + 1];
        location.hash = next.id;
        openItem(next.id);
      });
    }
    // ===== End Next Lesson Button wiring =====
  

  // Click -> partial load (only stage changes)
  lessonNav.addEventListener('click', (e) => {
    const link = e.target.closest('.lp-nav-link');
    if (!link) return;

    e.preventDefault();
    const id = link.dataset.id;
    location.hash = id;
    openItem(id);
  });

  // Support back/forward/deep link
  window.addEventListener('hashchange', () => {
    const id = decodeURIComponent((location.hash || `#${items[0].id}`).slice(1));
    openItem(id);
  });

  // Initial
  openItem(decodeURIComponent((location.hash || `#${items[0].id}`).slice(1)));

    //Set Content Centered
    function setStageCenter(isCentered) {
      stage.classList.toggle('text-center', !!isCentered);
    }

    function openItem(id) {
      id = String(id).trim();
      currentId = id;

      setActive(id);
      refreshDoneIcons();
      updateNextButtonVisibility(id);

      const item = items.find(x => x.id === id);
      console.log('[openItem] found item =', item);
      if (!item) return;

      // ✅ Center lessons, left-align quiz/knowledge check
      // (Adjust rule if you want pdf/video left-aligned too)
      if (item.kind === 'lesson') {
        setStageCenter(true);              // keep centered (your default)
        lessonTitle.textContent = item.title;
        renderLesson(item);
        return;
      }

      if (item.kind === 'quiz') {
        setStageCenter(false);             // remove text-center
        lessonTitle.textContent = item.title;

        window.dispatchEvent(new CustomEvent('lp:openQuiz', {
          detail: { quizId: item.quizId, quizTitle: item.title }
        }));
        return;
      }

      // If you later have knowledge check as a kind:
      if (item.kind === 'knowledgeCheck') {
        setStageCenter(false);             // remove text-center
        lessonTitle.textContent = item.title;
        // renderKnowledgeCheck(item) or dispatch event...
        return;
      }

      setStageCenter(false);
      stage.innerHTML = `<div class="text-danger">Unknown item kind: ${escapeHtml(item.kind)}</div>`;
    }


  function renderLesson(item) {
    stage.innerHTML = '';
    const type = (item.type || '').toLowerCase();

    if (type === 'image') {
      const img = document.createElement('img');
      img.src = item.src;
      img.alt = item.title;
      img.style.maxWidth = '100%';
      img.style.maxHeight = '75vh';
      img.style.objectFit = 'contain';
      stage.appendChild(img);
      return;
    }

    if (type === 'pdf') {
      const iframe = document.createElement('iframe');
      iframe.src = item.src;
      iframe.style.width = '100%';
      iframe.style.height = '75vh';
      iframe.style.border = '0';
      stage.appendChild(iframe);
      return;
    }

    if (type === 'video') {
      const v = document.createElement('iframe');
      v.allow = 'fullscreen; picture-in-picture';  
      v.controls = true;
      v.allowFullscreen = true;  
      v.style.width = '100%';
      v.style.height = '75vh';
      v.className = 'lp-video-frame';  
      v.src = item.src;
      stage.appendChild(v);
      return;
    }

    // PPTX fallback
    if (type === 'ppt' || type === 'pptx') {
      stage.innerHTML = `
        <div class="text-center">
          <div class="fw-semibold mb-2">${escapeHtml(item.title)}</div>
          <div class="text-muted mb-3">Convert PPTX to PDF for in-page viewing.</div>
          <a class="btn btn-primary" href="${item.src}" target="_blank" rel="noopener">Download PPT</a>
        </div>
      `;
      return;
    }

    stage.textContent = 'Unsupported lesson type';
  }

  function setActive(id) {
    document.querySelectorAll('#lessonNav .lp-nav-link').forEach(a => a.classList.remove('active'));
    const active = document.querySelector(`#lessonNav .lp-nav-link[data-id="${cssEscape(id)}"]`);
    if (active) active.classList.add('active');
  }

  function escapeHtml(s) {
    return String(s).replace(/[&<>"']/g, m => ({
      '&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'
    }[m]));
  }
  function escapeAttr(s) { return String(s).replace(/"/g, '&quot;'); }
  function cssEscape(v) { return (window.CSS && CSS.escape) ? CSS.escape(String(v)) : String(v).replace(/["\\]/g, '\\$&'); }
    

});

//Nav Completion Icon
const COMPLETION_KEY = 'lp_completed_v1';

function getCompletedMap() {
  try { return JSON.parse(localStorage.getItem(COMPLETION_KEY) || '{}'); }
  catch { return {}; }
}
function setCompleted(lessonId, isDone = true) {
  const map = getCompletedMap();
  map[lessonId] = isDone;
  localStorage.setItem(COMPLETION_KEY, JSON.stringify(map));
  refreshDoneIcons();
}
function refreshDoneIcons() {
  const map = getCompletedMap();
  document.querySelectorAll('[data-done-for]').forEach(el => {
    const id = el.getAttribute('data-done-for');
    el.style.display = map[id] ? 'inline-flex' : 'none';
  });
}
//Nav Completion Icon


