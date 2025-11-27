@extends('layouts.app')

@section('content')
<style>
  .container-table { max-width: 100%; margin: 0 auto; }
  h3 { background: #f1f1f1; padding: 8px; margin-bottom: 10px; font-weight: bold; border: 1px solid #ddd; }
  .top-bar { display:flex; align-items:center; flex-wrap:wrap; gap:10px; margin-bottom:10px; }
  .left-group { display:flex; align-items:center; gap:10px; flex:1 1 auto; min-width:220px; }
  .left-buttons { display:flex; gap:10px; align-items:center; }
  .records-found { font-size:14px; color:#555; min-width:150px; }
  .action-buttons { margin-left:auto; display:flex; gap:10px; align-items:center; }
  .btn { border:none; cursor:pointer; padding:6px 12px; font-size:13px; border-radius:2px; white-space:nowrap; transition:background-color .2s; text-decoration:none; color:inherit; background:#fff; border:1px solid #ccc; }
  .btn-add { background:#df7900; color:#fff; border-color:#df7900; }
  .btn-export, .btn-column { background:#fff; color:#000; border:1px solid #ccc; }
  .btn-back { background:#ccc; color:#333; border-color:#ccc; }
  .table-responsive { width: 100%; overflow-x: auto; border: 1px solid #ddd; max-height: 520px; overflow-y: auto; background: #fff; }
  .footer { display:flex; justify-content:center; align-items:center; padding:5px 0; gap:10px; border-top:1px solid #ccc; flex-wrap:wrap; margin-top:15px; }
  .paginator { display:flex; align-items:center; gap:5px; font-size:12px; color:#555; white-space:nowrap; justify-content:center; }
  .btn-page { color:#2d2d2d; font-size:25px; width:22px; height:50px; padding:5px; cursor:pointer; text-decoration:none; display:inline-flex; align-items:center; justify-content:center; }
  table { width:100%; border-collapse:collapse; font-size:13px; min-width:900px; }
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
  .badge-module { font-size:11px; padding:4px 8px; display:inline-block; border-radius:4px; background:#6c757d; color:#fff; }
  .action-buttons-cell { white-space:nowrap; }
  input[type="text"], select { padding:6px 8px; border:1px solid #ccc; border-radius:2px; font-size:13px; }
  @media (max-width:768px) { .table-responsive { max-height:500px; } }
</style>

<div class="dashboard">
  <div class="container-table">
    <h3>Permissions</h3>

    @if(session('success'))
      <div class="alert alert-success" id="successAlert" style="padding:8px 12px; margin-bottom:12px; border:1px solid #c3e6cb; background:#d4edda; color:#155724;">
        {{ session('success') }}
        <button type="button" class="alert-close" onclick="document.getElementById('successAlert').style.display='none'" style="float:right;background:none;border:none;font-size:16px;cursor:pointer;">×</button>
      </div>
    @endif

    <div class="top-bar">
      <div class="left-group">
        <div class="records-found">Records Found - {{ $permissions->total() }}</div>
        <div class="left-buttons">
          <form method="GET" action="{{ route('permissions.index') }}" style="display: flex; gap: 8px; align-items: center; flex-wrap: wrap;">
            <input type="text" name="search" placeholder="Search permissions..." value="{{ request('search') }}" style="min-width: 150px;">
            <select name="module">
              <option value="">All Modules</option>
              @foreach($modules as $module)
                <option value="{{ $module }}" {{ request('module') == $module ? 'selected' : '' }}>{{ ucfirst($module) }}</option>
              @endforeach
            </select>
            <button type="submit" class="btn">Filter</button>
            @if(request()->hasAny(['search', 'module']))
              <a href="{{ route('permissions.index') }}" class="btn">Clear</a>
            @endif
          </form>
        </div>
      </div>
      <div class="action-buttons">
        <a href="{{ route('permissions.create') }}" class="btn btn-add">Add</a>
        <a href="{{ route('roles.index') }}" class="btn" style="background:#007bff; color:#fff; border-color:#007bff;">Manage Roles</a>
        <button class="btn btn-back" onclick="window.history.back()">Back</button>
      </div>
    </div>

    <div class="table-responsive">
      <table>
        <thead>
          <tr>
            <th>Name</th>
            <th>Slug</th>
            <th>Module</th>
            <th>Description</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($permissions as $permission)
            <tr>
              <td>{{ $permission->name }}</td>
              <td><code style="font-size:11px; background:#f5f5f5; padding:2px 6px; border-radius:2px;">{{ $permission->slug }}</code></td>
              <td>
                @if($permission->module)
                  <span class="badge-module">{{ ucfirst($permission->module) }}</span>
                @else
                  <span style="color: #999;">—</span>
                @endif
              </td>
              <td>{{ $permission->description ?? '—' }}</td>
              <td class="action-buttons-cell">
                <span class="icon-expand" onclick="window.location.href='{{ route('permissions.edit', $permission->id) }}'" title="Edit Permission">⤢</span>
                <form action="{{ route('permissions.destroy', $permission->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this permission?');">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn-action" style="background: #dc3545; color: white; border-color: #dc3545;" title="Delete Permission">🗑</button>
                </form>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="5" style="padding: 20px; text-align: center; color: #999;">No permissions found</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div class="footer">
      <div class="paginator">
        {{ $permissions->links() }}
      </div>
    </div>
  </div>
</div>
@endsection

