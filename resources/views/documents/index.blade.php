@extends('layouts.app')
@section('content')

@include('partials.table-styles')

@php
  $config = \App\Helpers\TableConfigHelper::getConfig('documents');
  $selectedColumns = \App\Helpers\TableConfigHelper::getSelectedColumns('documents');
  $columnDefinitions = $config['column_definitions'] ?? [];
  $mandatoryColumns = $config['mandatory_columns'] ?? [];
@endphp

<div class="dashboard">
  <!-- Main Documents Table View -->
  <div class="clients-table-view" id="clientsTableView">
  <div class="container-table">
    <!-- Documents Card -->
    <div style="background:#fff; border:1px solid #ddd; border-radius:4px; overflow:hidden;">
      <div class="page-header" style="background:#fff; border-bottom:1px solid #ddd; margin-bottom:0;">
      <div class="page-title-section">
        <h3>Documents</h3>
        <div class="records-found">Records Found - {{ $documents->total() }}</div>
      </div>
      <div class="action-buttons">
        <button class="btn btn-add" id="addDocumentBtn">Add</button>
      </div>
    </div>

    @if(session('success'))
      <div class="alert alert-success" id="successAlert" style="padding:8px 12px; margin:15px 20px; border:1px solid #c3e6cb; background:#d4edda; color:#155724;">
        {{ session('success') }}
        <button type="button" class="alert-close" onclick="document.getElementById('successAlert').style.display='none'" style="float:right;background:none;border:none;font-size:16px;cursor:pointer;">×</button>
      </div>
    @endif

    <div class="table-responsive" id="tableResponsive">
      <table id="documentsTable">
        <thead>
          <tr>
            <th style="text-align:center;">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="display:inline-block; vertical-align:middle;">
                <path d="M12 2C8.13 2 5 5.13 5 9C5 14.25 2 16 2 16H22C22 16 19 14.25 19 9C19 5.13 15.87 2 12 2Z" fill="#fff" stroke="#fff" stroke-width="1.5"/>
                <path d="M9 21C9 22.1 9.9 23 11 23H13C14.1 23 15 22.1 15 21H9Z" fill="#fff"/>
              </svg>
            </th>
            <th>Action</th>
            @foreach($selectedColumns as $col)
              @if(isset($columnDefinitions[$col]))
                <th data-column="{{ $col }}">{{ $columnDefinitions[$col] }}</th>
              @endif
            @endforeach
          </tr>
        </thead>
        <tbody>
          @foreach($documents as $doc)
            <tr>
              <td class="bell-cell">
                <div style="display:flex; align-items:center; justify-content:center;">
                  <div class="status-indicator normal" style="width:18px; height:18px; border-radius:50%; border:2px solid #000; background-color:transparent;"></div>
                </div>
              </td>
              <td class="action-cell">
                <svg class="action-expand" onclick="openDocumentDetails({{ $doc->id }})" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="cursor:pointer; vertical-align:middle;">
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
                <svg class="action-delete" onclick="if(confirm('Delete this document?')) { deleteDocumentFromTable({{ $doc->id }}); }" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="cursor:pointer; vertical-align:middle;">
                  <!-- Trash icon -->
                  <path d="M3 6H5H21" stroke="#2d2d2d" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                  <path d="M8 6V4C8 3.46957 8.21071 2.96086 8.58579 2.58579C8.96086 2.21071 9.46957 2 10 2H14C14.5304 2 15.0391 2.21071 15.4142 2.58579C15.7893 2.96086 16 3.46957 16 4V6M19 6V20C19 20.5304 18.7893 21.0391 18.4142 21.4142C18.0391 21.7893 17.5304 22 17 22H7C6.46957 22 5.96086 21.7893 5.58579 21.4142C5.21071 21.0391 5 20.5304 5 20V6H19Z" stroke="#2d2d2d" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                  <path d="M10 11V17" stroke="#2d2d2d" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                  <path d="M14 11V17" stroke="#2d2d2d" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
              </td>
              @foreach($selectedColumns as $col)
                @if($col == 'doc_id')
                  <td data-column="doc_id">
                  {{ $doc->doc_id }}
                  </td>
                @elseif($col == 'tied_to')
                  <td data-column="tied_to">{{ $doc->tied_to ?? '-' }}</td>
                @elseif($col == 'name')
                  <td data-column="name">{{ $doc->name ?? '-' }}</td>
                @elseif($col == 'group')
                  <td data-column="group">{{ $doc->group ?? '-' }}</td>
                @elseif($col == 'type')
                  <td data-column="type">{{ $doc->type ?? '-' }}</td>
                @elseif($col == 'format')
                  <td data-column="format">{{ $doc->format ?? '-' }}</td>
                @elseif($col == 'date_added')
                  <td data-column="date_added">{{ $doc->date_added ? \Carbon\Carbon::parse($doc->date_added)->format('d-M-y') : '-' }}</td>
                @elseif($col == 'year')
                  <td data-column="year">{{ $doc->year ?? '-' }}</td>
                @elseif($col == 'file_path')
                  <td data-column="file_path">
                    @if($doc->file_path)
                      <a href="{{ asset('storage/'.$doc->file_path) }}" target="_blank" style="color:#007bff; text-decoration:underline;">View</a>
                    @else
                      -
                    @endif
                  </td>
                @elseif($col == 'notes')
                  <td data-column="notes">{{ $doc->notes ?? '-' }}</td>
                @endif
              @endforeach
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    </div>

    <div class="footer" style="background:#fff; border-top:1px solid #ddd; padding:10px 20px; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap;">
      <div class="footer-left">
        <a class="btn btn-export" href="{{ route('documents.export') }}">Export</a>
        <button class="btn btn-column" id="columnBtn2" type="button">Column</button>
      </div>
      <div class="paginator">
        @php
          $base = url()->current();
          $q = request()->query();
          $current = $documents->currentPage();
          $last = max(1, $documents->lastPage());
          function page_url($base, $q, $p) {
            $params = array_merge($q, ['page' => $p]);
            return $base . '?' . http_build_query($params);
          }
        @endphp

        <a class="btn-page" href="{{ $current > 1 ? page_url($base, $q, 1) : '#' }}" @if($current <= 1) disabled @endif>&laquo;</a>
        <a class="btn-page" href="{{ $current > 1 ? page_url($base, $q, $current - 1) : '#' }}" @if($current <= 1) disabled @endif>&lsaquo;</a>

        <span style="padding:0 8px;">Page {{ $current }} of {{ $last }}</span>

        <a class="btn-page" href="{{ $current < $last ? page_url($base, $q, $current + 1) : '#' }}" @if($current >= $last) disabled @endif>&rsaquo;</a>
        <a class="btn-page" href="{{ $current < $last ? page_url($base, $q, $last) : '#' }}" @if($current >= $last) disabled @endif>&raquo;</a>
      </div>
    </div>
    </div>
  </div>

  <!-- Document Page View (Full Page) -->
  <div class="client-page-view" id="documentPageView" style="display:none;">
    <div class="client-page-header">
      <div class="client-page-title">
        <span id="documentPageTitle">Document</span> - <span class="client-name" id="documentPageName"></span>
      </div>
      <div class="client-page-actions">
        <button class="btn btn-edit" id="editDocumentFromPageBtn" style="background:#f3742a; color:#fff; border:none; padding:6px 16px; border-radius:2px; cursor:pointer; display:none;">Edit</button>
        <button class="btn" id="closeDocumentPageBtn" onclick="closeDocumentPageView()" style="background:#e0e0e0; color:#000; border:none; padding:6px 16px; border-radius:2px; cursor:pointer;">Close</button>
      </div>
    </div>
    <div class="client-page-body">
      <div class="client-page-content">
        <!-- Document Details View -->
        <div id="documentDetailsPageContent" style="display:none;">
          <div style="background:#fff; border:1px solid #ddd; border-radius:4px; margin-bottom:15px; overflow:hidden;">
            <div id="documentDetailsContent" style="display:grid; grid-template-columns:repeat(4, 1fr); gap:0; align-items:start; padding:12px;">
              <!-- Content will be loaded via JavaScript -->
            </div>
          </div>
        </div>

        <!-- Document Edit/Add Form -->
        <div id="documentFormPageContent" style="display:none;">
          <div style="background:#fff; border:1px solid #ddd; border-radius:4px; margin-bottom:15px; overflow:hidden;">
            <div style="display:flex; justify-content:flex-end; align-items:center; padding:12px 15px; border-bottom:1px solid #ddd; background:#fff;">
              <div class="client-page-actions">
                <button type="button" class="btn-delete" id="documentDeleteBtn" style="display:none; background:#dc3545; color:#fff; border:none; padding:6px 16px; border-radius:2px; cursor:pointer;" onclick="deleteDocument()">Delete</button>
                <button type="submit" form="documentPageForm" class="btn-save" style="background:#f3742a; color:#fff; border:none; padding:6px 16px; border-radius:2px; cursor:pointer;">Save</button>
                <button type="button" class="btn" id="closeDocumentFormBtn" onclick="closeDocumentPageView()" style="background:#e0e0e0; color:#000; border:none; padding:6px 16px; border-radius:2px; cursor:pointer; display:none;">Close</button>
              </div>
            </div>
            <form id="documentPageForm" method="POST" action="{{ route('documents.store') }}" enctype="multipart/form-data">
              @csrf
              <div id="documentPageFormMethod" style="display:none;"></div>
              <div style="padding:12px;">
                <!-- Form content will be cloned from modal -->
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Document Details Modal -->
  <div class="modal" id="documentDetailsModal">
    <div class="modal-content" style="max-width:800px;">
      <div class="modal-header">
        <h4 id="documentDetailsModalTitle">Document Details</h4>
        <button type="button" class="modal-close" onclick="closeDocumentDetailsModal()">×</button>
      </div>
      <div class="modal-body" style="padding:20px;">
        <div id="documentDetailsContent" style="display:grid; grid-template-columns:repeat(2, 1fr); gap:15px;">
          <!-- Content will be loaded via JavaScript -->
        </div>
      </div>
      <div class="modal-footer" style="padding:15px 20px; border-top:1px solid #ddd; background:#fff; display:flex; justify-content:flex-end; gap:10px;">
        <button type="button" class="btn-edit" id="editDocumentFromDetailsBtn" onclick="openDocumentModal('edit', currentDocumentId)" style="background:#f3742a; color:#fff; border:none; padding:8px 20px; border-radius:2px; cursor:pointer; font-size:13px; display:none;">Edit</button>
        <button type="button" class="btn-cancel" onclick="closeDocumentDetailsModal()" style="background:#000; color:#fff; border:none; padding:8px 20px; border-radius:2px; cursor:pointer; font-size:13px;">Close</button>
      </div>
    </div>
  </div>

  <!-- Add/Edit Document Modal -->
  <div class="modal" id="documentModal">
    <div class="modal-content" style="max-width:800px;">
      <div class="modal-header">
        <h4 id="documentModalTitle">Add Document</h4>
        <button type="button" class="modal-close" onclick="closeDocumentModal()">×</button>
      </div>
      <form id="documentForm" method="POST" action="{{ route('documents.store') }}" enctype="multipart/form-data">
        @csrf
        <div id="documentFormMethod" style="display:none;"></div>
        <div class="modal-body" style="padding:20px;">
          <div class="form-row" style="display:flex; gap:15px; margin-bottom:15px;">
            <div class="form-group" style="flex:1;">
              <label for="tied_to" style="display:block; margin-bottom:5px; font-size:13px; font-weight:500;">Tied To</label>
              <input type="text" class="form-control" name="tied_to" id="tied_to" style="width:100%; padding:8px; border:1px solid #ddd; border-radius:2px; font-size:13px;">
            </div>
            <div class="form-group" style="flex:1;">
              <label for="name" style="display:block; margin-bottom:5px; font-size:13px; font-weight:500;">Name *</label>
              <input type="text" class="form-control" name="name" id="name" required style="width:100%; padding:8px; border:1px solid #ddd; border-radius:2px; font-size:13px;">
            </div>
            <div class="form-group" style="flex:1;">
              <label for="group" style="display:block; margin-bottom:5px; font-size:13px; font-weight:500;">Group</label>
              <input type="text" class="form-control" name="group" id="group" style="width:100%; padding:8px; border:1px solid #ddd; border-radius:2px; font-size:13px;">
            </div>
          </div>
          <div class="form-row" style="display:flex; gap:15px; margin-bottom:15px;">
            <div class="form-group" style="flex:1;">
              <label for="type" style="display:block; margin-bottom:5px; font-size:13px; font-weight:500;">Type</label>
              <input type="text" class="form-control" name="type" id="type" style="width:100%; padding:8px; border:1px solid #ddd; border-radius:2px; font-size:13px;">
            </div>
            <div class="form-group" style="flex:1;">
              <label for="date_added" style="display:block; margin-bottom:5px; font-size:13px; font-weight:500;">Date Added</label>
              <input type="date" class="form-control" name="date_added" id="date_added" style="width:100%; padding:8px; border:1px solid #ddd; border-radius:2px; font-size:13px;">
            </div>
            <div class="form-group" style="flex:1;">
              <label for="year" style="display:block; margin-bottom:5px; font-size:13px; font-weight:500;">Year</label>
              <input type="text" class="form-control" name="year" id="year" style="width:100%; padding:8px; border:1px solid #ddd; border-radius:2px; font-size:13px;">
            </div>
          </div>
          <div class="form-row" style="display:flex; gap:15px; margin-bottom:15px;">
            <div class="form-group" style="flex:1 1 100%;">
              <label for="file" style="display:block; margin-bottom:5px; font-size:13px; font-weight:500;">File</label>
              <input type="file" class="form-control" name="file" id="file" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx,.xls,.xlsx" style="width:100%; padding:8px; border:1px solid #ddd; border-radius:2px; font-size:13px;">
            </div>
          </div>
          <div class="form-row" style="display:flex; gap:15px; margin-bottom:15px;">
            <div class="form-group" style="flex:1 1 100%;">
              <label for="notes" style="display:block; margin-bottom:5px; font-size:13px; font-weight:500;">Notes</label>
              <textarea class="form-control" name="notes" id="notes" rows="4" style="width:100%; padding:8px; border:1px solid #ddd; border-radius:2px; font-size:13px; resize:vertical;"></textarea>
            </div>
          </div>
        </div>
        <div class="modal-footer" style="padding:15px 20px; border-top:1px solid #ddd; background:#fff; display:flex; justify-content:center; gap:10px;">
          <button type="button" class="btn-cancel" onclick="closeDocumentModal()" style="background:#000; color:#fff; border:none; padding:8px 20px; border-radius:2px; cursor:pointer; font-size:13px;">Cancel</button>
          <button type="button" class="btn-delete" id="documentDeleteBtnModal" style="display: none; background:#dc3545; color:#fff; border:none; padding:8px 20px; border-radius:2px; cursor:pointer; font-size:13px;" onclick="deleteDocument()">Delete</button>
          <button type="submit" class="btn-save" style="background:#f3742a; color:#fff; border:none; padding:8px 20px; border-radius:2px; cursor:pointer; font-size:13px;">Save</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Column Selection Modal -->
  <div class="modal" id="columnModal">
    <div class="modal-content">
      <div class="modal-header">
        <h4>Column Select & Sort</h4>
        <button type="button" class="modal-close" onclick="closeColumnModal()">×</button>
      </div>
      <div class="modal-body">
        <div class="column-actions">
          <button type="button" class="btn-select-all" onclick="selectAllColumns()">Select All</button>
          <button type="button" class="btn-deselect-all" onclick="deselectAllColumns()">Deselect All</button>
        </div>

        <form id="columnForm" action="{{ route('documents.save-column-settings') }}" method="POST">
          @csrf
          <div class="column-selection" id="columnSelection">
            @php
              $all = $config['column_definitions'];
              // Maintain order based on selectedColumns
              $ordered = [];
              foreach($selectedColumns as $col) {
                if(isset($all[$col])) {
                  $ordered[$col] = $all[$col];
                  unset($all[$col]);
                }
              }
              $ordered = array_merge($ordered, $all);
            @endphp

            @foreach($ordered as $key => $label)
              @php
                $isMandatory = in_array($key, $mandatoryColumns);
                $isChecked = in_array($key, $selectedColumns) || $isMandatory;
              @endphp
              <div class="column-item" draggable="true" data-column="{{ $key }}" style="cursor:move;">
                <span style="cursor:move; margin-right:8px; font-size:16px; color:#666;">☰</span>
                <input type="checkbox" class="column-checkbox" id="col_{{ $key }}" value="{{ $key }}" @if($isChecked) checked @endif @if($isMandatory) disabled @endif>
                <label for="col_{{ $key }}" style="cursor:pointer; flex:1; user-select:none;">{{ $label }}</label>
              </div>
            @endforeach
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button class="btn-cancel" onclick="closeColumnModal()">Cancel</button>
        <button class="btn-save" onclick="saveColumnSettings()">Save Settings</button>
      </div>
    </div>
  </div>

