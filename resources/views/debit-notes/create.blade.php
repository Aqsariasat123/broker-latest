@extends('layouts.app')

@section('content')
<style>
  .container-table { max-width: 100%; margin: 0 auto; }
  h3 { background: #f1f1f1; padding: 8px; margin-bottom: 10px; font-weight: bold; border: 1px solid #ddd; }
  .top-bar { display:flex; align-items:center; flex-wrap:wrap; gap:10px; margin-bottom:10px; }
  .left-group { display:flex; align-items:center; gap:10px; flex:1 1 auto; min-width:220px; }
  .btn { border:none; cursor:pointer; padding:6px 12px; font-size:13px; border-radius:2px; white-space:nowrap; transition:background-color .2s; text-decoration:none; color:inherit; background:#fff; border:1px solid #ccc; }
  .btn-back { background:#ccc; color:#333; border-color:#ccc; }
  .form-container { background:#fff; border:1px solid #ddd; padding:20px; max-width:800px; margin:0 auto; }
  .form-row { display:flex; gap:10px; margin-bottom:12px; flex-wrap:wrap; align-items:flex-start; }
  .form-group { flex:0 0 calc((100% - 20px) / 2); }
  .form-group.full-width { flex:0 0 100%; }
  .form-group label { display:block; margin-bottom:4px; font-weight:600; font-size:13px; }
  .form-control, select { width:100%; padding:6px 8px; border:1px solid #ccc; border-radius:2px; font-size:13px; }
  .form-control:focus, select:focus { outline:none; border-color:#007bff; }
  .error-message { color:#dc3545; font-size:12px; margin-top:4px; }
  .btn-save { background:#007bff; color:#fff; border:none; padding:6px 12px; border-radius:2px; cursor:pointer; }
  .btn-cancel { background:#6c757d; color:#fff; border:none; padding:6px 12px; border-radius:2px; cursor:pointer; }
  @media (max-width:768px) { .form-group { flex:0 0 100%; } }
</style>

<div class="dashboard">
  <div class="container-table">
    <h3>Create Debit Note</h3>

    @if(session('success'))
      <div class="alert alert-success" id="successAlert" style="padding:8px 12px; margin-bottom:12px; border:1px solid #c3e6cb; background:#d4edda; color:#155724;">
        {{ session('success') }}
        <button type="button" class="alert-close" onclick="document.getElementById('successAlert').style.display='none'" style="float:right;background:none;border:none;font-size:16px;cursor:pointer;">×</button>
      </div>
    @endif

    <div class="top-bar">
      <div class="left-group">
        <a href="{{ route('debit-notes.index') }}" class="btn btn-back">Back</a>
      </div>
    </div>

    <div class="form-container">
      <form action="{{ route('debit-notes.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-row">
          <div class="form-group full-width">
            <label for="payment_plan_id">Payment Plan *</label>
            <select id="payment_plan_id" name="payment_plan_id" class="form-control" required>
              <option value="">Select Payment Plan</option>
              @foreach($paymentPlans as $plan)
                <option value="{{ $plan->id }}" {{ old('payment_plan_id', request('payment_plan_id')) == $plan->id ? 'selected' : '' }}>
                  {{ $plan->schedule->policy->policy_no ?? 'N/A' }} - 
                  {{ $plan->schedule->policy->client->client_name ?? 'N/A' }} - 
                  {{ $plan->installment_label ?? 'Instalment #' . $plan->id }}
                </option>
              @endforeach
            </select>
            @error('payment_plan_id')<span class="error-message">{{ $message }}</span>@enderror
            @if($paymentPlans->isEmpty())
              <span class="error-message" style="color:#ff9800;">No payment plans found. <a href="{{ route('payment-plans.create') }}">Create a payment plan first</a></span>
            @endif
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label for="debit_note_no">Debit Note Number *</label>
            <input type="text" id="debit_note_no" name="debit_note_no" class="form-control" required value="{{ old('debit_note_no') }}">
            @error('debit_note_no')<span class="error-message">{{ $message }}</span>@enderror
          </div>

          <div class="form-group">
            <label for="issued_on">Issued On *</label>
            <input type="date" id="issued_on" name="issued_on" class="form-control" required value="{{ old('issued_on', date('Y-m-d')) }}">
            @error('issued_on')<span class="error-message">{{ $message }}</span>@enderror
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label for="amount">Amount *</label>
            <input type="number" id="amount" name="amount" step="0.01" min="0" class="form-control" required value="{{ old('amount') }}">
            @error('amount')<span class="error-message">{{ $message }}</span>@enderror
          </div>

          <div class="form-group">
            <label for="status">Status *</label>
            <select id="status" name="status" class="form-control" required>
              <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
              <option value="issued" {{ old('status') == 'issued' ? 'selected' : '' }}>Issued</option>
              <option value="paid" {{ old('status') == 'paid' ? 'selected' : '' }}>Paid</option>
              <option value="overdue" {{ old('status') == 'overdue' ? 'selected' : '' }}>Overdue</option>
              <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
            @error('status')<span class="error-message">{{ $message }}</span>@enderror
          </div>
        </div>

        <div class="form-row">
          <div class="form-group full-width">
            <label for="document">Document (PDF, Image, Word, Excel)</label>
            <input type="file" id="document" name="document" class="form-control" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx,.xls,.xlsx">
            <small style="color:#666; font-size:11px;">Max file size: 10MB. Allowed formats: PDF, JPG, PNG, DOC, DOCX, XLS, XLSX</small>
            @error('document')<span class="error-message">{{ $message }}</span>@enderror
          </div>
        </div>

        <div style="display:flex; justify-content:flex-end; gap:10px; margin-top:20px; padding-top:15px; border-top:1px solid #ddd;">
          <a href="{{ route('debit-notes.index') }}" class="btn-cancel" style="text-decoration:none; display:inline-block;">Cancel</a>
          <button type="submit" class="btn-save">Create</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
