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
  .footer { background-color:#fff !important; }
</style>

@php
  $config = \App\Helpers\TableConfigHelper::getConfig('expenses');
  $selectedColumns = \App\Helpers\TableConfigHelper::getSelectedColumns('expenses');
  $columnDefinitions = $config['column_definitions'] ?? [];
  $mandatoryColumns = $config['mandatory_columns'] ?? [];
@endphp

<div class="dashboard">
  <!-- Main Expenses Table View -->
  <div class="clients-table-view" id="clientsTableView">
  <div class="container-table">
    <!-- Expenses Card -->
    <div style="background:#fff; border:1px solid #ddd; border-radius:4px; overflow:hidden;">
      <div class="page-header" style="background:#fff; border-bottom:1px solid #ddd; margin-bottom:0;">
      <div class="page-title-section">
        <h3>Expenses</h3>
        <div class="records-found">Records Found - {{ $expenses->total() }}</div>
      </div>
      <div class="action-buttons">
        @if(auth()->check() && (auth()->user()->hasPermission('expenses.create') || auth()->user()->isAdmin()))
        <button class="btn btn-add" id="addExpenseBtn">Add</button>
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
      <table id="expensesTable">
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
          @foreach($expenses as $expense)
            <tr class="{{ $expense->hasExpired ?? false ? 'has-expired' : ($expense->hasExpiring ?? false ? 'has-expiring' : '') }}">
              <td class="bell-cell {{ $expense->hasExpired ?? false ? 'expired' : ($expense->hasExpiring ?? false ? 'expiring' : '') }}">
                <div style="display:flex; align-items:center; justify-content:center;">
                  @php
                    $isExpired = $expense->hasExpired ?? false;
                    $isExpiring = $expense->hasExpiring ?? false;
                  @endphp
                  <div class="status-indicator {{ $isExpired ? 'expired' : ($isExpiring ? 'expiring' : 'normal') }}" style="width:18px; height:18px; border-radius:50%; border:2px solid #000; background-color:{{ $isExpired ? '#dc3545' : ($isExpiring ? '#ffc107' : 'transparent') }};"></div>
                </div>
              </td>
              <td class="action-cell">
                @if(auth()->check() && (auth()->user()->hasPermission('expenses.view') || auth()->user()->hasPermission('expenses.edit') || auth()->user()->isAdmin()))
                <svg class="action-expand" onclick="openExpenseDetails({{ $expense->id }})" width="22" height="22" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="cursor:pointer; vertical-align:middle;">
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
                @endif
              </td>
              @foreach($selectedColumns as $col)
                @if($col == 'expense_id')
                  <td data-column="expense_id">
                    {{ $expense->expense_id }}
                  </td>
                @elseif($col == 'payee')
                  <td data-column="payee">{{ $expense->payee ?? '-' }}</td>
                @elseif($col == 'date_paid')
                  <td data-column="date_paid">{{ $expense->date_paid ? $expense->date_paid->format('d-M-y') : '-' }}</td>
                @elseif($col == 'amount_paid')
                  <td data-column="amount_paid">{{ $expense->amount_paid ? number_format($expense->amount_paid, 2) : '-' }}</td>
                @elseif($col == 'description')
                  <td data-column="description">{{ $expense->description ?? '-' }}</td>
                @elseif($col == 'category_id')
                  <td data-column="category_id">{{ $expense->expenseCategory ? $expense->expenseCategory->name : '-' }}</td>
                @elseif($col == 'mode_of_payment')
                  <td data-column="mode_of_payment">{{ $expense->modeOfPayment ? $expense->modeOfPayment->name : '-' }}</td>
                @elseif($col == 'expense_notes')
                  <td data-column="expense_notes">{{ $expense->expense_notes ?? '-' }}</td>
                @endif
              @endforeach
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    </div>

 
    </div>
    <div class="footer">
      <div class="footer-left">
        <a class="btn btn-export" href="{{ route('expenses.export', array_merge(request()->query(), ['page' => $expenses->currentPage()])) }}">Export</a>
        <button class="btn btn-column" id="columnBtn2" type="button">Column</button>
      </div>
      <div class="paginator">
        @php
          $base = url()->current();
          $q = request()->query();
          $current = $expenses->currentPage();
          $last = max(1, $expenses->lastPage());
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


  <!-- Expense Page View (Full Page) -->
  <div class="client-page-view" id="expensePageView" style="display:none;">
    <div class="client-page-header">
      <div class="client-page-title">
        <span id="expensePageTitle">Expense</span> - <span class="client-name" id="expensePageName"></span>
      </div>
      <div class="client-page-actions">
        <button class="btn btn-edit" id="editExpenseFromPageBtn" style="background:#f3742a; color:#fff; border:none; padding:6px 16px; border-radius:2px; cursor:pointer; display:none;">Edit</button>
        <button class="btn" id="closeExpensePageBtn" onclick="closeExpensePageView()" style="background:#e0e0e0; color:#000; border:none; padding:6px 16px; border-radius:2px; cursor:pointer;">Close</button>
      </div>
    </div>
    <div class="client-page-body">
      <div class="client-page-content">
        <!-- Expense Details View -->
        <div id="expenseDetailsPageContent" style="display:none;">
          <div style="background:#fff; border:1px solid #ddd; border-radius:4px; margin-bottom:15px; overflow:hidden;">
            <div id="expenseDetailsContent" style="display:grid; grid-template-columns:repeat(4, 1fr); gap:0; align-items:start; padding:12px;">
              <!-- Content will be loaded via JavaScript -->
            </div>
          </div>
        </div>

        <!-- Expense Edit/Add Form -->
        <div id="expenseFormPageContent" style="display:none;">
          <div style="background:#fff; border:1px solid #ddd; border-radius:4px; margin-bottom:15px; overflow:hidden;">
            <div style="display:flex; justify-content:flex-end; align-items:center; padding:12px 15px; border-bottom:1px solid #ddd; background:#fff;">
              <div class="client-page-actions">
                <button type="button" class="btn-delete" id="expenseDeleteBtn" style="display:none; background:#dc3545; color:#fff; border:none; padding:6px 16px; border-radius:2px; cursor:pointer;" onclick="deleteExpense()">Delete</button>
                <button type="submit" form="expensePageForm" class="btn-save" style="background:#f3742a; color:#fff; border:none; padding:6px 16px; border-radius:2px; cursor:pointer;">Save</button>
                <button type="button" class="btn" id="closeExpenseFormBtn" onclick="closeExpensePageView()" style="background:#e0e0e0; color:#000; border:none; padding:6px 16px; border-radius:2px; cursor:pointer; display:none;">Close</button>
              </div>
            </div>
            <form id="expensePageForm" method="POST" action="{{ route('expenses.store') }}">
              @csrf
              <div id="expensePageFormMethod" style="display:none;"></div>
              <div style="padding:12px;">
                <!-- Form content will be cloned from modal -->
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Add/Edit Expense Modal -->
  <div class="modal" id="expenseModal">
    <div class="modal-content" style="max-width:800px; max-height:90vh; overflow-y:auto;">
      <div class="modal-header" style="display:flex; justify-content:space-between; align-items:center; padding:15px 20px; border-bottom:1px solid #ddd; background:#fff;">
        <h4 id="expenseModalTitle" style="margin:0; font-size:18px; font-weight:bold;">Add Expense</h4>
        <div style="display:flex; gap:10px;">
          <button type="submit" form="expenseForm" class="btn-save" style="background:#f3742a; color:#fff; border:none; padding:8px 20px; border-radius:2px; cursor:pointer; font-size:13px;">Save</button>
          <button type="button" class="btn-cancel" onclick="closeExpenseModal()" style="background:#000; color:#fff; border:none; padding:8px 20px; border-radius:2px; cursor:pointer; font-size:13px;">Cancel</button>
      </div>
      </div>
      <form id="expenseForm" method="POST" action="{{ route('expenses.store') }}" enctype="multipart/form-data">
        @csrf
        <div id="expenseFormMethod" style="display:none;"></div>
        <input type="file" name="receipt" id="receiptFileInput" style="display:none;" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
        <div class="modal-body" style="padding:20px;">
          <div class="form-row" style="display:flex; gap:15px; margin-bottom:15px;">
            <div class="form-group" style="flex:1;">
              <label for="payee" style="display:block; margin-bottom:5px; font-size:13px; font-weight:500;">Payee</label>
              <input type="text" class="form-control" name="payee" id="payee" required style="width:100%; padding:8px; border:1px solid #ddd; border-radius:2px; font-size:13px;">
            </div>
            <div class="form-group" style="flex:1;">
              <label for="date_paid" style="display:block; margin-bottom:5px; font-size:13px; font-weight:500;">Date Paid</label>
              <input type="date" class="form-control" name="date_paid" id="date_paid" required style="width:100%; padding:8px; border:1px solid #ddd; border-radius:2px; font-size:13px;">
            </div>
            <div class="form-group" style="flex:1;">
              <label for="amount_paid" style="display:block; margin-bottom:5px; font-size:13px; font-weight:500;">Amount Paid</label>
              <input type="number" step="0.01" class="form-control" name="amount_paid" id="amount_paid" required style="width:100%; padding:8px; border:1px solid #ddd; border-radius:2px; font-size:13px;">
            </div>
          </div>
          <div class="form-row" style="display:flex; gap:15px; margin-bottom:15px;">
            <div class="form-group" style="flex:1;">
              <label for="category_id" style="display:block; margin-bottom:5px; font-size:13px; font-weight:500;">Category</label>
              <select class="form-control" name="category_id" id="category_id" required style="width:100%; padding:8px; border:1px solid #ddd; border-radius:2px; font-size:13px;">
                <option value="">Select Category</option>
                @if(isset($expenseCategories))
                  @foreach($expenseCategories as $cat)
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
              <select class="form-control" name="mode_of_payment_id" id="mode_of_payment_id" required style="width:100%; padding:8px; border:1px solid #ddd; border-radius:2px; font-size:13px;">
                <option value="">Select Mode Of Payment</option>
                @if(isset($modesOfPayment))
                  @foreach($modesOfPayment as $mode)
                    <option value="{{ $mode->id }}">{{ $mode->name }}</option>
                  @endforeach
                @endif
              </select>
            </div>
          </div>
          <div class="form-row" style="display:flex; gap:15px; margin-bottom:15px;">
            <div class="form-group" style="flex:1;">
              <label for="receipt_no" style="display:block; margin-bottom:5px; font-size:13px; font-weight:500;">Receipt No.</label>
              <input type="text" class="form-control" name="receipt_no" id="receipt_no" style="width:100%; padding:8px; border:1px solid #ddd; border-radius:2px; font-size:13px;">
            </div>
          </div>
          <div class="form-row" style="display:flex; gap:15px; margin-bottom:15px;">
            <div class="form-group" style="flex:1 1 100%;">
              <label for="expense_notes" style="display:block; margin-bottom:5px; font-size:13px; font-weight:500;">Expense Notes</label>
              <textarea class="form-control" name="expense_notes" id="expense_notes" rows="4" style="width:100%; padding:8px; border:1px solid #ddd; border-radius:2px; font-size:13px; resize:vertical;"></textarea>
            </div>
          </div>
          <div id="selectedReceiptPreview" style="margin-top:15px; padding:10px; background:#f5f5f5; border-radius:4px; display:none;">
            <div style="display:flex; justify-content:space-between; align-items:center;">
              <div>
                <p style="margin:0; font-size:12px; color:#666; font-weight:500;">Selected Receipt:</p>
                <p id="selectedReceiptName" style="margin:5px 0 0 0; font-size:13px; color:#000;"></p>
        </div>
              <button type="button" onclick="removeSelectedReceipt()" style="background:#dc3545; color:#fff; border:none; padding:4px 10px; border-radius:2px; cursor:pointer; font-size:11px;">Remove</button>
            </div>
            <div id="selectedReceiptImagePreview" style="margin-top:10px; max-width:200px; max-height:200px;"></div>
          </div>
        </div>
        <div class="modal-footer" style="padding:15px 20px; border-top:1px solid #ddd; background:#fff; display:flex; justify-content:center;">
          <button type="button" class="btn-upload" onclick="openReceiptUploadModal()" style="background:#f3742a; color:#fff; border:none; padding:8px 20px; border-radius:2px; cursor:pointer; font-size:13px;">Upload Receipt</button>
          <button type="button" class="btn-delete" id="expenseDeleteBtnModal" style="display: none; background:#dc3545; color:#fff; border:none; padding:8px 20px; border-radius:2px; cursor:pointer; font-size:13px; margin-left:10px;" onclick="deleteExpense()">Delete</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Receipt Upload Modal -->
  <div class="modal" id="receiptUploadModal">
    <div class="modal-content" style="max-width:500px;">
      <div class="modal-header">
        <h4>Select Receipt</h4>
        <button type="button" class="modal-close" onclick="closeReceiptUploadModal()">×</button>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label for="receiptFile">Select Receipt File</label>
          <input type="file" class="form-control" name="receipt" id="receiptFile" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx" onchange="handleReceiptFileSelect(event)">
          <small style="color:#666; font-size:11px;">Accepted formats: PDF, JPG, JPEG, PNG, DOC, DOCX (Max 5MB)</small>
        </div>
        <div id="receiptPreview" style="margin-top:15px; display:none;">
          <p style="font-size:12px; color:#666; font-weight:500;">Preview:</p>
          <div id="receiptPreviewContent" style="margin-top:10px;"></div>
        </div>
        <div id="existingReceiptPreview" style="margin-top:15px; display:none;">
          <p style="font-size:12px; color:#666;">Current receipt:</p>
          <div id="existingReceiptPreviewContent"></div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn-cancel" onclick="closeReceiptUploadModal()">Cancel</button>
        <button type="button" class="btn-save" onclick="confirmReceiptSelection()">Select</button>
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

        <form id="columnForm" action="{{ route('expenses.save-column-settings') }}" method="POST">
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
  let currentExpenseId = null;
  const lookupData = @json($lookupData);
  const selectedColumns = @json($selectedColumns);
  const mandatoryColumns = @json($mandatoryColumns);
  const canDeleteExpense = @json(auth()->check() && (auth()->user()->hasPermission('expenses.delete') || auth()->user()->isAdmin()));
  const canEditExpense = @json(auth()->check() && (auth()->user()->hasPermission('expenses.edit') || auth()->user()->isAdmin()));

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

  // Open expense details - opens edit modal
  function openExpenseDetails(id) {
    openExpenseModal('edit', id);
  }

  // Populate expense details view
  function populateExpenseDetails(expense) {
    const content = document.getElementById('expenseDetailsContent');
    if (!content) return;

    const col1 = `
      <div class="detail-section">
        <div class="detail-section-header">EXPENSE DETAILS</div>
        <div class="detail-section-body">
          <div class="detail-row">
            <span class="detail-label">Expense ID</span>
            <div class="detail-value">${expense.expense_id || '-'}</div>
          </div>
          <div class="detail-row">
            <span class="detail-label">Payee</span>
            <div class="detail-value">${expense.payee || '-'}</div>
          </div>
          <div class="detail-row">
            <span class="detail-label">Date Paid</span>
            <div class="detail-value">${formatDate(expense.date_paid)}</div>
          </div>
          <div class="detail-row">
            <span class="detail-label">Amount Paid</span>
            <div class="detail-value">${formatNumber(expense.amount_paid)}</div>
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
            <div class="detail-value">${expense.description || '-'}</div>
          </div>
          <div class="detail-row">
            <span class="detail-label">Category</span>
            <div class="detail-value">${expense.expense_category ? expense.expense_category.name : (expense.category || '-')}</div>
          </div>
          <div class="detail-row">
            <span class="detail-label">Mode Of Payment</span>
            <div class="detail-value">${expense.mode_of_payment ? expense.mode_of_payment.name : (expense.modeOfPayment ? expense.modeOfPayment.name : '-')}</div>
          </div>
        </div>
      </div>
    `;

    const col3 = `
      <div class="detail-section">
        <div class="detail-section-header">NOTES</div>
        <div class="detail-section-body">
          <div class="detail-row" style="align-items:flex-start;">
            <span class="detail-label">Expense Notes</span>
            <textarea class="detail-value" style="min-height:120px; resize:vertical; flex:1; font-size:11px; padding:4px 6px;" readonly>${expense.expense_notes || ''}</textarea>
          </div>
        </div>
      </div>
    `;

    const col4 = `
    `;

    content.innerHTML = col1 + col2 + col3 + col4;
  }

  // Open expense page (Add or Edit)
  async function openExpensePage(mode) {
    if (mode === 'add') {
      openExpenseForm('add');
    } else {
      if (currentExpenseId) {
        openEditExpense(currentExpenseId);
      }
    }
  }

  // Add Expense Button - Open Modal
  document.getElementById('addExpenseBtn').addEventListener('click', () => openExpenseModal('add'));
  document.getElementById('columnBtn2').addEventListener('click', () => openColumnModal());

  async function openEditExpense(id) {
    try {
      const res = await fetch(`/expenses/${id}/edit`, {
        headers: {
          'Accept': 'application/json',
          'X-Requested-With': 'XMLHttpRequest'
        }
      });
      if (!res.ok) throw new Error('Network error');
      const expense = await res.json();
      currentExpenseId = id;
      openExpenseForm('edit', expense);
    } catch (e) {
      console.error(e);
      alert('Error loading expense data');
    }
  }

  function openExpenseForm(mode, expense = null) {
    // Clone form from modal
    const modalForm = document.getElementById('expenseModal').querySelector('form');
    const pageForm = document.getElementById('expensePageForm');
    const formContentDiv = pageForm.querySelector('div[style*="padding:12px"]');
    
    // Clone the modal form body
    const modalBody = modalForm.querySelector('.modal-body');
    if (modalBody && formContentDiv) {
      formContentDiv.innerHTML = modalBody.innerHTML;
    }

    const formMethod = document.getElementById('expensePageFormMethod');
    const deleteBtn = document.getElementById('expenseDeleteBtn');
    const editBtn = document.getElementById('editExpenseFromPageBtn');
    const closeBtn = document.getElementById('closeExpensePageBtn');
    const closeFormBtn = document.getElementById('closeExpenseFormBtn');

    if (mode === 'add') {
      document.getElementById('expensePageTitle').textContent = 'Add Expense';
      document.getElementById('expensePageName').textContent = '';
      pageForm.action = '{{ route("expenses.store") }}';
      formMethod.innerHTML = '';
      deleteBtn.style.display = 'none';
      if (editBtn) editBtn.style.display = 'none';
      if (closeBtn) closeBtn.style.display = 'inline-block';
      if (closeFormBtn) closeFormBtn.style.display = 'none';
      pageForm.reset();
    } else {
      const expenseName = expense.expense_id || 'Unknown';
      document.getElementById('expensePageTitle').textContent = 'Edit Expense';
      document.getElementById('expensePageName').textContent = expenseName;
      pageForm.action = `/expenses/${currentExpenseId}`;
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

      // Populate form fields
      if (expense.payee) document.getElementById('payee').value = expense.payee;
      if (expense.date_paid) document.getElementById('date_paid').value = expense.date_paid ? (typeof expense.date_paid === 'string' ? expense.date_paid.substring(0,10) : expense.date_paid) : '';
      if (expense.amount_paid) document.getElementById('amount_paid').value = expense.amount_paid;
      if (expense.description) document.getElementById('description').value = expense.description;
      if (expense.category_id) document.getElementById('category_id').value = expense.category_id;
      if (expense.mode_of_payment_id) document.getElementById('mode_of_payment_id').value = expense.mode_of_payment_id;
      if (expense.receipt_no) document.getElementById('receipt_no').value = expense.receipt_no;
      if (expense.expense_notes) document.getElementById('expense_notes').value = expense.expense_notes;
    }

    // Hide table view, show page view
    document.getElementById('clientsTableView').classList.add('hidden');
    const expensePageView = document.getElementById('expensePageView');
    expensePageView.style.display = 'block';
    expensePageView.classList.add('show');
    document.getElementById('expenseDetailsPageContent').style.display = 'none';
    document.getElementById('expenseFormPageContent').style.display = 'block';
  }

  function closeExpensePageView() {
    const expensePageView = document.getElementById('expensePageView');
    expensePageView.classList.remove('show');
    expensePageView.style.display = 'none';
    document.getElementById('clientsTableView').classList.remove('hidden');
    document.getElementById('expenseDetailsPageContent').style.display = 'none';
    document.getElementById('expenseFormPageContent').style.display = 'none';
    currentExpenseId = null;
  }

  // Edit button from details page
  const editBtn = document.getElementById('editExpenseFromPageBtn');
  if (editBtn) {
    editBtn.addEventListener('click', function() {
      if (currentExpenseId) {
        openEditExpense(currentExpenseId);
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

  function deleteExpense() {
    if (!currentExpenseId) return;
    if (!confirm('Delete this expense?')) return;
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = `/expenses/${currentExpenseId}`;
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

  // Open Expense Modal
  async function openExpenseModal(mode, expenseId = null) {
    const modal = document.getElementById('expenseModal');
    const form = document.getElementById('expenseForm');
    const formMethod = document.getElementById('expenseFormMethod');
    const title = document.getElementById('expenseModalTitle');
    const deleteBtn = document.getElementById('expenseDeleteBtnModal');
    
    if (!modal || !form || !formMethod || !title) {
      console.error('Required modal elements not found');
      alert('Error: Modal elements not found');
      return;
    }
    
    if (mode === 'add') {
      title.textContent = 'Add Expense';
      form.action = '{{ route("expenses.store") }}';
      formMethod.innerHTML = '';
      if (deleteBtn) deleteBtn.style.display = 'none';
      form.reset();
      currentExpenseId = null;
      // Reset receipt preview
      document.getElementById('selectedReceiptPreview').style.display = 'none';
      document.getElementById('receiptFileInput').value = '';
    } else if (mode === 'edit' && expenseId) {
      try {
        const res = await fetch(`/expenses/${expenseId}/edit`, {
          headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
          }
        });
        if (!res.ok) throw new Error('Network error');
        const expense = await res.json();
        currentExpenseId = expenseId;
        
        title.textContent = 'Edit Expense';
        form.action = `/expenses/${expenseId}`;
        formMethod.innerHTML = '<input type="hidden" name="_method" value="PUT">';
        if (deleteBtn) deleteBtn.style.display = canDeleteExpense ? 'inline-block' : 'none';
        
        // Populate form fields
        document.getElementById('payee').value = expense.payee || '';
        document.getElementById('date_paid').value = expense.date_paid ? (typeof expense.date_paid === 'string' ? expense.date_paid.substring(0,10) : expense.date_paid) : '';
        document.getElementById('amount_paid').value = expense.amount_paid || '';
        document.getElementById('category_id').value = expense.category_id || (expense.expense_category ? expense.expense_category.id : '');
        document.getElementById('description').value = expense.description || '';
        document.getElementById('mode_of_payment_id').value = expense.mode_of_payment_id || (expense.mode_of_payment ? expense.mode_of_payment.id : (expense.modeOfPayment ? expense.modeOfPayment.id : ''));
        document.getElementById('receipt_no').value = expense.receipt_no || '';
        document.getElementById('expense_notes').value = expense.expense_notes || '';
        
        // Show existing receipt preview if available (from documents table)
        const previewDiv = document.getElementById('selectedReceiptPreview');
        const receiptName = document.getElementById('selectedReceiptName');
        const imagePreview = document.getElementById('selectedReceiptImagePreview');
        
        if (expense.documents && expense.documents.length > 0) {
          const receipt = expense.documents.find(doc => doc.type === 'receipt') || expense.documents[0];
          if (receipt && receipt.file_path) {
            previewDiv.style.display = 'block';
            const fileName = receipt.file_path.split('/').pop();
            receiptName.textContent = receipt.name || fileName;
            // Show link to view receipt
            imagePreview.innerHTML = `
              <a href="/storage/${receipt.file_path}" target="_blank" style="color:#007bff; text-decoration:underline; font-size:12px;">
                View Current Receipt
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
        alert('Error loading expense data');
        return;
      }
    }
    
    // Show the modal
    modal.classList.add('show');
    document.body.style.overflow = 'hidden';
  }

  function closeExpenseModal() {
    const modal = document.getElementById('expenseModal');
    if (modal) {
      modal.classList.remove('show');
      document.body.style.overflow = '';
      currentExpenseId = null;
    }
  }

  // Close modal when clicking outside
  document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('expenseModal');
    if (modal) {
      modal.addEventListener('click', function(e) {
        if (e.target === this) {
          closeExpenseModal();
        }
      });
    }
    
    // Close modal on ESC key
    document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape') {
        const expenseModal = document.getElementById('expenseModal');
        const receiptModal = document.getElementById('receiptUploadModal');
        if (expenseModal && expenseModal.classList.contains('show')) {
          closeExpenseModal();
        } else if (receiptModal && receiptModal.classList.contains('show')) {
          closeReceiptUploadModal();
        }
      }
    });

    // Close receipt upload modal when clicking outside
    const receiptModal = document.getElementById('receiptUploadModal');
    if (receiptModal) {
      receiptModal.addEventListener('click', function(e) {
        if (e.target === this) {
          closeReceiptUploadModal();
        }
      });
    }
  });

  // Receipt Upload Modal
  function openReceiptUploadModal() {
    const modal = document.getElementById('receiptUploadModal');
    if (modal) {
      // If in edit mode and expense has a receipt, show existing receipt
      if (currentExpenseId) {
        // Fetch expense to check for existing receipt
        fetch(`/expenses/${currentExpenseId}`, {
          headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
          }
        })
        .then(res => res.json())
        .then(expense => {
          const existingPreview = document.getElementById('existingReceiptPreview');
          const existingPreviewContent = document.getElementById('existingReceiptPreviewContent');
          if (expense.documents && expense.documents.length > 0) {
            const receipt = expense.documents.find(doc => doc.type === 'receipt') || expense.documents[0];
            if (receipt && receipt.file_path) {
              existingPreview.style.display = 'block';
              existingPreviewContent.innerHTML = `
                <a href="/storage/${receipt.file_path}" target="_blank" style="color:#007bff; text-decoration:underline; font-size:12px;">
                  View Current Receipt
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
          console.error('Error loading expense:', err);
        });
      } else {
        // Add mode - no existing receipt
        document.getElementById('existingReceiptPreview').style.display = 'none';
      }
      
      // Reset file input
      document.getElementById('receiptFile').value = '';
      document.getElementById('receiptPreview').style.display = 'none';
      
      modal.classList.add('show');
      document.body.style.overflow = 'hidden';
    }
  }

  function closeReceiptUploadModal() {
    const modal = document.getElementById('receiptUploadModal');
    if (modal) {
      modal.classList.remove('show');
      document.body.style.overflow = '';
      document.getElementById('receiptFile').value = '';
      document.getElementById('receiptPreview').style.display = 'none';
    }
  }

  function handleReceiptFileSelect(event) {
    const file = event.target.files[0];
    if (!file) return;

    const preview = document.getElementById('receiptPreview');
    const previewContent = document.getElementById('receiptPreviewContent');
    
    // Show preview
    preview.style.display = 'block';
    
    // Check if it's an image
    if (file.type.startsWith('image/')) {
      const reader = new FileReader();
      reader.onload = function(e) {
        previewContent.innerHTML = `
          <img src="${e.target.result}" style="max-width:100%; max-height:300px; border:1px solid #ddd; border-radius:4px;" alt="Receipt preview">
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

  function confirmReceiptSelection() {
    const fileInput = document.getElementById('receiptFile');
    const hiddenInput = document.getElementById('receiptFileInput');
    const previewDiv = document.getElementById('selectedReceiptPreview');
    const receiptName = document.getElementById('selectedReceiptName');
    const imagePreview = document.getElementById('selectedReceiptImagePreview');
    
    if (!fileInput.files || !fileInput.files[0]) {
      alert('Please select a file first');
      return;
    }

    const file = fileInput.files[0];
    
    // Copy file to hidden input in expense form
    const dataTransfer = new DataTransfer();
    dataTransfer.items.add(file);
    hiddenInput.files = dataTransfer.files;
    
    // Show preview in expense form
    receiptName.textContent = file.name;
    previewDiv.style.display = 'block';
    
    // Show image preview if it's an image
    if (file.type.startsWith('image/')) {
      const reader = new FileReader();
      reader.onload = function(e) {
        imagePreview.innerHTML = `<img src="${e.target.result}" style="max-width:100%; max-height:150px; border:1px solid #ddd; border-radius:4px;" alt="Receipt preview">`;
      };
      reader.readAsDataURL(file);
    } else {
      imagePreview.innerHTML = '';
    }
    
    // Close modal
    closeReceiptUploadModal();
  }

  function removeSelectedReceipt() {
    document.getElementById('receiptFileInput').value = '';
    document.getElementById('selectedReceiptPreview').style.display = 'none';
    document.getElementById('selectedReceiptName').textContent = '';
    document.getElementById('selectedReceiptImagePreview').innerHTML = '';
  }

  // Handle expense form submission to include receipt
  document.addEventListener('DOMContentLoaded', function() {
    const expenseForm = document.getElementById('expenseForm');
    if (expenseForm) {
      expenseForm.addEventListener('submit', function(e) {
        // Form will submit normally with receipt file included if selected
        // The receipt will be saved along with the expense in the store/update method
      });
    }
  });

  // Update table row click to open edit modal
  function openExpenseDetails(id) {
    openExpenseModal('edit', id);
  }

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

</html>
</html>