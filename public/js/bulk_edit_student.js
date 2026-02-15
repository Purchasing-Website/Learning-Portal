// =========================
// Student Bulk Selection + Bulk Edit (FIXED)
// =========================

(function () {

  const selectedIds = new Set();
  let table = null;

  function updateBulkBar() {
    const count = selectedIds.size;
    const countEl = document.getElementById('selectedCount');
    const barEl = document.getElementById('bulkBar');

    if (countEl) countEl.textContent = count;
    if (barEl) barEl.style.display = count > 0 ? 'flex' : 'none';
  }

  function setRowVisualState($tr, checked) {
    $tr.toggleClass('is-selected', checked);
    $tr.find('.row-check').prop('checked', checked);
  }

  function escapeHtml(str) {
    return String(str)
      .replaceAll('&', '&amp;')
      .replaceAll('<', '&lt;')
      .replaceAll('>', '&gt;')
      .replaceAll('"', '&quot;')
      .replaceAll("'", '&#039;');
  }

  // STRICT: check selectAll only if ALL visible rows are selected
  function syncSelectAllStrict() {
    const $selectAll = $('#selectAll');
    const $visibleRows = $('#example tbody tr');

    if ($selectAll.length === 0) return;

    if ($visibleRows.length === 0) {
      $selectAll.prop('checked', false).prop('indeterminate', false);
      return;
    }

    let checkedCount = 0;
    $visibleRows.each(function () {
      const id = $(this).attr('data-student-id');
      if (id && selectedIds.has(id)) checkedCount++;
    });

    const allChecked = checkedCount === $visibleRows.length;
    $selectAll.prop('checked', allChecked).prop('indeterminate', false);
  }

  function clearAllSelectionUI() {
    // Clear state
    selectedIds.clear();

    // Clear visible checkboxes + highlight
    $('#example tbody tr').each(function () {
      setRowVisualState($(this), false);
    });

    // Clear select-all
    $('#selectAll').prop('checked', false).prop('indeterminate', false);

    updateBulkBar();
  }

  $(document).ready(function () {

    // =========================
    // 1) Init DataTables ONCE
    // =========================
    table = $('#example').DataTable({
      pageLength: 10,
      order: [[1, 'asc']], // user id column (0 is checkbox)
      columnDefs: [
        { orderable: false, targets: [0, 9] } // checkbox + action not sortable
      ]
    });

    // =========================
    // 2) Apply selection state after redraw (search/pagination)
    // =========================
    table.on('draw', function () {
      $('#example tbody tr').each(function () {
        const id = $(this).attr('data-student-id');
        setRowVisualState($(this), id && selectedIds.has(id));
      });

      syncSelectAllStrict();
      updateBulkBar();
    });

    // =========================
    // 3) Row checkbox click (delegated)
    // =========================
    $(document).off('click.bulk', '#example tbody .row-check');
    $(document).on('click.bulk', '#example tbody .row-check', function (e) {
      e.stopPropagation();

      const $tr = $(this).closest('tr');
      const id = $tr.attr('data-student-id');
      if (!id) return;

      const checked = $(this).is(':checked');
      if (checked) selectedIds.add(id);
      else selectedIds.delete(id);

      setRowVisualState($tr, checked);

      syncSelectAllStrict();
      updateBulkBar();
    });

    // =========================
    // 4) Click row toggles selection (but NOT when clicking action buttons/icons)
    // =========================
    $(document).off('click.bulk', '#example tbody tr');
    $(document).on('click.bulk', '#example tbody tr', function (e) {
      // If click is on action area / interactive element, ignore
      if ($(e.target).closest('.no-row-toggle, button, a, i, input').length > 0) return;

      const $tr = $(this);
      const id = $tr.attr('data-student-id');
      if (!id) return;

      const willSelect = !selectedIds.has(id);

      if (willSelect) selectedIds.add(id);
      else selectedIds.delete(id);

      setRowVisualState($tr, willSelect);

      syncSelectAllStrict();
      updateBulkBar();
    });

    // =========================
    // 5) Select All change (delegated)
    // Works even if #selectAll is outside table
    // =========================
    $(document).off('change.bulk', '#selectAll');
    $(document).on('change.bulk', '#selectAll', function () {
      const check = $(this).is(':checked');

      $('#example tbody tr').each(function () {
        const $tr = $(this);
        const id = $tr.attr('data-student-id');
        if (!id) return;

        if (check) selectedIds.add(id);
        else selectedIds.delete(id);

        setRowVisualState($tr, check);
      });

      syncSelectAllStrict();
      updateBulkBar();
    });

    // =========================
    // 6) Clear selection button
    // =========================
    $(document).off('click.bulk', '#btnClearSelection');
    $(document).on('click.bulk', '#btnClearSelection', function () {
      clearAllSelectionUI();
    });

    // =========================
    // 7) Bulk Edit Apply
    // Fields:
    // - Status: #bulkStatus
    // - User Level: #bulkStatus-1
    // =========================
    $(document).off('click.bulk', '#btnApplyBulkEdit');
    $(document).on('click.bulk', '#btnApplyBulkEdit', function () {

      const statusValRaw = ($('#bulkStatus').val() || '').trim();
      const levelValRaw  = ($('#bulkStatus-1').val() || '').trim();

      const statusVal = (!statusValRaw || statusValRaw === 'selected') ? '' : statusValRaw;
      const levelVal  = (!levelValRaw  || levelValRaw  === 'selected') ? '' : levelValRaw;

      if (selectedIds.size === 0) {
        alert('No student selected.');
        return;
      }

      if (!statusVal && !levelVal) {
        alert('No changes selected. Please choose at least one field to update.');
        return;
      }

      const idsArr = Array.from(selectedIds);
      const idsText = idsArr.join(', ');

      const statusText = statusVal ? $('#bulkStatus option:selected').text().trim() : '';
      const levelText  = levelVal  ? $('#bulkStatus-1 option:selected').text().trim() : '';

      let changeLines = '';
      if (statusVal) changeLines += `• Status -> ${statusText}\n`;
      if (levelVal)  changeLines += `• User Level -> ${levelText}\n`;

      const msg =
        `You are about to update ${selectedIds.size} selected student(s):\n\n` +
        `Selected User ID(s):\n${idsText}\n\n` +
        `${changeLines}\nProceed?`;

      const ok = window.confirm(msg);
      if (!ok) return;

      // =========================
      // UI update (Status only, because your table screenshot has Status column but no User Level column)
      // Columns: 0 checkbox, 1 userId, 2 email, 3 first, 4 last, 5 gender, 6 phone, 7 signup, 8 status, 9 action
      // =========================
      const STATUS_COL_INDEX = 8;

      table.rows().every(function () {
        const rowNode = this.node();
        const id = $(rowNode).attr('data-student-id');
        if (!id || !selectedIds.has(id)) return;

        const rowData = this.data();

        if (statusVal) {
          rowData[STATUS_COL_INDEX] = `<span class="text-center d-block">${escapeHtml(statusText)}</span>`;
        }

        // If you later add a "User Level" column, tell me the column index and I’ll add update here.

        this.data(rowData);
      });

      table.draw(false);

      // Show success alert
      $('#successMessage').removeClass('d-none');

      // Reset modal fields
      $('#bulkStatus').val('');
      $('#bulkStatus-1').val('');

      // Close modal if Bootstrap modal instance exists
      const modalEl = document.getElementById('BulkEditModal');
      const modal = modalEl ? bootstrap.Modal.getInstance(modalEl) : null;
      if (modal) modal.hide();

      // ✅ After apply: unselect all
      clearAllSelectionUI();
    });

    // Initial sync
    updateBulkBar();
    syncSelectAllStrict();
  });

});
