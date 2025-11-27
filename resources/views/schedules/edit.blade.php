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
    <h3>Edit Schedule</h3>

    <div class="top-bar">
      <div class="left-group">
        <a href="{{ route('schedules.index') }}" class="btn btn-back">Back</a>
      </div>
    </div>

    <div class="form-container">
      <form action="{{ route('schedules.update', $schedule->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-row">
          <div class="form-group full-width">
            <label for="policy_id">Policy *</label>
            <select id="policy_id" name="policy_id" class="form-control" required>
              <option value="">Select Policy</option>
              @foreach($policies as $policy)
                <option value="{{ $policy->id }}" {{ old('policy_id', $schedule->policy_id) == $policy->id ? 'selected' : '' }}>
                  {{ $policy->policy_no }} - {{ $policy->client->client_name ?? 'N/A' }}
                </option>
              @endforeach
            </select>
            @error('policy_id')<span class="error-message">{{ $message }}</span>@enderror
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label for="schedule_no">Schedule Number *</label>
            <input type="text" id="schedule_no" name="schedule_no" class="form-control" required value="{{ old('schedule_no', $schedule->schedule_no) }}">
            @error('schedule_no')<span class="error-message">{{ $message }}</span>@enderror
          </div>

          <div class="form-group">
            <label for="status">Status *</label>
            <select id="status" name="status" class="form-control" required>
              <option value="draft" {{ old('status', $schedule->status) == 'draft' ? 'selected' : '' }}>Draft</option>
              <option value="active" {{ old('status', $schedule->status) == 'active' ? 'selected' : '' }}>Active</option>
              <option value="expired" {{ old('status', $schedule->status) == 'expired' ? 'selected' : '' }}>Expired</option>
              <option value="cancelled" {{ old('status', $schedule->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
            @error('status')<span class="error-message">{{ $message }}</span>@enderror
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label for="issued_on">Issued On</label>
            <input type="date" id="issued_on" name="issued_on" class="form-control" value="{{ old('issued_on', $schedule->issued_on ? $schedule->issued_on->format('Y-m-d') : '') }}">
            @error('issued_on')<span class="error-message">{{ $message }}</span>@enderror
          </div>

          <div class="form-group">
            <label for="effective_from">Effective From</label>
            <input type="date" id="effective_from" name="effective_from" class="form-control" value="{{ old('effective_from', $schedule->effective_from ? $schedule->effective_from->format('Y-m-d') : '') }}">
            @error('effective_from')<span class="error-message">{{ $message }}</span>@enderror
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label for="effective_to">Effective To</label>
            <input type="date" id="effective_to" name="effective_to" class="form-control" value="{{ old('effective_to', $schedule->effective_to ? $schedule->effective_to->format('Y-m-d') : '') }}">
            @error('effective_to')<span class="error-message">{{ $message }}</span>@enderror
          </div>
        </div>

        <div class="form-row">
          <div class="form-group full-width">
            <label for="notes">Notes</label>
            <textarea id="notes" name="notes" class="form-control" style="min-height:80px;">{{ old('notes', $schedule->notes) }}</textarea>
            @error('notes')<span class="error-message">{{ $message }}</span>@enderror
          </div>
        </div>

        <div style="display:flex; justify-content:flex-end; gap:10px; margin-top:20px; padding-top:15px; border-top:1px solid #ddd;">
          <a href="{{ route('schedules.index') }}" class="btn-cancel" style="text-decoration:none; display:inline-block;">Cancel</a>
          <button type="submit" class="btn-save">Update</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