</div>

<script>
  let currentDocumentId = null;
  const selectedColumns = @json($selectedColumns);
  const mandatoryColumns = @json($mandatoryColumns);

  // Helper function for date formatting
  function formatDate(dateStr) {
    if (!dateStr) return '-';
    const date = new Date(dateStr);
    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    return `${date.getDate()}-${months[date.getMonth()]}-${String(date.getFullYear()).slice(-2)}`;
  }

  // Open document details modal
  async function openDocumentDetails(id) {
    try {
      const res = await fetch(`/documents/${id}`, {
        headers: {
          'Accept': 'application/json',
          'X-Requested-With': 'XMLHttpRequest'
        }
      });
      if (!res.ok) throw new Error(`HTTP ${res.status}`);
      const doc = await res.json();
      currentDocumentId = id;
      
      // Set document name in header
      const documentName = doc.name || doc.doc_id || 'Unknown';
      document.getElementById('documentDetailsModalTitle').textContent = 'Document Details - ' + documentName;
      
      populateDocumentDetailsModal(doc);
      
      // Show edit button
      const editBtn = document.getElementById('editDocumentFromDetailsBtn');
      if (editBtn) editBtn.style.display = 'inline-block';
      
      // Show modal
      const modal = document.getElementById('documentDetailsModal');
      modal.classList.add('show');
      document.body.style.overflow = 'hidden';
    } catch (e) {
      console.error(e);
      alert('Error loading document details: ' + e.message);
    }
  }

  function closeDocumentDetailsModal() {
    const modal = document.getElementById('documentDetailsModal');
    modal.classList.remove('show');
    document.body.style.overflow = '';
    currentDocumentId = null;
  }

  // Populate document details modal
  function populateDocumentDetailsModal(doc) {
    const content = document.getElementById('documentDetailsContent');
    if (!content) return;

    const fileLink = doc.file_path ? `<a href="{{ asset('storage') }}/${doc.file_path}" target="_blank" style="color:#007bff; text-decoration:underline;">View File</a>` : '-';

    content.innerHTML = `
      <div style="background:#f5f5f5; padding:12px; border-radius:4px;">
        <div style="margin-bottom:10px;">
          <span style="font-size:12px; color:#666; font-weight:500;">Document ID:</span>
          <div style="font-size:13px; color:#000; margin-top:4px;">${doc.doc_id || '-'}</div>
        </div>
        <div style="margin-bottom:10px;">
          <span style="font-size:12px; color:#666; font-weight:500;">Name:</span>
          <div style="font-size:13px; color:#000; margin-top:4px;">${doc.name || '-'}</div>
        </div>
        <div style="margin-bottom:10px;">
          <span style="font-size:12px; color:#666; font-weight:500;">Tied To:</span>
          <div style="font-size:13px; color:#000; margin-top:4px;">${doc.tied_to || '-'}</div>
        </div>
        <div style="margin-bottom:10px;">
          <span style="font-size:12px; color:#666; font-weight:500;">Group:</span>
          <div style="font-size:13px; color:#000; margin-top:4px;">${doc.group || '-'}</div>
        </div>
        <div style="margin-bottom:10px;">
          <span style="font-size:12px; color:#666; font-weight:500;">Type:</span>
          <div style="font-size:13px; color:#000; margin-top:4px;">${doc.type || '-'}</div>
        </div>
      </div>
      <div style="background:#f5f5f5; padding:12px; border-radius:4px;">
        <div style="margin-bottom:10px;">
          <span style="font-size:12px; color:#666; font-weight:500;">Format:</span>
          <div style="font-size:13px; color:#000; margin-top:4px;">${doc.format || '-'}</div>
        </div>
        <div style="margin-bottom:10px;">
          <span style="font-size:12px; color:#666; font-weight:500;">Date Added:</span>
          <div style="font-size:13px; color:#000; margin-top:4px;">${formatDate(doc.date_added)}</div>
        </div>
        <div style="margin-bottom:10px;">
          <span style="font-size:12px; color:#666; font-weight:500;">Year:</span>
          <div style="font-size:13px; color:#000; margin-top:4px;">${doc.year || '-'}</div>
        </div>
        <div style="margin-bottom:10px;">
          <span style="font-size:12px; color:#666; font-weight:500;">File:</span>
          <div style="font-size:13px; color:#000; margin-top:4px;">${fileLink}</div>
        </div>
        <div style="margin-top:15px;">
          <span style="font-size:12px; color:#666; font-weight:500;">Notes:</span>
          <div style="font-size:13px; color:#000; margin-top:4px; white-space:pre-wrap;">${doc.notes || '-'}</div>
        </div>
      </div>
    `;
  }

  // Open document modal (Add or Edit)
  function openDocumentModal(mode, id = null) {
    const modal = document.getElementById('documentModal');
    const form = document.getElementById('documentForm');
    const formMethod = document.getElementById('documentFormMethod');
    const deleteBtn = document.getElementById('documentDeleteBtnModal');
    const title = document.getElementById('documentModalTitle');
    
    if (mode === 'add') {
      currentDocumentId = null;
      title.textContent = 'Add Document';
      form.action = '{{ route("documents.store") }}';
      formMethod.innerHTML = '';
      if (deleteBtn) deleteBtn.style.display = 'none';
      form.reset();
    } else if (id) {
      // Load document data for edit
      fetch(`/documents/${id}/edit`, {
        headers: {
          'Accept': 'application/json',
          'X-Requested-With': 'XMLHttpRequest'
        }
      })
      .then(res => res.json())
      .then(doc => {
        currentDocumentId = id;
        title.textContent = 'Edit Document';
        form.action = `/documents/${id}`;
        formMethod.innerHTML = '<input type="hidden" name="_method" value="PUT">';
        if (deleteBtn) deleteBtn.style.display = 'inline-block';
        
        // Populate form fields
        document.getElementById('tied_to').value = doc.tied_to || '';
        document.getElementById('name').value = doc.name || '';
        document.getElementById('group').value = doc.group || '';
        document.getElementById('type').value = doc.type || '';
        document.getElementById('date_added').value = doc.date_added ? doc.date_added.substring(0, 10) : '';
        document.getElementById('year').value = doc.year || '';
        document.getElementById('notes').value = doc.notes || '';
        
        // Show modal
        modal.classList.add('show');
        document.body.style.overflow = 'hidden';
      })
      .catch(e => {
        console.error(e);
        alert('Error loading document data');
      });
      return;
    }
    
    // Show modal for add mode
    modal.classList.add('show');
    document.body.style.overflow = 'hidden';
  }

  function closeDocumentModal() {
    const modal = document.getElementById('documentModal');
    modal.classList.remove('show');
    document.body.style.overflow = '';
    currentDocumentId = null;
  }

  // Add Document Button
  document.getElementById('addDocumentBtn').addEventListener('click', () => openDocumentModal('add'));
  document.getElementById('columnBtn2').addEventListener('click', () => openColumnModal());

  // Legacy functions for backward compatibility
  async function openDocumentPage(mode) {
    if (mode === 'add') {
      openDocumentModal('add');
    } else {
      if (currentDocumentId) {
        openDocumentModal('edit', currentDocumentId);
      }
    }
  }

  async function openEditDocument(id) {
    openDocumentModal('edit', id);
  }

  function openDocumentForm(mode, doc = null) {
    // Clone form from modal
    const modalForm = document.getElementById('documentModal').querySelector('form');
    const pageForm = document.getElementById('documentPageForm');
    const formContentDiv = pageForm.querySelector('div[style*="padding:12px"]');
    
    // Clone the modal form body
    const modalBody = modalForm.querySelector('.modal-body');
    if (modalBody && formContentDiv) {
      formContentDiv.innerHTML = modalBody.innerHTML;
    }

    const formMethod = document.getElementById('documentPageFormMethod');
    const deleteBtn = document.getElementById('documentDeleteBtn');
    const editBtn = document.getElementById('editDocumentFromPageBtn');
    const closeBtn = document.getElementById('closeDocumentPageBtn');
    const closeFormBtn = document.getElementById('closeDocumentFormBtn');

    if (mode === 'add') {
      document.getElementById('documentPageTitle').textContent = 'Add Document';
      document.getElementById('documentPageName').textContent = '';
      pageForm.action = '{{ route("documents.store") }}';
      formMethod.innerHTML = '';
      deleteBtn.style.display = 'none';
      if (editBtn) editBtn.style.display = 'none';
      if (closeBtn) closeBtn.style.display = 'inline-block';
      if (closeFormBtn) closeFormBtn.style.display = 'none';
      pageForm.reset();
    } else {
      const documentName = doc.name || doc.doc_id || 'Unknown';
      document.getElementById('documentPageTitle').textContent = 'Edit Document';
      document.getElementById('documentPageName').textContent = documentName;
      pageForm.action = `/documents/${currentDocumentId}`;
      const methodInput = document.createElement('input');
      methodInput.type = 'hidden';
      methodInput.name = '_method';
      methodInput.value = 'PUT';
      formMethod.innerHTML = '';
      formMethod.appendChild(methodInput);
      deleteBtn.style.display = 'inline-block';
      if (editBtn) editBtn.style.display = 'none';
      if (closeBtn) closeBtn.style.display = 'none';
      if (closeFormBtn) closeFormBtn.style.display = 'inline-block';

      const fields = ['tied_to','name','group','type','date_added','year','notes'];
      fields.forEach(k => {
        const el = formContentDiv ? formContentDiv.querySelector(`#${k}`) : null;
        if (!el) return;
        if (el.type === 'date') {
          el.value = doc[k] ? (typeof doc[k] === 'string' ? doc[k].substring(0,10) : doc[k]) : '';
        } else if (el.tagName === 'TEXTAREA') {
          el.value = doc[k] ?? '';
        } else {
          el.value = doc[k] ?? '';
        }
      });
    }

    // Hide table view, show page view
    document.getElementById('clientsTableView').classList.add('hidden');
    const documentPageView = document.getElementById('documentPageView');
    documentPageView.style.display = 'block';
    documentPageView.classList.add('show');
    document.getElementById('documentDetailsPageContent').style.display = 'none';
    document.getElementById('documentFormPageContent').style.display = 'block';
  }

  function closeDocumentPageView() {
    const documentPageView = document.getElementById('documentPageView');
    documentPageView.classList.remove('show');
    documentPageView.style.display = 'none';
    document.getElementById('clientsTableView').classList.remove('hidden');
    document.getElementById('documentDetailsPageContent').style.display = 'none';
    document.getElementById('documentFormPageContent').style.display = 'none';
    currentDocumentId = null;
  }

  // Edit button from details page
  const editBtn = document.getElementById('editDocumentFromPageBtn');
  if (editBtn) {
    editBtn.addEventListener('click', function() {
      if (currentDocumentId) {
        openEditDocument(currentDocumentId);
      }
    });
  }

  // Column modal functions
  function openColumnModal() {
    document.getElementById('tableResponsive').classList.add('no-scroll');
    document.querySelectorAll('.column-checkbox').forEach(cb => {
      // Always check mandatory fields, otherwise check if in selectedColumns
      cb.checked = mandatoryColumns.includes(cb.value) || selectedColumns.includes(cb.value);
    });
    document.body.style.overflow = 'hidden';
    document.getElementById('columnModal').classList.add('show');
    // Initialize drag and drop after modal is shown
    setTimeout(initDragAndDrop, 100);
  }

  function closeColumnModal() {
    document.getElementById('tableResponsive').classList.remove('no-scroll');
    document.getElementById('columnModal').classList.remove('show');
    document.body.style.overflow = '';
  }

  function selectAllColumns() {
    document.querySelectorAll('.column-checkbox').forEach(cb => {
      cb.checked = true;
    });
  }

  function deselectAllColumns() {
    document.querySelectorAll('.column-checkbox').forEach(cb => {
      // Don't uncheck mandatory fields
      if (!mandatoryColumns.includes(cb.value)) {
        cb.checked = false;
      }
    });
  }

  function saveColumnSettings() {
    // Mandatory fields that should always be included
    const mandatoryFields = mandatoryColumns;

    // Get order from DOM - this preserves the drag and drop order
    const items = Array.from(document.querySelectorAll('#columnSelection .column-item'));
    const order = items.map(item => item.dataset.column);
    const checked = Array.from(document.querySelectorAll('.column-checkbox:checked')).map(n => n.value);

    // Ensure mandatory fields are always included
    mandatoryFields.forEach(field => {
      if (!checked.includes(field)) {
        checked.push(field);
      }
    });

    // Maintain order of checked items based on DOM order (drag and drop order)
    const orderedChecked = order.filter(col => checked.includes(col));

    const form = document.getElementById('columnForm');
    const existing = form.querySelectorAll('input[name="columns[]"]');
    existing.forEach(e => e.remove());

    // Add columns in the order they appear in the DOM (after drag and drop)
    orderedChecked.forEach(c => {
      const i = document.createElement('input');
      i.type = 'hidden';
      i.name = 'columns[]';
      i.value = c;
      form.appendChild(i);
    });

    form.submit();
  }

  function deleteDocument() {
    if (!currentDocumentId) return;
    if (!confirm('Delete this document?')) return;
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = `/documents/${currentDocumentId}`;
    const csrf = document.createElement('input');
    csrf.type = 'hidden';
    csrf.name = '_token';
    csrf.value = '{{ csrf_token() }}';
    form.appendChild(csrf);
    const method = document.createElement('input');
    method.type = 'hidden';
    method.name = '_method';
    method.value = 'DELETE';
    form.appendChild(method);
    document.body.appendChild(form);
    form.submit();
  }

  function deleteDocumentFromTable(id) {
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = `/documents/${id}`;
    const csrf = document.createElement('input');
    csrf.type = 'hidden';
    csrf.name = '_token';
    csrf.value = '{{ csrf_token() }}';
    form.appendChild(csrf);
    const method = document.createElement('input');
    method.type = 'hidden';
    method.name = '_method';
    method.value = 'DELETE';
    form.appendChild(method);
    document.body.appendChild(form);
    form.submit();
  }

  // Handle form submission
  document.getElementById('documentForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const form = this;
    const formData = new FormData(form);
    const isEdit = form.action.includes('/documents/') && form.action !== '{{ route("documents.store") }}';
    
    if (isEdit) {
      formData.append('_method', 'PUT');
    }
    
    try {
      const response = await fetch(form.action, {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}',
          'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
      });
      
      if (response.ok) {
        const result = await response.json();
        if (result.success || response.status === 200) {
          alert(isEdit ? 'Document updated successfully!' : 'Document created successfully!');
          closeDocumentModal();
          location.reload();
        } else {
          alert('Error: ' + (result.message || 'Unknown error'));
        }
      } else {
        const errorData = await response.json();
        if (errorData.errors) {
          let errorMsg = 'Validation errors:\n';
          Object.keys(errorData.errors).forEach(key => {
            errorMsg += errorData.errors[key][0] + '\n';
          });
          alert(errorMsg);
        } else {
          alert('Error saving document: ' + (errorData.message || 'Unknown error'));
        }
      }
    } catch (error) {
      console.error('Error:', error);
      alert('Error saving document: ' + error.message);
    }
  });

  // Close modals on ESC key
  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
      closeDocumentModal();
      closeDocumentDetailsModal();
    }
  });

  // Close modals when clicking outside
  document.getElementById('documentModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
      closeDocumentModal();
    }
  });

  document.getElementById('documentDetailsModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
      closeDocumentDetailsModal();
    }
  });

  let draggedElement = null;
  let dragOverElement = null;

  // Initialize drag and drop when column modal opens
  let dragInitialized = false;

  function initDragAndDrop() {
    const columnSelection = document.getElementById('columnSelection');
    if (!columnSelection) return;

    // Only initialize once to avoid duplicate event listeners
    if (dragInitialized) {
      // Re-enable draggable on all items
      const columnItems = columnSelection.querySelectorAll('.column-item');
      columnItems.forEach(item => {
        item.setAttribute('draggable', 'true');
      });
      return;
    }

    // Make all column items draggable
    const columnItems = columnSelection.querySelectorAll('.column-item');

    columnItems.forEach(item => {
      // Ensure draggable attribute is set
      item.setAttribute('draggable', 'true');
      item.style.cursor = 'move';

      // Drag start
      item.addEventListener('dragstart', function(e) {
        draggedElement = this;
        this.classList.add('dragging');
        e.dataTransfer.effectAllowed = 'move';
        e.dataTransfer.setData('text/plain', ''); // Required for Firefox
        // Create a ghost image
        const dragImage = this.cloneNode(true);
        dragImage.style.opacity = '0.5';
        document.body.appendChild(dragImage);
        e.dataTransfer.setDragImage(dragImage, 0, 0);
        setTimeout(() => {
          if (document.body.contains(dragImage)) {
            document.body.removeChild(dragImage);
          }
        }, 0);
      });

      // Drag end
      item.addEventListener('dragend', function(e) {
        this.classList.remove('dragging');
        if (dragOverElement) {
          dragOverElement.classList.remove('drag-over');
          dragOverElement = null;
        }
        draggedElement = null;
      });

      // Drag over
      item.addEventListener('dragover', function(e) {
        e.preventDefault();
        e.dataTransfer.dropEffect = 'move';

        if (draggedElement && this !== draggedElement) {
          if (dragOverElement && dragOverElement !== this) {
            dragOverElement.classList.remove('drag-over');
          }

          this.classList.add('drag-over');
          dragOverElement = this;

          const rect = this.getBoundingClientRect();
          const next = (e.clientY - rect.top) / (rect.bottom - rect.top) > 0.5;

          if (next) {
            if (this.nextSibling && this.nextSibling !== draggedElement) {
              this.parentNode.insertBefore(draggedElement, this.nextSibling);
            } else if (!this.nextSibling) {
              this.parentNode.appendChild(draggedElement);
            }
          } else {
            if (this.previousSibling !== draggedElement) {
              this.parentNode.insertBefore(draggedElement, this);
            }
          }
        }
      });

      // Drag leave
      item.addEventListener('dragleave', function(e) {
        if (!this.contains(e.relatedTarget)) {
          this.classList.remove('drag-over');
          if (dragOverElement === this) {
            dragOverElement = null;
          }
        }
      });

      // Drop
      item.addEventListener('drop', function(e) {
        e.preventDefault();
        e.stopPropagation();
        this.classList.remove('drag-over');
        dragOverElement = null;
        return false;
      });
    });

    dragInitialized = true;
  }
</script>

@include('partials.table-scripts', [
  'mandatoryColumns' => $mandatoryColumns,
])

@endsection
