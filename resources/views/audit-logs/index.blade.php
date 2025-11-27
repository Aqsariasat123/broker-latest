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
  .btn-export, .btn-column { background:#fff; color:#000; border:1px solid #ccc; }
  .btn-back { background:#ccc; color:#333; border-color:#ccc; }
  .table-responsive { width: 100%; overflow-x: auto; border: 1px solid #ddd; max-height: 520px; overflow-y: auto; background: #fff; }
  .footer { display:flex; justify-content:center; align-items:center; padding:5px 0; gap:10px; border-top:1px solid #ccc; flex-wrap:wrap; margin-top:15px; }
  .paginator { display:flex; align-items:center; gap:5px; font-size:12px; color:#555; white-space:nowrap; justify-content:center; }
  .btn-page { color:#2d2d2d; font-size:25px; width:22px; height:50px; padding:5px; cursor:pointer; text-decoration:none; display:inline-flex; align-items:center; justify-content:center; }
  table { width:100%; border-collapse:collapse; font-size:13px; min-width:1200px; }
  thead tr { background-color: black; color: white; height:35px; font-weight: normal; }
  thead th { padding:6px 5px; text-align:left; border-right:1px solid #444; white-space:nowrap; font-weight: normal; }
  thead th:last-child { border-right:none; }
  tbody tr { background-color:#fefefe; border-bottom:1px solid #ddd; min-height:28px; }
  tbody tr:nth-child(even) { background-color:#f8f8f8; }
  tbody td { padding:5px 5px; border-right:1px solid #ddd; white-space:nowrap; vertical-align:middle; font-size:12px; }
  tbody td:last-child { border-right:none; }
  .btn-action { padding:2px 6px; font-size:11px; margin:1px; border:1px solid #ddd; background:#fff; cursor:pointer; border-radius:2px; display:inline-block; }
  .btn-action:hover { background:#f0f0f0; }
  .badge-action { font-size:11px; padding:4px 8px; display:inline-block; border-radius:4px; color:#fff; }
  .badge-create { background:#28a745; }
  .badge-update { background:#ffc107; color:#000; }
  .badge-delete { background:#dc3545; }
  .badge-login { background:#17a2b8; }
  .badge-logout { background:#6c757d; }
  input[type="text"], select { padding:6px 8px; border:1px solid #ccc; border-radius:2px; font-size:13px; }
  .filters { background:#f9f9f9; padding:12px; border:1px solid #ddd; margin-bottom:12px; border-radius:4px; }
  .filter-row { display:flex; gap:10px; flex-wrap:wrap; margin-bottom:8px; }
  .filter-group { display:flex; flex-direction:column; gap:4px; min-width:150px; }
  .filter-group label { font-size:12px; color:#555; font-weight:600; }
  .filter-actions { display:flex; gap:8px; margin-top:8px; }
  @media (max-width:768px) { .table-responsive { max-height:500px; } }
</style>

<div class="dashboard">
  <div class="container-table">
    <h3>Audit Logs</h3>
    
    <div class="filters">
      <form method="GET" action="{{ route('audit-logs.index') }}">
        <div class="filter-row">
          <div class="filter-group">
            <label>Search</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search description...">
          </div>
          <div class="filter-group">
            <label>User</label>
            <select name="user_id">
              <option value="">All Users</option>
              @foreach($users as $user)
                <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
              @endforeach
            </select>
          </div>
          <div class="filter-group">
            <label>Action</label>
            <select name="action">
              <option value="">All Actions</option>
              @foreach($actions as $action)
                <option value="{{ $action }}" {{ request('action') == $action ? 'selected' : '' }}>{{ ucfirst($action) }}</option>
              @endforeach
            </select>
          </div>
          <div class="filter-group">
            <label>Model Type</label>
            <select name="model_type">
              <option value="">All Models</option>
              @foreach($modelTypes as $type)
                <option value="{{ $type }}" {{ request('model_type') == $type ? 'selected' : '' }}>{{ class_basename($type) }}</option>
              @endforeach
            </select>
          </div>
          <div class="filter-group">
            <label>Date From</label>
            <input type="date" name="date_from" value="{{ request('date_from') }}">
          </div>
          <div class="filter-group">
            <label>Date To</label>
            <input type="date" name="date_to" value="{{ request('date_to') }}">
          </div>
        </div>
        <div class="filter-actions">
          <button type="submit" class="btn" style="background:#007bff; color:#fff; border-color:#007bff;">Filter</button>
          <a href="{{ route('audit-logs.index') }}" class="btn" style="background:#6c757d; color:#fff; border-color:#6c757d;">Clear</a>
        </div>
      </form>
    </div>

    <div class="top-bar">
      <div class="left-group">
        <div class="records-found">Records Found - {{ $logs->total() }}</div>
      </div>
      <div class="action-buttons">
        <button class="btn btn-back" onclick="window.history.back()">Back</button>
      </div>
    </div>

    <div class="table-responsive">
      <table>
        <thead>
          <tr>
            <th>Date & Time</th>
            <th>User</th>
            <th>Action</th>
            <th>Description</th>
            <th>Model</th>
            <th>IP Address</th>
            <th>Method</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($logs as $log)
            <tr>
              <td>{{ $log->created_at->format('d-M-y H:i:s') }}</td>
              <td>{{ $log->user ? $log->user->name : 'System' }}</td>
              <td>
                <span class="badge-action badge-{{ $log->action }}">
                  {{ ucfirst($log->action) }}
                </span>
              </td>
              <td>{{ $log->description }}</td>
              <td>
                @if($log->model_type)
                  {{ class_basename($log->model_type) }} #{{ $log->model_id }}
                @else
                  -
                @endif
              </td>
              <td>{{ $log->ip_address ?? '-' }}</td>
              <td>{{ $log->method ?? '-' }}</td>
              <td>
                <a href="{{ route('audit-logs.show', $log->id) }}" class="btn-action" title="View Details">View</a>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="8" style="text-align:center; padding:20px; color:#999;">No audit logs found</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div class="footer">
      <div class="paginator">
        {{ $logs->links() }}
      </div>
    </div>
  </div>
</div>
@endsection

