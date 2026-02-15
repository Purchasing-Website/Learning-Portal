const SAMPLE_DATA = {
    programs: [
    { id: "PRG-01", name: "Lifestyle Program" },
    { id: "PRG-02", name: "Business Program" }
  ],
  courses: [
    { id: "CRS-1001", name: "Feng Shui Basics", category: "Lifestyle", level: "Beginner", programId: "PRG-02" },
    { id: "CRS-1002", name: "Advanced Color Matching", category: "Design", level: "Intermediate", programId: "PRG-01" },
    { id: "CRS-1003", name: "Personal Finance 101", category: "Finance", level: "Beginner", programId: "PRG-01" },
    { id: "CRS-1004", name: "Branding Starter Kit", category: "Business", level: "Beginner", programId: "PRG-02" }
  ],
  userLevels: [
    { id: '4', name: '弟子' },
    { id: '3', name: '代理' },
    { id: '2', name: '会员' },
    { id: '0', name: '免费用户' },
  ]
};

function byId(id) { return document.getElementById(id); }

// function getProgramById(programId) {
//   return SAMPLE_DATA.programs.find(p => p.id === programId);
// }

function escapeHtml(str) {
  return String(str ?? "")
    .replace(/&/g, "&amp;")
    .replace(/</g, "&lt;")
    .replace(/>/g, "&gt;")
    .replace(/"/g, "&quot;")
    .replace(/'/g, "&#039;");
}

function createSearchDropdown(opts) {
  const {
    root,
    input,
    hidden,
    panelSearch,
    listEl,
    getItems,
    itemToLabel,
    itemToMeta,
    onSelect,
  } = opts;

  function open() { root.classList.add('open'); }
  function close(){ root.classList.remove('open'); }

  function render(filterText = "") {
    const ft = filterText.trim().toLowerCase();
    const items = getItems().filter(it => {
      const label = itemToLabel(it).toLowerCase();
      const meta  = itemToMeta(it).toLowerCase();
      return label.includes(ft) || meta.includes(ft);
    });

    listEl.innerHTML = items.length
      ? items.map(it => `
          <div class="dd-item" data-id="${it.id}">
            <div style="flex:1;">
              <div class="fw-semibold">${escapeHtml(itemToLabel(it))}</div>
              <small>${escapeHtml(itemToMeta(it))}</small>
            </div>
            <span class="chip">${escapeHtml(it.id)}</span>
          </div>
        `).join("")
      : `<div class="p-3 text-center small" style="color: rgba(229,231,235,.75);">No results</div>`;
  }

  // open on click/focus
  input.addEventListener('focus', () => {
    if (input.disabled) return;
    render(panelSearch.value || "");
    open();
  });
  input.addEventListener('click', () => {
    if (input.disabled) return;
    render(panelSearch.value || "");
    open();
  });

  // filter inside panel
  panelSearch.addEventListener('input', () => render(panelSearch.value));

  // select item
  listEl.addEventListener('click', (e) => {
    const itemEl = e.target.closest('.dd-item');
    if (!itemEl) return;

    const id = itemEl.getAttribute('data-id');
    const item = getItems().find(x => x.id === id);
    if (!item) return;

    hidden.value = item.id;
    input.value  = itemToLabel(item);
    input.classList.remove('is-invalid');
    close();
    onSelect?.(item);
  });

  // close on outside click
  document.addEventListener('click', (e) => {
    if (!root.contains(e.target)) close();
  });

  return {
    open,
    close,
    render,
    clear: () => {
      hidden.value = "";
      input.value = "";
      panelSearch.value = "";
      render("");
    }
  };
}

function initClassOffcanvas(suffix, cfg) {
  const offcanvasEl = byId(cfg.offcanvasId);
  const formEl      = byId(cfg.formId);
  const resultEl    = byId(cfg.resultId);

  if (!offcanvasEl || !formEl) {
    console.warn(`[class_offcanvas] Skip init (missing offcanvas/form):`, cfg.offcanvasId, cfg.formId);
    return;
  }

  // Core course dropdown nodes
  const courseDDRoot = byId(`courseDD${suffix}`);
  const courseInput  = byId(`courseInput${suffix}`);
  const courseId     = byId(`courseId${suffix}`);
  const courseSearch = byId(`courseSearch${suffix}`);
  const courseList   = byId(`courseList${suffix}`);

  // ✅ User level dropdown nodes
  const userLevelDDRoot  = byId(`userLevelDD${suffix}`);
  const userLevelInput   = byId(`userLevelInput${suffix}`);
  const userLevelId      = byId(`userLevelId${suffix}`);
  const userLevelSearch  = byId(`userLevelSearch${suffix}`);
  const userLevelList    = byId(`userLevelList${suffix}`);

  // ✅ Declare dropdown instances (prevents "already declared" issues)
  let courseDD = null;
  let userLevelDD = null;

  const requiredForDropdown =
    courseDDRoot &&
    courseInput && courseId && courseSearch && courseList &&
    userLevelDDRoot &&
    userLevelInput && userLevelId && userLevelSearch && userLevelList;

  if (!requiredForDropdown) {
    console.warn(`[class_offcanvas] Dropdown nodes missing for suffix "${suffix}". Check IDs in HTML.`);
  } else {

    courseDD = createSearchDropdown({
      root: courseDDRoot,
      input: courseInput,
      hidden: courseId,
      panelSearch: courseSearch,
      listEl: courseList,
      getItems: () => SAMPLE_DATA.courses,
      itemToLabel: (c) => c.name,
      itemToMeta:  (c) => c.programId || "—",
      onSelect: (course) => {
        console.log("Selected course:", course.id, course.name);
      }
    });

    userLevelDD = createSearchDropdown({
      root: userLevelDDRoot,          // ✅ fixed
      input: userLevelInput,
      hidden: userLevelId,
      panelSearch: userLevelSearch,
      listEl: userLevelList,
      getItems: () => SAMPLE_DATA.userLevels,
      itemToLabel: (lv) => lv.name,
      itemToMeta:  (lv) => `ID: ${lv.id}`,
      onSelect: (lv) => {
        console.log("Selected user level:", lv.id, lv.name);
      }
    });

    userLevelDD.render("");
  }

  // Offcanvas open behavior
  offcanvasEl.addEventListener("show.bs.offcanvas", () => {
    if (cfg.resetOnOpen) {
      formEl.reset();
      if (resultEl) resultEl.style.display = "none";

      if (courseId) courseId.value = "";
      if (userLevelId) userLevelId.value = "";

      if (courseInput) courseInput.classList.remove("is-invalid");
      if (userLevelInput) userLevelInput.classList.remove("is-invalid");

      courseDD && courseDD.clear();
      userLevelDD && userLevelDD.clear();
    }
  });
} // ✅ end initClassOffcanvas

// Run after DOM is ready
document.addEventListener("DOMContentLoaded", () => {
  initClassOffcanvas("", {
    mode: "add",
    offcanvasId: "AddClass",
    formId: "classForm",
    resultId: "result",
    resetOnOpen: true,
  });

  initClassOffcanvas("-1", {
    mode: "edit",
    offcanvasId: "EditClass",
    formId: "classForm-1",
    resultId: "editresult",
    resetOnOpen: false,
  });
});
