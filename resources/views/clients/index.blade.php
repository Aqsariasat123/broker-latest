@extends('layouts.app')
@section('content')
<link rel="stylesheet" href="{{ asset('css/clients-index.css') }}">



@php
  $selectedColumns = session('client_columns', [
    'client_name','client_type','nin_bcrn','dob_dor','mobile_no','wa','district','occupation','source','status','signed_up',
    'employer','clid','contact_person','income_source','married','spouses_name','alternate_no','email_address','location',
    'island','country','po_box_no','pep','pep_comment','image','salutation','first_name','other_names','surname','passport_no'
  ]);
@endphp

<div class="dashboard">
  <!-- Main Clients Table View -->
  <div class="clients-table-view" id="clientsTableView">
  <div class="container-table">
    <!-- Clients Card -->
    <div style="background:#fff; border:1px solid #ddd; border-radius:4px; overflow:hidden;">
      <div class="page-header" style="background:#fff; border-bottom:1px solid #ddd; margin-bottom:0;">
      <div class="page-title-section">
        <h3>Clients</h3>
        <div class="records-found">Records Found - {{ $clients->total() }}</div>
        <div style="display:flex; align-items:center; gap:15px; margin-top:10px;">
          <div class="filter-group">
            <label class="toggle-switch">
              <input type="checkbox" id="filterToggle" {{ request()->get('follow_up') == 'true' ? 'checked' : '' }}>
              <span class="toggle-slider"></span>
            </label>
            <label for="filterToggle" style="font-size:14px; color:#2d2d2d; margin:0; cursor:pointer; user-select:none;">Filter</label>
          </div>
          @if(request()->get('follow_up') == 'true')
            <button class="btn btn-list-all" id="listAllBtn">List ALL</button>
          @else
            <button class="btn btn-follow-up" id="followUpBtn">To Follow Up</button>
          @endif
        </div>
      </div>
      <div class="action-buttons">
        <button class="btn btn-add" id="addClientBtn">Add</button>
        <!-- <button class="btn btn-close" id="closeBtn">Close</button> -->
      </div>
    </div>

    @if(session('success'))
      <div class="alert alert-success" id="successAlert" style="padding:8px 12px; margin:15px 20px; border:1px solid #c3e6cb; background:#d4edda; color:#155724;">
        {{ session('success') }}
        <button type="button" class="alert-close" onclick="document.getElementById('successAlert').style.display='none'" style="float:right;background:none;border:none;font-size:16px;cursor:pointer;">×</button>
      </div>
    @endif

    <div class="table-responsive" id="tableResponsive">
      <table id="clientsTable">
        <thead>
          <tr>
            <th style="text-align:center;">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="display:inline-block; vertical-align:middle;">
                <path d="M12 2C8.13 2 5 5.13 5 9C5 14.25 2 16 2 16H22C22 16 19 14.25 19 9C19 5.13 15.87 2 12 2Z" fill="#fff" stroke="#fff" stroke-width="1.5"/>
                <path d="M9 21C9 22.1 9.9 23 11 23H13C14.1 23 15 22.1 15 21H9Z" fill="#fff"/>
              </svg>
            </th>
            <th>Action</th>
            @php
              $columnDefinitions = [
                'client_name' => ['label' => 'Client Name', 'filter' => true],
                'client_type' => ['label' => 'Client Type', 'filter' => true],
                'nin_bcrn' => ['label' => 'NIN/BCRN', 'filter' => true],
                'dob_dor' => ['label' => 'DOB/DOR', 'filter' => false],
                'mobile_no' => ['label' => 'MobileNo', 'filter' => false],
                'wa' => ['label' => 'WA', 'filter' => false],
                'district' => ['label' => 'District', 'filter' => false],
                'occupation' => ['label' => 'Occupation', 'filter' => false],
                'source' => ['label' => 'Source', 'filter' => false],
                'status' => ['label' => 'Status', 'filter' => false],
                'signed_up' => ['label' => 'Signed Up', 'filter' => false],
                'employer' => ['label' => 'Employer', 'filter' => false],
                'clid' => ['label' => 'CLID', 'filter' => false],
                'contact_person' => ['label' => 'Contact Person', 'filter' => false],
                'income_source' => ['label' => 'Income Source', 'filter' => false],
                'married' => ['label' => 'Married', 'filter' => false],
                'spouses_name' => ['label' => 'Spouses Name', 'filter' => false],
                'alternate_no' => ['label' => 'Alternate No', 'filter' => false],
                'email_address' => ['label' => 'Email Address', 'filter' => false],
                'location' => ['label' => 'Location', 'filter' => false],
                'island' => ['label' => 'Island', 'filter' => false],
                'country' => ['label' => 'Country', 'filter' => false],
                'po_box_no' => ['label' => 'P.O. Box No', 'filter' => false],
                'pep' => ['label' => 'PEP', 'filter' => false],
                'pep_comment' => ['label' => 'PEP Comment', 'filter' => false],
                'image' => ['label' => 'Image', 'filter' => false],
                'salutation' => ['label' => 'Salutation', 'filter' => false],
                'first_name' => ['label' => 'First Name', 'filter' => false],
                'other_names' => ['label' => 'Other Names', 'filter' => false],
                'surname' => ['label' => 'Surname', 'filter' => false],
                'passport_no' => ['label' => 'Passport No', 'filter' => false],
              ];
            @endphp
            @foreach($selectedColumns as $col)
              @if(isset($columnDefinitions[$col]))
                <th data-column="{{ $col }}">
                  {{ $columnDefinitions[$col]['label'] }}
                  @if($columnDefinitions[$col]['filter'])
                    <input type="text" class="column-filter" data-column="{{ $col }}" placeholder="Filter {{ $columnDefinitions[$col]['label'] }}..." style="width:100%; margin-top:4px; padding:4px 6px; font-size:11px; border:1px solid #666; background:#000; color:#fff; border-radius:2px; transition:all 0.2s;">
                  @endif
                </th>
              @endif
            @endforeach
          </tr>
        </thead>
        <tbody>
          @foreach($clients as $client)
            <tr class="{{ $client->status === 'Inactive' ? 'inactive-row' : '' }} {{ $client->hasExpired ?? false ? 'has-expired' : ($client->hasExpiring ?? false ? 'has-expiring' : '') }}">
              <td class="bell-cell {{ $client->hasExpired ?? false ? 'expired' : ($client->hasExpiring ?? false ? 'expiring' : '') }}">
                <div style="display:flex; align-items:center; justify-content:center;">
                  @php
                    $isExpired = $client->hasExpired ?? false;
                    $isExpiring = $client->hasExpiring ?? false;
                  @endphp
                  <div class="status-indicator {{ $isExpired ? 'expired' : 'normal' }}" style="width:18px; height:18px; border-radius:50%; border:2px solid #000; background-color:{{ $isExpired ? '#dc3545' : 'transparent' }};"></div>
                </div>
              </td>
              <td class="action-cell">
                <svg class="action-expand" onclick="openClientDetailsModal({{ $client->id }})" width="22" height="22" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="cursor:pointer; vertical-align:middle;">
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
                <svg class="action-clock" onclick="window.location.href='{{ route('clients.index') }}?follow_up=true'" width="22" height="22" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="cursor:pointer; vertical-align:middle;">
                  <circle cx="12" cy="12" r="9" stroke="#2d2d2d" stroke-width="1.5" fill="none"/>
                  <path d="M12 7V12L15 15" stroke="#2d2d2d" stroke-width="1.5" stroke-linecap="round"/>
                </svg>
                
              </td>
              @foreach($selectedColumns as $col)
                @if($col == 'client_name')
                  <td data-column="client_name">
                   {{ $client->client_name }}
                  </td>
                @elseif($col == 'client_type')
                  <td data-column="client_type">{{ $client->client_type }}</td>
                @elseif($col == 'nin_bcrn')
                  <td data-column="nin_bcrn">{{ $client->nin_bcrn ?? '##########' }}</td>
                @elseif($col == 'dob_dor')
                  <td data-column="dob_dor">{{ $client->dob_dor ? $client->dob_dor->format('d-M-y') : '##########' }}</td>
                @elseif($col == 'mobile_no')
                  <td data-column="mobile_no">{{ $client->mobile_no }}</td>
                @elseif($col == 'wa')
                  <td data-column="wa" class="checkbox-cell">
                    <input type="checkbox" {{ $client->wa ? 'checked' : '' }} disabled>
                  </td>
                @elseif($col == 'district')
                  <td data-column="district">{{ $client->district ?? '-' }}</td>
                @elseif($col == 'occupation')
                  <td data-column="occupation">{{ $client->occupation ?? '-' }}</td>
                @elseif($col == 'source')
                  <td data-column="source">{{ $client->source }}</td>
                @elseif($col == 'status')
                  <td data-column="status">{{ $client->status == 'Inactive' ? 'Dormant' : ($client->status == 'Active' ? 'Active' : $client->status) }}</td>
                @elseif($col == 'signed_up')
                  <td data-column="signed_up">{{ $client->signed_up ? $client->signed_up->format('d-M-y') : '##########' }}</td>
                @elseif($col == 'employer')
                  <td data-column="employer">{{ $client->employer ?? '-' }}</td>
                @elseif($col == 'clid')
                  <td data-column="clid">{{ $client->clid }}</td>
                @elseif($col == 'contact_person')
                  <td data-column="contact_person">{{ $client->contact_person ?? '-' }}</td>
                @elseif($col == 'income_source')
                  <td data-column="income_source">{{ $client->income_source ?? '-' }}</td>
                @elseif($col == 'married')
                  <td data-column="married">{{ $client->married ? 'Yes' : 'No' }}</td>
                @elseif($col == 'spouses_name')
                  <td data-column="spouses_name">{{ $client->spouses_name ?? '-' }}</td>
                @elseif($col == 'alternate_no')
                  <td data-column="alternate_no">{{ $client->alternate_no ?? '-' }}</td>
                @elseif($col == 'email_address')
                  <td data-column="email_address">{{ $client->email_address ?? '-' }}</td>
                @elseif($col == 'location')
                  <td data-column="location">{{ $client->location ?? '-' }}</td>
                @elseif($col == 'island')
                  <td data-column="island">{{ $client->island ?? '-' }}</td>
                @elseif($col == 'country')
                  <td data-column="country">{{ $client->country ?? '-' }}</td>
                @elseif($col == 'po_box_no')
                  <td data-column="po_box_no">{{ $client->po_box_no ?? '-' }}</td>
                @elseif($col == 'pep')
                  <td data-column="pep">{{ $client->pep ? 'Yes' : 'No' }}</td>
                @elseif($col == 'pep_comment')
                  <td data-column="pep_comment">{{ $client->pep_comment ?? '-' }}</td>
                @elseif($col == 'image')
                  <td data-column="image">{{ $client->image ? '📷' : '-' }}</td>
                @elseif($col == 'salutation')
                  <td data-column="salutation">{{ $client->salutation ?? '-' }}</td>
                @elseif($col == 'first_name')
                  <td data-column="first_name">{{ $client->first_name }}</td>
                @elseif($col == 'other_names')
                  <td data-column="other_names">{{ $client->other_names ?? '-' }}</td>
                @elseif($col == 'surname')
                  <td data-column="surname">{{ $client->surname }}</td>
                @elseif($col == 'passport_no')
                  <td data-column="passport_no">{{ $client->passport_no ?? '-' }}</td>
                @endif
              @endforeach
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>

      <div class="footer" style="background:#fff; border-top:1px solid #ddd; margin-top:0;">
      <div class="footer-left">
        <a class="btn btn-export" href="{{ route('clients.export', array_merge(request()->query(), ['page' => $clients->currentPage()])) }}">Export</a>
        <button class="btn btn-column" id="columnBtn" type="button">Column</button>
        <button class="btn btn-export" id="printBtn" type="button" style="margin-left:10px;">Print</button>
      </div>
      <div class="paginator">
        @php
          $base = url()->current();
          $q = request()->query();
          $current = $clients->currentPage();
          $last = max(1,$clients->lastPage());
          function page_url($base,$q,$p){ $params = array_merge($q,['page'=>$p]); return $base . '?' . http_build_query($params); }
        @endphp

        <a class="btn-page" href="{{ $current>1 ? page_url($base,$q,1) : '#' }}" @if($current<=1) disabled @endif>&laquo;</a>
        <a class="btn-page" href="{{ $current>1 ? page_url($base,$q,$current-1) : '#' }}" @if($current<=1) disabled @endif>&lsaquo;</a>
        <span class="page-info">Page {{ $current }} of {{ $last }}</span>
        <a class="btn-page" href="{{ $current<$last ? page_url($base,$q,$current+1) : '#' }}" @if($current>= $last) disabled @endif>&rsaquo;</a>
        <a class="btn-page" href="{{ $current<$last ? page_url($base,$q,$last) : '#' }}" @if($current>=$last) disabled @endif>&raquo;</a>
      </div>
    </div>
  </div>
  </div>
