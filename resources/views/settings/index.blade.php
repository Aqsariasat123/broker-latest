@extends('layouts.app')

@section('page-title', 'Settings')

@section('content')
@include('partials.table-styles')

<div class="dashboard">
  <div class="container-table">
    <div style="background:#fff; border:1px solid #ddd; border-radius:4px; overflow:hidden;">
      <div class="table-header" style="background:#fff; border-bottom:1px solid #ddd; margin-bottom:0;">
        <div class="records-found">Settings - Lookup Values</div>
        <div class="action-buttons" style="display:flex; gap:10px;">
          <a href="{{ route('settings.logout') }}" class="btn" style="background:#dc3545; color:#fff; border-color:#dc3545;">Lock Settings</a>
          <a href="/dashboard" class="btn btn-back">Back</a>
        </div>
      </div>

      @if(session('success'))
      <div class="alert alert-success" id="successAlert" style="padding:8px 12px; margin:15px 20px; border:1px solid #c3e6cb; background:#d4edda; color:#155724;">
        {{ session('success') }}
        <button type="button" class="alert-close" onclick="document.getElementById('successAlert').style.display='none'" style="float:right;background:none;border:none;font-size:16px;cursor:pointer;">x</button>
      </div>
      @endif

      <div style="padding:15px;">
        <p style="font-size:12px; color:#666; margin-bottom:15px;">Changes to lookup values will only affect future records. Existing records retain their original values.</p>

        <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(320px, 1fr)); gap:15px;">
          @foreach($categories as $category)
          <div style="border:1px solid #ddd; border-radius:4px; overflow:hidden;">
            <div style="background:#2d2d2d; color:#fff; padding:8px 12px; font-size:13px; font-weight:600; display:flex; justify-content:space-between; align-items:center;">
              <span>{{ $category->name }}</span>
              <button type="button" class="btn" onclick="toggleAddForm({{ $category->id }})" style="background:transparent; color:#fff; border:1px solid #fff; padding:2px 8px; font-size:11px; height:auto;">+ Add</button>
            </div>

            <div id="addForm_{{ $category->id }}" style="display:none; padding:8px 12px; background:#f9f9f9; border-bottom:1px solid #ddd;">
              <form method="POST" action="{{ route('settings.store-value') }}" style="display:flex; gap:8px; align-items:center;">
                @csrf
                <input type="hidden" name="lookup_category_id" value="{{ $category->id }}">
                <input type="text" name="name" class="form-control" placeholder="New value..." required style="flex:1; padding:4px 8px; font-size:12px;">
                <button type="submit" class="btn btn-add" style="padding:4px 12px; height:auto; font-size:12px;">Save</button>
              </form>
            </div>

            <div style="max-height:250px; overflow-y:auto;">
              @foreach($category->values as $value)
              <div style="display:flex; align-items:center; padding:6px 12px; border-bottom:1px solid #f0f0f0; gap:8px;" id="valueRow_{{ $value->id }}">
                <input type="text" value="{{ $value->name }}" class="form-control" id="valueName_{{ $value->id }}" style="flex:1; padding:3px 6px; font-size:12px; border:1px solid #ddd;">
                <label style="display:flex; align-items:center; gap:4px; font-size:11px; color:#666; white-space:nowrap; margin:0; cursor:pointer;">
                  <input type="checkbox" id="valueActive_{{ $value->id }}" {{ $value->active ? 'checked' : '' }} style="margin:0;">
                  Active
                </label>
                <button type="button" class="btn" onclick="updateValue({{ $value->id }})" style="padding:2px 10px; height:auto; font-size:11px; background:#f3742a; color:#fff; border-color:#f3742a;">Save</button>
              </div>
              @endforeach
              @if($category->values->isEmpty())
              <div style="padding:12px; text-align:center; color:#999; font-size:12px;">No values</div>
              @endif
            </div>
          </div>
          @endforeach
        </div>
      </div>
    </div>
  </div>
</div>

<script>
function toggleAddForm(categoryId) {
  const form = document.getElementById('addForm_' + categoryId);
  form.style.display = form.style.display === 'none' ? 'block' : 'none';
}

function updateValue(valueId) {
  const name = document.getElementById('valueName_' + valueId).value;
  const active = document.getElementById('valueActive_' + valueId).checked ? 1 : 0;

  fetch('/settings/values/' + valueId, {
    method: 'PUT',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': '{{ csrf_token() }}',
      'X-Requested-With': 'XMLHttpRequest',
      'Accept': 'application/json'
    },
    body: JSON.stringify({ name: name, active: active })
  })
  .then(r => r.json())
  .then(data => {
    if (data.success) {
      const row = document.getElementById('valueRow_' + valueId);
      row.style.background = '#d4edda';
      setTimeout(() => { row.style.background = ''; }, 1500);
    } else {
      alert(data.error || 'Error updating value');
    }
  })
  .catch(e => { alert('Error: ' + e.message); });
}
</script>
@endsection
