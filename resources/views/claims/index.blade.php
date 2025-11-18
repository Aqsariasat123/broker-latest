<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Claims</title>
  <style>
    * { box-sizing: border-box; }
    body { font-family: Arial, sans-serif; color: #000; margin: 10px; background: #fff; }
    .container-table { max-width: 100%; margin: 0 auto; }
    h3 { background: #f1f1f1; padding: 8px; margin-bottom: 10px; font-weight: bold; border: 1px solid #ddd; }
    .top-bar { display:flex; align-items:center; flex-wrap:wrap; gap:10px; margin-bottom:10px; }
    .left-group { display:flex; align-items:center; gap:10px; flex:1 1 auto; min-width:220px; }
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
    /* Modal styles */
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
  $selectedColumns = session('claim_columns', [
    'claim_id','policy_no','client_name','loss_date','claim_date','claim_amount','claim_summary','status','close_date','paid_amount','settlment_notes'
  ]);
  $allColumns = [
    'claim_id' => 'Claim ID',
    'policy_no' => 'Policy No',
    'client_name' => 'Client Name',
    'loss_date' => 'Loss Date',
    'claim_date' => 'Claim Date',
    'claim_amount' => 'Claim Amount',
    'claim_summary' => 'Claim Summary',
    'status' => 'Status',
    'close_date' => 'Close Date',
    'paid_amount' => 'Paid Amount',
    'settlment_notes' => 'Settlment Notes'
  ];
@endphp
<div class="dashboard">
  <div class="container-table">
    <h3>Claims</h3>
    @if(session('success'))
      <div class="alert alert-success" id="successAlert" style="padding:8px 12px; margin-bottom:12px; border:1px solid #c3e6cb; background:#d4edda; color:#155724;">
        {{ session('success') }}
        <button type="button" class="alert-close" onclick="document.getElementById('successAlert').style.display='none'" style="float:right;background:none;border:none;font-size:16px;cursor:pointer;">×</button>
      </div>
    @endif
    <div class="top-bar">
      <div class="left-group">
        <div class="records-found">Records Found - {{ $claims->total() }}</div>
        <a class="btn btn-export" href="{{ route('claims.export', array_merge(request()->query(), ['page' => $claims->currentPage()])) }}">Export</a>
        <button class="btn btn-column" id="columnBtn" type="button">Column</button>
      </div>
      <div class="action-buttons">
        <button class="btn btn-add" id="addClaimBtn">Add</button>
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
          @foreach($claims as $clm)
          <tr>
            <td>
              <span class="icon-expand" style="cursor:pointer;" onclick="openEditClaim({{ $clm->id }})">⤢</span>
              <form action="{{ route('claims.destroy', $clm->id) }}" method="POST" style="display:inline;">
                @csrf
                @method('DELETE')
                <button class="btn-action btn-delete" title="Delete" style="background:none;border:none;padding:0 6px;font-size:12px;color:#dc3545;vertical-align:middle;cursor:pointer;" onclick="return confirm('Delete this claim?')">
                  🗑️
                </button>
              </form>
            </td>
            @if(in_array('claim_id', $selectedColumns))<td>{{ $clm->claim_id }}</td>@endif
            @if(in_array('policy_no', $selectedColumns))<td>{{ $clm->policy_no }}</td>@endif
            @if(in_array('client_name', $selectedColumns))<td>{{ $clm->client_name }}</td>@endif
            @if(in_array('loss_date', $selectedColumns))<td>{{ $clm->loss_date }}</td>@endif
            @if(in_array('claim_date', $selectedColumns))<td>{{ $clm->claim_date }}</td>@endif
            @if(in_array('claim_amount', $selectedColumns))<td>{{ $clm->claim_amount }}</td>@endif
            @if(in_array('claim_summary', $selectedColumns))<td>{{ $clm->claim_summary }}</td>@endif
            @if(in_array('status', $selectedColumns))<td>{{ $clm->status }}</td>@endif
            @if(in_array('close_date', $selectedColumns))<td>{{ $clm->close_date }}</td>@endif
            @if(in_array('paid_amount', $selectedColumns))<td>{{ $clm->paid_amount }}</td>@endif
            @if(in_array('settlment_notes', $selectedColumns))<td>{{ $clm->settlment_notes }}</td>@endif
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
          $current = $claims->currentPage();
          $last = max(1, $claims->lastPage());
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
        <form id="columnForm" action="{{ route('claims.save-column-settings') }}" method="POST">
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

  <!-- Add/Edit Claim Modal -->
  <div class="modal" id="claimModal">
    <div class="modal-content">
      <div class="modal-header">
        <h4 id="claimModalTitle">Add Claim</h4>
        <button type="button" class="modal-close" onclick="closeClaimModal()">×</button>
      </div>
      <form id="claimForm" method="POST">
        @csrf
        <input type="hidden" name="_method" id="claimFormMethod" value="POST">
        <div class="modal-body">
          <div class="form-row">
            <div class="form-group">
              <label for="policy_no">Policy No</label>
              <input type="text" class="form-control" name="policy_no" id="policy_no">
            </div>
            <div class="form-group">
              <label for="client_name">Client Name</label>
              <input type="text" class="form-control" name="client_name" id="client_name">
            </div>
            <div class="form-group">
              <label for="loss_date">Loss Date</label>
              <input type="date" class="form-control" name="loss_date" id="loss_date">
            </div>
          </div>
          <div class="form-row">
            <div class="form-group">
              <label for="claim_date">Claim Date</label>
              <input type="date" class="form-control" name="claim_date" id="claim_date">
            </div>
            <div class="form-group">
              <label for="claim_amount">Claim Amount</label>
              <input type="number" step="0.01" class="form-control" name="claim_amount" id="claim_amount">
            </div>
            <div class="form-group">
              <label for="claim_summary">Claim Summary</label>
              <input type="text" class="form-control" name="claim_summary" id="claim_summary">
            </div>
          </div>
          <div class="form-row">
            <div class="form-group">
              <label for="status">Status</label>
              <input type="text" class="form-control" name="status" id="status">
            </div>
            <div class="form-group">
              <label for="close_date">Close Date</label>
              <input type="date" class="form-control" name="close_date" id="close_date">
            </div>
            <div class="form-group">
              <label for="paid_amount">Paid Amount</label>
              <input type="number" step="0.01" class="form-control" name="paid_amount" id="paid_amount">
            </div>
          </div>
          <div class="form-row">
            <div class="form-group" style="flex:1 1 100%;">
              <label for="settlment_notes">Settlment Notes</label>
              <textarea class="form-control" name="settlment_notes" id="settlment_notes"></textarea>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn-cancel" onclick="closeClaimModal()">Cancel</button>
          <button type="submit" class="btn-save" id="claimSaveBtn">Save</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
let currentClaimId = null;

document.getElementById('addClaimBtn').addEventListener('click', () => openClaimModal('add'));
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

function openClaimModal(mode, clm = null) {
  const modal = document.getElementById('claimModal');
  const title = document.getElementById('claimModalTitle');
  const form = document.getElementById('claimForm');
  const formMethod = document.getElementById('claimFormMethod');
  form.reset();

  if (mode === 'add') {
    title.textContent = 'Add Claim';
    form.action = "{{ route('claims.store') }}";
    formMethod.value = 'POST';
    currentClaimId = null;
    document.getElementById('policy_no').value = '';
    document.getElementById('client_name').value = '';
    document.getElementById('loss_date').value = '';
    document.getElementById('claim_date').value = '';
    document.getElementById('claim_amount').value = '';
    document.getElementById('claim_summary').value = '';
    document.getElementById('status').value = '';
    document.getElementById('close_date').value = '';
    document.getElementById('paid_amount').value = '';
    document.getElementById('settlment_notes').value = '';
  } else if (mode === 'edit' && clm) {
    title.textContent = 'Edit Claim';
    form.action = `/claims/${clm.id}`;
    formMethod.value = 'PUT';
    currentClaimId = clm.id;
    document.getElementById('policy_no').value = clm.policy_no ?? '';
    document.getElementById('client_name').value = clm.client_name ?? '';
    document.getElementById('loss_date').value = clm.loss_date ?? '';
    document.getElementById('claim_date').value = clm.claim_date ?? '';
    document.getElementById('claim_amount').value = clm.claim_amount ?? '';
    document.getElementById('claim_summary').value = clm.claim_summary ?? '';
    document.getElementById('status').value = clm.status ?? '';
    document.getElementById('close_date').value = clm.close_date ?? '';
    document.getElementById('paid_amount').value = clm.paid_amount ?? '';
    document.getElementById('settlment_notes').value = clm.settlment_notes ?? '';
  }

  document.body.style.overflow = 'hidden';
  modal.classList.add('show');
}

function closeClaimModal() {
  document.getElementById('claimModal').classList.remove('show');
  document.body.style.overflow = '';
}

function openEditClaim(id) {
  fetch(`/claims/${id}/edit`)
    .then(res => res.json())
    .then(clm => openClaimModal('edit', clm));
}

// Close modal on backdrop click or ESC
document.getElementById('claimModal').addEventListener('click', e => {
  if (e.target === document.getElementById('claimModal')) closeClaimModal();
});
document.addEventListener('keydown', e => {
  if (e.key === 'Escape') closeClaimModal();
});
</script>
@endsection
</body>
</html>