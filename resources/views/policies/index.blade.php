<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Policies</title>
  <style>
    * { box-sizing: border-box; }
    body { font-family: Arial, sans-serif; color: #000; margin: 10px; background: #fff; }
    .container-table { max-width: 100%; margin: 0 auto; }
    h3 { background: #f1f1f1; padding: 8px; margin-bottom: 10px; font-weight: bold; border: 1px solid #ddd; }
    .top-bar { display:flex; align-items:center; flex-wrap:wrap; gap:10px; margin-bottom:10px; }
    .left-group { display:flex; align-items:center; gap:10px; flex:1 1 auto; min-width:220px; }
    .left-buttons { display:flex; gap:10px; align-items:center; }
    .records-found { font-size:14px; color:#555; min-width:150px; }
    .action-buttons { margin-left:auto; display:flex; gap:10px; }
    .btn { border:none; cursor:pointer; padding:6px 12px; font-size:13px; border-radius:2px; white-space:nowrap; transition:background-color .2s; text-decoration:none; color:inherit; background:#fff; border:1px solid #ccc; }
    .btn-add { background:#df7900; color:#fff; border-color:#df7900; }
    .btn-dfr { background:#000; color:#fff; border-color:#000; }
    .btn-export, .btn-column { background:#fff; color:#000; border:1px solid #ccc; }
    .btn-back { background:#ccc; color:#333; border-color:#ccc; }
    .table-responsive { width: 100%; overflow-x: auto; border: 1px solid #ddd; max-height: 420px; overflow-y: auto; background: #fff; }
    .footer { display:flex; justify-content:center; align-items:center; padding:5px 0; gap:10px; border-top:1px solid #ccc; flex-wrap:wrap; margin-top:15px; position:relative; }
    .paginator { display:flex; align-items:center; gap:5px; font-size:12px; color:#555; white-space:nowrap; justify-content:center; }
    .page-info { padding:0 8px; display:inline-flex; align-items:center; justify-content:center; min-width:120px; }
    .btn-page { color:#2d2d2d; font-size:25px; width:22px; height:50px; padding:5px; cursor:pointer; text-decoration:none; display:inline-flex; align-items:center; justify-content:center; }
    .btn-page[disabled] { cursor:not-allowed; opacity:.5; }
    table { width:100%; border-collapse:collapse; font-size:13px; min-width:1200px; }
    thead tr { background-color: black; color: white; height:35px; font-weight: normal; }
    thead th { padding:6px 5px; text-align:left; border-right:1px solid #444; white-space:nowrap; font-weight: normal; }
    thead th:last-child { border-right:none; }
    tbody tr { background-color:#fefefe; border-bottom:1px solid #ddd; min-height:28px; }
    tbody tr:nth-child(even) { background-color:#f8f8f8; }
    tbody tr.dfr-row { background:#fff3cd !important; }
    tbody td { padding:5px 5px; border-right:1px solid #ddd; white-space:nowrap; vertical-align:middle; font-size:12px; }
    tbody td:last-child { border-right:none; }
    .icon-expand { cursor:pointer; color:black; text-align:center; width:20px; }
    .btn-action { padding:2px 6px; font-size:11px; margin:1px; border:1px solid #ddd; background:#fff; cursor:pointer; border-radius:2px; display:inline-block; }
    .badge-status { font-size:11px; padding:4px 8px; display:inline-block; border-radius:4px; color:#fff; }
    .modal { display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,.5); z-index:1000; align-items:center; justify-content:center; }
    .modal.show { display:flex; }
    .modal-content { background:#fff; border-radius:6px; width:92%; max-width:1100px; max-height:calc(100vh - 40px); overflow:auto; box-shadow:0 4px 6px rgba(0,0,0,.1); padding:0; }
    .modal-header { padding:12px 15px; border-bottom:1px solid #ddd; display:flex; justify-content:space-between; align-items:center; background:#f5f5f5; }
    .modal-body { padding:15px; }
    .modal-close { background:none; border:none; font-size:18px; cursor:pointer; color:#666; }
    .modal-footer { padding:12px 15px; border-top:1px solid #ddd; display:flex; justify-content:flex-end; gap:8px; background:#f9f9f9; }
    .form-row { display:flex; gap:10px; margin-bottom:12px; flex-wrap:wrap; align-items:flex-start; }
    .form-group { flex:0 0 calc((100% - 20px) / 3); }
    .form-group label { display:block; margin-bottom:4px; font-weight:600; font-size:13px; }
    .form-control, select, textarea { width:100%; padding:6px 8px; border:1px solid #ccc; border-radius:2px; font-size:13px; }
    .btn-save { background:#007bff; color:#fff; border:none; padding:6px 12px; border-radius:2px; cursor:pointer; }
    .btn-cancel { background:#6c757d; color:#fff; border:none; padding:6px 12px; border-radius:2px; cursor:pointer; }
    .btn-delete { background:#dc3545; color:#fff; border:none; padding:6px 12px; border-radius:2px; cursor:pointer; }
    .column-selection { display:grid; grid-template-columns:repeat(auto-fill,minmax(200px,1fr)); gap:8px; margin-bottom:15px; }
    .column-item { display:flex; align-items:center; gap:8px; padding:6px 8px; border:1px solid #ddd; border-radius:2px; cursor:pointer; }
    @media (max-width:1200px) { table { min-width:900px; } }
    @media (max-width:768px) { .form-row .form-group { flex:0 0 calc((100% - 20px) / 2); } .table-responsive { max-height:500px; } }
  </style>
</head>
<body>
@extends('layouts.app')
@section('content')

@php
  $selectedColumns = session('policy_columns', [
    'policy_no','client_name','insurer','policy_class','policy_plan','sum_insured','start_date','end_date','insured','policy_status','date_registered','policy_id','insured_item','renewable','biz_type','term','term_unit','base_premium','premium','frequency','pay_plan','agency','agent','notes'
  ]);
@endphp

<div class="dashboard">
  <div class="container-table">
    <h3>Policies</h3>

    @if(session('success'))
      <div class="alert alert-success" id="successAlert" style="padding:8px 12px; margin-bottom:12px; border:1px solid #c3e6cb; background:#d4edda; color:#155724;">
        {{ session('success') }}
        <button type="button" class="alert-close" onclick="document.getElementById('successAlert').style.display='none'" style="float:right;background:none;border:none;font-size:16px;cursor:pointer;">×</button>
      </div>
    @endif

    <div class="top-bar">
      <div class="left-group">
        <div class="records-found">Records Found - {{ $policies->total() }}</div>
        <div class="left-buttons" aria-label="left action buttons">
          <a class="btn btn-export" href="{{ route('policies.export', array_merge(request()->query(), ['page' => $policies->currentPage()])) }}">Export</a>
          <button class="btn btn-column" id="columnBtn" type="button">Column</button>
          <button class="btn btn-dfr" id="dfrOnlyBtn" type="button">Due For Renewal</button>
        </div>
      </div>
      <div class="action-buttons">
        <button class="btn btn-add" id="addPolicyBtn">Add</button>
        <button class="btn btn-back" onclick="window.history.back()">Back</button>
      </div>
    </div>

    <div class="table-responsive" id="tableResponsive">
      <table id="policiesTable">
        <thead>
          <tr>
            <th>Action</th>
            @if(in_array('policy_no',$selectedColumns))<th data-column="policy_no">Policy No</th>@endif
            @if(in_array('client_name',$selectedColumns))<th data-column="client_name">Client Name</th>@endif
            @if(in_array('insurer',$selectedColumns))<th data-column="insurer">Insurer</th>@endif
            @if(in_array('policy_class',$selectedColumns))<th data-column="policy_class">Policy Class</th>@endif
            @if(in_array('policy_plan',$selectedColumns))<th data-column="policy_plan">Policy Plan</th>@endif
            @if(in_array('sum_insured',$selectedColumns))<th data-column="sum_insured">Sum Insured</th>@endif
            @if(in_array('start_date',$selectedColumns))<th data-column="start_date">Start Date</th>@endif
            @if(in_array('end_date',$selectedColumns))<th data-column="end_date">End Date</th>@endif
            @if(in_array('insured',$selectedColumns))<th data-column="insured">Insured</th>@endif
            @if(in_array('policy_status',$selectedColumns))<th data-column="policy_status">Policy Status</th>@endif
            @if(in_array('date_registered',$selectedColumns))<th data-column="date_registered">Date Registered</th>@endif
            @if(in_array('policy_id',$selectedColumns))<th data-column="policy_id">Policy ID</th>@endif
            @if(in_array('insured_item',$selectedColumns))<th data-column="insured_item">Insured Item</th>@endif
            @if(in_array('renewable',$selectedColumns))<th data-column="renewable">Renewable</th>@endif
            @if(in_array('biz_type',$selectedColumns))<th data-column="biz_type">Biz Type</th>@endif
            @if(in_array('term',$selectedColumns))<th data-column="term">Term</th>@endif
            @if(in_array('term_unit',$selectedColumns))<th data-column="term_unit">Term Unit</th>@endif
            @if(in_array('base_premium',$selectedColumns))<th data-column="base_premium">Base Premium</th>@endif
            @if(in_array('premium',$selectedColumns))<th data-column="premium">Premium</th>@endif
            @if(in_array('frequency',$selectedColumns))<th data-column="frequency">Frequency</th>@endif
            @if(in_array('pay_plan',$selectedColumns))<th data-column="pay_plan">Pay Plan</th>@endif
            @if(in_array('agency',$selectedColumns))<th data-column="agency">Agency</th>@endif
            @if(in_array('agent',$selectedColumns))<th data-column="agent">Agent</th>@endif
            @if(in_array('notes',$selectedColumns))<th data-column="notes">Notes</th>@endif
            <!-- <th>Actions</th> -->
          </tr>
        </thead>
        <tbody>
          @foreach($policies as $policy)
            <tr class="{{ $policy->policy_status == 'DFR' ? 'dfr-row' : '' }}">
              <td class="icon-expand" onclick="openEditPolicy({{ $policy->id }})">⤢</td>
              @if(in_array('policy_no',$selectedColumns))<td data-column="policy_no">{{ $policy->policy_no }}</td>@endif
              @if(in_array('client_name',$selectedColumns))<td data-column="client_name">{{ $policy->client_name }}</td>@endif
              @if(in_array('insurer',$selectedColumns))<td data-column="insurer">{{ $policy->insurer }}</td>@endif
              @if(in_array('policy_class',$selectedColumns))<td data-column="policy_class">{{ $policy->policy_class }}</td>@endif
              @if(in_array('policy_plan',$selectedColumns))<td data-column="policy_plan">{{ $policy->policy_plan }}</td>@endif
              @if(in_array('sum_insured',$selectedColumns))<td data-column="sum_insured">{{ $policy->sum_insured ? number_format($policy->sum_insured,2) : '###########' }}</td>@endif
              @if(in_array('start_date',$selectedColumns))<td data-column="start_date">{{ $policy->start_date ? $policy->start_date->format('d-M-y') : '###########' }}</td>@endif
              @if(in_array('end_date',$selectedColumns))<td data-column="end_date">{{ $policy->end_date ? $policy->end_date->format('d-M-y') : '###########' }}</td>@endif
              @if(in_array('insured',$selectedColumns))<td data-column="insured">{{ $policy->insured ?? '###########' }}</td>@endif
              @if(in_array('policy_status',$selectedColumns))<td data-column="policy_status"><span class="badge-status" style="background:{{ $policy->policy_status == 'In Force' ? '#28a745' : ($policy->policy_status=='DFR' ? '#ffc107' : ($policy->policy_status=='Expired' ? '#6c757d' : '#dc3545')) }}">{{ $policy->policy_status }}</span></td>@endif
              @if(in_array('date_registered',$selectedColumns))<td data-column="date_registered">{{ $policy->date_registered ? $policy->date_registered->format('d-M-y') : '###########' }}</td>@endif
              @if(in_array('policy_id',$selectedColumns))<td data-column="policy_id">{{ $policy->policy_id }}</td>@endif
              @if(in_array('insured_item',$selectedColumns))<td data-column="insured_item">{{ $policy->insured_item ?? '-' }}</td>@endif
              @if(in_array('renewable',$selectedColumns))<td data-column="renewable">{{ $policy->renewable }}</td>@endif
              @if(in_array('biz_type',$selectedColumns))<td data-column="biz_type">{{ $policy->biz_type }}</td>@endif
              @if(in_array('term',$selectedColumns))<td data-column="term">{{ $policy->term }}</td>@endif
              @if(in_array('term_unit',$selectedColumns))<td data-column="term_unit">{{ $policy->term_unit }}</td>@endif
              @if(in_array('base_premium',$selectedColumns))<td data-column="base_premium">{{ number_format($policy->base_premium,2) }}</td>@endif
              @if(in_array('premium',$selectedColumns))<td data-column="premium">{{ number_format($policy->premium,2) }}</td>@endif
              @if(in_array('frequency',$selectedColumns))<td data-column="frequency">{{ $policy->frequency }}</td>@endif
              @if(in_array('pay_plan',$selectedColumns))<td data-column="pay_plan">{{ $policy->pay_plan }}</td>@endif
              @if(in_array('agency',$selectedColumns))<td data-column="agency">{{ $policy->agency ?? '-' }}</td>@endif
              @if(in_array('agent',$selectedColumns))<td data-column="agent">{{ $policy->agent ?? '-' }}</td>@endif
              @if(in_array('notes',$selectedColumns))<td data-column="notes">{{ $policy->notes ?? '-' }}</td>@endif
              <!-- <td>
                <button class="btn-action btn-delete" onclick="deletePolicy({{ $policy->id }})">Delete</button>
              </td> -->
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    <div class="footer">
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

  <!-- Add/Edit Policy Modal (single) -->
  <div class="modal" id="policyModal">
    <div class="modal-content">
      <div class="modal-header">
        <h4 id="policyModalTitle">Add Policy</h4>
 
        <button type="button" class="modal-close" onclick="closePolicyModal()">×</button>

      </div>

      <div class="modal-footer">
          <!-- <button type="button" class="btn-cancel" onclick="window.location.href='/schedule'">Schedule</button>
          <button type="button" class="btn-delete" onclick="window.location.href='/payments'">Payments</button> -->
          <button type="submit" class="btn-save" onclick="window.location.href='/vehicles'">Vehicles</button>
          <button type="button" class="btn-cancel" onclick="window.location.href='/claims'">Claims</button>
            <button type="button" class="btn-cancel" onclick="window.location.href='/documents'">Documents</button>
          <!-- <button type="button" class="btn-delete" onclick="window.location.href='/endorsement'">Endorsement</button> -->
          <button type="submit" class="btn-save" onclick="window.location.href='/commissions'">Commissions</button>
          <!-- <button type="button" class="btn-cancel" onclick="window.location.href='/nominees'">Nominees</button> -->

        </div>
      <form id="policyForm" method="POST" action="{{ route('policies.store') }}">
        @csrf
        <div id="policyFormMethod" style="display:none;"></div>
        <div class="modal-body">
          <div class="form-row">
            <div class="form-group">
              <label for="policy_no">Policy No *</label>
              <input id="policy_no" name="policy_no" class="form-control" required>
            </div>
            <div class="form-group">
              <label for="client_name">Client Name *</label>
              <input id="client_name" name="client_name" class="form-control" required>
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
              <label for="policy_class">Policy Class *</label>
              <select id="policy_class" name="policy_class" class="form-control" required>
                <option value="">Select</option>
                @foreach($lookupData['policy_classes'] as $s) <option value="{{ $s }}">{{ $s }}</option> @endforeach
              </select>
            </div>
            <div class="form-group">
              <label for="policy_plan">Policy Plan *</label>
              <select id="policy_plan" name="policy_plan" class="form-control" required>
                <option value="">Select</option>
                @foreach($lookupData['policy_plans'] as $s) <option value="{{ $s }}">{{ $s }}</option> @endforeach
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
              <label for="policy_status">Policy Status *</label>
              <select id="policy_status" name="policy_status" class="form-control" required>
                <option value="">Select</option>
                @foreach($lookupData['policy_statuses'] as $s) <option value="{{ $s }}">{{ $s }}</option> @endforeach
              </select>
            </div>
            <div class="form-group">
              <label for="date_registered">Date Registered *</label>
              <input id="date_registered" name="date_registered" type="date" class="form-control" required>
            </div>
            <div class="form-group">
              <label for="policy_id">Policy ID *</label>
              <input id="policy_id" name="policy_id" class="form-control" required>
            </div>
          </div>
          <div class="form-row">
            <div class="form-group">
              <label for="insured_item">Insured Item</label>
              <input id="insured_item" name="insured_item" class="form-control">
            </div>
            <div class="form-group">
              <label for="renewable">Renewable *</label>
              <select id="renewable" name="renewable" class="form-control" required>
                <option value="">Select</option>
                @foreach($lookupData['renewable_options'] as $s) <option value="{{ $s }}">{{ $s }}</option> @endforeach
              </select>
            </div>
            <div class="form-group">
              <label for="biz_type">Business Type *</label>
              <select id="biz_type" name="biz_type" class="form-control" required>
                <option value="">Select</option>
                @foreach($lookupData['biz_types'] as $s) <option value="{{ $s }}">{{ $s }}</option> @endforeach
              </select>
            </div>
          </div>
          <div class="form-row">
            <div class="form-group">
              <label for="term">Term *</label>
              <input id="term" name="term" type="number" class="form-control" required>
            </div>
            <div class="form-group">
              <label for="term_unit">Term Unit *</label>
              <select id="term_unit" name="term_unit" class="form-control" required>
                <option value="">Select</option>
                @foreach($lookupData['term_units'] as $s) <option value="{{ $s }}">{{ $s }}</option> @endforeach
              </select>
            </div>
            <div class="form-group">
              <label for="base_premium">Base Premium *</label>
              <input id="base_premium" name="base_premium" type="number" step="0.01" class="form-control" required>
            </div>
          </div>
          <div class="form-row">
            <div class="form-group">
              <label for="premium">Premium *</label>
              <input id="premium" name="premium" type="number" step="0.01" class="form-control" required>
            </div>
            <div class="form-group">
              <label for="frequency">Frequency *</label>
              <select id="frequency" name="frequency" class="form-control" required>
                <option value="">Select</option>
                @foreach($lookupData['frequencies'] as $s) <option value="{{ $s }}">{{ $s }}</option> @endforeach
              </select>
            </div>
            <div class="form-group">
              <label for="pay_plan">Pay Plan *</label>
              <select id="pay_plan" name="pay_plan" class="form-control" required>
                <option value="">Select</option>
                @foreach($lookupData['pay_plans'] as $s) <option value="{{ $s }}">{{ $s }}</option> @endforeach
              </select>
            </div>
          </div>
          <div class="form-row">
            <div class="form-group">
              <label for="agency">Agency</label>
              <input id="agency" name="agency" class="form-control">
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
        <div class="modal-footer">
          <button type="button" class="btn-cancel" onclick="closePolicyModal()">Cancel</button>
          <button type="button" class="btn-delete" id="policyDeleteBtn" style="display:none;" onclick="deletePolicy()">Delete</button>
          <button type="submit" class="btn-save">Save</button>
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
        <div style="display:flex;gap:8px;margin-bottom:12px;">
          <button class="btn" onclick="selectAllColumns()">Select All</button>
          <button class="btn" onclick="deselectAllColumns()">Deselect All</button>
        </div>
        <form id="columnForm" action="{{ route('policies.save-column-settings') }}" method="POST">
          @csrf
          <div class="column-selection">
            @php
              $all = [
                'policy_no'=>'Policy No','client_name'=>'Client Name','insurer'=>'Insurer','policy_class'=>'Policy Class','policy_plan'=>'Policy Plan',
                'sum_insured'=>'Sum Insured','start_date'=>'Start Date','end_date'=>'End Date','insured'=>'Insured','policy_status'=>'Policy Status',
                'date_registered'=>'Date Registered','policy_id'=>'Policy ID','insured_item'=>'Insured Item','renewable'=>'Renewable','biz_type'=>'Biz Type',
                'term'=>'Term','term_unit'=>'Term Unit','base_premium'=>'Base Premium','premium'=>'Premium','frequency'=>'Frequency','pay_plan'=>'Pay Plan',
                'agency'=>'Agency','agent'=>'Agent','notes'=>'Notes'
              ];
            @endphp
            @foreach($all as $key => $label)
              <div class="column-item">
                <input type="checkbox" class="column-checkbox" id="col_{{ $key }}" value="{{ $key }}" @if(in_array($key,$selectedColumns)) checked @endif>
                <label for="col_{{ $key }}">{{ $label }}</label>
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
  let currentPolicyId = null;
  const lookupData = @json($lookupData);
  const selectedColumns = @json($selectedColumns);

  document.getElementById('addPolicyBtn').addEventListener('click', () => openPolicyModal('add'));
  document.getElementById('columnBtn').addEventListener('click', () => openColumnModal());

  // DFR Only Filter
  (function(){
    const btn = document.getElementById('dfrOnlyBtn');
    btn.addEventListener('click', () => {
      const rows = document.querySelectorAll('tbody tr');
      let showDfrOnly = btn.dataset.active === '1';
      showDfrOnly = !showDfrOnly;
      btn.dataset.active = showDfrOnly ? '1' : '0';
      rows.forEach(row => {
        if (showDfrOnly) {
          if (row.classList.contains('dfr-row')) {
            row.style.display = '';
          } else {
            row.style.display = 'none';
          }
          btn.style.backgroundColor = '#dc3545';
          btn.textContent = 'Show All';
        } else {
          row.style.display = '';
          btn.style.backgroundColor = '#000';
          btn.textContent = 'Due For Renewal';
        }
      });
    });
  })();

  async function openEditPolicy(id){
    try {
      const res = await fetch(`/policies/${id}/edit`, { headers: { 'Accept': 'application/json' } });
      if (!res.ok) throw new Error('Network error');
      const policy = await res.json();
      currentPolicyId = id;
      openPolicyModal('edit', policy);
    } catch (e) {
      console.error(e);
      alert('Error loading policy data');
    }
  }

  function openPolicyModal(mode, policy = null){
    const modal = document.getElementById('policyModal');
    const title = document.getElementById('policyModalTitle');
    const form = document.getElementById('policyForm');
    const formMethod = document.getElementById('policyFormMethod');
    const deleteBtn = document.getElementById('policyDeleteBtn');

    if (mode === 'add') {
      title.textContent = 'Add Policy';
      form.action = '{{ route("policies.store") }}';
      formMethod.innerHTML = '';
      deleteBtn.style.display = 'none';
      form.reset();
    } else {
      title.textContent = 'Edit Policy';
      form.action = `/policies/${currentPolicyId}`;
      formMethod.innerHTML = `@method('PUT')`;
      deleteBtn.style.display = 'inline-block';

      // Populate fields
      const fields = [
        'policy_no','client_name','insurer','policy_class','policy_plan','sum_insured','start_date','end_date','insured',
        'policy_status','date_registered','policy_id','insured_item','renewable','biz_type','term','term_unit','base_premium',
        'premium','frequency','pay_plan','agency','agent','notes'
      ];
      fields.forEach(k => {
        const el = document.getElementById(k);
        if (!el) return;
        if (el.type === 'date') {
          el.value = policy[k] ? policy[k].substring(0,10) : '';
        } else if (el.tagName === 'SELECT' || el.tagName === 'INPUT' || el.tagName === 'TEXTAREA') {
          el.value = policy[k] ?? '';
        }
      });
    }

    document.body.style.overflow = 'hidden';
    modal.classList.add('show');
  }

  function closePolicyModal(){
    document.getElementById('policyModal').classList.remove('show');
    currentPolicyId = null;
    document.body.style.overflow = '';
  }

  function deletePolicy(id=null){
    if (!id && !currentPolicyId) return;
    if (!confirm('Delete this policy?')) return;
    const policyId = id || currentPolicyId;
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = `/policies/${policyId}`;
    const csrf = document.createElement('input'); csrf.type='hidden'; csrf.name='_token'; csrf.value='{{ csrf_token() }}'; form.appendChild(csrf);
    const method = document.createElement('input'); method.type='hidden'; method.name='_method'; method.value='DELETE'; form.appendChild(method);
    document.body.appendChild(form);
    form.submit();
  }

  // Column modal functions
  function openColumnModal(){
    document.getElementById('tableResponsive').classList.add('no-scroll');
    document.querySelectorAll('.column-checkbox').forEach(cb => cb.checked = selectedColumns.includes(cb.value));
    document.body.style.overflow = 'hidden';
    document.getElementById('columnModal').classList.add('show');
  }
  function closeColumnModal(){
    document.getElementById('tableResponsive').classList.remove('no-scroll');
    document.getElementById('columnModal').classList.remove('show');
    document.body.style.overflow = '';
  }
  function selectAllColumns(){ document.querySelectorAll('.column-checkbox').forEach(cb=>cb.checked=true); }
  function deselectAllColumns(){ document.querySelectorAll('.column-checkbox').forEach(cb=>cb.checked=false); }
  function saveColumnSettings(){
    const checked = Array.from(document.querySelectorAll('.column-checkbox:checked')).map(n=>n.value);
    const form = document.getElementById('columnForm');
    const existing = form.querySelectorAll('input[name="columns[]"]'); existing.forEach(e=>e.remove());
    checked.forEach(c => {
      const i = document.createElement('input'); i.type='hidden'; i.name='columns[]'; i.value=c; form.appendChild(i);
    });
    form.submit();
    toggleTableScroll();
  }

  // Close modals on ESC or backdrop
  document.addEventListener('keydown', e => { if (e.key === 'Escape') { closePolicyModal(); closeColumnModal(); } });
  document.querySelectorAll('.modal').forEach(m => {
    m.addEventListener('click', e => { if (e.target === m) { m.classList.remove('show'); document.body.style.overflow = ''; } });
  });

  // Simple validation
  document.getElementById('policyForm').addEventListener('submit', function(e){
    const req = this.querySelectorAll('[required]');
    let ok = true;
    req.forEach(f => { if (!String(f.value||'').trim()) { ok = false; f.style.borderColor='red'; } else { f.style.borderColor=''; } });
    if (!ok) { e.preventDefault(); alert('Please fill required fields'); }
  });

  // Toggle scrollbar helper for responsive table
  function toggleTableScroll() {
    const table = document.getElementById('policiesTable');
    const wrapper = document.getElementById('tableResponsive');
    if (!table || !wrapper) return;
    const hasHorizontalOverflow = table.offsetWidth > wrapper.offsetWidth;
    const hasVerticalOverflow = table.offsetHeight > wrapper.offsetHeight;
    wrapper.classList.toggle('no-scroll', !hasHorizontalOverflow && !hasVerticalOverflow);
  }
  window.addEventListener('load', toggleTableScroll);
  window.addEventListener('resize', toggleTableScroll);

</script>
@endsection
</body>
</html>