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
  .instalment-section { background:#f9f9f9; border:1px solid #ddd; padding:20px; margin-top:30px; border-radius:4px; }
  .instalment-section h4 { margin-top:0; margin-bottom:15px; font-size:14px; font-weight:600; color:#333; }
  @media (max-width:768px) { .form-group { flex:0 0 100%; } }
</style>

<div class="dashboard">
  <div class="container-table">
    <h3>Create Payment Plan</h3>

    @if(session('success'))
      <div class="alert alert-success" id="successAlert" style="padding:8px 12px; margin-bottom:12px; border:1px solid #c3e6cb; background:#d4edda; color:#155724;">
        {{ session('success') }}
        <button type="button" class="alert-close" onclick="document.getElementById('successAlert').style.display='none'" style="float:right;background:none;border:none;font-size:16px;cursor:pointer;">×</button>
      </div>
    @endif

    @if(session('error'))
      <div class="alert alert-danger" id="errorAlert" style="padding:8px 12px; margin-bottom:12px; border:1px solid #f5c6cb; background:#f8d7da; color:#721c24;">
        {{ session('error') }}
        <button type="button" class="alert-close" onclick="document.getElementById('errorAlert').style.display='none'" style="float:right;background:none;border:none;font-size:16px;cursor:pointer;">×</button>
      </div>
    @endif

    <div class="top-bar">
      <div class="left-group">
        <a href="{{ route('payment-plans.index') }}" class="btn btn-back">Back</a>
      </div>
    </div>

    <div class="form-container">
      <form action="{{ route('payment-plans.store') }}" method="POST">
        @csrf

        <div class="form-row">
          <div class="form-group">
            <label for="schedule_id">Schedule *</label>
            <select id="schedule_id" name="schedule_id" class="form-control" required>
              <option value="">Select Schedule</option>
              @foreach($schedules as $schedule)
                <option value="{{ $schedule->id }}" {{ old('schedule_id') == $schedule->id ? 'selected' : '' }}>
                  {{ $schedule->policy->policy_no ?? 'N/A' }} - 
                  {{ $schedule->policy->client->client_name ?? 'N/A' }} - 
                  Schedule #{{ $schedule->schedule_no ?? $schedule->id }}
                </option>
              @endforeach
            </select>
            @error('schedule_id')<span class="error-message">{{ $message }}</span>@enderror
          </div>

          <div class="form-group">
            <label for="installment_label">Instalment Label</label>
            <input type="text" id="installment_label" name="installment_label" class="form-control" value="{{ old('installment_label') }}" placeholder="e.g., Instalment 1 of 4">
            @error('installment_label')<span class="error-message">{{ $message }}</span>@enderror
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label for="due_date">Due Date *</label>
            <input type="date" id="due_date" name="due_date" class="form-control" required value="{{ old('due_date') }}">
            @error('due_date')<span class="error-message">{{ $message }}</span>@enderror
          </div>

          <div class="form-group">
            <label for="amount">Amount *</label>
            <input type="number" id="amount" name="amount" step="0.01" min="0" class="form-control" required value="{{ old('amount') }}">
            @error('amount')<span class="error-message">{{ $message }}</span>@enderror
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label for="frequency">Frequency</label>
            <select id="frequency" name="frequency" class="form-control">
              <option value="">Select Frequency</option>
              @foreach($frequencies as $freq)
                <option value="{{ $freq->name }}" {{ old('frequency') == $freq->name ? 'selected' : '' }}>{{ $freq->name }}</option>
              @endforeach
              <option value="Monthly" {{ old('frequency') == 'Monthly' ? 'selected' : '' }}>Monthly</option>
              <option value="Quarterly" {{ old('frequency') == 'Quarterly' ? 'selected' : '' }}>Quarterly</option>
              <option value="Annually" {{ old('frequency') == 'Annually' ? 'selected' : '' }}>Annually</option>
            </select>
            @error('frequency')<span class="error-message">{{ $message }}</span>@enderror
          </div>

          <div class="form-group">
            <label for="status">Status *</label>
            <select id="status" name="status" class="form-control" required>
              <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
              <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
              <option value="paid" {{ old('status') == 'paid' ? 'selected' : '' }}>Paid</option>
              <option value="overdue" {{ old('status') == 'overdue' ? 'selected' : '' }}>Overdue</option>
              <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
            @error('status')<span class="error-message">{{ $message }}</span>@enderror
          </div>
        </div>

        <div style="display:flex; justify-content:flex-end; gap:10px; margin-top:20px; padding-top:15px; border-top:1px solid #ddd;">
          <a href="{{ route('payment-plans.index') }}" class="btn-cancel" style="text-decoration:none; display:inline-block;">Cancel</a>
          <button type="submit" class="btn-save">Save</button>
        </div>
      </form>
    </div>

    <div class="instalment-section">
      <h4>Or Create Multiple Instalments</h4>
      <form action="{{ route('payment-plans.create-instalments') }}" method="POST">
        @csrf

        <div class="form-row">
          <div class="form-group">
            <label for="instalment_schedule_id">Schedule *</label>
            <select id="instalment_schedule_id" name="schedule_id" class="form-control" required>
              <option value="">Select Schedule</option>
              @foreach($schedules as $schedule)
                <option value="{{ $schedule->id }}" {{ request('schedule_id') == $schedule->id ? 'selected' : '' }}>
                  {{ $schedule->policy->policy_no ?? 'N/A' }} - 
                  {{ $schedule->policy->client->client_name ?? 'N/A' }}
                </option>
              @endforeach
            </select>
            @error('schedule_id')<span class="error-message">{{ $message }}</span>@enderror
            @if($schedules->isEmpty())
              <span class="error-message" style="color:#ff9800;">No schedules found. <a href="{{ route('schedules.create') }}">Create a schedule first</a></span>
            @endif
          </div>

          <div class="form-group">
            <label for="total_amount">Total Amount *</label>
            <input type="number" id="total_amount" name="total_amount" step="0.01" min="0" class="form-control" required placeholder="Total amount to split">
            @error('total_amount')<span class="error-message">{{ $message }}</span>@enderror
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label for="number_of_instalments">Number of Instalments *</label>
            <input type="number" id="number_of_instalments" name="number_of_instalments" min="1" max="12" class="form-control" required placeholder="1-12">
            @error('number_of_instalments')<span class="error-message">{{ $message }}</span>@enderror
          </div>

          <div class="form-group">
            <label for="first_due_date">First Due Date *</label>
            <input type="date" id="first_due_date" name="first_due_date" class="form-control" required>
            @error('first_due_date')<span class="error-message">{{ $message }}</span>@enderror
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label for="instalment_frequency">Frequency *</label>
            <select id="instalment_frequency" name="frequency" class="form-control" required>
              <option value="">Select Frequency</option>
              @foreach($frequencies as $freq)
                <option value="{{ $freq->name }}">{{ $freq->name }}</option>
              @endforeach
              <option value="Monthly">Monthly</option>
              <option value="Quarterly">Quarterly</option>
              <option value="Annually">Annually</option>
            </select>
            @error('frequency')<span class="error-message">{{ $message }}</span>@enderror
          </div>
        </div>

        <div style="display:flex; justify-content:flex-end; gap:10px; margin-top:20px; padding-top:15px; border-top:1px solid #ddd;">
          <button type="submit" class="btn-save">Create Instalments</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
