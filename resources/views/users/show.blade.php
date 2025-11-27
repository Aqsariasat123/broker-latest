@extends('layouts.app')

@section('content')
<style>
  .container-table { max-width: 100%; margin: 0 auto; }
  h3 { background: #f1f1f1; padding: 8px; margin-bottom: 10px; font-weight: bold; border: 1px solid #ddd; }
  .top-bar { display:flex; align-items:center; flex-wrap:wrap; gap:10px; margin-bottom:10px; }
  .action-buttons { margin-left:auto; display:flex; gap:10px; }
  .btn { border:none; cursor:pointer; padding:6px 12px; font-size:13px; border-radius:2px; white-space:nowrap; transition:background-color .2s; text-decoration:none; color:inherit; background:#fff; border:1px solid #ccc; }
  .btn-back { background:#ccc; color:#333; border-color:#ccc; }
  .btn-add { background:#df7900; color:#fff; border-color:#df7900; }
  .info-section { background:#fff; border:1px solid #ddd; margin-bottom:15px; padding:15px; }
  .info-section h4 { margin:0 0 12px 0; font-size:15px; font-weight:bold; color:#333; border-bottom:1px solid #ddd; padding-bottom:8px; }
  .info-grid { display:grid; grid-template-columns:repeat(auto-fit, minmax(250px, 1fr)); gap:12px; }
  .info-item { display:flex; flex-direction:column; }
  .info-label { font-size:12px; color:#555; font-weight:600; margin-bottom:4px; }
  .info-value { font-size:13px; color:#000; }
  .badge-status { font-size:11px; padding:4px 8px; display:inline-block; border-radius:4px; color:#fff; }
  .activity-list { max-height:400px; overflow-y:auto; }
  .activity-item { padding:8px; border-bottom:1px solid #eee; font-size:12px; }
  .activity-item:last-child { border-bottom:none; }
  .activity-action { font-weight:600; color:#333; }
  .activity-description { color:#666; margin-top:4px; }
  .activity-meta { color:#999; font-size:11px; margin-top:4px; }
  .empty-state { text-align:center; padding:20px; color:#999; font-size:13px; }
  @media (max-width:768px) { .info-grid { grid-template-columns:1fr; } }
</style>

<div class="dashboard">
  <div class="container-table">
    <h3>User Details</h3>

    @if(session('success'))
      <div class="alert alert-success" id="successAlert" style="padding:8px 12px; margin-bottom:12px; border:1px solid #c3e6cb; background:#d4edda; color:#155724;">
        {{ session('success') }}
        <button type="button" class="alert-close" onclick="document.getElementById('successAlert').style.display='none'" style="float:right;background:none;border:none;font-size:16px;cursor:pointer;">×</button>
      </div>
    @endif

    <div class="top-bar">
      <div class="action-buttons">
        @auth
        @if(auth()->user()->isAdmin())
          <a href="{{ route('users.edit', $user->id) }}" class="btn btn-add">Edit</a>
        @endif
        @endauth
        <button class="btn btn-back" onclick="window.location.href='{{ route('users.index') }}'">Back</button>
      </div>
    </div>

    <div class="info-section">
      <h4>User Information</h4>
      <div class="info-grid">
        <div class="info-item">
          <span class="info-label">Name</span>
          <span class="info-value">{{ $user->name }}</span>
        </div>
        <div class="info-item">
          <span class="info-label">Email</span>
          <span class="info-value">{{ $user->email }}</span>
        </div>
        <div class="info-item">
          <span class="info-label">Role</span>
          <span class="info-value">
            @php
              $roleName = $user->roleModel ? $user->roleModel->name : ($user->role ?? 'N/A');
              $roleSlug = $user->roleModel ? $user->roleModel->slug : ($user->role ?? '');
              $roleColor = ($roleSlug == 'admin') ? '#dc3545' : '#007bff';
            @endphp
            <span class="badge-status" style="background: {{ $roleColor }};">
              {{ $roleName }}
            </span>
          </span>
        </div>
        <div class="info-item">
          <span class="info-label">Status</span>
          <span class="info-value">
            <span class="badge-status" style="background: {{ $user->is_active ? '#28a745' : '#6c757d' }};">
              {{ $user->is_active ? 'Active' : 'Inactive' }}
            </span>
          </span>
        </div>
        <div class="info-item">
          <span class="info-label">Last Login</span>
          <span class="info-value">
            @if($user->last_login_at)
              {{ $user->last_login_at->format('d-M-y H:i') }}
              <br><small style="color: #999; font-size: 11px;">IP: {{ $user->last_login_ip }}</small>
            @else
              <span style="color: #999;">Never</span>
            @endif
          </span>
        </div>
        <div class="info-item">
          <span class="info-label">Created</span>
          <span class="info-value">{{ $user->created_at->format('d-M-y H:i') }}</span>
        </div>
      </div>
    </div>

    <div class="info-section">
      <h4>Recent Activity</h4>
      <div class="activity-list">
        @forelse($recentLogs as $log)
          <div class="activity-item">
            <div class="activity-action">{{ ucfirst($log->action) }}</div>
            <div class="activity-description">{{ $log->description }}</div>
            <div class="activity-meta">
              {{ $log->created_at->format('d-M-y H:i') }}
              @if($log->ip_address)
                • {{ $log->ip_address }}
              @endif
            </div>
          </div>
        @empty
          <div class="empty-state">No activity recorded</div>
        @endforelse
      </div>
    </div>
  </div>
</div>
@endsection

