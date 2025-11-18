<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Expenses</title>
  <style>
    * { box-sizing: border-box; }
    body { font-family: Arial, sans-serif; color: #000; margin: 10px; background: #fff; }
    .container-table { max-width: 100%; margin: 0 auto; }
    h3 { background: #f1f1f1; padding: 8px; margin-bottom: 10px; font-weight: bold; border: 1px solid #ddd; }
    .top-bar { display:flex; align-items:center; flex-wrap:wrap; gap:10px; margin-bottom:10px; }
    .left-group { display:flex; align-items:center; gap:10px; flex:1 1 auto; min-width:220px; }
    .left-buttons { display:flex; gap:10px; align-items:center; }
    .records-found { font-size:14px; color:#555; min-width:150px; }
    .action-buttons { margin-left:auto; display:flex; gap:10px; }
    .btn { border:none; cursor:pointer; padding:6px 12px; font-size:13px; border-radius:2px; white-space:nowrap; transition:background-color .2s; text-decoration:none; color:inherit; background:#fff; border:1px solid #ccc; }
    .btn-add { background:#df7900; color:#fff; border-color:#df7900; }
    .btn-export, .btn-column { background:#fff; color:#000; border:1px solid #ccc; }
    .btn-back { background:#ccc; color:#333; border-color:#ccc; }
    .table-responsive { width: 100%; overflow-x: auto; border: 1px solid #ddd; max-height: 520px; overflow-y: auto; background: #fff; }
    .footer { display:flex; justify-content:center; align-items:center; padding:5px 0; gap:10px; border-top:1px solid #ccc; flex-wrap:wrap; margin-top:15px; }
    .paginator {
      display: flex;
      align-items: center;
      gap: 5px;
      font-size: 12px;
      color: #555;
      white-space: nowrap;
      justify-content: center;
    }
    .btn-page{
      color: #2d2d2d;
      font-size: 25px;
      width: 22px;
      height: 50px;
      padding: 5px;
      cursor: pointer;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      justify-content: center;
    }
    table { width:100%; border-collapse:collapse; font-size:13px; min-width:900px; }
    thead tr { background-color: black; color: white; height:35px; font-weight: normal; }
    thead th { padding:6px 5px; text-align:left; border-right:1px solid #444; white-space:nowrap; font-weight: normal; }
    thead th:last-child { border-right:none; }
    tbody tr { background-color:#fefefe; border-bottom:1px solid #ddd; min-height:28px; }
    tbody tr:nth-child(even) { background-color:#f8f8f8; }
    tbody tr.inactive-row { background:#fff3cd !important; }
    tbody td { padding:5px 5px; border-right:1px solid #ddd; white-space:nowrap; vertical-align:middle; font-size:12px; }
    tbody td:last-child { border-right:none; }
    .icon-expand { cursor:pointer; color:black; text-align:center; width:20px; }
    .btn-action { padding:2px 6px; font-size:11px; margin:1px; border:1px solid #ddd; background:#fff; cursor:pointer; border-radius:2px; display:inline-block; }
    .badge-status { font-size:11px; padding:4px 8px; display:inline-block; border-radius:4px; color:#fff; }
    /* Modal styles (custom, like incomes) */
    .modal { display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,.5); z-index:1000; align-items:center; justify-content:center; }
    .modal.show { display:flex; }
    .modal-content { background:#fff; border-radius:6px; width:92%; max-width:1100px; max-height:calc(100vh - 40px); overflow:auto; box-shadow:0 4px 6px rgba(0,0,0,.1); padding:0; }
    .modal-header { padding:12px 15px; border-bottom:1px solid #ddd; display:flex; justify-content:space-between; align-items:center; background:#f5f5f5; }
    .modal-body { padding:15px; }
    .modal-close { background:none; border:none; font-size:18px; cursor:pointer; color:#666; }
    .modal-footer { padding:12px 15px; border-top:1px solid #ddd; display:flex; justify-content:flex-end; gap:8px; background:#f9f9f9; }
    .form-row { display:flex; gap:10px; margin-bottom:12px; flex-wrap:wrap; align-items:flex-start; }
    .form-group { flex:0 0 calc((100% - 20px) / 3); }
    .form-group label { display:block; margin-bottom:4px; font-weight:600; font-size:13px; }
    .form-control, select, textarea { width:100%; padding:6px 8px; border:1px solid #ccc; border-radius:2px; font-size:13px; }
    .btn-save { background:#007bff; color:#fff; border:none; padding:6px 12px; border-radius:2px; cursor:pointer; }
    .btn-cancel { background:#6c757d; color:#fff; border:none; padding:6px 12px; border-radius:2px; cursor:pointer; }
    .btn-delete { background:#dc3545; color:#fff; border:none; padding:6px 12px; border-radius:2px; cursor:pointer; }
    .column-selection { display:grid; grid-template-columns:repeat(auto-fill,minmax(200px,1fr)); gap:8px; margin-bottom:15px; }
    .column-item { display:flex; align-items:center; gap:8px; padding:6px 8px; border:1px solid #ddd; border-radius:2px; cursor:pointer; }
    @media (max-width:768px) { .form-row .form-group { flex:0 0 calc((100% - 20px) / 2); } .table-responsive { max-height:500px; } }
  </style>
</head>
<body>
@extends('layouts.app')
@section('content')
@php
  $selectedColumns = session('expense_columns', [
    'expense_id','payee','date_paid','amount_paid','description','category','mode_of_payment','expense_notes'
  ]);
  $allColumns = [
    'expense_id' => 'Expense ID',
    'payee' => 'Payee',
    'date_paid' => 'Date Paid',
    'amount_paid' => 'Amount Paid',
    'description' => 'Description',
    'category' => 'Category',
    'mode_of_payment' => 'Mode Of Payment',
    'expense_notes' => 'Expense Notes'
  ];
@endphp
<div class="dashboard">
  <div class="container-table">
    <h3>Expenses</h3>
    @if(session('success'))
      <div class="alert alert-success" id="successAlert" style="padding:8px 12px; margin-bottom:12px; border:1px solid #c3e6cb; background:#d4edda; color:#155724;">
        {{ session('success') }}
        <button type="button" class="alert-close" onclick="document.getElementById('successAlert').style.display='none'" style="float:right;background:none;border:none;font-size:16px;cursor:pointer;">×</button>
      </div>
    @endif
    <div class="top-bar">
      <div class="left-group">
        <div class="records-found">Records Found - {{ $expenses->total() }}</div>
        <a class="btn btn-export" href="{{ route('expenses.export', array_merge(request()->query(), ['page' => $expenses->currentPage()])) }}">Export</a>
        <button class="btn btn-column" id="columnBtn" type="button">Column</button>
      </div>
      <div class="action-buttons">
        <button class="btn btn-add" id="addExpenseBtn">Add</button>
        <button class="btn btn-back" onclick="window.history.back()">Back</button>
      </div>
    </div>
    <div class="table-responsive">
      <table>
        <thead>
          <tr>
            <th>Action</th>
            @foreach($allColumns as $key => $label)
              @if(in_array($key, $selectedColumns))
                <th data-column="{{ $key }}">{{ $label }}</th>
              @endif
            @endforeach
          </tr>
        </thead>
        <tbody>
          @foreach($expenses as $expense)
          <tr>
            <td>
              <span class="icon-expand" style="cursor:pointer;" onclick="openEditExpense({{ $expense->id }})">⤢</span>
              <form action="{{ route('expenses.destroy', $expense->id) }}" method="POST" style="display:inline;">
                @csrf
                @method('DELETE')
                <button class="btn-action btn-delete" title="Delete" style="background:none;border:none;padding:0 6px;font-size:12px;color:#dc3545;vertical-align:middle;cursor:pointer;" onclick="return confirm('Delete this expense?')">
                  🗑️
                </button>
              </form>
            </td>
            @if(in_array('expense_id', $selectedColumns))<td>{{ $expense->expense_id }}</td>@endif
            @if(in_array('payee', $selectedColumns))<td>{{ $expense->payee }}</td>@endif
            @if(in_array('date_paid', $selectedColumns))<td>{{ $expense->date_paid ? \Carbon\Carbon::parse($expense->date_paid)->format('d-M-Y') : '' }}</td>@endif
            @if(in_array('amount_paid', $selectedColumns))<td>{{ number_format($expense->amount_paid, 2) }}</td>@endif
            @if(in_array('description', $selectedColumns))<td>{{ $expense->description ?? '-' }}</td>@endif
            @if(in_array('category', $selectedColumns))<td>{{ $expense->category }}</td>@endif
            @if(in_array('mode_of_payment', $selectedColumns))<td>{{ $expense->mode_of_payment }}</td>@endif
            @if(in_array('expense_notes', $selectedColumns))<td>{{ $expense->expense_notes ?? '-' }}</td>@endif
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    <div class="footer">
      <div class="paginator">
        @php
          $base = url()->current();
          $q = request()->query();
          $current = $expenses->currentPage();
          $last = max(1, $expenses->lastPage());
          function page_url($base, $q, $p) { $params = array_merge($q, ['page' => $p]); return $base . '?' . http_build_query($params); }
        @endphp
        <a class="btn-page" href="{{ $current > 1 ? page_url($base, $q, 1) : '#' }}" @if($current <= 1) disabled @endif>&laquo;</a>
        <a class="btn-page" href="{{ $current > 1 ? page_url($base, $q, $current-1) : '#' }}" @if($current <= 1) disabled @endif>&lsaquo;</a>
        <span class="page-info">Page {{ $current }} of {{ $last }}</span>
        <a class="btn-page" href="{{ $current < $last ? page_url($base, $q, $current+1) : '#' }}" @if($current >= $last) disabled @endif>&rsaquo;</a>
        <a class="btn-page" href="{{ $current < $last ? page_url($base, $q, $last) : '#' }}" @if($current >= $last) disabled @endif>&raquo;</a>
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
        <div style="display:flex;gap:8px;margin-bottom:12px;">
          <button class="btn" onclick="selectAllColumns()">Select All</button>
          <button class="btn" onclick="deselectAllColumns()">Deselect All</button>
        </div>
        <form id="columnForm" action="{{ route('expenses.save-column-settings') }}" method="POST">
          @csrf
          <div class="column-selection">
            @foreach($allColumns as $key => $label)
              <div class="column-item">
                <input type="checkbox" class="column-checkbox" id="col_{{ $key }}" value="{{ $key }}" name="columns[]" @if(in_array($key, $selectedColumns)) checked @endif>
                <label for="col_{{ $key }}">{{ $label }}</label>
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

  <!-- Add/Edit Expense Modal -->
  <div class="modal" id="expenseModal">
    <div class="modal-content">
      <div class="modal-header">
        <h4 id="expenseModalTitle">Add Expense</h4>
        <button type="button" class="modal-close" onclick="closeExpenseModal()">×</button>
      </div>
      <form id="expenseForm" method="POST">
        @csrf
        <input type="hidden" name="_method" id="expenseFormMethod" value="POST">
        <div class="modal-body">
          <div class="form-row">
            <div class="form-group">
              <label for="payee">Payee</label>
              <input type="text" class="form-control" name="payee" id="payee" required>
            </div>
            <div class="form-group">
              <label for="date_paid">Date Paid</label>
              <input type="date" class="form-control" name="date_paid" id="date_paid" required>
            </div>
            <div class="form-group">
              <label for="amount_paid">Amount Paid</label>
              <input type="number" step="0.01" class="form-control" name="amount_paid" id="amount_paid" required>
            </div>
          </div>
          <div class="form-row">
            <div class="form-group">
              <label for="description">Description</label>
              <input type="text" class="form-control" name="description" id="description">
            </div>
            <div class="form-group">
              <label for="category">Category</label>
              <input type="text" class="form-control" name="category" id="category" required>
            </div>
            <div class="form-group">
              <label for="mode_of_payment">Mode Of Payment</label>
              <input type="text" class="form-control" name="mode_of_payment" id="mode_of_payment" required>
            </div>
          </div>
          <div class="form-row">
            <div class="form-group" style="flex:1 1 100%;">
              <label for="expense_notes">Expense Notes</label>
              <textarea class="form-control" name="expense_notes" id="expense_notes"></textarea>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn-cancel" onclick="closeExpenseModal()">Cancel</button>
          <button type="submit" class="btn-save" id="expenseSaveBtn">Save</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
let currentExpenseId = null;

document.getElementById('addExpenseBtn').addEventListener('click', () => openExpenseModal('add'));
document.getElementById('columnBtn').addEventListener('click', () => openColumnModal());

function openColumnModal(){
  document.getElementById('columnModal').classList.add('show');
  document.body.style.overflow = 'hidden';
}
function closeColumnModal(){
  document.getElementById('columnModal').classList.remove('show');
  document.body.style.overflow = '';
}
function selectAllColumns(){ document.querySelectorAll('.column-checkbox').forEach(cb=>cb.checked=true); }
function deselectAllColumns(){ document.querySelectorAll('.column-checkbox').forEach(cb=>cb.checked=false); }
function saveColumnSettings(){
  const checked = Array.from(document.querySelectorAll('.column-checkbox:checked')).map(n=>n.value);
  const form = document.getElementById('columnForm');
  const existing = form.querySelectorAll('input[name="columns[]"]'); existing.forEach(e=>e.remove());
  checked.forEach(c => {
    const i = document.createElement('input'); i.type='hidden'; i.name='columns[]'; i.value=c; form.appendChild(i);
  });
  form.submit();
}

function openExpenseModal(mode, exp = null) {
  const modal = document.getElementById('expenseModal');
  const title = document.getElementById('expenseModalTitle');
  const form = document.getElementById('expenseForm');
  const formMethod = document.getElementById('expenseFormMethod');
  form.reset();

  if (mode === 'add') {
    title.textContent = 'Add Expense';
    form.action = "{{ route('expenses.store') }}";
    formMethod.value = 'POST';
    currentExpenseId = null;
    document.getElementById('payee').value = '';
    document.getElementById('date_paid').value = '';
    document.getElementById('amount_paid').value = '';
    document.getElementById('description').value = '';
    document.getElementById('category').value = '';
    document.getElementById('mode_of_payment').value = '';
    document.getElementById('expense_notes').value = '';
  } else if (mode === 'edit' && exp) {
    title.textContent = 'Edit Expense';
    form.action = `/expenses/${exp.id}`;
    formMethod.value = 'PUT';
    currentExpenseId = exp.id;
    document.getElementById('payee').value = exp.payee ?? '';
    document.getElementById('date_paid').value = exp.date_paid ?? '';
    document.getElementById('amount_paid').value = exp.amount_paid ?? '';
    document.getElementById('description').value = exp.description ?? '';
    document.getElementById('category').value = exp.category ?? '';
    document.getElementById('mode_of_payment').value = exp.mode_of_payment ?? '';
    document.getElementById('expense_notes').value = exp.expense_notes ?? '';
  }

  document.body.style.overflow = 'hidden';
  modal.classList.add('show');
}

function closeExpenseModal() {
  document.getElementById('expenseModal').classList.remove('show');
  document.body.style.overflow = '';
}

function openEditExpense(id) {
  fetch(`/expenses/${id}/edit`)
    .then(res => res.json())
    .then(exp => openExpenseModal('edit', exp));
}

// Close modal on backdrop click or ESC
document.getElementById('expenseModal').addEventListener('click', e => {
  if (e.target === document.getElementById('expenseModal')) closeExpenseModal();
});
document.addEventListener('keydown', e => {
  if (e.key === 'Escape') closeExpenseModal();
});
</script>
@endsection
</body>
</html>