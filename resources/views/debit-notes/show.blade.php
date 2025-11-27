@extends('layouts.app')

@section('content')
<style>
  .container-table { max-width: 100%; margin: 0 auto; }
  h3 { background: #f1f1f1; padding: 8px; margin-bottom: 10px; font-weight: bold; border: 1px solid #ddd; }
  .top-bar { display:flex; align-items:center; flex-wrap:wrap; gap:10px; margin-bottom:10px; }
  .left-group { display:flex; align-items:center; gap:10px; flex:1 1 auto; min-width:220px; }
  .btn { border:none; cursor:pointer; padding:6px 12px; font-size:13px; border-radius:2px; white-space:nowrap; transition:background-color .2s; text-decoration:none; color:inherit; background:#fff; border:1px solid #ccc; }
  .btn-back { background:#ccc; color:#333; border-color:#ccc; }
  .btn-add { background:#df7900; color:#fff; border-color:#df7900; }
  .info-section { background:#fff; border:1px solid #ddd; padding:15px; margin-bottom:15px; }
  .info-section h4 { margin-top:0; margin-bottom:12px; color:#333; border-bottom:2px solid #007bff; padding-bottom:8px; }
  .detail-row { display:grid; grid-template-columns: 200px 1fr; gap:10px; padding:8px 0; border-bottom:1px solid #eee; }
  .detail-row:last-child { border-bottom:none; }
  .detail-label { font-weight:600; color:#555; }
  .detail-value { color:#333; }
  .badge-status { font-size:11px; padding:4px 8px; display:inline-block; border-radius:4px; color:#fff; }
  .badge-pending { background:#ffc107; color:#000; }
  .badge-issued { background:#17a2b8; }
  .badge-paid { background:#28a745; }
  .badge-overdue { background:#dc3545; }
  .badge-cancelled { background:#6c757d; }
  .table-responsive { width: 100%; overflow-x: auto; border: 1px solid #ddd; max-height: 400px; overflow-y: auto; background: #fff; margin-top:10px; }
  table { width:100%; border-collapse:collapse; font-size:13px; }
  thead tr { background-color: black; color: white; height:35px; font-weight: normal; }
  thead th { padding:6px 5px; text-align:left; border-right:1px solid #444; white-space:nowrap; font-weight: normal; }
  tbody tr { background-color:#fefefe; border-bottom:1px solid #ddd; }
  tbody tr:nth-child(even) { background-color:#f8f8f8; }
  tbody td { padding:5px 5px; border-right:1px solid #ddd; white-space:nowrap; vertical-align:middle; font-size:12px; }
  .btn-action { padding:2px 6px; font-size:11px; margin:1px; border:1px solid #ddd; background:#fff; cursor:pointer; border-radius:2px; display:inline-block; text-decoration:none; color:#333; }
  .btn-action:hover { background:#f0f0f0; }
</style>

<div class="dashboard">
  <div class="container-table">
    <h3>Debit Note Details</h3>
    
    <div class="top-bar">
      <div class="left-group">
        <a href="{{ route('debit-notes.index') }}" class="btn btn-back">Back to List</a>
        <a href="{{ route('debit-notes.edit', $debitNote->id) }}" class="btn">Edit</a>
        <a href="{{ route('payments.create') }}?debit_note_id={{ $debitNote->id }}" class="btn btn-add">Add Payment</a>
      </div>
    </div>

    <div class="info-section">
      <h4>Debit Note Information</h4>
      <div class="detail-row">
        <div class="detail-label">Debit Note Number</div>
        <div class="detail-value">{{ $debitNote->debit_note_no }}</div>
      </div>
      <div class="detail-row">
        <div class="detail-label">Policy</div>
        <div class="detail-value">{{ $debitNote->paymentPlan->schedule->policy->policy_no ?? '-' }}</div>
      </div>
      <div class="detail-row">
        <div class="detail-label">Client</div>
        <div class="detail-value">{{ $debitNote->paymentPlan->schedule->policy->client->client_name ?? '-' }}</div>
      </div>
      <div class="detail-row">
        <div class="detail-label">Payment Plan</div>
        <div class="detail-value">{{ $debitNote->paymentPlan->installment_label ?? 'Instalment #' . $debitNote->paymentPlan->id }}</div>
      </div>
      <div class="detail-row">
        <div class="detail-label">Issued On</div>
        <div class="detail-value">{{ $debitNote->issued_on ? $debitNote->issued_on->format('d-M-Y') : '-' }}</div>
      </div>
      <div class="detail-row">
        <div class="detail-label">Amount</div>
        <div class="detail-value">{{ number_format($debitNote->amount, 2) }}</div>
      </div>
      <div class="detail-row">
        <div class="detail-label">Status</div>
        <div class="detail-value">
          <span class="badge-status badge-{{ $debitNote->status }}">
            {{ ucfirst($debitNote->status) }}
          </span>
        </div>
      </div>
      @if($debitNote->document_path)
      <div class="detail-row">
        <div class="detail-label">Document</div>
        <div class="detail-value">
          <a href="{{ ($debitNote->is_encrypted ?? false) ? route('secure.file', ['type' => 'debit-note', 'id' => $debitNote->id]) : route('storage.serve', $debitNote->document_path) }}" target="_blank" class="btn-action" style="text-decoration:none;">View Document</a>
        </div>
      </div>
      @endif
    </div>

    <div class="info-section">
      <h4>Payments</h4>
      @php
        $totalPaid = $debitNote->payments->sum('amount');
        $remaining = $debitNote->amount - $totalPaid;
      @endphp
      <div style="margin-bottom:10px; padding:8px; background:#f9f9f9; border:1px solid #ddd; border-radius:4px;">
        <strong>Total Amount:</strong> {{ number_format($debitNote->amount, 2) }} | 
        <strong>Total Paid:</strong> {{ number_format($totalPaid, 2) }} | 
        <strong>Remaining:</strong> {{ number_format($remaining, 2) }}
      </div>
      
      <div class="table-responsive">
        <table>
          <thead>
            <tr>
              <th>Payment Reference</th>
              <th>Paid On</th>
              <th>Amount</th>
              <th>Mode of Payment</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            @forelse($debitNote->payments as $payment)
              <tr>
                <td>{{ $payment->payment_reference }}</td>
                <td>{{ $payment->paid_on ? $payment->paid_on->format('d-M-y') : '-' }}</td>
                <td>{{ number_format($payment->amount, 2) }}</td>
                <td>
                  @if($payment->mode_of_payment_id)
                    @php
                      $mode = \App\Models\LookupValue::find($payment->mode_of_payment_id);
                    @endphp
                    {{ $mode->name ?? '-' }}
                  @else
                    -
                  @endif
                </td>
                <td>
                  <a href="{{ route('payments.show', $payment->id) }}" class="btn-action">View</a>
                  <a href="{{ route('payments.edit', $payment->id) }}" class="btn-action">Edit</a>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="5" style="text-align:center; padding:20px; color:#999;">No payments recorded yet</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection

