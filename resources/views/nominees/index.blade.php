@extends('layouts.app')
@section('content')

@include('partials.table-styles')

@php
  $config = \App\Helpers\TableConfigHelper::getConfig('nominees');
  $selectedColumns = \App\Helpers\TableConfigHelper::getSelectedColumns('nominees');
  // Ensure $selectedColumns is always an array
  if (!is_array($selectedColumns)) {
    $selectedColumns = $config['default_columns'] ?? [];
  }
  $columnDefinitions = $config['column_definitions'] ?? [];
  $mandatoryColumns = $config['mandatory_columns'] ?? [];
@endphp

<style>
  .nominee-checkbox {
    width: 18px;
    height: 18px;
    accent-color: #f3742a;
    cursor: pointer;
  }
</style>

<div class="dashboard">
  <div class="container-table">
    <!-- Nominees Card -->
    <div style="background:#fff; border:1px solid #ddd; border-radius:4px; overflow:hidden; margin-bottom:15px;">
      <div class="page-header" style="background:#fff; border-bottom:1px solid #ddd; margin-bottom:0;">
        <div class="page-title-section">
          <h3>
            @if($policy)
              {{ $policy->policy_no }} - 
            @endif
            Nominees
          </h3>
          <div class="records-found">Records Found - {{ $nominees->total() }}</div>
        </div>
        <div class="action-buttons">
          <button class="btn" onclick="removeSelectedNominees()" style="background:#dc3545; color:#fff; border:none; padding:6px 16px; border-radius:2px; cursor:pointer; font-size:13px;">Remove</button>
          <button class="btn btn-add" onclick="openNomineeDialog()">Add</button>
          <a href="{{ $policy ? route('policies.show', $policy->id) : route('policies.index') }}" class="btn" style="background:#6c757d; color:#fff; border:none; padding:6px 16px; border-radius:2px; cursor:pointer; text-decoration:none; font-size:13px;">Back</a>
        </div>
      </div>

      @if(session('success') || request()->get('success'))
        <div class="alert alert-success" id="successAlert" style="padding:8px 12px; margin:15px 20px; border:1px solid #c3e6cb; background:#d4edda; color:#155724;">
          {{ session('success') ?? request()->get('success') }}
          <button type="button" class="alert-close" onclick="document.getElementById('successAlert').style.display='none'" style="float:right;background:none;border:none;font-size:16px;cursor:pointer;">×</button>
        </div>
      @endif

      <div class="table-responsive" id="tableResponsive">
        <table id="nomineesTable">
          <thead>
            <tr>
            <th style="text-align:center;">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="display:inline-block; vertical-align:middle;">
                <path d="M12 2C8.13 2 5 5.13 5 9C5 14.25 2 16 2 16H22C22 16 19 14.25 19 9C19 5.13 15.87 2 12 2Z" fill="#fff" stroke="#fff" stroke-width="1.5"/>
                <path d="M9 21C9 22.1 9.9 23 11 23H13C14.1 23 15 22.1 15 21H9Z" fill="#fff"/>
              </svg>
            </th>
              <th style="text-align:center; width:50px;">
                <input type="checkbox" class="nominee-checkbox" id="selectAllNominees" onchange="toggleAllNominees(this)">
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
            @forelse($nominees as $nominee)
              @php
                $age = $nominee->date_of_birth ? \Carbon\Carbon::parse($nominee->date_of_birth)->age : null;
              @endphp
              <tr>
                <td class="bell-cell {{ $nominee->date_removed ? 'expired' : '' }}">
                  <div style="display:flex; align-items:center; justify-content:center;">
                    <div class="status-indicator {{ $nominee->date_removed ? 'expired' : 'normal' }}" style="width:18px; height:18px; border-radius:50%; border:2px solid {{ $nominee->date_removed ? '#000' : '#f3742a' }}; background-color:{{ $nominee->date_removed ? '#000' : 'transparent' }};"></div>
                  </div>
                </td>
                <td style="text-align:center;">
                  <input type="checkbox" name="selected_nominees[]" value="{{ $nominee->id }}" class="nominee-checkbox">
                </td>
                <td class="action-cell">
                  <svg class="action-expand" onclick="editNominee({{ $nominee->id }})" width="22" height="22" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="cursor:pointer; vertical-align:middle;">
                    <rect x="9" y="9" width="6" height="6" stroke="#2d2d2d" stroke-width="1.5" fill="none"/>
                    <path d="M12 9L12 5M12 15L12 19M9 12L5 12M15 12L19 12" stroke="#2d2d2d" stroke-width="1.5" stroke-linecap="round"/>
                    <path d="M12 5L10 7M12 5L14 7M12 19L10 17M12 19L14 17M5 12L7 10M5 12L7 14M19 12L17 10M19 12L17 14" stroke="#2d2d2d" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                  </svg>
                </td>
                @foreach($selectedColumns as $col)
                  @if($col == 'full_name')
                    <td data-column="full_name">{{ $nominee->full_name }}</td>
                  @elseif($col == 'date_of_birth')
                    <td data-column="date_of_birth">{{ $nominee->date_of_birth ? \Carbon\Carbon::parse($nominee->date_of_birth)->format('d-M-y') : '—' }}</td>
                  @elseif($col == 'age')
                    <td data-column="age">{{ $age ?? '—' }}</td>
                  @elseif($col == 'nin_passport_no')
                    <td data-column="nin_passport_no">{{ $nominee->nin_passport_no ?? '—' }}</td>
                  @elseif($col == 'relationship')
                    <td data-column="relationship">{{ $nominee->relationship ?? '—' }}</td>
                  @elseif($col == 'share_percentage')
                    <td data-column="share_percentage">{{ $nominee->share_percentage ? number_format($nominee->share_percentage, 2) . '%' : '—' }}</td>
                  @elseif($col == 'date_added')
                    <td data-column="date_added">{{ $nominee->created_at ? \Carbon\Carbon::parse($nominee->created_at)->format('d-M-y') : '—' }}</td>
                  @elseif($col == 'date_removed')
                    <td data-column="date_removed">{{ $nominee->date_removed ? \Carbon\Carbon::parse($nominee->date_removed)->format('d-M-y') : '—' }}</td>
                  @elseif($col == 'notes')
                    <td data-column="notes">{{ $nominee->notes ?? '—' }}</td>
                  @elseif($col == 'nominee_code')
                    <td data-column="nominee_code">{{ $nominee->nominee_code ?? '—' }}</td>
                  @endif
                @endforeach
              </tr>
            @empty
              <tr>
                <td colspan="{{ count($selectedColumns) + 2 }}" style="text-align:center; padding:20px; color:#999;">No nominees found</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      <div class="footer">
        <div class="footer-left">
          <a class="btn btn-export" href="{{ route('nominees.export', $policyId ? ['policy_id' => $policyId] : []) }}">Export</a>
          <button class="btn btn-column" id="columnBtn" type="button">Column</button>
          <button class="btn btn-export" id="printBtn" type="button" style="margin-left:10px;">Print</button>
        </div>
        <div class="paginator">
          @php
            $base = url()->current();
            $q = request()->query();
            $current = $nominees->currentPage();
            $last = max(1, $nominees->lastPage());
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

    <!-- Documents Card -->
    <div style="background:#fff; border:1px solid #ddd; border-radius:4px; overflow:hidden; margin-bottom:15px;">
      <div style="display:flex; justify-content:space-between; align-items:center; padding:12px 15px; border-bottom:1px solid #ddd;">
        <h4 style="margin:0; font-size:16px; font-weight:600;">Documents</h4>
        <button class="btn btn-add" onclick="openDocumentUpload()" style="background:#f3742a; color:#fff; border:none; padding:6px 16px; border-radius:2px; cursor:pointer; font-size:13px;">Add Document</button>
      </div>
      <div id="documentsContent" style="display:flex; gap:10px; flex-wrap:wrap; padding:15px; min-height:100px;">
        <!-- Documents will be loaded here -->
        <div style="color:#999; font-size:12px;">No documents uploaded</div>
      </div>
    </div>
  </div>
