<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Tasks Table</title>
  <style>
    * {
      box-sizing: border-box;
    }

    body {
      font-family: Arial, sans-serif;
      color: #000;
      margin: 10px;
      background: #fff;
    }

    .container-table {
      max-width: 100%;
      margin: 0 auto;
    }

    h3 {
      background: #f1f1f1;
      padding: 8px;
      margin-bottom: 10px;
      font-weight: bold;
      border: 1px solid #ddd;
    }

    .top-bar {
      display: flex;
      align-items: center;
      flex-wrap: wrap;
      gap: 10px;
      margin-bottom: 10px;
    }

    /* Left group contains records and left-side buttons (Export, Column, Overdue) */
    .left-group {
      display: flex;
      align-items: center;
      gap: 10px;
      flex: 1 1 auto;
      min-width: 220px;
    }

    .left-buttons {
      display: flex;
      gap: 10px;
      align-items: center;
    }

    .records-found {
      font-size: 14px;
      color: #555;
      min-width: 150px;
    }

    .filter-section {
      display: flex;
      align-items: center;
      gap: 10px;
      flex-wrap: nowrap;
    }

    .action-buttons {
      margin-left: auto;
      display: flex;
      gap: 10px;
    }

    .btn {
      border: none;
      cursor: pointer;
      padding: 6px 12px;
      font-size: 13px;
      border-radius: 2px;
      white-space: nowrap;
      transition: background-color 0.3s ease;
    }

    .btn-overdue {
      background-color: black;
      color: white;
    }

    .btn-overdue:hover {
      background-color: #222;
    }

    .btn-add {
      background-color: #df7900;
      color: white;
    }

    .btn-add:hover {
      background-color: #b46500;
    }

    .btn-back {
      background-color: #ccc;
      color: #333;
    }

    .btn-back:hover {
      background-color: #aaa;
    }

    .table-responsive {
      width: 100%;
      overflow-x: auto;
      border: 1px solid #ddd;
      max-height: 420px;
      overflow-y: auto;
      background: #fff;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      font-size: 13px;
      min-width: 900px;
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

    tbody tr.overdue {
      background-color: #ffe6e6;
    }

    tbody td {
      padding: 5px 5px;
      border-right: 1px solid #ddd;
      white-space: nowrap;
      vertical-align: middle;
      font-weight: 400;
      font-size: 12px;
    }

    tbody td:last-child {
      border-right: none;
    }

    .icon-expand {
      cursor: pointer;
      color: black;
      text-align: center;
      width: 20px;
    }

    .icon-dot {
      width: 20px;
      text-align: center;
      color: red;
      font-size: 14px;
      font-weight: bold;
    }

    input[type="radio"] {
      cursor: pointer;
    }

    input[type="radio"]:disabled {
      cursor: default;
    }

    footer {
      margin-top: 15px;
    }

    .footer {
      display: flex;
      align-items: center;
      padding: 5px 0;
      gap: 10px;
      border-top: 1px solid #ccc;
      flex-wrap: wrap;
    }

    .btn-export {
      background: white;
        text-decoration: none;
      border: 1px solid #ccc;
      color: black;
    }

    .btn-export:hover {
      background: #eee;
    }

    .btn-column {
      background: white;
      border: 1px solid #ccc;
      color: black;
    }

    .btn-column:hover {
      background: #eee;
    }

    .paginator {
      /* center paginator horizontally */
      margin: 0 auto;
      display: flex;
      align-items: center;
      gap: 5px;
      font-size: 12px;
      color: #555;
      flex-wrap: nowrap;
      white-space: nowrap;
      text-align: center;
      transform: translateY(-6px); /* move page label slightly up */
    }

    .footer a{
      text-decoration: none;
    }

    .btn-page {
      color: #2d2d2d;
      font-size: 25px;
      width: 22px;
      height: 50px;
      padding: 5px;
      cursor: pointer;
    }

    .btn-page:disabled {
      cursor: not-allowed;
      opacity: 0.5;
    }

    /* Switch toggle style */
    .switch {
      position: relative;
      display: inline-block;
      width: 40px;
      height: 18px;
    }

    .switch input {
      opacity: 0;
      width: 0;
      height: 0;
    }

    .slider {
      position: absolute;
      cursor: pointer;
      top: 1px;
      left: 0;
      right: 0;
      bottom: 0;
      background-color: #ccc;
      transition: 0.2s;
      border-radius: 10px;
    }

    .slider:before {
      position: absolute;
      content: "";
      height: 14px;
      width: 14px;
      left: 2px;
      bottom: 2px;
      background-color: white;
      transition: 0.2s;
      border-radius: 50%;
    }

    input:checked + .slider {
      background-color: #4a4a4a;
    }

    input:checked + .slider:before {
      transform: translateX(22px);
    }

    /* Modal Styles */
    .modal {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.5);
      z-index: 1000;
      align-items: center;
      justify-content: center;
    }

    .modal.show {
      display: flex;
    }

    .modal-content {
      background: white;
      border-radius: 4px;
      width: 88%;
      max-width: 1000px; /* increased so desktop layout fits without forcing inner scroll */
      max-height: calc(100vh - 40px); /* ensure modal fits viewport and inner scrolls only in modal */
      overflow: auto;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      padding: 0;
    }

    .modal-header {
      padding: 12px 15px;
      border-bottom: 1px solid #ddd;
      display: flex;
      justify-content: space-between;
      align-items: center;
      background: #f5f5f5;
    }

    .modal-header h4 {
      margin: 0;
      font-size: 16px;
      font-weight: bold;
    }

    .modal-close {
      background: none;
      border: none;
      font-size: 18px;
      cursor: pointer;
      color: #666;
    }

    .modal-body {
      padding: 15px;
    }

    .form-group {
      margin-bottom: 12px;
    }

    .form-group label {
      display: block;
      margin-bottom: 4px;
      font-weight: bold;
      font-size: 13px;
    }

    .form-control {
      width: 100%;
      padding: 6px 8px;
      border: 1px solid #ccc;
      border-radius: 2px;
      font-size: 13px;
    }

    /* Layout: make rows support 3 inputs per row on desktop */
    .form-row {
      display: flex;
      gap: 10px;
      margin-bottom: 12px;
      flex-wrap: wrap;
      align-items: flex-start;
    }

    /* Default: 3 columns per .form-row (desktop). Subtract total gap (2 * 10px) */
    .form-row .form-group {
      flex: 0 0 calc((100% - 20px) / 3);
      margin-bottom: 0;
    }

    .modal-footer {
      padding: 12px 15px;
      border-top: 1px solid #ddd;
      display: flex;
      justify-content: flex-end;
      gap: 8px;
      background: #f9f9f9;
    }

    .btn-save {
      background: #007bff;
      color: white;
      border: none;
      padding: 6px 12px;
      border-radius: 2px;
      cursor: pointer;
    }

    .btn-cancel {
      background: #6c757d;
      color: white;
      border: none;
      padding: 6px 12px;
      border-radius: 2px;
      cursor: pointer;
    }

    .btn-delete {
      background: #dc3545;
      color: white;
      border: none;
      padding: 6px 12px;
      border-radius: 2px;
      cursor: pointer;
    }

    .alert {
      padding: 8px 12px;
      margin-bottom: 12px;
      border-radius: 2px;
      font-size: 13px;
    }

    .alert-success {
      background: #d4edda;
      color: #155724;
      border: 1px solid #c3e6cb;
    }

    .alert-error {
      background: #f8d7da;
      color: #721c24;
      border: 1px solid #f5c6cb;
    }

    /* Column Selection Styles */
    .column-selection {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
      gap: 8px;
      margin-bottom: 15px;
    }

    .column-item {
      display: flex;
      align-items: center;
      gap: 8px;
      padding: 6px 8px;
      border: 1px solid #ddd;
      border-radius: 2px;
      cursor: pointer;
      transition: all 0.2s;
    }

    .column-item:hover {
      background: #f5f5f5;
    }

    .column-item.selected {
      background: #007bff;
      color: white;
      border-color: #007bff;
    }

    .column-item input[type="checkbox"] {
      margin: 0;
    }

    .column-actions {
      display: flex;
      gap: 8px;
      margin-bottom: 15px;
    }

    .btn-select-all {
      background: #28a745;
      color: white;
      border: none;
      padding: 6px 12px;
      border-radius: 2px;
      cursor: pointer;
      font-size: 12px;
    }

    .btn-deselect-all {
      background: #dc3545;
      color: white;
      border: none;
      padding: 6px 12px;
      border-radius: 2px;
      cursor: pointer;
      font-size: 12px;
    }

    @media (max-width: 1100px) {
      table {
        min-width: 700px;
      }

      /* slightly reduce columns inside modal so it doesn't force inner scrollbar too early */
      .form-row .form-group {
        flex: 0 0 calc((100% - 20px) / 2);
      }
    }

    @media (max-width: 768px) {
      .top-bar {
        flex-direction: column;
        align-items: stretch;
      }

      /* Left group becomes stacked; buttons show one-per-row */
      .left-group {
        width: 100%;
        flex-direction: column;
        align-items: stretch;
        gap: 8px;
      }

      .records-found {
        order: 0;
        width: 100%;
      }

      .left-buttons {
        flex-direction: column;
        width: 100%;
      }

      .left-buttons .btn,
      .left-buttons .btn-export,
      .left-buttons .btn-column,
      .left-buttons .btn-overdue {
        width: 100%;
        text-align: left;
      
        padding-left: 10px;
      }

      .action-buttons {
        margin-left: 0;
        width: 100%;
        justify-content: flex-end;
        gap: 8px;
      }

      .action-buttons .btn {
        width: auto;
      }

      .table-responsive {
        max-height: 320px;
      }

      .form-row {
        flex-direction: column;
        gap: 8px;
      }

      /* stack inputs on small screens */
      .form-row .form-group {
        flex: 1;
      }

      .column-selection {
        grid-template-columns: 1fr;
      }
    }

    @media (max-width: 480px) {
      .top-bar {
        gap: 6px;
      }

      .btn {
        padding: 5px 10px;
        font-size: 12px;
      }

      table {
        min-width: 600px;
        font-size: 11px;
      }

      thead th,
      tbody td {
        padding: 4px 4px;
      }

      .paginator {
        font-size: 11px;
        width: 100%;
        justify-content: center;
        transform: translateY(-4px); /* slightly less shift on small screens */
      }

      /* ensure paginator stays centered on small screens */
      .paginator {
        width: 100%;
        justify-content: center;
      }
    }
    svg{
      height: 10px
    }
  </style>
