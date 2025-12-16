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

  // Open payment plan details (full page view) - MUST be defined before HTML onclick handlers
  async function openPaymentPlanDetails(id) {
    try {
      const res = await fetch(`/payment-plans/${id}`, {
        headers: {
          'Accept': 'application/json',
          'X-Requested-With': 'XMLHttpRequest'
        }
      });
      if (!res.ok) throw new Error(`HTTP ${res.status}`);
      const plan = await res.json();
      currentPaymentPlanId = id;
      
      // Get all required elements
      const paymentPlanPageName = document.getElementById('paymentPlanPageName');
      const paymentPlanPageTitle = document.getElementById('paymentPlanPageTitle');
      const clientsTableView = document.getElementById('clientsTableView');
      const paymentPlanPageView = document.getElementById('paymentPlanPageView');
      const paymentPlanDetailsPageContent = document.getElementById('paymentPlanDetailsPageContent');
      const paymentPlanFormPageContent = document.getElementById('paymentPlanFormPageContent');
      const editPaymentPlanFromPageBtn = document.getElementById('editPaymentPlanFromPageBtn');
      const closePaymentPlanPageBtn = document.getElementById('closePaymentPlanPageBtn');
      
      if (!paymentPlanPageName || !paymentPlanPageTitle || !clientsTableView || !paymentPlanPageView || 
          !paymentPlanDetailsPageContent || !paymentPlanFormPageContent) {
        console.error('Required elements not found');
        alert('Error: Page elements not found');
        return;
      }
      
      // Set payment plan name in header
      const planName = plan.installment_label || 'Instalment #' + plan.id;
      paymentPlanPageName.textContent = planName;
      paymentPlanPageTitle.textContent = 'Payment Plan';
      
      populatePaymentPlanDetails(plan);
      
      // Hide table view, show page view
      clientsTableView.classList.add('hidden');
      paymentPlanPageView.style.display = 'block';
      paymentPlanPageView.classList.add('show');
      paymentPlanDetailsPageContent.style.display = 'block';
      paymentPlanFormPageContent.style.display = 'none';
      if (editPaymentPlanFromPageBtn) editPaymentPlanFromPageBtn.style.display = 'inline-block';
      if (closePaymentPlanPageBtn) closePaymentPlanPageBtn.style.display = 'inline-block';
    } catch (e) {
      console.error(e);
      alert('Error loading payment plan details: ' + e.message);
    }
  }

  // Populate payment plan details view
  function populatePaymentPlanDetails(plan) {
    const content = document.getElementById('paymentPlanDetailsContent');
    if (!content) return;

    const schedule = plan.schedule || {};
    const policy = schedule.policy || {};
    const client = policy.client || {};

    const col1 = `
      <div class="detail-section">
        <div class="detail-section-header">PAYMENT PLAN DETAILS</div>
        <div class="detail-section-body">
          <div class="detail-row">
            <span class="detail-label">Instalment Label</span>
            <div class="detail-value">${plan.installment_label || 'Instalment #' + plan.id}</div>
          </div>
          <div class="detail-row">
            <span class="detail-label">Policy No</span>
            <div class="detail-value">${policy.policy_no || '-'}</div>
          </div>
          <div class="detail-row">
            <span class="detail-label">Client Name</span>
            <div class="detail-value">${client.client_name || '-'}</div>
          </div>
          <div class="detail-row">
            <span class="detail-label">Due Date</span>
            <div class="detail-value">${formatDate(plan.due_date)}</div>
          </div>
        </div>
      </div>
    `;

    const col2 = `
      <div class="detail-section">
        <div class="detail-section-header">FINANCIAL INFO</div>
        <div class="detail-section-body">
          <div class="detail-row">
            <span class="detail-label">Amount</span>
            <div class="detail-value">${formatNumber(plan.amount)}</div>
          </div>
          <div class="detail-row">
            <span class="detail-label">Frequency</span>
            <div class="detail-value">${plan.frequency || '-'}</div>
          </div>
          <div class="detail-row">
            <span class="detail-label">Status</span>
            <div class="detail-value">${plan.status ? plan.status.charAt(0).toUpperCase() + plan.status.slice(1) : '-'}</div>
          </div>
        </div>
      </div>
    `;

    const col3 = `
    `;

    const col4 = `
    `;

    content.innerHTML = col1 + col2 + col3 + col4;
  }

  // Open payment plan page (Add or Edit)
  async function openPaymentPlanPage(mode) {
    if (mode === 'add') {
      openPaymentPlanForm('add');
    } else {
      if (currentPaymentPlanId) {
        openEditPaymentPlan(currentPaymentPlanId);
      }
    }
  }

  // Add Payment Plan Button
  document.getElementById('addPaymentPlanBtn').addEventListener('click', () => openPaymentPlanModal('add'));

  document.getElementById('columnBtn2').addEventListener('click', () => openColumnModal());

  async function openEditPaymentPlan(id) {
    try {
      const res = await fetch(`/payment-plans/${id}/edit`, {
        headers: {
          'Accept': 'application/json',
          'X-Requested-With': 'XMLHttpRequest'
        }
      });
      if (!res.ok) throw new Error('Network error');
      const plan = await res.json();
      currentPaymentPlanId = id;
      openPaymentPlanForm('edit', plan);
    } catch (e) {
      console.error(e);
      alert('Error loading payment plan data');
    }
  }

  function openPaymentPlanForm(mode, plan = null) {
    // Clone form from modal
    const modalForm = document.getElementById('paymentPlanModal').querySelector('form');
    const pageForm = document.getElementById('paymentPlanPageForm');
    const formContentDiv = pageForm.querySelector('div[style*="padding:12px"]');
    
    // Clone the modal form body
    const modalBody = modalForm.querySelector('.modal-body');
    if (modalBody && formContentDiv) {
      formContentDiv.innerHTML = modalBody.innerHTML;
    }

    const formMethod = document.getElementById('paymentPlanPageFormMethod');
    const deleteBtn = document.getElementById('paymentPlanDeleteBtn');
    const editBtn = document.getElementById('editPaymentPlanFromPageBtn');
    const closeBtn = document.getElementById('closePaymentPlanPageBtn');
    const closeFormBtn = document.getElementById('closePaymentPlanFormBtn');

    if (mode === 'add') {
      document.getElementById('paymentPlanPageTitle').textContent = 'Add Payment Plan';
      document.getElementById('paymentPlanPageName').textContent = '';
      pageForm.action = paymentPlansStoreRoute;
      formMethod.innerHTML = '';
      deleteBtn.style.display = 'none';
      if (editBtn) editBtn.style.display = 'none';
      if (closeBtn) closeBtn.style.display = 'inline-block';
      if (closeFormBtn) closeFormBtn.style.display = 'none';
      pageForm.reset();
    } else {
      const planName = plan.installment_label || 'Instalment #' + plan.id;
      document.getElementById('paymentPlanPageTitle').textContent = 'Edit Payment Plan';
      document.getElementById('paymentPlanPageName').textContent = planName;
      pageForm.action = `/payment-plans/${currentPaymentPlanId}`;
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

      const fields = ['schedule_id','installment_label','due_date','amount','frequency','status'];
      fields.forEach(k => {
        const el = formContentDiv ? formContentDiv.querySelector(`#${k}`) : null;
        if (!el) return;
        if (el.type === 'date') {
          el.value = plan[k] ? (typeof plan[k] === 'string' ? plan[k].substring(0,10) : plan[k]) : '';
        } else {
          el.value = plan[k] ?? '';
        }
      });
    }

    // Hide table view, show page view
    document.getElementById('clientsTableView').classList.add('hidden');
    const paymentPlanPageView = document.getElementById('paymentPlanPageView');
    paymentPlanPageView.style.display = 'block';
    paymentPlanPageView.classList.add('show');
    document.getElementById('paymentPlanDetailsPageContent').style.display = 'none';
    document.getElementById('paymentPlanFormPageContent').style.display = 'block';
  }

  function closePaymentPlanPageView() {
    const paymentPlanPageView = document.getElementById('paymentPlanPageView');
    paymentPlanPageView.classList.remove('show');
    paymentPlanPageView.style.display = 'none';
    document.getElementById('clientsTableView').classList.remove('hidden');
    document.getElementById('paymentPlanDetailsPageContent').style.display = 'none';
    document.getElementById('paymentPlanFormPageContent').style.display = 'none';
    currentPaymentPlanId = null;
  }

  // Edit button from details page
  const editBtn = document.getElementById('editPaymentPlanFromPageBtn');
  if (editBtn) {
    editBtn.addEventListener('click', function() {
      if (currentPaymentPlanId) {
        openEditPaymentPlan(currentPaymentPlanId);
      }
    });
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

  function deletePaymentPlan() {
    if (!currentPaymentPlanId) return;
    if (!confirm('Delete this payment plan?')) return;
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = `/payment-plans/${currentPaymentPlanId}`;
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

  function openPaymentPlanModal(mode, planId = null) {
    const modal = document.getElementById('paymentPlanModal');
    const form = document.getElementById('paymentPlanForm');
    const formMethod = document.getElementById('paymentPlanFormMethod');
    const modalTitle = document.getElementById('paymentPlanModalTitle');
    const deleteBtn = document.getElementById('paymentPlanDeleteBtn');
    
    if (mode === 'add') {
      modalTitle.textContent = 'Add Payment Plan';
      form.reset();
      form.action = paymentPlansStoreRoute;
      formMethod.innerHTML = '';
      deleteBtn.style.display = 'none';
      currentPaymentPlanId = null;
    } else if (mode === 'edit' && planId) {
      modalTitle.textContent = 'Edit Payment Plan';
      form.action = paymentPlansUpdateRouteTemplate.replace(':id', planId);
      formMethod.innerHTML = '@method("PUT")';
      deleteBtn.style.display = 'inline-block';
      currentPaymentPlanId = planId;
      
      // Fetch payment plan data
      fetch(`/payment-plans/${planId}/edit`, {
        headers: {
          'Accept': 'application/json',
          'X-Requested-With': 'XMLHttpRequest'
        }
      })
        .then(response => response.json())
        .then(data => {
          if (data.paymentPlan) {
            const p = data.paymentPlan;
            document.getElementById('schedule_id').value = p.schedule_id || '';
            document.getElementById('installment_label').value = p.installment_label || '';
            document.getElementById('due_date').value = p.due_date ? p.due_date.split('T')[0] : '';
            document.getElementById('amount').value = p.amount || '';
            document.getElementById('frequency').value = p.frequency || '';
            document.getElementById('status').value = p.status || 'pending';
          }
        })
        .catch(error => {
          console.error('Error fetching payment plan data:', error);
          alert('Error loading payment plan data');
        });
    }
    
    modal.classList.add('show');
    document.body.style.overflow = 'hidden';
  }

  function closePaymentPlanModal() {
    const modal = document.getElementById('paymentPlanModal');
    modal.classList.remove('show');
    document.body.style.overflow = '';
    const form = document.getElementById('paymentPlanForm');
    form.reset();
    currentPaymentPlanId = null;
  }

  // Close modal on outside click
  document.getElementById('paymentPlanModal').addEventListener('click', function(e) {
    if (e.target === this) {
      closePaymentPlanModal();
    }
  });

  // Handle form submission
  document.getElementById('paymentPlanForm').addEventListener('submit', function(e) {
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
        closePaymentPlanModal();
        window.location.reload();
      } else {
        alert(data.message || 'Error saving payment plan');
      }
    })
    .catch(error => {
      console.error('Error:', error);
      alert('Error saving payment plan');
    });
  });

  // Update openPaymentPlanDetails to open modal in edit mode
  const originalOpenPaymentPlanDetails = window.openPaymentPlanDetails;
  window.openPaymentPlanDetails = function(id) {
    openPaymentPlanModal('edit', id);
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
