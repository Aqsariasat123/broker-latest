@extends('layouts.app')
@section('content')

@include('partials.table-styles')

<style>
  /* Orange styling for radio buttons in policies table */
  .bell-radio {
    accent-color: #f3742a !important;
    width: 18px !important;
    height: 18px !important;
    cursor: not-allowed;
    pointer-events: none;
  }
  
  .bell-radio:checked {
    accent-color: #f3742a !important;
  }
  
  .bell-radio.expired {
    accent-color: #dc3545 !important;
  }
  
  .bell-radio.dfr {
    accent-color: #f3742a !important;
  }
  
  .bell-radio.normal {
    accent-color: #f3742a !important;
  }
  
  /* Orange styling for checkboxes in policy forms */
  #policyForm input[type="checkbox"],
  .modal-body input[type="checkbox"],
  #policyFormPageContent input[type="checkbox"],
  .form-group input[type="checkbox"] {
    width: 18px !important;
    height: 18px !important;
    accent-color: #f3742a !important;
    cursor: pointer;
    appearance: none;
    -webkit-appearance: none;
    -moz-appearance: none;
    border: 2px solid #ccc;
    border-radius: 3px;
    background: #fff;
    position: relative;
    margin: 0;
    margin-right: 8px;
    vertical-align: middle;
    flex-shrink: 0;
  }
  
  #policyForm input[type="checkbox"]:checked,
  .modal-body input[type="checkbox"]:checked,
  #policyFormPageContent input[type="checkbox"]:checked,
  .form-group input[type="checkbox"]:checked {
    background-color: #f3742a !important;
    border-color: #f3742a !important;
    accent-color: #f3742a !important;
  }
  
  #policyForm input[type="checkbox"]:checked::after,
  .modal-body input[type="checkbox"]:checked::after,
  #policyFormPageContent input[type="checkbox"]:checked::after,
  .form-group input[type="checkbox"]:checked::after {
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
  
  /* Style for checkbox labels */
  .form-group label[for="renewable"] {
    display: flex;
    align-items: center;
    gap: 8px;
  }
  
  /* Orange styling for checkboxes in detail view */
  .detail-value.checkbox input[type="checkbox"] {
    accent-color: #f3742a !important;
    appearance: none;
    -webkit-appearance: none;
    -moz-appearance: none;
    width: 18px !important;
    height: 18px !important;
    border: 2px solid #ccc;
    border-radius: 3px;
    background: #fff;
    position: relative;
    cursor: default;
  }
  
  .detail-value.checkbox input[type="checkbox"]:checked {
    background-color: #f3742a !important;
    border-color: #f3742a !important;
    accent-color: #f3742a !important;
  }
  
  .detail-value.checkbox input[type="checkbox"]:checked::after {
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
  
  /* Orange styling for all checkboxes in column selection and other modals */
  .column-item input[type="checkbox"],
  .column-selection input[type="checkbox"],
  input[type="checkbox"]:checked {
    accent-color: #f3742a !important;
  }
  
  .column-item input[type="checkbox"]:checked,
  .column-selection input[type="checkbox"]:checked {
    background-color: #f3742a !important;
    border-color: #f3742a !important;
    accent-color: #f3742a !important;
  }
  
  /* Ensure all checkboxes that are checked show orange */
  input[type="checkbox"][checked],
  input[type="checkbox"]:checked {
    background-color: #f3742a !important;
    border-color: #f3742a !important;
    accent-color: #f3742a !important;
  }
  
  /* Policy Details Page Styles */
  .policy-tab {
    padding: 6px 12px;
    background: #333;
    border: none;
    border-right: 1px solid #444;
    cursor: pointer;
    font-size: 11px;
    color: #fff;
    transition: all 0.2s;
  }
  
  .policy-tab:last-child {
    border-right: none;
  }
  
  .policy-tab:hover {
    background: #555;
  }
  
  .policy-tab.active {
    background: #333;
    color: #fff;
    font-weight: 600;
  }
  
  /* Policy Page Header - matching clients page */
  .client-page-header { 
    background:#fff; 
    color:#000; 
    padding:15px 20px; 
    display:flex; 
    justify-content:space-between; 
    align-items:center; 
    border-bottom:1px solid #ddd; 
    position:sticky; 
    margin-bottom: 10px;
    top:0; 
    z-index:10; 
    box-shadow:0 2px 4px rgba(0,0,0,0.1); 
  }
  .client-page-title { 
    font-size:20px; 
    font-weight:bold; 
    color:#000; 
    display:flex; 
    align-items:center; 
    gap:8px; 
  }
  .client-page-title .client-name { 
    color:#f3742a; 
  }
  .client-page-nav { 
    display:flex; 
    gap:8px; 
  }
  .client-page-actions { 
    display:flex; 
    gap:8px; 
  }
  
  .detail-section-card {
    border: none;
    border-radius: 0;
    padding: 0;
    background: #fff;
    margin-right: 8px;
    min-width: 0;
  }
  
  .detail-section-card:last-child {
    margin-right: 0;
  }
  
  /* Add gap between cards for clear separation */
  #policyDetailsContent,
  #policyScheduleContent {
    gap: 0;
  }
  
  .detail-section-header {
    background: #a0a0a0;
    color: #fff;
    padding: 4px 8px;
    font-weight: bold;
    font-size: 11px;
    border-bottom: 1px solid #ddd;
    text-transform: uppercase;
    line-height: 1.3;
    margin-bottom: 0;
  }
  
  .detail-row {
    display: flex !important;
    flex-direction: row !important;
    align-items: center;
    margin-bottom: 6px;
    gap: 6px;
  }
  
  .detail-row:last-child {
    margin-bottom: 0;
  }
  
  .detail-label {
    font-size: 10px;
    color: #555;
    font-weight: 600;
    line-height: 1.2;
    display: block;
    min-width: 90px;
    flex-shrink: 0;
  }
  
  .detail-value {
    font-size: 10px;
    color: #000;
    padding: 3px 5px;
    border: 1px solid #ddd;
    background: #fff;
    border-radius: 2px;
    min-height: 20px;
    display: flex;
    align-items: center;
    box-sizing: border-box;
    flex: 1;
    min-width: 0;
  }
  
  .detail-section-body {
    padding: 6px 8px;
  }
  
  .detail-value input[type="text"],
  .detail-value select {
    width: 100%;
    border: 1px solid #ddd;
    padding: 3px 5px;
    font-size: 10px;
    background: #fff;
    border-radius: 2px;
    box-sizing: border-box;
    max-width: 100%;
    min-width: 0;
  }
  
  .detail-value textarea {
    width: 100%;
    border: 1px solid #ddd;
    padding: 3px 5px;
    font-size: 10px;
    box-sizing: border-box;
    max-width: 100%;
    min-width: 0;
    font-size: 12px;
    background: #fff;
    border-radius: 2px;
    resize: vertical;
    min-height: 50px;
  }
  
  .btn-dfr {
    background: #f3742a;
    color: #fff;
    border: none;
    padding: 3px 8px;
    border-radius: 2px;
    font-size: 10px;
    cursor: pointer;
    margin-left: 6px;
  }
  
  .btn-sms {
    background: #28a745;
    color: #fff;
    border: none;
    padding: 3px 8px;
    border-radius: 2px;
    font-size: 10px;
    cursor: pointer;
    margin-left: 6px;
  }
  
  .renewal-checkbox {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 10px;
  }
  
  .policy-actions-top {
    display: flex;
    gap: 8px;
    justify-content: flex-end;
    margin-bottom: 12px;
  }
  
  .document-icon {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 4px;
    padding: 6px;
    border: 1px solid #ddd;
    border-radius: 3px;
    cursor: pointer;
    transition: all 0.2s;
  }
  
  .document-icon:hover {
    background: #f5f5f5;
    border-color: #f3742a;
  }
  
  .document-icon img {
    width: 30px;
    height: 30px;
  }
  
  .document-icon svg {
    width: 30px;
    height: 30px;
  }
  
  .document-icon span {
    font-size: 10px;
    color: #666;
  }
  
  /* Compact layout for policy details page */
  #policyPageView .client-page-body {
    padding-bottom: 0;
    overflow-y: auto;
    overflow-x: hidden;
    -webkit-overflow-scrolling: touch;
    background: #f5f5f5;
  }
  
  #policyPageView .client-page-content {
    padding: 0;
    overflow-x: hidden;
    background: #f5f5f5;
  }
  
  #policyDetailsPageContent {
    overflow-x: hidden;
  }
  
  #policyDetailsContent,
  #policyScheduleContent {
    min-width: 0;
    width: 100%;
  }
  
  @media (max-width: 1400px) {
    #policyDetailsContent,
    #policyScheduleContent,
    #policyFormContent,
    #policyFormScheduleContent {
      grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
    }
  }
  
  @media (max-width: 768px) {
    #policyDetailsContent,
    #policyScheduleContent,
    #policyFormContent,
    #policyFormScheduleContent {
      grid-template-columns: 1fr !important;
    }
    .detail-section-card {
      margin-right: 0 !important;
      margin-bottom: 10px;
    }
  }
</style>

@php
  $config = \App\Helpers\TableConfigHelper::getConfig('policies');
  $selectedColumns = \App\Helpers\TableConfigHelper::getSelectedColumns('policies');
  $columnDefinitions = $config['column_definitions'];
  $mandatoryColumns = $config['mandatory_columns'];
@endphp

