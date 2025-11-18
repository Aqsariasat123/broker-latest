<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Commissions</title>
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
  $selectedColumns = session('commission_columns', [
    'policy_number','client_name','insurer','grouping','basic_premium','rate','amount_due','payment_status','amount_rcvd','date_rcvd','state_no','mode_of_payment','variance','reason','date_due','cnid'
  ]);
  $allColumns = [
    'policy_number' => 'Policy Number',
    'client_name' => "Client's Name",
    'insurer' => 'Insurer',
    'grouping' => 'Grouping',
    'basic_premium' => 'Basic Premium',
    'rate' => 'Rate',
    'amount_due' => 'Amount Due',
    'payment_status' => 'Payment Status',
    'amount_rcvd' => 'Amount Rcvd',
    'date_rcvd' => 'Date Rcvd',
    'state_no' => 'State No',
    'mode_of_payment' => 'Mode Of Payment (Life)',
    'variance' => 'Variance',
    'reason' => 'Reason',
    'date_due' => 'Date Due',
    'cnid' => 'CNID'
  ];
@endphp
<div class="dashboard">
  <div class="container-table">
    <h3>Commissions</h3>
    @if(session('success'))
      <div class="alert alert-success" id="successAlert" style="padding:8px 12px; margin-bottom:12px; border:1px solid #c3e6cb; background:#d4edda; color:#155724;">
        {{ session('success') }}
        <button type="button" class="alert-close" onclick="document.getElementById('successAlert').style.display='none'" style="float:right;background:none;border:none;font-size:16px;cursor:pointer;">×</button>
      </div>
    @endif
    <div class="top-bar" style="justify-content:center;">
      <div class="records-found">Records Found - {{ $commissions->total() }}</div> <!-- Add this line -->
      <a class="btn btn-export" href="{{ route('commissions.export', array_merge(request()->query(), ['page' => $commissions->currentPage()])) }}">Export</a>
      <button class="btn btn-column" id="columnBtn" type="button">Column</button>
      <div class="action-buttons" style="margin:auto;">
        @foreach(['SACOS','Alliance','Hsavy','MUA'] as $insurerBtn)
          <button class="btn btn-column" onclick="filterByInsurer('{{ $insurerBtn }}')" style="margin-left:5px;{{ isset($insurerFilter) && $insurerFilter==$insurerBtn ? 'background:#007bff;color:#fff;' : '' }}">{{ $insurerBtn }}</button>
        @endforeach
        <button class="btn btn-back" onclick="window.location.href='{{ route('commissions.index') }}'">All</button>
      </div>
      <div class="action-buttons">
        <button class="btn btn-add" id="addCommissionBtn">Add</button>
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
          @foreach($commissions as $com)
          <tr>
            <td>
              <span class="icon-expand" style="cursor:pointer;" onclick="openEditCommission({{ $com->id }})">⤢</span>
              <form action="{{ route('commissions.destroy', $com->id) }}" method="POST" style="display:inline;">
                @csrf
                @method('DELETE')
                <button class="btn-action btn-delete" title="Delete" style="background:none;border:none;padding:0 6px;font-size:12px;color:#dc3545;vertical-align:middle;cursor:pointer;" onclick="return confirm('Delete this commission?')">
                  🗑️
                </button>
              </form>
            </td>
            @if(in_array('policy_number', $selectedColumns))<td>{{ $com->policy_number }}</td>@endif
            @if(in_array('client_name', $selectedColumns))<td>{{ $com->client_name }}</td>@endif
            @if(in_array('insurer', $selectedColumns))<td>{{ $com->insurer ? $com->insurer->name : '' }}</td>@endif
            @if(in_array('grouping', $selectedColumns))<td>{{ $com->grouping }}</td>@endif
            @if(in_array('basic_premium', $selectedColumns))<td>{{ $com->basic_premium }}</td>@endif
            @if(in_array('rate', $selectedColumns))<td>{{ $com->rate }}</td>@endif
            @if(in_array('amount_due', $selectedColumns))<td>{{ $com->amount_due }}</td>@endif
            @if(in_array('payment_status', $selectedColumns))<td>{{ $com->paymentStatus ? $com->paymentStatus->name : '' }}</td>@endif
            @if(in_array('amount_rcvd', $selectedColumns))<td>{{ $com->amount_rcvd }}</td>@endif
            @if(in_array('date_rcvd', $selectedColumns))<td>{{ $com->date_rcvd }}</td>@endif
            @if(in_array('state_no', $selectedColumns))<td>{{ $com->state_no }}</td>@endif
            @if(in_array('mode_of_payment', $selectedColumns))<td>{{ $com->modeOfPayment ? $com->modeOfPayment->name : '' }}</td>@endif
            @if(in_array('variance', $selectedColumns))<td>{{ $com->variance }}</td>@endif
            @if(in_array('reason', $selectedColumns))<td>{{ $com->reason }}</td>@endif
            @if(in_array('date_due', $selectedColumns))<td>{{ $com->date_due }}</td>@endif
            @if(in_array('cnid', $selectedColumns))<td>{{ $com->cnid }}</td>@endif
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
          $current = $commissions->currentPage();
          $last = max(1, $commissions->lastPage());
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
        <form id="columnForm" action="{{ route('commissions.save-column-settings') }}" method="POST">
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

  <!-- Add/Edit Commission Modal -->
  <div class="modal" id="commissionModal">
    <div class="modal-content">
      <div class="modal-header">
        <h4 id="commissionModalTitle">Add Commission</h4>
        <button type="button" class="modal-close" onclick="closeCommissionModal()">×</button>
      </div>
      <form id="commissionForm" method="POST">
        @csrf
        <input type="hidden" name="_method" id="commissionFormMethod" value="POST">
        <div class="modal-body">
          <div class="form-row">
            <div class="form-group">
              <label for="policy_number">Policy Number</label>
              <input type="text" class="form-control" name="policy_number" id="policy_number">
            </div>
            <div class="form-group">
              <label for="client_name">Client's Name</label>
              <input type="text" class="form-control" name="client_name" id="client_name">
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
          </div>
          <div class="form-row">
            <div class="form-group">
              <label for="grouping">Grouping</label>
              <input type="text" class="form-control" name="grouping" id="grouping">
            </div>
            <div class="form-group">
              <label for="basic_premium">Basic Premium</label>
              <input type="number" step="0.01" class="form-control" name="basic_premium" id="basic_premium">
            </div>
            <div class="form-group">
              <label for="rate">Rate</label>
              <input type="number" step="0.01" class="form-control" name="rate" id="rate">
            </div>
          </div>
          <div class="form-row">
            <div class="form-group">
              <label for="amount_due">Amount Due</label>
              <input type="number" step="0.01" class="form-control" name="amount_due" id="amount_due">
            </div>
            <div class="form-group">
              <label for="payment_status_id">Payment Status</label>
              <select class="form-control" name="payment_status_id" id="payment_status_id">
                <option value="">Select</option>
                @foreach($paymentStatuses as $ps)
                  <option value="{{ $ps->id }}">{{ $ps->name }}</option>
                @endforeach
              </select>
            </div>
            <div class="form-group">
              <label for="amount_rcvd">Amount Rcvd</label>
              <input type="number" step="0.01" class="form-control" name="amount_rcvd" id="amount_rcvd">
            </div>
          </div>
          <div class="form-row">
            <div class="form-group">
              <label for="date_rcvd">Date Rcvd</label>
              <input type="date" class="form-control" name="date_rcvd" id="date_rcvd">
            </div>
            <div class="form-group">
              <label for="state_no">State No</label>
              <input type="text" class="form-control" name="state_no" id="state_no">
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
            <div class="form-group">
              <label for="variance">Variance</label>
              <input type="number" step="0.01" class="form-control" name="variance" id="variance">
            </div>
            <div class="form-group">
              <label for="reason">Reason</label>
              <input type="text" class="form-control" name="reason" id="reason">
            </div>
            <div class="form-group">
              <label for="date_due">Date Due</label>
              <input type="date" class="form-control" name="date_due" id="date_due">
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn-cancel" onclick="closeCommissionModal()">Cancel</button>
          <button type="submit" class="btn-save" id="commissionSaveBtn">Save</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
