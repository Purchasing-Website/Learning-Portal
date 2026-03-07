  /* =========================================================
     JSON moved to JS (dynamic class + user + lessons)
     ========================================================= */
  const CLASS_DATA = window.CLASS_DATA || {
      classId: "CLS-0000",
      className: "Class",
      user: { name: "User" },
      footer: "",
      items: []
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
	let currentIndex = Math.max(0, items.findIndex(it => it.currentLesson === true));

  // ===== Completion store (lessons + knowledge check + quiz) =====
	const COMPLETION_KEY = `lp_completion_${CLASS_DATA.classId}`; // unique per class


  document.addEventListener("DOMContentLoaded", () => {
    // Apply dynamic header values
    classSideEl.textContent = CLASS_DATA.className;
    classMobileEl.textContent = CLASS_DATA.className;
    userNameEl.textContent = CLASS_DATA.user?.name || "User";
    footerEl.textContent = CLASS_DATA.footer || "";

    renderNav();
    openItem(currentIndex);

	nextBtn.addEventListener("click", () => {
	  const cur = items[currentIndex];
	  if (!cur) return;

	  const lessonId = cur.lessonId ?? cur.id;
	  fetch("/lesson/completelesson", {
		method: "POST",
		headers: {
		  "Content-Type": "application/json",
      "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
		},
		body: JSON.stringify({
      lesson_id:parseInt(lessonId.split('-')[1]),
      class_id:parseInt(CLASS_DATA.classId.split('-')[1]),
		})
	  })
		.then((response) => {
		  if (response.ok) {
			console.log("POST success");
		  }
		})
		.catch((error) => {
		  console.error("POST failed:", error);
		});

	  // Only lessons can be completed by Next button
	  if (cur.kind === "lesson") {
		cur.completed = true;    // ✅ updates CLASS_DATA
		renderNav();             // ✅ refresh ✔
	  }

	  if (currentIndex < items.length - 1) {
		openItem(currentIndex + 1);
	  }
	});


    // exitBtn.addEventListener("click", () => {
    //   // Replace with your real logout / redirect
    //   window.location.href = "index.html"; // example
    // });

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
      stage.classList.toggle('text-center', !!isCentered);
    }
<<<<<<< Updated upstream
	
  function openItem(index){
    currentIndex = index;
    const item = items[index];

    // active style
    navEl.querySelectorAll("a").forEach(a => a.classList.remove("active"));
    navEl.querySelector(`a[data-index="${index}"]`)?.classList.add("active");

    // lesson title
    titleEl.textContent = item.title;

    // next visibility
    //nextBtn.style.visibility = (index < items.length - 1) ? "visible" : "hidden";
    nextBtn.innerHTML = index < items.length - 1
    ? `Next <i class="bi bi-chevron-double-down"></i>`
    : `Complete <i class="bi bi-chevron-double-down"></i>`;
    
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
=======
  
      //Set Content Centered
      function setStageCenter(isCentered) {
        stage.classList.toggle('text-center', !!isCentered);
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
      nextBtn.style.visibility = (index < items.length - 1) ? "visible" : "Complete";
  
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
          stageEl.innerHTML = `
            <iframe class="lp-iframe"
                    src="${item.src}"
                    title="${escapeHtml(item.title)}"
                    allowfullscreen
                    allow="fullscreen; autoplay; encrypted-media; picture-in-picture"></iframe>`;
          return;
        }
  
        stageEl.innerHTML = `<div class="text-warning small">Unsupported lesson type: ${escapeHtml(type)}</div>`;
>>>>>>> Stashed changes
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
        stageEl.innerHTML = `
          <iframe class="lp-iframe"
                  src="${item.src}"
                  title="${escapeHtml(item.title)}"
                  allowfullscreen
                  allow="fullscreen; autoplay; encrypted-media; picture-in-picture"></iframe>`;
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
