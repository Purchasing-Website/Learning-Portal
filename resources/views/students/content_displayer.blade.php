@extends('layouts.comtent_displayer_app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/content_displayer.css') }}">
@endpush

@section('content')
  <!-- dynamic class name (top of main, like your screenshot area) -->

  <!-- dynamic current lesson title -->
  <h1 class="lp-title" id="lpLessonTitle">Select a lesson</h1>

  <div class="lp-card">
    <div class="lp-stage">
      <div class="lp-viewbox" id="lpStage">
        <div class="text-center">
          <div class="fw-semibold" style="color:rgba(234,240,255,.75)">Learning Content</div>
          <div class="small" style="color:var(--lp-muted)">Choose a lesson on the left</div>
        </div>
      </div>
    </div>
  </div>

  <div class="lp-nextbar">
    <button class="lp-nextbtn" id="btnNextLesson" type="button">
      Next <i class="bi bi-chevron-double-down"></i>
    </button>
  </div>
  
  <!-- QUIZ TEMPLATE (quiz.js clones this into #stage) -->
  <div id="quizRoot" class="d-none">
    <div class="p-3 p-md-4 w-100">

    <div class="d-flex align-items-left justify-content-between flex-wrap gap-2 mb-3">
      <div>
      <div class="small text-white-50" id="quizProgress">Question 1 / 1</div>
      <div class="fw-semibold" id="quizType">Single choice</div>
      </div>
      <div id="quizError" class="text-warning small d-none">
      Please select an answer to continue.
      </div>
    </div>

    <h5 class="mb-3" id="quizQuestionText">Question text</h5>

    <div class="d-grid gap-2" id="quizOptions"></div>

    <div class="d-flex gap-2 mt-4">
      <button type="button" class="btn btn-outline-light" id="btnPrev">
      <i class="bi bi-arrow-left"></i> Prev
      </button>

      <button type="button" class="btn btn-light ms-auto" id="btnNext">
      Next <i class="bi bi-arrow-right"></i>
      </button>

      <button type="button" class="btn btn-success ms-auto d-none" id="btnSubmit">
      Submit <i class="bi bi-check2"></i>
      </button>
    </div>
    </div>
  </div>
@endsection
  
@push('scripts')
<script>
/* =========================================================
     JSON moved to JS (dynamic class + user + lessons)
     ========================================================= */
  const CLASS_DATA = {
    classId: "CLS-1001",
    className: "Hao Lin Class A",
    user: { name: "Khye Shen" },
    footer: "Hao Lin© Brand 2025",
    items: [
      { id: "lesson-1", title: "Lesson 1", kind: "lesson", type: "image", src:"https://khyeshen.b-cdn.net/color.jpeg", completed:false },
      { id: "lesson-2", title: "Lesson 2", kind: "lesson", type: "pdf",   src: "https://haolin.b-cdn.net/Haolin Learning Portal Proposal.pdf", completed:false },
      {  id: "lesson-3",  title: "Lesson 3",  kind: "lesson",  type: "video",  videoId: "c713c2d7-01e6-4d41-8b19-c8d86e9d9870",  completed: false},
      { id: "knowledge-check", title: "Knowledge Check", kind: "quiz", quizId: "qc-1", completed:false },
      { id: "quiz",            title: "Quiz",            kind: "quiz", quizId: "quiz-1", completed:false }
    ]
  };

  const navEl = document.getElementById("lpNav");
  const classSideEl = document.getElementById("lpClassNameSide");
  const classMobileEl = document.getElementById("lpClassNameMobile");
  const userNameEl = document.getElementById("lpUserName");
  const footerEl = document.getElementById("lpFooterText");

  const titleEl = document.getElementById("lpLessonTitle");
  const stageEl = document.getElementById("lpStage");
  const nextBtn = document.getElementById("btnNextLesson");
  const offcanvasEl = document.getElementById("lpSidebar");

  const exitBtn = document.getElementById("btnExit");
  const sidebarToggleBtn = document.getElementById("btnSidebarToggle");

  let items = CLASS_DATA.items;
  let currentIndex = 0;
  
  // ===== Completion store (lessons + knowledge check + quiz) =====
	const COMPLETION_KEY = `lp_completion_${CLASS_DATA.classId}`; // unique per class
	
	const BUNNY_EMBED_CACHE = new Map();

	async function getSignedBunnyEmbedUrl(videoId) {
	  if (BUNNY_EMBED_CACHE.has(videoId)) return BUNNY_EMBED_CACHE.get(videoId);

	  const res = await fetch(`ContentDisplayer.php?action=bunny_embed&videoId=${encodeURIComponent(videoId)}`, {
		cache: "no-store",
		credentials: "same-origin"
	  });

	  const data = await res.json();
	  if (!res.ok || !data.signedUrl) {
		throw new Error(data.error || "Failed to get signed video URL");
	  }

	  BUNNY_EMBED_CACHE.set(videoId, data.signedUrl);
	  return data.signedUrl;
	}


  document.addEventListener("DOMContentLoaded", () => {
    // Apply dynamic header values
    classSideEl.textContent = CLASS_DATA.className;
    classMobileEl.textContent = CLASS_DATA.className;
    userNameEl.textContent = CLASS_DATA.user?.name || "User";
    footerEl.textContent = CLASS_DATA.footer || "";

    renderNav();
    openItem(0);

	nextBtn.addEventListener("click", () => {
	  const cur = items[currentIndex];
	  if (!cur) return;

	  // Only lessons can be completed by Next button
	  if (cur.kind === "lesson") {
		cur.completed = true;    // ✅ updates CLASS_DATA
		renderNav();             // ✅ refresh ✔
	  }

	  if (currentIndex < items.length - 1) {
		openItem(currentIndex + 1);
	  }
	});


    exitBtn.addEventListener("click", () => {
      // Replace with your real logout / redirect
      window.location.href = "index.html"; // example
    });

    // If you want deep link: open via hash
    window.addEventListener("hashchange", () => {
      const id = decodeURIComponent(location.hash.replace("#",""));
      const idx = items.findIndex(x => x.id === id);
      if (idx >= 0) openItem(idx);
    });
	
	// =========================================================
	// Sidebar toggler (mobile: offcanvas show/hide, desktop: collapse fixed sidebar)
	// =========================================================
	const mqDesktop = window.matchMedia("(min-width: 992px)");

	function isDesktop() {
	  return mqDesktop.matches;
	}

	function toggleSidebar() {
	  if (isDesktop()) {
		document.body.classList.toggle("lp-sidebar-collapsed");
		return;
	  }

	  // Mobile: use Bootstrap offcanvas show/hide
	  const oc = bootstrap.Offcanvas.getOrCreateInstance(offcanvasEl);
	  if (offcanvasEl.classList.contains("show")) oc.hide();
	  else oc.show();
	}

	sidebarToggleBtn?.addEventListener("click", toggleSidebar);

	// If user resized from mobile -> desktop, ensure offcanvas is not stuck open
	mqDesktop.addEventListener("change", () => {
	  if (isDesktop()) {
		bootstrap.Offcanvas.getInstance(offcanvasEl)?.hide();
	  }
	});

  });

  function renderNav(){
    navEl.innerHTML = items.map((it, idx) => {
	const rightBadge =
	  it.completed === true
		? `<span class="lp-done"><i class="bi bi-check-lg"></i></span>`
		: (it.kind === "quiz"
			? `<span class="badge text-bg-warning">Go</span>`
			: `<span class="badge text-bg-secondary">New</span>`);


      return `
        <a href="#${encodeURIComponent(it.id)}" data-index="${idx}" class="${idx === currentIndex ? "active" : ""}">
          <span>${escapeHtml(it.title)}</span>
          ${rightBadge}
        </a>
      `;
    }).join("");

    navEl.querySelectorAll("a").forEach(a => {
      a.addEventListener("click", (e) => {
        e.preventDefault();
        openItem(+a.dataset.index);

        // close offcanvas on mobile
        if (window.matchMedia("(max-width: 991.98px)").matches) {
          bootstrap.Offcanvas.getInstance(offcanvasEl)?.hide();
        }
      });
    });
  }

    //Set Content Centered
    function setStageCenter(isCentered) {
      stageEl.classList.toggle('text-center', !!isCentered);
    }
	
  function openItem(index){
    currentIndex = index;
    const item = items[index];

    // active style
    navEl.querySelectorAll("a").forEach(a => a.classList.remove("active"));
    navEl.querySelector(`a[data-index="${index}"]`)?.classList.add("active");

    // lesson title
    titleEl.textContent = item.title;

    // next visibility
    nextBtn.style.visibility = (index < items.length - 1) ? "visible" : "hidden";

    // render content
    renderContent(item);

    // set hash
    location.hash = item.id;
  }

  function renderContent(item){
    stageEl.innerHTML = "";

    if (item.kind === "lesson") {
      const type = (item.type || "").toLowerCase();

      if (type === "image") {
        const img = document.createElement("img");
        img.src = encodeURI(item.src); // important for Chinese filename
        img.alt = item.title;

        img.onerror = () => {
          stageEl.innerHTML = `<div class="text-danger small">
            Image failed to load.<br>
            URL: ${escapeHtml(item.src)}
          </div>`;
        };

        stageEl.appendChild(img);
        return;
      }

      if (type === "pdf") {
        stageEl.innerHTML = `
          <iframe class="lp-iframe"
                  src="${encodeURI(item.src)}"
                  title="${escapeHtml(item.title)}"></iframe>`;
        return;
      }

      if (type === "video") {
	  // show loading first
	  stageEl.innerHTML = `
		<div class="text-center small">
		  Loading video...
		</div>`;

	  const videoId = item.videoId;
	  if (!videoId) {
		stageEl.innerHTML = `<div class="text-warning small">Missing videoId for this lesson.</div>`;
		return;
	  }

	  getSignedBunnyEmbedUrl(videoId)
		.then((signedUrl) => {
		  stageEl.innerHTML = `
			<iframe class="lp-iframe"
					src="${signedUrl}"
					title="${escapeHtml(item.title)}"
					allow="fullscreen; autoplay; encrypted-media; picture-in-picture"></iframe>`;
		})
		.catch((err) => {
		  stageEl.innerHTML = `<div class="text-danger small">
			Failed to load secured video.<br>
			${escapeHtml(err.message)}
		  </div>`;
		});

	  return;
	}


      stageEl.innerHTML = `<div class="text-warning small">Unsupported lesson type: ${escapeHtml(type)}</div>`;
      return;
    }

    // quiz placeholder
    stageEl.innerHTML = `
    <div class="text-center w-100">
      <div class="fw-semibold">${escapeHtml(item.title)}</div>
      <div class="small mt-1" style="color: var(--lp-muted)">
        Quiz ID: ${escapeHtml(item.quizId || item.id)}
      </div>

      <button class="btn btn-light btn-sm mt-3" type="button" id="btnStartQuiz">
        Start
      </button>
    </div>
	`;

	  const btn = document.getElementById("btnStartQuiz");
	  btn.addEventListener("click", () => {
		// IMPORTANT: trigger quiz.js
		window.dispatchEvent(new CustomEvent("lp:openQuiz", {
		  detail: {
			quizId: item.quizId || item.id,
			quizTitle: item.title,
			itemId: item.id            // ✅ IMPORTANT: nav item id ("quiz" / "knowledge-check")
		  }
		}));
	  });
  }

  function escapeHtml(str){
    return String(str).replace(/[&<>"']/g, m => ({
      "&":"&amp;","<":"&lt;",">":"&gt;",
      '"':"&quot;","'":"&#39;"
    }[m]));
  }
</script>
@endpush
