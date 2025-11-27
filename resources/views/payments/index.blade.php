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
    <h3>Payments</h3>

    <div class="top-bar">
      <div class="left-group">
        <div class="records-found">Records Found - {{ $payments->total() }}</div>
        <form method="GET" action="{{ route('payments.index') }}" style="display: flex; gap: 8px; align-items: center; flex-wrap: wrap;">
          <input type="text" name="search" placeholder="Search..." value="{{ request('search') }}">
          <input type="date" name="date_from" value="{{ request('date_from') }}" placeholder="From">
          <input type="date" name="date_to" value="{{ request('date_to') }}" placeholder="To">
          <button type="submit" class="btn">Filter</button>
          @if(request()->hasAny(['search', 'date_from', 'date_to']))
            <a href="{{ route('payments.index') }}" class="btn">Clear</a>
          @endif
        </form>
      </div>
      <div class="action-buttons">
        <a href="{{ route('payments.create') }}" class="btn btn-add">Add</a>
        <a href="{{ route('payments.report') }}" class="btn">Report</a>
        <button class="btn btn-back" onclick="window.history.back()">Back</button>
      </div>
    </div>

    <div class="table-responsive">
      <table>
        <thead>
          <tr>
            <th>Payment Reference</th>
            <th>Policy</th>
            <th>Client</th>
            <th>Debit Note</th>
            <th>Paid On</th>
            <th>Amount</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($payments as $payment)
            <tr>
              <td>{{ $payment->payment_reference }}</td>
              <td>{{ $payment->debitNote->paymentPlan->schedule->policy->policy_no ?? '-' }}</td>
              <td>{{ $payment->debitNote->paymentPlan->schedule->policy->client->client_name ?? '-' }}</td>
              <td>{{ $payment->debitNote->debit_note_no ?? '-' }}</td>
              <td>{{ $payment->paid_on ? $payment->paid_on->format('d-M-y') : '-' }}</td>
              <td>{{ number_format($payment->amount, 2) }}</td>
              <td>
                <a href="{{ route('payments.show', $payment->id) }}" class="btn-action">View</a>
                <a href="{{ route('payments.edit', $payment->id) }}" class="btn-action">Edit</a>
                <form action="{{ route('payments.destroy', $payment->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure?');">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn-action">Delete</button>
                </form>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="7" style="text-align:center; padding:20px; color:#999;">No payments found</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div class="footer">
      {{ $payments->links() }}
    </div>
  </div>
</div>
@endsection

