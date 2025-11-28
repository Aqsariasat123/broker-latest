@extends('layouts.app')

@section('content')
<style>
  .container-table { max-width: 100%; margin: 0 auto; padding-bottom: 20px; }
  h3 { background: #f1f1f1; padding: 10px 15px; margin-bottom: 15px; font-weight: bold; border: 1px solid #ddd; font-size: 18px; border-radius: 4px; }
  .top-bar { display:flex; align-items:center; flex-wrap:wrap; gap:8px; margin-bottom:15px; }
  .action-buttons { margin-left:auto; display:flex; gap:8px; }
  .btn { border:none; cursor:pointer; padding:8px 16px; font-size:13px; border-radius:4px; white-space:nowrap; transition:background-color .2s; text-decoration:none; color:inherit; background:#fff; border:1px solid #ccc; font-weight:500; }
  .btn:hover { opacity:0.9; transform:translateY(-1px); box-shadow:0 2px 4px rgba(0,0,0,0.1); }
  .btn-back { background:#6c757d; color:#fff; border-color:#6c757d; }
  .btn-add { background:#f3742a; color:#fff; border-color:#f3742a; }
  .info-section { background:#fff; border:1px solid #ddd; margin-bottom:15px; padding:15px; border-radius:4px; box-shadow:0 1px 3px rgba(0,0,0,0.05); }
  .info-section:last-child { margin-bottom:0; }
  .dashboard { overflow: hidden; height: calc(100vh - 80px); }
  .info-section h4 { margin:0 0 12px 0; font-size:16px; font-weight:bold; color:#333; border-bottom:2px solid #f3742a; padding-bottom:8px; }
  .info-grid { display:grid; grid-template-columns:repeat(6, 1fr); gap:12px; }
  .info-item { display:flex; flex-direction:column; padding:12px; background:#f8f9fa; border-radius:4px; border:1px solid #e9ecef; }
  .info-label { font-size:12px; color:#666; font-weight:600; margin-bottom:6px; text-transform:uppercase; letter-spacing:0.5px; }
  .info-value { font-size:14px; color:#2d2d2d; font-weight:500; line-height:1.4; }
  .badge-status { font-size:11px; padding:4px 10px; display:inline-block; border-radius:4px; color:#fff; font-weight:500; }
  .activity-list { max-height:400px; overflow-y:auto; padding:4px; }
  .activity-item { padding:12px; border-bottom:1px solid #eee; font-size:13px; background:#fff; margin-bottom:6px; border-radius:4px; border:1px solid #e9ecef; }
  .activity-item:last-child { border-bottom:none; margin-bottom:0; }
  .activity-action { font-weight:600; color:#333; font-size:14px; margin-bottom:4px; }
  .activity-description { color:#666; margin-top:6px; line-height:1.4; }
  .activity-meta { color:#999; font-size:11px; margin-top:6px; }
  .empty-state { text-align:center; padding:30px 20px; color:#999; font-size:14px; }
  @media (max-width:1200px) { 
    .info-grid { grid-template-columns:repeat(3, 1fr); gap:16px; }
  }
  @media (max-width:768px) { 
    .info-grid { grid-template-columns:1fr; gap:16px; }
    .info-section { padding:16px; }
    .info-item { padding:12px; }
  }
</style>

<div class="dashboard">
  <div class="container-table">
    <h3>User Details</h3>

    @if(session('success'))
      <div class="alert alert-success" id="successAlert" style="padding:10px 14px; margin-bottom:15px; border:1px solid #c3e6cb; background:#d4edda; color:#155724; border-radius:4px; display:flex; justify-content:space-between; align-items:center;">
        <span>{{ session('success') }}</span>
        <button type="button" class="alert-close" onclick="document.getElementById('successAlert').style.display='none'" style="background:none;border:none;font-size:18px;cursor:pointer;color:#155724;padding:0;margin-left:10px;">×</button>
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

