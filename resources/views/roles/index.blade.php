@extends('layouts.app')

@section('content')
<style>
  .dashboard { overflow: visible !important; height: auto !important; }
  .container-table { max-width: 100%; margin: 0 auto; }
  h3 { background: #f1f1f1; padding: 8px; margin-bottom: 10px; font-weight: bold; border: 1px solid #ddd; }
  .top-bar { display:flex; align-items:center; flex-wrap:wrap; gap:10px; margin-bottom:10px; }
  .left-group { display:flex; align-items:center; gap:10px; flex:1 1 auto; min-width:220px; }
  .left-buttons { display:flex; gap:10px; align-items:center; }
  .records-found { font-size:14px; color:#555; min-width:150px; }
  .action-buttons { margin-left:auto; display:flex; gap:10px; align-items:center; }
  .btn { border:none; cursor:pointer; padding:6px 12px; font-size:13px; border-radius:2px; white-space:nowrap; transition:background-color .2s; text-decoration:none; color:inherit; background:#fff; border:1px solid #ccc; }
  .btn-add { background:#df7900; color:#fff; border-color:#df7900; }
  .btn-back { background:#ccc; color:#333; border-color:#ccc; }
  .table-responsive { width: 100%; overflow-x: auto; border: 1px solid #ddd; max-height: 400px; overflow-y: auto; background: #fff; margin-bottom:20px; }
  table { width:100%; border-collapse:collapse; font-size:13px; min-width:800px; }
  thead tr { background-color: black; color: white; height:35px; font-weight: normal; }
  thead th { padding:6px 5px; text-align:left; border-right:1px solid #444; white-space:nowrap; font-weight: normal; }
  thead th:last-child { border-right:none; }
  tbody tr { background-color:#fefefe; border-bottom:1px solid #ddd; min-height:28px; }
  tbody tr:nth-child(even) { background-color:#f8f8f8; }
  tbody td { padding:5px 5px; border-right:1px solid #ddd; white-space:nowrap; vertical-align:middle; font-size:12px; }
  tbody td:last-child { border-right:none; }
  .icon-expand { cursor:pointer; color:black; text-align:center; width:20px; }
  .btn-action { padding:2px 6px; font-size:11px; margin:1px; border:1px solid #ddd; background:#fff; cursor:pointer; border-radius:2px; display:inline-block; }
  .btn-action:hover { background:#f0f0f0; }
  .badge-role { font-size:11px; padding:4px 8px; display:inline-block; border-radius:4px; color:#fff; }
  .badge-system { background:#6c757d; }
  .action-buttons-cell { white-space:nowrap; }
  
  /* Tab Styles */
  .tabs-container { background:#fff; border:1px solid #ddd; margin-top:20px; }
  .tabs-header { display:flex; border-bottom:2px solid #ddd; background:#f8f8f8; overflow-x:auto; }
  .tab-button { padding:12px 20px; background:transparent; border:none; border-bottom:3px solid transparent; cursor:pointer; font-size:14px; color:#666; white-space:nowrap; transition:all 0.2s; position:relative; }
  .tab-button:hover { background:#f0f0f0; color:#333; }
  .tab-button.active { background:#fff; color:#df7900; border-bottom-color:#df7900; font-weight:600; }
  .tab-content { display:none; padding:20px; }
  .tab-content.active { display:block; }
  
  .role-container { background:#fff; }
  .role-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:15px; padding-bottom:10px; border-bottom:2px solid #ddd; }
  .role-title { font-size:18px; font-weight:bold; color:#333; }
  .btn-save { background:#007bff; color:#fff; border:none; padding:6px 12px; border-radius:2px; cursor:pointer; }
  .btn-save:hover { background:#0056b3; }
  .permissions-grid { display:grid; grid-template-columns:repeat(auto-fill, minmax(300px, 1fr)); gap:10px; margin-bottom:15px; max-height:calc(100vh - 350px); overflow-y:auto; padding:5px; }
  .module-section { border:1px solid #e0e0e0; padding:12px; border-radius:4px; background:#f9f9f9; }
  .module-title { font-weight:600; font-size:13px; color:#555; margin-bottom:8px; padding-bottom:6px; border-bottom:1px solid #ddd; }
  .permission-item { display:flex; align-items:center; gap:8px; padding:4px 0; }
  .permission-item input[type="checkbox"] { width:auto; cursor:pointer; }
  .permission-item label { font-size:12px; color:#333; cursor:pointer; margin:0; font-weight:normal; }
  .permission-slug { font-size:11px; color:#666; margin-left:4px; }
  .select-all-module { font-size:11px; color:#007bff; cursor:pointer; text-decoration:underline; margin-bottom:6px; display:inline-block; }
  .select-all-module:hover { color:#0056b3; }
  @media (max-width:768px) { 
    .permissions-grid { grid-template-columns:1fr; }
    .tabs-header { flex-wrap:nowrap; }
    .tab-button { padding:10px 15px; font-size:13px; }
  }
</style>

<div class="dashboard">
  <div class="container-table">
    <h3>Roles Management</h3>

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
        <div class="records-found">Total Roles: {{ $roles->count() }}</div>
      </div>
      <div class="action-buttons">
        <a href="{{ route('roles.create') }}" class="btn btn-add">Add Role</a>
        <a href="{{ route('permissions.index') }}" class="btn" style="background:#df7900; color:#fff; border-color:#df7900;">Manage Permissions</a>
        <button class="btn btn-back" onclick="window.history.back()">Back</button>
      </div>
    </div>

    <div class="table-responsive">
      <table>
        <thead>
          <tr>
            <th>Name</th>
            <th>Slug</th>
            <th>Description</th>
            <th>Type</th>
            <th>Users</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($roles as $role)
            <tr>
              <td>{{ $role->name }}</td>
              <td><code style="font-size:11px; background:#f5f5f5; padding:2px 6px; border-radius:2px;">{{ $role->slug }}</code></td>
              <td>{{ $role->description ?? '—' }}</td>
              <td>
                @if($role->is_system)
                  <span class="badge-role badge-system">System</span>
                @else
                  <span class="badge-role" style="background:#28a745;">Custom</span>
                @endif
              </td>
              <td>{{ $role->users()->count() }}</td>
              <td class="action-buttons-cell">
                <span class="icon-expand" onclick="switchTab({{ $role->id }})" title="Manage Permissions">⚙</span>
                <span class="icon-expand" onclick="window.location.href='{{ route('roles.edit', $role->id) }}'" title="Edit Role">⤢</span>
                @if(!$role->is_system && $role->users()->count() == 0)
                  <form action="{{ route('roles.destroy', $role->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this role?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-action" style="background: #dc3545; color: white; border-color: #dc3545;" title="Delete Role">🗑</button>
                  </form>
                @endif
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6" style="padding: 20px; text-align: center; color: #999;">No roles found</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    @if($roles->count() > 0)
      <div class="tabs-container">
        <div class="tabs-header">
          @foreach($roles as $index => $role)
            <button class="tab-button {{ $index === 0 ? 'active' : '' }}" 
                    onclick="switchTab({{ $role->id }})" 
                    id="tab-btn-{{ $role->id }}">
              {{ $role->name }}
              <span class="badge-role {{ $role->is_system ? 'badge-system' : '' }}" 
                    style="margin-left:8px; background:{{ $role->is_system ? '#6c757d' : '#28a745' }}; font-size:10px; padding:2px 6px;">
                {{ $role->is_system ? 'System' : 'Custom' }}
              </span>
              <span style="margin-left:8px; color:#999; font-size:11px;">({{ $role->users()->count() }})</span>
            </button>
          @endforeach
        </div>

        @foreach($roles as $index => $role)
          <div class="tab-content {{ $index === 0 ? 'active' : '' }}" id="tab-content-{{ $role->id }}">
            <div class="role-container">
              <form method="POST" action="{{ route('roles.permissions.update', $role->id) }}">
                @csrf
                @method('PUT')
                
                <div class="role-header">
                  <div>
                    <span class="role-title">{{ $role->name }}</span>
                    <span class="badge-role {{ $role->is_system ? 'badge-system' : '' }}" 
                          style="margin-left:10px; background:{{ $role->is_system ? '#6c757d' : '#28a745' }};">
                      {{ $role->is_system ? 'System' : 'Custom' }}
                    </span>
                    <span style="margin-left:10px; color:#666; font-size:13px;">
                      <code style="font-size:11px; background:#f5f5f5; padding:2px 6px; border-radius:2px;">{{ $role->slug }}</code>
                    </span>
                    @if($role->description)
                      <span style="margin-left:10px; color:#999; font-size:12px;">{{ $role->description }}</span>
                    @endif
                  </div>
                  <div style="display:flex; gap:10px; align-items:center;">
                    <a href="{{ route('roles.edit', $role->id) }}" class="btn" style="background:#6c757d; color:#fff; border-color:#6c757d;">Edit Role</a>
                    @if(!$role->is_system && $role->users()->count() == 0)
                      <form action="{{ route('roles.destroy', $role->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this role?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn" style="background: #dc3545; color: white; border-color: #dc3545;">Delete</button>
                      </form>
                    @endif
                    <button type="submit" class="btn-save">Save Permissions</button>
                  </div>
                </div>

                <div class="permissions-grid">
                  @foreach($permissions as $module => $modulePermissions)
                    <div class="module-section">
                      <div>
                        <span class="module-title">{{ ucfirst($module ?: 'Other') }}</span>
                        <span class="select-all-module" onclick="toggleModule('{{ $role->id }}_{{ $module }}')">Select All</span>
                      </div>
                      @foreach($modulePermissions as $permission)
                        <div class="permission-item">
                          <input type="checkbox" 
                                 id="perm_{{ $role->id }}_{{ $permission->id }}" 
                                 name="permissions[]" 
                                 value="{{ $permission->id }}"
                                 data-module="{{ $role->id }}_{{ $module }}"
                                 {{ in_array($permission->id, $rolePermissions[$role->id] ?? []) ? 'checked' : '' }}>
                          <label for="perm_{{ $role->id }}_{{ $permission->id }}">
                            {{ $permission->name }}
                            <span class="permission-slug">({{ $permission->slug }})</span>
                          </label>
                        </div>
                      @endforeach
                    </div>
                  @endforeach
                </div>
              </form>
            </div>
          </div>
        @endforeach
      </div>
    @else
      <div style="padding:40px; text-align:center; background:#fff; border:1px solid #ddd; margin-top:20px;">
        <p style="color:#999; font-size:14px;">No roles found. <a href="{{ route('roles.create') }}" style="color:#df7900;">Create your first role</a></p>
      </div>
    @endif
  </div>
</div>

<script>
  function switchTab(roleId) {
    // Hide all tab contents
    document.querySelectorAll('.tab-content').forEach(content => {
      content.classList.remove('active');
    });
    
    // Remove active class from all tab buttons
    document.querySelectorAll('.tab-button').forEach(button => {
      button.classList.remove('active');
    });
    
    // Show selected tab content
    const selectedContent = document.getElementById('tab-content-' + roleId);
    if (selectedContent) {
      selectedContent.classList.add('active');
    }
    
    // Add active class to selected tab button
    const selectedButton = document.getElementById('tab-btn-' + roleId);
    if (selectedButton) {
      selectedButton.classList.add('active');
    }
  }

  function toggleModule(moduleId) {
    const checkboxes = document.querySelectorAll(`input[data-module="${moduleId}"]`);
    const allChecked = Array.from(checkboxes).every(cb => cb.checked);
    checkboxes.forEach(cb => cb.checked = !allChecked);
  }
</script>
@endsection

