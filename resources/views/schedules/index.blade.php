@extends('layouts.app')

@section('content')
<style>
  .container-table { max-width: 100%; margin: 0 auto; }
  h3 { background: #f1f1f1; padding: 8px; margin-bottom: 10px; font-weight: bold; border: 1px solid #ddd; }
  .top-bar { display:flex; align-items:center; flex-wrap:wrap; gap:10px; margin-bottom:10px; }
  .left-group { display:flex; align-items:center; gap:10px; flex:1 1 auto; min-width:220px; }
  .records-found { font-size:14px; color:#555; min-width:150px; }
  .action-buttons { margin-left:auto; display:flex; gap:10px; align-items:center; }
  .btn { border:none; cursor:pointer; padding:6px 12px; font-size:13px; border-radius:2px; white-space:nowrap; transition:background-color .2s; text-decoration:none; color:inherit; background:#fff; border:1px solid #ccc; }
  .btn-add { background:#df7900; color:#fff; border-color:#df7900; }
  .btn-back { background:#ccc; color:#333; border-color:#ccc; }
  .table-responsive { width: 100%; overflow-x: auto; border: 1px solid #ddd; max-height: 520px; overflow-y: auto; background: #fff; }
  .footer { display:flex; justify-content:center; align-items:center; padding:5px 0; gap:10px; border-top:1px solid #ccc; flex-wrap:wrap; margin-top:15px; }
  table { width:100%; border-collapse:collapse; font-size:13px; min-width:1000px; }
  thead tr { background-color: black; color: white; height:35px; font-weight: normal; }
  thead th { padding:6px 5px; text-align:left; border-right:1px solid #444; white-space:nowrap; font-weight: normal; }
  tbody tr { background-color:#fefefe; border-bottom:1px solid #ddd; }
  tbody tr:nth-child(even) { background-color:#f8f8f8; }
  tbody td { padding:5px 5px; border-right:1px solid #ddd; white-space:nowrap; vertical-align:middle; font-size:12px; }
  .btn-action { padding:2px 6px; font-size:11px; margin:1px; border:1px solid #ddd; background:#fff; cursor:pointer; border-radius:2px; display:inline-block; }
  .badge-status { font-size:11px; padding:4px 8px; display:inline-block; border-radius:4px; color:#fff; }
  .badge-draft { background:#6c757d; }
  .badge-active { background:#28a745; }
  .badge-expired { background:#dc3545; }
  .badge-cancelled { background:#ffc107; color:#000; }
  input[type="text"], select { padding:6px 8px; border:1px solid #ccc; border-radius:2px; font-size:13px; }
  
  /* Modal Styles */
  .modal { display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,.5); z-index:1000; align-items:center; justify-content:center; }
  .modal.show { display:flex; }
  .modal-content { background:#fff; border-radius:6px; width:90%; max-width:800px; max-height:calc(100vh - 40px); overflow:auto; box-shadow:0 4px 6px rgba(0,0,0,.1); padding:0; }
  .modal-header { padding:15px 20px; border-bottom:1px solid #ddd; display:flex; justify-content:space-between; align-items:center; background:white; }
  .modal-header h4 { margin:0; font-size:18px; font-weight:bold; color:#2d2d2d; }
  .modal-body { padding:20px; }
  .modal-footer { padding:15px 20px; border-top:1px solid #ddd; display:flex; justify-content:center; gap:10px; background:#f9f9f9; }
  .modal-close { background:none; border:none; font-size:24px; cursor:pointer; color:#666; line-height:1; padding:0; width:24px; height:24px; }
  
  /* Form Styles */
  .form-group { margin-bottom:15px; }
  .form-group label { display:block; margin-bottom:5px; font-weight:bold; font-size:13px; color:#2d2d2d; }
  .form-control { width:100%; padding:6px 10px; border:1px solid #ddd; border-radius:2px; font-size:13px; background:#f8f8f8; }
  .form-control:focus { outline:none; border-color:#007bff; background:#fff; }
  textarea.form-control { min-height:100px; resize:vertical; }
  .form-row { display:grid; grid-template-columns:repeat(2, 1fr); gap:15px; margin-bottom:15px; }
  .form-row.full-width { grid-template-columns:1fr; }
  
  /* Button Styles */
  .btn-save { background:#f3742a; color:#fff; border:none; padding:8px 20px; border-radius:2px; cursor:pointer; font-size:13px; }
  .btn-cancel { background:#000; color:#fff; border:none; padding:8px 20px; border-radius:2px; cursor:pointer; font-size:13px; }
</style>

@if(session('success'))
  <div class="alert alert-success" id="successAlert" style="padding:8px 12px; margin-bottom:12px; border:1px solid #c3e6cb; background:#d4edda; color:#155724;">
    {{ session('success') }}
    <button type="button" class="alert-close" onclick="document.getElementById('successAlert').style.display='none'" style="float:right;background:none;border:none;font-size:16px;cursor:pointer;">×</button>
  </div>
@endif

<div class="dashboard">
  <div class="container-table">
    <h3>Schedules</h3>

    <div class="top-bar">
      <div class="left-group">
        <div class="records-found">Records Found - {{ $schedules->total() }}</div>
        <form method="GET" action="{{ route('schedules.index') }}" style="display: flex; gap: 8px; align-items: center; flex-wrap: wrap;">
          <input type="text" name="search" placeholder="Search..." value="{{ request('search') }}">
          <select name="status">
            <option value="">All Status</option>
            <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
            <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expired</option>
            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
          </select>
          <button type="submit" class="btn">Filter</button>
          @if(request()->hasAny(['search', 'status']))
            <a href="{{ route('schedules.index') }}" class="btn">Clear</a>
          @endif
        </form>
      </div>
      <div class="action-buttons">
        <button class="btn btn-add" onclick="openScheduleModal('add')">Add</button>
        <button class="btn btn-back" onclick="window.history.back()">Back</button>
      </div>
    </div>

    <div class="table-responsive">
      <table>
        <thead>
          <tr>
            <th>Schedule No</th>
            <th>Policy</th>
            <th>Client</th>
            <th>Issued On</th>
            <th>Effective From</th>
            <th>Effective To</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($schedules as $schedule)
            <tr>
              <td>{{ $schedule->schedule_no }}</td>
              <td>{{ $schedule->policy->policy_no ?? '-' }}</td>
              <td>{{ $schedule->policy->client->client_name ?? '-' }}</td>
              <td>{{ $schedule->issued_on ? $schedule->issued_on->format('d-M-y') : '-' }}</td>
              <td>{{ $schedule->effective_from ? $schedule->effective_from->format('d-M-y') : '-' }}</td>
              <td>{{ $schedule->effective_to ? $schedule->effective_to->format('d-M-y') : '-' }}</td>
              <td>
                <span class="badge-status badge-{{ $schedule->status }}">
                  {{ ucfirst($schedule->status) }}
                </span>
              </td>
              <td>
                <a href="{{ route('schedules.show', $schedule->id) }}" class="btn-action">View</a>
                <button type="button" class="btn-action" onclick="openScheduleModal('edit', {{ $schedule->id }})">Edit</button>
                <form action="{{ route('schedules.destroy', $schedule->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure?');">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn-action">Delete</button>
                </form>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="8" style="text-align:center; padding:20px; color:#999;">No schedules found</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div class="footer">
      {{ $schedules->links() }}
    </div>
  </div>
</div>

<!-- Add/Edit Schedule Modal -->
<div class="modal" id="scheduleModal">
  <div class="modal-content" style="max-width:800px; max-height:90vh; overflow-y:auto;">
    <div class="modal-header" style="display:flex; justify-content:space-between; align-items:center; padding:15px 20px; border-bottom:1px solid #ddd; background:#fff;">
      <h4 id="scheduleModalTitle" style="margin:0; font-size:18px; font-weight:bold;">Add Schedule</h4>
      <div style="display:flex; gap:10px;">
        <button type="submit" form="scheduleForm" class="btn-save" style="background:#f3742a; color:#fff; border:none; padding:8px 20px; border-radius:2px; cursor:pointer; font-size:13px;">Save</button>
        <button type="button" class="btn-cancel" onclick="closeScheduleModal()" style="background:#000; color:#fff; border:none; padding:8px 20px; border-radius:2px; cursor:pointer; font-size:13px;">Cancel</button>
      </div>
    </div>
    <form id="scheduleForm" method="POST" action="{{ route('schedules.store') }}">
      @csrf
      <div id="scheduleFormMethod" style="display:none;"></div>
      <div class="modal-body" style="padding:20px;">
        <div class="form-row full-width" style="display:flex; gap:15px; margin-bottom:15px;">
          <div class="form-group" style="flex:1;">
            <label for="policy_id" style="display:block; margin-bottom:5px; font-size:13px; font-weight:500;">Policy *</label>
            <select id="policy_id" name="policy_id" class="form-control" required style="width:100%; padding:8px; border:1px solid #ddd; border-radius:2px; font-size:13px;">
              <option value="">Select Policy</option>
              @foreach($policies as $policy)
                <option value="{{ $policy->id }}">
                  {{ $policy->policy_no }} - {{ $policy->client->client_name ?? 'N/A' }}
                </option>
              @endforeach
            </select>
          </div>
        </div>
        <div class="form-row" style="display:flex; gap:15px; margin-bottom:15px;">
          <div class="form-group" style="flex:1;">
            <label for="schedule_no" style="display:block; margin-bottom:5px; font-size:13px; font-weight:500;">Schedule Number *</label>
            <input type="text" id="schedule_no" name="schedule_no" class="form-control" required style="width:100%; padding:8px; border:1px solid #ddd; border-radius:2px; font-size:13px;">
          </div>
          <div class="form-group" style="flex:1;">
            <label for="status" style="display:block; margin-bottom:5px; font-size:13px; font-weight:500;">Status *</label>
            <select id="status" name="status" class="form-control" required style="width:100%; padding:8px; border:1px solid #ddd; border-radius:2px; font-size:13px;">
              <option value="draft">Draft</option>
              <option value="active">Active</option>
              <option value="expired">Expired</option>
              <option value="cancelled">Cancelled</option>
            </select>
          </div>
        </div>
        <div class="form-row" style="display:flex; gap:15px; margin-bottom:15px;">
          <div class="form-group" style="flex:1;">
            <label for="issued_on" style="display:block; margin-bottom:5px; font-size:13px; font-weight:500;">Issued On</label>
            <input type="date" id="issued_on" name="issued_on" class="form-control" style="width:100%; padding:8px; border:1px solid #ddd; border-radius:2px; font-size:13px;">
          </div>
          <div class="form-group" style="flex:1;">
            <label for="effective_from" style="display:block; margin-bottom:5px; font-size:13px; font-weight:500;">Effective From</label>
            <input type="date" id="effective_from" name="effective_from" class="form-control" style="width:100%; padding:8px; border:1px solid #ddd; border-radius:2px; font-size:13px;">
          </div>
        </div>
        <div class="form-row" style="display:flex; gap:15px; margin-bottom:15px;">
          <div class="form-group" style="flex:1;">
            <label for="effective_to" style="display:block; margin-bottom:5px; font-size:13px; font-weight:500;">Effective To</label>
            <input type="date" id="effective_to" name="effective_to" class="form-control" style="width:100%; padding:8px; border:1px solid #ddd; border-radius:2px; font-size:13px;">
          </div>
        </div>
        <div class="form-row full-width" style="display:flex; gap:15px; margin-bottom:15px;">
          <div class="form-group" style="flex:1 1 100%;">
            <label for="notes" style="display:block; margin-bottom:5px; font-size:13px; font-weight:500;">Notes</label>
            <textarea id="notes" name="notes" class="form-control" rows="4" style="width:100%; padding:8px; border:1px solid #ddd; border-radius:2px; font-size:13px; resize:vertical;"></textarea>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>

<script>
  let currentScheduleId = null;

  function openScheduleModal(mode, scheduleId = null) {
    const modal = document.getElementById('scheduleModal');
    const form = document.getElementById('scheduleForm');
    const formMethod = document.getElementById('scheduleFormMethod');
    const modalTitle = document.getElementById('scheduleModalTitle');
    
    currentScheduleId = scheduleId;
    
    if (mode === 'add') {
      modalTitle.textContent = 'Add Schedule';
      form.reset();
      form.action = '{{ route("schedules.store") }}';
      formMethod.innerHTML = '';
      currentScheduleId = null;
    } else if (mode === 'edit' && scheduleId) {
      modalTitle.textContent = 'Edit Schedule';
      form.action = '{{ route("schedules.update", ":id") }}'.replace(':id', scheduleId);
      formMethod.innerHTML = '@method("PUT")';
      
      // Fetch schedule data
      fetch(`/schedules/${scheduleId}/edit`)
        .then(response => response.json())
        .then(data => {
          if (data.schedule) {
            const s = data.schedule;
            document.getElementById('policy_id').value = s.policy_id || '';
            document.getElementById('schedule_no').value = s.schedule_no || '';
            document.getElementById('status').value = s.status || 'draft';
            document.getElementById('issued_on').value = s.issued_on ? s.issued_on.split('T')[0] : '';
            document.getElementById('effective_from').value = s.effective_from ? s.effective_from.split('T')[0] : '';
            document.getElementById('effective_to').value = s.effective_to ? s.effective_to.split('T')[0] : '';
            document.getElementById('notes').value = s.notes || '';
          }
        })
        .catch(error => {
          console.error('Error fetching schedule data:', error);
          alert('Error loading schedule data');
        });
    }
    
    modal.classList.add('show');
    document.body.style.overflow = 'hidden';
  }

  function closeScheduleModal() {
    const modal = document.getElementById('scheduleModal');
    modal.classList.remove('show');
    document.body.style.overflow = '';
    const form = document.getElementById('scheduleForm');
    form.reset();
    currentScheduleId = null;
  }

  // Close modal on outside click
  document.getElementById('scheduleModal').addEventListener('click', function(e) {
    if (e.target === this) {
      closeScheduleModal();
    }
  });

  // Close modal on ESC key
  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
      closeScheduleModal();
    }
  });

  // Handle form submission
  document.getElementById('scheduleForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const form = this;
    const formData = new FormData(form);
    const url = form.action;
    const method = form.querySelector('[name="_method"]') ? form.querySelector('[name="_method"]').value : 'POST';
    
    // Add method override if needed
    if (method !== 'POST') {
      formData.append('_method', method);
    }
    
    fetch(url, {
      method: 'POST',
      body: formData,
      headers: {
        'X-Requested-With': 'XMLHttpRequest',
        'Accept': 'application/json'
      }
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        closeScheduleModal();
        window.location.reload();
      } else {
        alert(data.message || 'Error saving schedule');
      }
    })
    .catch(error => {
      console.error('Error:', error);
      alert('Error saving schedule');
    });
  });
</script>
@endsection

