@extends('layouts.app')

@section('content')
<style>
  .container-table { max-width: 100%; margin: 0 auto; }
  h3 { background: #f1f1f1; padding: 8px; margin-bottom: 10px; font-weight: bold; border: 1px solid #ddd; }
  .detail-section { background:#fff; border:1px solid #ddd; padding:15px; margin-bottom:15px; }
  .detail-section h4 { margin-top:0; margin-bottom:12px; color:#333; border-bottom:2px solid #007bff; padding-bottom:8px; }
  .detail-row { display:grid; grid-template-columns: 200px 1fr; gap:10px; padding:8px 0; border-bottom:1px solid #eee; }
  .detail-row:last-child { border-bottom:none; }
  .detail-label { font-weight:600; color:#555; }
  .detail-value { color:#333; }
  .badge-action { font-size:12px; padding:4px 8px; display:inline-block; border-radius:4px; color:#fff; }
  .badge-create { background:#28a745; }
  .badge-update { background:#ffc107; color:#000; }
  .badge-delete { background:#dc3545; }
  .badge-login { background:#17a2b8; }
  .badge-logout { background:#6c757d; }
  .json-view { background:#f5f5f5; padding:10px; border:1px solid #ddd; border-radius:4px; font-family:monospace; font-size:12px; max-height:300px; overflow-y:auto; }
  .btn { border:none; cursor:pointer; padding:6px 12px; font-size:13px; border-radius:2px; white-space:nowrap; transition:background-color .2s; text-decoration:none; color:inherit; background:#fff; border:1px solid #ccc; display:inline-block; }
  .btn-back { background:#ccc; color:#333; border-color:#ccc; }
  .action-buttons { margin-bottom:15px; }
</style>

<div class="dashboard">
  <div class="container-table">
    <h3>Audit Log Details</h3>
    
    <div class="action-buttons">
      <a href="{{ route('audit-logs.index') }}" class="btn btn-back">Back to List</a>
    </div>

    <div class="detail-section">
      <h4>Basic Information</h4>
      <div class="detail-row">
        <div class="detail-label">Date & Time</div>
        <div class="detail-value">{{ $auditLog->created_at->format('d-M-Y H:i:s') }}</div>
      </div>
      <div class="detail-row">
        <div class="detail-label">User</div>
        <div class="detail-value">{{ $auditLog->user ? $auditLog->user->name : 'System' }}</div>
      </div>
      <div class="detail-row">
        <div class="detail-label">Action</div>
        <div class="detail-value">
          <span class="badge-action badge-{{ $auditLog->action }}">
            {{ ucfirst($auditLog->action) }}
          </span>
        </div>
      </div>
      <div class="detail-row">
        <div class="detail-label">Description</div>
        <div class="detail-value">{{ $auditLog->description }}</div>
      </div>
      <div class="detail-row">
        <div class="detail-label">Model</div>
        <div class="detail-value">
          @if($auditLog->model_type)
            {{ class_basename($auditLog->model_type) }} #{{ $auditLog->model_id }}
          @else
            -
          @endif
        </div>
      </div>
    </div>

    <div class="detail-section">
      <h4>Request Information</h4>
      <div class="detail-row">
        <div class="detail-label">IP Address</div>
        <div class="detail-value">{{ $auditLog->ip_address ?? '-' }}</div>
      </div>
      <div class="detail-row">
        <div class="detail-label">User Agent</div>
        <div class="detail-value">{{ $auditLog->user_agent ?? '-' }}</div>
      </div>
      <div class="detail-row">
        <div class="detail-label">HTTP Method</div>
        <div class="detail-value">{{ $auditLog->method ?? '-' }}</div>
      </div>
      <div class="detail-row">
        <div class="detail-label">URL</div>
        <div class="detail-value">{{ $auditLog->url ?? '-' }}</div>
      </div>
    </div>

    @if($auditLog->old_values || $auditLog->new_values)
    <div class="detail-section">
      <h4>Data Changes</h4>
      @if($auditLog->old_values)
      <div class="detail-row">
        <div class="detail-label">Old Values</div>
        <div class="detail-value">
          <div class="json-view">{{ json_encode($auditLog->old_values, JSON_PRETTY_PRINT) }}</div>
        </div>
      </div>
      @endif
      @if($auditLog->new_values)
      <div class="detail-row">
        <div class="detail-label">New Values</div>
        <div class="detail-value">
          <div class="json-view">{{ json_encode($auditLog->new_values, JSON_PRETTY_PRINT) }}</div>
        </div>
      </div>
      @endif
    </div>
    @endif
  </div>
</div>
@endsection

