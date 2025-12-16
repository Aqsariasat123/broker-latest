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

  // Print function
  function printTable() {
    const table = document.getElementById('vehiclesTable');
    if (!table) return;
    
    // Get table headers - preserve order
    const headers = [];
    const headerCells = table.querySelectorAll('thead th');
    headerCells.forEach(th => {
      let headerText = '';
      const clone = th.cloneNode(true);
      const filterInput = clone.querySelector('.column-filter');
      if (filterInput) filterInput.remove();
      headerText = clone.textContent.trim();
      // Handle bell icon column
      if (clone.querySelector('svg')) {
        headerText = '🔔';
      }
      if (headerText) {
        headers.push(headerText);
      }
    });
    
    // Get table rows data
    const rows = [];
    const tableRows = table.querySelectorAll('tbody tr:not([style*="display: none"])');
    tableRows.forEach(row => {
      if (row.style.display === 'none') return;
      
      const cells = [];
      const rowCells = row.querySelectorAll('td');
      rowCells.forEach((cell) => {
        let cellContent = '';
        
        // Handle radio button column
        if (cell.querySelector('input[type="radio"]')) {
          const radio = cell.querySelector('input[type="radio"]');
          cellContent = radio && radio.checked ? '●' : '○';
        } 
        // Handle action column
        else if (cell.classList.contains('action-cell')) {
          const expandIcon = cell.querySelector('.action-expand');
          if (expandIcon) cellContent = '⤢';
        } 
        // Handle regular cells
        else {
          const link = cell.querySelector('a');
          if (link) {
            cellContent = link.textContent.trim();
          } else {
            cellContent = cell.textContent.trim();
          }
        }
        
        cells.push(cellContent || '-');
      });
      rows.push(cells);
    });
    
    // Escape HTML to prevent XSS
    function escapeHtml(text) {
      if (!text) return '';
      const div = document.createElement('div');
      div.textContent = text;
      return div.innerHTML;
    }
    
    // Build headers HTML
    const headersHTML = headers.map(h => '<th>' + escapeHtml(h) + '</th>').join('');
    
    // Build rows HTML
    const rowsHTML = rows.map(row => {
      const cellsHTML = row.map(cell => {
        const cellText = escapeHtml(String(cell || '-'));
        return '<td>' + cellText + '</td>';
      }).join('');
      return '<tr>' + cellsHTML + '</tr>';
    }).join('');
    
    // Create print window
    const printWindow = window.open('', '_blank', 'width=800,height=600');
    
    const printHTML = '<!DOCTYPE html>' +
      '<html>' +
      '<head>' +
      '<title>Vehicles - Print</title>' +
      '<style>' +
      '@page { margin: 1cm; size: A4 landscape; }' +
      'html, body { margin: 0; padding: 0; background: #fff !important; }' +
      'body { font-family: Arial, sans-serif; font-size: 10px; }' +
      'table { width: 100%; border-collapse: collapse; page-break-inside: auto; }' +
      'thead { display: table-header-group; }' +
      'thead th { background-color: #000 !important; color: #fff !important; padding: 8px 5px; text-align: left; border: 1px solid #333; font-weight: normal; -webkit-print-color-adjust: exact; print-color-adjust: exact; }' +
      'tbody tr { page-break-inside: avoid; border-bottom: 1px solid #ddd; }' +
      'tbody tr:nth-child(even) { background-color: #f8f8f8; }' +
      'tbody td { padding: 6px 5px; border: 1px solid #ddd; white-space: nowrap; }' +
      '</style>' +
      '</head>' +
      '<body>' +
      '<table>' +
      '<thead><tr>' + headersHTML + '</tr></thead>' +
      '<tbody>' + rowsHTML + '</tbody>' +
      '</table>' +
      '<scr' + 'ipt>' +
      'window.onload = function() {' +
      '  setTimeout(function() {' +
      '    window.print();' +
      '  }, 100);' +
      '};' +
      'window.onafterprint = function() {' +
      '  window.close();' +
      '};' +
      '</scr' + 'ipt>' +
      '</body>' +
      '</html>';
    
    if (printWindow) {
      printWindow.document.open();
      printWindow.document.write(printHTML);
      printWindow.document.close();
    }
  }

  // Toggle filter function
  function toggleFilter() {
    const toggle = document.getElementById('filterToggle');
    const table = document.getElementById('vehiclesTable');
    if (!table || !toggle) return;
    
    const headers = table.querySelectorAll('thead th');
    headers.forEach((th, index) => {
      if (index === 0) return; // Skip bell icon column
      if (index === 1) return; // Skip action column
      
      let filterInput = th.querySelector('.column-filter');
      if (toggle.checked) {
        if (!filterInput) {
          filterInput = document.createElement('input');
          filterInput.type = 'text';
          filterInput.className = 'column-filter';
          filterInput.placeholder = 'Filter...';
          filterInput.style.cssText = 'width:100%; padding:4px; margin-top:4px; border:1px solid #ddd; border-radius:2px; font-size:12px;';
          filterInput.addEventListener('input', function() {
            filterTable();
          });
          th.appendChild(filterInput);
        }
        filterInput.style.display = 'block';
      } else {
        if (filterInput) {
          filterInput.style.display = 'none';
          filterInput.value = '';
          filterTable();
        }
      }
    });
  }

  // Filter table function
  function filterTable() {
    const table = document.getElementById('vehiclesTable');
    if (!table) return;
    
    const rows = table.querySelectorAll('tbody tr');
    const headers = table.querySelectorAll('thead th');
    
    rows.forEach(row => {
      let showRow = true;
      const cells = row.querySelectorAll('td');
      
      headers.forEach((th, index) => {
        if (index === 0 || index === 1) return; // Skip bell and action columns
        
        const filterInput = th.querySelector('.column-filter');
        if (filterInput && filterInput.value) {
          const cell = cells[index];
          if (cell) {
            const cellText = cell.textContent.trim().toLowerCase();
            const filterText = filterInput.value.toLowerCase();
            if (!cellText.includes(filterText)) {
              showRow = false;
            }
          }
        }
      });
      
      row.style.display = showRow ? '' : 'none';
    });
  }

  // Select vehicle function
  function selectVehicle(id) {
    currentVehicleId = id;
    // You can add additional logic here if needed
  }

  // Add event listener for print button
  document.addEventListener('DOMContentLoaded', function() {
    const printBtn = document.getElementById('printBtn');
    if (printBtn) {
      printBtn.addEventListener('click', printTable);
    }
  });

  // Open vehicle details (full page view) - MUST be defined before HTML onclick handlers
  async function openVehicleDetails(id) {
    try {
      const res = await fetch(`/vehicles/${id}`, {
        headers: {
          'Accept': 'application/json',
          'X-Requested-With': 'XMLHttpRequest'
        }
      });
      if (!res.ok) throw new Error(`HTTP ${res.status}`);
      const vehicle = await res.json();
      currentVehicleId = id;
      
      // Get all required elements
      const vehiclePageName = document.getElementById('vehiclePageName');
      const vehiclePageTitle = document.getElementById('vehiclePageTitle');
      const clientsTableView = document.getElementById('clientsTableView');
      const vehiclePageView = document.getElementById('vehiclePageView');
      const vehicleDetailsPageContent = document.getElementById('vehicleDetailsPageContent');
      const vehicleFormPageContent = document.getElementById('vehicleFormPageContent');
      const editVehicleFromPageBtn = document.getElementById('editVehicleFromPageBtn');
      const closeVehiclePageBtn = document.getElementById('closeVehiclePageBtn');
      
      if (!vehiclePageName || !vehiclePageTitle || !clientsTableView || !vehiclePageView || 
          !vehicleDetailsPageContent || !vehicleFormPageContent) {
        console.error('Required elements not found');
        alert('Error: Page elements not found');
        return;
      }
      
      // Set vehicle name in header
      const vehicleName = vehicle.regn_no || vehicle.vehicle_id || 'Unknown';
      vehiclePageName.textContent = vehicleName;
      vehiclePageTitle.textContent = 'Vehicle';
      
      populateVehicleDetails(vehicle);
      
      // Hide table view, show page view
      clientsTableView.classList.add('hidden');
      vehiclePageView.style.display = 'block';
      vehiclePageView.classList.add('show');
      vehicleDetailsPageContent.style.display = 'block';
      vehicleFormPageContent.style.display = 'none';
      if (editVehicleFromPageBtn) editVehicleFromPageBtn.style.display = 'inline-block';
      if (closeVehiclePageBtn) closeVehiclePageBtn.style.display = 'inline-block';
    } catch (e) {
      console.error(e);
      alert('Error loading vehicle details: ' + e.message);
    }
  }

  // Populate vehicle details view
  function populateVehicleDetails(vehicle) {
    const content = document.getElementById('vehicleDetailsContent');
    if (!content) return;

    const col1 = `
      <div class="detail-section">
        <div class="detail-section-header">VEHICLE BASIC INFO</div>
        <div class="detail-section-body">
          <div class="detail-row">
            <span class="detail-label">Registration No</span>
            <div class="detail-value">${vehicle.regn_no || '-'}</div>
          </div>
          <div class="detail-row">
            <span class="detail-label">Make</span>
            <div class="detail-value">${vehicle.make || '-'}</div>
          </div>
          <div class="detail-row">
            <span class="detail-label">Model</span>
            <div class="detail-value">${vehicle.model || '-'}</div>
          </div>
          <div class="detail-row">
            <span class="detail-label">Type</span>
            <div class="detail-value">${vehicle.type || '-'}</div>
          </div>
          <div class="detail-row">
            <span class="detail-label">Usage</span>
            <div class="detail-value">${vehicle.useage || '-'}</div>
          </div>
          <div class="detail-row">
            <span class="detail-label">Year</span>
            <div class="detail-value">${vehicle.year || '-'}</div>
          </div>
        </div>
      </div>
    `;

    const col2 = `
      <div class="detail-section">
        <div class="detail-section-header">VEHICLE DETAILS</div>
        <div class="detail-section-body">
          <div class="detail-row">
            <span class="detail-label">Value</span>
            <div class="detail-value">${formatNumber(vehicle.value)}</div>
          </div>
          <div class="detail-row">
            <span class="detail-label">Policy ID</span>
            <div class="detail-value">${vehicle.policy_id || '-'}</div>
          </div>
          <div class="detail-row">
            <span class="detail-label">Engine</span>
            <div class="detail-value">${vehicle.engine || '-'}</div>
          </div>
          <div class="detail-row">
            <span class="detail-label">Engine Type</span>
            <div class="detail-value">${vehicle.engine_type || '-'}</div>
          </div>
          <div class="detail-row">
            <span class="detail-label">CC</span>
            <div class="detail-value">${vehicle.cc || '-'}</div>
          </div>
        </div>
      </div>
    `;

    const col3 = `
      <div class="detail-section">
        <div class="detail-section-header">IDENTIFICATION</div>
        <div class="detail-section-body">
          <div class="detail-row">
            <span class="detail-label">Engine No</span>
            <div class="detail-value">${vehicle.engine_no || '-'}</div>
          </div>
          <div class="detail-row">
            <span class="detail-label">Chassis No</span>
            <div class="detail-value">${vehicle.chassis_no || '-'}</div>
          </div>
          <div class="detail-row">
            <span class="detail-label">Vehicle ID</span>
            <div class="detail-value">${vehicle.vehicle_id || '-'}</div>
          </div>
          <div class="detail-row">
            <span class="detail-label">From</span>
            <div class="detail-value">${formatDate(vehicle.from)}</div>
          </div>
          <div class="detail-row">
            <span class="detail-label">To</span>
            <div class="detail-value">${formatDate(vehicle.to)}</div>
          </div>
        </div>
      </div>
    `;

    const col4 = `
      <div class="detail-section">
        <div class="detail-section-header">NOTES</div>
        <div class="detail-section-body">
          <div class="detail-row" style="align-items:flex-start;">
            <span class="detail-label">Notes</span>
            <textarea class="detail-value" style="min-height:200px; resize:vertical; flex:1; font-size:11px; padding:4px 6px;" readonly>${vehicle.notes || ''}</textarea>
          </div>
        </div>
      </div>
    `;

    content.innerHTML = col1 + col2 + col3 + col4;
  }

  // Open vehicle page (Add or Edit)
  async function openVehiclePage(mode) {
    if (mode === 'add') {
      openVehicleForm('add');
    } else {
      if (currentVehicleId) {
        openEditVehicle(currentVehicleId);
      }
    }
  }

  // Add Vehicle Button
  const addVehicleBtn = document.getElementById('addVehicleBtn');
  if (addVehicleBtn) {
    addVehicleBtn.addEventListener('click', () => openVehiclePage('add'));
  }
  
  const columnBtn2 = document.getElementById('columnBtn2');
  if (columnBtn2) {
    columnBtn2.addEventListener('click', () => openColumnModal());
  }

  async function openEditVehicle(id) {
    try {
      const res = await fetch(`/vehicles/${id}/edit`, {
        headers: {
          'Accept': 'application/json',
          'X-Requested-With': 'XMLHttpRequest'
        }
      });
      if (!res.ok) throw new Error('Network error');
      const vehicle = await res.json();
      currentVehicleId = id;
      openVehicleForm('edit', vehicle);
    } catch (e) {
      console.error(e);
      alert('Error loading vehicle data');
    }
  }

  function openVehicleForm(mode, vehicle = null) {
    // Clone form from modal
    const modalForm = document.getElementById('vehicleModal').querySelector('form');
    const pageForm = document.getElementById('vehiclePageForm');
    const formContentDiv = pageForm.querySelector('div[style*="padding:12px"]');
    
    // Clone the modal form body
    const modalBody = modalForm.querySelector('.modal-body');
    if (modalBody && formContentDiv) {
      formContentDiv.innerHTML = modalBody.innerHTML;
    }

    const formMethod = document.getElementById('vehiclePageFormMethod');
    const deleteBtn = document.getElementById('vehicleDeleteBtn');
    const editBtn = document.getElementById('editVehicleFromPageBtn');
    const closeBtn = document.getElementById('closeVehiclePageBtn');
    const closeFormBtn = document.getElementById('closeVehicleFormBtn');

    if (mode === 'add') {
      document.getElementById('vehiclePageTitle').textContent = 'Add Vehicle';
      document.getElementById('vehiclePageName').textContent = '';
      pageForm.action = vehiclesStoreRoute;
      formMethod.innerHTML = '';
      deleteBtn.style.display = 'none';
      if (editBtn) editBtn.style.display = 'none';
      if (closeBtn) closeBtn.style.display = 'inline-block';
      if (closeFormBtn) closeFormBtn.style.display = 'none';
      pageForm.reset();
    } else {
      const vehicleName = vehicle.regn_no || vehicle.vehicle_id || 'Unknown';
      document.getElementById('vehiclePageTitle').textContent = 'Edit Vehicle';
      document.getElementById('vehiclePageName').textContent = vehicleName;
      pageForm.action = `/vehicles/${currentVehicleId}`;
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

      const fields = ['regn_no','make','model','type','useage','year','value','policy_id','engine','engine_type','cc','engine_no','chassis_no','from','to','notes'];
      fields.forEach(k => {
        const el = formContentDiv ? formContentDiv.querySelector(`#${k}`) : null;
        if (!el) return;
        if (el.type === 'date') {
          el.value = vehicle[k] ? (typeof vehicle[k] === 'string' ? vehicle[k].substring(0,10) : vehicle[k]) : '';
        } else if (el.tagName === 'TEXTAREA') {
          el.value = vehicle[k] ?? '';
        } else {
          el.value = vehicle[k] ?? '';
        }
      });
    }

    // Hide table view, show page view
    document.getElementById('clientsTableView').classList.add('hidden');
    const vehiclePageView = document.getElementById('vehiclePageView');
    vehiclePageView.style.display = 'block';
    vehiclePageView.classList.add('show');
    document.getElementById('vehicleDetailsPageContent').style.display = 'none';
    document.getElementById('vehicleFormPageContent').style.display = 'block';
  }

  function closeVehiclePageView() {
    const vehiclePageView = document.getElementById('vehiclePageView');
    vehiclePageView.classList.remove('show');
    vehiclePageView.style.display = 'none';
    document.getElementById('clientsTableView').classList.remove('hidden');
    document.getElementById('vehicleDetailsPageContent').style.display = 'none';
    document.getElementById('vehicleFormPageContent').style.display = 'none';
    currentVehicleId = null;
  }

  // Edit button from details page
  const editBtn = document.getElementById('editVehicleFromPageBtn');
  if (editBtn) {
    editBtn.addEventListener('click', function() {
      if (currentVehicleId) {
        openEditVehicle(currentVehicleId);
      }
    });
  }

  // Column modal functions are provided by partials-table-scripts.js

  function deleteVehicle() {
    if (!currentVehicleId) return;
    if (!confirm('Delete this vehicle?')) return;
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = `/vehicles/${currentVehicleId}`;
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

  // Legacy function for backward compatibility
  function openVehicleModal(mode, vehicle = null) {
    if (mode === 'add') {
      openVehiclePage('add');
    } else if (vehicle && currentVehicleId) {
      openEditVehicle(currentVehicleId);
    }
  }

  function closeVehicleModal() {
    closeVehiclePageView();
  }
