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
  .btn-action { padding:2px 6px; font-size:11px; margin:1px; border:1px solid #ddd; background:#fff; cursor:pointer; border-radius:2px; display:inline-block; text-decoration:none; color:#333; }
</style>

<div class="dashboard">
  <div class="container-table">
    <h3>Payment Details</h3>
    
    <div class="top-bar">
      <div class="left-group">
        <a href="{{ route('payments.index') }}" class="btn btn-back">Back to List</a>
        <a href="{{ route('payments.edit', $payment->id) }}" class="btn">Edit</a>
      </div>
    </div>

    <div class="info-section">
      <h4>Payment Information</h4>
      <div class="detail-row">
        <div class="detail-label">Payment Reference</div>
        <div class="detail-value">{{ $payment->payment_reference }}</div>
      </div>
      <div class="detail-row">
        <div class="detail-label">Debit Note</div>
        <div class="detail-value">{{ $payment->debitNote->debit_note_no ?? '-' }}</div>
      </div>
      <div class="detail-row">
        <div class="detail-label">Policy</div>
        <div class="detail-value">{{ $payment->debitNote->paymentPlan->schedule->policy->policy_no ?? '-' }}</div>
      </div>
      <div class="detail-row">
        <div class="detail-label">Client</div>
        <div class="detail-value">{{ $payment->debitNote->paymentPlan->schedule->policy->client->client_name ?? '-' }}</div>
      </div>
      <div class="detail-row">
        <div class="detail-label">Paid On</div>
        <div class="detail-value">{{ $payment->paid_on ? $payment->paid_on->format('d-M-Y') : '-' }}</div>
      </div>
      <div class="detail-row">
        <div class="detail-label">Amount</div>
        <div class="detail-value">{{ number_format($payment->amount, 2) }}</div>
      </div>
      <div class="detail-row">
        <div class="detail-label">Mode of Payment</div>
        <div class="detail-value">
          @if($payment->mode_of_payment_id)
            @php
              $mode = \App\Models\LookupValue::find($payment->mode_of_payment_id);
            @endphp
            {{ $mode->name ?? '-' }}
          @else
            -
          @endif
        </div>
      </div>
      @if($payment->receipt_path)
      <div class="detail-row">
        <div class="detail-label">Receipt</div>
        <div class="detail-value">
          <a href="{{ ($payment->is_encrypted ?? false) ? route('secure.file', ['type' => 'payment', 'id' => $payment->id]) : route('storage.serve', $payment->receipt_path) }}" target="_blank" class="btn-action" style="text-decoration:none;">View Receipt</a>
        </div>
      </div>
      @endif
      @if($payment->notes)
      <div class="detail-row">
        <div class="detail-label">Notes</div>
        <div class="detail-value">{{ $payment->notes }}</div>
      </div>
      @endif
    </div>
  </div>
</div>
@endsection

