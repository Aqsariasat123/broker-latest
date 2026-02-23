
  // Drag and drop functionality
  // Only declare if not already declared (to avoid duplicate declaration errors)
  if (typeof draggedElement === 'undefined') {
    var draggedElement = null;
  }
  if (typeof dragOverElement === 'undefined') {
    var dragOverElement = null;
  }
  
  // Initialize drag and drop when column modal opens
  function initDragAndDrop() {
    const columnSelection = document.getElementById('columnSelection');
    if (!columnSelection) return;

    // Make all column items draggable (support both old and new class names)
    const columnItems = columnSelection.querySelectorAll('.column-item, .column-item-vertical');
    
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

  // Column modal functions
  function openColumnModal(){
    // Mandatory fields that should always be checked
    const mandatoryFields = mandatoryColumns;
    
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
    const mandatoryFields = mandatoryColumns;
    document.querySelectorAll('.column-checkbox').forEach(cb => {
      // Don't uncheck mandatory fields
      if (!mandatoryFields.includes(cb.value)) {
        cb.checked = false;
      }
    });
  }

  function saveColumnSettings(){
    // Mandatory fields that should always be included
    const mandatoryFields = mandatoryColumns;

    // Get order from DOM - this preserves the drag and drop order (support both old and new class names)
    const items = Array.from(document.querySelectorAll('#columnSelection .column-item, #columnSelection .column-item-vertical'));
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

  // Autocomplete for name fields
  function initAutocomplete() {
    document.querySelectorAll('input[data-autocomplete]').forEach(input => {
      if (input.dataset.acInit) return;
      input.dataset.acInit = 'true';

      const type = input.dataset.autocomplete; // 'clients' or 'contacts'
      let dropdown = document.createElement('div');
      dropdown.className = 'ac-dropdown';
      dropdown.style.cssText = 'display:none; position:absolute; background:#fff; border:1px solid #ddd; border-radius:3px; max-height:180px; overflow-y:auto; z-index:9999; box-shadow:0 2px 8px rgba(0,0,0,0.15); font-size:12px; min-width:200px;';
      input.parentNode.style.position = 'relative';
      input.parentNode.appendChild(dropdown);

      let debounce = null;
      input.addEventListener('input', function() {
        clearTimeout(debounce);
        const q = this.value.trim();
        if (q.length < 1) { dropdown.style.display = 'none'; return; }

        debounce = setTimeout(() => {
          fetch(`/api/search?type=${type}&q=${encodeURIComponent(q)}&limit=10`, {
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
          })
          .then(r => r.json())
          .then(data => {
            if (!data.length) { dropdown.style.display = 'none'; return; }
            dropdown.innerHTML = data.map(item =>
              `<div class="ac-item" data-id="${item.id}" style="padding:6px 10px; cursor:pointer; border-bottom:1px solid #f0f0f0;">${item.name}</div>`
            ).join('');
            dropdown.style.display = 'block';
            dropdown.style.width = input.offsetWidth + 'px';

            dropdown.querySelectorAll('.ac-item').forEach(item => {
              item.addEventListener('mousedown', function(e) {
                e.preventDefault();
                input.value = this.textContent;
                // Set hidden ID field if exists
                const idField = document.getElementById(input.dataset.idField);
                if (idField) idField.value = this.dataset.id;
                dropdown.style.display = 'none';
              });
              item.addEventListener('mouseenter', function() {
                this.style.background = '#f5f5f5';
              });
              item.addEventListener('mouseleave', function() {
                this.style.background = '#fff';
              });
            });
          })
          .catch(() => { dropdown.style.display = 'none'; });
        }, 250);
      });

      input.addEventListener('blur', function() {
        setTimeout(() => { dropdown.style.display = 'none'; }, 200);
      });
    });
  }

  // Table search - live filter rows by text
  function initTableSearch() {
    document.querySelectorAll('input[data-table-search]').forEach(input => {
      if (input.dataset.searchInit) return;
      input.dataset.searchInit = 'true';

      const tableId = input.dataset.tableSearch;
      const table = document.getElementById(tableId);
      if (!table) return;

      input.addEventListener('input', function() {
        const q = this.value.trim().toLowerCase();
        const rows = table.querySelectorAll('tbody tr');
        rows.forEach(row => {
          if (!q) { row.style.display = ''; return; }
          const text = row.textContent.toLowerCase();
          row.style.display = text.includes(q) ? '' : 'none';
        });
      });
    });
  }

  document.addEventListener('DOMContentLoaded', function() {
    initAutocomplete();
    initTableSearch();
  });

  // Column resize functionality
  function initColumnResize() {
    const tables = document.querySelectorAll('table');
    tables.forEach(table => {
      if (table.dataset.resizeInit) return;
      table.dataset.resizeInit = 'true';

      const ths = table.querySelectorAll('thead th');
      ths.forEach(th => {
        // Skip bell-cell and action columns
        if (th.querySelector('svg') || th.textContent.trim() === 'Action') return;

        const handle = document.createElement('div');
        handle.className = 'col-resize-handle';
        th.appendChild(handle);

        let startX, startWidth, colIndex;

        handle.addEventListener('mousedown', function(e) {
          e.preventDefault();
          e.stopPropagation();
          startX = e.pageX;
          startWidth = th.offsetWidth;
          colIndex = Array.from(th.parentNode.children).indexOf(th);
          handle.classList.add('resizing');
          table.classList.add('resizing');

          function onMouseMove(ev) {
            const diff = ev.pageX - startX;
            const newWidth = Math.max(40, startWidth + diff);
            th.style.width = newWidth + 'px';
            th.style.minWidth = newWidth + 'px';
            // Also set width on matching tbody tds
            const rows = table.querySelectorAll('tbody tr');
            rows.forEach(row => {
              const td = row.children[colIndex];
              if (td) {
                td.style.width = newWidth + 'px';
                td.style.minWidth = newWidth + 'px';
              }
            });
          }

          function onMouseUp() {
            handle.classList.remove('resizing');
            table.classList.remove('resizing');
            document.removeEventListener('mousemove', onMouseMove);
            document.removeEventListener('mouseup', onMouseUp);
          }

          document.addEventListener('mousemove', onMouseMove);
          document.addEventListener('mouseup', onMouseUp);
        });
      });
    });
  }

  // Initialize column resize on DOM ready
  document.addEventListener('DOMContentLoaded', initColumnResize);

  // close modals on ESC and clicking backdrop
  document.addEventListener('keydown', e => { 
    if (e.key === 'Escape') { 
      const modals = document.querySelectorAll('.modal.show');
      modals.forEach(m => {
        m.classList.remove('show');
        document.body.style.overflow = '';
      });
    } 
  });
  
  document.querySelectorAll('.modal').forEach(m => {
    m.addEventListener('click', e => { 
      if (e.target === m) { 
        m.classList.remove('show'); 
        document.body.style.overflow = '';
        document.getElementById('tableResponsive')?.classList.remove('no-scroll');
      } 
    });
  });