<div class="dashboard">
  <!-- Main Policies Table View -->
  <div class="clients-table-view" id="clientsTableView">
  <div class="container-table">
    <!-- Policies Card -->
    <div style="background:#fff; border:1px solid #ddd; border-radius:4px; overflow:hidden;">
      <div class="page-header" style="background:#fff; border-bottom:1px solid #ddd; margin-bottom:0;">
      <div class="page-title-section">
        <h3>Policies</h3>
        <div class="records-found">Records Found - {{ $policies->total() }}</div>
        <div style="display:flex; align-items:center; gap:15px; margin-top:10px;">
        <div class="filter-group">
            @if(request()->get('dfr') == 'true')
              <button class="btn btn-list-all" id="listAllBtn">List ALL</button>
            @else
              <button class="btn btn-follow-up" id="dfrOnlyBtn">Due For Renewal</button>
            @endif
        </div>
        </div>
      </div>
      <div class="action-buttons">
        <button type="button" class="btn btn-add" id="addPolicyBtn">Add</button>
      </div>
    </div>

    @if(session('success'))
      <div class="alert alert-success" id="successAlert" style="padding:8px 12px; margin:15px 20px; border:1px solid #c3e6cb; background:#d4edda; color:#155724;">
        {{ session('success') }}
        <button type="button" class="alert-close" onclick="document.getElementById('successAlert').style.display='none'" style="float:right;background:none;border:none;font-size:16px;cursor:pointer;">×</button>
      </div>
    @endif

    <div class="table-responsive" id="tableResponsive">
      <table id="policiesTable">
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
          @foreach($policies as $policy)
            @php
              $policyStatusName = $policy->policy_status_name ?? 'N/A';
              $isDFR = stripos($policyStatusName, 'DFR') !== false || (optional($policy->end_date) && $policy->end_date->isBetween(now(), now()->addDays(30)));
              $isExpired = stripos($policyStatusName, 'Expired') !== false || (optional($policy->end_date) && $policy->end_date->isPast());
            @endphp
            <tr class="{{ $isExpired ? 'expired-row' : ($isDFR ? 'dfr-row' : '') }}">
              <td class="bell-cell {{ $isExpired ? 'expired' : ($isDFR ? 'dfr' : '') }}">
                <div style="display:flex; align-items:center; justify-content:center;">
                  <input type="radio" name="policy_select" class="bell-radio {{ $isExpired ? 'expired' : ($isDFR ? 'dfr' : 'normal') }}" value="{{ $policy->id }}" disabled {{ ($isExpired || $isDFR) ? 'checked' : '' }}>
                </div>
              </td>
              <td class="action-cell">
                <svg class="action-expand" onclick="openPolicyDetails({{ $policy->id }})" width="22" height="22" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="cursor:pointer; vertical-align:middle;">
                  <rect x="9" y="9" width="6" height="6" stroke="#2d2d2d" stroke-width="1.5" fill="none"/>
                  <path d="M12 9L12 5M12 15L12 19M9 12L5 12M15 12L19 12" stroke="#2d2d2d" stroke-width="1.5" stroke-linecap="round"/>
                  <path d="M12 5L10 7M12 5L14 7M12 19L10 17M12 19L14 17M5 12L7 10M5 12L7 14M19 12L17 10M19 12L17 14" stroke="#2d2d2d" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <svg class="action-clock" onclick="window.location.href='{{ route('policies.index') }}?dfr=true'" width="22" height="22" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="cursor:pointer; vertical-align:middle;">
                  <circle cx="12" cy="12" r="9" stroke="#2d2d2d" stroke-width="1.5" fill="none"/>
                  <path d="M12 7V12L15 15" stroke="#2d2d2d" stroke-width="1.5" stroke-linecap="round"/>
                </svg>
                <span class="action-ellipsis" style="cursor:pointer;">⋯</span>
              </td>
              @foreach($selectedColumns as $col)
                @if($col == 'policy_no')
                  <td data-column="policy_no"><a href="javascript:void(0)" onclick="openPolicyDetails({{ $policy->id }})" style="color:#007bff; text-decoration:underline;">{{ $policy->policy_no }}</a></td>
                @elseif($col == 'client_name')
                  <td data-column="client_name">
                    @php $clientName = $policy->client_name; @endphp
                    @if($clientName){{ $clientName }}@else<span style="color:#999;" title="Client ID: {{ $policy->client_id ?? 'NULL' }}">—</span>@endif
                  </td>
                @elseif($col == 'insurer')
                  <td data-column="insurer">
                    @php $insurerName = $policy->insurer_name; @endphp
                    @if($insurerName){{ $insurerName }}@else<span style="color:#999;" title="Insurer ID: {{ $policy->insurer_id ?? 'NULL' }}">—</span>@endif
                  </td>
                @elseif($col == 'policy_class')
                  <td data-column="policy_class">{{ $policy->policy_class_name ?? '—' }}</td>
                @elseif($col == 'policy_plan')
                  <td data-column="policy_plan">{{ $policy->policy_plan_name ?? '—' }}</td>
                @elseif($col == 'sum_insured')
                  <td data-column="sum_insured">{{ $policy->sum_insured ? number_format($policy->sum_insured,2) : '###########' }}</td>
                @elseif($col == 'start_date')
                  <td data-column="start_date">{{ $policy->start_date ? $policy->start_date->format('d-M-y') : '###########' }}</td>
                @elseif($col == 'end_date')
                  <td data-column="end_date">{{ $policy->end_date ? $policy->end_date->format('d-M-y') : '###########' }}</td>
                @elseif($col == 'insured')
                  <td data-column="insured">{{ $policy->insured ?? '###########' }}</td>
                @elseif($col == 'policy_status')
                  @php
                    $statusColor = '#6c757d';
                    if ($isExpired || stripos($policyStatusName, 'Expired') !== false) {
                      $statusColor = '#dc3545';
                    } elseif ($isDFR || stripos($policyStatusName, 'DFR') !== false || stripos($policyStatusName, 'Due') !== false) {
                      $statusColor = '#ffc107';
                    } elseif (stripos($policyStatusName, 'In Force') !== false) {
                      $statusColor = '#28a745';
                    } elseif (stripos($policyStatusName, 'Cancelled') !== false) {
                      $statusColor = '#dc3545';
                    }
                  @endphp
                  <td data-column="policy_status"><span class="badge-status" style="background:{{ $statusColor }}">{{ $policyStatusName }}</span></td>
                @elseif($col == 'date_registered')
                  <td data-column="date_registered">{{ $policy->date_registered ? $policy->date_registered->format('d-M-y') : '###########' }}</td>
                @elseif($col == 'policy_id')
                  <td data-column="policy_id">{{ $policy->policy_code ?? $policy->id }}</td>
                @elseif($col == 'insured_item')
                  <td data-column="insured_item">{{ $policy->insured_item ?? '-' }}</td>
                @elseif($col == 'renewable')
                  <td data-column="renewable">{{ $policy->renewable ? 'Yes' : 'No' }}</td>
                @elseif($col == 'biz_type')
                  <td data-column="biz_type">{{ $policy->business_type_name ?? 'N/A' }}</td>
                @elseif($col == 'term')
                  <td data-column="term">{{ $policy->term ?? '-' }}</td>
                @elseif($col == 'term_unit')
                  <td data-column="term_unit">{{ $policy->term_unit ?? '-' }}</td>
                @elseif($col == 'base_premium')
                  <td data-column="base_premium">{{ $policy->base_premium ? number_format($policy->base_premium,2) : '###########' }}</td>
                @elseif($col == 'premium')
                  <td data-column="premium">{{ $policy->premium ? number_format($policy->premium,2) : '###########' }}</td>
                @elseif($col == 'frequency')
                  <td data-column="frequency">{{ $policy->frequency_name ?? 'N/A' }}</td>
                @elseif($col == 'pay_plan')
                  <td data-column="pay_plan">{{ $policy->pay_plan_name ?? 'N/A' }}</td>
                @elseif($col == 'agency')
                  <td data-column="agency">{{ $policy->agency_name ?? ($policy->agent ?? '-') }}</td>
                @elseif($col == 'agent')
                  <td data-column="agent">{{ $policy->agent ?? '-' }}</td>
                @elseif($col == 'notes')
                  <td data-column="notes">{{ $policy->notes ?? '-' }}</td>
                @endif
              @endforeach
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    </div>

    <div class="footer" style="background:#fff; border-top:1px solid #ddd; margin-top:0;">
      <div class="footer-left">
        <a class="btn btn-export" href="{{ route('policies.export', array_merge(request()->query(), ['page' => $policies->currentPage()])) }}">Export</a>
        <button class="btn btn-column" id="columnBtn" type="button">Column</button>
      </div>
        <div class="paginator">
        @php
          $base = url()->current();
          $q = request()->query();
          $current = $policies->currentPage();
          $last = max(1,$policies->lastPage());
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

  <!-- Policy Page View (Full Page) -->
  <div class="client-page-view" id="policyPageView" style="display:none;">
    <!-- Header Card with Policy Number -->
    <div class="client-page-header">
      <div class="client-page-title">
        <span id="policyPageTitle">Policy No</span> - <span class="client-name" id="policyPageName">-</span>
      </div>
      </div>
    
    <div class="client-page-body">
      <div class="client-page-content">
        <!-- Navigation Tabs and Actions Card -->
      
        
        <!-- Policy Details Content Card - Separate -->
        <div id="policyDetailsContentWrapper" style="display:none; background:#fff; border:1px solid #ddd; border-radius:4px; margin-bottom:15px; padding:12px; overflow:hidden;">
        <div id="policyDetailsPageContent" style="display:none;">
          <div style="background:#fff; border:1px solid #ddd; border-radius:4px; margin-bottom:15px; overflow:hidden;">
              <div style="display:flex; justify-content:space-between; align-items:center; padding:12px 15px; border-bottom:1px solid #ddd; background:#fff;">
                <div class="client-page-nav">
                  <button class="policy-tab active" data-tab="schedules">Schedules</button>
                  <button class="policy-tab" data-tab="payments">Payments</button>
                  <button class="policy-tab" data-tab="vehicles">Vehicles</button>
                  <button class="policy-tab" data-tab="claims">Claims</button>
                  <button class="policy-tab" data-tab="documents">Documents</button>
                  <button class="policy-tab" data-tab="endorsements">Endorsements</button>
                  <button class="policy-tab" data-tab="commission">Commission</button>
                  <button class="policy-tab" data-tab="nominees">Nominees</button>
                </div>
                <div class="client-page-actions" id="policyHeaderActions">
                  <button class="btn btn-edit" id="editPolicyFromPageBtn" style="background:#f3742a; color:#fff; border:none; padding:4px 12px; border-radius:2px; cursor:pointer; font-size:12px; display:none;" onclick="if(currentPolicyId) openEditPolicy(currentPolicyId)">Edit</button>
                  <button class="btn" id="closePolicyPageBtn" onclick="closePolicyPageView()" style="background:#e0e0e0; color:#000; border:none; padding:4px 12px; border-radius:2px; cursor:pointer; font-size:12px;">Close</button>
                </div>
              </div>
            </div>
          </div> 
        <div id="policyDetailsContent" style="display:grid; grid-template-columns:repeat(4, minmax(0, 1fr)); gap:0; padding:0;">
              <!-- Content will be loaded via JavaScript -->
            </div>
        </div>
        
        <!-- Policy Schedule Card - Separate -->
        <div id="policyScheduleContentWrapper" style="display:none; background:#fff; border:1px solid #ddd; border-radius:4px; padding:12px;  margin-bottom:15px; overflow:hidden;">
          <div style="padding:10px 10px 8px 10px; border-bottom:1px solid #ddd;">
            <h4 style="margin:0; font-size:12px; font-weight:600; color:#333;">Policy Schedule</h4>
          </div>
          <div id="policyScheduleContent" style="display:grid; grid-template-columns:repeat(4, minmax(0, 1fr)); gap:0; padding:0;">
            <!-- Content will be loaded via JavaScript -->
          </div>
        </div>
        
        <!-- Documents Card - Separate -->
        <div id="documentsContentWrapper" style="display:none; background:#fff; border:1px solid #ddd; border-radius:4px; margin-bottom:15px; overflow:hidden;">
          <div style="display:flex; justify-content:space-between; align-items:center; padding:10px; border-bottom:1px solid #ddd;">
            <h4 style="margin:0; font-size:12px; font-weight:600; color:#333;">Documents</h4>
            <button class="btn" style="background:#f3742a; color:#fff; border:none; padding:4px 12px; border-radius:2px; cursor:pointer; font-size:12px;">Add Document</button>
          </div>
          <div id="documentsContent" style="display:flex; gap:10px; flex-wrap:wrap; padding:10px;">
            <!-- Documents will be loaded via JavaScript -->
          </div>
        </div>
        
        <!-- Policy Add Form -->
        <div id="policyFormPageContent" style="display:none;">
          <!-- Navigation Tabs and Actions Card -->
          <div style="background:#fff; border:1px solid #ddd; border-radius:4px; margin-bottom:15px; overflow:hidden;">
            <div style="display:flex; justify-content:space-between; align-items:center; padding:12px 15px; border-bottom:1px solid #ddd; background:#fff;">
              <div class="client-page-nav">
                <button class="policy-tab active" data-tab="schedules">Schedules</button>
                <button class="policy-tab" data-tab="payments">Payments</button>
                <button class="policy-tab" data-tab="vehicles">Vehicles</button>
                <button class="policy-tab" data-tab="claims">Claims</button>
                <button class="policy-tab" data-tab="documents">Documents</button>
                <button class="policy-tab" data-tab="endorsements">Endorsements</button>
                <button class="policy-tab" data-tab="commission">Commission</button>
                <button class="policy-tab" data-tab="nominees">Nominees</button>
              </div>
              <div class="client-page-actions" id="policyFormHeaderActions">
                <button type="submit" form="policyForm" class="btn-save" id="policySaveBtnHeader" style="display:inline-block; background:#f3742a; color:#fff; border:none; padding:4px 12px; border-radius:2px; cursor:pointer; font-size:12px;">Save</button>
                <button type="button" class="btn" id="closePolicyFormBtnHeader" style="display:inline-block; background:#e0e0e0; color:#000; border:none; padding:4px 12px; border-radius:2px; cursor:pointer; font-size:12px;" onclick="closePolicyPageView()">Cancel</button>
            </div>
            </div>
          </div>
          
          <!-- Policy Form - Single form wrapping all fields -->
          <form id="policyForm" method="POST" action="{{ route('policies.store') }}" enctype="multipart/form-data">
              @csrf
              <div id="policyFormMethod" style="display:none;"></div>
            
            <!-- Policy Form Content Card -->
            <div id="policyFormContentWrapper" style="background:#fff; border:1px solid #ddd; border-radius:4px; margin-bottom:15px; padding:12px; overflow:hidden;">
              <!-- Top Section: Policy Information -->
              <div id="policyFormContent" style="display:grid; grid-template-columns:repeat(4, minmax(0, 1fr)); gap:0; padding:0; min-height:0;">
                <!-- Content will be loaded via JavaScript -->
              </div>
          </div>
            
            <!-- Policy Schedule Card -->
            <div style="background:#fff; border:1px solid #ddd; border-radius:4px; margin-bottom:15px; padding:12px; overflow:hidden;">
              <div style="padding:10px 10px 8px 10px; border-bottom:1px solid #ddd; margin: -12px -12px 0 -12px;">
                <h4 style="margin:0; font-size:12px; font-weight:600; color:#333;">Policy Schedule</h4>
        </div>
              <div id="policyFormScheduleContent" style="display:grid; grid-template-columns:repeat(4, minmax(0, 1fr)); gap:0; padding:0; margin-top:8px;">
                <!-- Content will be loaded via JavaScript -->
              </div>
            </div>
            
            <!-- Documents Card -->
            <div style="background:#fff; border:1px solid #ddd; border-radius:4px; margin-bottom:15px; overflow:hidden;">
              <div style="display:flex; justify-content:space-between; align-items:center; padding:10px; border-bottom:1px solid #ddd;">
                <h4 style="margin:0; font-size:12px; font-weight:600; color:#333;">Documents</h4>
                <div>
                  <input type="file" id="documentUpload" name="documents[]" multiple accept=".pdf,.jpg,.jpeg,.png" style="display:none;" onchange="handleDocumentUpload(event)">
                  <button type="button" class="btn" style="background:#f3742a; color:#fff; border:none; padding:4px 12px; border-radius:2px; cursor:pointer; font-size:12px;" onclick="document.getElementById('documentUpload').click()">Add Document</button>
                </div>
              </div>
              <div id="policyFormDocumentsContent" style="display:flex; gap:10px; flex-wrap:wrap; padding:10px;">
                <!-- Documents will be loaded via JavaScript -->
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Add Policy Modal (hidden, used for form structure) -->
  <div class="modal" id="policyModal" style="display:none;">
    <div class="modal-content">
      <div class="modal-header">
        <h4 id="policyModalTitle">Add Policy</h4>
        <button type="button" class="modal-close" onclick="closePolicyModal()">×</button>
        </div>
      <form id="policyModalForm" method="POST" action="{{ route('policies.store') }}">
        @csrf
        <div id="policyFormMethod" style="display:none;"></div>
        <div class="modal-body">
          <div class="form-row">
            <div class="form-group">
              <label for="policy_no">Policy No *</label>
              <input id="policy_no" name="policy_no" class="form-control" required>
            </div>
            <div class="form-group">
              <label for="client_id">Client *</label>
              <select id="client_id" name="client_id" class="form-control" required>
                <option value="">Select</option>
                @foreach($lookupData['clients'] as $client)
                  <option value="{{ $client['id'] }}">{{ $client['client_name'] }} ({{ $client['clid'] ?? '' }})</option>
                @endforeach
              </select>
            </div>
            <div class="form-group">
              <label for="insurer_id">Insurer *</label>
              <select id="insurer_id" name="insurer_id" class="form-control" required>
                <option value="">Select</option>
                @foreach($lookupData['insurers'] as $insurer)
                  <option value="{{ $insurer['id'] }}">{{ $insurer['name'] }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="form-row">
            <div class="form-group">
              <label for="policy_class_id">Policy Class *</label>
              <select id="policy_class_id" name="policy_class_id" class="form-control" required>
                <option value="">Select</option>
                @foreach($lookupData['policy_classes'] as $class)
                  <option value="{{ $class['id'] }}">{{ $class['name'] }}</option>
                @endforeach
              </select>
            </div>
            <div class="form-group">
              <label for="policy_plan_id">Policy Plan *</label>
              <select id="policy_plan_id" name="policy_plan_id" class="form-control" required>
                <option value="">Select</option>
                @foreach($lookupData['policy_plans'] as $plan)
                  <option value="{{ $plan['id'] }}">{{ $plan['name'] }}</option>
                @endforeach
              </select>
            </div>
            <div class="form-group">
              <label for="sum_insured">Sum Insured</label>
              <input id="sum_insured" name="sum_insured" type="number" step="0.01" class="form-control">
            </div>
          </div>
          <div class="form-row">
            <div class="form-group">
              <label for="start_date">Start Date *</label>
              <input id="start_date" name="start_date" type="date" class="form-control" required>
            </div>
            <div class="form-group">
              <label for="end_date">End Date *</label>
              <input id="end_date" name="end_date" type="date" class="form-control" required>
            </div>
            <div class="form-group">
              <label for="insured">Insured</label>
              <input id="insured" name="insured" class="form-control">
            </div>
          </div>
          <div class="form-row">
            <div class="form-group">
              <label for="policy_status_id">Policy Status</label>
              <select id="policy_status_id" name="policy_status_id" class="form-control">
                <option value="">Select</option>
                @foreach($lookupData['policy_statuses'] as $status)
                  <option value="{{ $status['id'] }}">{{ $status['name'] }}</option>
                @endforeach
              </select>
            </div>
            <div class="form-group">
              <label for="date_registered">Date Registered *</label>
              <input id="date_registered" name="date_registered" type="date" class="form-control" required>
            </div>
            <div class="form-group">
              <label for="insured_item">Insured Item</label>
              <input id="insured_item" name="insured_item" class="form-control">
            </div>
          </div>
          <div class="form-row">
            <div class="form-group">
              <label for="renewable">Renewable</label>
              <input id="renewable" name="renewable" type="checkbox" value="1">
            </div>
            <div class="form-group">
              <label for="business_type_id">Business Type</label>
              <select id="business_type_id" name="business_type_id" class="form-control">
                <option value="">Select</option>
                @foreach($lookupData['business_types'] ?? [] as $bizType)
                  <option value="{{ $bizType['id'] }}">{{ $bizType['name'] }}</option>
                @endforeach
              </select>
            </div>
            <div class="form-group">
              <label for="term">Term</label>
              <input id="term" name="term" type="number" class="form-control">
            </div>
          </div>
          <div class="form-row">
            <div class="form-group">
              <label for="term_unit">Term Unit</label>
              <input id="term_unit" name="term_unit" class="form-control">
            </div>
            <div class="form-group">
              <label for="base_premium">Base Premium</label>
              <input id="base_premium" name="base_premium" type="number" step="0.01" class="form-control">
            </div>
            <div class="form-group">
              <label for="premium">Premium</label>
              <input id="premium" name="premium" type="number" step="0.01" class="form-control">
            </div>
          </div>
          <div class="form-row">
            <div class="form-group">
              <label for="frequency_id">Frequency *</label>
              <select id="frequency_id" name="frequency_id" class="form-control" required>
                <option value="">Select</option>
                @foreach($lookupData['frequencies'] as $freq)
                  <option value="{{ $freq['id'] ?? '' }}">{{ $freq['name'] }}</option>
                @endforeach
              </select>
            </div>
            <div class="form-group">
              <label for="pay_plan_lookup_id">Pay Plan *</label>
              <select id="pay_plan_lookup_id" name="pay_plan_lookup_id" class="form-control" required>
                <option value="">Select</option>
                @foreach($lookupData['pay_plans'] as $payPlan)
                  <option value="{{ $payPlan['id'] ?? '' }}">{{ $payPlan['name'] }}</option>
                @endforeach
              </select>
            </div>
            <div class="form-group">
              <label for="agency_id">Agency</label>
              <select id="agency_id" name="agency_id" class="form-control">
                <option value="">Select</option>
                @foreach($lookupData['agencies'] ?? [] as $agency)
                  <option value="{{ $agency['id'] }}">{{ $agency['name'] }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="form-row">
            <div class="form-group">
              <label for="channel_id">Channel</label>
              <select id="channel_id" name="channel_id" class="form-control">
                <option value="">Select</option>
                @foreach($lookupData['channels'] ?? [] as $channel)
                  <option value="{{ $channel['id'] }}">{{ $channel['name'] }}</option>
                @endforeach
              </select>
            </div>
            <div class="form-group">
              <label for="agent">Agent</label>
              <input id="agent" name="agent" class="form-control">
            </div>
            <div class="form-group">
              <label for="notes">Notes</label>
              <textarea id="notes" name="notes" class="form-control" rows="2"></textarea>
            </div>
          </div>
        </div>
        <div class="modal-footer" style="display:flex; gap:8px; justify-content:flex-end;">
          <button type="button" class="btn-cancel" onclick="closePolicyModal()">Close</button>
          <button type="button" class="btn-delete" id="policyModalDeleteBtn" style="display:none;" onclick="deletePolicy()">Delete</button>
          <button type="submit" class="btn-save">Save</button>
        </div>
      </form>
    </div>
  </div>

@include('partials.column-selection-modal', [
  'selectedColumns' => $selectedColumns,
  'columnDefinitions' => $columnDefinitions,
  'mandatoryColumns' => $mandatoryColumns,
  'columnSettingsRoute' => route('policies.save-column-settings'),
])

<script>
  let currentPolicyId = null;
  const lookupData = @json($lookupData);
  const selectedColumns = @json($selectedColumns);

  // Open policy details (full page view) - MUST be defined before event listeners
  async function openPolicyDetails(id){
    try {
      const res = await fetch(`/policies/${id}`, {
        headers: {
          'Accept': 'application/json',
          'X-Requested-With': 'XMLHttpRequest'
        }
      });
      if (!res.ok) throw new Error(`HTTP ${res.status}`);
      const data = await res.json();
      const policy = data.policy || data;
      currentPolicyId = id;
      
      // Ensure client data is accessible
      if (policy.client) {
        policy.client_name = policy.client.client_name || policy.client_name;
        policy.source = policy.client.source || policy.source;
        policy.source_name = policy.client.source_name || policy.source_name;
      }
      
      // Get all required elements
      const policyPageName = document.getElementById('policyPageName');
      const clientsTableView = document.getElementById('clientsTableView');
      const policyPageView = document.getElementById('policyPageView');
      const policyDetailsPageContent = document.getElementById('policyDetailsPageContent');
      const policyFormPageContent = document.getElementById('policyFormPageContent');
      const closePolicyPageBtn = document.getElementById('closePolicyPageBtn');
      
      if (!policyPageName || !clientsTableView || !policyPageView || 
          !policyDetailsPageContent || !policyFormPageContent) {
        console.error('Required elements not found');
        console.error('policyPageName:', policyPageName);
        console.error('clientsTableView:', clientsTableView);
        console.error('policyPageView:', policyPageView);
        console.error('policyDetailsPageContent:', policyDetailsPageContent);
        console.error('policyFormPageContent:', policyFormPageContent);
        alert('Error: Page elements not found');
        return;
      }
      
      // Set policy name in header
      const policyPageTitleEl = document.getElementById('policyPageTitle');
      const policyName = policy.policy_no || 'Unknown';
      if (policyPageTitleEl) policyPageTitleEl.textContent = 'Policy No';
      if (policyPageName) policyPageName.textContent = policyName;
      
      populatePolicyDetails(policy);
      
      // Hide table view, show page view
      clientsTableView.classList.add('hidden');
      policyPageView.style.display = 'block';
      policyPageView.classList.add('show');
      policyDetailsPageContent.style.display = 'block';
      document.getElementById('policyDetailsContentWrapper').style.display = 'block';
      document.getElementById('policyScheduleContentWrapper').style.display = 'block';
      document.getElementById('documentsContentWrapper').style.display = 'block';
      policyFormPageContent.style.display = 'none';
      const editPolicyFromPageBtn = document.getElementById('editPolicyFromPageBtn');
      if (editPolicyFromPageBtn) editPolicyFromPageBtn.style.display = 'inline-block';
      if (closePolicyPageBtn) closePolicyPageBtn.style.display = 'inline-block';
    } catch (e) {
      console.error(e);
      alert('Error loading policy details: ' + e.message);
    }
  }
  
  // Edit button from details page
  const editBtn = document.getElementById('editPolicyFromPageBtn');
  if (editBtn) {
    editBtn.addEventListener('click', function() {
      if (currentPolicyId) {
        openEditPolicy(currentPolicyId);
      }
    });
  }
  
  // Wait for DOM to be ready before attaching event listeners
  function initializeEventListeners() {
    const addPolicyBtn = document.getElementById('addPolicyBtn');
    if (addPolicyBtn) {
      addPolicyBtn.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        console.log('Add button clicked');
        try {
          openPolicyPage('add');
        } catch (error) {
          console.error('Error opening policy page:', error);
          alert('Error opening add policy form: ' + error.message);
        }
      });
    } else {
      console.error('Add policy button not found');
    }
    
    const columnBtn = document.getElementById('columnBtn');
    if (columnBtn) {
      columnBtn.addEventListener('click', () => openColumnModal());
    }
  }
  
  // Initialize when DOM is ready
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initializeEventListeners);
  } else {
    // DOM is already ready
    initializeEventListeners();
  }
  
  // Tab switching functionality (using event delegation)
  document.addEventListener('click', function(e) {
    if (e.target.classList.contains('policy-tab')) {
      document.querySelectorAll('.policy-tab').forEach(t => t.classList.remove('active'));
      e.target.classList.add('active');
      // Tab content switching can be implemented here if needed
    }
  });
  
  // Form submission handler - use standard form submission
  const policyForm = document.getElementById('policyForm');
  if (policyForm) {
    policyForm.addEventListener('submit', function(e) {
      // Ensure checkbox value is properly set
      const renewableCheckbox = this.querySelector('input[name="renewable"][type="checkbox"]');
      if (renewableCheckbox) {
        if (renewableCheckbox.checked) {
          renewableCheckbox.value = '1';
        } else {
          // Remove the checkbox so it's not submitted (Laravel will treat as false/0)
          renewableCheckbox.disabled = true;
          // Add a hidden input with value 0
          const existingHidden = this.querySelector('input[name="renewable"][type="hidden"]');
          if (!existingHidden) {
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'renewable';
            hiddenInput.value = '0';
            this.appendChild(hiddenInput);
          }
        }
      }
      
      // Let the form submit normally - Laravel will handle redirects
    });
  }
  
  // Handle client selection change to update source fields
  document.addEventListener('change', async function(e) {
    if (e.target.id === 'client_id' && e.target.form && e.target.form.id === 'policyForm') {
      const clientId = e.target.value;
      if (clientId) {
        try {
          const response = await fetch(`/clients/${clientId}`, {
            headers: {
              'Accept': 'application/json',
              'X-Requested-With': 'XMLHttpRequest'
            }
          });
          if (response.ok) {
            const client = await response.json();
            const sourceField = document.getElementById('source');
            const sourceNameField = document.getElementById('source_name');
            if (sourceField && client.source) sourceField.value = client.source || '';
            if (sourceNameField && client.source_name) sourceNameField.value = client.source_name || '';
          }
        } catch (error) {
          console.error('Error fetching client data:', error);
        }
      }
    }
  });

  // DFR Only Filter
  (function(){
    const btn = document.getElementById('dfrOnlyBtn');
    if (btn) {
      btn.addEventListener('click', () => {
        const u = new URL(window.location.href);
        if (u.searchParams.get('dfr') === 'true') {
          u.searchParams.delete('dfr');
        } else {
          u.searchParams.set('dfr', 'true');
        }
        window.location.href = u.toString();
      });
    }
    const listAllBtn = document.getElementById('listAllBtn');
    if (listAllBtn) {
      listAllBtn.addEventListener('click', () => {
        window.location.href = '{{ route("policies.index") }}';
      });
    }
  })();

  // Populate policy details view
  function populatePolicyDetails(policy) {
    const content = document.getElementById('policyDetailsContent');
    const scheduleContent = document.getElementById('policyScheduleContent');
    const documentsContent = document.getElementById('documentsContent');
    if (!content || !scheduleContent || !documentsContent) return;

    function formatDate(dateStr) {
      if (!dateStr) return '';
      const date = new Date(dateStr);
      const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
      const day = date.getDate();
      const month = months[date.getMonth()];
      const year = String(date.getFullYear()).slice(-2);
      // Format: 7-Dec-25 (no leading zero for day if single digit)
      return `${day}-${month}-${year}`;
    }

    function formatNumber(num) {
      if (!num && num !== 0) return '';
      const numVal = parseFloat(num);
      // If it's a whole number, don't show decimals
      if (numVal % 1 === 0) {
        return numVal.toLocaleString('en-US');
      }
      return numVal.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }
    
    // Get client data
    const client = policy.client || {};
    let clientName = policy.client_name || '';
    if (!clientName && client) {
      clientName = client.client_name || (client.first_name ? `${client.first_name} ${client.surname || ''}`.trim() : '');
    }
    const source = policy.source || client.source || '';
    const sourceName = policy.source_name || client.source_name || '';
    const applicationDate = formatDate(policy.date_registered);
    const channelName = policy.channel_name || (policy.channel ? policy.channel.name : '');
    
    // Top Section: 4 columns
    const col1 = `
      <div class="detail-section-card">
        <div class="detail-section-header">PARTIES AND CLASS</div>
        <div class="detail-section-body">
          <div class="detail-row">
            <span class="detail-label">Policyholder</span>
            <input type="text" class="detail-value" value="${clientName}" readonly>
          </div>
          <div class="detail-row">
            <span class="detail-label">Insurer</span>
            <input type="text" class="detail-value" value="${policy.insurer_name || ''}" readonly>
          </div>
          <div class="detail-row">
            <span class="detail-label">Insurance Class</span>
            <input type="text" class="detail-value" value="${policy.policy_class_name || ''}" readonly>
          </div>
          <div class="detail-row">
            <span class="detail-label">Insured</span>
            <input type="text" class="detail-value" value="${policy.insured || ''}" readonly>
          </div>
          <div class="detail-row">
            <span class="detail-label">Insured Item</span>
            <input type="text" class="detail-value" value="${policy.insured_item || ''}" readonly>
          </div>
        </div>
      </div>
    `;

    const col2 = `
      <div class="detail-section-card">
        <div class="detail-section-header">POLICY DETAILS</div>
        <div class="detail-section-body">
          <div class="detail-row">
            <span class="detail-label">Policy No</span>
            <input type="text" class="detail-value" value="${policy.policy_no || ''}" readonly>
          </div>
          <div class="detail-row">
            <span class="detail-label">Policy Code</span>
            <input type="text" class="detail-value" value="${policy.policy_code || ''}" readonly>
          </div>
          <div class="detail-row">
            <span class="detail-label">Application Date</span>
            <input type="text" class="detail-value" value="${applicationDate}" readonly>
          </div>
          <div class="detail-row">
            <span class="detail-label">Business Type</span>
            <input type="text" class="detail-value" value="${policy.business_type_name || ''}" readonly>
          </div>
          <div class="detail-row">
            <span class="detail-label">Policy Status</span>
            <div style="display:flex; align-items:center; gap:4px;">
              <input type="text" class="detail-value" value="${policy.policy_status_name || ''}" readonly style="flex:1;">
              <button class="btn-dfr">DFR</button>
            </div>
          </div>
          <div class="detail-row">
            <div class="renewal-checkbox">
              <input type="checkbox" ${policy.renewable ? 'checked' : ''} disabled style="margin:0;">
              <span style="font-size:10px; color:#666;">Renewal Notices</span>
              <span style="font-size:10px; color:#666; margin-left:6px;">Channel</span>
              <input type="text" class="detail-value" value="${channelName || ''}" readonly style="flex:1; min-width:100px; padding:3px 6px; font-size:10px;">
              <button class="btn-sms">SMS</button>
            </div>
          </div>
        </div>
      </div>
    `;

    const col3 = `
      <div class="detail-section-card">
        <div class="detail-section-header">AGENCY & SOURCE</div>
        <div class="detail-section-body">
          <div class="detail-row">
            <span class="detail-label">Agency</span>
            <input type="text" class="detail-value" value="${policy.agency_name || ''}" readonly>
          </div>
          <div class="detail-row">
            <span class="detail-label">Agent</span>
            <input type="text" class="detail-value" value="${policy.agent || ''}" readonly>
          </div>
          <div class="detail-row">
            <span class="detail-label">Source</span>
            <input type="text" class="detail-value" value="${source}" readonly>
            </div>
          <div class="detail-row">
            <span class="detail-label">Source Name</span>
            <input type="text" class="detail-value" value="${sourceName}" readonly>
          </div>
        </div>
      </div>
    `;

    const col4 = `
      <div class="detail-section-card">
        <div class="detail-section-header">OTHER DETAILS</div>
        <div class="detail-section-body">
          <div class="detail-row">
            <textarea class="detail-value" readonly style="min-height:50px;">${policy.notes || ''}</textarea>
          </div>
        </div>
      </div>
    `;

    content.innerHTML = col1 + col2 + col3 + col4;
    
    // Middle Section: Policy Schedule
    const currentYear = policy.start_date ? new Date(policy.start_date).getFullYear() : new Date().getFullYear();
    const termNumber = policy.term || '1';
    const termUnit = policy.term_unit || 'Year';
    const payPlan = policy.pay_plan_name || (policy.payPlan ? policy.payPlan.name : '');
    // Get NOP and interval from policy data
    const nop = policy.no_of_instalments || '';
    const interval = policy.frequency_name || (policy.frequency ? policy.frequency.name : '');
    // Handle policy plan name - check both direct field and relationship
    const policyPlanName = policy.policy_plan_name || (policy.policyPlan ? policy.policyPlan.name : '');
    
    const scheduleCol1 = `
      <div class="detail-section-card">
        <div class="detail-section-header">PLAN & VALUE DETAILS</div>
        <div class="detail-section-body">
          <div class="detail-row">
            <span class="detail-label">Year</span>
            <select class="detail-value" disabled style="appearance:auto; -webkit-appearance:menulist;">
              <option ${policy.start_date ? new Date(policy.start_date).getFullYear() == currentYear ? 'selected' : '' : ''}>${currentYear}</option>
            </select>
          </div>
          <div class="detail-row">
            <span class="detail-label">Policy Plan</span>
            <input type="text" class="detail-value" value="${policyPlanName || ''}" readonly>
          </div>
          <div class="detail-row">
            <span class="detail-label">Sum Insured</span>
            <input type="text" class="detail-value" value="${policy.sum_insured ? formatNumber(policy.sum_insured) : ''}" readonly>
          </div>
          <div class="detail-row">
            <span class="detail-label">Last Endorsement</span>
            <input type="text" class="detail-value" value="${policy.last_endorsement || ''}" readonly>
          </div>
        </div>
      </div>
    `;

    const scheduleCol2 = `
      <div class="detail-section-card">
        <div class="detail-section-header">PERIOD OF COVER</div>
        <div class="detail-section-body">
          <div class="detail-row">
            <span class="detail-label">Term</span>
            <div style="display:flex; gap:6px; align-items:center;">
              <input type="text" class="detail-value" value="${termNumber}" readonly style="flex:0 0 50px;">
              <input type="text" class="detail-value" value="${termUnit}" readonly style="flex:1;">
            </div>
          </div>
          <div class="detail-row">
            <span class="detail-label">Start Date</span>
            <input type="text" class="detail-value" value="${policy.start_date ? formatDate(policy.start_date) : ''}" readonly>
          </div>
          <div class="detail-row">
            <span class="detail-label">End Date</span>
            <input type="text" class="detail-value" value="${policy.end_date ? formatDate(policy.end_date) : ''}" readonly>
          </div>
          <div class="detail-row">
            <span class="detail-label">Cancelled Date</span>
            <input type="text" class="detail-value" value="${policy.cancelled_date ? formatDate(policy.cancelled_date) : ''}" readonly>
          </div>
        </div>
      </div>
    `;

    const scheduleCol3 = `
      <div class="detail-section-card">
        <div class="detail-section-header">ADD ONS (Motor)</div>
        <div class="detail-section-body">
          <div class="detail-row">
            <span class="detail-label">WSC</span>
            <input type="text" class="detail-value" value="${policy.wsc ? formatNumber(policy.wsc) : ''}" readonly>
          </div>
          <div class="detail-row">
            <span class="detail-label">LOU</span>
            <input type="text" class="detail-value" value="${policy.lou ? formatNumber(policy.lou) : ''}" readonly>
        </div>
          <div class="detail-row">
            <span class="detail-label">PA</span>
            <input type="text" class="detail-value" value="${policy.pa ? formatNumber(policy.pa) : ''}" readonly>
      </div>
        </div>
      </div>
    `;

    const scheduleCol4 = `
      <div class="detail-section-card">
        <div class="detail-section-header">PREMIUM & PAYMENT PLAN</div>
        <div class="detail-section-body">
          <div class="detail-row">
            <span class="detail-label">Base Premium</span>
            <input type="text" class="detail-value" value="${formatNumber(policy.base_premium)}" readonly>
          </div>
          <div class="detail-row">
            <span class="detail-label">Total Premium</span>
            <input type="text" class="detail-value" value="${formatNumber(policy.premium)}" readonly>
          </div>
          <div class="detail-row">
            <span class="detail-label">Payment Plan</span>
            <input type="text" class="detail-value" value="${payPlan || ''}" readonly>
          </div>
          <div class="detail-row">
            <span class="detail-label">NOP & Interval</span>
            <div style="display:flex; gap:6px; align-items:center;">
              <input type="text" class="detail-value" value="${nop || ''}" readonly style="flex:0 0 50px;">
              <input type="text" class="detail-value" value="${interval || ''}" readonly style="flex:1;">
            </div>
          </div>
        </div>
      </div>
    `;

    scheduleContent.innerHTML = scheduleCol1 + scheduleCol2 + scheduleCol3 + scheduleCol4;
    
    // Bottom Section: Documents - Dynamic based on policy documents
    let documentsHTML = '';
    // Check if documents exist and is an array
    const documents = policy.documents || [];
    if (Array.isArray(documents) && documents.length > 0) {
      // If policy has documents array, display them
      documents.forEach(doc => {
        const docName = doc.name || doc.file_name || (doc.type || 'Document');
        const isPDF = docName.toLowerCase().endsWith('.pdf') || (doc.type && doc.type.toLowerCase().includes('pdf')) || (doc.format && doc.format.toLowerCase().includes('pdf'));
        const iconColor = isPDF ? '#dc3545' : '#000';
        const fileIcon = isPDF ? 
          '<path d="M14 2H6C5.46957 2 4.96086 2.21071 4.58579 2.58579C4.21071 2.96086 4 3.46957 4 4V20C4 20.5304 4.21071 21.0391 4.58579 21.4142C4.96086 21.7893 5.46957 22 6 22H18C18.5304 22 19.0391 21.7893 19.4142 21.4142C19.7893 21.0391 20 20.5304 20 20V8L14 2Z" stroke="#dc3545" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M14 2V8H20" stroke="#dc3545" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>' :
          '<path d="M14 2H6C5.46957 2 4.96086 2.21071 4.58579 2.58579C4.21071 2.96086 4 3.46957 4 4V20C4 20.5304 4.21071 21.0391 4.58579 21.4142C4.96086 21.7893 5.46957 22 6 22H18C18.5304 22 19.0391 21.7893 19.4142 21.4142C19.7893 21.0391 20 20.5304 20 20V8L14 2Z" stroke="#000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M14 2V8H20" stroke="#000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>';
        documentsHTML += `
          <div class="document-icon">
            <svg width="30" height="30" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
              ${fileIcon}
            </svg>
            <span>${docName}</span>
          </div>
        `;
      });
    } else {
      // Default document icons if no documents available
      documentsHTML = `
      <div class="document-icon">
        <svg width="30" height="30" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M14 2H6C5.46957 2 4.96086 2.21071 4.58579 2.58579C4.21071 2.96086 4 3.46957 4 4V20C4 20.5304 4.21071 21.0391 4.58579 21.4142C4.96086 21.7893 5.46957 22 6 22H18C18.5304 22 19.0391 21.7893 19.4142 21.4142C19.7893 21.0391 20 20.5304 20 20V8L14 2Z" stroke="#dc3545" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
          <path d="M14 2V8H20" stroke="#dc3545" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        <span>Proposal</span>
      </div>
      <div class="document-icon">
        <svg width="30" height="30" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
          <rect x="3" y="3" width="18" height="18" rx="2" stroke="#333" stroke-width="2"/>
          <path d="M9 9H15M9 15H15M9 12H15" stroke="#333" stroke-width="2" stroke-linecap="round"/>
        </svg>
        <span>Debit Note</span>
      </div>
      <div class="document-icon">
        <svg width="30" height="30" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
          <rect x="3" y="3" width="18" height="18" rx="2" stroke="#333" stroke-width="2"/>
          <path d="M9 9H15M9 15H15M9 12H15" stroke="#333" stroke-width="2" stroke-linecap="round"/>
        </svg>
        <span>Receipt</span>
      </div>
      <div class="document-icon">
        <svg width="30" height="30" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M14 2H6C5.46957 2 4.96086 2.21071 4.58579 2.58579C4.21071 2.96086 4 3.46957 4 4V20C4 20.5304 4.21071 21.0391 4.58579 21.4142C4.96086 21.7893 5.46957 22 6 22H18C18.5304 22 19.0391 21.7893 19.4142 21.4142C19.7893 21.0391 20 20.5304 20 20V8L14 2Z" stroke="#dc3545" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
          <path d="M14 2V8H20" stroke="#dc3545" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        <span>Schedule</span>
      </div>
      `;
    }
    documentsContent.innerHTML = documentsHTML;
  }
  
  // Open policy page (Add only)
  function openPolicyPage(mode) {
    if (mode === 'add') {
      openPolicyForm('add');
    }
  }

  async function openEditPolicy(id){
    try {
      const res = await fetch(`/policies/${id}/edit`, { 
        headers: { 
          'Accept': 'application/json',
          'X-Requested-With': 'XMLHttpRequest'
        } 
      });
      if (!res.ok) throw new Error('Network error');
      const policy = await res.json();
      currentPolicyId = id;
      openPolicyForm('edit', policy);
    } catch (e) {
      console.error(e);
      alert('Error loading policy data');
    }
  }

  function openPolicyForm(mode = 'add', policy = null){
    const pageForm = document.getElementById('policyForm');
    const formMethod = document.getElementById('policyFormMethod');
    const formContent = document.getElementById('policyFormContent');
    const formScheduleContent = document.getElementById('policyFormScheduleContent');
    const formDocumentsContent = document.getElementById('policyFormDocumentsContent');
    
    if (!pageForm || !formMethod || !formContent || !formScheduleContent || !formDocumentsContent) {
      console.error('Required form elements not found');
      alert('Error: Form elements not found. Please refresh the page.');
      return;
    }
    
    const closeBtn = document.getElementById('closePolicyPageBtn');
    const editBtn = document.getElementById('editPolicyFromPageBtn');

    if (mode === 'add') {
      // Set header
      const policyPageTitleEl = document.getElementById('policyPageTitle');
      const policyPageNameEl = document.getElementById('policyPageName');
      if (policyPageTitleEl) policyPageTitleEl.textContent = 'Policy';
      if (policyPageNameEl) policyPageNameEl.textContent = 'Add New';
      
      // Set form action
      pageForm.action = '{{ route("policies.store") }}';
      if (formMethod) formMethod.innerHTML = '';
      
      // Hide/show buttons
      if (closeBtn) closeBtn.style.display = 'none';
      if (editBtn) editBtn.style.display = 'none';
      
      // Populate form with empty data
      populatePolicyForm(null, formContent, formScheduleContent, formDocumentsContent);
          } else {
      // Edit mode - same design as add mode
      // Set header - same as add mode
      const policyPageTitleEl = document.getElementById('policyPageTitle');
      const policyPageNameEl = document.getElementById('policyPageName');
      if (policyPageTitleEl) policyPageTitleEl.textContent = 'Policy';
      if (policyPageNameEl) policyPageNameEl.textContent = 'Add New';
      
      // Set form action for update
      pageForm.action = `/policies/${currentPolicyId}`;
      formMethod.innerHTML = `<input type="hidden" name="_method" value="PUT">`;
      
      // Hide/show buttons - same as add mode
      if (closeBtn) closeBtn.style.display = 'none';
      if (editBtn) editBtn.style.display = 'none';
      
      // Get client data
      const client = policy.client || {};
      if (policy.client) {
        policy.client_name = policy.client.client_name || policy.client_name;
        policy.source = policy.client.source || policy.source;
        policy.source_name = policy.client.source_name || policy.source_name;
      }
      
      // Populate form with policy data
      populatePolicyForm(policy, formContent, formScheduleContent, formDocumentsContent);
    }

    // Hide table view, show page view
    const clientsTableView = document.getElementById('clientsTableView');
    const policyPageView = document.getElementById('policyPageView');
    const policyDetailsPageContent = document.getElementById('policyDetailsPageContent');
    const policyDetailsContentWrapper = document.getElementById('policyDetailsContentWrapper');
    const policyScheduleContentWrapper = document.getElementById('policyScheduleContentWrapper');
    const documentsContentWrapper = document.getElementById('documentsContentWrapper');
    const policyFormPageContent = document.getElementById('policyFormPageContent');
    
    if (!clientsTableView || !policyPageView || !policyFormPageContent) {
      console.error('Required page elements not found');
      alert('Error: Page elements not found. Please refresh the page.');
      return;
    }
    
    clientsTableView.classList.add('hidden');
    policyPageView.style.display = 'block';
    policyPageView.classList.add('show');
    
    // Hide all detail view elements
    if (policyDetailsPageContent) policyDetailsPageContent.style.display = 'none';
    if (policyDetailsContentWrapper) policyDetailsContentWrapper.style.display = 'none';
    if (policyScheduleContentWrapper) policyScheduleContentWrapper.style.display = 'none';
    if (documentsContentWrapper) documentsContentWrapper.style.display = 'none';
    
    // Show form view
    policyFormPageContent.style.display = 'block';
    
    // Ensure form content wrapper is visible
    const formContentWrapper = document.getElementById('policyFormContentWrapper');
    if (formContentWrapper) {
      formContentWrapper.style.display = 'block';
    }
  }
  
  function populatePolicyForm(policy, formContent, formScheduleContent, formDocumentsContent) {
    if (!formContent || !formScheduleContent || !formDocumentsContent) {
      console.error('Form elements not found');
      return;
    }
    
    if (!lookupData) {
      console.error('lookupData not available');
      return;
    }
    
    // Clear all content first
    formContent.innerHTML = '';
    formScheduleContent.innerHTML = '';
    formDocumentsContent.innerHTML = '';
    
    function formatDateForInput(dateStr) {
      if (!dateStr) return '';
      try {
        let date;
        // Handle different date formats
        if (typeof dateStr === 'string') {
          // If it's already in YYYY-MM-DD format, return as is
          if (/^\d{4}-\d{2}-\d{2}$/.test(dateStr)) {
            return dateStr;
          }
          date = new Date(dateStr);
        } else {
          date = new Date(dateStr);
        }
        if (isNaN(date.getTime())) return '';
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
      } catch (e) {
        return '';
      }
    }
    
    function formatNumber(num) {
      if (!num && num !== 0) return '';
      const numVal = parseFloat(num);
      if (numVal % 1 === 0) {
        return numVal.toLocaleString('en-US');
      }
      return numVal.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }
    
    const p = policy || {};
    const client = p.client || {};
    let clientName = p.client_name || '';
    if (!clientName && client) {
      clientName = client.client_name || (client.first_name ? `${client.first_name} ${client.surname || ''}`.trim() : '');
    }
    const source = p.source || client.source || '';
    const sourceName = p.source_name || client.source_name || '';
    
    // Helper to create select options
    function createSelectOptions(options, selectedValue, includeEmpty = true, nameField = null) {
      let html = includeEmpty ? '<option value="">Select</option>' : '';
      options.forEach(opt => {
        const value = opt.id || opt;
        // Determine the name field - check for client_name first, then name, then try to get a string value
        let name;
        if (nameField) {
          name = opt[nameField];
        } else if (opt.client_name) {
          // For clients, include clid if available
          name = opt.clid ? `${opt.client_name} (${opt.clid})` : opt.client_name;
        } else if (opt.name) {
          name = opt.name;
        } else {
          // Fallback: try to find any string property
          name = Object.values(opt).find(v => typeof v === 'string' && v !== value) || String(value);
        }
        const selected = value == selectedValue ? 'selected' : '';
        html += `<option value="${value}" ${selected}>${name}</option>`;
      });
      return html;
    }
    
    // Top Section: 4 columns - Matching details view exactly
    const col1 = `
      <div class="detail-section-card">
        <div class="detail-section-header">PARTIES AND CLASS</div>
        <div class="detail-section-body">
          <div class="detail-row">
            <span class="detail-label">Policyholder</span>
            <select name="client_id" id="client_id" class="detail-value" required>
              ${createSelectOptions(lookupData.clients || [], p.client_id)}
            </select>
          </div>
          <div class="detail-row">
            <span class="detail-label">Insurer</span>
            <select name="insurer_id" id="insurer_id" class="detail-value">
              ${createSelectOptions(lookupData.insurers || [], p.insurer_id)}
            </select>
          </div>
          <div class="detail-row">
            <span class="detail-label">Insurance Class</span>
            <select name="policy_class_id" id="policy_class_id" class="detail-value">
              ${createSelectOptions(lookupData.policy_classes || [], p.policy_class_id)}
            </select>
          </div>
          <div class="detail-row">
            <span class="detail-label">Insured</span>
            <input type="text" name="insured" id="insured" class="detail-value" value="${p.insured || ''}">
          </div>
        </div>
      </div>
    `;

    const col2 = `
      <div class="detail-section-card">
        <div class="detail-section-header">POLICY DETAILS</div>
        <div class="detail-section-body">
          <div class="detail-row">
            <span class="detail-label">Policy No</span>
            <input type="text" name="policy_no" id="policy_no" class="detail-value" value="${p.policy_no || ''}" ${!p || !p.policy_no ? 'required' : ''}>
          </div>
          <div class="detail-row">
            <span class="detail-label">Application Date</span>
            <input type="date" name="date_registered" id="date_registered" class="detail-value" value="${formatDateForInput(p.date_registered)}" required>
          </div>
          <div class="detail-row">
            <span class="detail-label">Business Type</span>
            <select name="business_type_id" id="business_type_id" class="detail-value">
              ${createSelectOptions(lookupData.business_types || [], p.business_type_id)}
            </select>
          </div>
          <div class="detail-row">
            <span class="detail-label">Policy Status</span>
            <div style="display:flex; align-items:center; gap:4px;">
              <select name="policy_status_id" id="policy_status_id" class="detail-value" style="flex:1;">
                ${createSelectOptions(lookupData.policy_statuses || [], p.policy_status_id)}
              </select>
              <button type="button" class="btn-dfr">DFR</button>
            </div>
          </div>
          <div class="detail-row">
            <div class="renewal-checkbox">
              <input type="checkbox" name="renewable" id="renewable" value="1" ${p.renewable ? 'checked' : ''} style="margin:0;">
              <span style="font-size:10px; color:#666;">Renewal Notices</span>
              <span style="font-size:10px; color:#666; margin-left:6px;">Channel</span>
              <input type="text" name="channel_text" id="channel_text" class="detail-value" style="flex:1; min-width:100px; padding:3px 6px; font-size:10px;" placeholder="">
              <button type="button" class="btn-sms">SMS</button>
            </div>
          </div>
        </div>
      </div>
    `;

    const col3 = `
      <div class="detail-section-card">
        <div class="detail-section-header">AGENCY & SOURCE</div>
        <div class="detail-section-body">
          <div class="detail-row">
            <span class="detail-label">Agency</span>
            <select name="agency_id" id="agency_id" class="detail-value">
              ${createSelectOptions(lookupData.agencies || [], p.agency_id)}
            </select>
          </div>
          <div class="detail-row">
            <span class="detail-label">Agent</span>
            <input type="text" name="agent" id="agent" class="detail-value" value="${p.agent || ''}">
          </div>
          <div class="detail-row">
            <span class="detail-label">Source</span>
            <input type="text" name="source" id="source" class="detail-value" value="${source}" readonly>
          </div>
          <div class="detail-row">
            <span class="detail-label">Source Name</span>
            <input type="text" name="source_name" id="source_name" class="detail-value" value="${sourceName}" readonly>
          </div>
        </div>
      </div>
    `;

    const col4 = `
      <div class="detail-section-card">
        <div class="detail-section-header">OTHER DETAILS</div>
        <div class="detail-section-body">
          <div class="detail-row">
            <textarea name="notes" id="notes" class="detail-value" style="min-height:50px; resize:vertical;">${p.notes || ''}</textarea>
          </div>
        </div>
      </div>
    `;

    // Set form content - ensure it's visible
    if (formContent) {
      formContent.innerHTML = col1 + col2 + col3 + col4;
      formContent.style.display = 'grid';
    }
    
    // Middle Section: Schedule Details - Matching image layout
    const currentYear = p.start_date ? new Date(p.start_date).getFullYear() : new Date().getFullYear();
    const termNumber = p.term || '1';
    const termUnit = p.term_unit || 'Year';
    const payPlan = p.pay_plan_lookup_id || '';
    const frequency = p.frequency_id || '';
    const interval = lookupData.frequencies?.find(f => f.id == frequency)?.name || '';
    
    const scheduleCol1 = `
      <div class="detail-section-card">
        <div class="detail-section-header">PLAN & VALUE DETAILS</div>
        <div class="detail-section-body">
          <div class="detail-row">
            <span class="detail-label">Year</span>
            <select class="detail-value" disabled style="appearance:auto; -webkit-appearance:menulist;">
              <option ${p.start_date ? new Date(p.start_date).getFullYear() == currentYear ? 'selected' : '' : ''}>${currentYear}</option>
            </select>
          </div>
          <div class="detail-row">
            <span class="detail-label">Policy Plan</span>
            <select name="policy_plan_id" id="policy_plan_id" class="detail-value">
              ${createSelectOptions(lookupData.policy_plans || [], p.policy_plan_id)}
            </select>
          </div>
          <div class="detail-row">
            <span class="detail-label">Sum Insured</span>
            <input type="number" step="0.01" name="sum_insured" id="sum_insured" class="detail-value" value="${p.sum_insured || ''}">
          </div>
          <div class="detail-row">
            <span class="detail-label">Last Endorsement</span>
            <input type="text" class="detail-value" value="${p.last_endorsement || ''}" readonly>
          </div>
        </div>
      </div>
    `;

    const scheduleCol2 = `
      <div class="detail-section-card">
        <div class="detail-section-header">PERIOD OF COVER</div>
        <div class="detail-section-body">
          <div class="detail-row">
            <span class="detail-label">Term</span>
            <div style="display:flex; gap:6px; align-items:center;">
              <input type="number" name="term" id="term" class="detail-value" value="${termNumber}" style="flex:0 0 50px;">
              <select name="term_unit" id="term_unit" class="detail-value" style="flex:1;">
                ${createSelectOptions(lookupData.term_units || [], termUnit)}
              </select>
            </div>
          </div>
          <div class="detail-row">
            <span class="detail-label">Start Date</span>
            <input type="date" name="start_date" id="start_date" class="detail-value" value="${formatDateForInput(p.start_date)}" required>
          </div>
          <div class="detail-row">
            <span class="detail-label">End Date</span>
            <input type="date" name="end_date" id="end_date" class="detail-value" value="${formatDateForInput(p.end_date)}" required>
          </div>
          <div class="detail-row">
            <span class="detail-label">Cancelled Date</span>
            <input type="date" name="cancelled_date" id="cancelled_date" class="detail-value" value="${formatDateForInput(p.cancelled_date)}">
          </div>
        </div>
      </div>
    `;

    const scheduleCol3 = `
      <div class="detail-section-card">
        <div class="detail-section-header">ADD ONS (Motor)</div>
        <div class="detail-section-body">
          <div class="detail-row">
            <span class="detail-label">WSC</span>
            <input type="text" class="detail-value" value="10,000" readonly>
          </div>
          <div class="detail-row">
            <span class="detail-label">LOU</span>
            <input type="text" class="detail-value" value="15,000" readonly>
          </div>
          <div class="detail-row">
            <span class="detail-label">PA</span>
            <input type="text" class="detail-value" value="250,000" readonly>
          </div>
        </div>
      </div>
    `;

    const scheduleCol4 = `
      <div class="detail-section-card">
        <div class="detail-section-header">PREMIUM & PAYMENT PLAN</div>
        <div class="detail-section-body">
          <div class="detail-row">
            <span class="detail-label">Base Premium</span>
            <input type="number" step="0.01" name="base_premium" id="base_premium" class="detail-value" value="${p.base_premium || ''}">
          </div>
          <div class="detail-row">
            <span class="detail-label">Total Premium</span>
            <input type="number" step="0.01" name="premium" id="premium" class="detail-value" value="${p.premium || ''}">
          </div>
          <div class="detail-row">
            <span class="detail-label">Payment Plan</span>
            <select name="pay_plan_lookup_id" id="pay_plan_lookup_id" class="detail-value">
              ${createSelectOptions(lookupData.pay_plans || [], payPlan)}
            </select>
          </div>
          <div class="detail-row">
            <span class="detail-label">NOP & Interval</span>
            <div style="display:flex; gap:6px; align-items:center;">
              <input type="number" name="no_of_instalments" id="no_of_instalments" class="detail-value" value="${p.no_of_instalments || '2'}" style="flex:0 0 50px;">
              <select name="frequency_id" id="frequency_id" class="detail-value" style="flex:1;">
                ${createSelectOptions(lookupData.frequencies || [], frequency)}
              </select>
            </div>
          </div>
        </div>
      </div>
    `;

    // Set schedule content - ensure it's visible
    if (formScheduleContent) {
      formScheduleContent.innerHTML = scheduleCol1 + scheduleCol2 + scheduleCol3 + scheduleCol4;
      formScheduleContent.style.display = 'grid';
    }
    
    // Documents Section - Display existing documents if editing, or empty if adding
    if (formDocumentsContent) {
      formDocumentsContent.innerHTML = '';
      formDocumentsContent.style.display = 'flex';
      
      // If editing and policy has documents, display them
      if (p && p.documents && p.documents.length > 0) {
        p.documents.forEach(doc => {
          const docName = doc.name || doc.file_name || doc.type || 'Document';
          const isPDF = docName.toLowerCase().endsWith('.pdf') || doc.type === 'application/pdf';
          const iconColor = isPDF ? '#dc3545' : '#000';
          const fileIcon = isPDF ? 
            '<path d="M14 2H6C5.46957 2 4.96086 2.21071 4.58579 2.58579C4.21071 2.96086 4 3.46957 4 4V20C4 20.5304 4.21071 21.0391 4.58579 21.4142C4.96086 21.7893 5.46957 22 6 22H18C18.5304 22 19.0391 21.7893 19.4142 21.4142C19.7893 21.0391 20 20.5304 20 20V8L14 2Z" stroke="#dc3545" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M14 2V8H20" stroke="#dc3545" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>' :
            '<path d="M14 2H6C5.46957 2 4.96086 2.21071 4.58579 2.58579C4.21071 2.96086 4 3.46957 4 4V20C4 20.5304 4.21071 21.0391 4.58579 21.4142C4.96086 21.7893 5.46957 22 6 22H18C18.5304 22 19.0391 21.7893 19.4142 21.4142C19.7893 21.0391 20 20.5304 20 20V8L14 2Z" stroke="#000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M14 2V8H20" stroke="#000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>';
          const docDiv = document.createElement('div');
          docDiv.className = 'document-icon';
          docDiv.innerHTML = `
            <svg width="30" height="30" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
              ${fileIcon}
            </svg>
            <span>${docName}</span>
          `;
          formDocumentsContent.appendChild(docDiv);
        });
      }
    }
    
    // Clear file input
    const fileInput = document.getElementById('documentUpload');
    if (fileInput) {
      fileInput.value = '';
    }
  }

  function closePolicyPageView(){
    const policyPageView = document.getElementById('policyPageView');
    policyPageView.classList.remove('show');
    policyPageView.style.display = 'none';
    document.getElementById('clientsTableView').classList.remove('hidden');
    document.getElementById('policyDetailsPageContent').style.display = 'none';
    document.getElementById('policyDetailsContentWrapper').style.display = 'none';
    document.getElementById('policyScheduleContentWrapper').style.display = 'none';
    document.getElementById('documentsContentWrapper').style.display = 'none';
    document.getElementById('policyFormPageContent').style.display = 'none';
    currentPolicyId = null;
  }

  function closePolicyModal(){
    closePolicyPageView();
  }

  // Handle document upload
  function handleDocumentUpload(event) {
    const files = event.target.files;
    if (!files || files.length === 0) return;
    
    // Display uploaded files
    const documentsContent = document.getElementById('policyFormDocumentsContent');
    if (!documentsContent) {
      console.error('Documents content container not found');
      return;
    }
    
    Array.from(files).forEach(file => {
      // Check if file already exists (by name)
      const existingDocs = documentsContent.querySelectorAll('.document-icon span');
      let alreadyExists = false;
      existingDocs.forEach(span => {
        if (span.textContent === file.name) {
          alreadyExists = true;
        }
      });
      
      if (alreadyExists) {
        return; // Skip if file already displayed
      }
      
      const isPDF = file.type === 'application/pdf' || file.name.toLowerCase().endsWith('.pdf');
      const fileIcon = isPDF ? 
        '<path d="M14 2H6C5.46957 2 4.96086 2.21071 4.58579 2.58579C4.21071 2.96086 4 3.46957 4 4V20C4 20.5304 4.21071 21.0391 4.58579 21.4142C4.96086 21.7893 5.46957 22 6 22H18C18.5304 22 19.0391 21.7893 19.4142 21.4142C19.7893 21.0391 20 20.5304 20 20V8L14 2Z" stroke="#dc3545" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M14 2V8H20" stroke="#dc3545" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>' :
        '<path d="M14 2H6C5.46957 2 4.96086 2.21071 4.58579 2.58579C4.21071 2.96086 4 3.46957 4 4V20C4 20.5304 4.21071 21.0391 4.58579 21.4142C4.96086 21.7893 5.46957 22 6 22H18C18.5304 22 19.0391 21.7893 19.4142 21.4142C19.7893 21.0391 20 20.5304 20 20V8L14 2Z" stroke="#000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M14 2V8H20" stroke="#000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>';
      
      const docDiv = document.createElement('div');
      docDiv.className = 'document-icon';
      docDiv.innerHTML = `
        <svg width="30" height="30" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
          ${fileIcon}
        </svg>
        <span>${file.name}</span>
      `;
      documentsContent.appendChild(docDiv);
    });
  }
  
</script>

@include('partials.table-scripts', [
  'mandatoryColumns' => $mandatoryColumns,
])

@endsection
