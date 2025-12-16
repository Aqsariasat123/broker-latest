  // Data initialized in Blade template

  // Format date helper function
  function formatDate(dateStr) {
    if (!dateStr) return '-';
    const date = new Date(dateStr);
    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    return `${date.getDate()}-${months[date.getMonth()]}-${String(date.getFullYear()).slice(-2)}`;
  }

  // Format number helper function
  function formatNumber(num) {
    if (!num && num !== 0) return '-';
    return parseFloat(num).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
  }


  // Event listeners
  document.addEventListener('DOMContentLoaded', function(){
    // Add button handler
    const addBtn = document.getElementById('addContactBtn');
    if (addBtn) {
      addBtn.addEventListener('click', function() {
        openContactModal('add');
      });
    }

    // Column button
    const columnBtn = document.getElementById('columnBtn2');
    if (columnBtn) {
      columnBtn.addEventListener('click', function() {
        openColumnModal();
      });
    }

    // Filter toggle handler
    const filterToggle = document.getElementById('filterToggle');
    if (filterToggle) {
      const urlParams = new URLSearchParams(window.location.search);
      filterToggle.checked = urlParams.get('follow_up') === 'true' || urlParams.get('follow_up') === '1';
      
      filterToggle.addEventListener('change', function(e) {
        const u = new URL(window.location.href);
        if (this.checked) {
          u.searchParams.set('follow_up', '1');
        } else {
          u.searchParams.delete('follow_up');
        }
        window.location.href = u.toString();
      });
    }

    // To Follow Up button handler
    const followUpBtn = document.getElementById('followUpBtn');
    if (followUpBtn) {
      followUpBtn.addEventListener('click', function(e) {
        e.preventDefault();
        const u = new URL(window.location.href);
        u.searchParams.set('follow_up', '1');
        window.location.href = u.toString();
      });
    }

    // List ALL button handler
    const listAllBtn = document.getElementById('listAllBtn');
    if (listAllBtn) {
      listAllBtn.addEventListener('click', function(e) {
        e.preventDefault();
        const u = new URL(window.location.href);
        u.searchParams.delete('follow_up');
        window.location.href = u.toString();
      });
    }

    // Setup form listeners on page load
    setupContactFormListeners(document.getElementById('contactModal'));
  });


  // Open modal for add
  function openContactModal(mode) {
    const modal = document.getElementById('contactModal');
    if (!modal) return;
    
    const title = document.getElementById('contactModalTitle');
    const form = document.getElementById('contactForm');
    const deleteBtn = document.getElementById('contactDeleteBtn');
    const formMethod = document.getElementById('contactFormMethod');
    
    if (mode === 'add') {
      if (title) title.textContent = 'Add Contact';
      if (form) {
        form.action = contactsStoreRoute;
        form.reset();
      }
      if (formMethod) formMethod.innerHTML = '';
      if (deleteBtn) deleteBtn.style.display = 'none';
      currentContactId = null;
      const ageDisplay = document.getElementById('age_display');
      if (ageDisplay) ageDisplay.value = '';
    }
    
    document.body.style.overflow = 'hidden';
    modal.classList.add('show');
    setTimeout(() => setupContactFormListeners(modal), 100);
  }

  // Open modal with contact data for editing
  function openModalWithContact(mode, contact) {
    const modal = document.getElementById('contactModal');
    if (!modal) return;
    
    const title = document.getElementById('contactModalTitle');
    const form = document.getElementById('contactForm');
    const deleteBtn = document.getElementById('contactDeleteBtn');
    const formMethod = document.getElementById('contactFormMethod');
    
    if (mode === 'edit' && contact) {
      if (title) title.textContent = 'Edit Contact';
      if (form) {
        form.action = `/contacts/${currentContactId}`;
      }
      if (formMethod) {
        formMethod.innerHTML = '';
        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'PUT';
        formMethod.appendChild(methodInput);
      }
      if (deleteBtn) deleteBtn.style.display = 'block';
      
      const fields = ['type','contact_name','contact_no','wa','occupation','employer','email_address','address','location','dob','acquired','source','source_name','agency','agent','status','rank','savings_budget','children'];
      fields.forEach(id => {
        const el = form.querySelector(`#${id}`);
        if (!el) return;
        if (el.type === 'checkbox') {
          el.checked = !!contact[id];
        } else if (el.type === 'date') {
          if (contact[id]) {
            let dateValue = contact[id];
            if (typeof dateValue === 'string' && dateValue.match(/^\d{4}-\d{2}-\d{2}/)) {
              el.value = dateValue.substring(0, 10);
            } else if (typeof dateValue === 'string') {
              try {
                const date = new Date(dateValue);
                if (!isNaN(date.getTime())) el.value = date.toISOString().substring(0, 10);
              } catch (e) {}
            }
          }
        } else if (el.tagName === 'SELECT') {
          el.value = contact[id] ?? '';
        } else {
          el.value = contact[id] ?? '';
        }
      });
      
      const dobField = form.querySelector('#dob');
      const ageDisplay = document.getElementById('age_display');
      if (dobField && ageDisplay && contact.dob) {
        try {
          const dob = new Date(contact.dob);
          const today = new Date();
          let age = today.getFullYear() - dob.getFullYear();
          const monthDiff = today.getMonth() - dob.getMonth();
          if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < dob.getDate())) age--;
          ageDisplay.value = age;
        } catch (e) {}
      }
    }
    
    document.body.style.overflow = 'hidden';
    modal.classList.add('show');
    setTimeout(() => setupContactFormListeners(modal), 100);
  }

  // Setup form event listeners
  function setupContactFormListeners(container) {
    if (!container) return;
    const dobField = container.querySelector('#dob');
    const ageDisplay = document.getElementById('age_display');
    if (dobField && ageDisplay) {
      dobField.addEventListener('change', function() {
        if (this.value) {
          try {
            const dob = new Date(this.value);
            const today = new Date();
            let age = today.getFullYear() - dob.getFullYear();
            const monthDiff = today.getMonth() - dob.getMonth();
            if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < dob.getDate())) age--;
            ageDisplay.value = age;
          } catch (e) {
            ageDisplay.value = '';
          }
        } else {
          ageDisplay.value = '';
        }
      });
    }
  }

  function closeContactModal(){
    document.getElementById('contactModal').classList.remove('show');
    currentContactId = null;
    document.body.style.overflow = '';
  }

  // show edit: fetch /contacts/{id}/edit which returns JSON in controller
  async function openEditContact(id){
    try {
      const res = await fetch(`/contacts/${id}/edit`, {
        headers: {
          'Accept': 'application/json',
          'X-Requested-With': 'XMLHttpRequest'
        }
      });
      if (!res.ok) throw new Error('Network error');
      const contact = await res.json();
      currentContactId = id;
      openModalWithContact('edit', contact);
    } catch (e) {
      console.error(e);
      alert('Error loading contact data');
    }
  }

  function deleteContact(){
    if (!currentContactId) return;
    if (!confirm('Delete this contact?')) return;
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = `/contacts/${currentContactId}`;
    const csrf = document.createElement('input'); csrf.type='hidden'; csrf.name='_token'; csrf.value = csrfToken; form.appendChild(csrf);
    const method = document.createElement('input'); method.type='hidden'; method.name='_method'; method.value='DELETE'; form.appendChild(method);
    document.body.appendChild(form);
    form.submit();
  }

  // Column modal functions
  function openColumnModal(){
    document.getElementById('tableResponsive').classList.add('no-scroll');
    document.querySelectorAll('.column-checkbox').forEach(cb => {
      // Always check mandatory fields, otherwise check if in selectedColumns
      cb.checked = mandatoryFields.includes(cb.value) || selectedColumns.includes(cb.value);
    });
    document.body.style.overflow = 'hidden';
    document.getElementById('columnModal').classList.add('show');
    // Initialize drag and drop after modal is shown
    setTimeout(initDragAndDrop, 100);
  }
  function closeColumnModal(){
    document.getElementById('tableResponsive').classList.remove('no-scroll');
    document.getElementById('columnModal').classList.remove('show');
    document.body.style.overflow = '';
  }
  function selectAllColumns(){ 
    document.querySelectorAll('.column-checkbox').forEach(cb => {
      cb.checked = true;
    });
  }
  function deselectAllColumns(){ 
    document.querySelectorAll('.column-checkbox').forEach(cb => {
      // Don't uncheck mandatory fields
      if (!mandatoryFields.includes(cb.value)) {
        cb.checked = false;
      }
    });
  }

  function saveColumnSettings(){
    
    // Get order from DOM - this preserves the drag and drop order
    const items = Array.from(document.querySelectorAll('#columnSelection .column-item'));
    const order = items.map(item => item.dataset.column);
    const checked = Array.from(document.querySelectorAll('.column-checkbox:checked')).map(n=>n.value);
    
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
    existing.forEach(e=>e.remove());
    
    // Add columns in the order they appear in the DOM (after drag and drop)
    orderedChecked.forEach(c => {
      const i = document.createElement('input'); 
      i.type='hidden'; 
      i.name='columns[]'; 
      i.value=c; 
      form.appendChild(i);
    });
    
    form.submit();
  }

  // Drag and drop functionality
  let draggedElement = null;
  let dragOverElement = null;
  
  // Initialize drag and drop when column modal opens
  function initDragAndDrop() {
    const columnSelection = document.getElementById('columnSelection');
    if (!columnSelection) return;
    
    // Make all column items draggable
    const columnItems = columnSelection.querySelectorAll('.column-item');
    
    columnItems.forEach(item => {
      // Skip if already initialized
      if (item.dataset.dragInitialized === 'true') {
        return;
      }
      item.dataset.dragInitialized = 'true';
      // Prevent checkbox from interfering with drag
      const checkbox = item.querySelector('.column-checkbox');
      if (checkbox) {
        checkbox.addEventListener('mousedown', function(e) {
          e.stopPropagation();
        });
        checkbox.addEventListener('click', function(e) {
          e.stopPropagation();
        });
      }
      
      // Prevent label from interfering with drag
      const label = item.querySelector('label');
      if (label) {
        label.addEventListener('mousedown', function(e) {
          // Only prevent if clicking on the label text, not the checkbox
          if (e.target === label) {
            e.preventDefault();
          }
        });
      }
      
      // Drag start
      item.addEventListener('dragstart', function(e) {
        draggedElement = this;
        this.classList.add('dragging');
        e.dataTransfer.effectAllowed = 'move';
        e.dataTransfer.setData('text/html', this.outerHTML);
        e.dataTransfer.setData('text/plain', this.dataset.column);
      });
      
      // Drag end
      item.addEventListener('dragend', function(e) {
        this.classList.remove('dragging');
        // Remove drag-over from all items
        columnItems.forEach(i => i.classList.remove('drag-over'));
        if (dragOverElement) {
          dragOverElement.classList.remove('drag-over');
          dragOverElement = null;
        }
        draggedElement = null;
      });
      
      // Drag over
      item.addEventListener('dragover', function(e) {
        e.preventDefault();
        e.stopPropagation();
        e.dataTransfer.dropEffect = 'move';
        
        if (draggedElement && this !== draggedElement) {
          // Remove drag-over class from previous element
          if (dragOverElement && dragOverElement !== this) {
            dragOverElement.classList.remove('drag-over');
          }
          
          // Add drag-over class to current element
          this.classList.add('drag-over');
          dragOverElement = this;
          
          const rect = this.getBoundingClientRect();
          const midpoint = rect.top + (rect.height / 2);
          const next = e.clientY > midpoint;
          
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
      
      // Drag enter
      item.addEventListener('dragenter', function(e) {
        e.preventDefault();
        if (draggedElement && this !== draggedElement) {
          this.classList.add('drag-over');
        }
      });
      
      // Drag leave
      item.addEventListener('dragleave', function(e) {
        // Only remove if we're actually leaving the element
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
  }

  // close modals on ESC and clicking backdrop
  document.addEventListener('DOMContentLoaded', function() {
    document.addEventListener('keydown', e => { if (e.key === 'Escape') { closeContactModal(); closeColumnModal(); } });
    document.querySelectorAll('.modal').forEach(m => {
      m.addEventListener('click', e => { if (e.target === m) { m.classList.remove('show'); document.body.style.overflow = ''; } });
    });

    // Basic client-side validation for contact form (prevent empty required)
    const contactForm = document.getElementById('contactForm');
    if (contactForm) {
      contactForm.addEventListener('submit', function(e){
        const req = this.querySelectorAll('[required]');
        let ok = true;
        req.forEach(f => { if (!String(f.value||'').trim()) { ok = false; f.style.borderColor='red'; } else { f.style.borderColor=''; } });
        if (!ok) { e.preventDefault(); alert('Please fill required fields'); }
      });
    }
  });