</div>

<!-- Nominee Dialog Modal -->
<div class="modal" id="nomineeModal" style="display:none;" onclick="if(event.target === this) closeNomineeDialog();">
  <div class="modal-content" style="max-width:500px;" onclick="event.stopPropagation();">
    <div class="modal-header" style="display:flex; justify-content:space-between; align-items:center;">
      <h4 style="margin:0;" id="nomineeModalTitle">Add Nominee</h4>
      <div style="display:flex; gap:8px; align-items:center;">
        <button type="button" onclick="saveNominee()" style="background:#f3742a; color:#fff; border:none; padding:6px 20px; border-radius:2px; cursor:pointer; font-size:12px; font-weight:500;">Save</button>
        <button type="button" onclick="closeNomineeDialog()" style="background:#000; color:#fff; border:none; padding:6px 20px; border-radius:2px; cursor:pointer; font-size:12px; font-weight:500;">Cancel</button>
      </div>
    </div>
      <form id="nomineeForm">
      <input type="hidden" name="nominee_id" id="nominee_id">
      <input type="hidden" name="policy_id" id="nominee_policy_id" value="{{ $policy->id ?? '' }}">
      <div class="modal-body" style="padding:20px;">
        <div class="form-group" style="margin-bottom:15px;">
          <label style="display:block; margin-bottom:5px; font-weight:600; font-size:12px;">Full Name</label>
          <input type="text" name="full_name" id="nominee_full_name" class="form-control" required style="padding:6px; font-size:12px;">
        </div>
        <div class="form-row" style="display:grid; grid-template-columns:1fr 1fr; gap:15px; margin-bottom:15px;">
          <div class="form-group">
            <label style="display:block; margin-bottom:5px; font-weight:600; font-size:12px;">Date Of Birth</label>
            <input type="date" name="date_of_birth" id="nominee_date_of_birth" class="form-control" style="padding:6px; font-size:12px;">
          </div>
          <div class="form-group">
            <label style="display:block; margin-bottom:5px; font-weight:600; font-size:12px;">NIN/Passport No</label>
            <input type="text" name="nin_passport_no" id="nominee_nin_passport_no" class="form-control" style="padding:6px; font-size:12px;">
          </div>
          <div class="form-group">
            <label style="display:block; margin-bottom:5px; font-weight:600; font-size:12px;">Relationship</label>
            <input type="text" name="relationship" id="nominee_relationship" class="form-control" style="padding:6px; font-size:12px;">
          </div>
          <div class="form-group">
            <label style="display:block; margin-bottom:5px; font-weight:600; font-size:12px;">Share</label>
            <input type="number" step="0.01" name="share_percentage" id="nominee_share_percentage" class="form-control" style="padding:6px; font-size:12px;" placeholder="%">
          </div>
          <div class="form-group">
            <label style="display:block; margin-bottom:5px; font-weight:600; font-size:12px;">Date Removed</label>
            <input type="date" name="date_removed" id="nominee_date_removed" class="form-control" style="padding:6px; font-size:12px;">
          </div>
        </div>
        <div class="form-group" style="margin-bottom:15px;">
          <label style="display:block; margin-bottom:5px; font-weight:600; font-size:12px;">Notes</label>
          <textarea name="notes" id="nominee_notes" class="form-control" rows="3" style="padding:6px; font-size:12px;"></textarea>
        </div>
      </div>
      <div class="modal-footer" style="display:flex; gap:8px; justify-content:flex-end; padding:15px 20px; border-top:1px solid #ddd;">
        <button type="button" class="btn-save" onclick="saveNomineeAndAddAnother()" style="background:#f3742a; color:#fff; border:none; padding:6px 20px; border-radius:2px; cursor:pointer; font-size:12px;">Upload ID</button>
        <button type="button" class="btn-save" onclick="saveNomineeAndAddAnother()" style="background:#f3742a; color:#fff; border:none; padding:6px 20px; border-radius:2px; cursor:pointer; font-size:12px;">Add Another</button>
      </div>
    </form>
  </div>
