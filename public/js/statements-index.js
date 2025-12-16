
  let currentStatementId = null;
  // Data initialized in Blade template

  // Helper function for date formatting
  function formatDate(dateStr) {
    if (!dateStr) return '-';
    const date = new Date(dateStr);
    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    return `${date.getDate()}-${months[date.getMonth()]}-${String(date.getFullYear()).slice(-2)}`;
  }

  // Helper function for number formatting
  function formatNumber(num) {
    if (!num && num !== 0) return '-';
    return parseFloat(num).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
  }

  // Open statement details (full page view) - MUST be defined before HTML onclick handlers
  async function openStatementDetails(id) {
    try {
      const res = await fetch(`/statements/${id}`, {
        headers: {
          'Accept': 'application/json',
          'X-Requested-With': 'XMLHttpRequest'
        }
      });
      if (!res.ok) throw new Error(`HTTP ${res.status}`);
      const statement = await res.json();
      currentStatementId = id;
      
      // Get all required elements
      const statementPageName = document.getElementById('statementPageName');
      const statementPageTitle = document.getElementById('statementPageTitle');
      const clientsTableView = document.getElementById('clientsTableView');
      const statementPageView = document.getElementById('statementPageView');
      const statementDetailsPageContent = document.getElementById('statementDetailsPageContent');
      const statementFormPageContent = document.getElementById('statementFormPageContent');
      const editStatementFromPageBtn = document.getElementById('editStatementFromPageBtn');
      const closeStatementPageBtn = document.getElementById('closeStatementPageBtn');
      
      if (!statementPageName || !statementPageTitle || !clientsTableView || !statementPageView || 
          !statementDetailsPageContent || !statementFormPageContent) {
        console.error('Required elements not found');
        alert('Error: Page elements not found');
        return;
      }
      
      // Set statement name in header
      const statementName = statement.statement_no || 'Unknown';
      statementPageName.textContent = statementName;
      statementPageTitle.textContent = 'Statement';
      
      populateStatementDetails(statement);
      
      // Hide table view, show page view
      clientsTableView.classList.add('hidden');
      statementPageView.style.display = 'block';
      statementPageView.classList.add('show');
      statementDetailsPageContent.style.display = 'block';
      statementFormPageContent.style.display = 'none';
      if (editStatementFromPageBtn) editStatementFromPageBtn.style.display = 'inline-block';
      if (closeStatementPageBtn) closeStatementPageBtn.style.display = 'inline-block';
    } catch (e) {
      console.error(e);
      alert('Error loading statement details: ' + e.message);
    }
  }

  // Populate statement details view
  function populateStatementDetails(statement) {
    const content = document.getElementById('statementDetailsContent');
    if (!content) return;

    const col1 = `
      <div class="detail-section">
        <div class="detail-section-header">STATEMENT DETAILS</div>
        <div class="detail-section-body">
          <div class="detail-row">
            <span class="detail-label">Statement No</span>
            <div class="detail-value">${statement.statement_no || '-'}</div>
          </div>
          <div class="detail-row">
            <span class="detail-label">Year</span>
            <div class="detail-value">${statement.year || '-'}</div>
          </div>
          <div class="detail-row">
            <span class="detail-label">Insurer</span>
            <div class="detail-value">${statement.insurer ? statement.insurer.name : '-'}</div>
          </div>
          <div class="detail-row">
            <span class="detail-label">Business Category</span>
            <div class="detail-value">${statement.business_category || '-'}</div>
          </div>
        </div>
      </div>
    `;

    const col2 = `
      <div class="detail-section">
        <div class="detail-section-header">PAYMENT INFO</div>
        <div class="detail-section-body">
          <div class="detail-row">
            <span class="detail-label">Date Received</span>
            <div class="detail-value">${formatDate(statement.date_received)}</div>
          </div>
          <div class="detail-row">
            <span class="detail-label">Amount Received</span>
            <div class="detail-value">${formatNumber(statement.amount_received)}</div>
          </div>
          <div class="detail-row">
            <span class="detail-label">Mode Of Payment</span>
            <div class="detail-value">${statement.mode_of_payment ? statement.mode_of_payment.name : (statement.modeOfPayment ? statement.modeOfPayment.name : '-')}</div>
          </div>
        </div>
      </div>
    `;

    const col3 = `
      <div class="detail-section">
        <div class="detail-section-header">ADDITIONAL INFO</div>
        <div class="detail-section-body">
          <div class="detail-row" style="align-items:flex-start;">
            <span class="detail-label">Remarks</span>
            <textarea class="detail-value" style="min-height:60px; resize:vertical; flex:1; font-size:11px; padding:4px 6px;" readonly>${statement.remarks || ''}</textarea>
          </div>
        </div>
      </div>
    `;

    const col4 = `
    `;

    content.innerHTML = col1 + col2 + col3 + col4;
  }

  // Open statement page (Add or Edit)
  async function openStatementPage(mode) {
    if (mode === 'add') {
      openStatementForm('add');
    } else {
      if (currentStatementId) {
        openEditStatement(currentStatementId);
      }
    }
  }

  // Add Statement Button
  document.getElementById('addStatementBtn').addEventListener('click', () => openStatementModal('add'));
  document.getElementById('columnBtn2').addEventListener('click', () => openColumnModal());

  async function openEditStatement(id) {
    try {
      const res = await fetch(`/statements/${id}/edit`, {
        headers: {
          'Accept': 'application/json',
          'X-Requested-With': 'XMLHttpRequest'
        }
      });
      if (!res.ok) throw new Error('Network error');
      const statement = await res.json();
      currentStatementId = id;
      openStatementForm('edit', statement);
    } catch (e) {
      console.error(e);
      alert('Error loading statement data');
    }
  }

  function openStatementForm(mode, statement = null) {
    // Clone form from modal
    const modalForm = document.getElementById('statementModal').querySelector('form');
    const pageForm = document.getElementById('statementPageForm');
    const formContentDiv = pageForm.querySelector('div[style*="padding:12px"]');
    
    // Clone the modal form body
    const modalBody = modalForm.querySelector('.modal-body');
    if (modalBody && formContentDiv) {
      formContentDiv.innerHTML = modalBody.innerHTML;
    }

    const formMethod = document.getElementById('statementPageFormMethod');
    const deleteBtn = document.getElementById('statementDeleteBtn');
    const editBtn = document.getElementById('editStatementFromPageBtn');
    const closeBtn = document.getElementById('closeStatementPageBtn');
    const closeFormBtn = document.getElementById('closeStatementFormBtn');

    if (mode === 'add') {
      document.getElementById('statementPageTitle').textContent = 'Add Statement';
      document.getElementById('statementPageName').textContent = '';
      pageForm.action = statementsStoreRoute;
      formMethod.innerHTML = '';
      deleteBtn.style.display = 'none';
      if (editBtn) editBtn.style.display = 'none';
      if (closeBtn) closeBtn.style.display = 'inline-block';
      if (closeFormBtn) closeFormBtn.style.display = 'none';
      pageForm.reset();
    } else {
      const statementName = statement.statement_no || 'Unknown';
      document.getElementById('statementPageTitle').textContent = 'Edit Statement';
      document.getElementById('statementPageName').textContent = statementName;
      pageForm.action = `/statements/${currentStatementId}`;
      const methodInput = document.createElement('input');
      methodInput.type = 'hidden';
      methodInput.name = '_method';
      methodInput.value = 'PUT';
      formMethod.innerHTML = '';
      formMethod.appendChild(methodInput);
      deleteBtn.style.display = 'inline-block';
      if (editBtn) editBtn.style.display = 'none';
      if (closeBtn) closeBtn.style.display = 'none';
      if (closeFormBtn) closeFormBtn.style.display = 'inline-block';

      const fields = ['year','insurer_id','business_category','date_received','amount_received','mode_of_payment_id','remarks'];
      fields.forEach(k => {
        const el = formContentDiv ? formContentDiv.querySelector(`#${k}`) : null;
        if (!el) return;
        if (el.type === 'date') {
          el.value = statement[k] ? (typeof statement[k] === 'string' ? statement[k].substring(0,10) : statement[k]) : '';
        } else if (el.tagName === 'TEXTAREA') {
          el.value = statement[k] ?? '';
        } else {
          el.value = statement[k] ?? '';
        }
      });
    }

    // Hide table view, show page view
    document.getElementById('clientsTableView').classList.add('hidden');
    const statementPageView = document.getElementById('statementPageView');
    statementPageView.style.display = 'block';
    statementPageView.classList.add('show');
    document.getElementById('statementDetailsPageContent').style.display = 'none';
    document.getElementById('statementFormPageContent').style.display = 'block';
  }

  function closeStatementPageView() {
    const statementPageView = document.getElementById('statementPageView');
    statementPageView.classList.remove('show');
    statementPageView.style.display = 'none';
    document.getElementById('clientsTableView').classList.remove('hidden');
    document.getElementById('statementDetailsPageContent').style.display = 'none';
    document.getElementById('statementFormPageContent').style.display = 'none';
    currentStatementId = null;
  }

  // Edit button from details page
  const editBtn = document.getElementById('editStatementFromPageBtn');
  if (editBtn) {
    editBtn.addEventListener('click', function() {
      if (currentStatementId) {
        openEditStatement(currentStatementId);
      }
    });
  }

  function filterByInsurer(insurer) {
    window.location.href = `${statementsIndexRoute}?insurer=${insurer}`;
  }

  // Column modal functions
  function openColumnModal() {
    document.getElementById('tableResponsive').classList.add('no-scroll');
    document.querySelectorAll('.column-checkbox').forEach(cb => {
      // Always check mandatory fields, otherwise check if in selectedColumns
      cb.checked = mandatoryColumns.includes(cb.value) || selectedColumns.includes(cb.value);
    });
    document.body.style.overflow = 'hidden';
    document.getElementById('columnModal').classList.add('show');
    // Initialize drag and drop after modal is shown
    setTimeout(initDragAndDrop, 100);
  }

  function closeColumnModal() {
    document.getElementById('tableResponsive').classList.remove('no-scroll');
    document.getElementById('columnModal').classList.remove('show');
    document.body.style.overflow = '';
  }

  function selectAllColumns() {
    document.querySelectorAll('.column-checkbox').forEach(cb => {
      cb.checked = true;
    });
  }

  function deselectAllColumns() {
    document.querySelectorAll('.column-checkbox').forEach(cb => {
      // Don't uncheck mandatory fields
      if (!mandatoryColumns.includes(cb.value)) {
        cb.checked = false;
      }
    });
  }

  function saveColumnSettings() {
    // Mandatory fields that should always be included
    const mandatoryFields = mandatoryColumns;

    // Get order from DOM - this preserves the drag and drop order
    const items = Array.from(document.querySelectorAll('#columnSelection .column-item'));
    const order = items.map(item => item.dataset.column);
    const checked = Array.from(document.querySelectorAll('.column-checkbox:checked')).map(n => n.value);

    // Ensure mandatory fields are always included
    mandatoryFields.forEach(field => {
      if (!checked.includes(field)) {
        checked.push(field);
      }
    });

    // Maintain order of checked items based on DOM order (drag and drop order)
    const orderedChecked = order.filter(col => checked.includes(col));

    const form = document.getElementById('columnForm');
    const existing = form.querySelectorAll('input[name="columns[]"]');
    existing.forEach(e => e.remove());

    // Add columns in the order they appear in the DOM (after drag and drop)
    orderedChecked.forEach(c => {
      const i = document.createElement('input');
      i.type = 'hidden';
      i.name = 'columns[]';
      i.value = c;
      form.appendChild(i);
    });

    form.submit();
  }

  function deleteStatement() {
    if (!currentStatementId) return;
    if (!confirm('Delete this statement?')) return;
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = `/statements/${currentStatementId}`;
    const csrf = document.createElement('input');
    csrf.type = 'hidden';
    csrf.name = '_token';
    csrf.value = csrfToken;
    form.appendChild(csrf);
    const method = document.createElement('input');
    method.type = 'hidden';
    method.name = '_method';
    method.value = 'DELETE';
    form.appendChild(method);
    document.body.appendChild(form);
    form.submit();
  }

  function openStatementModal(mode, statementId = null) {
    const modal = document.getElementById('statementModal');
    const form = document.getElementById('statementForm');
    const formMethod = document.getElementById('statementFormMethod');
    const modalTitle = document.getElementById('statementModalTitle');
    const deleteBtn = document.getElementById('statementDeleteBtn');
    
    if (mode === 'add') {
      modalTitle.textContent = 'Add Statement';
      form.reset();
      form.action = statementsStoreRoute;
      formMethod.innerHTML = '';
      deleteBtn.style.display = 'none';
      currentStatementId = null;
    } else if (mode === 'edit' && statementId) {
      modalTitle.textContent = 'Edit Statement';
      form.action = statementsUpdateRouteTemplate.replace(':id', statementId);
      formMethod.innerHTML = '@method("PUT")';
      deleteBtn.style.display = 'inline-block';
      currentStatementId = statementId;
      
      // Fetch statement data
      fetch(`/statements/${statementId}/edit`, {
        headers: {
          'Accept': 'application/json',
          'X-Requested-With': 'XMLHttpRequest'
        }
      })
        .then(response => response.json())
        .then(data => {
          if (data.statement) {
            const s = data.statement;
            document.getElementById('year').value = s.year || '';
            document.getElementById('insurer_id').value = s.insurer_id || '';
            document.getElementById('business_category').value = s.business_category || '';
            document.getElementById('date_received').value = s.date_received ? s.date_received.split('T')[0] : '';
            document.getElementById('amount_received').value = s.amount_received || '';
            document.getElementById('mode_of_payment_id').value = s.mode_of_payment_id || '';
            document.getElementById('remarks').value = s.remarks || '';
          }
        })
        .catch(error => {
          console.error('Error fetching statement data:', error);
          alert('Error loading statement data');
        });
    }
    
    modal.classList.add('show');
    document.body.style.overflow = 'hidden';
  }

  function closeStatementModal() {
    const modal = document.getElementById('statementModal');
    modal.classList.remove('show');
    document.body.style.overflow = '';
    const form = document.getElementById('statementForm');
    form.reset();
    currentStatementId = null;
  }

  // Close modal on outside click
  document.getElementById('statementModal').addEventListener('click', function(e) {
    if (e.target === this) {
      closeStatementModal();
    }
  });

  // Handle form submission
  document.getElementById('statementForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const form = this;
    const formData = new FormData(form);
    const url = form.action;
    const method = form.querySelector('[name="_method"]') ? form.querySelector('[name="_method"]').value : 'POST';
    
    if (method !== 'POST') {
      formData.append('_method', method);
    }
    
    fetch(url, {
      method: 'POST',
      body: formData,
      headers: {
        'X-Requested-With': 'XMLHttpRequest',
        'Accept': 'application/json'
      }
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        closeStatementModal();
        window.location.reload();
      } else {
        alert(data.message || 'Error saving statement');
      }
    })
    .catch(error => {
      console.error('Error:', error);
      alert('Error saving statement');
    });
  });

  // Update openStatementDetails to open modal in edit mode
  const originalOpenStatementDetails = window.openStatementDetails;
  window.openStatementDetails = function(id) {
    openStatementModal('edit', id);
  };

  // Only declare if not already declared (to avoid duplicate declaration errors)
  if (typeof draggedElement === 'undefined') {
    var draggedElement = null;
  }
  if (typeof dragOverElement === 'undefined') {
    var dragOverElement = null;
  }

  // Initialize drag and drop when column modal opens
  let dragInitialized = false;

  function initDragAndDrop() {
    const columnSelection = document.getElementById('columnSelection');
    if (!columnSelection) return;

    // Only initialize once to avoid duplicate event listeners
    if (dragInitialized) {
      // Re-enable draggable on all items
      const columnItems = columnSelection.querySelectorAll('.column-item');
      columnItems.forEach(item => {
        item.setAttribute('draggable', 'true');
      });
      return;
    }

    // Make all column items draggable
    const columnItems = columnSelection.querySelectorAll('.column-item');

    columnItems.forEach(item => {
      // Ensure draggable attribute is set
      item.setAttribute('draggable', 'true');
      item.style.cursor = 'move';

      // Drag start
      item.addEventListener('dragstart', function(e) {
        draggedElement = this;
        this.classList.add('dragging');
        e.dataTransfer.effectAllowed = 'move';
        e.dataTransfer.setData('text/plain', ''); // Required for Firefox
        // Create a ghost image
        const dragImage = this.cloneNode(true);
        dragImage.style.opacity = '0.5';
        document.body.appendChild(dragImage);
        e.dataTransfer.setDragImage(dragImage, 0, 0);
        setTimeout(() => {
          if (document.body.contains(dragImage)) {
            document.body.removeChild(dragImage);
          }
        }, 0);
      });

      // Drag end
      item.addEventListener('dragend', function(e) {
        this.classList.remove('dragging');
        if (dragOverElement) {
          dragOverElement.classList.remove('drag-over');
          dragOverElement = null;
        }
        draggedElement = null;
      });

      // Drag over
      item.addEventListener('dragover', function(e) {
        e.preventDefault();
        e.dataTransfer.dropEffect = 'move';

        if (draggedElement && this !== draggedElement) {
          if (dragOverElement && dragOverElement !== this) {
            dragOverElement.classList.remove('drag-over');
          }

          this.classList.add('drag-over');
          dragOverElement = this;

          const rect = this.getBoundingClientRect();
          const next = (e.clientY - rect.top) / (rect.bottom - rect.top) > 0.5;

          if (next) {
            if (this.nextSibling && this.nextSibling !== draggedElement) {
              this.parentNode.insertBefore(draggedElement, this.nextSibling);
            } else if (!this.nextSibling) {
              this.parentNode.appendChild(draggedElement);
            }
          } else {
            if (this.previousSibling !== draggedElement) {
              this.parentNode.insertBefore(draggedElement, this);
            }
          }
        }
      });

      // Drag leave
      item.addEventListener('dragleave', function(e) {
        if (!this.contains(e.relatedTarget)) {
          this.classList.remove('drag-over');
          if (dragOverElement === this) {
            dragOverElement = null;
          }
        }
      });

      // Drop
      item.addEventListener('drop', function(e) {
        e.preventDefault();
        e.stopPropagation();
        this.classList.remove('drag-over');
        dragOverElement = null;
        return false;
      });
    });

    dragInitialized = true;
  }
