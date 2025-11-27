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
  .form-control, textarea { width:100%; padding:6px 8px; border:1px solid #ccc; border-radius:2px; font-size:13px; }
  .form-control:focus, textarea:focus { outline:none; border-color:#007bff; }
  .form-control:disabled { background:#f5f5f5; color:#999; }
  .error-message { color:#dc3545; font-size:12px; margin-top:4px; }
  .btn-save { background:#007bff; color:#fff; border:none; padding:6px 12px; border-radius:2px; cursor:pointer; }
  .btn-cancel { background:#6c757d; color:#fff; border:none; padding:6px 12px; border-radius:2px; cursor:pointer; }
  .btn-delete { background:#dc3545; color:#fff; border:none; padding:6px 12px; border-radius:2px; cursor:pointer; }
  @media (max-width:768px) { .form-group { flex:0 0 100%; } }
</style>

<div class="dashboard">
  <div class="container-table">
    <h3>Edit Role</h3>

    @if(session('success'))
      <div class="alert alert-success" id="successAlert" style="padding:8px 12px; margin-bottom:12px; border:1px solid #c3e6cb; background:#d4edda; color:#155724;">
        {{ session('success') }}
        <button type="button" class="alert-close" onclick="document.getElementById('successAlert').style.display='none'" style="float:right;background:none;border:none;font-size:16px;cursor:pointer;">×</button>
      </div>
    @endif

    <div class="top-bar">
      <div class="left-group">
        <a href="{{ route('roles.index') }}" class="btn btn-back">Back</a>
      </div>
    </div>

    <div class="form-container">
      <form method="POST" action="{{ route('roles.update', $role->id) }}">
        @csrf
        @method('PUT')

        <div class="form-row">
          <div class="form-group">
            <label for="name">Name *</label>
            <input type="text" id="name" name="name" value="{{ old('name', $role->name) }}" class="form-control" required>
            @error('name')<span class="error-message">{{ $message }}</span>@enderror
          </div>

          <div class="form-group">
            <label for="slug">Slug *</label>
            <input type="text" id="slug" name="slug" value="{{ old('slug', $role->slug) }}" class="form-control" required {{ $role->is_system ? 'disabled' : '' }}>
            @if($role->is_system)
              <small style="color:#999; font-size:11px;">System role slug cannot be changed</small>
            @endif
            @error('slug')<span class="error-message">{{ $message }}</span>@enderror
          </div>
        </div>

        <div class="form-row">
          <div class="form-group full-width">
            <label for="description">Description</label>
            <textarea id="description" name="description" class="form-control" rows="3">{{ old('description', $role->description) }}</textarea>
            @error('description')<span class="error-message">{{ $message }}</span>@enderror
          </div>
        </div>

        <div style="display:flex; justify-content:flex-end; gap:10px; margin-top:20px; padding-top:15px; border-top:1px solid #ddd;">
          <a href="{{ route('roles.index') }}" class="btn-cancel" style="text-decoration:none; display:inline-block;">Cancel</a>
          @if(!$role->is_system && $role->users()->count() == 0)
            <form action="{{ route('roles.destroy', $role->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this role?');">
              @csrf
              @method('DELETE')
              <button type="submit" class="btn-delete">Delete</button>
            </form>
          @endif
          <button type="submit" class="btn-save">Save</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

