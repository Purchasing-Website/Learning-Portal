document.addEventListener('DOMContentLoaded', () => {
  const form = document.getElementById('enrolForm');

  // Dual list
  const selAvailable = document.getElementById('selAvailable');
  const selEnrolled  = document.getElementById('selEnrolled');

  const btnAdd       = document.getElementById('btnAdd');
  const btnRemove    = document.getElementById('btnRemove');
  const btnAddAll    = document.getElementById('btnAddAll');
  const btnRemoveAll = document.getElementById('btnRemoveAll');

  const availableCount = document.getElementById('availableCount');
  const enrolledCount  = document.getElementById('enrolledCount');

  // ✅ NEW: User Level dropdown (your new HTML id)
  const selUserLevel = document.getElementById('selUserLevel');

  // Titles (reuse your UI)
  const leftTitle  = document.getElementById('leftTitle');
  const rightTitle = document.getElementById('rightTitle');

  // Hidden payloads (keep compatible; ok if some are removed in your new HTML)
  const studentIds = document.getElementById('studentIds'); // used to submit selected right list
  const payloadUserLevelId = document.getElementById('payloadUserLevelId'); // optional hidden field

  // ====== Mock master data (replace with backend later) ======
  const userLevels = [
    { id: '4', name: '弟子' },
    { id: '3', name: '代理' },
    { id: '2', name: '会员' },
    { id: '0', name: '免费用户' },
  ];

  const students = [
    { id: '00001', name: 'Khye Shen (00001)' },
    { id: '00002', name: 'Jackson (00002)' },
    { id: '00003', name: 'Chong Wei (00003)' },
    { id: '00004', name: 'Jennie (00004)' },
    { id: '00005', name: 'Jacky (00005)' },
  ];

  // ====== User Level assignment store ======
  // levelId -> Set(studentId)
  const levelToStudents = new Map();
  userLevels.forEach(lv => levelToStudents.set(String(lv.id), new Set()));

  // Seed demo data (example: some students already assigned)
  upsertLevelAssignment('4', ['00001']); // 弟子: 00001
  upsertLevelAssignment('3', ['00002']); // 代理: 00002
  upsertLevelAssignment('2', ['00003', '00004']); // 会员: 00003, 00004
  // 00005 is unassigned (available everywhere except when selected level contains it)

  function upsertLevelAssignment(levelId, studentIdList) {
    const lvId = String(levelId);
    if (!levelToStudents.has(lvId)) levelToStudents.set(lvId, new Set());
    const set = levelToStudents.get(lvId);

    studentIdList.forEach(sid => set.add(String(sid)));
    levelToStudents.set(lvId, set);
  }

  // ====== Utilities (kept from your original style) ======
  function clearSelect(selectEl) {
    selectEl.options.length = 0;
  }

  function addOptions(selectEl, items, getText, getValue) {
    const frag = document.createDocumentFragment();
    items.forEach(it => frag.appendChild(new Option(getText(it), getValue(it))));
    selectEl.appendChild(frag);
  }

  function sortSelect(selectEl) {
    const options = Array.from(selectEl.options);
    options.sort((a, b) => a.text.localeCompare(b.text, undefined, { sensitivity: 'base' }));
    selectEl.options.length = 0;
    options.forEach(opt => selectEl.add(opt));
  }

  function updateButtons() {
    btnAdd.disabled    = selAvailable.selectedOptions.length === 0;
    btnRemove.disabled = selEnrolled.selectedOptions.length === 0;

    btnAddAll.disabled    = selAvailable.options.length === 0;
    btnRemoveAll.disabled = selEnrolled.options.length === 0;
  }

  function updateCounts() {
    availableCount.textContent = `${selAvailable.options.length} available`;
    enrolledCount.textContent  = `${selEnrolled.options.length} assigned`;
  }

  function updateUI() {
    updateButtons();
    updateCounts();
  }

  function getCurrentUserLevelId() {
    return String(selUserLevel?.value || '');
  }

  // ✅ Ensure one student can belong to ONLY ONE user level
  function removeStudentFromAllLevels(studentId) {
    const sid = String(studentId);
    levelToStudents.forEach((set) => set.delete(sid));
  }

  // ====== Core: refresh the dual list by selected User Level ======
  function refreshDualList() {
    const levelId = getCurrentUserLevelId();
    if (!levelId) {
      clearSelect(selAvailable);
      clearSelect(selEnrolled);
      updateUI();
      return;
    }

    clearSelect(selAvailable);
    clearSelect(selEnrolled);

    const assignedSet = levelToStudents.get(levelId) || new Set();

    const assignedStudents = students.filter(s => assignedSet.has(String(s.id)));
    const availableStudents = students.filter(s => !assignedSet.has(String(s.id)));

    addOptions(selAvailable, availableStudents, s => s.name, s => String(s.id));
    addOptions(selEnrolled,  assignedStudents,  s => s.name, s => String(s.id));

    sortSelect(selAvailable);
    sortSelect(selEnrolled);
    updateUI();
  }

  // ====== Assign/Unassign functions (adapted from your original) ======
  function addEnrollment(values) {
    const levelId = getCurrentUserLevelId();
    if (!levelId) return;

    const set = levelToStudents.get(levelId) || new Set();

    values.forEach(studentId => {
      const sid = String(studentId);

      // one student one level
      removeStudentFromAllLevels(sid);

      set.add(sid);
    });

    levelToStudents.set(levelId, set);
  }

  function removeEnrollment(values) {
    const levelId = getCurrentUserLevelId();
    if (!levelId) return;

    const set = levelToStudents.get(levelId) || new Set();

    values.forEach(studentId => {
      set.delete(String(studentId));
    });

    levelToStudents.set(levelId, set);
  }

  function moveSelected(from, direction) {
    const selected = Array.from(from.selectedOptions).map(o => o.value);
    if (selected.length === 0) return;

    if (direction === 'add') addEnrollment(selected);
    if (direction === 'remove') removeEnrollment(selected);

    refreshDualList();
  }

  function moveAll(from, direction) {
    const all = Array.from(from.options).map(o => o.value);
    if (all.length === 0) return;

    if (direction === 'add') addEnrollment(all);
    if (direction === 'remove') removeEnrollment(all);

    refreshDualList();
  }

  // ====== Init dropdown (NEW) ======
  function initUserLevelDropdown() {
    if (!selUserLevel) {
      console.error('Missing #selUserLevel in HTML');
      return;
    }

    clearSelect(selUserLevel);
    addOptions(selUserLevel, userLevels, lv => lv.name, lv => String(lv.id));

    // default select first level
    selUserLevel.value = String(userLevels[0].id);
  }

  // ====== UI titles (optional but nice) ======
  function applyTitles() {
    leftTitle.textContent = 'Available Students';
    rightTitle.textContent = 'Assigned Students';
  }

  // ====== Events (simplified) ======
  selUserLevel?.addEventListener('change', () => {
    refreshDualList();
  });

  btnAdd.addEventListener('click', () => moveSelected(selAvailable, 'add'));
  btnRemove.addEventListener('click', () => moveSelected(selEnrolled, 'remove'));
  btnAddAll.addEventListener('click', () => moveAll(selAvailable, 'add'));
  btnRemoveAll.addEventListener('click', () => moveAll(selEnrolled, 'remove'));

  selAvailable.addEventListener('change', updateButtons);
  selEnrolled.addEventListener('change', updateButtons);

  selAvailable.addEventListener('dblclick', () => moveSelected(selAvailable, 'add'));
  selEnrolled.addEventListener('dblclick', () => moveSelected(selEnrolled, 'remove'));

  // ====== Submit (mock) ======
  form.addEventListener('submit', (e) => {
    e.preventDefault();

    const levelId = getCurrentUserLevelId();
    const enrolledIds = Array.from(selEnrolled.options).map(o => o.value);

    if (studentIds) studentIds.value = enrolledIds.join(',');
    if (payloadUserLevelId) payloadUserLevelId.value = levelId;

    const levelName = (userLevels.find(l => String(l.id) === levelId)?.name) || levelId;

    alert(
      `Saved (Mock)\n\n` +
      `User Level: ${levelName} (${levelId})\n` +
      `Student IDs: ${enrolledIds.join(', ') || '(none)'}`
    );
  });

  // ====== Init ======
  initUserLevelDropdown();
  applyTitles();
  refreshDualList();
  updateUI();
});