</head>
<body>

@extends('layouts.app')

@section('content')

<div class="dashboard">
    
  <div class="container-table">
    <h3>Tasks</h3>

    @if(session('success'))
      <div class="alert alert-success" id="successAlert">
        {{ session('success') }}
        <button type="button" class="alert-close" onclick="closeAlert('successAlert')" style="float: right; background: none; border: none; font-size: 16px; cursor: pointer;">×</button>
      </div>
    @endif

    <div class="top-bar">
      <div class="left-group">
        <div class="records-found">Records Found - {{ $tasks->total() }}</div>

        <div class="left-buttons" aria-label="left action buttons">
          <a class="btn btn-export" href="{{ route('tasks.export', array_merge(request()->query(), ['page' => $tasks->currentPage()])) }}">Export</a>
          <button class="btn btn-column" id="columnBtn" type="button">Column</button>
          <button class="btn btn-overdue" id="overdueOnly" type="button">Overdue Only</button>
        </div>
      </div>

      <div class="filter-section">
        <label class="switch">
          <!-- <input type="checkbox" id="filterToggle" />
          <span class="slider"></span> -->
        </label>
      </div>

      <div class="action-buttons">
        <button class="btn btn-add" id="addTaskBtn">Add</button>
        <button class="btn btn-back" onclick="window.history.back()">Back</button>
      </div>
    </div>

    <div class="table-responsive">
      <table id="tasksTable">
        <thead>
          <tr>
       
            <th>Action</th>
            @if(in_array('task_id', $selectedColumns))<th data-column="task_id">Task ID</th>@endif
            @if(in_array('category', $selectedColumns))<th data-column="category">Category</th>@endif
            @if(in_array('description', $selectedColumns))<th data-column="description">Description</th>@endif
            @if(in_array('name', $selectedColumns))<th data-column="name">Name</th>@endif
            @if(in_array('contact_no', $selectedColumns))<th data-column="contact_no">Contact No</th>@endif
            @if(in_array('due_date', $selectedColumns))<th data-column="due_date">Due Date</th>@endif
            @if(in_array('due_time', $selectedColumns))<th data-column="due_time">Time</th>@endif
            @if(in_array('date_in', $selectedColumns))<th data-column="date_in">Date In</th>@endif
            @if(in_array('assignee', $selectedColumns))<th data-column="assignee">Assignee</th>@endif
            @if(in_array('task_status', $selectedColumns))<th data-column="task_status">Task Status</th>@endif
            @if(in_array('date_done', $selectedColumns))<th data-column="date_done">Date Done</th>@endif
            @if(in_array('repeat', $selectedColumns))<th data-column="repeat">Repeat</th>@endif
            @if(in_array('frequency', $selectedColumns))<th data-column="frequency">Frequency</th>@endif
            @if(in_array('rpt_date', $selectedColumns))<th data-column="rpt_date">Rpt Date</th>@endif
            @if(in_array('rpt_stop_date', $selectedColumns))<th data-column="rpt_stop_date">Rpt Stop Date</th>@endif
          </tr>
        </thead>
        <tbody>
          @foreach($tasks as $task)
          <tr class="{{ $task->isOverdue() ? 'overdue' : '' }}">
      
            <td class="icon-expand" onclick="editTask({{ $task->id }})">⤢</td>
            @if(in_array('task_id', $selectedColumns))<td data-column="task_id">{{ $task->task_id }}</td>@endif
            @if(in_array('category', $selectedColumns))<td data-column="category">{{ $task->category }}</td>@endif
            @if(in_array('description', $selectedColumns))<td data-column="description">{{ $task->description }}</td>@endif
            @if(in_array('name', $selectedColumns))<td data-column="name">{{ $task->name }}</td>@endif
            @if(in_array('contact_no', $selectedColumns))<td data-column="contact_no">{{ $task->contact_no }}</td>@endif
            @if(in_array('due_date', $selectedColumns))<td data-column="due_date">{{ $task->due_date? \Carbon\Carbon::parse($task->due_date)->format('d-M-y') : '' }}</td>@endif
            @if(in_array('due_time', $selectedColumns))<td data-column="due_time">{{ $task->due_time ? $task->due_time : '' }}</td>@endif
            @if(in_array('date_in', $selectedColumns))<td data-column="date_in">{{ $task->date_in ? \Carbon\Carbon::parse($task->date_in)->format('d-M-y') : '' }}</td>@endif
            @if(in_array('assignee', $selectedColumns))<td data-column="assignee">{{ $task->assignee }}</td>@endif
            @if(in_array('task_status', $selectedColumns))<td data-column="task_status">{{ $task->task_status }}</td>@endif
            @if(in_array('date_done', $selectedColumns))<td data-column="date_done">{{ $task->date_done ? \Carbon\Carbon::parse($task->date_done)->format('d-M-y') : '' }}</td>@endif
            @if(in_array('repeat', $selectedColumns))<td data-column="repeat">{{ $task->repeat ? 'Y' : 'N' }}</td>@endif
            @if(in_array('frequency', $selectedColumns))<td data-column="frequency">{{ $task->frequency }}</td>@endif
            @if(in_array('rpt_date', $selectedColumns))<td data-column="rpt_date">{{ $task->rpt_date ? \Carbon\Carbon::parse($task->rpt_date)->format('d-M-y') : '' }}</td>@endif
            @if(in_array('rpt_stop_date', $selectedColumns))<td data-column="rpt_stop_date">{{ $task->rpt_stop_date ? \Carbon\Carbon::parse($task->rpt_stop_date)->format('d-M-y') : '' }}</td>@endif
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
          $current = $tasks->currentPage();
          $last = max(1, $tasks->lastPage());
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

  <!-- Add/Edit Task Modal -->
  <div class="modal" id="taskModal">
    <div class="modal-content">
      <div class="modal-header">
        <h4 id="modalTitle">Add Task</h4>
        <button type="button" class="modal-close" onclick="closeModal()">×</button>
      </div>
      <form id="taskForm" method="POST">
        @csrf
        <div id="formMethod" style="display: none;"></div>
        
        <div class="modal-body">
          <!-- ALWAYS render inputs for add/edit so JS can set values and server validation can run.
               Column selection only affects table display, not the add/edit form. -->
          <div class="form-row">
            <div class="form-group">
              <label for="category">Category</label>
              <input type="text" class="form-control" id="category" name="category" required>
            </div>
            <div class="form-group">
              <label for="description">Description</label>
              <input type="text" class="form-control" id="description" name="description" required>
            </div>
            <div class="form-group">
              <label for="task_id_hidden" style="visibility:hidden">placeholder</label>
              <input type="hidden" id="task_id_hidden" name="task_id_hidden">
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label for="name">Name</label>
              <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="form-group">
              <label for="contact_no">Contact No</label>
              <input type="text" class="form-control" id="contact_no" name="contact_no">
            </div>
            <div class="form-group">
              <label for="assignee_small" style="visibility:hidden">placeholder</label>
              <input type="hidden" id="assignee_small" name="assignee_small">
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label for="due_date">Due Date</label>
              <input type="date" class="form-control" id="due_date" name="due_date" required>
            </div>
            <div class="form-group">
              <label for="due_time">Due Time</label>
              <input type="time" class="form-control" id="due_time" name="due_time">
            </div>
            <div class="form-group">
              <label for="date_in">Date In</label>
              <input type="date" class="form-control" id="date_in" name="date_in">
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label for="assignee">Assignee</label>
              <input type="text" class="form-control" id="assignee" name="assignee" required>
            </div>
            <div class="form-group">
              <label for="task_status">Task Status</label>
              <select class="form-control" id="task_status" name="task_status" required>
                <option value="Not Done">Not Done</option>
                <option value="In Progress">In Progress</option>
                <option value="Completed">Completed</option>
              </select>
            </div>
            <div class="form-group">
              <label for="date_done">Date Done</label>
              <input type="date" class="form-control" id="date_done" name="date_done">
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label for="task_notes">Task Notes</label>
              <input type="text" class="form-control" id="task_notes" name="task_notes">
            </div>
            <div class="form-group" style="align-items: center; display:flex; gap:8px;">
              <label style="display: flex; align-items: center; gap: 8px; margin:0;">
                <input type="checkbox" id="repeat" name="repeat" value="1">
                Repeat
              </label>
            </div>
            <div class="form-group">
              <label for="frequency">Frequency</label>
              <input type="text" class="form-control" id="frequency" name="frequency">
            </div>
          </div>

          <div style="border: 1px solid #ddd; padding: 12px; margin-bottom: 12px;">
            <h5 style="margin: 0 0 10px 0; font-size: 14px;">Repeat / Frequency</h5>
            
            <div class="form-row">
              <div class="form-group">
                <label for="rpt_date">Repeat Date</label>
                <input type="date" class="form-control" id="rpt_date" name="rpt_date">
              </div>
              <div class="form-group">
                <label for="rpt_stop_date">Repeat Stop Date</label>
                <input type="date" class="form-control" id="rpt_stop_date" name="rpt_stop_date">
              </div>
              <div class="form-group">
                <!-- empty placeholder to keep three-column layout -->
                <label style="visibility:hidden">placeholder</label>
                <input type="hidden">
              </div>
            </div>
          </div>
        </div>
        
        <div class="modal-footer">
          <button type="button" class="btn-cancel" onclick="closeModal()">Cancel</button>
          <button type="button" class="btn-delete" id="deleteBtn" style="display: none;" onclick="deleteTask()">Delete</button>
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
        <div class="column-actions">
          <button type="button" class="btn-select-all" onclick="selectAllColumns()">Select All</button>
          <button type="button" class="btn-deselect-all" onclick="deselectAllColumns()">Deselect All</button>
        </div>
        
        <form id="columnForm" action="{{ route('tasks.save-column-settings') }}" method="POST">
          @csrf
          <div class="column-selection">
            <div class="column-item">
              <input type="checkbox" class="column-checkbox" value="task_id" id="col_task_id">
              <label for="col_task_id">Task ID</label>
            </div>
            <div class="column-item">
              <input type="checkbox" class="column-checkbox" value="category" id="col_category">
              <label for="col_category">Category</label>
            </div>
            <div class="column-item">
              <input type="checkbox" class="column-checkbox" value="description" id="col_description">
              <label for="col_description">Description</label>
            </div>
            <div class="column-item">
              <input type="checkbox" class="column-checkbox" value="name" id="col_name">
              <label for="col_name">Name</label>
            </div>
            <div class="column-item">
              <input type="checkbox" class="column-checkbox" value="contact_no" id="col_contact_no">
              <label for="col_contact_no">Contact No</label>
            </div>
            <div class="column-item">
              <input type="checkbox" class="column-checkbox" value="due_date" id="col_due_date">
              <label for="col_due_date">Due Date</label>
            </div>
            <div class="column-item">
              <input type="checkbox" class="column-checkbox" value="due_time" id="col_due_time">
              <label for="col_due_time">Due Time</label>
            </div>
            <div class="column-item">
              <input type="checkbox" class="column-checkbox" value="date_in" id="col_date_in">
              <label for="col_date_in">Date In</label>
            </div>
            <div class="column-item">
              <input type="checkbox" class="column-checkbox" value="assignee" id="col_assignee">
              <label for="col_assignee">Assignee</label>
            </div>
            <div class="column-item">
              <input type="checkbox" class="column-checkbox" value="task_status" id="col_task_status">
              <label for="col_task_status">Task Status</label>
            </div>
            <div class="column-item">
              <input type="checkbox" class="column-checkbox" value="date_done" id="col_date_done">
              <label for="col_date_done">Date Done</label>
            </div>
            <div class="column-item">
              <input type="checkbox" class="column-checkbox" value="repeat" id="col_repeat">
              <label for="col_repeat">Repeat</label>
            </div>
            <div class="column-item">
              <input type="checkbox" class="column-checkbox" value="frequency" id="col_frequency">
              <label for="col_frequency">Frequency</label>
            </div>
            <div class="column-item">
              <input type="checkbox" class="column-checkbox" value="rpt_date" id="col_rpt_date">
              <label for="col_rpt_date">Rpt Date</label>
            </div>
            <div class="column-item">
              <input type="checkbox" class="column-checkbox" value="rpt_stop_date" id="col_rpt_stop_date">
              <label for="col_rpt_stop_date">Rpt Stop Date</label>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn-cancel" onclick="closeColumnModal()">Cancel</button>
        <button type="button" class="btn-save" onclick="saveColumnSettings()">Save Settings</button>
      </div>
    </div>
  </div>
