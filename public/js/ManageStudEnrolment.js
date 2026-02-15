document.addEventListener('DOMContentLoaded', () => {
  const form = document.getElementById('enrolForm');

  const selAvailable = document.getElementById('selAvailable');
  const selEnrolled  = document.getElementById('selEnrolled');

  const btnAdd       = document.getElementById('btnAdd');
  const btnRemove    = document.getElementById('btnRemove');
  const btnAddAll    = document.getElementById('btnAddAll');
  const btnRemoveAll = document.getElementById('btnRemoveAll');

  const availableCount = document.getElementById('availableCount');
  const enrolledCount  = document.getElementById('enrolledCount');

  // ====== 1) Seed test data (Method 3 - DocumentFragment) ======
  const students = [
    { id: 101, name: 'Student A' },
    { id: 102, name: 'Student B' },
    { id: 103, name: 'Student C' },
    { id: 104, name: 'Student D' },
    { id: 105, name: 'Student E' },
    { id: 106, name: 'Student F' },
  ];

  const fragAvail = document.createDocumentFragment();
  students.forEach(s => fragAvail.appendChild(new Option(s.name, s.id)));
  selAvailable.appendChild(fragAvail);

  // Optional: simulate already enrolled (edit class scenario)
  const enrolledTest = [
    { id: 201, name: 'Enrolled item A' },
    { id: 202, name: 'Enrolled item B' },
  ];
  const fragEnrolled = document.createDocumentFragment();
  enrolledTest.forEach(s => fragEnrolled.appendChild(new Option(s.name, s.id)));
  selEnrolled.appendChild(fragEnrolled);

  // ====== 2) Helpers ======
  function optionExists(selectEl, value) {
    return !!selectEl.querySelector(`option[value="${CSS.escape(String(value))}"]`);
  }

  function moveSelected(from, to) {
    const selected = Array.from(from.selectedOptions);

    selected.forEach(opt => {
      // prevent duplicates
      if (!optionExists(to, opt.value)) {
        opt.selected = false;
        to.add(opt); // move node
      }
    });
     
    sortSelect(from);
    sortSelect(to);
    updateUI();
  }

  function moveAll(from, to) {
    const all = Array.from(from.options);

    all.forEach(opt => {
      if (!optionExists(to, opt.value)) {
        opt.selected = false;
        to.add(opt);
      }
    });

    updateUI();
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

  // ====== 3) Wire events ======
  btnAdd.addEventListener('click', () => moveSelected(selAvailable, selEnrolled));
  btnRemove.addEventListener('click', () => moveSelected(selEnrolled, selAvailable));

  btnAddAll.addEventListener('click', () => moveAll(selAvailable, selEnrolled));
  btnRemoveAll.addEventListener('click', () => moveAll(selEnrolled, selAvailable));

  // When selection changes, update button states
  selAvailable.addEventListener('change', updateButtons);
  selEnrolled.addEventListener('change', updateButtons);

  // Double click to quickly move
  selAvailable.addEventListener('dblclick', () => moveSelected(selAvailable, selEnrolled));
  selEnrolled.addEventListener('dblclick', () => moveSelected(selEnrolled, selAvailable));

  // ====== 4) Submit enrolled IDs ======
  form.addEventListener('submit', (e) => {
    // for testing, prevent actual submit
    e.preventDefault();

    const ids = Array.from(selEnrolled.options).map(o => o.value);
    document.getElementById('studentIds').value = ids.join(',');

    // Test output
    console.log('Submitting student_ids:', document.getElementById('studentIds').value);
    alert('Saved! student_ids = ' + document.getElementById('studentIds').value);
  });

  //sort the option  
  function sortSelect(selectEl) {
      const options = Array.from(selectEl.options);

      options.sort((a, b) =>
        a.text.localeCompare(b.text, undefined, { sensitivity: 'base' })
      );

      // Clear and re-add in sorted order
      selectEl.options.length = 0;
      options.forEach(opt => selectEl.add(opt));
  } 
    
  // init UI
  updateUI();
});