</div>  
  <!-- Client Page View (Full Page) -->
  <div class="client-page-view" id="clientPageView">
    <div class="client-page-header">
      <div class="client-page-title">
        <span id="clientPageTitle">Client</span> - <span class="client-name" id="clientPageName"></span>
      </div>
    </div>
    <div class="client-page-body">
      <div class="client-page-content">
        <!-- Client Details View -->
        <div id="clientDetailsPageContent" style="display:none;">
      
          <!-- Client Details Card -->
          <div style="background:#fff; border:1px solid #ddd; border-radius:4px; margin-bottom:15px; overflow:hidden;">
            <div style="display:flex; justify-content:space-between; align-items:center; padding:12px 15px; border-bottom:1px solid #ddd; background:#fff;">
              <div class="client-page-nav">
                <button class="nav-tab" data-tab="proposals" data-url="{{ route('life-proposals.index') }}">Proposals</button>
                <button class="nav-tab" data-tab="policies" data-url="{{ route('policies.index') }}">Policies</button>
                <button class="nav-tab" data-tab="payments" data-url="{{ route('payments.index') }}">Payments</button>
                <button class="nav-tab" data-tab="vehicles" data-url="{{ route('vehicles.index') }}">Vehicles</button>
                <button class="nav-tab" data-tab="claims" data-url="{{ route('claims.index') }}">Claims</button>
                <button class="nav-tab active" data-tab="documents" data-url="{{ route('documents.index') }}">Documents</button>
              </div>
              <div class="client-page-actions">
                <button class="btn btn-edit" id="editClientFromPageBtn" style="background:#f3742a; color:#fff; border:none; padding:6px 16px; border-radius:2px; cursor:pointer; display:none;">Edit</button>
                <button class="btn" onclick="closeClientPageView()" style="background:#e0e0e0; color:#000; border:none; padding:6px 16px; border-radius:2px; cursor:pointer;">Close</button>
              </div>
            </div>
          
            <div id="clientDetailsContent" style="display:grid; grid-template-columns:repeat(4, 1fr); gap:0; align-items:start; padding:12px;">
              <!-- Content will be loaded via JavaScript -->
            </div>
          </div>
          
          <!-- Documents Card -->
          <div id="clientDocumentsSection" style="background:#fff; border:1px solid #ddd; border-radius:4px; padding:15px;">
            <h4 style="font-weight:bold; margin-bottom:10px; color:#000; font-size:13px;">Documents</h4>
            <div id="clientDocumentsList" style="display:flex; gap:10px; flex-wrap:wrap; margin-bottom:10px;">
              <!-- Documents will be loaded here -->
            </div>
            <div style="display:flex; gap:10px; justify-content:flex-end;">
              <input type="file" id="photoUploadInput" accept="image/*" style="display:none;" onchange="handlePhotoUpload(event)">
              <button class="btn" onclick="document.getElementById('photoUploadInput').click()" style="background:#f3742a; color:#fff; border:none; padding:6px 16px; border-radius:2px; cursor:pointer; font-size:13px;">Upload Photo</button>
              <button id="addDocumentBtn1" class="btn" onclick="openDocumentUploadModal()" style="background:#f3742a; color:#fff; border:none; padding:6px 16px; border-radius:2px; cursor:pointer; font-size:13px; display:none;">Add Document</button>
            </div>
          </div>
        </div>
        
        <!-- Client Edit/Add Form -->
        <div id="clientFormPageContent" style="display:none;">
          <!-- Client Form Card -->
          <div style="background:#fff; border:1px solid #ddd; border-radius:4px; margin-bottom:15px; overflow:hidden;">
            <div style="display:flex; justify-content:flex-end; align-items:center; padding:12px 15px; border-bottom:1px solid #ddd; background:#fff;">
              <div class="client-page-actions">
                <button type="button" class="btn-delete" id="clientDeleteBtn" style="display:none; background:#dc3545; color:#fff; border:none; padding:6px 16px; border-radius:2px; cursor:pointer;" onclick="deleteClient()">Delete</button>
                <button type="submit" form="clientForm" class="btn-save" style="background:#f3742a; color:#fff; border:none; padding:6px 16px; border-radius:2px; cursor:pointer;">Save</button>
                <button type="button" class="btn" onclick="closeClientPageView()" style="background:#e0e0e0; color:#000; border:none; padding:6px 16px; border-radius:2px; cursor:pointer;">Close</button>
              </div>
            </div>
            
            <form id="clientForm" method="POST" action="{{ route('clients.store') }}" enctype="multipart/form-data">
              @csrf
              <div id="clientFormMethod" style="display:none;"></div>
              <div style="padding:12px;">
                <!-- Form content will be cloned from modal -->
              </div>
            </form>
          </div>
          
          <!-- Documents Card (will be cloned from modal) -->
          <div id="editFormDocumentsSection" style="background:#fff; border:1px solid #ddd; border-radius:4px; padding:15px; display:none;">
            <!-- Documents section will be cloned here -->
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

  <!-- Add/Edit Client Modal (hidden, used for form structure) -->
  <div class="modal" id="clientModal">
    <div class="modal-content" style="max-width:95%; width:1400px; max-height:95vh; overflow-y:auto;">
      <form id="clientForm" method="POST" action="{{ route('clients.store') }}" enctype="multipart/form-data">
        @csrf
        <div id="clientFormMethod" style="display:none;"></div>
        
        <div class="modal-header" style="background:#fff; color:#000; padding:12px 15px; display:flex; justify-content:flex-end; align-items:center; border-bottom:1px solid #ddd;">
          <div style="display:flex; gap:8px;">
            <button type="button" class="btn-delete" id="clientDeleteBtn" style="display:none; background:#f3742a; color:#fff; border:none; padding:6px 16px; border-radius:2px; cursor:pointer;" onclick="deleteClient()">Delete</button>
            <button type="submit" class="btn-save" style="background:#f3742a; color:#fff; border:none; padding:6px 16px; border-radius:2px; cursor:pointer;">Save</button>
            <button type="button" class="modal-close" onclick="closeClientModal()" style="background:#f3742a; color:#fff; border:none; padding:6px 16px; border-radius:2px; cursor:pointer;">Close</button>
          </div>
        </div>

        <div class="modal-body" style="background:#f5f5f5; padding:12px;">
          <div style="display:grid; grid-template-columns:repeat(4, 1fr); gap:10px;">
            <!-- Column 1: Customer Details & Individual Details -->
            <div>
              <div class="detail-section">
                <div class="detail-section-header">CUSTOMER DETAILS</div>
                <div class="detail-section-body">
                  <div class="detail-row">
                    <span class="detail-label">Client Type</span>
                    <select id="client_type" name="client_type" class="detail-value" required style="flex:1; border:1px solid #ddd; padding:4px 6px; border-radius:2px; font-size:11px;">
                      <option value="">Select</option>
                      @if(isset($lookupData['client_types']))
                        @foreach($lookupData['client_types'] as $clientType)
                          <option value="{{ $clientType }}">{{ $clientType }}</option>
                        @endforeach
                      @else
                        <option value="Individual">Individual</option>
                        <option value="Business">Business</option>
                        <option value="Company">Company</option>
                        <option value="Organization">Organization</option>
                      @endif
                    </select>
                  </div>
                  <div class="detail-row">
                    <span class="detail-label">DOB/DOR</span>
                    <div style="display:flex; gap:5px; align-items:center; flex:1;">
                      <input id="dob_dor" name="dob_dor" type="date" class="detail-value" style="flex:1; border:1px solid #ddd; padding:4px 6px; border-radius:2px; font-size:11px;">
                      <input id="dob_age" type="text" readonly class="detail-value" style="width:50px; border:1px solid #ddd; padding:4px 6px; border-radius:2px; background:#f5f5f5; font-size:11px; flex-shrink:0;">
                    </div>
                  </div>
                  <div class="detail-row">
                    <span class="detail-label">NIN/BCRN</span>
                    <input id="nin_bcrn" name="nin_bcrn" class="detail-value" style="flex:1; border:1px solid #ddd; padding:4px 6px; border-radius:2px; font-size:11px;">
                  </div>
                  <div class="detail-row">
                    <span class="detail-label">ID Expiry Date</span>
                    <div style="display:flex; gap:5px; align-items:center; flex:1;">
                      <input id="id_expiry_date" name="id_expiry_date" type="date" class="detail-value" style="flex:1; border:1px solid #ddd; padding:4px 6px; border-radius:2px; font-size:11px;">
                      <input id="id_expiry_days" type="text" readonly class="detail-value" style="width:50px; border:1px solid #ddd; padding:4px 6px; border-radius:2px; background:#f5f5f5; font-size:11px; flex-shrink:0;">
                    </div>
                  </div>
                  <div class="detail-row">
                    <span class="detail-label">Client Status</span>
                    <select id="status" name="status" class="detail-value" required style="flex:1; border:1px solid #ddd; padding:4px 6px; border-radius:2px; font-size:11px;">
                      <option value="">Select</option>
                      @foreach($lookupData['client_statuses'] as $st) <option value="{{ $st }}">{{ $st }}</option> @endforeach
                    </select>
                  </div>
                </div>
              </div>
              <div class="detail-section">
                <div class="detail-section-header">INDIVIDUAL DETAILS</div>
                <div class="detail-section-body">
                  <div style="display:flex; gap:10px; align-items:flex-start;">
                    <div style="flex:1; display:flex; flex-direction:column; gap:8px;">
                      <div class="detail-row" style="margin-bottom:0;">
                    <span class="detail-label">Salutation</span>
                        <select id="salutation" name="salutation" class="detail-value" style="flex:1; border:1px solid #ddd; padding:4px 6px; border-radius:2px; font-size:11px;">
                      <option value="">Select</option>
                      @foreach($lookupData['salutations'] as $s) <option value="{{ $s }}">{{ $s }}</option> @endforeach
                    </select>
                  </div>
                      <div class="detail-row" style="margin-bottom:0;">
                      <span class="detail-label">First Name</span>
                      <input id="first_name" name="first_name" class="detail-value" required style="flex:1; border:1px solid #ddd; padding:4px 6px; border-radius:2px; font-size:11px;">
                    </div>
                    </div>
                    <div style="display:flex; flex-direction:column; flex-shrink:0; margin-top:13px;">
                      <div id="clientPhotoPreview" style="width:80px; height:100px; border:1px solid #ddd; border-radius:2px; background:#f5f5f5; display:flex; align-items:center; justify-content:center; overflow:hidden;">
                        <img id="clientPhotoImg" src="" alt="Photo" class="detail-photo" style="display:none; width:100%; height:100%; object-fit:cover;">
                        <span style="font-size:10px; color:#999;">Photo</span>
                      </div>
                      <input id="image" name="image" type="file" accept="image/*" style="margin-top:5px; font-size:10px; width:80px;" onchange="previewClientPhoto(event)">
                      <input type="hidden" id="existing_image" name="existing_image" value="">
                    </div>
                  </div>
                  <div class="detail-row">
                    <span class="detail-label">Other Names</span>
                    <input id="other_names" name="other_names" class="detail-value" style="flex:1; border:1px solid #ddd; padding:4px 6px; border-radius:2px; font-size:11px;">
                  </div>
                  <div class="detail-row">
                    <span class="detail-label">Surname</span>
                    <input id="surname" name="surname" class="detail-value" required style="flex:1; border:1px solid #ddd; padding:4px 6px; border-radius:2px; font-size:11px;">
                  </div>
                  <div class="detail-row">
                    <span class="detail-label">Passport No</span>
                    <div style="display:flex; gap:5px; align-items:center; flex:1;">
                      <input id="passport_no" name="passport_no" class="detail-value" style="flex:1; border:1px solid #ddd; padding:4px 6px; border-radius:2px; font-size:11px;">
                      <input type="text" value="SEY" readonly style="width:60px; border:1px solid #ddd; padding:4px 6px; border-radius:2px; background:#fff; text-align:center; font-size:11px; flex-shrink:0;">
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Column 2: Contact Details & Income Details -->
            <div>
              <div class="detail-section">
                <div class="detail-section-header">CONTACT DETAILS</div>
                <div class="detail-section-body">
                  <div class="detail-row">
                    <span class="detail-label">Mobile No</span>
                    <input id="mobile_no" name="mobile_no" class="detail-value" required style="flex:1; border:1px solid #ddd; padding:4px 6px; border-radius:2px; font-size:11px;">
                  </div>
                  <div class="detail-row">
                    <span class="detail-label">On Wattsapp</span>
                    <div class="detail-value checkbox">
                      <input id="wa" name="wa" type="checkbox" value="1">
                    </div>
                  </div>
                  <div class="detail-row" id="alternate_no_row">
                    <span class="detail-label">Alternate No</span>
                    <input id="alternate_no" name="alternate_no" class="detail-value" style="flex:1; border:1px solid #ddd; padding:4px 6px; border-radius:2px; font-size:11px;">
                  </div>
                  <div class="detail-row">
                    <span class="detail-label">Email Address</span>
                    <input id="email_address" name="email_address" type="email" class="detail-value" style="flex:1; border:1px solid #ddd; padding:4px 6px; border-radius:2px; font-size:11px;">
                  </div>
                  <div class="detail-row">
                    <span class="detail-label">Contact Person</span>
                    <input id="contact_person" name="contact_person" class="detail-value" style="flex:1; border:1px solid #ddd; padding:4px 6px; border-radius:2px; font-size:11px;">
                  </div>
                </div>
              </div>
              <div class="detail-section">
                <div class="detail-section-header">INCOME DETAILS</div>
                <div class="detail-section-body">
                  <div class="detail-row">
                    <span class="detail-label">Occupation</span>
                    <select id="occupation" name="occupation" class="detail-value" style="flex:1; border:1px solid #ddd; padding:4px 6px; border-radius:2px; font-size:11px;">
                      <option value="">Select</option>
                      @foreach($lookupData['occupations'] as $o) <option value="{{ $o }}">{{ $o }}</option> @endforeach
                    </select>
                  </div>
                  <div class="detail-row">
                    <span class="detail-label">Income Source</span>
                    <select id="income_source" name="income_source" class="detail-value" style="flex:1; border:1px solid #ddd; padding:4px 6px; border-radius:2px; font-size:11px;">
                      <option value="">Select</option>
                      @foreach($lookupData['income_sources'] as $i) <option value="{{ $i }}">{{ $i }}</option> @endforeach
                    </select>
                  </div>
                  <div class="detail-row">
                    <span class="detail-label">Employer</span>
                    <input id="employer" name="employer" class="detail-value" style="flex:1; border:1px solid #ddd; padding:4px 6px; border-radius:2px; font-size:11px;">
                  </div>
                  <div class="detail-row">
                    <span class="detail-label">Monthly Income</span>
                    <input id="monthly_income" name="monthly_income" class="detail-value" style="flex:1; border:1px solid #ddd; padding:4px 6px; border-radius:2px; font-size:11px;">
                  </div>
                  <div class="detail-row">
                    <span class="detail-label"></span>
                    <input type="text" class="detail-value" style="flex:1; border:1px solid #ddd; padding:4px 6px; border-radius:2px; font-size:11px;">
                  </div>
                </div>
              </div>
            </div>

            <!-- Column 3: Address Details & Other Details -->
            <div>
              <div class="detail-section">
                <div class="detail-section-header">ADDRESS DETAILS</div>
                <div class="detail-section-body">
                  <div class="detail-row">
                    <span class="detail-label">District</span>
                    <select id="district" name="district" class="detail-value" style="flex:1; border:1px solid #ddd; padding:4px 6px; border-radius:2px; font-size:11px;">
                      <option value="">Select</option>
                      @foreach($lookupData['districts'] as $d) <option value="{{ $d }}">{{ $d }}</option> @endforeach
                    </select>
                  </div>
                  <div class="detail-row">
                    <span class="detail-label">Address</span>
                    <input id="location" name="location" class="detail-value" style="flex:1; border:1px solid #ddd; padding:4px 6px; border-radius:2px; font-size:11px;">
                  </div>
                  <div class="detail-row">
                    <span class="detail-label">Island</span>
                    <select id="island" name="island" class="detail-value" style="flex:1; border:1px solid #ddd; padding:4px 6px; border-radius:2px; font-size:11px;">
                      <option value="">Select</option>
                      @foreach($lookupData['islands'] as $is) <option value="{{ $is }}">{{ $is }}</option> @endforeach
                    </select>
                  </div>
                  <div class="detail-row">
                    <span class="detail-label">Country</span>
                    <select id="country" name="country" class="detail-value" style="flex:1; border:1px solid #ddd; padding:4px 6px; border-radius:2px; font-size:11px;">
                      <option value="">Select</option>
                      @foreach($lookupData['countries'] as $c) <option value="{{ $c }}">{{ $c }}</option> @endforeach
                    </select>
                  </div>
                  <div class="detail-row">
                    <span class="detail-label">P.O. Box No</span>
                    <input id="po_box_no" name="po_box_no" class="detail-value" style="flex:1; border:1px solid #ddd; padding:4px 6px; border-radius:2px; font-size:11px;">
                  </div>
                </div>
              </div>
              <div class="detail-section">
                <div class="detail-section-header">OTHER DETAILS</div>
                <div class="detail-section-body">
                  <div class="detail-row">
                    <span class="detail-label">Married</span>
                    <div class="detail-value checkbox">
                      <input id="married" name="married" type="checkbox" value="1">
                    </div>
                  </div>
                  <div class="detail-row">
                    <span class="detail-label">Spouse's Name</span>
                    <input id="spouses_name" name="spouses_name" class="detail-value" style="flex:1; border:1px solid #ddd; padding:4px 6px; border-radius:2px; font-size:11px;">
                  </div>
                  <div class="detail-row">
                    <span class="detail-label">PEP</span>
                    <div style="display:flex; gap:5px; align-items:center; flex:1;">
                      <div class="detail-value checkbox" style="flex:0 0 auto; min-width:auto;">
                        <input id="pep" name="pep" type="checkbox" value="1">
                      </div>
                      <input type="text" value="PEP Details" readonly style="flex:1; border:1px solid #ddd; padding:4px 6px; border-radius:2px; background:#fff; font-size:11px; font-family:inherit; box-sizing:border-box; min-height:22px;">
                    </div>
                  </div>
                  <div class="detail-row">
                    <span class="detail-label"></span>
                    <textarea id="pep_comment" name="pep_comment" class="detail-value" style="flex:1; border:1px solid #ddd; padding:4px 6px; border-radius:2px; min-height:40px; resize:vertical; font-size:11px;"></textarea>
                  </div>
                </div>
              </div>
            </div>

            <!-- Column 4: Registration Details & Insurables -->
            <div>
              <div class="detail-section">
                <div class="detail-section-header">REGISTRATION DETAILS</div>
                <div class="detail-section-body">
                  <div class="detail-row">
                    <span class="detail-label">Sign Up Date</span>
                    <input id="signed_up" name="signed_up" type="date" class="detail-value" required style="flex:1; border:1px solid #ddd; padding:4px 6px; border-radius:2px; font-size:11px;">
                  </div>
                  <div class="detail-row">
                    <span class="detail-label">Agency</span>
                    <input id="agency" name="agency" type="text" value="Keystone" readonly class="detail-value" style="flex:1; border:1px solid #ddd; padding:4px 6px; border-radius:2px; background:#f5f5f5; font-size:11px;">
                  </div>
                  <div class="detail-row">
                    <span class="detail-label">Agent</span>
                    <input id="agent" name="agent" class="detail-value" style="flex:1; border:1px solid #ddd; padding:4px 6px; border-radius:2px; font-size:11px;">
                  </div>
                  <div class="detail-row">
                    <span class="detail-label">Source</span>
                    <select id="source" name="source" class="detail-value" required style="flex:1; border:1px solid #ddd; padding:4px 6px; border-radius:2px; font-size:11px;">
                      <option value="">Select</option>
                      @foreach($lookupData['sources'] as $s) <option value="{{ $s }}">{{ $s }}</option> @endforeach
                    </select>
                  </div>
                  <div class="detail-row">
                    <span class="detail-label">Source Name</span>
                    <input id="source_name" name="source_name" class="detail-value" style="flex:1; border:1px solid #ddd; padding:4px 6px; border-radius:2px; font-size:11px;">
                  </div>
                </div>
              </div>
              <div class="detail-section">
                <div class="detail-section-header">INSURABLES</div>
                <div class="detail-section-body">
                  <div style="display:flex; gap:12px; margin-bottom:8px; flex-wrap:wrap;">
                    <div class="detail-row" style="margin-bottom:0; flex:1; min-width:80px; align-items:center;">
                      <span class="detail-label" style="min-width:auto; flex-shrink:0; margin-right:8px;">Vehicle</span>
                      <div class="detail-value checkbox" style="flex:0 0 auto;">
                        <input id="has_vehicle" name="has_vehicle" type="checkbox" value="1">
                      </div>
                    </div>
                    <div class="detail-row" style="margin-bottom:0; flex:1; min-width:80px; align-items:center;">
                      <span class="detail-label" style="min-width:auto; flex-shrink:0; margin-right:8px;">House</span>
                      <div class="detail-value checkbox" style="flex:0 0 auto;">
                        <input id="has_house" name="has_house" type="checkbox" value="1">
                      </div>
                    </div>
                    <div class="detail-row" style="margin-bottom:0; flex:1; min-width:80px; align-items:center;">
                      <span class="detail-label" style="min-width:auto; flex-shrink:0; margin-right:8px;">Business</span>
                      <div class="detail-value checkbox" style="flex:0 0 auto;">
                        <input id="has_business" name="has_business" type="checkbox" value="1">
                      </div>
                    </div>
                  </div>
                  <div class="detail-row" style="margin-bottom:8px; align-items:center;">
                    <span class="detail-label" style="min-width:auto; flex-shrink:0; margin-right:8px;">Boat</span>
                    <div class="detail-value checkbox" style="flex:0 0 auto;">
                        <input id="has_boat" name="has_boat" type="checkbox" value="1">
                    </div>
                  </div>
                  <div class="detail-row">
                    <span class="detail-label">Notes</span>
                    <textarea id="notes" name="notes" class="detail-value" style="flex:1; border:1px solid #ddd; padding:4px 6px; border-radius:2px; min-height:40px; resize:vertical; font-size:11px;"></textarea>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Documents Section -->
          <div style="margin-top:15px; padding-top:12px; border-top:2px solid #ddd;">
            <h4 style="font-weight:bold; margin-bottom:10px; color:#000; font-size:13px;">Documents</h4>
            <div id="editClientDocumentsList" style="display:flex; gap:10px; flex-wrap:wrap; margin-bottom:10px;">
              <!-- Documents will be loaded here -->
            </div>
            <div style="display:flex; gap:10px; justify-content:flex-end;">
              <button type="button" class="btn" onclick="document.getElementById('image').click()" style="background:#f3742a; color:#fff; border:none; padding:6px 16px; border-radius:2px; cursor:pointer; font-size:13px;">Upload Photo</button>
              <button id="addDocumentBtn2" type="button" class="btn" onclick="openDocumentUploadModal()" style="background:#f3742a; color:#fff; border:none; padding:6px 16px; border-radius:2px; cursor:pointer; font-size:13px; display:none;">Add Document</button>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>

  <!-- Client Details Modal -->
  <div class="modal" id="clientDetailsModal">
    <div class="modal-content" style="max-width:95%; width:1400px; max-height:95vh; overflow-y:auto;">
      <div class="modal-header" style="background:#fff; color:#000; padding:12px 15px; display:flex; justify-content:space-between; align-items:center; border-bottom:1px solid #ddd;">
        <div style="display:flex; gap:8px;">
          <button class="nav-tab" data-tab="proposals">Proposals</button>
          <button class="nav-tab" data-tab="policies">Policies</button>
          <button class="nav-tab" data-tab="payments">Payments</button>
          <button class="nav-tab" data-tab="vehicles">Vehicles</button>
          <button class="nav-tab" data-tab="claims">Claims</button>
          <button class="nav-tab active" data-tab="documents">Documents</button>
        </div>
        <div style="display:flex; gap:8px;">
          <button class="btn btn-edit" id="editClientFromModalBtn" style="background:#f3742a; color:#fff; border:none; padding:6px 16px; border-radius:2px; cursor:pointer;">Edit</button>
          <button class="modal-close" onclick="closeClientDetailsModal()" style="background:#e0e0e0; color:#000; border:none; padding:6px 16px; border-radius:2px; cursor:pointer;">Close</button>
        </div>
      </div>
      <div class="modal-body" style="background:#f5f5f5; padding:12px;">
        <div id="clientDetailsContent" style="display:grid; grid-template-columns:repeat(4, 1fr); gap:10px; align-items:start;">
          <!-- Content will be loaded via JavaScript -->
        </div>
        <div id="clientDocumentsSection" style="margin-top:15px; padding-top:12px; border-top:2px solid #ddd; background:#f5f5f5;">
          <h4 style="font-weight:bold; margin-bottom:10px; color:#000; font-size:13px; padding:0 12px;">Documents</h4>
          <div id="clientDocumentsList" style="display:flex; gap:10px; flex-wrap:wrap; margin-bottom:10px; padding:0 12px;">
            <!-- Documents will be loaded here -->
          </div>
          <div style="display:flex; gap:10px; justify-content:flex-end; padding:0 12px 12px;">
            <input type="file" id="photoUploadInput" accept="image/*" style="display:none;" onchange="handlePhotoUpload(event)">
            <button class="btn" onclick="document.getElementById('photoUploadInput').click()" style="background:#f3742a; color:#fff; border:none; padding:6px 16px; border-radius:2px; cursor:pointer; font-size:13px;">Upload Photo</button>
            <button id="addDocumentBtn3" class="btn" onclick="openDocumentUploadModal()" style="background:#f3742a; color:#fff; border:none; padding:6px 16px; border-radius:2px; cursor:pointer; font-size:13px; display:none;">Add Document</button>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Document Upload Modal -->
  <div class="modal" id="documentUploadModal">
    <div class="modal-content" style="max-width:600px;">
      <div class="modal-header">
        <h4>Add Document</h4>
        <button type="button" class="modal-close" onclick="closeDocumentUploadModal()">×</button>
      </div>
      <div class="modal-body">
        <form id="documentUploadForm">
          <div class="form-group" style="margin-bottom:15px;">
            <label for="documentType" style="display:block; margin-bottom:5px; font-weight:600;">Document Type</label>
            <select id="documentType" name="document_type" class="form-control" required>
              <option value="">Select Document Type</option>
              <option value="id_document">ID Card</option>
              <option value="poa_document">Proof Of Address</option>
              <option value="other">Other Document</option>
            </select>
          </div>
          <div class="form-group" style="margin-bottom:15px;">
            <label for="documentFile" style="display:block; margin-bottom:5px; font-weight:600;">Select File</label>
            <input type="file" id="documentFile" name="document" class="form-control" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx" required onchange="previewDocument(event)">
            <small style="color:#666; font-size:11px;">Accepted formats: JPG, PNG, PDF, DOC, DOCX (Max 5MB)</small>
          </div>
          <div id="documentPreviewContainer" style="display:none; margin-top:15px; padding:15px; border:1px solid #ddd; border-radius:4px; background:#f9f9f9;">
            <div style="font-weight:600; margin-bottom:10px; font-size:13px;">Preview:</div>
            <div id="documentPreviewContent" style="display:flex; align-items:center; justify-content:center; min-height:200px;">
              <!-- Preview will be shown here -->
            </div>
            <div id="documentPreviewInfo" style="margin-top:10px; font-size:12px; color:#666;">
              <!-- File info will be shown here -->
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn-cancel" onclick="closeDocumentUploadModal()">Cancel</button>
        <button type="button" class="btn-save" onclick="handleDocumentUpload()">Upload</button>
      </div>
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
        <div style="display:flex;gap:8px;margin-bottom:12px;">
          <button class="btn" onclick="selectAllColumns()">Select All</button>
          <button class="btn" onclick="deselectAllColumns()">Deselect All</button>
        </div>

        <form id="columnForm" action="{{ route('clients.save-column-settings') }}" method="POST">
          @csrf
          <div class="column-selection" id="columnSelection">
            @php
              $all = [
                'client_name'=>'Client Name',
                'client_type'=>'Client Type',
                'nin_bcrn'=>'NIN/BCRN',
                'dob_dor'=>'DOB/DOR',
                'mobile_no'=>'MobileNo',
                'wa'=>'WA',
                'district'=>'District',
                'occupation'=>'Occupation',
                'source'=>'Source',
                'status'=>'Status',
                'signed_up'=>'Signed Up',
                'employer'=>'Employer',
                'clid'=>'CLID',
                'contact_person'=>'Contact Person',
                'income_source'=>'Income Source',
                'married'=>'Married',
                'spouses_name'=>'Spouses Name',
                'alternate_no'=>'Alternate No',
                'email_address'=>'Email Address',
                'location'=>'Location',
                'island'=>'Island',
                'country'=>'Country',
                'po_box_no'=>'P.O. Box No',
                'pep'=>'PEP',
                'pep_comment'=>'PEP Comment',
                'image'=>'Image',
                'salutation'=>'Salutation',
                'first_name'=>'First Name',
                'other_names'=>'Other Names',
                'surname'=>'Surname',
                'passport_no'=>'Passport No'
              ];
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

            @php
              // Mandatory fields that should always be checked and disabled
              $mandatoryFields = ['client_name', 'client_type', 'mobile_no', 'source', 'status', 'signed_up', 'clid', 'first_name', 'surname'];
            @endphp
            @foreach($ordered as $key => $label)
              @php
                $isMandatory = in_array($key, $mandatoryFields);
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
  // Initialize data from Blade
  let currentClientId = null;
  const lookupData = @json($lookupData ?? []);
  const selectedColumns = @json($selectedColumns ?? []);
  const clientsIndexRoute = '{{ route("clients.index") }}';
  const clientsStoreRoute = '{{ route("clients.store") }}';
  const csrfToken = '{{ csrf_token() }}';
  const clientsTotal = {{ $clients->total() }};
</script>
<script src="{{ asset('js/clients-index.js') }}"></script>
@endsection
