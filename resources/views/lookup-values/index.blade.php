@extends('layouts.app')
@section('content')

@include('partials.table-styles')

<div class="dashboard">
  <div class="container-table">
    <div style="background:#fff; border:1px solid #ddd; border-radius:4px; overflow:hidden; margin-bottom:15px;">
      <div class="page-header" style="background:#fff; border-bottom:1px solid #ddd; margin-bottom:0;">
        <div class="page-title-section">
          <h3>Lookup Values</h3>
          <div class="records-found">Records Found - {{ $values->total() }}</div>
        </div>
        <div class="action-buttons">
          <button class="btn btn-add" onclick="openValueDialog()">Add</button>
          <a href="{{ route('dashboard') }}" class="btn" style="background:#6c757d; color:#fff; border:none; padding:6px 16px; border-radius:2px; cursor:pointer; text-decoration:none; font-size:13px;">Back</a>
        </div>
      </div>
      
      <!-- Filters -->
      <div style="padding:15px 20px; border-bottom:1px solid #ddd; background:#f8f9fa; display:flex; gap:10px; align-items:center; flex-wrap:wrap;">
        <div style="min-width:150px;">
          <select id="categoryFilter" style="padding:6px 12px; border:1px solid #ccc; border-radius:2px; font-size:13px; width:100%;">
            <option value="">All Categories</option>
            @foreach($categories as $cat)
              <option value="{{ $cat->id }}" {{ $categoryId == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
            @endforeach
          </select>
        </div>
        <div style="flex:1; min-width:150px;">
          <input type="text" id="searchInput" placeholder="Search by name..." value="{{ request('search') }}" style="width:100%; padding:6px 12px; border:1px solid #ccc; border-radius:2px; font-size:13px;">
        </div>
        <div style="min-width:120px;">
          <input type="text" id="codeInput" placeholder="Search by code..." value="{{ request('code') }}" style="width:100%; padding:6px 12px; border:1px solid #ccc; border-radius:2px; font-size:13px;">
        </div>
        <div style="min-width:120px;">
          <input type="text" id="typeInput" placeholder="Search by type..." value="{{ request('type') }}" style="width:100%; padding:6px 12px; border:1px solid #ccc; border-radius:2px; font-size:13px;">
        </div>
        <div style="min-width:120px;">
          <select id="activeFilter" style="padding:6px 12px; border:1px solid #ccc; border-radius:2px; font-size:13px; width:100%;">
            <option value="">All Status</option>
            <option value="1" {{ request('active') == '1' ? 'selected' : '' }}>Active</option>
            <option value="0" {{ request('active') == '0' ? 'selected' : '' }}>Inactive</option>
          </select>
        </div>
        <div>
          <button onclick="applyFilters()" class="btn" style="background:#f3742a; color:#fff; border:none; padding:6px 16px; border-radius:2px; cursor:pointer; font-size:13px;">Filter</button>
        </div>
        <div>
          <button onclick="clearFilters()" class="btn" style="background:#6c757d; color:#fff; border:none; padding:6px 16px; border-radius:2px; cursor:pointer; font-size:13px;">Clear</button>
        </div>
      </div>

      @if(session('success') || request()->get('success'))
        <div class="alert alert-success" id="successAlert" style="padding:8px 12px; margin:15px 20px; border:1px solid #c3e6cb; background:#d4edda; color:#155724;">
          {{ session('success') ?? request()->get('success') }}
          <button type="button" class="alert-close" onclick="document.getElementById('successAlert').style.display='none'" style="float:right;background:none;border:none;font-size:16px;cursor:pointer;">×</button>
        </div>
      @endif

      <div class="table-responsive" id="tableResponsive">
        <table id="valuesTable">
          <thead>
            <tr>
              <th>Action</th>
              <th>ID</th>
              <th>Category</th>
              <th>Seq</th>
              <th>Name</th>
              <th>Code</th>
              <th>Type</th>
              <th>Description</th>
              <th>Active</th>
              <th>Created At</th>
              <th>Updated At</th>
            </tr>
          </thead>
          <tbody>
            @forelse($values as $value)
              <tr>
                <td class="action-cell">
                  <svg class="action-expand" onclick="editValue({{ $value->id }})" width="22" height="22" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="cursor:pointer; vertical-align:middle;">
                    <!-- Maximize icon: four arrows pointing outward from center -->
                    <!-- Top arrow -->
                    <path d="M12 2L12 8M12 2L10 4M12 2L14 4" stroke="#2d2d2d" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <!-- Right arrow -->
                    <path d="M22 12L16 12M22 12L20 10M22 12L20 14" stroke="#2d2d2d" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <!-- Bottom arrow -->
                    <path d="M12 22L12 16M12 22L10 20M12 22L14 20" stroke="#2d2d2d" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <!-- Left arrow -->
                    <path d="M2 12L8 12M2 12L4 10M2 12L4 14" stroke="#2d2d2d" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                  </svg>
                </td>
                <td>{{ $value->id }}</td>
                <td>{{ $value->category->name ?? '—' }}</td>
                <td>{{ $value->seq }}</td>
                <td><strong>{{ $value->name }}</strong></td>
                <td>{{ $value->code ?? '—' }}</td>
                <td>{{ $value->type ?? '—' }}</td>
                <td>{{ $value->description ? (strlen($value->description) > 50 ? substr($value->description, 0, 50) . '...' : $value->description) : '—' }}</td>
                <td>
                  <span style="color: {{ $value->active ? '#28a745' : '#dc3545' }}; font-weight:bold;">
                    {{ $value->active ? 'Yes' : 'No' }}
                  </span>
                </td>
                <td>{{ $value->created_at ? $value->created_at->format('d-M-y') : '—' }}</td>
                <td>{{ $value->updated_at ? $value->updated_at->format('d-M-y') : '—' }}</td>
              </tr>
            @empty
              <tr>
                <td colspan="11" style="text-align:center; padding:20px; color:#6c757d;">No values found</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      @if($values->hasPages())
        <div class="paginator" style="padding:15px 20px; border-top:1px solid #ddd; display:flex; align-items:center; justify-content:center; gap:8px;">
          @php
            $base = url()->current();
            $q = request()->query();
            $current = $values->currentPage();
            $last = max(1, $values->lastPage());
            function page_url($base, $q, $p) {
              $params = array_merge($q, ['page' => $p]);
              return $base . '?' . http_build_query($params);
            }
          @endphp

          <a class="btn-page" href="{{ $current > 1 ? page_url($base, $q, 1) : '#' }}" @if($current <= 1) style="opacity:0.5; cursor:not-allowed;" @endif>&laquo;</a>
          <a class="btn-page" href="{{ $current > 1 ? page_url($base, $q, $current - 1) : '#' }}" @if($current <= 1) style="opacity:0.5; cursor:not-allowed;" @endif>&lsaquo;</a>

          <span style="padding:0 8px;">Page {{ $current }} of {{ $last }}</span>

          <a class="btn-page" href="{{ $current < $last ? page_url($base, $q, $current + 1) : '#' }}" @if($current >= $last) style="opacity:0.5; cursor:not-allowed;" @endif>&rsaquo;</a>
          <a class="btn-page" href="{{ $current < $last ? page_url($base, $q, $last) : '#' }}" @if($current >= $last) style="opacity:0.5; cursor:not-allowed;" @endif>&raquo;</a>
        </div>
      @endif
    </div>
  </div>
</div>

<!-- Value Modal Dialog -->
<div id="valueModal" class="modal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:1000; align-items:center; justify-content:center;">
  <div class="modal-content" style="background:#fff; border-radius:6px; width:95%; max-width:600px; box-shadow:0 4px 6px rgba(0,0,0,0.1); padding:0;">
    <div class="modal-header" style="background-color:#f8f9fa; border-bottom:1px solid #dee2e6; padding:15px 20px; display:flex; align-items:center; justify-content:space-between;">
      <h3 id="valueModalTitle" style="margin:0; font-size:18px; font-weight:600;">Add Value</h3>
      <div style="display:flex; gap:8px;">
        <button type="button" class="btn-save" onclick="saveValue()" style="background:#f3742a; color:#fff; border:none; padding:6px 16px; border-radius:2px; cursor:pointer; font-size:13px;">Save</button>
        <button type="button" class="modal-close" onclick="closeValueDialog()" style="background:none; border:none; font-size:24px; cursor:pointer; color:#666; padding:0; width:30px; height:30px; display:flex; align-items:center; justify-content:center;">×</button>
      </div>
    </div>
    <form id="valueForm" style="margin:0;">
      @csrf
      <input type="hidden" id="value_id" name="id">
      <div class="modal-body" style="padding:20px;">
        <div class="form-group" style="margin-bottom:15px;">
          <label style="display:block; margin-bottom:5px; font-weight:600; font-size:13px;">Category <span style="color:red;">*</span></label>
          <select name="lookup_category_id" id="value_category_id" class="form-control" required style="width:100%; padding:8px; font-size:13px; border:1px solid #ccc; border-radius:2px;">
            <option value="">Select Category</option>
            @foreach($categories as $cat)
              <option value="{{ $cat->id }}">{{ $cat->name }}</option>
            @endforeach
          </select>
        </div>
        <div class="form-group" style="margin-bottom:15px;">
          <label style="display:block; margin-bottom:5px; font-weight:600; font-size:13px;">Sequence Number <span style="color:red;">*</span></label>
          <input type="number" name="seq" id="value_seq" class="form-control" required min="1" style="width:100%; padding:8px; font-size:13px; border:1px solid #ccc; border-radius:2px;">
        </div>
        <div class="form-group" style="margin-bottom:15px;">
          <label style="display:block; margin-bottom:5px; font-weight:600; font-size:13px;">Name <span style="color:red;">*</span></label>
          <input type="text" name="name" id="value_name" class="form-control" required style="width:100%; padding:8px; font-size:13px; border:1px solid #ccc; border-radius:2px;">
        </div>
        <div class="form-group" style="margin-bottom:15px;">
          <label style="display:block; margin-bottom:5px; font-weight:600; font-size:13px;">Code</label>
          <input type="text" name="code" id="value_code" class="form-control" style="width:100%; padding:8px; font-size:13px; border:1px solid #ccc; border-radius:2px;">
        </div>
        <div class="form-group" style="margin-bottom:15px;">
          <label style="display:block; margin-bottom:5px; font-weight:600; font-size:13px;">Type</label>
          <input type="text" name="type" id="value_type" class="form-control" style="width:100%; padding:8px; font-size:13px; border:1px solid #ccc; border-radius:2px;">
        </div>
        <div class="form-group" style="margin-bottom:15px;">
          <label style="display:block; margin-bottom:5px; font-weight:600; font-size:13px;">Description</label>
          <textarea name="description" id="value_description" class="form-control" rows="3" style="width:100%; padding:8px; font-size:13px; border:1px solid #ccc; border-radius:2px;"></textarea>
        </div>
        <div class="form-group" style="margin-bottom:15px;">
          <label style="display:flex; align-items:center; gap:8px; font-weight:600; font-size:13px; cursor:pointer;">
            <input type="checkbox" name="active" id="value_active" value="1" checked style="width:18px; height:18px; cursor:pointer;">
            <span>Active</span>
          </label>
        </div>
      </div>
    </form>
  </div>
</div>

<script>
  let currentValueId = null;

  // Filter functions
  function applyFilters() {
    const categoryId = document.getElementById('categoryFilter').value;
    const search = document.getElementById('searchInput').value;
    const code = document.getElementById('codeInput').value;
    const type = document.getElementById('typeInput').value;
    const active = document.getElementById('activeFilter').value;
    const url = new URL(window.location.href);
    
    if (categoryId) {
      url.searchParams.set('category_id', categoryId);
    } else {
      url.searchParams.delete('category_id');
    }
    
    if (search) {
      url.searchParams.set('search', search);
    } else {
      url.searchParams.delete('search');
    }
    
    if (code) {
      url.searchParams.set('code', code);
    } else {
      url.searchParams.delete('code');
    }
    
    if (type) {
      url.searchParams.set('type', type);
    } else {
      url.searchParams.delete('type');
    }
    
    if (active) {
      url.searchParams.set('active', active);
    } else {
      url.searchParams.delete('active');
    }
    
    url.searchParams.delete('page'); // Reset to first page
    window.location.href = url.toString();
  }
  
  function clearFilters() {
    window.location.href = '{{ route("lookup-values.index") }}';
  }
  
  // Allow Enter key to trigger filter
  document.getElementById('searchInput').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
      applyFilters();
    }
  });
  
  document.getElementById('codeInput').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
      applyFilters();
    }
  });
  
  document.getElementById('typeInput').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
      applyFilters();
    }
  });

  function openValueDialog() {
    currentValueId = null;
    document.getElementById('valueModalTitle').textContent = 'Add Value';
    document.getElementById('valueForm').reset();
    document.getElementById('value_id').value = '';
    document.getElementById('value_active').checked = true;
    // Set default category if filtered
    const categoryFilter = document.getElementById('categoryFilter').value;
    if (categoryFilter) {
      document.getElementById('value_category_id').value = categoryFilter;
    }
    const modal = document.getElementById('valueModal');
    modal.style.display = 'flex';
    modal.classList.add('show');
  }

  function closeValueDialog() {
    const modal = document.getElementById('valueModal');
    modal.style.display = 'none';
    modal.classList.remove('show');
    document.getElementById('valueForm').reset();
    currentValueId = null;
  }

  async function editValue(id) {
    try {
      const response = await fetch(`/lookups/values/${id}/edit`, {
        headers: {
          'Accept': 'application/json',
          'X-Requested-With': 'XMLHttpRequest'
        }
      });
      if (!response.ok) throw new Error('Network error');
      const value = await response.json();
      
      currentValueId = id;
      document.getElementById('valueModalTitle').textContent = 'Edit Value';
      document.getElementById('value_id').value = id;
      document.getElementById('value_category_id').value = value.lookup_category_id || '';
      document.getElementById('value_seq').value = value.seq || '';
      document.getElementById('value_name').value = value.name || '';
      document.getElementById('value_code').value = value.code || '';
      document.getElementById('value_type').value = value.type || '';
      document.getElementById('value_description').value = value.description || '';
      document.getElementById('value_active').checked = value.active ? true : false;
      
      const modal = document.getElementById('valueModal');
      modal.style.display = 'flex';
      modal.classList.add('show');
    } catch (error) {
      console.error('Error loading value:', error);
      alert('Error loading value details');
    }
  }

  async function saveValue() {
    const form = document.getElementById('valueForm');
    
    if (!form.checkValidity()) {
      form.reportValidity();
      return;
    }
    
    const formData = new FormData();
    formData.append('lookup_category_id', document.getElementById('value_category_id').value);
    formData.append('seq', document.getElementById('value_seq').value);
    formData.append('name', document.getElementById('value_name').value);
    formData.append('code', document.getElementById('value_code').value);
    formData.append('type', document.getElementById('value_type').value);
    formData.append('description', document.getElementById('value_description').value);
    formData.append('active', document.getElementById('value_active').checked ? '1' : '0');
    
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
    formData.append('_token', csrfToken);
    
    const url = currentValueId 
      ? `/lookups/values/${currentValueId}`
      : '{{ route("lookup-values.store") }}';
    
    if (currentValueId) {
      formData.append('_method', 'PUT');
    }

    try {
      const response = await fetch(url, {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': csrfToken,
          'Accept': 'application/json',
          'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
      });

      const data = await response.json();

      if (response.ok && data.success) {
        closeValueDialog();
        window.location.reload();
      } else {
        alert(data.message || 'Error saving value');
      }
    } catch (error) {
      console.error('Error saving value:', error);
      alert('Error saving value');
    }
  }

  // Close modal on backdrop click
  document.getElementById('valueModal').addEventListener('click', function(e) {
    if (e.target === this) {
      closeValueDialog();
    }
  });

  // Close modal on ESC key
  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
      closeValueDialog();
    }
  });
</script>

@endsection

