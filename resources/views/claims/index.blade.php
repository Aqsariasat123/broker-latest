@extends('layouts.app')
@section('content')

@include('partials.table-styles')

<style>
  /* Modal Styles */
  .modal { display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,.5); z-index:1000; align-items:center; justify-content:center; }
  .modal.show { display:flex; }
  .modal-content { background:#fff; border-radius:6px; width:90%; max-width:800px; max-height:calc(100vh - 40px); overflow:auto; box-shadow:0 4px 6px rgba(0,0,0,.1); padding:0; }
  .modal-header { padding:15px 20px; border-bottom:1px solid #ddd; display:flex; justify-content:space-between; align-items:center; background:white; }
  .modal-header h4 { margin:0; font-size:18px; font-weight:bold; color:#2d2d2d; }
  .modal-body { padding:20px; }
  .modal-footer { padding:15px 20px; border-top:1px solid #ddd; display:flex; justify-content:center; gap:10px; background:#f9f9f9; }
  .modal-close { background:none; border:none; font-size:24px; cursor:pointer; color:#666; line-height:1; padding:0; width:24px; height:24px; }
  
  /* Form Styles */
  .form-group { margin-bottom:15px; }
  .form-group label { display:block; margin-bottom:5px; font-weight:bold; font-size:13px; color:#2d2d2d; }
  .form-control { width:100%; padding:6px 10px; border:1px solid #ddd; border-radius:2px; font-size:13px; background:#f8f8f8; }
  .form-control:focus { outline:none; border-color:#007bff; background:#fff; }
  .form-control[readonly] { background-color:#f5f5f5; cursor:not-allowed; }
  textarea.form-control { min-height:100px; resize:vertical; }
  .form-row { display:grid; grid-template-columns:repeat(2, 1fr); gap:15px; margin-bottom:15px; }
  .form-row.full-width { grid-template-columns:1fr; }
  
  /* Button Styles */
  .btn-save { background:#f3742a; color:#fff; border:none; padding:8px 20px; border-radius:2px; cursor:pointer; font-size:13px; }
  .btn-cancel { background:#000; color:#fff; border:none; padding:8px 20px; border-radius:2px; cursor:pointer; font-size:13px; }
  .btn-upload { background:#f3742a; color:#fff; border:none; padding:8px 20px; border-radius:2px; cursor:pointer; font-size:13px; }
  .btn-delete { background:#dc3545; color:#fff; border:none; padding:6px 16px; border-radius:2px; cursor:pointer; font-size:13px; }
  
  /* Filter Toggle Styles */
  #filterToggle {
    appearance: none;
    -webkit-appearance: none;
    -moz-appearance: none;
    width: 50px;
    height: 24px;
    background-color: #ccc;
    border-radius: 12px;
    position: relative;
    cursor: pointer;
    transition: background-color 0.3s;
    outline: none;
  }
  
  #filterToggle:checked {
    background-color: #28a745;
  }
  
  #filterToggle::before {
    content: '';
    position: absolute;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    background-color: white;
    top: 2px;
    left: 2px;
    transition: left 0.3s;
    box-shadow: 0 2px 4px rgba(0,0,0,0.2);
  }
  
  #filterToggle:checked::before {
    left: 28px;
  }
  
  /* Bell/Checkbox Column Styles */
  .bell-cell { 
    text-align:center; 
    padding:8px 5px; 
    vertical-align:middle; 
    min-width:50px; 
  }
  .bell-cell input[type="checkbox"] {
    width: 18px;
    height: 18px;
    cursor: pointer;
    accent-color: #f3742a;
    border-radius: 3px;
    appearance: none;
    -webkit-appearance: none;
    -moz-appearance: none;
    border: 2px solid #ccc;
    background-color: #fff;
    position: relative;
    margin: 0;
  }
  .bell-cell input[type="checkbox"]:checked {
    background-color: #f3742a;
    border-color: #f3742a;
  }
  .bell-cell input[type="checkbox"]:checked::after {
    content: '✓';
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    color: #fff;
    font-size: 12px;
    font-weight: bold;
    line-height: 1;
  }
</style>

@php
  $config = \App\Helpers\TableConfigHelper::getConfig('claims');
  $selectedColumns = \App\Helpers\TableConfigHelper::getSelectedColumns('claims');
  $columnDefinitions = $config['column_definitions'] ?? [];
  $mandatoryColumns = $config['mandatory_columns'] ?? [];
@endphp

<div class="dashboard">
  <!-- Main Claims Table View -->
  <div class="clients-table-view" id="clientsTableView">
  <div class="container-table">
    <!-- Claims Card -->
    <div style="background:#fff; border:1px solid #ddd; border-radius:4px; overflow:hidden;">
      <div class="page-header" style="background:#fff; border-bottom:1px solid #ddd; margin-bottom:0;">
      <div class="page-title-section">
        <h3>Claims</h3>
        <div class="records-found">Records Found - {{ $claims->total() }}</div>
        <div style="display:flex; align-items:center; gap:15px; margin-top:10px;">
          <div class="filter-group" style="display:flex; align-items:center; gap:10px;">
            <label style="display:flex; align-items:center; gap:8px; margin:0; cursor:pointer;">
              <span style="font-size:13px;">Filter</span>
              @php
              
                $hasPending = request()->has('pending') && (request()->pending == 'true' || request()->pending == '1');
              @endphp
              <input type="checkbox" id="filterToggle" {{ $hasPending ? 'checked' : '' }}>
            </label>
            @if($hasPending)
              <button class="btn" id="listAllBtn" type="button" style="background:#28a745; color:#fff; border:none; padding:6px 16px; border-radius:2px; cursor:pointer;">List ALL</button>
            @else
              <button class="btn" id="showPendingBtn" type="button" style="background:#000; color:#fff; border:none; padding:6px 16px; border-radius:2px; cursor:pointer;">Show Pending</button>
            @endif
          </div>
        </div>
      </div>
      <div class="action-buttons">
        <button class="btn btn-add" id="addClaimBtn">Add</button>
      </div>
    </div>

    @if(session('success'))
      <div class="alert alert-success" id="successAlert" style="padding:8px 12px; margin:15px 20px; border:1px solid #c3e6cb; background:#d4edda; color:#155724;">
        {{ session('success') }}
        <button type="button" class="alert-close" onclick="document.getElementById('successAlert').style.display='none'" style="float:right;background:none;border:none;font-size:16px;cursor:pointer;">×</button>
      </div>
    @endif

    <div class="table-responsive" id="tableResponsive">
      <table id="claimsTable">
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
          @foreach($claims as $index => $clm)
            <tr class="{{ $clm->hasExpired ?? false ? 'expired' : ($clm->hasExpiring ?? false ? 'expiring' : '') }}">
             
              <td class="bell-cell {{ $client->hasExpired ?? false ? 'expired' : ($client->hasExpiring ?? false ? 'expiring' : '') }}">
                <div style="display:flex; align-items:center; justify-content:center;">
                  @php
                    $isExpired = $clm->hasExpired ?? false;
                    $isExpiring = $clm->hasExpiring ?? false;
                  @endphp
                  <div class="status-indicator {{ $isExpired ? 'expired' : 'normal' }}" style="width:18px; height:18px; border-radius:50%; border:2px solid #000; background-color:{{ $isExpired ? '#dc3545' : 'transparent' }};"></div>
                </div>
              </td>
              <td class="action-cell">
                <svg class="action-expand" onclick="openClaimModal('edit', {{ $clm->id }})" width="22" height="22" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="cursor:pointer; vertical-align:middle;">
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
              @foreach($selectedColumns as $col)
                @if($col == 'claim_id')
                  <td data-column="claim_id">
                   {{ $clm->claim_id }}
                  </td>
                @elseif($col == 'policy_no')
                  <td data-column="policy_no">{{ $clm->policy ? $clm->policy->policy_no : ($clm->policy_no ?? '-') }}</td>
                @elseif($col == 'client_name')
                  <td data-column="client_name">{{ $clm->client ? $clm->client->client_name : '-' }}</td>
                @elseif($col == 'loss_date')
                  <td data-column="loss_date">{{ $clm->loss_date ? \Carbon\Carbon::parse($clm->loss_date)->format('d-M-y') : '-' }}</td>
                @elseif($col == 'claim_date')
                  <td data-column="claim_date">{{ $clm->claim_date ? \Carbon\Carbon::parse($clm->claim_date)->format('d-M-y') : '-' }}</td>
                @elseif($col == 'claim_amount')
                  <td data-column="claim_amount">{{ $clm->claim_amount ? number_format($clm->claim_amount, 2) : '-' }}</td>
                @elseif($col == 'claim_summary')
                  <td data-column="claim_summary">{{ $clm->claim_summary ?? '-' }}</td>
                @elseif($col == 'status')
                  <td data-column="status">{{ $clm->status ?? '-' }}</td>
                @elseif($col == 'close_date')
                  <td data-column="close_date">{{ $clm->close_date ? \Carbon\Carbon::parse($clm->close_date)->format('d-M-y') : '-' }}</td>
                @elseif($col == 'paid_amount')
                  <td data-column="paid_amount">{{ $clm->paid_amount ? number_format($clm->paid_amount, 2) : '-' }}</td>
                @elseif($col == 'settlment_notes')
                  <td data-column="settlment_notes">{{ $clm->settlment_notes ?? '-' }}</td>
                @endif
              @endforeach
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    <div class="footer" style="background:#fff; border-top:1px solid #ddd; padding:10px 20px; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap;">
      <div class="footer-left">
        <a class="btn btn-export" href="{{ route('claims.export', array_merge(request()->query(), ['page' => $claims->currentPage()])) }}">Export</a>
        <button class="btn btn-column" id="columnBtn2" type="button">Column</button>
      </div>
      <div class="paginator">
        @php
          $base = url()->current();
          $q = request()->query();
          $current = $claims->currentPage();
          $last = max(1, $claims->lastPage());
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

  <!-- Add/Edit Claim Modal -->
  <div class="modal" id="claimModal">
    <div class="modal-content">
      <div class="modal-header">
        <h4 id="claimModalTitle">View/Edit Claim</h4>
        <div style="display:flex; gap:10px;">
          <button type="submit" form="claimForm" class="btn-save">Save</button>
          <button type="button" class="btn-cancel" onclick="closeClaimModal()">Cancel</button>
        </div>
      </div>
      <form id="claimForm" method="POST" action="{{ route('claims.store') }}">
        @csrf
        <div id="claimFormMethod" style="display:none;"></div>
        <div class="modal-body">
          <div class="form-row">
            <div class="form-group">
              <label for="policy_id">Policy Number</label>
              <select class="form-control" name="policy_id" id="policy_id" required>
                <option value="">Select Policy</option>
                @if(isset($policies))
                  @foreach($policies as $policy)
                    <option value="{{ $policy->id }}">{{ $policy->policy_no }}</option>
                  @endforeach
                @endif
              </select>
            </div>
            <div class="form-group">
              <label for="loss_date">Loss Date</label>
              <input type="date" class="form-control" name="loss_date" id="loss_date">
            </div>
          </div>
     
          <div class="form-row">
            <div class="form-group">
              <label for="claim_date">Claim Date</label>
              <input type="date" class="form-control" name="claim_date" id="claim_date">
            </div>
            <div class="form-group">
              <label for="claim_amount">Claim Amount</label>
              <input type="number" step="0.01" class="form-control" name="claim_amount" id="claim_amount">
            </div>
          </div>
          <div class="form-row">
            <div class="form-group">
              <label for="claim_stage">Claim Stage</label>
              <select class="form-control" name="claim_stage" id="claim_stage">
                <option value="">Select</option>
                @if(isset($lookupData['claim_stages']))
                  @foreach($lookupData['claim_stages'] as $stage)
                    <option value="{{ $stage }}">{{ $stage }}</option>
                  @endforeach
                @endif
              </select>
            </div>
            <div class="form-group">
              <label for="status">Claim Status</label>
              <select class="form-control" name="status" id="status">
                <option value="">Select</option>
                @if(isset($lookupData['claim_statuses']))
                  @foreach($lookupData['claim_statuses'] as $claimStatus)
                    <option value="{{ $claimStatus }}">{{ $claimStatus }}</option>
                  @endforeach
                @endif
              </select>
            </div>
          </div>
          <div class="form-row">
            <div class="form-group">
              <label for="close_date">Date Closed</label>
              <input type="date" class="form-control" name="close_date" id="close_date">
            </div>
            <div class="form-group">
              <label for="paid_amount">Paid Amount</label>
              <input type="number" step="0.01" class="form-control" name="paid_amount" id="paid_amount">
            </div>
          </div>
          <div class="form-row full-width">
            <div class="form-group">
              <label for="claim_summary">Claim Summary</label>
              <textarea class="form-control" name="claim_summary" id="claim_summary" rows="4"></textarea>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn-upload" id="claimUploadBtnModal" style="display: none;" onclick="openDocumentUploadModal()">Upload Document</button>
          <button type="button" class="btn-delete" id="claimDeleteBtnModal" style="display: none;" onclick="deleteClaim()">Delete</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Document Upload Modal -->
  <div class="modal" id="claimDocumentUploadModal">
    <div class="modal-content" style="max-width:500px;">
      <div class="modal-header">
        <h4>Upload Document</h4>
        <button type="button" class="modal-close" onclick="closeDocumentUploadModal()">×</button>
      </div>
      <form id="claimDocumentUploadForm" onsubmit="uploadDocument(event)">
        @csrf
        <div class="modal-body">
          <div class="form-group">
            <label for="documentType">Document Type</label>
            <select class="form-control" name="document_type" id="documentType" required>
              <option value="">Select Document Type</option>
              <option value="claim_form">Claim Form</option>
              <option value="supporting_document">Supporting Document</option>
              <option value="medical_report">Medical Report</option>
              <option value="police_report">Police Report</option>
              <option value="estimate">Estimate</option>
              <option value="other">Other</option>
            </select>
          </div>
          <div class="form-group">
            <label for="documentFile">Select File</label>
            <input type="file" class="form-control" name="document" id="documentFile" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" required>
            <small style="color:#666; font-size:11px;">Accepted formats: PDF, DOC, DOCX, JPG, JPEG, PNG (Max 5MB)</small>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn-cancel" onclick="closeDocumentUploadModal()">Cancel</button>
          <button type="submit" class="btn-save">Upload</button>
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

        <form id="columnForm" action="{{ route('claims.save-column-settings') }}" method="POST">
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
  let currentClaimId = null;
  const selectedColumns = @json($selectedColumns);
  const mandatoryColumns = @json($mandatoryColumns);

  // Helper function for date formatting
  function formatDate(dateStr) {
    if (!dateStr) return '-';
    const date = new Date(dateStr);
    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    return `${date.getDate()}-${months[date.getMonth()]}-${String(date.getFullYear()).slice(-2)}`;
  }

  // Helper function for number formatting
  function formatNumber(num) {
    if (!num && num !== 0) return '-';
    return parseFloat(num).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
  }

  // Open Claim Modal - MUST be defined before event listeners
  async function openClaimModal(mode, claimId = null) {
    console.log('openClaimModal called with mode:', mode, 'claimId:', claimId);
    const modal = document.getElementById('claimModal');
    if (!modal) {
      console.error('Modal element not found');
      alert('Error: Modal element not found');
      return;
    }
    console.log('Modal element found:', modal);
    
    const form = document.getElementById('claimForm');
    const formMethod = document.getElementById('claimFormMethod');
    const title = document.getElementById('claimModalTitle');
    const deleteBtn = document.getElementById('claimDeleteBtnModal');
    const uploadBtn = document.getElementById('claimUploadBtnModal');
    
    if (!form || !formMethod || !title) {
      console.error('Required form elements not found', {form, formMethod, title});
      alert('Error: Form elements not found');
      return;
    }
    
    if (mode === 'add') {
      title.textContent = 'Add Claim';
      form.action = '{{ route("claims.store") }}';
      formMethod.innerHTML = '';
      if (deleteBtn) deleteBtn.style.display = 'none';
      if (uploadBtn) uploadBtn.style.display = 'none';
      form.reset();
      
      // Enable all fields for add mode
      const allFields = form.querySelectorAll('input, select, textarea');
      allFields.forEach(field => {
        field.removeAttribute('readonly');
        field.removeAttribute('disabled');
        field.style.backgroundColor = '#fff';
        field.style.cursor = 'text';
      });
      
      currentClaimId = null;
    } else if (mode === 'edit' && claimId) {
      try {
        const res = await fetch(`/claims/${claimId}/edit`, {
          headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
          }
        });
        if (!res.ok) throw new Error('Network error');
        const claim = await res.json();
        currentClaimId = claimId;
        
        title.textContent = 'View/Edit Claim';
        form.action = `/claims/${claimId}`;
        formMethod.innerHTML = '<input type="hidden" name="_method" value="PUT">';
        if (deleteBtn) deleteBtn.style.display = 'inline-block';
        if (uploadBtn) uploadBtn.style.display = 'inline-block';
        
        // Enable all fields for edit mode
        const allFields = form.querySelectorAll('input, select, textarea');
        allFields.forEach(field => {
          field.removeAttribute('readonly');
          field.removeAttribute('disabled');
          field.style.backgroundColor = '#fff';
          field.style.cursor = 'text';
        });
        
        // Populate form fields
        const policyIdField = document.getElementById('policy_id');
        if (policyIdField) {
          policyIdField.value = claim.policy_id || '';
        }
        document.getElementById('loss_date').value = claim.loss_date ? (typeof claim.loss_date === 'string' ? claim.loss_date.substring(0,10) : claim.loss_date) : '';
        document.getElementById('claim_date').value = claim.claim_date ? (typeof claim.claim_date === 'string' ? claim.claim_date.substring(0,10) : claim.claim_date) : '';
        document.getElementById('claim_amount').value = claim.claim_amount || '';
        if (document.getElementById('claim_stage')) {
          document.getElementById('claim_stage').value = claim.claim_stage || claim.status || '';
        }
        document.getElementById('status').value = claim.status || '';
        document.getElementById('close_date').value = claim.close_date ? (typeof claim.close_date === 'string' ? claim.close_date.substring(0,10) : claim.close_date) : '';
        document.getElementById('paid_amount').value = claim.paid_amount || '';
        document.getElementById('claim_summary').value = claim.claim_summary || '';
      } catch (e) {
        console.error(e);
        alert('Error loading claim data');
        return;
      }
    }
    
    // Show the modal
    console.log('Adding show class to modal');
    modal.classList.add('show');
    document.body.style.overflow = 'hidden';
    console.log('Modal classes:', modal.className);
    console.log('Modal display style:', window.getComputedStyle(modal).display);
  }

  function closeClaimModal() {
    const modal = document.getElementById('claimModal');
    if (modal) {
    modal.classList.remove('show');
    document.body.style.overflow = '';
    currentClaimId = null;
  }
  }


  // Event listeners - wait for DOM to be ready
  document.addEventListener('DOMContentLoaded', function() {
    // Add Claim Button - Open Modal
    const addBtn = document.getElementById('addClaimBtn');
    if (addBtn) {
      addBtn.addEventListener('click', () => openClaimModal('add'));
    }
    
    const columnBtn = document.getElementById('columnBtn2');
    if (columnBtn) {
      columnBtn.addEventListener('click', () => openColumnModal());
    }
    
    // Filter toggle handler
    const filterToggle = document.getElementById('filterToggle');
    if (filterToggle) {
      const urlParams = new URLSearchParams(window.location.search);
      const hasPending = urlParams.get('pending') === 'true' || urlParams.get('pending') === '1';
      filterToggle.checked = hasPending;
      
      filterToggle.addEventListener('change', function(e) {
        e.preventDefault();
        e.stopPropagation();
        if (!this.checked) {
          // Clear filter when toggle is unchecked
          const u = new URL(window.location.href);
          u.searchParams.delete('pending');
          window.location.href = u.toString();
        } else {
          // Activate pending filter
          const u = new URL(window.location.href);
          u.searchParams.set('pending', '1');
          window.location.href = u.toString();
        }
      });
    }
    
    // Show Pending button handler
    const showPendingBtn = document.getElementById('showPendingBtn');
    if (showPendingBtn) {
      showPendingBtn.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        const u = new URL(window.location.href);
        u.searchParams.set('pending', '1');
        window.location.href = u.toString();
      });
    }
    
    // List ALL button handler
    const listAllBtn = document.getElementById('listAllBtn');
    if (listAllBtn) {
      listAllBtn.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        const u = new URL(window.location.href);
        u.searchParams.delete('pending');
        window.location.href = u.toString();
      });
  }
  
  // Close modal on backdrop click
    const claimModal = document.getElementById('claimModal');
    if (claimModal) {
      claimModal.addEventListener('click', function(e) {
    if (e.target === this) {
      closeClaimModal();
    }
  });
    }
  
  // Close modal on ESC key
  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
      closeClaimModal();
    }
  });
  });


  function deleteClaim() {
    if (!currentClaimId) return;
    if (!confirm('Delete this claim?')) return;
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = `/claims/${currentClaimId}`;
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

  // Document Upload Modal
  function openDocumentUploadModal() {
    if (!currentClaimId) {
      alert('Please save the claim first before uploading documents');
      return;
    }
    const modal = document.getElementById('claimDocumentUploadModal');
    if (modal) {
      modal.classList.add('show');
      document.body.style.overflow = 'hidden';
    }
  }

  function closeDocumentUploadModal() {
    const modal = document.getElementById('claimDocumentUploadModal');
    if (modal) {
      modal.classList.remove('show');
      document.body.style.overflow = '';
      document.getElementById('claimDocumentUploadForm').reset();
    }
  }

  async function uploadDocument(event) {
    event.preventDefault();
    if (!currentClaimId) {
      alert('No claim selected');
      return;
    }

    const form = document.getElementById('claimDocumentUploadForm');
    const formData = new FormData(form);
    formData.append('claim_id', currentClaimId);

    try {
      const response = await fetch('/claims/upload-document', {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': '{{ csrf_token() }}',
          'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
      });

      const result = await response.json();
      
      if (result.success) {
        alert('Document uploaded successfully!');
        closeDocumentUploadModal();
      } else {
        alert('Error uploading document: ' + (result.message || 'Unknown error'));
      }
    } catch (error) {
      console.error('Error:', error);
      alert('Error uploading document: ' + error.message);
    }
  }
</script>

@include('partials.table-scripts', [
  'mandatoryColumns' => $mandatoryColumns,
])

@endsection
