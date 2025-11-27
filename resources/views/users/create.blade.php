@extends('layouts.app')

@section('content')
<style>
  .container-table { max-width: 100%; margin: 0 auto; }
  h3 { background: #f1f1f1; padding: 8px; margin-bottom: 10px; font-weight: bold; border: 1px solid #ddd; }
  .top-bar { display:flex; align-items:center; flex-wrap:wrap; gap:10px; margin-bottom:10px; }
  .left-group { display:flex; align-items:center; gap:10px; flex:1 1 auto; min-width:220px; }
  .btn { border:none; cursor:pointer; padding:6px 12px; font-size:13px; border-radius:2px; white-space:nowrap; transition:background-color .2s; text-decoration:none; color:inherit; background:#fff; border:1px solid #ccc; }
  .btn-add { background:#df7900; color:#fff; border-color:#df7900; }
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
  .checkbox-group { display:flex; align-items:center; gap:8px; padding:8px 0; }
  .checkbox-group input[type="checkbox"] { width:auto; }
  @media (max-width:768px) { .form-group { flex:0 0 100%; } }
</style>

<div class="dashboard">
  <div class="container-table">
    <h3>Create User</h3>

    @if(session('success'))
      <div class="alert alert-success" id="successAlert" style="padding:8px 12px; margin-bottom:12px; border:1px solid #c3e6cb; background:#d4edda; color:#155724;">
        {{ session('success') }}
        <button type="button" class="alert-close" onclick="document.getElementById('successAlert').style.display='none'" style="float:right;background:none;border:none;font-size:16px;cursor:pointer;">×</button>
      </div>
    @endif

    <div class="top-bar">
      <div class="left-group">
        <a href="{{ route('users.index') }}" class="btn btn-back">Back</a>
      </div>
    </div>

    <div class="form-container">
      <form method="POST" action="{{ route('users.store') }}">
        @csrf

        <div class="form-row">
          <div class="form-group">
            <label for="name">Name *</label>
            <input type="text" id="name" name="name" value="{{ old('name') }}" class="form-control" required>
            @error('name')<span class="error-message">{{ $message }}</span>@enderror
          </div>

          <div class="form-group">
            <label for="email">Email *</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" class="form-control" required>
            @error('email')<span class="error-message">{{ $message }}</span>@enderror
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label for="password">Password *</label>
            <input type="password" id="password" name="password" class="form-control" required>
            @error('password')<span class="error-message">{{ $message }}</span>@enderror
          </div>

          <div class="form-group">
            <label for="password_confirmation">Confirm Password *</label>
            <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required>
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label for="role_id">Role *</label>
            <select id="role_id" name="role_id" class="form-control" required>
              <option value="">Select Role</option>
              @foreach($roles as $role)
                <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
              @endforeach
            </select>
            @error('role_id')<span class="error-message">{{ $message }}</span>@enderror
          </div>

          <div class="form-group">
            <label>&nbsp;</label>
            <div class="checkbox-group">
              <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
              <label for="is_active" style="font-weight:normal; margin:0; cursor:pointer;">Active</label>
            </div>
          </div>
        </div>

        <div style="display:flex; justify-content:flex-end; gap:10px; margin-top:20px; padding-top:15px; border-top:1px solid #ddd;">
          <a href="{{ route('users.index') }}" class="btn-cancel" style="text-decoration:none; display:inline-block;">Cancel</a>
          <button type="submit" class="btn-save">Save</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

