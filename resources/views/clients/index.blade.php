<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Clients</title>
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
    .btn-export, .btn-column { background:#fff; color:#000; border:1px solid #ccc; }
    .btn-back { background:#ccc; color:#333; border-color:#ccc; }
    .table-responsive { width: 100%; overflow-x: auto; border: 1px solid #ddd; max-height: 520px; overflow-y: auto; background: #fff; }
    .footer { display:flex; justify-content:center; align-items:center; padding:5px 0; gap:10px; border-top:1px solid #ccc; flex-wrap:wrap; margin-top:15px; }
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
    .btn-page{
            color: #2d2d2d;
    font-size: 25px;
    width: 22px;
    height: 50px;
    padding: 5px;
    cursor: pointer;
    text-decoration: none;
    display: inline-flex
;
    align-items: center;
    justify-content: center;
    }
    table { width:100%; border-collapse:collapse; font-size:13px; min-width:900px; }
    thead tr { background-color: black; color: white; height:35px; font-weight: normal; }
    thead th { padding:6px 5px; text-align:left; border-right:1px solid #444; white-space:nowrap; font-weight: normal; }
    thead th:last-child { border-right:none; }
    tbody tr { background-color:#fefefe; border-bottom:1px solid #ddd; min-height:28px; }
    tbody tr:nth-child(even) { background-color:#f8f8f8; }
    tbody tr.inactive-row { background:#fff3cd !important; }
    tbody td { padding:5px 5px; border-right:1px solid #ddd; white-space:nowrap; vertical-align:middle; font-size:12px; }
    tbody td:last-child { border-right:none; }
    .icon-expand { cursor:pointer; color:black; text-align:center; width:20px; }
    .btn-action { padding:2px 6px; font-size:11px; margin:1px; border:1px solid #ddd; background:#fff; cursor:pointer; border-radius:2px; display:inline-block; }
    .badge-status { font-size:11px; padding:4px 8px; display:inline-block; border-radius:4px; color:#fff; }
    /* Modal styles (simple, like contacts) */
    .modal { display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,.5); z-index:1000; align-items:center; justify-content:center; }
    .modal.show { display:flex; }
    .modal-content { background:#fff; border-radius:6px; width:92%; max-width:1100px; max-height:calc(100vh - 40px); overflow:auto; box-shadow:0 4px 6px rgba(0,0,0,.1); padding:0; }
    .modal-header { padding:12px 15px; border-bottom:1px solid #ddd; display:flex; justify-content:space-between; align-items:center; background:#f5f5f5; }
    .modal-body { padding:15px; }
    .modal-close { background:none; border:none; font-size:18px; cursor:pointer; color:#666; }
    .modal-footer
    { padding:12px 15px; border-top:1px solid #ddd; display:flex; 
      justify-content:flex-end; gap:8px; background:#f9f9f9; }
    .form-row { display:flex; gap:10px; margin-bottom:12px; flex-wrap:wrap; align-items:flex-start; }
    .form-group { flex:0 0 calc((100% - 20px) / 3); }
    .form-group label { display:block; margin-bottom:4px; font-weight:600; font-size:13px; }
    .form-control, select, textarea { width:100%; padding:6px 8px; border:1px solid #ccc; border-radius:2px; font-size:13px; }
    .btn-save { background:#007bff; color:#fff; border:none; padding:6px 12px; border-radius:2px; cursor:pointer; }
    .btn-cancel { background:#6c757d; color:#fff; border:none; padding:6px 12px; border-radius:2px; cursor:pointer; }
    .btn-delete { background:#dc3545; color:#fff; border:none; padding:6px 12px; border-radius:2px; cursor:pointer; }
    .column-selection { display:grid; grid-template-columns:repeat(auto-fill,minmax(200px,1fr)); gap:8px; margin-bottom:15px; }
    .column-item { display:flex; align-items:center; gap:8px; padding:6px 8px; border:1px solid #ddd; border-radius:2px; cursor:pointer; }
    @media (max-width:768px) { .form-row .form-group { flex:0 0 calc((100% - 20px) / 2); } .table-responsive { max-height:500px; } }
  </style>
</head>
<body>

@extends('layouts.app')
@section('content')

@php
  $selectedColumns = session('client_columns', [
    'client_name','client_type','nin_bcrn','dob_dor','mobile_no','wa','district','occupation','source','status','signed_up',
    'employer','clid','contact_person','income_source','married','spouses_name','alternate_no','email_address','location',
    'island','country','po_box_no','pep','pep_comment','image','salutation','first_name','other_names','surname','passport_no'
  ]);
@endphp

<div class="dashboard">
  <div class="container-table">
    <h3>Clients</h3>

    @if(session('success'))
      <div class="alert alert-success" id="successAlert" style="padding:8px 12px; margin-bottom:12px; border:1px solid #c3e6cb; background:#d4edda; color:#155724;">
        {{ session('success') }}
        <button type="button" class="alert-close" onclick="document.getElementById('successAlert').style.display='none'" style="float:right;background:none;border:none;font-size:16px;cursor:pointer;">×</button>
      </div>
    @endif

    <div class="top-bar">
      <div class="left-group">
        <div class="records-found">Records Found - {{ $clients->total() }}</div>

        <div class="left-buttons" aria-label="left action buttons">
          <a class="btn btn-export" href="{{ route('clients.export', array_merge(request()->query(), ['page' => $clients->currentPage()])) }}">Export</a>
          <button class="btn btn-column" id="columnBtn" type="button">Column</button>
        </div>
      </div>

      <div class="action-buttons">
        <button class="btn btn-add" id="addClientBtn">Add</button>
        <button class="btn btn-back" onclick="window.history.back()">Back</button>
      </div>
    </div>

    <div class="table-responsive" id="tableResponsive">
      <table id="clientsTable">
        <thead>
          <tr>
            <th>Action</th>
            @if(in_array('client_name',$selectedColumns))<th data-column="client_name">Client Name</th>@endif
            @if(in_array('client_type',$selectedColumns))<th data-column="client_type">Client Type</th>@endif
            @if(in_array('nin_bcrn',$selectedColumns))<th data-column="nin_bcrn">NIN/BCRN</th>@endif
            @if(in_array('dob_dor',$selectedColumns))<th data-column="dob_dor">DOB/DOR</th>@endif
            @if(in_array('mobile_no',$selectedColumns))<th data-column="mobile_no">MobileNo</th>@endif
            @if(in_array('wa',$selectedColumns))<th data-column="wa">WA</th>@endif
            @if(in_array('district',$selectedColumns))<th data-column="district">District</th>@endif
            @if(in_array('occupation',$selectedColumns))<th data-column="occupation">Occupation</th>@endif
            @if(in_array('source',$selectedColumns))<th data-column="source">Source</th>@endif
            @if(in_array('status',$selectedColumns))<th data-column="status">Status</th>@endif
            @if(in_array('signed_up',$selectedColumns))<th data-column="signed_up">Signed Up</th>@endif
            @if(in_array('employer',$selectedColumns))<th data-column="employer">Employer</th>@endif
            @if(in_array('clid',$selectedColumns))<th data-column="clid">CLID</th>@endif
            @if(in_array('contact_person',$selectedColumns))<th data-column="contact_person">Contact Person</th>@endif
            @if(in_array('income_source',$selectedColumns))<th data-column="income_source">Income Source</th>@endif
            @if(in_array('married',$selectedColumns))<th data-column="married">Married</th>@endif
            @if(in_array('spouses_name',$selectedColumns))<th data-column="spouses_name">Spouses Name</th>@endif
            @if(in_array('alternate_no',$selectedColumns))<th data-column="alternate_no">Alternate No</th>@endif
            @if(in_array('email_address',$selectedColumns))<th data-column="email_address">Email Address</th>@endif
            @if(in_array('location',$selectedColumns))<th data-column="location">Location</th>@endif
            @if(in_array('island',$selectedColumns))<th data-column="island">Island</th>@endif
            @if(in_array('country',$selectedColumns))<th data-column="country">Country</th>@endif
            @if(in_array('po_box_no',$selectedColumns))<th data-column="po_box_no">P.O. Box No</th>@endif
            @if(in_array('pep',$selectedColumns))<th data-column="pep">PEP</th>@endif
            @if(in_array('pep_comment',$selectedColumns))<th data-column="pep_comment">PEP Comment</th>@endif
            @if(in_array('image',$selectedColumns))<th data-column="image">Image</th>@endif
            @if(in_array('salutation',$selectedColumns))<th data-column="salutation">Salutation</th>@endif
            @if(in_array('first_name',$selectedColumns))<th data-column="first_name">First Name</th>@endif
            @if(in_array('other_names',$selectedColumns))<th data-column="other_names">Other Names</th>@endif
            @if(in_array('surname',$selectedColumns))<th data-column="surname">Surname</th>@endif
            @if(in_array('passport_no',$selectedColumns))<th data-column="passport_no">Passport No</th>@endif
          </tr>
        </thead>
        <tbody>
          @foreach($clients as $client)
            <tr class="{{ $client->status === 'Inactive' ? 'inactive-row' : '' }}">
              <td class="icon-expand" onclick="openEditClient({{ $client->id }})">⤢</td>
              @if(in_array('client_name',$selectedColumns))<td data-column="client_name">{{ $client->client_name }}</td>@endif
              @if(in_array('client_type',$selectedColumns))<td data-column="client_type">{{ $client->client_type }}</td>@endif
              @if(in_array('nin_bcrn',$selectedColumns))<td data-column="nin_bcrn">{{ $client->nin_bcrn ?? '##########' }}</td>@endif
              @if(in_array('dob_dor',$selectedColumns))<td data-column="dob_dor">{{ $client->dob_dor ? $client->dob_dor->format('d-M-y') : '##########' }}</td>@endif
              @if(in_array('mobile_no',$selectedColumns))<td data-column="mobile_no">{{ $client->mobile_no }}</td>@endif
              @if(in_array('wa',$selectedColumns))<td data-column="wa">{{ $client->wa ?? '-' }}</td>@endif
              @if(in_array('district',$selectedColumns))<td data-column="district">{{ $client->district ?? '-' }}</td>@endif
              @if(in_array('occupation',$selectedColumns))<td data-column="occupation">{{ $client->occupation ?? '-' }}</td>@endif
              @if(in_array('source',$selectedColumns))<td data-column="source">{{ $client->source }}</td>@endif
              @if(in_array('status',$selectedColumns))<td data-column="status"><span class="badge-status" style="background:{{ $client->status == 'Active' ? '#28a745' : ($client->status=='Inactive' ? '#6c757d' : '#ffc107') }}">{{ $client->status }}</span></td>@endif
              @if(in_array('signed_up',$selectedColumns))<td data-column="signed_up">{{ $client->signed_up ? $client->signed_up->format('d-M-y') : '##########' }}</td>@endif
              @if(in_array('employer',$selectedColumns))<td data-column="employer">{{ $client->employer ?? '-' }}</td>@endif
              @if(in_array('clid',$selectedColumns))<td data-column="clid">{{ $client->clid }}</td>@endif
              @if(in_array('contact_person',$selectedColumns))<td data-column="contact_person">{{ $client->contact_person ?? '-' }}</td>@endif
              @if(in_array('income_source',$selectedColumns))<td data-column="income_source">{{ $client->income_source ?? '-' }}</td>@endif
              @if(in_array('married',$selectedColumns))<td data-column="married">{{ $client->married ? 'Yes' : 'No' }}</td>@endif
              @if(in_array('spouses_name',$selectedColumns))<td data-column="spouses_name">{{ $client->spouses_name ?? '-' }}</td>@endif
              @if(in_array('alternate_no',$selectedColumns))<td data-column="alternate_no">{{ $client->alternate_no ?? '-' }}</td>@endif
              @if(in_array('email_address',$selectedColumns))<td data-column="email_address">{{ $client->email_address ?? '-' }}</td>@endif
              @if(in_array('location',$selectedColumns))<td data-column="location">{{ $client->location ?? '-' }}</td>@endif
              @if(in_array('island',$selectedColumns))<td data-column="island">{{ $client->island ?? '-' }}</td>@endif
              @if(in_array('country',$selectedColumns))<td data-column="country">{{ $client->country ?? '-' }}</td>@endif
              @if(in_array('po_box_no',$selectedColumns))<td data-column="po_box_no">{{ $client->po_box_no ?? '-' }}</td>@endif
              @if(in_array('pep',$selectedColumns))<td data-column="pep">{{ $client->pep ? 'Yes' : 'No' }}</td>@endif
              @if(in_array('pep_comment',$selectedColumns))<td data-column="pep_comment">{{ $client->pep_comment ?? '-' }}</td>@endif
              @if(in_array('image',$selectedColumns))<td data-column="image">{{ $client->image ? '📷' : '-' }}</td>@endif
              @if(in_array('salutation',$selectedColumns))<td data-column="salutation">{{ $client->salutation ?? '-' }}</td>@endif
              @if(in_array('first_name',$selectedColumns))<td data-column="first_name">{{ $client->first_name }}</td>@endif
              @if(in_array('other_names',$selectedColumns))<td data-column="other_names">{{ $client->other_names ?? '-' }}</td>@endif
              @if(in_array('surname',$selectedColumns))<td data-column="surname">{{ $client->surname }}</td>@endif
              @if(in_array('passport_no',$selectedColumns))<td data-column="passport_no">{{ $client->passport_no ?? '-' }}</td>@endif
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
          $current = $clients->currentPage();
          $last = max(1,$clients->lastPage());
          function page_url($base,$q,$p){ $params = array_merge($q,['page'=>$p]); return $base . '?' . http_build_query($params); }
        @endphp

        <a class="btn-page" href="{{ $current>1 ? page_url($base,$q,1) : '#' }}" @if($current<=1) disabled @endif>&laquo;</a>
        <a class="btn-page" href="{{ $current>1 ? page_url($base,$q,$current-1) : '#' }}" @if($current<=1) disabled @endif>&lsaquo;</a>
        <span class="page-info" style="padding:0 8px;">Page {{ $current }} of {{ $last }}</span>
        <a class="btn-page" href="{{ $current<$last ? page_url($base,$q,$current+1) : '#' }}" @if($current>= $last) disabled @endif>&rsaquo;</a>
        <a class="btn-page" href="{{ $current<$last ? page_url($base,$q,$last) : '#' }}" @if($current>=$last) disabled @endif>&raquo;</a>
      </div>
    </div>
  </div>

  <!-- Add/Edit Client Modal (single) -->
  <div class="modal" id="clientModal">
    <div class="modal-content">
      <div class="modal-header">
        <h4 id="clientModalTitle">Add Client</h4>
       <!-- Modal header buttons -->

        <button type="button" class="modal-close" onclick="closeClientModal()">×</button>

      </div>
         <div class="modal-footer">
          <button type="button" class="btn-cancel" onclick="window.location.href='/life-proposals'">Proposal</button>
          <button type="button" class="btn-delete" onclick="window.location.href='/policies'">Policies</button>
          <button type="submit" class="btn-save" onclick="window.location.href='/claims'">Claims</button>
          <button type="button" class="btn-cancel" onclick="window.location.href='/documents'">Document</button>
        </div>
      <form id="clientForm" method="POST" action="{{ route('clients.store') }}">
        @csrf
        <div id="clientFormMethod" style="display:none;"></div>

        <div class="modal-body">
          <div class="form-row">
            <div class="form-group">
              <label for="salutation">Salutation</label>
              <select id="salutation" name="salutation" class="form-control">
                <option value="">Select</option>
                @foreach($lookupData['salutations'] as $s) <option value="{{ $s }}">{{ $s }}</option> @endforeach
              </select>
            </div>

            <div class="form-group">
              <label for="first_name">First Name *</label>
              <input id="first_name" name="first_name" class="form-control" required>
            </div>

            <div class="form-group">
              <label for="other_names">Other Names</label>
              <input id="other_names" name="other_names" class="form-control">
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label for="surname">Surname *</label>
              <input id="surname" name="surname" class="form-control" required>
            </div>

            <div class="form-group">
              <label for="client_type">Client Type *</label>
              <select id="client_type" name="client_type" class="form-control" required>
                <option value="">Select</option>
                @foreach($lookupData['client_types'] as $t) <option value="{{ $t }}">{{ $t }}</option> @endforeach
              </select>
            </div>

            <div class="form-group">
              <label for="nin_bcrn">NIN/BCRN</label>
              <input id="nin_bcrn" name="nin_bcrn" class="form-control">
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label for="dob_dor">DOB/DOR</label>
              <input id="dob_dor" name="dob_dor" type="date" class="form-control">
            </div>
            <div class="form-group">
              <label for="passport_no">Passport No</label>
              <input id="passport_no" name="passport_no" class="form-control">
            </div>
            <div class="form-group">
              <label for="mobile_no">Mobile No *</label>
              <input id="mobile_no" name="mobile_no" class="form-control" required>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label for="wa">WhatsApp</label>
              <input id="wa" name="wa" class="form-control">
            </div>
            <div class="form-group">
              <label for="alternate_no">Alternate No</label>
              <input id="alternate_no" name="alternate_no" class="form-control">
            </div>
            <div class="form-group">
              <label for="email_address">Email</label>
              <input id="email_address" name="email_address" type="email" class="form-control">
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label for="occupation">Occupation</label>
              <select id="occupation" name="occupation" class="form-control">
                <option value="">Select</option>
                @foreach($lookupData['occupations'] as $o) <option value="{{ $o }}">{{ $o }}</option> @endforeach
              </select>
            </div>
            <div class="form-group">
              <label for="employer">Employer</label>
              <input id="employer" name="employer" class="form-control">
            </div>
            <div class="form-group">
              <label for="income_source">Income Source</label>
              <select id="income_source" name="income_source" class="form-control">
                <option value="">Select</option>
                @foreach($lookupData['income_sources'] as $i) <option value="{{ $i }}">{{ $i }}</option> @endforeach
              </select>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label for="source">Source *</label>
              <select id="source" name="source" class="form-control" required>
                <option value="">Select</option>
                @foreach($lookupData['sources'] as $s) <option value="{{ $s }}">{{ $s }}</option> @endforeach
              </select>
            </div>

            <div class="form-group">
              <label for="status">Status *</label>
              <select id="status" name="status" class="form-control" required>
                <option value="">Select</option>
                @foreach($lookupData['client_statuses'] as $st) <option value="{{ $st }}">{{ $st }}</option> @endforeach
              </select>
            </div>

            <div class="form-group">
              <label for="signed_up">Signed Up *</label>
              <input id="signed_up" name="signed_up" type="date" class="form-control" required>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group" style="flex:1">
              <label for="location">Location</label>
              <textarea id="location" name="location" class="form-control" rows="2"></textarea>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label for="district">District</label>
              <select id="district" name="district" class="form-control">
                <option value="">Select</option>
                @foreach($lookupData['districts'] as $d) <option value="{{ $d }}">{{ $d }}</option> @endforeach
              </select>
            </div>
            <div class="form-group">
              <label for="island">Island</label>
              <select id="island" name="island" class="form-control">
                <option value="">Select</option>
                @foreach($lookupData['islands'] as $is) <option value="{{ $is }}">{{ $is }}</option> @endforeach
              </select>
            </div>
            <div class="form-group">
              <label for="country">Country</label>
              <select id="country" name="country" class="form-control">
                <option value="">Select</option>
                @foreach($lookupData['countries'] as $c) <option value="{{ $c }}">{{ $c }}</option> @endforeach
              </select>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label for="po_box_no">P.O. Box No</label>
              <input id="po_box_no" name="po_box_no" class="form-control">
            </div>

            <div class="form-group">
              <label style="display:block;">Married</label>
              <input id="married" name="married" type="checkbox" value="1">
            </div>

            <div class="form-group">
              <label for="spouses_name">Spouses Name</label>
              <input id="spouses_name" name="spouses_name" class="form-control">
            </div>
          </div>

          <div class="form-row">
            <div class="form-group" style="flex:1">
              <label for="contact_person">Contact Person</label>
              <input id="contact_person" name="contact_person" class="form-control">
            </div>
            <div class="form-group">
              <label for="pep" style="display:block;">PEP</label>
              <input id="pep" name="pep" type="checkbox" value="1">
            </div>
            <div class="form-group">
              <label for="pep_comment">PEP Comment</label>
              <input id="pep_comment" name="pep_comment" class="form-control">
            </div>
          </div>

        </div>

        <div class="modal-footer">
          <button type="button" class="btn-cancel" onclick="closeClientModal()">Cancel</button>
          <button type="button" class="btn-delete" id="clientDeleteBtn" style="display:none;" onclick="deleteClient()">Delete</button>
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

        <form id="columnForm" action="{{ route('clients.save-column-settings') }}" method="POST">
          @csrf
          <div class="column-selection">
            @php
              $all = [
                'client_name'=>'Client Name','client_type'=>'Client Type','nin_bcrn'=>'NIN/BCRN','dob_dor'=>'DOB/DOR','mobile_no'=>'MobileNo',
                'wa'=>'WA','district'=>'District','occupation'=>'Occupation','source'=>'Source','status'=>'Status','signed_up'=>'Signed Up',
                'employer'=>'Employer','clid'=>'CLID','contact_person'=>'Contact Person','income_source'=>'Income Source','married'=>'Married',
                'spouses_name'=>'Spouses Name','alternate_no'=>'Alternate No','email_address'=>'Email Address','location'=>'Location',
                'island'=>'Island','country'=>'Country','po_box_no'=>'P.O. Box No','pep'=>'PEP','pep_comment'=>'PEP Comment',
                'image'=>'Image','salutation'=>'Salutation','first_name'=>'First Name','other_names'=>'Other Names','surname'=>'Surname','passport_no'=>'Passport No'
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
  let currentClientId = null;
  const lookupData = @json($lookupData);
  const selectedColumns = @json($selectedColumns);

  document.getElementById('addClientBtn').addEventListener('click', () => openClientModal('add'));
  document.getElementById('columnBtn').addEventListener('click', () => openColumnModal());

  async function openEditClient(id){
    try {
      const res = await fetch(`/clients/${id}/edit`);
      if (!res.ok) throw new Error('Network error');
      const client = await res.json();
      currentClientId = id;
      openClientModal('edit', client);
    } catch (e) {
      console.error(e);
      alert('Error loading client data');
    }
  }

  function openClientModal(mode, client = null){
    const modal = document.getElementById('clientModal');
    const title = document.getElementById('clientModalTitle');
    const form = document.getElementById('clientForm');
    const formMethod = document.getElementById('clientFormMethod');
    const deleteBtn = document.getElementById('clientDeleteBtn');

    if (mode === 'add') {
      title.textContent = 'Add Client';
      form.action = '{{ route("clients.store") }}';
      formMethod.innerHTML = '';
      deleteBtn.style.display = 'none';
      form.reset();
      // ensure checkboxes cleared
      document.getElementById('married').checked = false;
      document.getElementById('pep').checked = false;
    } else {
      title.textContent = 'Edit Client';
      form.action = `/clients/${currentClientId}`;
      formMethod.innerHTML = `@method('PUT')`;
      deleteBtn.style.display = 'inline-block';

      const fields = ['salutation','first_name','other_names','surname','client_type','nin_bcrn','dob_dor','passport_no','mobile_no','wa','alternate_no','email_address','occupation','employer','income_source','source','status','signed_up','location','district','island','country','po_box_no','spouses_name','contact_person','pep_comment'];
      fields.forEach(k => {
        const el = document.getElementById(k);
        if (!el) return;
        if (el.type === 'checkbox') {
          el.checked = !!client[k];
        } else if (el.tagName === 'SELECT' || el.tagName === 'INPUT' || el.tagName === 'TEXTAREA') {
          el.value = client[k] ?? '';
        }
      });
      document.getElementById('married').checked = !!client.married;
      document.getElementById('pep').checked = !!client.pep;
    }

    document.body.style.overflow = 'hidden';
    modal.classList.add('show');
  }

  function closeClientModal(){
    document.getElementById('clientModal').classList.remove('show');
    currentClientId = null;
    document.body.style.overflow = '';
  }

  function deleteClient(){
    if (!currentClientId) return;
    if (!confirm('Delete this client?')) return;
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = `/clients/${currentClientId}`;
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
  }

  // Close modals on ESC or backdrop
  document.addEventListener('keydown', e => { if (e.key === 'Escape') { closeClientModal(); closeColumnModal(); } });
  document.querySelectorAll('.modal').forEach(m => {
    m.addEventListener('click', e => { if (e.target === m) { m.classList.remove('show'); document.body.style.overflow = ''; } });
  });

  // Simple validation
  document.getElementById('clientForm').addEventListener('submit', function(e){
    const req = this.querySelectorAll('[required]');
    let ok = true;
    req.forEach(f => { if (!String(f.value||'').trim()) { ok = false; f.style.borderColor='red'; } else { f.style.borderColor=''; } });
    if (!ok) { e.preventDefault(); alert('Please fill required fields'); }
  });

  // Toggle scrollbar helper for responsive table
  function toggleTableScroll() {
    const table = document.getElementById('clientsTable');
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