@extends('layouts.app')

@section('content')
<style>
  .container-table { max-width: 100%; margin: 0 auto; }
  h3 { background: #f1f1f1; padding: 8px; margin-bottom: 10px; font-weight: bold; border: 1px solid #ddd; }
  .top-bar { display:flex; align-items:center; flex-wrap:wrap; gap:10px; margin-bottom:10px; }
  .left-group { display:flex; align-items:center; gap:10px; flex:1 1 auto; min-width:220px; }
  .btn { border:none; cursor:pointer; padding:6px 12px; font-size:13px; border-radius:2px; white-space:nowrap; transition:background-color .2s; text-decoration:none; color:inherit; background:#fff; border:1px solid #ccc; }
  .btn-back { background:#ccc; color:#333; border-color:#ccc; }
  .info-section { background:#fff; border:1px solid #ddd; padding:15px; margin-bottom:15px; }
  .info-section h4 { margin-top:0; margin-bottom:12px; color:#333; border-bottom:2px solid #007bff; padding-bottom:8px; }
  .detail-row { display:grid; grid-template-columns: 200px 1fr; gap:10px; padding:8px 0; border-bottom:1px solid #eee; }
  .detail-row:last-child { border-bottom:none; }
  .detail-label { font-weight:600; color:#555; }
  .detail-value { color:#333; }
  .badge-status { font-size:11px; padding:4px 8px; display:inline-block; border-radius:4px; color:#fff; }
  .badge-draft { background:#6c757d; }
  .badge-active { background:#28a745; }
  .badge-expired { background:#dc3545; }
  .badge-cancelled { background:#ffc107; color:#000; }
  .table-responsive { width: 100%; overflow-x: auto; border: 1px solid #ddd; max-height: 400px; overflow-y: auto; background: #fff; margin-top:10px; }
  table { width:100%; border-collapse:collapse; font-size:13px; }
  thead tr { background-color: black; color: white; height:35px; font-weight: normal; }
  thead th { padding:6px 5px; text-align:left; border-right:1px solid #444; white-space:nowrap; font-weight: normal; }
  tbody tr { background-color:#fefefe; border-bottom:1px solid #ddd; }
  tbody tr:nth-child(even) { background-color:#f8f8f8; }
  tbody td { padding:5px 5px; border-right:1px solid #ddd; white-space:nowrap; vertical-align:middle; font-size:12px; }
  .btn-action { padding:2px 6px; font-size:11px; margin:1px; border:1px solid #ddd; background:#fff; cursor:pointer; border-radius:2px; display:inline-block; text-decoration:none; color:#333; }
</style>

<div class="dashboard">
  <div class="container-table">
    <h3>Schedule Details</h3>
    
    <div class="top-bar">
      <div class="left-group">
        <a href="{{ route('schedules.index') }}" class="btn btn-back">Back to List</a>
        <a href="{{ route('schedules.edit', $schedule->id) }}" class="btn">Edit</a>
        <a href="{{ route('payment-plans.create') }}?schedule_id={{ $schedule->id }}" class="btn" style="background:#df7900; color:#fff; border-color:#df7900;">Add Payment Plan</a>
      </div>
    </div>

    <div class="info-section">
      <h4>Schedule Information</h4>
      <div class="detail-row">
        <div class="detail-label">Schedule Number</div>
        <div class="detail-value">{{ $schedule->schedule_no }}</div>
      </div>
      <div class="detail-row">
        <div class="detail-label">Policy</div>
        <div class="detail-value">{{ $schedule->policy->policy_no ?? '-' }}</div>
      </div>
      <div class="detail-row">
        <div class="detail-label">Client</div>
        <div class="detail-value">{{ $schedule->policy->client->client_name ?? '-' }}</div>
      </div>
      <div class="detail-row">
        <div class="detail-label">Issued On</div>
        <div class="detail-value">{{ $schedule->issued_on ? $schedule->issued_on->format('d-M-Y') : '-' }}</div>
      </div>
      <div class="detail-row">
        <div class="detail-label">Effective From</div>
        <div class="detail-value">{{ $schedule->effective_from ? $schedule->effective_from->format('d-M-Y') : '-' }}</div>
      </div>
      <div class="detail-row">
        <div class="detail-label">Effective To</div>
        <div class="detail-value">{{ $schedule->effective_to ? $schedule->effective_to->format('d-M-Y') : '-' }}</div>
      </div>
      <div class="detail-row">
        <div class="detail-label">Status</div>
        <div class="detail-value">
          <span class="badge-status badge-{{ $schedule->status }}">
            {{ ucfirst($schedule->status) }}
          </span>
        </div>
      </div>
      @if($schedule->notes)
      <div class="detail-row">
        <div class="detail-label">Notes</div>
        <div class="detail-value">{{ $schedule->notes }}</div>
      </div>
      @endif
    </div>

    <div class="info-section">
      <h4>Payment Plans</h4>
      <div class="table-responsive">
        <table>
          <thead>
            <tr>
              <th>Instalment Label</th>
              <th>Due Date</th>
              <th>Amount</th>
              <th>Frequency</th>
              <th>Status</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            @forelse($schedule->paymentPlans as $plan)
              <tr>
                <td>{{ $plan->installment_label ?? 'Instalment #' . $plan->id }}</td>
                <td>{{ $plan->due_date ? $plan->due_date->format('d-M-y') : '-' }}</td>
                <td>{{ number_format($plan->amount, 2) }}</td>
                <td>{{ $plan->frequency ?? '-' }}</td>
                <td>
                  <span class="badge-status badge-{{ $plan->status }}">
                    {{ ucfirst($plan->status) }}
                  </span>
                </td>
                <td>
                  <a href="{{ route('payment-plans.show', $plan->id) }}" class="btn-action">View</a>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="6" style="text-align:center; padding:20px; color:#999;">No payment plans for this schedule</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection

