@extends('layouts.app')
@section('content')

@include('partials.table-styles')

<style>
  .filter-toggle {
    display: flex;
    align-items: center;
    gap: 8px;
  }
  
  .toggle-switch {
    position: relative;
    display: inline-block;
    width: 44px;
    height: 24px;
  }
  
  .toggle-switch input {
    opacity: 0;
    width: 0;
    height: 0;
  }
  
  .toggle-slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #ccc;
    transition: .4s;
    border-radius: 24px;
  }
  
  .toggle-slider:before {
    position: absolute;
    content: "";
    height: 18px;
    width: 18px;
    left: 3px;
    bottom: 3px;
    background-color: white;
    transition: .4s;
    border-radius: 50%;
  }
  
  .toggle-switch input:checked + .toggle-slider {
    background-color: #f3742a;
  }
  
  .toggle-switch input:checked + .toggle-slider:before {
    transform: translateX(20px);
  }
  
  .column-filter {
    display: none;
  }
</style>

@php
  $config = \App\Helpers\TableConfigHelper::getConfig('vehicles');
  $selectedColumns = \App\Helpers\TableConfigHelper::getSelectedColumns('vehicles');
  $columnDefinitions = $config['column_definitions'] ?? [];
  $mandatoryColumns = $config['mandatory_columns'] ?? [];
@endphp