</div>

<script>
  let currentNomineeId = null;
  const policyId = @json($policyId ?? null);

  function openNomineeDialog() {
    currentNomineeId = null;
    document.getElementById('nomineeModalTitle').textContent = 'Add Nominee';
    document.getElementById('nomineeForm').reset();
    document.getElementById('nominee_id').value = '';
    document.getElementById('nominee_policy_id').value = policyId || '';
    const modal = document.getElementById('nomineeModal');
    modal.style.display = 'flex';
    modal.classList.add('show');
  }

  function closeNomineeDialog() {
    const modal = document.getElementById('nomineeModal');
    modal.style.display = 'none';
    modal.classList.remove('show');
    document.getElementById('nomineeForm').reset();
    currentNomineeId = null;
  }

  async function editNominee(id) {
    try {
      const response = await fetch(`/nominees/${id}`, {
        headers: {
          'Accept': 'application/json',
          'X-Requested-With': 'XMLHttpRequest'
        }
      });
      const nominee = await response.json();
      
      currentNomineeId = id;
      document.getElementById('nomineeModalTitle').textContent = 'Edit Nominee';
      document.getElementById('nominee_id').value = id;
      document.getElementById('nominee_full_name').value = nominee.full_name || '';
      document.getElementById('nominee_date_of_birth').value = nominee.date_of_birth || '';
      document.getElementById('nominee_nin_passport_no').value = nominee.nin_passport_no || '';
      document.getElementById('nominee_relationship').value = nominee.relationship || '';
      document.getElementById('nominee_share_percentage').value = nominee.share_percentage || '';
      document.getElementById('nominee_date_removed').value = nominee.date_removed || '';
      document.getElementById('nominee_notes').value = nominee.notes || '';
      document.getElementById('nominee_policy_id').value = nominee.policy_id || (policyId || '');
      
      const modal = document.getElementById('nomineeModal');
      modal.style.display = 'flex';
      modal.classList.add('show');
    } catch (error) {
      console.error('Error loading nominee:', error);
      alert('Error loading nominee details');
    }
  }

  async function saveNominee(addAnother = false) {
    const form = document.getElementById('nomineeForm');
    
    // Validate form before submission
    if (!form.checkValidity()) {
      form.reportValidity();
      return;
    }
    
    // Build form data explicitly to ensure all fields are included
    const formData = new FormData();
    
    // Get all form field values
    const fullName = document.getElementById('nominee_full_name').value;
    if (!fullName || fullName.trim() === '') {
      alert('Full Name is required');
      document.getElementById('nominee_full_name').focus();
      return;
    }
    
    // Add all form fields explicitly
    formData.append('full_name', fullName);
    formData.append('date_of_birth', document.getElementById('nominee_date_of_birth').value || '');
    formData.append('nin_passport_no', document.getElementById('nominee_nin_passport_no').value || '');
    formData.append('relationship', document.getElementById('nominee_relationship').value || '');
    formData.append('share_percentage', document.getElementById('nominee_share_percentage').value || '');
    formData.append('date_removed', document.getElementById('nominee_date_removed').value || '');
    formData.append('notes', document.getElementById('nominee_notes').value || '');
    
    // Add policy_id if it exists
    const policyIdValue = document.getElementById('nominee_policy_id').value;
    if (policyIdValue) {
      formData.append('policy_id', policyIdValue);
    }
    
    // Always use POST method, Laravel will handle method spoofing
    const url = currentNomineeId 
      ? `/nominees/${currentNomineeId}`
      : '/nominees';
    const method = 'POST';

    // Add CSRF token
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
    formData.append('_token', csrfToken);
    
    // Add method spoofing for PUT requests
    if (currentNomineeId) {
      formData.append('_method', 'PUT');
    }

    try {
      const response = await fetch(url, {
        method: method,
        headers: {
          'X-CSRF-TOKEN': csrfToken,
          'Accept': 'application/json',
          'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
      });

      const data = await response.json();

      if (response.ok && data.success) {
        if (!addAnother) {
          closeNomineeDialog();
          window.location.reload();
        } else {
          form.reset();
          document.getElementById('nominee_policy_id').value = policyId || '';
          currentNomineeId = null;
          document.getElementById('nomineeModalTitle').textContent = 'Add Nominee';
        }
      } else {
        // Handle validation errors
        if (data.errors) {
          const errorMessages = Object.values(data.errors).flat().join('\n');
          alert('Validation errors:\n' + errorMessages);
        } else if (data.message) {
          alert('Error: ' + data.message);
        } else {
          alert('Error: Failed to save nominee');
        }
      }
    } catch (error) {
      console.error('Error:', error);
      alert('Error saving nominee: ' + error.message);
    }
  }

  function saveNomineeAndAddAnother() {
    saveNominee(true);
  }

  function toggleAllNominees(checkbox) {
    const checkboxes = document.querySelectorAll('input[name="selected_nominees[]"]');
    checkboxes.forEach(cb => cb.checked = checkbox.checked);
  }

  async function removeSelectedNominees() {
    const selected = document.querySelectorAll('input[name="selected_nominees[]"]:checked');
    if (selected.length === 0) {
      alert('Please select at least one nominee to remove');
      return;
    }

    const count = selected.length;
    const message = count === 1 
      ? 'Are you sure you want to remove this nominee?'
      : `Are you sure you want to remove ${count} nominees?`;
    
    if (!confirm(message)) {
      return;
    }

    const nomineeIds = Array.from(selected).map(cb => cb.value);
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
    
    // Delete nominees one by one
    let successCount = 0;
    let errorCount = 0;
    const errors = [];
    
    for (const nomineeId of nomineeIds) {
      try {
        const response = await fetch(`/nominees/${nomineeId}`, {
          method: 'DELETE',
          headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
          }
        });

        if (response.ok) {
          const data = await response.json();
          if (data.success) {
            successCount++;
          } else {
            errorCount++;
            errors.push(`Nominee ${nomineeId}: ${data.message || 'Unknown error'}`);
          }
        } else {
          errorCount++;
          const errorData = await response.json().catch(() => ({}));
          errors.push(`Nominee ${nomineeId}: ${errorData.message || 'HTTP ' + response.status}`);
        }
      } catch (error) {
        console.error('Error removing nominee:', error);
        errorCount++;
        errors.push(`Nominee ${nomineeId}: ${error.message || 'Network error'}`);
      }
    }

    // Build success message with policy_id for redirect
    const policyId = @json($policyId ?? null);
    let redirectUrl = '/nominees';
    if (policyId) {
      redirectUrl += '?policy_id=' + policyId;
    }
    
    if (successCount > 0) {
      // Add success message to URL
      const message = successCount === 1 
        ? 'Nominee deleted successfully.'
        : `${successCount} nominee(s) deleted successfully.`;
      redirectUrl += (policyId ? '&' : '?') + 'success=' + encodeURIComponent(message);
    }

    if (errorCount === 0) {
      // All successful
      window.location.href = redirectUrl;
    } else if (successCount > 0) {
      // Some successful, some failed
      alert(`Removed ${successCount} nominee(s). ${errorCount} error(s) occurred.\n\nErrors:\n${errors.join('\n')}`);
      window.location.href = redirectUrl;
    } else {
      // All failed
      alert(`Failed to remove nominees. ${errorCount} error(s) occurred.\n\nErrors:\n${errors.join('\n')}`);
    }
  }

  function openDocumentUpload() {
    const modal = document.getElementById('documentUploadModal');
    if (modal) {
      modal.style.display = 'flex';
      modal.classList.add('show');
      document.getElementById('documentUploadForm').reset();
    }
  }

  function closeDocumentUploadModal() {
    const modal = document.getElementById('documentUploadModal');
    if (modal) {
      modal.style.display = 'none';
      modal.classList.remove('show');
    }
  }

  async function loadDocuments() {
    const policyId = @json($policyId ?? null);
    const documentsContent = document.getElementById('documentsContent');
    if (!documentsContent) return;

    try {
      const params = new URLSearchParams();
      if (policyId) {
        params.append('policy_id', policyId);
      }

      const response = await fetch(`/nominees/documents?${params.toString()}`, {
        headers: {
          'Accept': 'application/json',
          'X-Requested-With': 'XMLHttpRequest'
        }
      });

      const data = await response.json();
      
      if (data.success && data.documents) {
        displayDocuments(data.documents);
      } else {
        documentsContent.innerHTML = '<div style="color:#999; font-size:12px;">No documents uploaded</div>';
      }
    } catch (error) {
      console.error('Error loading documents:', error);
      documentsContent.innerHTML = '<div style="color:#999; font-size:12px;">Error loading documents</div>';
    }
  }

  function displayDocuments(documents) {
    const documentsContent = document.getElementById('documentsContent');
    if (!documentsContent) return;

    if (documents.length === 0) {
      documentsContent.innerHTML = '<div style="color:#999; font-size:12px;">No documents uploaded</div>';
      return;
    }

    const documentsHTML = documents.map(doc => {
      const fileUrl = doc.file_path ? `/storage/${doc.file_path}` : '#';
      const icon = getFileIcon(doc.format);
      const dateAdded = doc.date_added ? new Date(doc.date_added).toLocaleDateString() : 'N/A';
      
      return `
        <div style="background:#f8f9fa; border:1px solid #ddd; border-radius:4px; padding:10px; min-width:150px; max-width:200px;">
          <div style="display:flex; align-items:center; gap:8px; margin-bottom:8px;">
            <span style="font-size:20px;">${icon}</span>
            <div style="flex:1; min-width:0;">
              <div style="font-weight:600; font-size:12px; color:#333; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;" title="${doc.name}">${doc.name}</div>
              <div style="font-size:10px; color:#666;">${doc.doc_id}</div>
            </div>
          </div>
          <div style="font-size:10px; color:#666; margin-bottom:8px;">
            <div>Date: ${dateAdded}</div>
            ${doc.format ? `<div>Format: ${doc.format.toUpperCase()}</div>` : ''}
          </div>
          <div style="display:flex; gap:5px;">
            <a href="${fileUrl}" target="_blank" style="flex:1; background:#f3742a; color:#fff; border:none; padding:4px 8px; border-radius:2px; cursor:pointer; text-decoration:none; font-size:11px; text-align:center;">View</a>
            <button onclick="deleteDocument(${doc.id})" style="background:#dc3545; color:#fff; border:none; padding:4px 8px; border-radius:2px; cursor:pointer; font-size:11px;">Delete</button>
          </div>
        </div>
      `;
    }).join('');

    documentsContent.innerHTML = documentsHTML;
  }

  function getFileIcon(format) {
    const icons = {
      'pdf': '📄',
      'doc': '📝',
      'docx': '📝',
      'jpg': '🖼️',
      'jpeg': '🖼️',
      'png': '🖼️',
      'xls': '📊',
      'xlsx': '📊'
    };
    return icons[format?.toLowerCase()] || '📎';
  }

  async function uploadDocument() {
    const form = document.getElementById('documentUploadForm');
    const formData = new FormData(form);
    
    const policyId = @json($policyId ?? null);
    if (policyId) {
      formData.append('policy_id', policyId);
    }

    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
    formData.append('_token', csrfToken);

    try {
      const response = await fetch('/nominees/upload-document', {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': csrfToken,
          'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
      });

      const data = await response.json();

      if (data.success) {
        closeDocumentUploadModal();
        await loadDocuments();
        alert('Document uploaded successfully.');
      } else {
        alert('Error: ' + (data.message || 'Failed to upload document'));
      }
    } catch (error) {
      console.error('Error uploading document:', error);
      alert('Error uploading document: ' + error.message);
    }
  }

  async function deleteDocument(documentId) {
    if (!confirm('Are you sure you want to delete this document?')) {
      return;
    }

    try {
      const response = await fetch(`/documents/${documentId}`, {
        method: 'DELETE',
        headers: {
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
          'Accept': 'application/json',
          'X-Requested-With': 'XMLHttpRequest'
        }
      });

      if (response.ok) {
        await loadDocuments();
        alert('Document deleted successfully.');
      } else {
        alert('Error deleting document');
      }
    } catch (error) {
      console.error('Error deleting document:', error);
      alert('Error deleting document: ' + error.message);
    }
  }

  // Load documents on page load
  document.addEventListener('DOMContentLoaded', function() {
    loadDocuments();
  });

  // Print function
  function printTable() {
    const table = document.getElementById('nomineesTable');
    if (!table) return;
    
    // Get table headers - preserve order
    const headers = [];
    const headerCells = table.querySelectorAll('thead th');
    headerCells.forEach(th => {
      let headerText = '';
      const clone = th.cloneNode(true);
      const filterInput = clone.querySelector('.column-filter');
      if (filterInput) filterInput.remove();
      headerText = clone.textContent.trim();
      // Handle bell icon column
      if (clone.querySelector('svg')) {
        headerText = '🔔';
      }
      if (headerText) {
        headers.push(headerText);
      }
    });
    
    // Get table rows data
    const rows = [];
    const tableRows = table.querySelectorAll('tbody tr:not([style*="display: none"])');
    tableRows.forEach(row => {
      if (row.style.display === 'none') return;
      
      const cells = [];
      const rowCells = row.querySelectorAll('td');
      rowCells.forEach((cell) => {
        let cellContent = '';
        
        // Handle checkbox column (for selection)
        if (cell.querySelector('input[type="checkbox"][name="selected_nominees[]"]')) {
          const checkbox = cell.querySelector('input[type="checkbox"]');
          cellContent = checkbox && checkbox.checked ? '✓' : '';
        } 
        // Handle action column
        else if (cell.classList.contains('action-cell')) {
          const expandIcon = cell.querySelector('.action-expand');
          if (expandIcon) cellContent = '⤢';
        } 
        // Handle regular cells
        else {
          const link = cell.querySelector('a');
          if (link) {
            cellContent = link.textContent.trim();
          } else {
            cellContent = cell.textContent.trim();
          }
        }
        
        cells.push(cellContent || '-');
      });
      rows.push(cells);
    });
    
    // Escape HTML to prevent XSS
    function escapeHtml(text) {
      if (!text) return '';
      const div = document.createElement('div');
      div.textContent = text;
      return div.innerHTML;
    }
    
    // Build headers HTML
    const headersHTML = headers.map(h => '<th>' + escapeHtml(h) + '</th>').join('');
    
    // Build rows HTML
    const rowsHTML = rows.map(row => {
      const cellsHTML = row.map(cell => {
        const cellText = escapeHtml(String(cell || '-'));
        return '<td>' + cellText + '</td>';
      }).join('');
      return '<tr>' + cellsHTML + '</tr>';
    }).join('');
    
    // Create print window
    const printWindow = window.open('', '_blank', 'width=800,height=600');
    
    const printHTML = '<!DOCTYPE html>' +
      '<html>' +
      '<head>' +
      '<title>Nominees - Print</title>' +
      '<style>' +
      '@page { margin: 1cm; size: A4 landscape; }' +
      'html, body { margin: 0; padding: 0; background: #fff !important; }' +
      'body { font-family: Arial, sans-serif; font-size: 10px; }' +
      'table { width: 100%; border-collapse: collapse; page-break-inside: auto; }' +
      'thead { display: table-header-group; }' +
      'thead th { background-color: #000 !important; color: #fff !important; padding: 8px 5px; text-align: left; border: 1px solid #333; font-weight: normal; -webkit-print-color-adjust: exact; print-color-adjust: exact; }' +
      'tbody tr { page-break-inside: avoid; border-bottom: 1px solid #ddd; }' +
      'tbody tr:nth-child(even) { background-color: #f8f8f8; }' +
      'tbody td { padding: 6px 5px; border: 1px solid #ddd; white-space: nowrap; }' +
      '</style>' +
      '</head>' +
      '<body>' +
      '<table>' +
      '<thead><tr>' + headersHTML + '</tr></thead>' +
      '<tbody>' + rowsHTML + '</tbody>' +
      '</table>' +
      '<scr' + 'ipt>' +
      'window.onload = function() {' +
      '  setTimeout(function() {' +
      '    window.print();' +
      '  }, 100);' +
      '};' +
      'window.onafterprint = function() {' +
      '  window.close();' +
      '};' +
      '</scr' + 'ipt>' +
      '</body>' +
      '</html>';
    
    if (printWindow) {
      printWindow.document.open();
      printWindow.document.write(printHTML);
      printWindow.document.close();
    }
  }

  // Column modal functions
  function openColumnModal() {
    const modal = document.getElementById('columnModal');
    if (modal) {
      modal.style.display = 'flex';
      modal.classList.add('show');
      initDragAndDrop();
    }
  }

  function closeColumnModal() {
    const modal = document.getElementById('columnModal');
    if (modal) {
      modal.style.display = 'none';
      modal.classList.remove('show');
    }
  }

  function selectAllColumns() {
    const checkboxes = document.querySelectorAll('#columnSelection .column-checkbox:not(:disabled)');
    checkboxes.forEach(cb => cb.checked = true);
  }

  function deselectAllColumns() {
    const checkboxes = document.querySelectorAll('#columnSelection .column-checkbox:not(:disabled)');
    checkboxes.forEach(cb => cb.checked = false);
  }

  function saveColumnSettings() {
    const form = document.getElementById('columnForm');
    const checkboxes = document.querySelectorAll('#columnSelection .column-checkbox:checked');
    const columns = Array.from(checkboxes).map(cb => cb.value);
    
    // Create hidden input for columns
    let columnsInput = document.getElementById('columnsInput');
    if (!columnsInput) {
      columnsInput = document.createElement('input');
      columnsInput.type = 'hidden';
      columnsInput.name = 'columns';
      columnsInput.id = 'columnsInput';
      form.appendChild(columnsInput);
    }
    columnsInput.value = JSON.stringify(columns);
    
    // Add policy_id if exists
    const policyId = @json($policyId ?? null);
    if (policyId) {
      let policyIdInput = document.getElementById('policyIdInput');
      if (!policyIdInput) {
        policyIdInput = document.createElement('input');
        policyIdInput.type = 'hidden';
        policyIdInput.name = 'policy_id';
        policyIdInput.id = 'policyIdInput';
        form.appendChild(policyIdInput);
      }
      policyIdInput.value = policyId;
    }
    
    form.submit();
  }

  let draggedElement = null;
  let dragOverElement = null;
  let dragInitialized = false;

  function initDragAndDrop() {
    const columnSelection = document.getElementById('columnSelection');
    if (!columnSelection) return;

    if (dragInitialized) {
      const columnItems = columnSelection.querySelectorAll('.column-item');
      columnItems.forEach(item => {
        item.setAttribute('draggable', 'true');
      });
      return;
    }

    const columnItems = columnSelection.querySelectorAll('.column-item');
    columnItems.forEach(item => {
      item.setAttribute('draggable', 'true');

      item.addEventListener('dragstart', function(e) {
        draggedElement = this;
        this.style.opacity = '0.5';
        e.dataTransfer.effectAllowed = 'move';
        e.dataTransfer.setData('text/html', this.innerHTML);
      });

      item.addEventListener('dragend', function(e) {
        this.style.opacity = '1';
        const items = columnSelection.querySelectorAll('.column-item');
        items.forEach(item => {
          item.classList.remove('drag-over');
        });
      });

      item.addEventListener('dragover', function(e) {
        if (e.preventDefault) {
          e.preventDefault();
        }
        e.dataTransfer.dropEffect = 'move';
        this.classList.add('drag-over');
        dragOverElement = this;
        return false;
      });

      item.addEventListener('dragenter', function(e) {
        this.classList.add('drag-over');
      });

      item.addEventListener('dragleave', function(e) {
        this.classList.remove('drag-over');
      });

      item.addEventListener('drop', function(e) {
        if (e.stopPropagation) {
          e.stopPropagation();
        }

        if (draggedElement !== this) {
          const allItems = Array.from(columnSelection.querySelectorAll('.column-item'));
          const draggedIndex = allItems.indexOf(draggedElement);
          const targetIndex = allItems.indexOf(this);

          if (draggedIndex < targetIndex) {
            columnSelection.insertBefore(draggedElement, this.nextSibling);
          } else {
            columnSelection.insertBefore(draggedElement, this);
          }
        }

        this.classList.remove('drag-over');
        dragOverElement = null;
        return false;
      });
    });

    dragInitialized = true;
  }

  // Add event listeners
  document.addEventListener('DOMContentLoaded', function() {
    const printBtn = document.getElementById('printBtn');
    if (printBtn) {
      printBtn.addEventListener('click', printTable);
    }

    const columnBtn = document.getElementById('columnBtn');
    if (columnBtn) {
      columnBtn.addEventListener('click', openColumnModal);
    }
  });
