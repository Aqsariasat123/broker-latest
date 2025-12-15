@extends('layouts.app')
@section('content')

@include('partials.table-styles')

<style>
  /* Bell cell styles matching clients page */
  .bell-cell { text-align:center; padding:8px 5px; vertical-align:middle; min-width:50px; }
  .bell-cell.expired { background-color:#ffebee !important; }
  .bell-cell.expiring { background-color:#fff8e1 !important; }
  .bell-cell:not(.expired):not(.expiring) { background-color:#fff !important; }
  tbody tr.has-expired { background-color:#ffebee !important; }
  tbody tr.has-expired td { background-color:#ffebee !important; }
  tbody tr.has-expiring { background-color:#fff8e1 !important; }
  tbody tr.has-expiring td { background-color:#fff8e1 !important; }
  .footer { padding:15px 20px; border-top:1px solid #ddd; display:flex; justify-content:center; gap:10px; background:#f9f9f9; }

</style>

@php
  $config = \App\Helpers\TableConfigHelper::getConfig('incomes');
  $selectedColumns = \App\Helpers\TableConfigHelper::getSelectedColumns('incomes');
  $columnDefinitions = $config['column_definitions'] ?? [];
  $mandatoryColumns = $config['mandatory_columns'] ?? [];
@endphp

<div class="dashboard">
  <!-- Main Incomes Table View -->
  <div class="clients-table-view" id="clientsTableView">
  <div class="container-table">
    <!-- Incomes Card -->
    <div style="background:#fff; border:1px solid #ddd; border-radius:4px; overflow:hidden;">
      <div class="page-header" style="background:#fff; border-bottom:1px solid #ddd; margin-bottom:0;">
      <div class="page-title-section">
    <h3>Income</h3>
        <div class="records-found">Records Found - {{ $incomes->total() }}</div>
      </div>
      <div class="action-buttons">
        @if(auth()->check() && (auth()->user()->hasPermission('incomes.create') || auth()->user()->isAdmin()))
        <button class="btn btn-add" id="addIncomeBtn">Add</button>
        @endif
      </div>
    </div>

    @if(session('success'))
      <div class="alert alert-success" id="successAlert" style="padding:8px 12px; margin:15px 20px; border:1px solid #c3e6cb; background:#d4edda; color:#155724;">
        {{ session('success') }}
        <button type="button" class="alert-close" onclick="document.getElementById('successAlert').style.display='none'" style="float:right;background:none;border:none;font-size:16px;cursor:pointer;">×</button>
      </div>
    @endif

    <div class="table-responsive" id="tableResponsive">
      <table id="incomesTable">
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
          @foreach($incomes as $inc)
          <tr class="{{ $inc->hasExpired ?? false ? 'has-expired' : ($inc->hasExpiring ?? false ? 'has-expiring' : '') }}">
              <td class="bell-cell {{ $inc->hasExpired ?? false ? 'expired' : ($inc->hasExpiring ?? false ? 'expiring' : '') }}">
                <div style="display:flex; align-items:center; justify-content:center;">
                  @php
                    $isExpired = $inc->hasExpired ?? false;
                    $isExpiring = $inc->hasExpiring ?? false;
                  @endphp
                  <div class="status-indicator {{ $isExpired ? 'expired' : ($isExpiring ? 'expiring' : 'normal') }}" style="width:18px; height:18px; border-radius:50%; border:2px solid #000; background-color:{{ $isExpired ? '#dc3545' : ($isExpiring ? '#ffc107' : 'transparent') }};"></div>
                </div>
              </td>
              <td class="action-cell">
                <svg class="action-expand" onclick="openIncomeDetails({{ $inc->id }})" width="22" height="22" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="cursor:pointer; vertical-align:middle;">
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
                @if($col == 'income_id')
                  <td data-column="income_id">{{ $inc->income_id }}</td>
                @elseif($col == 'income_source')
                  <td data-column="income_source">{{ $inc->incomeSource ? $inc->incomeSource->name : '-' }}</td>
                @elseif($col == 'date_rcvd')
                  <td data-column="date_rcvd">{{ $inc->date_rcvd ? $inc->date_rcvd->format('d-M-y') : '-' }}</td>
                @elseif($col == 'amount_received')
                  <td data-column="amount_received">{{ $inc->amount_received ? number_format($inc->amount_received, 2) : '-' }}</td>
                @elseif($col == 'description')
                  <td data-column="description">{{ $inc->description ?? '-' }}</td>
                @elseif($col == 'category_id')
                  <td data-column="category_id">{{ $inc->incomeCategory ? $inc->incomeCategory->name : '-' }}</td>
                @elseif($col == 'mode_of_payment')
                  <td data-column="mode_of_payment">{{ $inc->modeOfPayment ? $inc->modeOfPayment->name : '-' }}</td>
                @elseif($col == 'statement_no')
                  <td data-column="statement_no">{{ $inc->statement_no ?? '-' }}</td>
                @elseif($col == 'income_notes')
                  <td data-column="income_notes">{{ $inc->income_notes ?? '-' }}</td>
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
        <a class="btn btn-export" href="{{ route('incomes.export', array_merge(request()->query(), ['page' => $incomes->currentPage()])) }}">Export</a>
        <button class="btn btn-column" id="columnBtn2" type="button">Column</button>
      </div>
      <div class="paginator">
        @php
          $base = url()->current();
          $q = request()->query();
          $current = $incomes->currentPage();
          $last = max(1, $incomes->lastPage());
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

  <!-- Income Page View (Full Page) -->
  <div class="client-page-view" id="incomePageView" style="display:none;">
    <div class="client-page-header">
      <div class="client-page-title">
        <span id="incomePageTitle">Income</span> - <span class="client-name" id="incomePageName"></span>
      </div>
      <div class="client-page-actions">
        <button class="btn btn-edit" id="editIncomeFromPageBtn" style="background:#f3742a; color:#fff; border:none; padding:6px 16px; border-radius:2px; cursor:pointer; display:none;">Edit</button>
        <button class="btn" id="closeIncomePageBtn" onclick="closeIncomePageView()" style="background:#e0e0e0; color:#000; border:none; padding:6px 16px; border-radius:2px; cursor:pointer;">Close</button>
      </div>
    </div>
    <div class="client-page-body">
      <div class="client-page-content">
        <!-- Income Details View -->
        <div id="incomeDetailsPageContent" style="display:none;">
          <div style="background:#fff; border:1px solid #ddd; border-radius:4px; margin-bottom:15px; overflow:hidden;">
            <div id="incomeDetailsContent" style="display:grid; grid-template-columns:repeat(4, 1fr); gap:0; align-items:start; padding:12px;">
              <!-- Content will be loaded via JavaScript -->
            </div>
          </div>
        </div>

        <!-- Income Edit/Add Form -->
        <div id="incomeFormPageContent" style="display:none;">
          <div style="background:#fff; border:1px solid #ddd; border-radius:4px; margin-bottom:15px; overflow:hidden;">
            <div style="display:flex; justify-content:flex-end; align-items:center; padding:12px 15px; border-bottom:1px solid #ddd; background:#fff;">
              <div class="client-page-actions">
                <button type="button" class="btn-delete" id="incomeDeleteBtn" style="display:none; background:#dc3545; color:#fff; border:none; padding:6px 16px; border-radius:2px; cursor:pointer;" onclick="deleteIncome()">Delete</button>
                <button type="submit" form="incomePageForm" class="btn-save" style="background:#f3742a; color:#fff; border:none; padding:6px 16px; border-radius:2px; cursor:pointer;">Save</button>
                <button type="button" class="btn" id="closeIncomeFormBtn" onclick="closeIncomePageView()" style="background:#e0e0e0; color:#000; border:none; padding:6px 16px; border-radius:2px; cursor:pointer; display:none;">Close</button>
              </div>
            </div>
            <form id="incomePageForm" method="POST" action="{{ route('incomes.store') }}">
              @csrf
              <div id="incomePageFormMethod" style="display:none;"></div>
              <div style="padding:12px;">
                <!-- Form content will be cloned from modal -->
          </div>
        </form>
      </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Add/Edit Income Modal -->
  <div class="modal" id="incomeModal">
    <div class="modal-content" style="max-width:800px; max-height:90vh; overflow-y:auto;">
      <div class="modal-header" style="display:flex; justify-content:space-between; align-items:center; padding:15px 20px; border-bottom:1px solid #ddd; background:#fff;">
        <h4 id="incomeModalTitle" style="margin:0; font-size:18px; font-weight:bold;">Add Income</h4>
        <div style="display:flex; gap:10px;">
          <button type="submit" form="incomeForm" class="btn-save" style="background:#f3742a; color:#fff; border:none; padding:8px 20px; border-radius:2px; cursor:pointer; font-size:13px;">Save</button>
          <button type="button" class="btn-cancel" onclick="closeIncomeModal()" style="background:#000; color:#fff; border:none; padding:8px 20px; border-radius:2px; cursor:pointer; font-size:13px;">Cancel</button>
        </div>
      </div>
      <form id="incomeForm" method="POST" action="{{ route('incomes.store') }}" enctype="multipart/form-data">
        @csrf
        <div id="incomeFormMethod" style="display:none;"></div>
        <input type="file" name="document" id="documentFileInput" style="display:none;" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
        <div class="modal-body" style="padding:20px;">
          <div class="form-row" style="display:flex; gap:15px; margin-bottom:15px;">
            <div class="form-group" style="flex:1;">
              <label for="income_source_id" style="display:block; margin-bottom:5px; font-size:13px; font-weight:500;">Income Source</label>
              <select class="form-control" name="income_source_id" id="income_source_id" style="width:100%; padding:8px; border:1px solid #ddd; border-radius:2px; font-size:13px;">
                <option value="">Select</option>
                @foreach($incomeSources as $src)
                  <option value="{{ $src->id }}">{{ $src->name }}</option>
                @endforeach
              </select>
            </div>
            <div class="form-group" style="flex:1;">
              <label for="date_rcvd" style="display:block; margin-bottom:5px; font-size:13px; font-weight:500;">Date Received</label>
              <input type="date" class="form-control" name="date_rcvd" id="date_rcvd" style="width:100%; padding:8px; border:1px solid #ddd; border-radius:2px; font-size:13px;">
            </div>
            <div class="form-group" style="flex:1;">
              <label for="amount_received" style="display:block; margin-bottom:5px; font-size:13px; font-weight:500;">Amount Received</label>
              <input type="number" step="0.01" class="form-control" name="amount_received" id="amount_received" style="width:100%; padding:8px; border:1px solid #ddd; border-radius:2px; font-size:13px;">
            </div>
          </div>
          <div class="form-row" style="display:flex; gap:15px; margin-bottom:15px;">
            <div class="form-group" style="flex:1;">
              <label for="category_id" style="display:block; margin-bottom:5px; font-size:13px; font-weight:500;">Category</label>
              <select class="form-control" name="category_id" id="category_id" style="width:100%; padding:8px; border:1px solid #ddd; border-radius:2px; font-size:13px;">
                <option value="">Select Category</option>
                @if(isset($incomeCategories))
                  @foreach($incomeCategories as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                  @endforeach
                @endif
              </select>
            </div>
            <div class="form-group" style="flex:1;">
              <label for="description" style="display:block; margin-bottom:5px; font-size:13px; font-weight:500;">Description</label>
              <input type="text" class="form-control" name="description" id="description" style="width:100%; padding:8px; border:1px solid #ddd; border-radius:2px; font-size:13px;">
            </div>
            <div class="form-group" style="flex:1;">
              <label for="mode_of_payment_id" style="display:block; margin-bottom:5px; font-size:13px; font-weight:500;">Mode Of Payment</label>
              <select class="form-control" name="mode_of_payment_id" id="mode_of_payment_id" style="width:100%; padding:8px; border:1px solid #ddd; border-radius:2px; font-size:13px;">
                <option value="">Select</option>
                @foreach($modesOfPayment as $mode)
                  <option value="{{ $mode->id }}">{{ $mode->name }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="form-row" style="display:flex; gap:15px; margin-bottom:15px;">
            <div class="form-group" style="flex:1;">
              <label for="statement_no" style="display:block; margin-bottom:5px; font-size:13px; font-weight:500;">Statement No.</label>
              <input type="text" class="form-control" name="statement_no" id="statement_no" style="width:100%; padding:8px; border:1px solid #ddd; border-radius:2px; font-size:13px;">
            </div>
          </div>
          <div class="form-row" style="display:flex; gap:15px; margin-bottom:15px;">
            <div class="form-group" style="flex:1 1 100%;">
              <label for="income_notes" style="display:block; margin-bottom:5px; font-size:13px; font-weight:500;">Income Notes</label>
              <textarea class="form-control" name="income_notes" id="income_notes" rows="4" style="width:100%; padding:8px; border:1px solid #ddd; border-radius:2px; font-size:13px; resize:vertical;"></textarea>
            </div>
          </div>
          <div id="selectedDocumentPreview" style="margin-top:15px; padding:10px; background:#f5f5f5; border-radius:4px; display:none;">
            <div style="display:flex; justify-content:space-between; align-items:center;">
              <div>
                <p style="margin:0; font-size:12px; color:#666; font-weight:500;">Selected Document:</p>
                <p id="selectedDocumentName" style="margin:5px 0 0 0; font-size:13px; color:#000;"></p>
              </div>
              <button type="button" onclick="removeSelectedDocument()" style="background:#dc3545; color:#fff; border:none; padding:4px 10px; border-radius:2px; cursor:pointer; font-size:11px;">Remove</button>
            </div>
            <div id="selectedDocumentImagePreview" style="margin-top:10px; max-width:200px; max-height:200px;"></div>
          </div>
        </div>
        <div class="modal-footer" style="padding:15px 20px; border-top:1px solid #ddd; background:#fff; display:flex; justify-content:center;">
          <button type="button" class="btn-upload" onclick="openDocumentUploadModal()" style="background:#f3742a; color:#fff; border:none; padding:8px 20px; border-radius:2px; cursor:pointer; font-size:13px;">Upload Document</button>
          <button type="button" class="btn-delete" id="incomeDeleteBtnModal" style="display: none; background:#dc3545; color:#fff; border:none; padding:8px 20px; border-radius:2px; cursor:pointer; font-size:13px; margin-left:10px;" onclick="deleteIncome()">Delete</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Document Upload Modal -->
  <div class="modal" id="documentUploadModal">
    <div class="modal-content" style="max-width:500px;">
      <div class="modal-header">
        <h4>Select Document</h4>
        <button type="button" class="modal-close" onclick="closeDocumentUploadModal()">×</button>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label for="documentFile">Select Document File</label>
          <input type="file" class="form-control" name="document" id="documentFile" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx" onchange="handleDocumentFileSelect(event)">
          <small style="color:#666; font-size:11px;">Accepted formats: PDF, JPG, JPEG, PNG, DOC, DOCX (Max 5MB)</small>
        </div>
        <div id="documentPreview" style="margin-top:15px; display:none;">
          <p style="font-size:12px; color:#666; font-weight:500;">Preview:</p>
          <div id="documentPreviewContent" style="margin-top:10px;"></div>
        </div>
        <div id="existingDocumentPreview" style="margin-top:15px; display:none;">
          <p style="font-size:12px; color:#666;">Current document:</p>
          <div id="existingDocumentPreviewContent"></div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn-cancel" onclick="closeDocumentUploadModal()">Cancel</button>
        <button type="button" class="btn-save" onclick="confirmDocumentSelection()">Select</button>
      </div>
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

        <form id="columnForm" action="{{ route('incomes.save-column-settings') }}" method="POST">
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
let currentIncomeId = null;
  const lookupData = {
    incomeSources: @json($incomeSources),
    modesOfPayment: @json($modesOfPayment)
  };
  const selectedColumns = @json($selectedColumns);
  const mandatoryColumns = @json($mandatoryColumns);
  const canDeleteIncome = @json(auth()->check() && (auth()->user()->hasPermission('incomes.delete') || auth()->user()->isAdmin()));
  const canEditIncome = @json(auth()->check() && (auth()->user()->hasPermission('incomes.edit') || auth()->user()->isAdmin()));

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

  // Open income details (full page view) - MUST be defined before HTML onclick handlers
  async function openIncomeDetails(id) {
    try {
      const res = await fetch(`/incomes/${id}`, {
        headers: {
          'Accept': 'application/json',
          'X-Requested-With': 'XMLHttpRequest'
        }
      });
      if (!res.ok) throw new Error(`HTTP ${res.status}`);
      const income = await res.json();
      currentIncomeId = id;
      
      // Get all required elements
      const incomePageName = document.getElementById('incomePageName');
      const incomePageTitle = document.getElementById('incomePageTitle');
      const clientsTableView = document.getElementById('clientsTableView');
      const incomePageView = document.getElementById('incomePageView');
      const incomeDetailsPageContent = document.getElementById('incomeDetailsPageContent');
      const incomeFormPageContent = document.getElementById('incomeFormPageContent');
      const editIncomeFromPageBtn = document.getElementById('editIncomeFromPageBtn');
      const closeIncomePageBtn = document.getElementById('closeIncomePageBtn');
      
      if (!incomePageName || !incomePageTitle || !clientsTableView || !incomePageView || 
          !incomeDetailsPageContent || !incomeFormPageContent) {
        console.error('Required elements not found');
        alert('Error: Page elements not found');
        return;
      }
      
      // Set income name in header
      const incomeName = income.income_id || 'Unknown';
      incomePageName.textContent = incomeName;
      incomePageTitle.textContent = 'Income';
      
      populateIncomeDetails(income);
      
      // Hide table view, show page view
      clientsTableView.classList.add('hidden');
      incomePageView.style.display = 'block';
      incomePageView.classList.add('show');
      incomeDetailsPageContent.style.display = 'block';
      incomeFormPageContent.style.display = 'none';
      if (editIncomeFromPageBtn) editIncomeFromPageBtn.style.display = 'inline-block';
      if (closeIncomePageBtn) closeIncomePageBtn.style.display = 'inline-block';
    } catch (e) {
      console.error(e);
      alert('Error loading income details: ' + e.message);
    }
  }

  // Populate income details view
  function populateIncomeDetails(income) {
    const content = document.getElementById('incomeDetailsContent');
    if (!content) return;

    const col1 = `
      <div class="detail-section">
        <div class="detail-section-header">INCOME DETAILS</div>
        <div class="detail-section-body">
          <div class="detail-row">
            <span class="detail-label">IncomeID</span>
            <div class="detail-value">${income.income_id || '-'}</div>
          </div>
          <div class="detail-row">
            <span class="detail-label">Income Source</span>
            <div class="detail-value">${income.income_source ? income.income_source.name : (income.incomeSource ? income.incomeSource.name : '-')}</div>
          </div>
          <div class="detail-row">
            <span class="detail-label">Date Rcvd</span>
            <div class="detail-value">${formatDate(income.date_rcvd)}</div>
          </div>
          <div class="detail-row">
            <span class="detail-label">Amount Received</span>
            <div class="detail-value">${formatNumber(income.amount_received)}</div>
          </div>
        </div>
      </div>
    `;

    const col2 = `
      <div class="detail-section">
        <div class="detail-section-header">ADDITIONAL INFO</div>
        <div class="detail-section-body">
          <div class="detail-row">
            <span class="detail-label">Description</span>
            <div class="detail-value">${income.description || '-'}</div>
          </div>
          <div class="detail-row">
            <span class="detail-label">Category</span>
            <div class="detail-value">${income.income_category ? income.income_category.name : (income.incomeCategory ? income.incomeCategory.name : '-')}</div>
          </div>
          <div class="detail-row">
            <span class="detail-label">Mode Of Payment</span>
            <div class="detail-value">${income.mode_of_payment ? income.mode_of_payment.name : (income.modeOfPayment ? income.modeOfPayment.name : '-')}</div>
          </div>
          <div class="detail-row">
            <span class="detail-label">Statement No</span>
            <div class="detail-value">${income.statement_no || '-'}</div>
          </div>
        </div>
      </div>
    `;

    const col3 = `
      <div class="detail-section">
        <div class="detail-section-header">NOTES</div>
        <div class="detail-section-body">
          <div class="detail-row" style="align-items:flex-start;">
            <span class="detail-label">Income Notes</span>
            <textarea class="detail-value" style="min-height:120px; resize:vertical; flex:1; font-size:11px; padding:4px 6px;" readonly>${income.income_notes || ''}</textarea>
          </div>
        </div>
      </div>
    `;

    const col4 = `
    `;

    content.innerHTML = col1 + col2 + col3 + col4;
  }

  // Open income page (Add or Edit)
  async function openIncomePage(mode) {
    if (mode === 'add') {
      openIncomeForm('add');
    } else {
      if (currentIncomeId) {
        openEditIncome(currentIncomeId);
      }
    }
  }

  // Add Income Button - Open Modal
  document.getElementById('addIncomeBtn').addEventListener('click', () => openIncomeModal('add'));
  document.getElementById('columnBtn2').addEventListener('click', () => openColumnModal());

  async function openEditIncome(id) {
    try {
      const res = await fetch(`/incomes/${id}/edit`, {
        headers: {
          'Accept': 'application/json',
          'X-Requested-With': 'XMLHttpRequest'
        }
      });
      if (!res.ok) throw new Error('Network error');
      const income = await res.json();
      currentIncomeId = id;
      openIncomeForm('edit', income);
    } catch (e) {
      console.error(e);
      alert('Error loading income data');
    }
  }

  function openIncomeForm(mode, income = null) {
    // Clone form from modal
    const modalForm = document.getElementById('incomeModal').querySelector('form');
    const pageForm = document.getElementById('incomePageForm');
    const formContentDiv = pageForm.querySelector('div[style*="padding:12px"]');
    
    // Clone the modal form body
    const modalBody = modalForm.querySelector('.modal-body');
    if (modalBody && formContentDiv) {
      formContentDiv.innerHTML = modalBody.innerHTML;
    }

    const formMethod = document.getElementById('incomePageFormMethod');
    const deleteBtn = document.getElementById('incomeDeleteBtn');
    const editBtn = document.getElementById('editIncomeFromPageBtn');
    const closeBtn = document.getElementById('closeIncomePageBtn');
    const closeFormBtn = document.getElementById('closeIncomeFormBtn');

    if (mode === 'add') {
      document.getElementById('incomePageTitle').textContent = 'Add Income';
      document.getElementById('incomePageName').textContent = '';
      pageForm.action = '{{ route("incomes.store") }}';
      formMethod.innerHTML = '';
      deleteBtn.style.display = 'none';
      if (editBtn) editBtn.style.display = 'none';
      if (closeBtn) closeBtn.style.display = 'inline-block';
      if (closeFormBtn) closeFormBtn.style.display = 'none';
      pageForm.reset();
    } else {
      const incomeName = income.income_id || 'Unknown';
      document.getElementById('incomePageTitle').textContent = 'Edit Income';
      document.getElementById('incomePageName').textContent = incomeName;
      pageForm.action = `/incomes/${currentIncomeId}`;
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

      const fields = ['income_source_id','date_rcvd','amount_received','description','category_id','mode_of_payment_id','statement_no','income_notes'];
      fields.forEach(k => {
        const el = formContentDiv ? formContentDiv.querySelector(`#${k}`) : null;
        if (!el) return;
        if (el.type === 'date') {
          el.value = income[k] ? (typeof income[k] === 'string' ? income[k].substring(0,10) : income[k]) : '';
        } else if (el.tagName === 'TEXTAREA') {
          el.value = income[k] ?? '';
        } else {
          el.value = income[k] ?? '';
        }
      });
    }

    // Hide table view, show page view
    document.getElementById('clientsTableView').classList.add('hidden');
    const incomePageView = document.getElementById('incomePageView');
    incomePageView.style.display = 'block';
    incomePageView.classList.add('show');
    document.getElementById('incomeDetailsPageContent').style.display = 'none';
    document.getElementById('incomeFormPageContent').style.display = 'block';
  }

  function closeIncomePageView() {
    const incomePageView = document.getElementById('incomePageView');
    incomePageView.classList.remove('show');
    incomePageView.style.display = 'none';
    document.getElementById('clientsTableView').classList.remove('hidden');
    document.getElementById('incomeDetailsPageContent').style.display = 'none';
    document.getElementById('incomeFormPageContent').style.display = 'none';
    currentIncomeId = null;
  }

  // Edit button from details page
  const editBtn = document.getElementById('editIncomeFromPageBtn');
  if (editBtn) {
    editBtn.addEventListener('click', function() {
      if (currentIncomeId) {
        openEditIncome(currentIncomeId);
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

  function deleteIncome() {
    if (!currentIncomeId) return;
    if (!confirm('Delete this income?')) return;
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = `/incomes/${currentIncomeId}`;
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

  // Open Income Modal
  async function openIncomeModal(mode, incomeId = null) {
    const modal = document.getElementById('incomeModal');
    const form = document.getElementById('incomeForm');
    const formMethod = document.getElementById('incomeFormMethod');
    const title = document.getElementById('incomeModalTitle');
    const deleteBtn = document.getElementById('incomeDeleteBtnModal');
    
    if (!modal || !form || !formMethod || !title) {
      console.error('Required modal elements not found');
      alert('Error: Modal elements not found');
      return;
    }
    
    if (mode === 'add') {
      title.textContent = 'Add Income';
      form.action = '{{ route("incomes.store") }}';
      formMethod.innerHTML = '';
      if (deleteBtn) deleteBtn.style.display = 'none';
      form.reset();
      currentIncomeId = null;
      // Reset document preview
      document.getElementById('selectedDocumentPreview').style.display = 'none';
      document.getElementById('documentFileInput').value = '';
    } else if (mode === 'edit' && incomeId) {
      try {
        const res = await fetch(`/incomes/${incomeId}/edit`, {
          headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
          }
        });
        if (!res.ok) throw new Error('Network error');
        const income = await res.json();
        currentIncomeId = incomeId;
        
        title.textContent = 'Edit Income';
        form.action = `/incomes/${incomeId}`;
        formMethod.innerHTML = '<input type="hidden" name="_method" value="PUT">';
        if (deleteBtn) deleteBtn.style.display = canDeleteIncome ? 'inline-block' : 'none';
        
        // Populate form fields
        document.getElementById('income_source_id').value = income.income_source_id || '';
        document.getElementById('date_rcvd').value = income.date_rcvd ? (typeof income.date_rcvd === 'string' ? income.date_rcvd.substring(0,10) : income.date_rcvd) : '';
        document.getElementById('amount_received').value = income.amount_received || '';
        document.getElementById('category_id').value = income.category_id || (income.income_category ? income.income_category.id : (income.incomeCategory ? income.incomeCategory.id : ''));
        document.getElementById('description').value = income.description || '';
        document.getElementById('mode_of_payment_id').value = income.mode_of_payment_id || '';
        document.getElementById('statement_no').value = income.statement_no || '';
        document.getElementById('income_notes').value = income.income_notes || '';
        
        // Show existing document preview if available (from documents table)
        const previewDiv = document.getElementById('selectedDocumentPreview');
        const docName = document.getElementById('selectedDocumentName');
        const imagePreview = document.getElementById('selectedDocumentImagePreview');
        
        if (income.documents && income.documents.length > 0) {
          const document = income.documents[0];
          if (document && document.file_path) {
            previewDiv.style.display = 'block';
            const fileName = document.file_path.split('/').pop();
            docName.textContent = document.name || fileName;
            // Show link to view document
            imagePreview.innerHTML = `
              <a href="/storage/${document.file_path}" target="_blank" style="color:#007bff; text-decoration:underline; font-size:12px;">
                View Current Document
              </a>
            `;
          } else {
            previewDiv.style.display = 'none';
          }
        } else {
          previewDiv.style.display = 'none';
        }
      } catch (e) {
        console.error(e);
        alert('Error loading income data');
        return;
      }
    }
    
    // Show the modal
    modal.classList.add('show');
    document.body.style.overflow = 'hidden';
  }

  function closeIncomeModal() {
    const modal = document.getElementById('incomeModal');
    if (modal) {
      modal.classList.remove('show');
      document.body.style.overflow = '';
      currentIncomeId = null;
    }
  }

  // Update table row click to open edit modal
  function openIncomeDetails(id) {
    openIncomeModal('edit', id);
  }

  // Document Upload Modal Functions
  function openDocumentUploadModal() {
    const modal = document.getElementById('documentUploadModal');
    if (modal) {
      // If in edit mode and income has a document, show existing document
      if (currentIncomeId) {
        // Fetch income to check for existing document
        fetch(`/incomes/${currentIncomeId}`, {
          headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
          }
        })
        .then(res => res.json())
        .then(income => {
          const existingPreview = document.getElementById('existingDocumentPreview');
          const existingPreviewContent = document.getElementById('existingDocumentPreviewContent');
          if (income.documents && income.documents.length > 0) {
            const document = income.documents[0];
            if (document && document.file_path) {
              existingPreview.style.display = 'block';
              existingPreviewContent.innerHTML = `
                <a href="/storage/${document.file_path}" target="_blank" style="color:#007bff; text-decoration:underline; font-size:12px;">
                  View Current Document
                </a>
              `;
            } else {
              existingPreview.style.display = 'none';
            }
          } else {
            existingPreview.style.display = 'none';
          }
        })
        .catch(err => {
          console.error('Error loading income:', err);
        });
      } else {
        // Add mode - no existing document
        document.getElementById('existingDocumentPreview').style.display = 'none';
      }
      
      // Reset file input
      document.getElementById('documentFile').value = '';
      document.getElementById('documentPreview').style.display = 'none';
      
      modal.classList.add('show');
      document.body.style.overflow = 'hidden';
    }
  }

  function closeDocumentUploadModal() {
    const modal = document.getElementById('documentUploadModal');
    if (modal) {
      modal.classList.remove('show');
      document.body.style.overflow = '';
      document.getElementById('documentFile').value = '';
      document.getElementById('documentPreview').style.display = 'none';
    }
  }

  function handleDocumentFileSelect(event) {
    const file = event.target.files[0];
    if (!file) return;

    const preview = document.getElementById('documentPreview');
    const previewContent = document.getElementById('documentPreviewContent');
    
    // Show preview
    preview.style.display = 'block';
    
    // Check if it's an image
    if (file.type.startsWith('image/')) {
      const reader = new FileReader();
      reader.onload = function(e) {
        previewContent.innerHTML = `
          <img src="${e.target.result}" style="max-width:100%; max-height:300px; border:1px solid #ddd; border-radius:4px;" alt="Document preview">
          <p style="margin-top:5px; font-size:11px; color:#666;">${file.name} (${(file.size / 1024).toFixed(2)} KB)</p>
        `;
      };
      reader.readAsDataURL(file);
    } else {
      // For PDF and other files, show file info
      previewContent.innerHTML = `
        <div style="padding:20px; text-align:center; border:1px solid #ddd; border-radius:4px; background:#f9f9f9;">
          <p style="margin:0; font-size:14px; color:#666;">📄 ${file.name}</p>
          <p style="margin:5px 0 0 0; font-size:11px; color:#999;">${(file.size / 1024).toFixed(2)} KB</p>
        </div>
      `;
    }
  }

  function confirmDocumentSelection() {
    const fileInput = document.getElementById('documentFile');
    const hiddenInput = document.getElementById('documentFileInput');
    const previewDiv = document.getElementById('selectedDocumentPreview');
    const docName = document.getElementById('selectedDocumentName');
    const imagePreview = document.getElementById('selectedDocumentImagePreview');
    
    if (!fileInput.files || !fileInput.files[0]) {
      alert('Please select a file first');
      return;
    }

    const file = fileInput.files[0];
    
    // Copy file to hidden input in income form
    const dataTransfer = new DataTransfer();
    dataTransfer.items.add(file);
    hiddenInput.files = dataTransfer.files;
    
    // Show preview in income form
    docName.textContent = file.name;
    previewDiv.style.display = 'block';
    
    // Show image preview if it's an image
    if (file.type.startsWith('image/')) {
      const reader = new FileReader();
      reader.onload = function(e) {
        imagePreview.innerHTML = `<img src="${e.target.result}" style="max-width:100%; max-height:150px; border:1px solid #ddd; border-radius:4px;" alt="Document preview">`;
      };
      reader.readAsDataURL(file);
    } else {
      imagePreview.innerHTML = '';
    }
    
    // Close modal
    closeDocumentUploadModal();
  }

  function removeSelectedDocument() {
    document.getElementById('documentFileInput').value = '';
    document.getElementById('selectedDocumentPreview').style.display = 'none';
    document.getElementById('selectedDocumentName').textContent = '';
    document.getElementById('selectedDocumentImagePreview').innerHTML = '';
  }

  // Close modal when clicking outside
  document.addEventListener('DOMContentLoaded', function() {
    const incomeModal = document.getElementById('incomeModal');
    const documentModal = document.getElementById('documentUploadModal');
    
    if (incomeModal) {
      incomeModal.addEventListener('click', function(e) {
        if (e.target === this) {
          closeIncomeModal();
        }
      });
    }
    
    if (documentModal) {
      documentModal.addEventListener('click', function(e) {
        if (e.target === this) {
          closeDocumentUploadModal();
        }
      });
    }
    
    // Close modal on ESC key
    document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape') {
        const incomeModal = document.getElementById('incomeModal');
        const documentModal = document.getElementById('documentUploadModal');
        if (incomeModal && incomeModal.classList.contains('show')) {
          closeIncomeModal();
        } else if (documentModal && documentModal.classList.contains('show')) {
          closeDocumentUploadModal();
        }
      }
    });
  });

  let draggedElement = null;
  let dragOverElement = null;

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
</script>

@include('partials.table-scripts', [
  'mandatoryColumns' => $mandatoryColumns,
])

@endsection
