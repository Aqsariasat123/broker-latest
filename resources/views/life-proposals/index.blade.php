@extends('layouts.app')
@section('content')

@include('partials.table-styles')

<style>
    * { box-sizing: border-box; }
    .dashboard { padding-left:0 !important; }
    body { font-family: Arial, sans-serif; color: #000; margin: 0; background: #f5f5f5; }
    .container-table { max-width: 100%; margin: 0 auto; background: #fff; padding: 0; }
    .page-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 0; flex-wrap: wrap; gap: 15px; background: #f5f5f5; padding: 15px 20px; border-bottom: 1px solid #ddd; }
    .page-title-section { display: flex; align-items: center; gap: 15px; flex: 1; }
    h3 { background: transparent; padding: 0; margin: 0; font-weight: bold; color: #2d2d2d; font-size: 24px; }
    .records-found { font-size: 14px; color: #2d2d2d; font-weight: normal; }
    .action-buttons { margin-left:auto; display:flex; gap:10px; align-items:center; }
    .btn { border:none; cursor:pointer; padding:6px 16px; font-size:13px; border-radius:2px; white-space:nowrap; transition:background-color .2s; text-decoration:none; color:inherit; background:#fff; border:1px solid #ccc; font-weight:normal; }
    .btn-add { background:#f3742a; color:#fff; border-color:#f3742a; }
    .btn-export, .btn-column { background:#fff; color:#000; border:1px solid #ccc; }
    .btn-close { background:#e0e0e0; color:#000; border-color:#ccc; }
    .btn-follow-up { background:#000; color:#fff; border-color:#000; }
    .btn-follow-up.inactive { background:#e0e0e0; color:#000; border-color:#ccc; }
    .btn-submitted { background:#e0e0e0; color:#000; border-color:#ccc; }
    .btn-submitted.active { background:#000; color:#fff; border-color:#000; }
    .filter-group { display:flex; align-items:center; gap:8px; }
    .table-responsive { width: 100%; border: none; background: #fff; margin-bottom:0; overflow-x: auto; padding: 0 20px; }
    .footer { display:flex; justify-content:space-between; align-items:center; padding:15px 20px; gap:10px; border-top:1px solid #ddd; flex-wrap:wrap; margin-top:0; background:#f5f5f5; }
    .footer-left { display:flex; gap:10px; }
    .paginator {
      display: flex;
      align-items: center;
      gap: 5px;
      font-size: 12px;
      color: #555;
      white-space: nowrap;
      margin: 0 auto;
    }
    .btn-page{
      color: #2d2d2d;
      font-size: 14px;
      width: 32px;
      height: 32px;
      padding: 0;
      cursor: pointer;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      background: #f5f5f5;
      border: 1px solid #ddd;
      border-radius: 2px;
      font-weight: normal;
    }
    .btn-page:hover:not([disabled]) { background: #e9e9e9; }
    .btn-page[disabled] { opacity: 0.5; cursor: not-allowed; background: #f5f5f5; }
    .page-info { padding: 0 12px; color: #555; font-size: 12px; }
    table { width:100%; border-collapse:collapse; font-size:13px; min-width:900px; }
    thead tr { background-color: #000; color: #fff; height:35px; font-weight: normal; }
    thead th { padding:8px 5px; text-align:left; border-right:1px solid #444; white-space:nowrap; font-weight: normal; color: #fff !important; }
    thead th:first-child { text-align:center; }
    thead th:last-child { border-right:none; }
    tbody tr { background-color:#fff; border-bottom:1px solid #ddd; min-height:32px; }
    tbody tr:nth-child(even) { background-color:#f8f8f8; }
    tbody td { padding:8px 5px; border-right:1px solid #ddd; white-space:nowrap; vertical-align:middle; font-size:12px; }
    tbody td:last-child { border-right:none; }
    .action-cell { display:flex; align-items:center; gap:8px; padding:8px; }
    .action-expand { width:22px; height:22px; cursor:pointer; display:inline-block; }
    .badge-status { font-size:11px; padding:4px 8px; display:inline-block; border-radius:4px; color:#fff; }
    /* Toggle Switch Styling */
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
    /* Form Styles - Compact */
    .modal-content { max-width: 98%; width: 98%; max-height: 95vh; }
    .modal-body { padding: 10px 15px; max-height: calc(95vh - 100px); overflow-y: auto; }
    .form-group { margin-bottom: 8px; }
    .form-group label { display: block; margin-bottom: 3px; font-weight: bold; font-size: 12px; color: #2d2d2d; }
    .form-control { width: 100%; padding: 4px 6px; border: 1px solid #ccc; border-radius: 2px; font-size: 12px; background: #f8f8f8; height: 28px; }
    .form-control:focus { outline: none; border-color: #007bff; background: #fff; }
    .form-control[readonly] { background-color: #f5f5f5; cursor: not-allowed; }
    textarea.form-control { height: auto; min-height: 50px; resize: vertical; }
    .form-row { display: flex; gap: 8px; margin-bottom: 8px; flex-wrap: wrap; align-items: flex-start; }
    .form-row .form-group { flex: 0 0 calc((100% - 24px) / 4); margin-bottom: 0; }
    .form-row .form-group.full-width { flex: 0 0 100%; }
    .form-row .form-group.half-width { flex: 0 0 calc((100% - 8px) / 2); }
    .form-row .form-group.quarter-width { flex: 0 0 calc((100% - 24px) / 4); }
    .form-section { margin-bottom: 12px; padding-bottom: 10px; border-bottom: 1px solid #e0e0e0; }
    .form-section:last-child { border-bottom: none; margin-bottom: 0; }
    .form-section-title { font-weight: bold; font-size: 13px; margin-bottom: 8px; color: #2d2d2d; }
    .rider-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 8px; margin-bottom: 8px; }
    .rider-item { display: flex; align-items: center; gap: 6px; }
    .rider-item label { margin: 0; font-weight: normal; font-size: 12px; }
    .rider-checkbox { width: 16px; height: 16px; cursor: pointer; margin: 0; }
    .rider-checkbox:checked { accent-color: #f3742a; }
    .rider-premium { width: 70px !important; padding: 2px 4px !important; font-size: 11px !important; }
    .btn-save { background: #f3742a; color: white; border: none; padding: 6px 16px; border-radius: 2px; cursor: pointer; font-size: 13px; }
    .btn-cancel { background: #e0e0e0; color: #000; border: none; padding: 6px 16px; border-radius: 2px; cursor: pointer; font-size: 13px; }
    .btn-delete { background: #dc3545; color: white; border: none; padding: 6px 12px; border-radius: 2px; cursor: pointer; }
    .field-required { border: 2px dashed #dc3545 !important; }
    .readonly-field { background-color: #f5f5f5; cursor: not-allowed; }
    .modal-header { display: flex; justify-content: space-between; align-items: center; padding: 10px 15px; border-bottom: 1px solid #ddd; }
    .modal-header h4 { margin: 0; font-size: 16px; font-weight: bold; }
    .modal-close { background: none; border: none; font-size: 24px; cursor: pointer; color: #666; line-height: 1; padding: 0; width: 24px; height: 24px; }
</style>

@php
  $config = \App\Helpers\TableConfigHelper::getConfig('life-proposals');
  $selectedColumns = \App\Helpers\TableConfigHelper::getSelectedColumns('life-proposals');
  $columnDefinitions = $config['column_definitions'];
  $mandatoryColumns = $config['mandatory_columns'];
@endphp

<div class="dashboard">
  <!-- Main Life Proposals Table View -->
  <div class="clients-table-view" id="clientsTableView">
  <div class="container-table">
    <!-- Life Proposals Card -->
    <div style="background:#fff; border:1px solid #ddd; border-radius:4px; overflow:hidden;">
      <div class="page-header" style="background:#fff; border-bottom:1px solid #ddd; margin-bottom:0;">
      <div class="page-title-section">
        <h3>Life Proposals</h3>
        <div class="records-found">Records Found - {{ $proposals->total() }}</div>
        <div style="display:flex; align-items:center; gap:15px; margin-top:10px;">
          <div class="filter-group" style="display:flex; align-items:center; gap:10px;">
            <label style="display:flex; align-items:center; gap:8px; margin:0; cursor:pointer;">
              <span style="font-size:13px;">Filter</span>
              @php
                $hasFollowUp = request()->has('follow_up') && (request()->follow_up == 'true' || request()->follow_up == '1');
                $hasSubmitted = request()->has('submitted') && (request()->submitted == 'true' || request()->submitted == '1');
              @endphp
              <input type="checkbox" id="filterToggle" {{ $hasFollowUp || $hasSubmitted ? 'checked' : '' }}>
            </label>
            <button class="btn btn-follow-up {{ $hasFollowUp ? '' : 'inactive' }}" id="followUpBtn" type="button" style="background:{{ $hasFollowUp ? '#000' : '#e0e0e0' }}; color:{{ $hasFollowUp ? '#fff' : '#000' }}; border:none; padding:6px 16px; border-radius:2px; cursor:pointer;">To Follow Up</button>
            <button class="btn btn-submitted {{ $hasSubmitted ? 'active' : '' }}" id="submittedBtn" type="button" style="background:{{ $hasSubmitted ? '#000' : '#e0e0e0' }}; color:{{ $hasSubmitted ? '#fff' : '#000' }}; border:none; padding:6px 16px; border-radius:2px; cursor:pointer;">Submitted</button>
          </div>
        </div>
      </div>
      <div class="action-buttons">
        <button class="btn btn-add" id="addProposalBtn">Add</button>
        <button class="btn btn-close" onclick="window.history.back()">Close</button>
      </div>
    </div>

    @if(session('success'))
      <div class="alert alert-success" id="successAlert" style="padding:8px 12px; margin:15px 20px; border:1px solid #c3e6cb; background:#d4edda; color:#155724;">
        {{ session('success') }}
        <button type="button" class="alert-close" onclick="document.getElementById('successAlert').style.display='none'" style="float:right;background:none;border:none;font-size:16px;cursor:pointer;">×</button>
      </div>
    @endif

    <div class="table-responsive" id="tableResponsive">
      <table id="proposalsTable">
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
          @foreach($proposals as $index => $proposal)
            <tr class="{{ $proposal->is_submitted ? 'submitted-row' : '' }}">
               <td class="bell-cell {{ $proposal->hasExpired ? 'expired' : ($proposal->hasExpiring ? 'expiring' : '') }}">
                <div style="display:flex; align-items:center; justify-content:center;">
                  @php
                    $isExpired = $proposal->hasExpired;
                    $isExpiring = $proposal->hasExpiring;
                  
                   $radioChecked = false;
                  $radioDotColor = 'transparent';
                  if ($index === 0 && ($isExpired || $isExpiring)) {
                    $radioChecked = true;
                    $radioDotColor = '#f3742a'; // Yellow
                  } elseif ($isExpired) {
                    $radioDotColor = '#dc3545'; // Red
                  } elseif ($isExpiring) {
                    $radioDotColor = '#f3742a'; // Yellow
                  } elseif ($proposal->offer_date && !$proposal->is_submitted) {
                    $radioDotColor = '#007bff'; // Blue
                  }
                @endphp
                <div style="position:relative; display:inline-block;">
                  <input type="radio" name="proposal_select" class="action-radio" value="{{ $proposal->id }}" data-proposal-id="{{ $proposal->id }}" data-dot-color="{{ $radioDotColor }}" style="width:16px; height:16px; cursor:pointer; opacity:0; position:absolute; z-index:2;" {{ $radioChecked ? 'checked' : '' }}>
                  <div class="radio-dot" style="width:16px; height:16px; border-radius:50%; border:2px solid #2d2d2d; background-color:{{ $radioDotColor !== 'transparent' ? $radioDotColor : 'transparent' }}; position:relative; z-index:1;"></div>
                </div>
              </td>
              <td class="action-cell">
       
               
                <svg class="action-expand" onclick="openEditProposal({{ $proposal->id }})" width="22" height="22" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="cursor:pointer; vertical-align:middle;">
                  <rect x="9" y="9" width="6" height="6" stroke="#2d2d2d" stroke-width="1.5" fill="none"/>
                  <path d="M12 9L12 5M12 15L12 19M9 12L5 12M15 12L19 12" stroke="#2d2d2d" stroke-width="1.5" stroke-linecap="round"/>
                  <path d="M12 5L10 7M12 5L14 7M12 19L10 17M12 19L14 17M5 12L7 10M5 12L7 14M19 12L17 10M19 12L17 14" stroke="#2d2d2d" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="cursor:pointer; vertical-align:middle;">
                  <circle cx="12" cy="12" r="10" stroke="#2d2d2d" stroke-width="1.5" fill="none"/>
                  <path d="M12 6V12L16 14" stroke="#2d2d2d" stroke-width="1.5" stroke-linecap="round"/>
                </svg>
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="cursor:pointer; vertical-align:middle;">
                  <circle cx="12" cy="5" r="1.5" fill="#2d2d2d"/>
                  <circle cx="12" cy="12" r="1.5" fill="#2d2d2d"/>
                  <circle cx="12" cy="19" r="1.5" fill="#2d2d2d"/>
                </svg>
              </td>
              @foreach($selectedColumns as $col)
                @if($col == 'proposers_name')
                  <td data-column="proposers_name">
                    <a href="javascript:void(0)" onclick="openEditProposal({{ $proposal->id }})" style="color:#007bff; text-decoration:underline;">{{ $proposal->proposers_name }}</a>
                  </td>
                @elseif($col == 'prid')
                  <td data-column="prid">
                    <a href="javascript:void(0)" onclick="openEditProposal({{ $proposal->id }})" style="color:#007bff; text-decoration:underline;">{{ $proposal->prid }}</a>
                  </td>
                @elseif($col == 'insurer')
                  <td data-column="insurer">{{ $proposal->insurer }}</td>
                @elseif($col == 'policy_plan')
                  <td data-column="policy_plan">{{ $proposal->policy_plan }}</td>
                @elseif($col == 'sum_assured')
                  <td data-column="sum_assured">{{ $proposal->sum_assured ? number_format($proposal->sum_assured,2) : '##########' }}</td>
                @elseif($col == 'term')
                  <td data-column="term">{{ $proposal->term }}</td>
                @elseif($col == 'add_ons')
                  <td data-column="add_ons">{{ $proposal->add_ons ?? '-' }}</td>
                @elseif($col == 'offer_date')
                  <td data-column="offer_date">{{ $proposal->offer_date ? $proposal->offer_date->format('d-M-y') : '##########' }}</td>
                @elseif($col == 'premium')
                  <td data-column="premium">{{ number_format($proposal->premium,2) }}</td>
                @elseif($col == 'frequency')
                  <td data-column="frequency">{{ $proposal->frequency }}</td>
                @elseif($col == 'stage')
                  <td data-column="stage">{{ $proposal->stage }}</td>
                @elseif($col == 'date')
                  <td data-column="date">{{ $proposal->date ? $proposal->date->format('d-M-y') : '##########' }}</td>
                @elseif($col == 'age')
                  <td data-column="age">{{ $proposal->age }}</td>
                @elseif($col == 'status')
                  <td data-column="status"><span class="badge-status" style="background:{{ $proposal->status == 'Approved' ? '#28a745' : ($proposal->status=='Pending' ? '#ffc107' : ($proposal->status=='Declined' ? '#dc3545' : '#6c757d')) }}">{{ $proposal->status }}</span></td>
                @elseif($col == 'source_of_payment')
                  <td data-column="source_of_payment">{{ $proposal->source_of_payment }}</td>
                @elseif($col == 'mcr')
                  <td data-column="mcr">{{ $proposal->mcr ?? '-' }}</td>
                @elseif($col == 'doctor')
                  <td data-column="doctor">{{ $proposal->doctor ?? '-' }}</td>
                @elseif($col == 'date_sent')
                  <td data-column="date_sent">{{ $proposal->date_sent ? $proposal->date_sent->format('d-M-y') : '##########' }}</td>
                @elseif($col == 'date_completed')
                  <td data-column="date_completed">{{ $proposal->date_completed ? $proposal->date_completed->format('d-M-y') : '##########' }}</td>
                @elseif($col == 'notes')
                  <td data-column="notes">{{ $proposal->notes ?? '-' }}</td>
                @elseif($col == 'agency')
                  <td data-column="agency">{{ $proposal->agency ?? '-' }}</td>
                @elseif($col == 'class')
                  <td data-column="class">{{ $proposal->class }}</td>
                @elseif($col == 'is_submitted')
                  <td data-column="is_submitted">{{ $proposal->is_submitted ? 'Yes' : 'No' }}</td>
                @endif
              @endforeach
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    </div>

    <div class="footer">
      <div class="footer-left">
        <a class="btn btn-export" href="{{ route('life-proposals.export') }}">Export</a>
        <button class="btn btn-column" id="columnBtn" type="button">Column</button>
      </div>
      <div class="paginator">
        @php
          $base = url()->current();
          $q = request()->query();
          $current = $proposals->currentPage();
          $last = max(1,$proposals->lastPage());
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

  <!-- Proposal Page View (Full Page) -->
  <div class="client-page-view" id="proposalPageView" style="display:none;">
    <div class="client-page-header">
      <div class="client-page-title">
        <span id="proposalPageTitle">Life Proposal</span> - <span class="client-name" id="proposalPageName"></span>
      </div>
      <div class="client-page-actions">
        <button class="btn btn-edit" id="editProposalFromPageBtn" style="background:#f3742a; color:#fff; border:none; padding:6px 16px; border-radius:2px; cursor:pointer; display:none;">Edit</button>
        <button class="btn" id="closeProposalPageBtn" onclick="closeProposalPageView()" style="background:#e0e0e0; color:#000; border:none; padding:6px 16px; border-radius:2px; cursor:pointer;">Close</button>
      </div>
    </div>
    <div class="client-page-body">
      <div class="client-page-content">
        <!-- Proposal Details View -->
        <div id="proposalDetailsPageContent" style="display:none;">
          <div style="background:#fff; border:1px solid #ddd; border-radius:4px; margin-bottom:15px; overflow:hidden;">
            <div id="proposalDetailsContent" style="display:grid; grid-template-columns:repeat(4, 1fr); gap:0; align-items:start; padding:12px;">
              <!-- Content will be loaded via JavaScript -->
            </div>
          </div>
        </div>
        
        <!-- Proposal Edit/Add Form -->
        <div id="proposalFormPageContent" style="display:none;">
          <div style="background:#fff; border:1px solid #ddd; border-radius:4px; margin-bottom:15px; overflow:hidden;">
            <div style="display:flex; justify-content:flex-end; align-items:center; padding:12px 15px; border-bottom:1px solid #ddd; background:#fff;">
              <div class="client-page-actions">
                <button type="button" class="btn-delete" id="proposalDeleteBtn" style="display:none; background:#dc3545; color:#fff; border:none; padding:6px 16px; border-radius:2px; cursor:pointer;" onclick="deleteProposal()">Delete</button>
                <button type="submit" form="proposalPageForm" class="btn-save" style="background:#f3742a; color:#fff; border:none; padding:6px 16px; border-radius:2px; cursor:pointer;">Save</button>
                <button type="button" class="btn" id="closeProposalFormBtn" onclick="closeProposalPageView()" style="background:#e0e0e0; color:#000; border:none; padding:6px 16px; border-radius:2px; cursor:pointer; display:none;">Close</button>
              </div>
            </div>
            <form id="proposalPageForm" method="POST" action="{{ route('life-proposals.store') }}">
              @csrf
              <div id="proposalPageFormMethod" style="display:none;"></div>
              <div style="padding:12px;">
                <!-- Form content will be cloned from modal -->
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Add/Edit Proposal Modal (hidden, used for form structure) -->
  <div class="modal" id="proposalModal">
    <div class="modal-content">
      <div class="modal-header">
        <h4 id="proposalModalTitle">Life Proposal - New/Update</h4>
        <div style="display:flex; gap:10px;">
          <button type="button" class="btn-cancel" onclick="closeProposalModal()">Cancel</button>
          <button type="submit" form="proposalForm" class="btn-save">Save</button>
        </div>
      </div>
      <form id="proposalForm" method="POST" action="{{ route('life-proposals.store') }}">
        @csrf
        <div id="proposalFormMethod" style="display:none;"></div>
        <div class="modal-body">
          <!-- Proposer's Details Section -->
          <div class="form-section">
            <div class="form-section-title">Proposer's Details</div>
            <div class="form-row">
              <div class="form-group">
                <label for="contact_id">Proposer's Name *</label>
                <select id="contact_id" name="contact_id" class="form-control" required>
                  <option value="">Select Contact</option>
                  @foreach($lookupData['contacts'] as $contact)
                    <option value="{{ $contact->id }}" data-salutation="{{ $contact->salutation }}" data-dob="{{ $contact->dob }}">{{ $contact->contact_name }}</option>
                  @endforeach
                </select>
                <input type="hidden" id="proposers_name" name="proposers_name">
              </div>
              <div class="form-group">
                <label for="salutation">Salutation</label>
                <select id="salutation" name="salutation" class="form-control">
                  <option value="">Select</option>
                  @foreach($lookupData['salutations'] as $s) <option value="{{ $s }}">{{ $s }}</option> @endforeach
                </select>
              </div>
              <div class="form-group">
                <label for="dob">DOB</label>
                <input id="dob" name="dob" type="date" class="form-control">
              </div>
              <div class="form-group">
                <label for="insurer">Insurer *</label>
                <select id="insurer" name="insurer" class="form-control" required>
                  <option value="">Select</option>
                  @foreach($lookupData['insurers'] as $s) <option value="{{ $s }}">{{ $s }}</option> @endforeach
                </select>
              </div>
            </div>
            <div class="form-row">
              <div class="form-group">
                <label for="policy_plan">Policy Plan *</label>
                <select id="policy_plan" name="policy_plan" class="form-control" required>
                  <option value="">Select</option>
                  @foreach($lookupData['policy_plans'] as $s) <option value="{{ $s }}">{{ $s }}</option> @endforeach
                </select>
              </div>
              <div class="form-group">
                <label for="term">Term *</label>
                <input id="term" name="term" type="number" min="1" class="form-control" required>
              </div>
              <div class="form-group">
                <label for="sum_assured">Sum Assured</label>
                <input id="sum_assured" name="sum_assured" type="number" step="0.01" class="form-control" oninput="formatNumberInput(this)">
              </div>
              <div class="form-group">
                <label for="age">Age</label>
                <input id="age" name="age" type="number" min="1" max="120" class="form-control readonly-field" readonly>
              </div>
            </div>
            <div class="form-row">
              <div class="form-group">
                <label for="sex">Sex</label>
                <select id="sex" name="sex" class="form-control">
                  <option value="">Select</option>
                  @foreach($lookupData['sex_options'] as $s) <option value="{{ $s }}">{{ $s }}</option> @endforeach
                </select>
              </div>
              <div class="form-group">
                <label for="anb">ANB</label>
                <input id="anb" name="anb" type="number" class="form-control readonly-field" readonly>
              </div>
            </div>
          </div>

          <!-- Additional Riders Section -->
          <div class="form-section">
            <div class="form-section-title">Additional Riders</div>
            <div class="rider-grid">
              @foreach($lookupData['riders'] as $rider)
                <div class="rider-item">
                  <input type="checkbox" class="rider-checkbox" id="rider_{{ $rider }}" name="riders[]" value="{{ $rider }}" data-rider="{{ $rider }}">
                  <label for="rider_{{ $rider }}" style="margin:0; font-weight:normal;">{{ $rider }}</label>
                  <input type="number" step="0.01" class="form-control rider-premium" id="rider_premium_{{ $rider }}" name="rider_premiums[{{ $rider }}]" style="width:80px; padding:4px;" placeholder="0.00" disabled>
                </div>
              @endforeach
            </div>
            <div class="form-row" style="margin-top:15px;">
              <div class="form-group">
                <label for="annual_premium">Annual Premium</label>
                <input id="annual_premium" name="annual_premium" type="number" step="0.01" class="form-control" oninput="calculateTotalPremium()">
              </div>
              <div class="form-group">
                <label for="total_rider_premium">Total</label>
                <input id="total_rider_premium" type="number" step="0.01" class="form-control readonly-field" readonly>
              </div>
            </div>
          </div>

          <!-- Proposal & Payment Details -->
          <div class="form-section">
            <div class="form-row">
              <div class="form-group">
                <label for="offer_date">Offer Date *</label>
                <input id="offer_date" name="offer_date" type="date" class="form-control" required>
              </div>
              <div class="form-group">
                <label for="stage">Proposal Stage *</label>
                <select id="stage" name="stage" class="form-control" required>
                  <option value="">Select</option>
                  @foreach($lookupData['stages'] as $s) <option value="{{ $s }}">{{ $s }}</option> @endforeach
                </select>
              </div>
              <div class="form-group">
                <label for="agency">Agency</label>
                <select id="agency" name="agency" class="form-control">
                  <option value="">Select</option>
                  @foreach($lookupData['agencies'] as $s) <option value="{{ $s }}">{{ $s }}</option> @endforeach
                </select>
              </div>
              <div class="form-group">
                <label for="source">Source</label>
                <select id="source" name="source" class="form-control">
                  <option value="">Select</option>
                  @foreach($lookupData['sources'] as $s) <option value="{{ $s }}">{{ $s }}</option> @endforeach
                </select>
              </div>
            </div>
            <div class="form-row">
              <div class="form-group">
                <label for="client_id">Source Name</label>
                <select id="client_id" name="client_id" class="form-control field-required">
                  <option value="">Select Client</option>
                  @foreach($lookupData['clients'] as $client)
                    <option value="{{ $client->id }}">{{ $client->client_name }}</option>
                  @endforeach
                </select>
                <input type="hidden" id="source_name" name="source_name">
              </div>
            </div>
            <div class="form-section-title" style="margin-top:15px;">Payment Plan</div>
            <div class="form-row">
              <div class="form-group">
                <label for="frequency">Frequency Of Payment *</label>
                <select id="frequency" name="frequency" class="form-control" required>
                  <option value="">Select</option>
                  @foreach($lookupData['frequencies'] as $s) <option value="{{ $s }}">{{ $s }}</option> @endforeach
                </select>
              </div>
              <div class="form-group">
                <label for="method_of_payment">Method Of Payment</label>
                <select id="method_of_payment" name="method_of_payment" class="form-control">
                  <option value="">Select</option>
                  @foreach($lookupData['method_of_payment_options'] as $s) <option value="{{ $s }}">{{ $s }}</option> @endforeach
                </select>
              </div>
              <div class="form-group">
                <label for="source_of_payment">Source Of Payment *</label>
                <select id="source_of_payment" name="source_of_payment" class="form-control" required>
                  <option value="">Select</option>
                  @foreach($lookupData['sources_of_payment'] as $s) <option value="{{ $s }}">{{ $s }}</option> @endforeach
                </select>
              </div>
            </div>
            <div class="form-row">
              <div class="form-group">
                <label for="base_premium">Base Premium</label>
                <input id="base_premium" name="base_premium" type="number" step="0.01" class="form-control" oninput="calculateTotalPremium()">
              </div>
              <div class="form-group">
                <label for="admin_fee">Admin Fee</label>
                <input id="admin_fee" name="admin_fee" type="number" step="0.01" class="form-control" oninput="calculateTotalPremium()">
              </div>
              <div class="form-group">
                <label for="total_premium">Total Premium</label>
                <input id="total_premium" name="total_premium" type="number" step="0.01" class="form-control readonly-field" readonly>
              </div>
            </div>
          </div>

          <!-- Medical Examination Required Section -->
          <div class="form-section">
            <div class="form-row">
              <div class="form-group">
                <label style="display:flex; align-items:center; gap:8px;">
                  <input type="checkbox" id="medical_examination_required" name="medical_examination_required" value="1" class="rider-checkbox" onchange="toggleMedicalFields()">
                  <span>Medical Examination Required?</span>
                </label>
              </div>
            </div>
            <div id="medicalFields" style="display:none;">
              <div class="form-row">
                <div class="form-group">
                  <label for="clinic">Clinic</label>
                  <select id="clinic" name="clinic" class="form-control field-required">
                    <option value="">Select</option>
                    @foreach($lookupData['clinics'] as $s) <option value="{{ $s }}">{{ $s }}</option> @endforeach
                  </select>
                </div>
                <div class="form-group">
                  <label for="date_referred">Date Referred</label>
                  <input id="date_referred" name="date_referred" type="date" class="form-control field-required">
                </div>
                <div class="form-group">
                  <label for="date_completed">Date Completed</label>
                  <input id="date_completed" name="date_completed" type="date" class="form-control field-required">
                </div>
              </div>
              <div class="form-row">
                <div class="form-group full-width">
                  <label for="exam_notes">Exam Notes</label>
                  <textarea id="exam_notes" name="exam_notes" class="form-control field-required" rows="2"></textarea>
                </div>
              </div>
            </div>
          </div>

          <!-- Application Details Section -->
          <div class="form-section">
            <div class="form-section-title">Application Details</div>
            <div class="form-row">
              <div class="form-group">
                <label for="date">Date *</label>
                <input id="date" name="date" type="date" class="form-control" required>
              </div>
              <div class="form-group">
                <label for="status">Proposal Status *</label>
                <select id="status" name="status" class="form-control" required>
                  <option value="">Select</option>
                  @foreach($lookupData['statuses'] as $s) <option value="{{ $s }}">{{ $s }}</option> @endforeach
                </select>
              </div>
              <div class="form-group">
                <label for="policy_no">Policy No</label>
                <input id="policy_no" name="policy_no" class="form-control">
              </div>
              <div class="form-group">
                <label for="loading_premium">Loading Premium</label>
                <input id="loading_premium" name="loading_premium" type="number" step="0.01" class="form-control">
              </div>
            </div>
            <div class="form-row">
              <div class="form-group">
                <label for="start_date">Start Date</label>
                <input id="start_date" name="start_date" type="date" class="form-control">
              </div>
              <div class="form-group">
                <label for="maturity_date">Maturity Date</label>
                <input id="maturity_date" name="maturity_date" type="date" class="form-control">
              </div>
            </div>
          </div>

          <!-- Documents Section -->
          <div class="form-section">
            <div class="form-section-title">Documents</div>
            <div class="form-row">
              <div class="form-group full-width" style="display:flex; justify-content:flex-end;">
                <button type="button" class="btn" style="background:#000; color:#fff; padding:6px 16px;">Upload</button>
              </div>
            </div>
          </div>

          <!-- Hidden Fields -->
          <input type="hidden" id="prid" name="prid">
          <input type="hidden" id="premium" name="premium">
          <input type="hidden" id="class" name="class" value="">
          <input type="hidden" id="is_submitted" name="is_submitted" value="0">
        </div>
      </form>
    </div>
  </div>

@include('partials.column-selection-modal', [
  'selectedColumns' => $selectedColumns,
  'columnDefinitions' => $columnDefinitions,
  'mandatoryColumns' => $mandatoryColumns,
  'columnSettingsRoute' => route('life-proposals.save-column-settings'),
])

<script>
  let currentProposalId = null;
  const lookupData = @json($lookupData);
  const selectedColumns = @json($selectedColumns);

  // Format number input with commas
  function formatNumberInput(input) {
    if (!input.value) return;
    let value = input.value.replace(/,/g, '');
    input.value = parseFloat(value).toLocaleString('en-US');
  }

  // Calculate age from DOB
  function calculateAge(dob) {
    if (!dob) return null;
    const today = new Date();
    const birthDate = new Date(dob);
    let age = today.getFullYear() - birthDate.getFullYear();
    const monthDiff = today.getMonth() - birthDate.getMonth();
    if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
      age--;
    }
    return age;
  }

  // Calculate ANB (Age Next Birthday)
  function calculateANB(age) {
    if (!age) return null;
    return parseInt(age) + 1;
  }

  // Calculate total rider premium
  function calculateTotalRiderPremium() {
    let total = 0;
    document.querySelectorAll('.rider-premium:not([disabled])').forEach(input => {
      const value = parseFloat(input.value) || 0;
      total += value;
    });
    document.getElementById('total_rider_premium').value = total.toFixed(2);
    calculateTotalPremium();
  }

  // Calculate total premium
  function calculateTotalPremium() {
    const basePremium = parseFloat(document.getElementById('base_premium')?.value || 0);
    const adminFee = parseFloat(document.getElementById('admin_fee')?.value || 0);
    const totalRider = parseFloat(document.getElementById('total_rider_premium')?.value || 0);
    const total = basePremium + adminFee + totalRider;
    document.getElementById('total_premium').value = total.toFixed(2);
    document.getElementById('premium').value = total.toFixed(2);
  }

  // Toggle medical fields
  function toggleMedicalFields() {
    const checkbox = document.getElementById('medical_examination_required');
    const fields = document.getElementById('medicalFields');
    if (checkbox.checked) {
      fields.style.display = 'block';
      fields.querySelectorAll('input, select, textarea').forEach(field => {
        field.required = true;
      });
    } else {
      fields.style.display = 'none';
      fields.querySelectorAll('input, select, textarea').forEach(field => {
        field.required = false;
        field.value = '';
      });
    }
  }

  // Toggle rider premium input
  function toggleRiderPremium(checkbox) {
    const riderName = checkbox.dataset.rider;
    const premiumInput = document.getElementById('rider_premium_' + riderName);
    if (checkbox.checked) {
      premiumInput.disabled = false;
      premiumInput.required = true;
    } else {
      premiumInput.disabled = true;
      premiumInput.required = false;
      premiumInput.value = '';
      calculateTotalRiderPremium();
    }
  }

  // Calculate maturity date from start date and term
  function calculateMaturityDate() {
    const startDate = document.getElementById('start_date')?.value;
    const term = parseInt(document.getElementById('term')?.value || 0);
    if (startDate && term) {
      const start = new Date(startDate);
      start.setFullYear(start.getFullYear() + term);
      document.getElementById('maturity_date').value = start.toISOString().split('T')[0];
    }
  }

  // Format date helper function
  function formatDate(dateStr) {
    if (!dateStr) return '-';
    const date = new Date(dateStr);
    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    return `${date.getDate()}-${months[date.getMonth()]}-${String(date.getFullYear()).slice(-2)}`;
  }

  // Format number helper function
  function formatNumber(num) {
    if (!num && num !== 0) return '-';
    return parseFloat(num).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
  }

  // Open proposal details (full page view) - MUST be defined before HTML onclick handlers
  async function openProposalDetails(id) {
    try {
      const res = await fetch(`/life-proposals/${id}`, {
        headers: {
          'Accept': 'application/json',
          'X-Requested-With': 'XMLHttpRequest'
        }
      });
      if (!res.ok) throw new Error(`HTTP ${res.status}`);
      const proposal = await res.json();
      currentProposalId = id;
      
      // Get all required elements
      const proposalPageName = document.getElementById('proposalPageName');
      const proposalPageTitle = document.getElementById('proposalPageTitle');
      const clientsTableView = document.getElementById('clientsTableView');
      const proposalPageView = document.getElementById('proposalPageView');
      const proposalDetailsPageContent = document.getElementById('proposalDetailsPageContent');
      const proposalFormPageContent = document.getElementById('proposalFormPageContent');
      const editProposalFromPageBtn = document.getElementById('editProposalFromPageBtn');
      const closeProposalPageBtn = document.getElementById('closeProposalPageBtn');
      
      if (!proposalPageName || !proposalPageTitle || !clientsTableView || !proposalPageView || 
          !proposalDetailsPageContent || !proposalFormPageContent) {
        console.error('Required elements not found');
        alert('Error: Page elements not found');
        return;
      }
      
      // Set proposal name in header
      const proposalName = proposal.proposers_name || proposal.prid || 'Unknown';
      proposalPageName.textContent = proposalName;
      proposalPageTitle.textContent = 'Life Proposal';
      
      populateProposalDetails(proposal);
      
      // Hide table view, show page view
      clientsTableView.classList.add('hidden');
      proposalPageView.style.display = 'block';
      proposalPageView.classList.add('show');
      proposalDetailsPageContent.style.display = 'block';
      proposalFormPageContent.style.display = 'none';
      if (editProposalFromPageBtn) editProposalFromPageBtn.style.display = 'inline-block';
      if (closeProposalPageBtn) closeProposalPageBtn.style.display = 'inline-block';
    } catch (e) {
      console.error(e);
      alert('Error loading proposal details: ' + e.message);
    }
  }

  // Populate proposal details view
  function populateProposalDetails(proposal) {
    const content = document.getElementById('proposalDetailsContent');
    if (!content) return;

    const col1 = `
      <div class="detail-section">
        <div class="detail-section-header">PROPOSAL DETAILS</div>
        <div class="detail-section-body">
          <div class="detail-row">
            <span class="detail-label">PRID</span>
            <div class="detail-value">${proposal.prid || '-'}</div>
          </div>
          <div class="detail-row">
            <span class="detail-label">Proposer's Name</span>
            <div class="detail-value">${proposal.proposers_name || '-'}</div>
          </div>
          <div class="detail-row">
            <span class="detail-label">Insurer</span>
            <div class="detail-value">${proposal.insurer || '-'}</div>
          </div>
          <div class="detail-row">
            <span class="detail-label">Policy Plan</span>
            <div class="detail-value">${proposal.policy_plan || '-'}</div>
          </div>
          <div class="detail-row">
            <span class="detail-label">Class</span>
            <div class="detail-value">${proposal.class || '-'}</div>
          </div>
        </div>
      </div>
    `;

    const col2 = `
      <div class="detail-section">
        <div class="detail-section-header">COVERAGE</div>
        <div class="detail-section-body">
          <div class="detail-row">
            <span class="detail-label">Sum Assured</span>
            <div class="detail-value">${formatNumber(proposal.sum_assured)}</div>
          </div>
          <div class="detail-row">
            <span class="detail-label">Term</span>
            <div class="detail-value">${proposal.term || '-'}</div>
          </div>
          <div class="detail-row">
            <span class="detail-label">Add Ons</span>
            <div class="detail-value">${proposal.add_ons || '-'}</div>
          </div>
          <div class="detail-row">
            <span class="detail-label">Premium</span>
            <div class="detail-value">${formatNumber(proposal.premium)}</div>
          </div>
          <div class="detail-row">
            <span class="detail-label">Frequency</span>
            <div class="detail-value">${proposal.frequency || '-'}</div>
          </div>
        </div>
      </div>
    `;

    const col3 = `
      <div class="detail-section">
        <div class="detail-section-header">STATUS & DATES</div>
        <div class="detail-section-body">
          <div class="detail-row">
            <span class="detail-label">Status</span>
            <div class="detail-value">${proposal.status || '-'}</div>
          </div>
          <div class="detail-row">
            <span class="detail-label">Stage</span>
            <div class="detail-value">${proposal.stage || '-'}</div>
          </div>
          <div class="detail-row">
            <span class="detail-label">Offer Date</span>
            <div class="detail-value">${formatDate(proposal.offer_date)}</div>
          </div>
          <div class="detail-row">
            <span class="detail-label">Date</span>
            <div class="detail-value">${formatDate(proposal.date)}</div>
          </div>
          <div class="detail-row">
            <span class="detail-label">Age</span>
            <div class="detail-value">${proposal.age || '-'}</div>
          </div>
        </div>
      </div>
    `;

    const col4 = `
      <div class="detail-section">
        <div class="detail-section-header">MEDICAL & PAYMENT</div>
        <div class="detail-section-body">
          <div class="detail-row">
            <span class="detail-label">Source Of Payment</span>
            <div class="detail-value">${proposal.source_of_payment || '-'}</div>
          </div>
          <div class="detail-row">
            <span class="detail-label">MCR</span>
            <div class="detail-value">${proposal.mcr || '-'}</div>
          </div>
          <div class="detail-row">
            <span class="detail-label">Doctor</span>
            <div class="detail-value">${proposal.doctor || '-'}</div>
          </div>
          <div class="detail-row">
            <span class="detail-label">Date Sent</span>
            <div class="detail-value">${formatDate(proposal.date_sent)}</div>
          </div>
          <div class="detail-row">
            <span class="detail-label">Date Completed</span>
            <div class="detail-value">${formatDate(proposal.date_completed)}</div>
          </div>
        </div>
      </div>
      <div class="detail-section">
        <div class="detail-section-header">ADDITIONAL INFO</div>
        <div class="detail-section-body">
          <div class="detail-row">
            <span class="detail-label">Agency</span>
            <div class="detail-value">${proposal.agency || '-'}</div>
          </div>
          <div class="detail-row">
            <span class="detail-label">Submitted</span>
            <div class="detail-value">
              <input type="checkbox" ${proposal.is_submitted ? 'checked' : ''} disabled>
            </div>
          </div>
          <div class="detail-row" style="align-items:flex-start;">
            <span class="detail-label">Notes</span>
            <textarea class="detail-value" style="min-height:40px; resize:vertical; flex:1; font-size:11px; padding:4px 6px;" readonly>${proposal.notes || ''}</textarea>
          </div>
        </div>
      </div>
    `;

    content.innerHTML = col1 + col2 + col3 + col4;
  }

  // Open proposal page (Add or Edit)
  async function openProposalPage(mode) {
    if (mode === 'add') {
      openProposalForm('add');
    } else {
      if (currentProposalId) {
        openEditProposal(currentProposalId);
      }
    }
  }

  // Event listeners
  document.addEventListener('DOMContentLoaded', function() {
    // Add Proposal Button
    const addBtn = document.getElementById('addProposalBtn');
    if (addBtn) {
      addBtn.addEventListener('click', () => openProposalPage('add'));
    }

    // Column button
    const columnBtn = document.getElementById('columnBtn');
    if (columnBtn) {
      columnBtn.addEventListener('click', () => openColumnModal());
    }

    // Filter toggle handler - just visual indicator, clears filters when unchecked
    const filterToggle = document.getElementById('filterToggle');
    if (filterToggle) {
      const urlParams = new URLSearchParams(window.location.search);
      const hasFollowUp = urlParams.get('follow_up') === 'true' || urlParams.get('follow_up') === '1';
      const hasSubmitted = urlParams.get('submitted') === 'true' || urlParams.get('submitted') === '1';
      filterToggle.checked = hasFollowUp || hasSubmitted;
      
      filterToggle.addEventListener('change', function(e) {
        e.preventDefault();
        e.stopPropagation();
        if (!this.checked) {
          // Clear all filters when toggle is unchecked
          const u = new URL(window.location.href);
          u.searchParams.delete('follow_up');
          u.searchParams.delete('submitted');
          window.location.href = u.toString();
        } else {
          // If checked but no filters active, activate "To Follow Up" by default
          if (!hasFollowUp && !hasSubmitted) {
            const u = new URL(window.location.href);
            u.searchParams.set('follow_up', '1');
            window.location.href = u.toString();
          }
        }
      });
    }

    // To Follow Up button handler
    const followUpBtn = document.getElementById('followUpBtn');
    if (followUpBtn) {
      followUpBtn.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        const u = new URL(window.location.href);
        const currentFollowUp = u.searchParams.get('follow_up');
        if (currentFollowUp === 'true' || currentFollowUp === '1') {
          // Deactivate filter
          u.searchParams.delete('follow_up');
        } else {
          // Activate filter
          u.searchParams.set('follow_up', '1');
          u.searchParams.delete('submitted');
        }
        window.location.href = u.toString();
      });
    }

    // Submitted button handler
    const submittedBtn = document.getElementById('submittedBtn');
    if (submittedBtn) {
      submittedBtn.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        const u = new URL(window.location.href);
        const currentSubmitted = u.searchParams.get('submitted');
        if (currentSubmitted === 'true' || currentSubmitted === '1') {
          // Deactivate filter
          u.searchParams.delete('submitted');
        } else {
          // Activate filter
          u.searchParams.set('submitted', '1');
          u.searchParams.delete('follow_up');
        }
        window.location.href = u.toString();
      });
    }

    // Radio button click handler - update visual dot
    document.querySelectorAll('.action-radio').forEach(radio => {
      radio.addEventListener('change', function() {
        // Update all radio dots
        document.querySelectorAll('.action-radio').forEach(r => {
          const dot = r.nextElementSibling;
          if (dot && dot.classList.contains('radio-dot')) {
            const dotColor = r.dataset.dotColor || 'transparent';
            if (r.checked) {
              dot.style.backgroundColor = dotColor !== 'transparent' ? dotColor : 'transparent';
            } else {
              dot.style.backgroundColor = 'transparent';
            }
          }
        });
      });
    });

    // Contact selection handler
    const contactSelect = document.getElementById('contact_id');
    if (contactSelect) {
      contactSelect.addEventListener('change', function() {
        const option = this.options[this.selectedIndex];
        if (option.value) {
          document.getElementById('salutation').value = option.dataset.salutation || '';
          document.getElementById('dob').value = option.dataset.dob || '';
          const age = calculateAge(option.dataset.dob);
          if (age !== null) {
            document.getElementById('age').value = age;
            document.getElementById('anb').value = calculateANB(age);
          }
          document.getElementById('proposers_name').value = option.text;
        }
      });
    }

    // DOB change handler
    const dobInput = document.getElementById('dob');
    if (dobInput) {
      dobInput.addEventListener('change', function() {
        const age = calculateAge(this.value);
        if (age !== null) {
          document.getElementById('age').value = age;
          document.getElementById('anb').value = calculateANB(age);
        }
      });
    }

    // Rider checkbox handlers
    document.querySelectorAll('.rider-checkbox[data-rider]').forEach(checkbox => {
      checkbox.addEventListener('change', function() {
        toggleRiderPremium(this);
        if (this.checked) {
          calculateTotalRiderPremium();
        }
      });
    });

    // Rider premium input handlers
    document.querySelectorAll('.rider-premium').forEach(input => {
      input.addEventListener('input', calculateTotalRiderPremium);
    });

    // Client selection handler
    const clientSelect = document.getElementById('client_id');
    if (clientSelect) {
      clientSelect.addEventListener('change', function() {
        const option = this.options[this.selectedIndex];
        if (option.value) {
          document.getElementById('source_name').value = option.text;
        }
      });
    }

    // Term and start date handlers for maturity date
    const termInput = document.getElementById('term');
    const startDateInput = document.getElementById('start_date');
    if (termInput) termInput.addEventListener('change', calculateMaturityDate);
    if (startDateInput) startDateInput.addEventListener('change', calculateMaturityDate);
  });

  async function openEditProposal(id) {
    try {
      const res = await fetch(`/life-proposals/${id}/edit`, {
        headers: {
          'Accept': 'application/json',
          'X-Requested-With': 'XMLHttpRequest'
        }
      });
      if (!res.ok) throw new Error('Network error');
      const proposal = await res.json();
      currentProposalId = id;
      openProposalForm('edit', proposal);
    } catch (e) {
      console.error(e);
      alert('Error loading proposal data');
    }
  }

  function openProposalForm(mode, proposal = null) {
    // Clone form from modal
    const modalForm = document.getElementById('proposalModal').querySelector('form');
    const pageForm = document.getElementById('proposalPageForm');
    const formContentDiv = pageForm.querySelector('div[style*="padding:12px"]');
    
    // Clone the modal form body
    const modalBody = modalForm.querySelector('.modal-body');
    if (modalBody && formContentDiv) {
      formContentDiv.innerHTML = modalBody.innerHTML;
    }

    const formMethod = document.getElementById('proposalPageFormMethod');
    const deleteBtn = document.getElementById('proposalDeleteBtn');
    const editBtn = document.getElementById('editProposalFromPageBtn');
    const closeBtn = document.getElementById('closeProposalPageBtn');
    const closeFormBtn = document.getElementById('closeProposalFormBtn');

    if (mode === 'add') {
      document.getElementById('proposalPageTitle').textContent = 'Add Life Proposal';
      document.getElementById('proposalPageName').textContent = '';
      pageForm.action = '{{ route("life-proposals.store") }}';
      formMethod.innerHTML = '';
      deleteBtn.style.display = 'none';
      if (editBtn) editBtn.style.display = 'none';
      if (closeBtn) closeBtn.style.display = 'inline-block';
      if (closeFormBtn) closeFormBtn.style.display = 'none';
      pageForm.reset();
    } else {
      const proposalName = proposal.proposers_name || proposal.prid || 'Unknown';
      document.getElementById('proposalPageTitle').textContent = 'Edit Life Proposal';
      document.getElementById('proposalPageName').textContent = proposalName;
      pageForm.action = `/life-proposals/${currentProposalId}`;
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

      const fields = ['proposers_name','insurer','policy_plan','sum_assured','term','add_ons','offer_date','premium','frequency','stage','date','age','status','source_of_payment','mcr','doctor','date_sent','date_completed','notes','agency','class','prid'];
      fields.forEach(k => {
        const el = formContentDiv ? formContentDiv.querySelector(`#${k}`) : null;
        if (!el) return;
        if (el.type === 'checkbox') {
          el.checked = !!proposal[k];
        } else if (el.type === 'date') {
          el.value = proposal[k] ? (typeof proposal[k] === 'string' ? proposal[k].substring(0,10) : proposal[k]) : '';
        } else {
          el.value = proposal[k] ?? '';
        }
      });
      const isSubmittedCheckbox = formContentDiv ? formContentDiv.querySelector('#is_submitted') : null;
      if (isSubmittedCheckbox) {
        isSubmittedCheckbox.checked = !!proposal.is_submitted;
      }
    }

    // Hide table view, show page view
    document.getElementById('clientsTableView').classList.add('hidden');
    const proposalPageView = document.getElementById('proposalPageView');
    proposalPageView.style.display = 'block';
    proposalPageView.classList.add('show');
    document.getElementById('proposalDetailsPageContent').style.display = 'none';
    document.getElementById('proposalFormPageContent').style.display = 'block';
  }

  function closeProposalPageView() {
    const proposalPageView = document.getElementById('proposalPageView');
    proposalPageView.classList.remove('show');
    proposalPageView.style.display = 'none';
    document.getElementById('clientsTableView').classList.remove('hidden');
    document.getElementById('proposalDetailsPageContent').style.display = 'none';
    document.getElementById('proposalFormPageContent').style.display = 'none';
    currentProposalId = null;
  }

  // Edit button from details page
  const editBtn = document.getElementById('editProposalFromPageBtn');
  if (editBtn) {
    editBtn.addEventListener('click', function() {
      if (currentProposalId) {
        openEditProposal(currentProposalId);
      }
    });
  }

  // Legacy function for backward compatibility
  function openProposalModal(mode, proposal = null) {
    if (mode === 'add') {
      openProposalPage('add');
    } else if (proposal && currentProposalId) {
      openEditProposal(currentProposalId);
    }
  }

  function closeProposalModal() {
    closeProposalPageView();
  }

  function deleteProposal() {
    if (!currentProposalId) return;
    if (!confirm('Delete this proposal?')) return;
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = `/life-proposals/${currentProposalId}`;
    const csrf = document.createElement('input'); csrf.type='hidden'; csrf.name='_token'; csrf.value='{{ csrf_token() }}'; form.appendChild(csrf);
    const method = document.createElement('input'); method.type='hidden'; method.name='_method'; method.value='DELETE'; form.appendChild(method);
    document.body.appendChild(form);
    form.submit();
  }
</script>

@include('partials.table-scripts', [
  'mandatoryColumns' => $mandatoryColumns,
])

@endsection
