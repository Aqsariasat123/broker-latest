<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Contacts</title>
  <style>
    /* Basic reset + layout - adapted from tasks view so modal doesn't cause body scrollbar */
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
    .btn-archived { background:#000; color:#fff; border-color:#000; }
    .btn-export, .btn-column { background:#fff; color:#000; border:1px solid #ccc; }
    .btn-back { background:#ccc; color:#333; border-color:#ccc; }
    .table-responsive { 
      width: 100%; 
      overflow-x: auto;
      border: 1px solid #ddd; 
      max-height: 420px; /* Match tasks view height */
      overflow-y: auto;
      background: #fff;
    }

    /* Center footer content */
    .footer {
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 5px 0;
      gap: 10px;
      border-top: 1px solid #ccc;
      flex-wrap: wrap;
      margin-top: 15px;
      position: relative;
    }

    /* Update paginator styles to center */
    .paginator {
      display: flex;
      align-items: center;
      gap: 5px;
      font-size: 12px;
      color: #555;
      white-space: nowrap;
      /* Remove margin-left:auto to center */
      justify-content: center;
    }

    /* Add styles for pagination text */
    .page-info {
      padding: 0 8px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      min-width: 120px;
    }

    /* Hide scrollbar when not needed */
    .table-responsive::-webkit-scrollbar {
      width: 8px;
      height: 8px;
    }

    .table-responsive::-webkit-scrollbar-track {
      background: #f1f1f1;
    }

    .table-responsive::-webkit-scrollbar-thumb {
      background: #ddd;
      border-radius: 4px;
    }

    .table-responsive::-webkit-scrollbar-thumb:hover {
      background: #ccc;
    }

    /* Hide scrollbars if content fits */
    .table-responsive.no-scroll {
      overflow: hidden !important;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      font-size: 13px;
      min-width: 900px; /* Match tasks view min-width */
    }

    thead tr {
      background-color: black;
      color: white;
      height: 35px;
      font-weight: normal;
    }

    thead th {
      padding: 6px 5px;
      text-align: left;
      border-right: 1px solid #444;
      white-space: nowrap;
      font-weight: normal;
    }

    thead th:last-child {
      border-right: none;
    }

    tbody tr {
      background-color: #fefefe;
      border-bottom: 1px solid #ddd;
      min-height: 28px;
    }

    tbody tr:nth-child(even) {
      background-color: #f8f8f8;
    }
    tbody tr.archived-row { background:#fff3cd !important; }
    tbody td { padding:5px 5px; border-right:1px solid #ddd; white-space:nowrap; vertical-align:middle; font-size:12px; }
    tbody td:last-child { border-right:none; }
    .icon-expand { cursor:pointer; color:black; text-align:center; width:20px; }
    .btn-action { padding:2px 6px; font-size:11px; margin:1px; border:1px solid #ddd; background:#fff; cursor:pointer; border-radius:2px; text-decoration:none; display:inline-block; }
    .footer { 
      display: flex; 
      align-items: center; 
      padding: 5px 0; 
      gap: 10px; 
      border-top: 1px solid #ccc; 
      flex-wrap: wrap; 
      margin-top: 15px;
      justify-content: center; /* Center pagination */
      position: relative; /* For absolute positioning of export button */
    }

    .paginator { 
      margin: 0 auto;
      display: flex;
      align-items: center; 
      gap: 5px;
      font-size: 12px;
      color: #555;
      white-space: nowrap;
      text-align: center;
      transform: translateY(-6px);
    }

    .btn-page {
      color: #2d2d2d;
      font-size: 25px;
      width: 22px;
      height: 50px;
      padding: 5px;
      cursor: pointer;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      justify-content: center;
    }

    .badge-status { font-size:11px; padding:4px 8px; display:inline-block; border-radius:4px; color:#fff; }
    /* Modal styles like tasks view (no page scrollbar) */
    .modal { display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,.5); z-index:1000; align-items:center; justify-content:center; }
    .modal.show { display:flex; }
    .modal-content { background:#fff; border-radius:4px; width:92%; max-width:1100px; max-height:calc(100vh - 40px); overflow:auto; box-shadow:0 4px 6px rgba(0,0,0,.1); padding:0; }
    .modal-header { padding:12px 15px; border-bottom:1px solid #ddd; display:flex; justify-content:space-between; align-items:center; background:#f5f5f5; }
    .modal-body { padding:15px; }
    .modal-close { background:none; border:none; font-size:18px; cursor:pointer; color:#666; }
    .form-row { display:flex; gap:10px; margin-bottom:12px; flex-wrap:wrap; align-items:flex-start; }
    .form-group { flex:0 0 calc((100% - 20px) / 3); }
    .form-group label { display:block; margin-bottom:4px; font-weight:bold; font-size:13px; }
    .form-control { width:100%; padding:6px 8px; border:1px solid #ccc; border-radius:2px; font-size:13px; }
    .modal-footer { padding:12px 15px; border-top:1px solid #ddd; display:flex; justify-content:flex-end; gap:8px; background:#f9f9f9; }
    .btn-save { background:#007bff; color:#fff; border:none; padding:6px 12px; border-radius:2px; cursor:pointer; }
    .btn-cancel { background:#6c757d; color:#fff; border:none; padding:6px 12px; border-radius:2px; cursor:pointer; }
    .btn-delete { background:#dc3545; color:#fff; border:none; padding:6px 12px; border-radius:2px; cursor:pointer; }
    /* Column selection */
    .column-selection { display:grid; grid-template-columns:repeat(auto-fill,minmax(200px,1fr)); gap:8px; margin-bottom:15px; }
    .column-item { display:flex; align-items:center; gap:8px; padding:6px 8px; border:1px solid #ddd; border-radius:2px; cursor:pointer; }
    .column-item.selected { background:#007bff; color:#fff; border-color:#007bff; }
    @media (max-width:768px) { .form-row .form-group { flex:0 0 calc((100% - 20px) / 2); } .table-responsive { max-height:500px; } }
  </style>
</head>
<body>

@extends('layouts.app')
@section('content')

@php
  // default visible columns
  $selectedColumns = session('contact_columns', [
    'contact_name','contact_no','type','occupation','employer','acquired','source','status','rank','first_contact','next_follow_up','coid','dob','salutation','source_name','agency','agent','address','email_address','contact_id','savings_budget','married','children','children_details','vehicle','house','business','other'
  ]);
@endphp

<div class="dashboard">
  <div class="container-table">
    <h3>Contacts</h3>

    @if(session('success'))
      <div class="alert alert-success" id="successAlert" style="padding:8px 12px; margin-bottom:12px; border:1px solid #c3e6cb; background:#d4edda; color:#155724;">
        {{ session('success') }}
        <button type="button" class="alert-close" onclick="document.getElementById('successAlert').style.display='none'" style="float:right;background:none;border:none;font-size:16px;cursor:pointer;">×</button>
      </div>
    @endif

    <div class="top-bar">
      <div class="left-group">
        <div class="records-found">Records Found - {{ $contacts->total() }}</div>

        <div class="left-buttons" aria-label="left action buttons">
          <a class="btn btn-export" href="{{ route('contacts.export', array_merge(request()->query(), ['page' => $contacts->currentPage()])) }}">Export</a>
          <button class="btn btn-column" id="columnBtn" type="button">Column</button>
          <button class="btn btn-archived" id="archivedOnlyBtn" type="button">Archived Only</button>
        </div>
      </div>

      <div class="action-buttons">
        <button class="btn btn-add" id="addContactBtn">Add</button>
        <button class="btn btn-back" onclick="window.history.back()">Back</button>
      </div>
    </div>

    <div class="table-responsive" id="tableResponsive">
      <table id="contactsTable">
        <thead>
          <tr>
            <th>Action</th>
            @if(in_array('contact_name', $selectedColumns))<th data-column="contact_name">Contact Name</th>@endif
            @if(in_array('contact_no', $selectedColumns))<th data-column="contact_no">Contact No</th>@endif
            @if(in_array('type', $selectedColumns))<th data-column="type">Type</th>@endif
            @if(in_array('occupation', $selectedColumns))<th data-column="occupation">Occupation</th>@endif
            @if(in_array('employer', $selectedColumns))<th data-column="employer">Employer</th>@endif
            @if(in_array('acquired', $selectedColumns))<th data-column="acquired">Acquired</th>@endif
            @if(in_array('source', $selectedColumns))<th data-column="source">Source</th>@endif
            @if(in_array('status', $selectedColumns))<th data-column="status">Status</th>@endif
            @if(in_array('rank', $selectedColumns))<th data-column="rank">Rank</th>@endif
            @if(in_array('first_contact', $selectedColumns))<th data-column="first_contact">1st Contact</th>@endif
            @if(in_array('next_follow_up', $selectedColumns))<th data-column="next_follow_up">Next FU</th>@endif
            @if(in_array('coid', $selectedColumns))<th data-column="coid">COID</th>@endif
            @if(in_array('dob', $selectedColumns))<th data-column="dob">DOB</th>@endif
            @if(in_array('salutation', $selectedColumns))<th data-column="salutation">Salutation</th>@endif
            @if(in_array('source_name', $selectedColumns))<th data-column="source_name">Source Name</th>@endif
            @if(in_array('agency', $selectedColumns))<th data-column="agency">Agency</th>@endif
            @if(in_array('agent', $selectedColumns))<th data-column="agent">Agent</th>@endif
            @if(in_array('address', $selectedColumns))<th data-column="address">Address</th>@endif
            @if(in_array('email_address', $selectedColumns))<th data-column="email_address">Email Address</th>@endif
            @if(in_array('contact_id', $selectedColumns))<th data-column="contact_id">Contact ID</th>@endif
            @if(in_array('savings_budget', $selectedColumns))<th data-column="savings_budget">Savings Budget</th>@endif
            @if(in_array('married', $selectedColumns))<th data-column="married">Married</th>@endif
            @if(in_array('children', $selectedColumns))<th data-column="children">Children</th>@endif
            @if(in_array('children_details', $selectedColumns))<th data-column="children_details">Children Details</th>@endif
            @if(in_array('vehicle', $selectedColumns))<th data-column="vehicle">Vehicle</th>@endif
            @if(in_array('house', $selectedColumns))<th data-column="house">House</th>@endif
            @if(in_array('business', $selectedColumns))<th data-column="business">Business</th>@endif
            @if(in_array('other', $selectedColumns))<th data-column="other">Other</th>@endif
          </tr>
        </thead>
        <tbody>
          @foreach($contacts as $contact)
            <tr class="{{ $contact->status === 'Archived' ? 'archived-row' : '' }}">
              <td class="icon-expand" onclick="openEditContact({{ $contact->id }})">⤢</td>
              @if(in_array('contact_name', $selectedColumns))<td data-column="contact_name">{{ $contact->contact_name }}</td>@endif
              @if(in_array('contact_no', $selectedColumns))<td data-column="contact_no">{{ $contact->contact_no ?? '##########' }}</td>@endif
              @if(in_array('type', $selectedColumns))<td data-column="type">{{ $contact->type }}</td>@endif
              @if(in_array('occupation', $selectedColumns))<td data-column="occupation">{{ $contact->occupation ?? '-' }}</td>@endif
              @if(in_array('employer', $selectedColumns))<td data-column="employer">{{ $contact->employer ?? '-' }}</td>@endif
              @if(in_array('acquired', $selectedColumns))<td data-column="acquired">{{ $contact->acquired ? $contact->acquired->format('d-M-y') : '##########' }}</td>@endif
              @if(in_array('source', $selectedColumns))<td data-column="source">{{ $contact->source }}</td>@endif
              @if(in_array('status', $selectedColumns))<td data-column="status"><span class="badge-status" style="background:{{ $contact->status == 'Archived' ? '#343a40' : ($contact->status=='Proposal Made' ? '#28a745' : ($contact->status=='In Discussion' ? '#ffc107' : '#6c757d')) }}">{{ $contact->status }}</span></td>@endif
              @if(in_array('rank', $selectedColumns))<td data-column="rank">{{ $contact->rank ?? '-' }}</td>@endif
              @if(in_array('first_contact', $selectedColumns))<td data-column="first_contact">{{ $contact->first_contact ? $contact->first_contact->format('d-M-y') : '##########' }}</td>@endif
              @if(in_array('next_follow_up', $selectedColumns))<td data-column="next_follow_up">{{ $contact->next_follow_up ? $contact->next_follow_up->format('d-M-y') : '##########' }}</td>@endif
              @if(in_array('coid', $selectedColumns))<td data-column="coid">{{ $contact->coid ?? '##########' }}</td>@endif
              @if(in_array('dob', $selectedColumns))<td data-column="dob">{{ $contact->dob ? $contact->dob->format('d-M-y') : '##########' }}</td>@endif
              @if(in_array('salutation', $selectedColumns))<td data-column="salutation">{{ $contact->salutation }}</td>@endif
              @if(in_array('source_name', $selectedColumns))<td data-column="source_name">{{ $contact->source_name ?? '-' }}</td>@endif
              @if(in_array('agency', $selectedColumns))<td data-column="agency">{{ $contact->agency ?? '-' }}</td>@endif
              @if(in_array('agent', $selectedColumns))<td data-column="agent">{{ $contact->agent ?? '-' }}</td>@endif
              @if(in_array('address', $selectedColumns))<td data-column="address">{{ $contact->address ?? '-' }}</td>@endif
              @if(in_array('email_address', $selectedColumns))<td data-column="email_address">{{ $contact->email_address ?? '-' }}</td>@endif
              @if(in_array('contact_id', $selectedColumns))<td data-column="contact_id">{{ $contact->contact_id }}</td>@endif
              @if(in_array('savings_budget', $selectedColumns))<td data-column="savings_budget">{{ $contact->savings_budget ? number_format($contact->savings_budget,2) : '##########' }}</td>@endif
              @if(in_array('married', $selectedColumns))<td data-column="married">{{ $contact->married ? 'Yes' : 'No' }}</td>@endif
              @if(in_array('children', $selectedColumns))<td data-column="children">{{ $contact->children ?? '0' }}</td>@endif
              @if(in_array('children_details', $selectedColumns))<td data-column="children_details">{{ $contact->children_details ?? '-' }}</td>@endif
              @if(in_array('vehicle', $selectedColumns))<td data-column="vehicle">{{ $contact->vehicle ?? '-' }}</td>@endif
              @if(in_array('house', $selectedColumns))<td data-column="house">{{ $contact->house ?? '-' }}</td>@endif
              @if(in_array('business', $selectedColumns))<td data-column="business">{{ $contact->business ?? '-' }}</td>@endif
              @if(in_array('other', $selectedColumns))<td data-column="other">{{ $contact->other ?? '-' }}</td>@endif
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
          $current = $contacts->currentPage();
          $last = max(1,$contacts->lastPage());
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

  <!-- Add/Edit Contact Modal (same modal for add & edit) -->
  <div class="modal" id="contactModal">
    <div class="modal-content">
      <div class="modal-header">
        <h4 id="contactModalTitle">Add Contact</h4>
        <button type="button" class="modal-close" onclick="closeContactModal()">×</button>
      </div>

      <form id="contactForm" method="POST" action="{{ route('contacts.store') }}">
        @csrf
        <div id="contactFormMethod" style="display:none;"></div>

        <div class="modal-body">
          <div class="form-row">
            <div class="form-group">
              <label for="salutation">Salutation</label>
              <select id="salutation" name="salutation" class="form-control" required>
                <option value="">Select</option>
                @foreach($lookupData['salutations'] as $s) <option value="{{ $s }}">{{ $s }}</option> @endforeach
              </select>
            </div>
            <div class="form-group">
              <label for="contact_name">Contact Name *</label>
              <input id="contact_name" name="contact_name" class="form-control" required>
            </div>
            <div class="form-group">
              <label for="contact_no">Contact No</label>
              <input id="contact_no" name="contact_no" class="form-control">
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label for="email_address">Email</label>
              <input id="email_address" name="email_address" type="email" class="form-control">
            </div>
            <div class="form-group">
              <label for="type">Type *</label>
              <select id="type" name="type" class="form-control" required>
                <option value="">Select</option>
                @foreach($lookupData['contact_types'] as $t) <option value="{{ $t }}">{{ $t }}</option> @endforeach
              </select>
            </div>
            <div class="form-group">
              <label for="occupation">Occupation</label>
              <input id="occupation" name="occupation" class="form-control">
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label for="employer">Employer</label>
              <input id="employer" name="employer" class="form-control">
            </div>
            <div class="form-group">
              <label for="acquired">Acquired</label>
              <input id="acquired" name="acquired" type="date" class="form-control">
            </div>
            <div class="form-group">
              <label for="source">Source *</label>
              <select id="source" name="source" class="form-control" required>
                <option value="">Select</option>
                @foreach($lookupData['sources'] as $s) <option value="{{ $s }}">{{ $s }}</option> @endforeach
              </select>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label for="status">Status *</label>
              <select id="status" name="status" class="form-control" required>
                <option value="">Select</option>
                @foreach($lookupData['contact_statuses'] as $st) <option value="{{ $st }}">{{ $st }}</option> @endforeach
              </select>
            </div>
            <div class="form-group">
              <label for="rank">Rank</label>
              <select id="rank" name="rank" class="form-control">
                <option value="">Select</option>
                @foreach($lookupData['ranks'] as $r) <option value="{{ $r }}">{{ $r }}</option> @endforeach
              </select>
            </div>
            <div class="form-group">
              <label for="first_contact">First Contact</label>
              <input id="first_contact" name="first_contact" type="date" class="form-control">
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label for="next_follow_up">Next Follow Up</label>
              <input id="next_follow_up" name="next_follow_up" type="date" class="form-control">
            </div>
            <div class="form-group">
              <label for="coid">COID</label>
              <input id="coid" name="coid" class="form-control">
            </div>
            <div class="form-group">
              <label for="dob">DOB</label>
              <input id="dob" name="dob" type="date" class="form-control">
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label for="agency">Agency</label>
              <select id="agency" name="agency" class="form-control">
                <option value="">Select</option>
                @foreach($lookupData['agencies'] as $a) <option value="{{ $a }}">{{ $a }}</option> @endforeach
              </select>
            </div>
            <div class="form-group">
              <label for="agent">Agent</label>
              <select id="agent" name="agent" class="form-control">
                <option value="">Select</option>
                @foreach($lookupData['agents'] as $a) <option value="{{ $a }}">{{ $a }}</option> @endforeach
              </select>
            </div>
            <div class="form-group">
              <label for="savings_budget">Savings Budget</label>
              <input id="savings_budget" name="savings_budget" type="number" step="0.01" class="form-control">
            </div>
          </div>

          <div class="form-row">
            <div class="form-group" style="flex:1">
              <label for="address">Address</label>
              <textarea id="address" name="address" class="form-control" rows="2"></textarea>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label for="married" style="display:block;">Married</label>
              <input id="married" name="married" type="checkbox" value="1">
            </div>
            <div class="form-group">
              <label for="children">Children</label>
              <input id="children" name="children" type="number" min="0" class="form-control" value="0">
            </div>
            <div class="form-group">
              <label for="children_details">Children Details</label>
              <input id="children_details" name="children_details" class="form-control">
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label for="vehicle">Vehicle</label>
              <input id="vehicle" name="vehicle" class="form-control">
            </div>
            <div class="form-group">
              <label for="house">House</label>
              <input id="house" name="house" class="form-control">
            </div>
            <div class="form-group">
              <label for="business">Business</label>
              <input id="business" name="business" class="form-control">
            </div>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn-cancel" onclick="closeContactModal()">Cancel</button>
          <button type="button" class="btn-delete" id="contactDeleteBtn" style="display:none;" onclick="deleteContact()">Delete</button>
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

        <form id="columnForm" action="{{ route('contacts.save-column-settings') }}" method="POST">
          @csrf
          <div class="column-selection">
            @php
              $all = [
                'contact_name'=>'Contact Name','contact_no'=>'Contact No','type'=>'Type','occupation'=>'Occupation','employer'=>'Employer',
                'acquired'=>'Acquired','source'=>'Source','status'=>'Status','rank'=>'Rank','first_contact'=>'1st Contact',
                'next_follow_up'=>'Next FU','coid'=>'COID','dob'=>'DOB','salutation'=>'Salutation','source_name'=>'Source Name',
                'agency'=>'Agency','agent'=>'Agent','address'=>'Address','email_address'=>'Email Address','contact_id'=>'Contact ID',
                'savings_budget'=>'Savings Budget','married'=>'Married','children'=>'Children','children_details'=>'Children Details',
                'vehicle'=>'Vehicle','house'=>'House','business'=>'Business','other'=>'Other'
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
  let currentContactId = null;
  const lookupData = @json($lookupData);
  const selectedColumns = @json($selectedColumns);

  // open add modal
  document.getElementById('addContactBtn').addEventListener('click', () => {
    openContactModal('add');
  });

  // column modal
  document.getElementById('columnBtn').addEventListener('click', () => {
    openColumnModal();
  });

  // archived filter toggles query param 'archived'
  (function(){
    const btn = document.getElementById('archivedOnlyBtn');
    const params = new URLSearchParams(window.location.search);
    const active = params.get('archived') === 'true' || params.get('archived') === '1';
    if (active) { btn.style.backgroundColor = '#dc3545'; btn.textContent = 'Show All'; }
    btn.addEventListener('click', () => {
      const u = new URL(window.location.href);
      const val = u.searchParams.get('archived');
      if (val === 'true' || val === '1') { u.searchParams.delete('archived'); } else { u.searchParams.set('archived','1'); }
      window.location.href = u.toString();
    });
  })();

  // show edit modal: fetch /contacts/{id}/edit which returns JSON in controller
  async function openEditContact(id){
    try {
      const res = await fetch(`/contacts/${id}/edit`);
      if (!res.ok) throw new Error('Network error');
      const contact = await res.json();
      currentContactId = id;
      openContactModal('edit', contact);
    } catch (e) {
      console.error(e);
      alert('Error loading contact data');
    }
  }

  function openContactModal(mode, contact = null){
    const modal = document.getElementById('contactModal');
    const title = document.getElementById('contactModalTitle');
    const form = document.getElementById('contactForm');
    const formMethod = document.getElementById('contactFormMethod');
    const deleteBtn = document.getElementById('contactDeleteBtn');

    if (mode === 'add') {
      title.textContent = 'Add Contact';
      form.action = '{{ route("contacts.store") }}';
      formMethod.innerHTML = '';
      deleteBtn.style.display = 'none';
      form.reset();
      // reset checkboxes
      document.getElementById('married').checked = false;
    } else {
      title.textContent = 'Edit Contact';
      form.action = `/contacts/${currentContactId}`;
      formMethod.innerHTML = `@method('PUT')`;
      deleteBtn.style.display = 'inline-block';

      // populate fields safely
      const fields = ['salutation','contact_name','contact_no','email_address','type','occupation','employer','acquired','source','status','rank','first_contact','next_follow_up','coid','dob','source_name','agency','agent','savings_budget','address','children','children_details','vehicle','house','business','other'];
      fields.forEach(k => {
        const el = document.getElementById(k);
        if (!el) return;
        if (el.type === 'checkbox') {
          el.checked = !!contact[k];
        } else {
          el.value = contact[k] ?? '';
        }
      });
      document.getElementById('married').checked = !!contact.married;
    }

    document.body.style.overflow = 'hidden';
    modal.classList.add('show');
  }

  function closeContactModal(){
    document.getElementById('contactModal').classList.remove('show');
    currentContactId = null;
    document.body.style.overflow = '';
  }

  function deleteContact(){
    if (!currentContactId) return;
    if (!confirm('Delete this contact?')) return;
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = `/contacts/${currentContactId}`;
    const csrf = document.createElement('input'); csrf.type='hidden'; csrf.name='_token'; csrf.value='{{ csrf_token() }}'; form.appendChild(csrf);
    const method = document.createElement('input'); method.type='hidden'; method.name='_method'; method.value='DELETE'; form.appendChild(method);
    document.body.appendChild(form);
    form.submit();
  }

  // Column modal functions
  function openColumnModal(){
    // Remove table scroll when column modal is open
    document.getElementById('tableResponsive').classList.add('no-scroll');
    
    // Set checkbox states 
    document.querySelectorAll('.column-checkbox').forEach(cb => {
      cb.checked = selectedColumns.includes(cb.value);
    });
    document.body.style.overflow = 'hidden';
    document.getElementById('columnModal').classList.add('show');
  }
  function closeColumnModal(){
    // Restore table scroll when column modal is closed
    document.getElementById('tableResponsive').classList.remove('no-scroll');
    
    document.getElementById('columnModal').classList.remove('show');
    document.body.style.overflow = '';
  }
  function selectAllColumns(){ document.querySelectorAll('.column-checkbox').forEach(cb=>cb.checked=true); }
  function deselectAllColumns(){ document.querySelectorAll('.column-checkbox').forEach(cb=>cb.checked=false); }
  function saveColumnSettings(){
    const checked = Array.from(document.querySelectorAll('.column-checkbox:checked')).map(n=>n.value);
    const form = document.getElementById('columnForm');
    // remove older hidden inputs if any (we used checkboxes so just submit form)
    // create hidden inputs to ensure controller receives columns[] values (some servers ignore unchecked)
    // We'll append hidden inputs for selected columns and submit.
    const existing = form.querySelectorAll('input[name="columns[]"]'); existing.forEach(e=>e.remove());
    checked.forEach(c => {
      const i = document.createElement('input'); i.type='hidden'; i.name='columns[]'; i.value=c; form.appendChild(i);
    });
    form.submit();
  }

  // close modals on ESC and clicking backdrop
  document.addEventListener('keydown', e => { if (e.key === 'Escape') { closeContactModal(); closeColumnModal(); } });
  document.querySelectorAll('.modal').forEach(m => {
    m.addEventListener('click', e => { if (e.target === m) { m.classList.remove('show'); document.body.style.overflow = ''; } });
  });

  // Basic client-side validation for contact form (prevent empty required)
  document.getElementById('contactForm').addEventListener('submit', function(e){
    const req = this.querySelectorAll('[required]');
    let ok = true;
    req.forEach(f => { if (!String(f.value||'').trim()) { ok = false; f.style.borderColor='red'; } else { f.style.borderColor=''; } });
    if (!ok) { e.preventDefault(); alert('Please fill required fields'); }
  });

  // Add function to check and toggle scrollbar
  function toggleTableScroll() {
    const table = document.getElementById('contactsTable');
    const wrapper = document.querySelector('.table-responsive');
    
    if (!table || !wrapper) return;
    
    // Check if content width is less than wrapper width
    const hasHorizontalOverflow = table.offsetWidth > wrapper.offsetWidth;
    const hasVerticalOverflow = table.offsetHeight > wrapper.offsetHeight;
    
    // Toggle scroll class
    wrapper.classList.toggle('no-scroll', !hasHorizontalOverflow && !hasVerticalOverflow);
  }

  // Call on load and window resize
  window.addEventListener('load', toggleTableScroll);
  window.addEventListener('resize', toggleTableScroll);

  // Also check after column settings change
  function saveColumnSettings(){
    const checked = Array.from(document.querySelectorAll('.column-checkbox:checked')).map(n=>n.value);
    const form = document.getElementById('columnForm');
    // remove older hidden inputs if any (we used checkboxes so just submit form)
    // create hidden inputs to ensure controller receives columns[] values (some servers ignore unchecked)
    // We'll append hidden inputs for selected columns and submit.
    const existing = form.querySelectorAll('input[name="columns[]"]'); existing.forEach(e=>e.remove());
    checked.forEach(c => {
      const i = document.createElement('input'); i.type='hidden'; i.name='columns[]'; i.value=c; form.appendChild(i);
    });
    form.submit();
    toggleTableScroll(); // Add this line
  }
</script>

@endsection
</body>
</html>