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
  .badge-pending { background:#ffc107; color:#000; }
  .badge-issued { background:#17a2b8; }
  .badge-paid { background:#28a745; }
  .badge-overdue { background:#dc3545; }
  .badge-cancelled { background:#6c757d; }
  input[type="text"], select { padding:6px 8px; border:1px solid #ccc; border-radius:2px; font-size:13px; }
</style>

@if(session('success'))
  <div class="alert alert-success" id="successAlert" style="padding:8px 12px; margin-bottom:12px; border:1px solid #c3e6cb; background:#d4edda; color:#155724;">
    {{ session('success') }}
    <button type="button" class="alert-close" onclick="document.getElementById('successAlert').style.display='none'" style="float:right;background:none;border:none;font-size:16px;cursor:pointer;">×</button>
  </div>
@endif

<div class="dashboard">
  <div class="container-table">
    <h3>Debit Notes</h3>

    <div class="top-bar">
      <div class="left-group">
        <div class="records-found">Records Found - {{ $debitNotes->total() }}</div>
        <form method="GET" action="{{ route('debit-notes.index') }}" style="display: flex; gap: 8px; align-items: center; flex-wrap: wrap;">
          <input type="text" name="search" placeholder="Search..." value="{{ request('search') }}">
          <select name="status">
            <option value="">All Status</option>
            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="issued" {{ request('status') == 'issued' ? 'selected' : '' }}>Issued</option>
            <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
            <option value="overdue" {{ request('status') == 'overdue' ? 'selected' : '' }}>Overdue</option>
            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
          </select>
          <button type="submit" class="btn">Filter</button>
          @if(request()->hasAny(['search', 'status']))
            <a href="{{ route('debit-notes.index') }}" class="btn">Clear</a>
          @endif
        </form>
      </div>
      <div class="action-buttons">
        <a href="{{ route('debit-notes.create') }}" class="btn btn-add">Add</a>
        <button class="btn btn-back" onclick="window.history.back()">Back</button>
      </div>
    </div>

    <div class="table-responsive">
      <table>
        <thead>
          <tr>
            <th>Debit Note No</th>
            <th>Policy</th>
            <th>Client</th>
            <th>Issued On</th>
            <th>Amount</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($debitNotes as $note)
            <tr>
              <td>{{ $note->debit_note_no }}</td>
              <td>{{ $note->paymentPlan->schedule->policy->policy_no ?? '-' }}</td>
              <td>{{ $note->paymentPlan->schedule->policy->client->client_name ?? '-' }}</td>
              <td>{{ $note->issued_on ? $note->issued_on->format('d-M-y') : '-' }}</td>
              <td>{{ number_format($note->amount, 2) }}</td>
              <td>
                <span class="badge-status badge-{{ $note->status }}">
                  {{ ucfirst($note->status) }}
                </span>
              </td>
              <td>
                <a href="{{ route('debit-notes.show', $note->id) }}" class="btn-action">View</a>
                <a href="{{ route('debit-notes.edit', $note->id) }}" class="btn-action">Edit</a>
                <form action="{{ route('debit-notes.destroy', $note->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure?');">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn-action">Delete</button>
                </form>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="7" style="text-align:center; padding:20px; color:#999;">No debit notes found</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div class="footer">
      {{ $debitNotes->links() }}
    </div>
  </div>
</div>
@endsection