</div>

  <script>
    let currentTaskId = null;
    let selectedColumns = @json($selectedColumns);

    // Initialize column checkboxes
    function initializeColumnCheckboxes() {
      const checkboxes = document.querySelectorAll('.column-checkbox');
      checkboxes.forEach(checkbox => {
        checkbox.checked = selectedColumns.includes(checkbox.value);
      });
    }

    // Add Task Button
    document.getElementById('addTaskBtn').addEventListener('click', function() {
      openModal('add');
    });

    // Column Button
    document.getElementById('columnBtn').addEventListener('click', function() {
      openColumnModal();
    });

    // Edit Task Function - guard element access so missing DOM nodes don't throw
    async function editTask(taskId) {
      try {
        const response = await fetch(`/tasks/${taskId}/get`);
        if (!response.ok) throw new Error('Network response was not ok');
        const task = await response.json();
        
        currentTaskId = taskId;
        openModal('edit');
        
        // Fill form with task data only if elements exist
        const fields = ['category','description','name','contact_no','due_date','due_time','date_in','assignee','task_status','date_done','task_notes','repeat','frequency','rpt_date','rpt_stop_date'];
        fields.forEach(id => {
          const el = document.getElementById(id);
          if (!el) return;
          if (el.type === 'checkbox') {
            el.checked = !!task[id];
          } else {
            el.value = task[id] ?? '';
          }
        });

      } catch (error) {
        console.error('Error fetching task:', error);
        alert('Error loading task data');
      }
    }

    // Open Task Modal
    function openModal(mode) {
      const modal = document.getElementById('taskModal');
      const title = document.getElementById('modalTitle');
      const form = document.getElementById('taskForm');
      const deleteBtn = document.getElementById('deleteBtn');
      const formMethod = document.getElementById('formMethod');
      
      if (mode === 'add') {
        title.textContent = 'Add Task';
        form.action = "{{ route('tasks.store') }}";
        form.method = 'POST';
        formMethod.innerHTML = '';
        deleteBtn.style.display = 'none';
        form.reset();
        currentTaskId = null;
      } else {
        title.textContent = 'Edit Task';
        form.action = `/tasks/${currentTaskId}`;
        form.method = 'POST';
        formMethod.innerHTML = '@method("PUT")';
        deleteBtn.style.display = 'block';
      }
      
      // prevent body scrollbar when modal open
      document.body.style.overflow = 'hidden';
      modal.classList.add('show');
    }

    // Close Task Modal
    function closeModal() {
      document.getElementById('taskModal').classList.remove('show');
      currentTaskId = null;
      // restore body scrollbar
      document.body.style.overflow = '';
    }

    // Open Column Modal
    function openColumnModal() {
      initializeColumnCheckboxes();
      // prevent body scrollbar when modal open
      document.body.style.overflow = 'hidden';
      document.getElementById('columnModal').classList.add('show');
    }

    // Close Column Modal
    function closeColumnModal() {
      document.getElementById('columnModal').classList.remove('show');
      // restore body scrollbar
      document.body.style.overflow = '';
    }

    // Select All Columns
    function selectAllColumns() {
      const checkboxes = document.querySelectorAll('.column-checkbox');
      checkboxes.forEach(checkbox => {
        checkbox.checked = true;
      });
    }

    // Deselect All Columns
    function deselectAllColumns() {
      const checkboxes = document.querySelectorAll('.column-checkbox');
      checkboxes.forEach(checkbox => {
        checkbox.checked = false;
      });
    }

    // Save Column Settings
    function saveColumnSettings() {
      const checkboxes = document.querySelectorAll('.column-checkbox:checked');
      const selected = Array.from(checkboxes).map(cb => cb.value);
      
      // Update the form with selected columns
      const form = document.getElementById('columnForm');
      const existingInputs = form.querySelectorAll('input[name="columns[]"]');
      existingInputs.forEach(input => input.remove());
      
      selected.forEach(column => {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'columns[]';
        input.value = column;
        form.appendChild(input);
      });
      
      form.submit();
    }

    // Delete Task
    function deleteTask() {
      if (confirm('Are you sure you want to delete this task?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/tasks/${currentTaskId}`;
        
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
    }

    // Set overdue filter if parameter exists + attach overdue button handler safely after DOM ready
    document.addEventListener('DOMContentLoaded', function() {
      // initialize column checkboxes and other startup code
      initializeColumnCheckboxes();

      // determine whether overdue param is active (supports '1' or 'true')
      const urlParams = new URLSearchParams(window.location.search);
      const overdueActive = urlParams.get('overdue') === 'true' || urlParams.get('overdue') === '1';

      // overdue button handler
      const overdueBtn = document.getElementById('overdueOnly');
      if (overdueBtn) {
        // optional visual state
        if (overdueActive) overdueBtn.classList.add('active');

        overdueBtn.addEventListener('click', function(e) {
          e.preventDefault();
          const u = new URL(window.location.href);
          const val = u.searchParams.get('overdue');
          if (val === 'true' || val === '1') {
            u.searchParams.delete('overdue');
          } else {
            u.searchParams.set('overdue', '1');
          }
          // navigate keeping other params intact
          window.location.href = u.toString();
        });
      }

      // keep compatibility for a (commented) filterToggle input if later enabled
      const filterToggle = document.getElementById('filterToggle');
      if (filterToggle) {
        filterToggle.checked = overdueActive;
      }
    });
  </script>
</body>
</html>

@endsection