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

  // New controls
  const selMode = document.getElementById('selMode');
  const selStudentContext = document.getElementById('selStudentContext');
  const selClassContext   = document.getElementById('selClassContext');
  const modeHint = document.getElementById('modeHint');

  // Titles
  const leftTitle  = document.getElementById('leftTitle');
  const rightTitle = document.getElementById('rightTitle');

  // Hidden payloads (safe even if you later remove backend)
  const studentIds = document.getElementById('studentIds');
  const payloadMode = document.getElementById('payloadMode');
  const payloadStudentId = document.getElementById('payloadStudentId');
  const payloadClassId = document.getElementById('payloadClassId');

  // ====== Mock master data ======
  const students = [
    { id: 101, name: 'Student A' },
    { id: 102, name: 'Student B' },
    { id: 103, name: 'Student C' },
    { id: 104, name: 'Student D' },
    { id: 105, name: 'Student E' },
    { id: 106, name: 'Student F' },
  ];

  const classes = [
    { id: 'CLS-1001', name: 'Class A (CLS-1001)' },
    { id: 'CLS-1002', name: 'Class B (CLS-1002)' },
    { id: 'CLS-1003', name: 'Class C (CLS-1003)' },
    { id: 'CLS-1004', name: 'Class D (CLS-1004)' },
  ];

  // ====== Enrollment store ======
  // classId -> Set(studentId)
  const classToStudents = new Map();
  // studentId -> Set(classId)
  const studentToClasses = new Map();

  classes.forEach(c => classToStudents.set(String(c.id), new Set()));
  students.forEach(s => studentToClasses.set(String(s.id), new Set()));

  // seed
  upsertEnrollment('CLS-1001', ['101', '102']);
  upsertEnrollment('CLS-1002', ['103']);

  function upsertEnrollment(classId, studentIdList) {
    const cId = String(classId);
    if (!classToStudents.has(cId)) classToStudents.set(cId, new Set());

    studentIdList.forEach(sid => {
      const sId = String(sid);
      classToStudents.get(cId).add(sId);

      if (!studentToClasses.has(sId)) studentToClasses.set(sId, new Set());
      studentToClasses.get(sId).add(cId);
    });
  }

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
    enrolledCount.textContent  = `${selEnrolled.options.length} enrolled`;
  }

  function updateUI() {
    updateButtons();
    updateCounts();
  }

  function getMode() { return selMode.value; } // class | student
  function getCurrentClassId() { return String(selClassContext.value || ''); }
  function getCurrentStudentId() { return String(selStudentContext.value || ''); }

  function applyModeUI() {
    const mode = getMode();

    if (mode === 'class') {
      selClassContext.disabled = false;
      selStudentContext.disabled = true;

      leftTitle.textContent = 'Available Students';
      rightTitle.textContent = 'Enrolled Students';
      modeHint.textContent = 'Students → Class';
    } else {
      selClassContext.disabled = true;
      selStudentContext.disabled = false;

      leftTitle.textContent = 'Available Classes';
      rightTitle.textContent = 'Enrolled Classes';
      modeHint.textContent = 'Classes → Student';
    }
  }

  function refreshDualList() {
    const mode = getMode();
    clearSelect(selAvailable);
    clearSelect(selEnrolled);

    if (mode === 'class') {
      const classId = getCurrentClassId();
      const enrolledSet = classToStudents.get(classId) || new Set();

      const enrolledStudents = students.filter(s => enrolledSet.has(String(s.id)));
      const availableStudents = students.filter(s => !enrolledSet.has(String(s.id)));

      addOptions(selAvailable, availableStudents, s => s.name, s => String(s.id));
      addOptions(selEnrolled,  enrolledStudents,  s => s.name, s => String(s.id));
    } else {
      const studentId = getCurrentStudentId();
      const enrolledSet = studentToClasses.get(studentId) || new Set();

      const enrolledClasses = classes.filter(c => enrolledSet.has(String(c.id)));
      const availableClasses = classes.filter(c => !enrolledSet.has(String(c.id)));

      addOptions(selAvailable, availableClasses, c => c.name, c => String(c.id));
      addOptions(selEnrolled,  enrolledClasses,  c => c.name, c => String(c.id));
    }

    sortSelect(selAvailable);
    sortSelect(selEnrolled);
    updateUI();
  }

  function addEnrollment(values) {
    const mode = getMode();

    if (mode === 'class') {
      const classId = getCurrentClassId();
      const set = classToStudents.get(classId) || new Set();

      values.forEach(studentId => {
        const sid = String(studentId);
        set.add(sid);
        classToStudents.set(classId, set);

        if (!studentToClasses.has(sid)) studentToClasses.set(sid, new Set());
        studentToClasses.get(sid).add(classId);
      });
    } else {
      const studentId = getCurrentStudentId();
      const set = studentToClasses.get(studentId) || new Set();

      values.forEach(classId => {
        const cid = String(classId);
        set.add(cid);
        studentToClasses.set(studentId, set);

        if (!classToStudents.has(cid)) classToStudents.set(cid, new Set());
        classToStudents.get(cid).add(studentId);
      });
    }
  }

  function removeEnrollment(values) {
    const mode = getMode();

    if (mode === 'class') {
      const classId = getCurrentClassId();
      const set = classToStudents.get(classId) || new Set();

      values.forEach(studentId => {
        const sid = String(studentId);
        set.delete(sid);

        const reverse = studentToClasses.get(sid);
        if (reverse) reverse.delete(classId);
      });

      classToStudents.set(classId, set);
    } else {
      const studentId = getCurrentStudentId();
      const set = studentToClasses.get(studentId) || new Set();

      values.forEach(classId => {
        const cid = String(classId);
        set.delete(cid);

        const reverse = classToStudents.get(cid);
        if (reverse) reverse.delete(studentId);
      });

      studentToClasses.set(studentId, set);
    }
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

  function initDropdowns() {
    clearSelect(selStudentContext);
    addOptions(selStudentContext, students, s => s.name, s => String(s.id));

    clearSelect(selClassContext);
    addOptions(selClassContext, classes, c => c.name, c => String(c.id));

    selClassContext.value = String(classes[0].id);
    selStudentContext.value = String(students[0].id);
  }

  // Events
  selMode.addEventListener('change', () => { applyModeUI(); refreshDualList(); });
  selClassContext.addEventListener('change', () => { if (getMode() === 'class') refreshDualList(); });
  selStudentContext.addEventListener('change', () => { if (getMode() === 'student') refreshDualList(); });

  btnAdd.addEventListener('click', () => moveSelected(selAvailable, 'add'));
  btnRemove.addEventListener('click', () => moveSelected(selEnrolled, 'remove'));
  btnAddAll.addEventListener('click', () => moveAll(selAvailable, 'add'));
  btnRemoveAll.addEventListener('click', () => moveAll(selEnrolled, 'remove'));

  selAvailable.addEventListener('change', updateButtons);
  selEnrolled.addEventListener('change', updateButtons);

  selAvailable.addEventListener('dblclick', () => moveSelected(selAvailable, 'add'));
  selEnrolled.addEventListener('dblclick', () => moveSelected(selEnrolled, 'remove'));

  // Submit (mock)
  form.addEventListener('submit', (e) => {
    e.preventDefault();

    const mode = getMode();
    const enrolledIds = Array.from(selEnrolled.options).map(o => o.value);

    studentIds.value = enrolledIds.join(','); // keep your original hidden input populated
    if (payloadMode) payloadMode.value = mode;

    if (mode === 'class') {
      if (payloadClassId) payloadClassId.value = getCurrentClassId();
      if (payloadStudentId) payloadStudentId.value = '';
      alert(`Saved (Mock)\n\nMode: Enroll by Class\nClass: ${getCurrentClassId()}\nStudent IDs: ${studentIds.value || '(none)'}`);
    } else {
      if (payloadStudentId) payloadStudentId.value = getCurrentStudentId();
      if (payloadClassId) payloadClassId.value = '';
      alert(`Saved (Mock)\n\nMode: Enroll by Student\nStudent: ${getCurrentStudentId()}\nClass IDs: ${studentIds.value || '(none)'}`);
    }
  });

  // Init
  initDropdowns();
  applyModeUI();
  refreshDualList();
});