<div class="dashboard">
  <!-- Main Vehicles Table View -->
  <div class="clients-table-view" id="clientsTableView">
  <div class="container-table">
    <!-- Vehicles Card -->
    <div style="background:#fff; border:1px solid #ddd; border-radius:4px; overflow:hidden;">
      <div class="page-header" style="background:#fff; border-bottom:1px solid #ddd; margin-bottom:0;">
      <div class="page-title-section">
        <h3>
          @if($policy)
            {{ $policy->policy_no }} - 
          @endif
          <span style="color:#f3742a;">Vehicles</span>
        </h3>
        <div class="records-found">Records Found - {{ $vehicles->total() }}</div>
        <div style="display:flex; align-items:center; gap:15px; margin-top:10px;">
          <div class="filter-group">
            <div class="filter-toggle">
              <label class="toggle-switch">
                <input type="checkbox" id="filterToggle" onchange="toggleFilter()">
                <span class="toggle-slider"></span>
              </label>
              <span style="font-size:13px; color:#555;">Filter</span>
            </div>
          </div>
        </div>
      </div>
      <div class="action-buttons">
        <!-- <button class="btn btn-add" id="addVehicleBtn">Add</button> -->
        <a href="{{ $policy ? route('policies.show', $policy->id) : route('policies.index') }}" class="btn" style="background:#6c757d; color:#fff; border:none; padding:6px 16px; border-radius:2px; cursor:pointer; text-decoration:none; font-size:13px;">Back</a>
      </div>
    </div>

    @if(session('success'))
      <div class="alert alert-success" id="successAlert" style="padding:8px 12px; margin:15px 20px; border:1px solid #c3e6cb; background:#d4edda; color:#155724;">
        {{ session('success') }}
        <button type="button" class="alert-close" onclick="document.getElementById('successAlert').style.display='none'" style="float:right;background:none;border:none;font-size:16px;cursor:pointer;">×</button>
      </div>
    @endif

    <div class="table-responsive" id="tableResponsive">
      <table id="vehiclesTable">
        <thead>
          <tr>
            <th style="text-align:center;">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="display:inline-block; vertical-align:middle;">
                <path d="M12 2C8.13 2 5 5.13 5 9C5 14.25 2 16 2 16H22C22 16 19 14.25 19 9C19 5.13 15.87 2 12 2Z" fill="#fff" stroke="#fff" stroke-width="1.5"/>
                <path d="M9 21C9 22.1 9.9 23 11 23H13C14.1 23 15 22.1 15 21H9Z" fill="#fff"/>
              </svg>
            </th>
            <th>Action</th>
            @foreach($selectedColumns as $col)
              @if(isset($columnDefinitions[$col]))
                <th data-column="{{ $col }}">{{ $columnDefinitions[$col] }}</th>
              @endif
            @endforeach
          </tr>
        </thead>
        <tbody>
          @foreach($vehicles as $vh)
            @php
              $hasNoPolicy = empty($vh->policy_id);
            @endphp
            <tr>
            <td class="bell-cell {{ $hasNoPolicy ? 'no-policy' : '' }}">
                <div style="display:flex; align-items:center; justify-content:center;">
                  <div class="status-indicator {{ $hasNoPolicy ? 'no-policy' : 'normal' }}" style="width:18px; height:18px; border-radius:50%; border:2px solid {{ $hasNoPolicy ? '#555' : '#f3742a' }}; background-color:{{ $hasNoPolicy ? '#555' : 'transparent' }};"></div>
                </div>
              </td>
              <td class="action-cell"><!-- onclick="openVehicleDetails({{ $vh->id }})" -->
                <svg class="action-expand" width="22" height="22" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="cursor:pointer; vertical-align:middle;">
                  <!-- Maximize icon: four arrows pointing outward from center -->
                  <!-- Top arrow -->
                  <path d="M12 2L12 8M12 2L10 4M12 2L14 4" stroke="#2d2d2d" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                  <!-- Right arrow -->
                  <path d="M22 12L16 12M22 12L20 10M22 12L20 14" stroke="#2d2d2d" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                  <!-- Bottom arrow -->
                  <path d="M12 22L12 16M12 22L10 20M12 22L14 20" stroke="#2d2d2d" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                  <!-- Left arrow -->
                  <path d="M2 12L8 12M2 12L4 10M2 12L4 14" stroke="#2d2d2d" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
              </td>
              @foreach($selectedColumns as $col)
                @if($col == 'regn_no')
                  <td data-column="regn_no">
                    <a href="javascript:void(0)" onclick="openVehicleDetails({{ $vh->id }})" style="color:#007bff; text-decoration:underline;">{{ $vh->regn_no }}</a>
                  </td>
                @elseif($col == 'make')
                  <td data-column="make">{{ $vh->make ?? '-' }}</td>
                @elseif($col == 'model')
                  <td data-column="model">{{ $vh->model ?? '-' }}</td>
                @elseif($col == 'type')
                  <td data-column="type">{{ $vh->type ?? '-' }}</td>
                @elseif($col == 'useage')
                  <td data-column="useage">{{ $vh->useage ?? '-' }}</td>
                @elseif($col == 'year')
                  <td data-column="year">{{ $vh->year ?? '-' }}</td>
                @elseif($col == 'value')
                  <td data-column="value">{{ $vh->value ? number_format($vh->value, 2) : '-' }}</td>
                @elseif($col == 'policy_id')
                  <td data-column="policy_id">{{ $vh->policy_id ?? '-' }}</td>
                @elseif($col == 'engine')
                  <td data-column="engine">{{ $vh->engine ?? '-' }}</td>
                @elseif($col == 'engine_type')
                  <td data-column="engine_type">{{ $vh->engine_type ?? '-' }}</td>
                @elseif($col == 'cc')
                  <td data-column="cc">{{ $vh->cc ?? '-' }}</td>
                @elseif($col == 'engine_no')
                  <td data-column="engine_no">{{ $vh->engine_no ?? '-' }}</td>
                @elseif($col == 'chassis_no')
                  <td data-column="chassis_no">{{ $vh->chassis_no ?? '-' }}</td>
                @elseif($col == 'from')
                  <td data-column="from">{{ $vh->from ? \Carbon\Carbon::parse($vh->from)->format('d-M-y') : '-' }}</td>
                @elseif($col == 'to')
                  <td data-column="to">{{ $vh->to ? \Carbon\Carbon::parse($vh->to)->format('d-M-y') : '-' }}</td>
                @elseif($col == 'notes')
                  <td data-column="notes">{{ $vh->notes ?? '-' }}</td>
                @elseif($col == 'vehicle_id')
                  <td data-column="vehicle_id">{{ $vh->vehicle_id ?? '-' }}</td>
                @endif
              @endforeach
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    </div>

    <div class="footer" style="background:#fff; border-top:1px solid #ddd; padding:10px 20px; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap;">
      <div class="footer-left">
        <a class="btn btn-export" href="{{ route('vehicles.export') }}">Export</a>
        <button class="btn btn-column" id="columnBtn2" type="button">Column</button>
        <button class="btn btn-export" id="printBtn" type="button" style="margin-left:10px;">Print</button>
      </div>
      <div class="paginator">
        @php
          $base = url()->current();
          $q = request()->query();
          $current = $vehicles->currentPage();
          $last = max(1, $vehicles->lastPage());
          function page_url($base, $q, $p) {
            $params = array_merge($q, ['page' => $p]);
            return $base . '?' . http_build_query($params);
          }
        @endphp

        <a class="btn-page" href="{{ $current > 1 ? page_url($base, $q, 1) : '#' }}" @if($current <= 1) disabled @endif>&laquo;</a>
        <a class="btn-page" href="{{ $current > 1 ? page_url($base, $q, $current - 1) : '#' }}" @if($current <= 1) disabled @endif>&lsaquo;</a>

        <span style="padding:0 8px;">Page {{ $current }} of {{ $last }}</span>

        <a class="btn-page" href="{{ $current < $last ? page_url($base, $q, $current + 1) : '#' }}" @if($current >= $last) disabled @endif>&rsaquo;</a>
        <a class="btn-page" href="{{ $current < $last ? page_url($base, $q, $last) : '#' }}" @if($current >= $last) disabled @endif>&raquo;</a>
      </div>
    </div>
    </div>
  </div>

  <!-- Vehicle Page View (Full Page) -->
  <div class="client-page-view" id="vehiclePageView" style="display:none;">
    <div class="client-page-header">
      <div class="client-page-title">
        <span id="vehiclePageTitle">Vehicle</span> - <span class="client-name" id="vehiclePageName"></span>
      </div>
      <div class="client-page-actions">
        <button class="btn btn-edit" id="editVehicleFromPageBtn" style="background:#f3742a; color:#fff; border:none; padding:6px 16px; border-radius:2px; cursor:pointer; display:none;">Edit</button>
        <button class="btn" id="closeVehiclePageBtn" onclick="closeVehiclePageView()" style="background:#e0e0e0; color:#000; border:none; padding:6px 16px; border-radius:2px; cursor:pointer;">Close</button>
      </div>
    </div>
    <div class="client-page-body">
      <div class="client-page-content">
        <!-- Vehicle Details View -->
        <div id="vehicleDetailsPageContent" style="display:none;">
          <div style="background:#fff; border:1px solid #ddd; border-radius:4px; margin-bottom:15px; overflow:hidden;">
            <div id="vehicleDetailsContent" style="display:grid; grid-template-columns:repeat(4, 1fr); gap:0; align-items:start; padding:12px;">
              <!-- Content will be loaded via JavaScript -->
            </div>
          </div>
        </div>

        <!-- Vehicle Edit/Add Form -->
        <div id="vehicleFormPageContent" style="display:none;">
          <div style="background:#fff; border:1px solid #ddd; border-radius:4px; margin-bottom:15px; overflow:hidden;">
            <div style="display:flex; justify-content:flex-end; align-items:center; padding:12px 15px; border-bottom:1px solid #ddd; background:#fff;">
              <div class="client-page-actions">
                <button type="button" class="btn-delete" id="vehicleDeleteBtn" style="display:none; background:#dc3545; color:#fff; border:none; padding:6px 16px; border-radius:2px; cursor:pointer;" onclick="deleteVehicle()">Delete</button>
                <button type="submit" form="vehiclePageForm" class="btn-save" style="background:#f3742a; color:#fff; border:none; padding:6px 16px; border-radius:2px; cursor:pointer;">Save</button>
                <button type="button" class="btn" id="closeVehicleFormBtn" onclick="closeVehiclePageView()" style="background:#e0e0e0; color:#000; border:none; padding:6px 16px; border-radius:2px; cursor:pointer; display:none;">Close</button>
              </div>
            </div>
            <form id="vehiclePageForm" method="POST" action="{{ route('vehicles.store') }}">
              @csrf
              <div id="vehiclePageFormMethod" style="display:none;"></div>
              <div style="padding:12px;">
                <!-- Form content will be cloned from modal -->
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Add/Edit Vehicle Modal (hidden, used for form structure) -->
  <div class="modal" id="vehicleModal">
    <div class="modal-content">
      <div class="modal-header">
        <h4 id="vehicleModalTitle">Add Vehicle</h4>
        <button type="button" class="modal-close" onclick="closeVehicleModal()">×</button>
      </div>
      <form id="vehicleForm" method="POST" action="{{ route('vehicles.store') }}">
        @csrf
        <div id="vehicleFormMethod" style="display:none;"></div>
        <div class="modal-body">
          <div class="form-row">
            <div class="form-group">
              <label for="regn_no">Regn No *</label>
              <input type="text" class="form-control" name="regn_no" id="regn_no" required>
            </div>
            <div class="form-group">
              <label for="make">Make</label>
              <input type="text" class="form-control" name="make" id="make">
            </div>
            <div class="form-group">
              <label for="model">Model</label>
              <input type="text" class="form-control" name="model" id="model">
            </div>
          </div>
          <div class="form-row">
            <div class="form-group">
              <label for="type">Type</label>
              <input type="text" class="form-control" name="type" id="type">
            </div>
            <div class="form-group">
              <label for="useage">Usage</label>
              <input type="text" class="form-control" name="useage" id="useage">
            </div>
            <div class="form-group">
              <label for="year">Year</label>
              <input type="text" class="form-control" name="year" id="year">
            </div>
          </div>
          <div class="form-row">
            <div class="form-group">
              <label for="value">Value</label>
              <input type="number" step="0.01" class="form-control" name="value" id="value">
            </div>
            <div class="form-group">
              <label for="policy_id">Policy ID</label>
              <input type="text" class="form-control" name="policy_id" id="policy_id">
            </div>
            <div class="form-group">
              <label for="engine">Engine</label>
              <input type="text" class="form-control" name="engine" id="engine">
            </div>
          </div>
          <div class="form-row">
            <div class="form-group">
              <label for="engine_type">Engine Type</label>
              <input type="text" class="form-control" name="engine_type" id="engine_type">
            </div>
            <div class="form-group">
              <label for="cc">CC</label>
              <input type="text" class="form-control" name="cc" id="cc">
            </div>
            <div class="form-group">
              <label for="engine_no">Engine No</label>
              <input type="text" class="form-control" name="engine_no" id="engine_no">
            </div>
          </div>
          <div class="form-row">
            <div class="form-group">
              <label for="chassis_no">Chassis No</label>
              <input type="text" class="form-control" name="chassis_no" id="chassis_no">
            </div>
            <div class="form-group">
              <label for="from">From</label>
              <input type="date" class="form-control" name="from" id="from">
            </div>
            <div class="form-group">
              <label for="to">To</label>
              <input type="date" class="form-control" name="to" id="to">
            </div>
          </div>
          <div class="form-row">
            <div class="form-group" style="flex:1 1 100%;">
              <label for="notes">Notes</label>
              <textarea class="form-control" name="notes" id="notes" rows="2"></textarea>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn-cancel" onclick="closeVehicleModal()">Cancel</button>
          <button type="button" class="btn-delete" id="vehicleDeleteBtn" style="display: none;" onclick="deleteVehicle()">Delete</button>
          <button type="submit" class="btn-save">Save</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Column Selection Modal -->
  <div class="modal" id="columnModal">
    <div class="modal-content">
      <div class="modal-header">
        <h4>Column Select & Sort</h4>
        <button type="button" class="modal-close" onclick="closeColumnModal()">×</button>
      </div>
      <div class="modal-body">
        <div class="column-actions">
          <button type="button" class="btn-select-all" onclick="selectAllColumns()">Select All</button>
          <button type="button" class="btn-deselect-all" onclick="deselectAllColumns()">Deselect All</button>
        </div>

        <form id="columnForm" action="{{ route('vehicles.save-column-settings') }}" method="POST">
          @csrf
          <div class="column-selection" id="columnSelection">
            @php
              $all = $config['column_definitions'];
              // Maintain order based on selectedColumns
              $ordered = [];
              foreach($selectedColumns as $col) {
                if(isset($all[$col])) {
                  $ordered[$col] = $all[$col];
                  unset($all[$col]);
                }
              }
              $ordered = array_merge($ordered, $all);
            @endphp

            @foreach($ordered as $key => $label)
              @php
                $isMandatory = in_array($key, $mandatoryColumns);
                $isChecked = in_array($key, $selectedColumns) || $isMandatory;
              @endphp
              <div class="column-item" draggable="true" data-column="{{ $key }}" style="cursor:move;">
                <span style="cursor:move; margin-right:8px; font-size:16px; color:#666;">☰</span>
                <input type="checkbox" class="column-checkbox" id="col_{{ $key }}" value="{{ $key }}" @if($isChecked) checked @endif @if($isMandatory) disabled @endif>
                <label for="col_{{ $key }}" style="cursor:pointer; flex:1; user-select:none;">{{ $label }}</label>
              </div>
            @endforeach
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button class="btn-cancel" onclick="closeColumnModal()">Cancel</button>
        <button class="btn-save" onclick="saveColumnSettings()">Save Settings</button>
      </div>
    </div>
  </div>

</div>

<script>
  let currentVehicleId = null;
  const selectedColumns = @json($selectedColumns);
  const mandatoryColumns = @json($mandatoryColumns);

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
      pageForm.action = '{{ route("vehicles.store") }}';
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

  function deleteVehicle() {
    if (!currentVehicleId) return;
    if (!confirm('Delete this vehicle?')) return;
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = `/vehicles/${currentVehicleId}`;
    const csrf = document.createElement('input');
    csrf.type = 'hidden';
    csrf.name = '_token';
    csrf.value = '{{ csrf_token() }}';
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
</script>

@include('partials.table-scripts', [
  'mandatoryColumns' => $mandatoryColumns,
])

@endsection