</script>

<!-- Column Selection Modal -->
<div class="modal" id="columnModal" style="display:none;" onclick="if(event.target === this) closeColumnModal();">
  <div class="modal-content" onclick="event.stopPropagation();">
    <div class="modal-header">
      <h4>Column Select & Sort</h4>
      <button type="button" class="modal-close" onclick="closeColumnModal()">×</button>
    </div>
    <div class="modal-body">
      <div class="column-actions">
        <button type="button" class="btn-select-all" onclick="selectAllColumns()">Select All</button>
        <button type="button" class="btn-deselect-all" onclick="deselectAllColumns()">Deselect All</button>
      </div>

      <form id="columnForm" action="{{ route('nominees.save-column-settings') }}" method="POST">
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

@include('partials.table-scripts', [
  'mandatoryColumns' => $mandatoryColumns,
])

<!-- Document Upload Modal -->
<div class="modal" id="documentUploadModal" style="display:none;" onclick="if(event.target === this) closeDocumentUploadModal();">
  <div class="modal-content" style="max-width:500px;" onclick="event.stopPropagation();">
    <div class="modal-header" style="display:flex; justify-content:space-between; align-items:center;">
      <h4 style="margin:0;">Upload Document</h4>
      <button type="button" onclick="closeDocumentUploadModal()" style="background:none; border:none; font-size:24px; cursor:pointer; color:#666;">×</button>
    </div>
    <form id="documentUploadForm" onsubmit="event.preventDefault(); uploadDocument();">
      <div class="modal-body" style="padding:20px;">
        <div class="form-group" style="margin-bottom:15px;">
          <label style="display:block; margin-bottom:5px; font-weight:600; font-size:12px;">Document Type</label>
          <select name="document_type" id="document_type" required style="width:100%; padding:6px; font-size:12px; border:1px solid #ddd; border-radius:2px;">
            <option value="">Select Type</option>
            <option value="nominee_document">Nominee Document</option>
            <option value="id_document">ID Document</option>
            <option value="other">Other Document</option>
          </select>
        </div>
        <div class="form-group" style="margin-bottom:15px;">
          <label style="display:block; margin-bottom:5px; font-weight:600; font-size:12px;">File</label>
          <input type="file" name="document" id="document" required accept=".jpg,.jpeg,.png,.pdf,.doc,.docx" style="width:100%; padding:6px; font-size:12px; border:1px solid #ddd; border-radius:2px;">
          <small style="color:#666; font-size:11px;">Max size: 5MB. Allowed: JPG, PNG, PDF, DOC, DOCX</small>
        </div>
      </div>
      <div class="modal-footer" style="display:flex; gap:8px; justify-content:flex-end; padding:15px 20px; border-top:1px solid #ddd;">
        <button type="button" onclick="closeDocumentUploadModal()" style="background:#6c757d; color:#fff; border:none; padding:6px 20px; border-radius:2px; cursor:pointer; font-size:12px;">Cancel</button>
        <button type="submit" style="background:#f3742a; color:#fff; border:none; padding:6px 20px; border-radius:2px; cursor:pointer; font-size:12px;">Upload</button>
      </div>
    </form>
  </div>
</div>

@endsection

