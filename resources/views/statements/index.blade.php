<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Statement</title>
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
      /* Remove margin-left:auto to center */
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
    display: inline-flex
;
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
    /* Modal styles (simple, like contacts) */
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
  $selectedColumns = session('statement_columns', [
    'statement_no','year','insurer','business_category','date_received','amount_received','mode_of_payment','remarks'
  ]);
  $allColumns = [
    'statement_no' => 'Statement No',
    'year' => 'Year',
    'insurer' => 'Insurer',
    'business_category' => 'Business Category',
    'date_received' => 'Date Received',
    'amount_received' => 'Amount Received',
    'mode_of_payment' => 'Mode Of Payment (Life)',
    'remarks' => 'Remarks'
  ];
@endphp
<div class="dashboard">
  <div class="container-table">
    <h3>Statements</h3>
    @if(session('success'))
      <div class="alert alert-success" id="successAlert" style="padding:8px 12px; margin-bottom:12px; border:1px solid #c3e6cb; background:#d4edda; color:#155724;">
        {{ session('success') }}
        <button type="button" class="alert-close" onclick="document.getElementById('successAlert').style.display='none'" style="float:right;background:none;border:none;font-size:16px;cursor:pointer;">×</button>
      </div>
    @endif
    <div class="top-bar" style="justify-content:center;">
      <div class="records-found">Records Found - {{ $statements->total() }}</div>
      <a class="btn btn-export" href="{{ route('statements.export', array_merge(request()->query(), ['page' => $statements->currentPage()])) }}">Export</a>
      <button class="btn btn-column" id="columnBtn" type="button">Column</button>
      <div class="action-buttons" style="margin:auto;">
        @foreach(['SACOS','Alliance','Hsavy','MUA'] as $insurerBtn)
          <button class="btn btn-column" onclick="filterByInsurer('{{ $insurerBtn }}')" style="margin-left:5px;{{ isset($insurerFilter) && $insurerFilter==$insurerBtn ? 'background:#007bff;color:#fff;' : '' }}">{{ $insurerBtn }}</button>
        @endforeach
        <button class="btn btn-back" onclick="window.location.href='{{ route('statements.index') }}'">All</button>
      </div>
      <div class="action-buttons">
        <button class="btn btn-add" id="addStatementBtn">Add</button>
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
          @foreach($statements as $st)
          <tr>
            <td>
              <span class="icon-expand" style="cursor:pointer;" onclick="openEditStatement({{ $st->id }})">⤢</span>
              <form action="{{ route('statements.destroy', $st->id) }}" method="POST" style="display:inline;">
                @csrf
                @method('DELETE')
                <button class="btn-action btn-delete" title="Delete" style="background:none;border:none;padding:0 6px;font-size:12px;color:#dc3545;vertical-align:middle;cursor:pointer;" onclick="return confirm('Delete this statement?')">
                  🗑️
                </button>
              </form>
            </td>
            @if(in_array('statement_no', $selectedColumns))<td>{{ $st->statement_no }}</td>@endif
            @if(in_array('year', $selectedColumns))<td>{{ $st->year }}</td>@endif
            @if(in_array('insurer', $selectedColumns))<td>{{ $st->insurer ? $st->insurer->name : '' }}</td>@endif
            @if(in_array('business_category', $selectedColumns))<td>{{ $st->business_category }}</td>@endif
            @if(in_array('date_received', $selectedColumns))<td>{{ $st->date_received }}</td>@endif
            @if(in_array('amount_received', $selectedColumns))<td>{{ $st->amount_received }}</td>@endif
            @if(in_array('mode_of_payment', $selectedColumns))<td>{{ $st->modeOfPayment ? $st->modeOfPayment->name : '' }}</td>@endif
            @if(in_array('remarks', $selectedColumns))<td>{{ $st->remarks }}</td>@endif
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
          $current = $statements->currentPage();
          $last = max(1, $statements->lastPage());
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
        <form id="columnForm" action="{{ route('statements.save-column-settings') }}" method="POST">
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

  <!-- Add/Edit Statement Modal -->
  <div class="modal" id="statementModal">
    <div class="modal-content">
      <div class="modal-header">
        <h4 id="statementModalTitle">Add Statement</h4>
        <button type="button" class="modal-close" onclick="closeStatementModal()">×</button>
      </div>
      <form id="statementForm" method="POST">
        @csrf
        <input type="hidden" name="_method" id="statementFormMethod" value="POST">
        <div class="modal-body">
          <div class="form-row">
            <div class="form-group">
              <label for="year">Year</label>
              <input type="text" class="form-control" name="year" id="year">
            </div>
            <div class="form-group">
              <label for="insurer_id">Insurer</label>
              <select class="form-control" name="insurer_id" id="insurer_id">
                <option value="">Select</option>
                @foreach($insurers as $ins)
                  <option value="{{ $ins->id }}">{{ $ins->name }}</option>
                @endforeach
              </select>
            </div>
            <div class="form-group">
              <label for="business_category">Business Category</label>
              <input type="text" class="form-control" name="business_category" id="business_category">
            </div>
          </div>
          <div class="form-row">
            <div class="form-group">
              <label for="date_received">Date Received</label>
              <input type="date" class="form-control" name="date_received" id="date_received">
            </div>
            <div class="form-group">
              <label for="amount_received">Amount Received</label>
              <input type="number" step="0.01" class="form-control" name="amount_received" id="amount_received">
            </div>
            <div class="form-group">
              <label for="mode_of_payment_id">Mode Of Payment (Life)</label>
              <select class="form-control" name="mode_of_payment_id" id="mode_of_payment_id">
                <option value="">Select</option>
                @foreach($modesOfPayment as $mode)
                  <option value="{{ $mode->id }}">{{ $mode->name }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="form-row">
            <div class="form-group" style="flex:1 1 100%;">
              <label for="remarks">Remarks</label>
              <input type="text" class="form-control" name="remarks" id="remarks">
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn-cancel" onclick="closeStatementModal()">Cancel</button>
          <button type="submit" class="btn-save" id="statementSaveBtn">Save</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
let currentStatementId = null;

function filterByInsurer(insurer) {
  window.location.href = `{{ route('statements.index') }}?insurer=${insurer}`;
}

document.getElementById('addStatementBtn').addEventListener('click', () => openStatementModal('add'));
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

function openStatementModal(mode, st = null) {
  const modal = document.getElementById('statementModal');
  const title = document.getElementById('statementModalTitle');
  const form = document.getElementById('statementForm');
  const formMethod = document.getElementById('statementFormMethod');
  form.reset();

  if (mode === 'add') {
    title.textContent = 'Add Statement';
    form.action = "{{ route('statements.store') }}";
    formMethod.value = 'POST';
    currentStatementId = null;
    ['year','insurer_id','business_category','date_received','amount_received','mode_of_payment_id','remarks'].forEach(id => {
      let el = document.getElementById(id);
      if(el) el.value = '';
    });
  } else if (mode === 'edit' && st) {
    title.textContent = 'Edit Statement';
    form.action = `/statements/${st.id}`;
    formMethod.value = 'PUT';
    currentStatementId = st.id;
    document.getElementById('year').value = st.year ?? '';
    document.getElementById('insurer_id').value = st.insurer_id ?? '';
    document.getElementById('business_category').value = st.business_category ?? '';
    document.getElementById('date_received').value = st.date_received ?? '';
    document.getElementById('amount_received').value = st.amount_received ?? '';
    document.getElementById('mode_of_payment_id').value = st.mode_of_payment_id ?? '';
    document.getElementById('remarks').value = st.remarks ?? '';
  }

  document.body.style.overflow = 'hidden';
  modal.classList.add('show');
}

function closeStatementModal() {
  document.getElementById('statementModal').classList.remove('show');
  document.body.style.overflow = '';
}

function openEditStatement(id) {
  fetch(`/statements/${id}/edit`)
    .then(res => res.json())
    .then(st => openStatementModal('edit', st));
}

// Close modal on backdrop click or ESC
document.getElementById('statementModal').addEventListener('click', e => {
  if (e.target === document.getElementById('statementModal')) closeStatementModal();
});
document.addEventListener('keydown', e => {
  if (e.key === 'Escape') closeStatementModal();
});

document.getElementById('columnModal').addEventListener('click', e => {
  if (e.target === document.getElementById('columnModal')) closeColumnModal();
});
document.addEventListener('keydown', e => {
  if (e.key === 'Escape') closeColumnModal();
});
</script>
@endsection