let currentCommissionId = null;

function filterByInsurer(insurer) {
  window.location.href = `{{ route('commissions.index') }}?insurer=${insurer}`;
}

document.getElementById('addCommissionBtn').addEventListener('click', () => openCommissionModal('add'));

function openCommissionModal(mode, com = null) {
  const modal = document.getElementById('commissionModal');
  const title = document.getElementById('commissionModalTitle');
  const form = document.getElementById('commissionForm');
  const formMethod = document.getElementById('commissionFormMethod');
  form.reset();

  if (mode === 'add') {
    title.textContent = 'Add Commission';
    form.action = "{{ route('commissions.store') }}";
    formMethod.value = 'POST';
    currentCommissionId = null;
    ['policy_number','client_name','insurer_id','grouping','basic_premium','rate','amount_due','payment_status_id','amount_rcvd','date_rcvd','state_no','mode_of_payment_id','variance','reason','date_due'].forEach(id => {
      let el = document.getElementById(id);
      if(el) el.value = '';
    });
  } else if (mode === 'edit' && com) {
    title.textContent = 'Edit Commission';
    form.action = `/commissions/${com.id}`;
    formMethod.value = 'PUT';
    currentCommissionId = com.id;
    document.getElementById('policy_number').value = com.policy_number ?? '';
    document.getElementById('client_name').value = com.client_name ?? '';
    document.getElementById('insurer_id').value = com.insurer_id ?? '';
    document.getElementById('grouping').value = com.grouping ?? '';
    document.getElementById('basic_premium').value = com.basic_premium ?? '';
    document.getElementById('rate').value = com.rate ?? '';
    document.getElementById('amount_due').value = com.amount_due ?? '';
    document.getElementById('payment_status_id').value = com.payment_status_id ?? '';
    document.getElementById('amount_rcvd').value = com.amount_rcvd ?? '';
    document.getElementById('date_rcvd').value = com.date_rcvd ?? '';
    document.getElementById('state_no').value = com.state_no ?? '';
    document.getElementById('mode_of_payment_id').value = com.mode_of_payment_id ?? '';
    document.getElementById('variance').value = com.variance ?? '';
    document.getElementById('reason').value = com.reason ?? '';
    document.getElementById('date_due').value = com.date_due ?? '';
  }

  document.body.style.overflow = 'hidden';
  modal.classList.add('show');
}

function closeCommissionModal() {
  document.getElementById('commissionModal').classList.remove('show');
  document.body.style.overflow = '';
}

function openEditCommission(id) {
  fetch(`/commissions/${id}/edit`)
    .then(res => res.json())
    .then(com => openCommissionModal('edit', com));
}

// Close modal on backdrop click or ESC
document.getElementById('commissionModal').addEventListener('click', e => {
  if (e.target === document.getElementById('commissionModal')) closeCommissionModal();
});
document.addEventListener('keydown', e => {
  if (e.key === 'Escape') closeCommissionModal();
});

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

// Close modal on backdrop click or ESC
document.getElementById('columnModal').addEventListener('click', e => {
  if (e.target === document.getElementById('columnModal')) closeColumnModal();
});
document.addEventListener('keydown', e => {
  if (e.key === 'Escape') closeColumnModal();
});
</script>
@endsection