document.addEventListener('DOMContentLoaded', () => {
  const data = {
    class_id: "CLS-1001",
    class_title: "Class A",
    lessons: [
      { lesson_id: "L001", title: "Lesson 1", sequence_no: 1 },
      { lesson_id: "L002", title: "Lesson 2", sequence_no: 2 },
      { lesson_id: "L003", title: "Lesson 3", sequence_no: 3 },
      { lesson_id: "L004", title: "Lesson 4", sequence_no: 4 }
    ]
  };

  const list = document.getElementById('lessonList');
  const btnSave = document.getElementById('btnSave');
  const btnReset = document.getElementById('btnReset');

  if (!list || !btnSave || !btnReset) {
    console.error("Missing element(s). Check IDs: lessonList, btnSave, btnReset");
    return;
  }

  let originalOrder = [];

  function getOrder() {
    return [...list.querySelectorAll('.lesson-block')].map(el => el.dataset.id);
  }

  function setBtnEnabled(el, enabled) {
    if (!el) return;
    if ('disabled' in el) el.disabled = !enabled;         // <button>
    el.classList.toggle('disabled', !enabled);            // <a> styled as button
    el.setAttribute('aria-disabled', (!enabled).toString());
    if (!enabled) el.setAttribute('tabindex', '-1');
    else el.removeAttribute('tabindex');
  }

  function setDirty(isDirty) {
    setBtnEnabled(btnSave, isDirty);
    setBtnEnabled(btnReset, isDirty);
  }

  function render() {
    const lessons = [...data.lessons].sort((a, b) => a.sequence_no - b.sequence_no);

    list.innerHTML = lessons.map(l => `
      <div class="lesson-block" data-id="${l.lesson_id}">
        ${l.title}
      </div>
    `).join('');

    originalOrder = getOrder();
    setDirty(false);
  }

  render();

  new Sortable(list, {
    animation: 150,
    onStart: () => setDirty(true),
    onEnd: () => {
      setDirty(true);
    }
  });

  btnReset.addEventListener('click', () => {
    const map = new Map([...list.querySelectorAll('.lesson-block')].map(el => [el.dataset.id, el]));
    list.innerHTML = '';
    originalOrder.forEach(id => list.appendChild(map.get(id)));
    setDirty(false);
  });

      btnSave.addEventListener('click', () => {
      const payload = {
        class_id: data.class_id,
        lesson_order: getOrder()
      };

      // Dialog box for testing
      alert("Submitted payload:\n\n" + JSON.stringify(payload, null, 2));

      // Pretend saved successfully:
      originalOrder = getOrder();
      setDirty(false);

      btnSave.textContent = "Saved ✓";
      setTimeout(() => (btnSave.textContent = "Save Order"), 900);
    });
});
