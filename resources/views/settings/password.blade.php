@extends('layouts.app')

@section('page-title', 'Settings')

@section('content')
@include('partials.table-styles')

<div class="dashboard">
  <div class="container-table">
    <div style="background:#fff; border:1px solid #ddd; border-radius:4px; overflow:hidden;">
      <div class="table-header" style="background:#fff; border-bottom:1px solid #ddd; margin-bottom:0;">
        <div class="records-found">Settings - Password Required</div>
        <a href="/dashboard" class="btn btn-back">Close</a>
      </div>
      <div style="padding:40px 20px; display:flex; justify-content:center;">
        <div style="max-width:360px; width:100%;">
          <form method="POST" action="{{ route('settings.authenticate') }}">
            @csrf
            <div class="form-group" style="margin-bottom:20px;">
              <label for="settings_password" style="font-size:13px; font-weight:600; margin-bottom:8px; display:block;">Enter Settings Password</label>
              <input type="password" id="settings_password" name="password" class="form-control" required autofocus style="padding:10px; font-size:14px;">
              @error('password')
              <div style="color:#dc3545; font-size:12px; margin-top:6px;">{{ $message }}</div>
              @enderror
            </div>
            <button type="submit" class="btn btn-add" style="width:100%; justify-content:center; padding:10px; font-size:14px;">Unlock Settings</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
