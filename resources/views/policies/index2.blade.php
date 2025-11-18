<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Policies Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            color: #000;
            margin: 0;
            background: #fff;
        }

        .container-table {
            max-width: 100%;
            margin: 0;
            padding: 10px;
        }

        h3 {
            background: #f1f1f1;
            padding: 6px;
            margin-bottom: 10px;
            font-weight: bold;
            border: 1px solid #ddd;
            font-size: 19px;
            line-height: 1.2;
        }

        .top-bar {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 10px;
        }

        .records-found {
            flex: 1 1 auto;
            font-size: 14px;
            color: #555;
            min-width: 150px;
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
            text-decoration: none;
            display: inline-block;
            text-align: center;
        }

        .btn-add {
            background-color: #df7900;
            color: white;
        }

        .btn-add:hover {
            background-color: #b46500;
        }

        .btn-dfr {
            background-color: black;
            color: white;
        }

        .btn-dfr:hover {
            background-color: #222;
        }

        .btn-export {
            background: white;
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
            max-height: 600px;
            overflow-y: auto;
            background: #fff;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
            min-width: 1200px;
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

        tbody tr.dfr-row {
            background-color: #fff3cd !important;
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

        .btn-action {
            padding: 2px 6px;
            font-size: 11px;
            margin: 1px;
            border: 1px solid #ddd;
            background: white;
            cursor: pointer;
            border-radius: 2px;
            text-decoration: none;
            display: inline-block;
        }

        .btn-edit {
            color: #0d6efd;
            border-color: #0d6efd;
        }

        .btn-edit:hover {
            background: #0d6efd;
            color: white;
        }

        .btn-delete {
            color: #dc3545;
            border-color: #dc3545;
        }

        .btn-delete:hover {
            background: #dc3545;
            color: white;
        }

        .footer {
            display: flex;
            align-items: center;
            padding: 5px 0;
            gap: 10px;
            border-top: 1px solid #ccc;
            flex-wrap: wrap;
            margin-top: 15px;
        }

        .paginator {
            margin-left: auto;
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 12px;
            color: #555;
            flex-wrap: nowrap;
            white-space: nowrap;
        }

        .btn-page {
            background: white;
            border: 1px solid #ccc;
            font-size: 14px;
            width: 28px;
            height: 28px;
            padding: 0;
            cursor: pointer;
        }

        .btn-page:disabled {
            cursor: not-allowed;
            opacity: 0.5;
        }

        /* Modal Styles */
        .modal-header {
            background-color: #f8f9fa;
            border-bottom: 1px solid #dee2e6;
            padding: 10px 15px;
        }

        .modal-title {
            font-weight: 600;
            font-size: 16px;
        }

        .form-control, .form-select {
            font-size: 13px;
        }

        .form-label {
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .badge-status {
            font-size: 11px;
            padding: 4px 8px;
        }

        @media (max-width: 768px) {
            .top-bar {
                flex-direction: column;
                align-items: flex-start;
            }

            .action-buttons {
                margin-left: 0;
                width: 100%;
            }

            .table-responsive {
                max-height: 500px;
            }
        }
    </style>
</head>
<body>
    @extends('layouts.app')

@section('content')

<div class="dashboard">
    <div class="container-table">
        <h3>Policies</h3>

        <div class="top-bar">
            <div class="records-found">Records Found - {{ $policies->count() }}</div>

            <div class="action-buttons">
                <button class="btn btn-dfr" id="dfrOnlyBtn">Due For Renewal</button>
                <button class="btn btn-add" id="addPolicyBtn">Add</button>
                <button class="btn btn-back" onclick="window.history.back()">Back</button>
            </div>
        </div>

        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <!-- <th>Action</th> -->
                        <th>Policy No</th>
                        <th>Client Name</th>
                        <th>Insurer</th>
                        <th>Policy Class</th>
                        <th>Policy Plan</th>
                        <th>Sum Insured</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Insured</th>
                        <th>Policy Status</th>
                        <th>Date Registered</th>
                        <th>PolicyID</th>
                        <th>Insured Item</th>
                        <th>Renewable</th>
                        <th>Biz Type</th>
                        <th>Term</th>
                        <th>Term Unit</th>
                        <th>Base Premium</th>
                        <th>Premium</th>
                        <th>Frequency</th>
                        <th>Pay Plan</th>
                        <th>Agency</th>
                        <th>Agent</th>
                        <th>Notes</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($policies as $policy)
                    <tr class="{{ $policy->policy_status == 'DFR' ? 'dfr-row' : '' }}">
                        <!-- <td class="icon-expand" onclick="showPolicyDetails({{ $policy->id }})">⤢</td> -->
                        <td>{{ $policy->policy_no }}</td>
                        <td>{{ $policy->client_name }}</td>
                        <td>{{ $policy->insurer }}</td>
                        <td>{{ $policy->policy_class }}</td>
                        <td>{{ $policy->policy_plan }}</td>
                        <td>{{ $policy->sum_insured ? number_format($policy->sum_insured, 2) : '###########' }}</td>
                        <td>{{ $policy->start_date ? $policy->start_date->format('d-M-y') : '###########' }}</td>
                        <td>{{ $policy->end_date ? $policy->end_date->format('d-M-y') : '###########' }}</td>
                        <td>{{ $policy->insured ?? '###########' }}</td>
                        <td>
                            <span class="badge badge-status bg-{{ $policy->policy_status == 'In Force' ? 'success' : ($policy->policy_status == 'DFR' ? 'warning' : ($policy->policy_status == 'Expired' ? 'secondary' : 'danger')) }}">
                                {{ $policy->policy_status }}
                            </span>
                        </td>
                        <td>{{ $policy->date_registered ? $policy->date_registered->format('d-M-y') : '###########' }}</td>
                        <td>{{ $policy->policy_id }}</td>
                        <td>{{ $policy->insured_item ?? '-' }}</td>
                        <td>{{ $policy->renewable }}</td>
                        <td>{{ $policy->biz_type }}</td>
                        <td>{{ $policy->term }}</td>
                        <td>{{ $policy->term_unit }}</td>
                        <td>{{ number_format($policy->base_premium, 2) }}</td>
                        <td>{{ number_format($policy->premium, 2) }}</td>
                        <td>{{ $policy->frequency }}</td>
                        <td>{{ $policy->pay_plan }}</td>
                        <td>{{ $policy->agency ?? '-' }}</td>
                        <td>{{ $policy->agent ?? '-' }}</td>
                        <td>{{ $policy->notes ?? '-' }}</td>
                        <td>
                            <button class="btn-action btn-edit" onclick="editPolicy({{ $policy->id }})">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn-action btn-delete" onclick="confirmDelete({{ $policy->id }})">
                                <i class="fas fa-trash"></i>
                            </button>
                            <form id="delete-form-{{ $policy->id }}" 
                                  action="{{ route('policies.destroy', $policy) }}" 
                                  method="POST" class="d-none">
                                @csrf
                                @method('DELETE')
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="footer">
            <button class="btn btn-export" onclick="exportPolicies()">Export</button>
            <button class="btn btn-column" id="columnSettingsBtn">Column</button>

            <div class="paginator">
                <button class="btn-page" disabled>&lt;&lt;</button>
                <button class="btn-page" disabled>&lt;</button>
                <span>Page 1 of 1</span>
                <button class="btn-page" disabled>&gt;</button>
                <button class="btn-page" disabled>&gt;&gt;</button>
            </div>
        </div>
    </div>

    <!-- Add Policy Modal -->
    <div class="modal fade" id="addPolicyModal" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Policy</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="addPolicyForm" action="{{ route('policies.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="policy_no" class="form-label">Policy No *</label>
                                    <input type="text" class="form-control" id="policy_no" name="policy_no" required>
                                </div>
                                <div class="mb-3">
                                    <label for="client_name" class="form-label">Client Name *</label>
                                    <input type="text" class="form-control" id="client_name" name="client_name" required>
                                </div>
                                <div class="mb-3">
                                    <label for="insurer" class="form-label">Insurer *</label>
                                    <select class="form-select" id="insurer" name="insurer" required>
                                        <option value="">Select Insurer</option>
                                        @foreach($lookupData['insurers'] as $insurer)
                                            <option value="{{ $insurer }}">{{ $insurer }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="policy_class" class="form-label">Policy Class *</label>
                                    <select class="form-select" id="policy_class" name="policy_class" required>
                                        <option value="">Select Policy Class</option>
                                        @foreach($lookupData['policy_classes'] as $class)
                                            <option value="{{ $class }}">{{ $class }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="policy_plan" class="form-label">Policy Plan *</label>
                                    <select class="form-select" id="policy_plan" name="policy_plan" required>
                                        <option value="">Select Policy Plan</option>
                                        @foreach($lookupData['policy_plans'] as $plan)
                                            <option value="{{ $plan }}">{{ $plan }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="sum_insured" class="form-label">Sum Insured</label>
                                    <input type="number" step="0.01" class="form-control" id="sum_insured" name="sum_insured">
                                </div>
                                <div class="mb-3">
                                    <label for="start_date" class="form-label">Start Date *</label>
                                    <input type="date" class="form-control" id="start_date" name="start_date" required>
                                </div>
                                <div class="mb-3">
                                    <label for="end_date" class="form-label">End Date *</label>
                                    <input type="date" class="form-control" id="end_date" name="end_date" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="insured" class="form-label">Insured</label>
                                    <input type="text" class="form-control" id="insured" name="insured">
                                </div>
                                <div class="mb-3">
                                    <label for="policy_status" class="form-label">Policy Status *</label>
                                    <select class="form-select" id="policy_status" name="policy_status" required>
                                        <option value="">Select Status</option>
                                        @foreach($lookupData['policy_statuses'] as $status)
                                            <option value="{{ $status }}">{{ $status }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="date_registered" class="form-label">Date Registered *</label>
                                    <input type="date" class="form-control" id="date_registered" name="date_registered" required>
                                </div>
                                <div class="mb-3">
                                    <label for="policy_id" class="form-label">Policy ID *</label>
                                    <input type="text" class="form-control" id="policy_id" name="policy_id" required>
                                </div>
                                <div class="mb-3">
                                    <label for="insured_item" class="form-label">Insured Item</label>
                                    <input type="text" class="form-control" id="insured_item" name="insured_item">
                                </div>
                                <div class="mb-3">
                                    <label for="renewable" class="form-label">Renewable *</label>
                                    <select class="form-select" id="renewable" name="renewable" required>
                                        <option value="">Select Option</option>
                                        @foreach($lookupData['renewable_options'] as $option)
                                            <option value="{{ $option }}">{{ $option }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="biz_type" class="form-label">Business Type *</label>
                                    <select class="form-select" id="biz_type" name="biz_type" required>
                                        <option value="">Select Business Type</option>
                                        @foreach($lookupData['biz_types'] as $type)
                                            <option value="{{ $type }}">{{ $type }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="term" class="form-label">Term *</label>
                                            <input type="number" class="form-control" id="term" name="term" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="term_unit" class="form-label">Term Unit *</label>
                                            <select class="form-select" id="term_unit" name="term_unit" required>
                                                <option value="">Select Unit</option>
                                                @foreach($lookupData['term_units'] as $unit)
                                                    <option value="{{ $unit }}">{{ $unit }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="base_premium" class="form-label">Base Premium *</label>
                                    <input type="number" step="0.01" class="form-control" id="base_premium" name="base_premium" required>
                                </div>
                                <div class="mb-3">
                                    <label for="premium" class="form-label">Premium *</label>
                                    <input type="number" step="0.01" class="form-control" id="premium" name="premium" required>
                                </div>
                                <div class="mb-3">
                                    <label for="frequency" class="form-label">Frequency *</label>
                                    <select class="form-select" id="frequency" name="frequency" required>
                                        <option value="">Select Frequency</option>
                                        @foreach($lookupData['frequencies'] as $freq)
                                            <option value="{{ $freq }}">{{ $freq }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="pay_plan" class="form-label">Pay Plan *</label>
                                    <select class="form-select" id="pay_plan" name="pay_plan" required>
                                        <option value="">Select Pay Plan</option>
                                        @foreach($lookupData['pay_plans'] as $plan)
                                            <option value="{{ $plan }}">{{ $plan }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="agency" class="form-label">Agency</label>
                                    <input type="text" class="form-control" id="agency" name="agency">
                                </div>
                                <div class="mb-3">
                                    <label for="agent" class="form-label">Agent</label>
                                    <input type="text" class="form-control" id="agent" name="agent">
                                </div>
                                <div class="mb-3">
                                    <label for="notes" class="form-label">Notes</label>
                                    <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Policy</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Policy Modal -->
    <div class="modal fade" id="editPolicyModal" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Policy</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="editPolicyForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body" id="editPolicyModalBody">
                        <!-- Content will be loaded via AJAX -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update Policy</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Policy Details Modal -->
    <div class="modal fade" id="policyDetailsModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Policy Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="policyDetailsBody">
                    <!-- Content will be loaded via AJAX -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="editFromDetailsBtn">Edit Policy</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Column Settings Modal -->
    <div class="modal fade" id="columnSettingsModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Column Select & Sort</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="columnSettingsForm" action="{{ route('policies.save-column-settings') }}" method="POST">
                        @csrf
                        <div class="row">
                            @php
                                $allColumns = [
                                    'Policy No', 'Client Name', 'Insurer', 'Policy Class', 'Policy Plan',
                                    'Sum Insured', 'Start Date', 'End Date', 'Insured', 'Policy Status',
                                    'Date Registered', 'PolicyID', 'Insured Item', 'Renewable', 'Biz Type',
                                    'Term', 'Term Unit', 'Base Premium', 'Premium', 'Frequency', 'Pay Plan',
                                    'Agency', 'Agent', 'Notes'
                                ];
                            @endphp
                            @foreach($allColumns as $index => $column)
                            <div class="col-md-6 mb-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="columns[]" 
                                           value="{{ $column }}" id="column{{ $index }}" checked>
                                    <label class="form-check-label" for="column{{ $index }}">
                                        {{ $index + 1 }}. {{ $column }}
                                    </label>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="saveColumnSettings()">Save Settings</button>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" style="position: fixed; top: 20px; right: 20px; z-index: 1000;">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let currentPolicyId = null;

        document.addEventListener('DOMContentLoaded', function() {
            // Column Settings Modal
            const columnSettingsBtn = document.getElementById('columnSettingsBtn');
            if (columnSettingsBtn) {
                columnSettingsBtn.addEventListener('click', function() {
                    const modal = new bootstrap.Modal(document.getElementById('columnSettingsModal'));
                    modal.show();
                });
            }

            // Add Policy Button
            const addPolicyBtn = document.getElementById('addPolicyBtn');
            if (addPolicyBtn) {
                addPolicyBtn.addEventListener('click', function() {
                    const modal = new bootstrap.Modal(document.getElementById('addPolicyModal'));
                    modal.show();
                });
            }

            // DFR Only Filter
            const dfrOnlyBtn = document.getElementById('dfrOnlyBtn');
            if (dfrOnlyBtn) {
                let showDfrOnly = false;
                
                dfrOnlyBtn.addEventListener('click', function() {
                    showDfrOnly = !showDfrOnly;
                    const rows = document.querySelectorAll('tbody tr');
                    
                    rows.forEach(row => {
                        if (showDfrOnly) {
                            if (row.classList.contains('dfr-row')) {
                                row.style.display = '';
                            } else {
                                row.style.display = 'none';
                            }
                            dfrOnlyBtn.style.backgroundColor = '#dc3545';
                            dfrOnlyBtn.textContent = 'Show All';
                        } else {
                            row.style.display = '';
                            dfrOnlyBtn.style.backgroundColor = 'black';
                            dfrOnlyBtn.textContent = 'Due For Renewal';
                        }
                    });
                });
            }

            // Edit from details button handler
            document.getElementById('editFromDetailsBtn')?.addEventListener('click', function() {
                if (currentPolicyId) {
                    const detailsModal = bootstrap.Modal.getInstance(document.getElementById('policyDetailsModal'));
                    detailsModal.hide();
                    editPolicy(currentPolicyId);
                }
            });

            // Auto-dismiss alerts
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    if (alert && alert.classList.contains('show')) {
                        const bsAlert = new bootstrap.Alert(alert);
                        bsAlert.close();
                    }
                }, 5000);
            });
        });

        function showPolicyDetails(policyId) {
            currentPolicyId = policyId;
            
            // Show loading state
            document.getElementById('policyDetailsBody').innerHTML = `
                <div class="text-center">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2">Loading policy details...</p>
                </div>
            `;
            
            const modal = new bootstrap.Modal(document.getElementById('policyDetailsModal'));
            modal.show();

            // Simulate API call - in real scenario, you'd fetch from server
            setTimeout(() => {
                const policy = getPolicyById(policyId);
                if (policy) {
                    document.getElementById('policyDetailsBody').innerHTML = generatePolicyDetailsHTML(policy);
                } else {
                    document.getElementById('policyDetailsBody').innerHTML = `
                        <div class="alert alert-danger">
                            Error loading policy details
                        </div>
                    `;
                }
            }, 500);
        }

        function getPolicyById(policyId) {
            // This is a mock function - in real scenario, you'd fetch from server
            const policies = {!! $policies->toJson() !!};
            return policies.find(p => p.id === policyId);
        }

        function generatePolicyDetailsHTML(policy) {
            return `
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-bordered table-sm">
                            <tr>
                                <th width="40%" class="bg-light">Policy No:</th>
                                <td>${policy.policy_no}</td>
                            </tr>
                            <tr>
                                <th class="bg-light">Client Name:</th>
                                <td>${policy.client_name}</td>
                            </tr>
                            <tr>
                                <th class="bg-light">Insurer:</th>
                                <td>${policy.insurer}</td>
                            </tr>
                            <tr>
                                <th class="bg-light">Policy Class:</th>
                                <td>${policy.policy_class}</td>
                            </tr>
                            <tr>
                                <th class="bg-light">Policy Plan:</th>
                                <td>${policy.policy_plan}</td>
                            </tr>
                            <tr>
                                <th class="bg-light">Sum Insured:</th>
                                <td>${policy.sum_insured ? new Intl.NumberFormat().format(policy.sum_insured) : 'N/A'}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-bordered table-sm">
                            <tr>
                                <th width="40%" class="bg-light">Start Date:</th>
                                <td>${new Date(policy.start_date).toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' })}</td>
                            </tr>
                            <tr>
                                <th class="bg-light">End Date:</th>
                                <td>${new Date(policy.end_date).toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' })}</td>
                            </tr>
                            <tr>
                                <th class="bg-light">Policy Status:</th>
                                <td>
                                    <span class="badge bg-${policy.policy_status == 'In Force' ? 'success' : (policy.policy_status == 'DFR' ? 'warning' : (policy.policy_status == 'Expired' ? 'secondary' : 'danger'))}">
                                        ${policy.policy_status}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th class="bg-light">Date Registered:</th>
                                <td>${new Date(policy.date_registered).toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' })}</td>
                            </tr>
                            <tr>
                                <th class="bg-light">Policy ID:</th>
                                <td>${policy.policy_id}</td>
                            </tr>
                            <tr>
                                <th class="bg-light">Renewable:</th>
                                <td>${policy.renewable}</td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-6">
                        <table class="table table-bordered table-sm">
                            <tr>
                                <th width="40%" class="bg-light">Business Type:</th>
                                <td>${policy.biz_type}</td>
                            </tr>
                            <tr>
                                <th class="bg-light">Term:</th>
                                <td>${policy.term} ${policy.term_unit}</td>
                            </tr>
                            <tr>
                                <th class="bg-light">Base Premium:</th>
                                <td>${new Intl.NumberFormat().format(policy.base_premium)}</td>
                            </tr>
                            <tr>
                                <th class="bg-light">Premium:</th>
                                <td>${new Intl.NumberFormat().format(policy.premium)}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-bordered table-sm">
                            <tr>
                                <th width="40%" class="bg-light">Frequency:</th>
                                <td>${policy.frequency}</td>
                            </tr>
                            <tr>
                                <th class="bg-light">Pay Plan:</th>
                                <td>${policy.pay_plan}</td>
                            </tr>
                            <tr>
                                <th class="bg-light">Agency:</th>
                                <td>${policy.agency || 'N/A'}</td>
                            </tr>
                            <tr>
                                <th class="bg-light">Agent:</th>
                                <td>${policy.agent || 'N/A'}</td>
                            </tr>
                        </table>
                    </div>
                </div>
                ${policy.notes ? `
                <div class="row mt-3">
                    <div class="col-12">
                        <table class="table table-bordered table-sm">
                            <tr>
                                <th width="20%" class="bg-light">Notes:</th>
                                <td>${policy.notes}</td>
                            </tr>
                        </table>
                    </div>
                </div>
                ` : ''}
            `;
        }

        function editPolicy(policyId) {
            currentPolicyId = policyId;
            
            // Show loading state
            document.getElementById('editPolicyModalBody').innerHTML = `
                <div class="text-center">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2">Loading policy data...</p>
                </div>
            `;
            
            const modal = new bootstrap.Modal(document.getElementById('editPolicyModal'));
            modal.show();

            // Simulate API call - in real scenario, you'd fetch from server
            setTimeout(() => {
                const policy = getPolicyById(policyId);
                if (policy) {
                    document.getElementById('editPolicyModalBody').innerHTML = generateEditFormHTML(policy);
                    document.getElementById('editPolicyForm').action = `/policies/${policyId}`;
                } else {
                    document.getElementById('editPolicyModalBody').innerHTML = `
                        <div class="alert alert-danger">
                            Error loading policy data
                        </div>
                    `;
                }
            }, 500);
        }

        function generateEditFormHTML(policy) {
            const lookupData = {!! json_encode($lookupData) !!};
            
            return `
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="edit_policy_no" class="form-label">Policy No *</label>
                            <input type="text" class="form-control" id="edit_policy_no" name="policy_no" value="${policy.policy_no}" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_client_name" class="form-label">Client Name *</label>
                            <input type="text" class="form-control" id="edit_client_name" name="client_name" value="${policy.client_name}" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_insurer" class="form-label">Insurer *</label>
                            <select class="form-select" id="edit_insurer" name="insurer" required>
                                <option value="">Select Insurer</option>
                                ${lookupData.insurers.map(insurer => `
                                    <option value="${insurer}" ${policy.insurer === insurer ? 'selected' : ''}>${insurer}</option>
                                `).join('')}
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="edit_policy_class" class="form-label">Policy Class *</label>
                            <select class="form-select" id="edit_policy_class" name="policy_class" required>
                                <option value="">Select Policy Class</option>
                                ${lookupData.policy_classes.map(cls => `
                                    <option value="${cls}" ${policy.policy_class === cls ? 'selected' : ''}>${cls}</option>
                                `).join('')}
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="edit_policy_plan" class="form-label">Policy Plan *</label>
                            <select class="form-select" id="edit_policy_plan" name="policy_plan" required>
                                <option value="">Select Policy Plan</option>
                                ${lookupData.policy_plans.map(plan => `
                                    <option value="${plan}" ${policy.policy_plan === plan ? 'selected' : ''}>${plan}</option>
                                `).join('')}
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="edit_sum_insured" class="form-label">Sum Insured</label>
                            <input type="number" step="0.01" class="form-control" id="edit_sum_insured" name="sum_insured" value="${policy.sum_insured || ''}">
                        </div>
                        <div class="mb-3">
                            <label for="edit_start_date" class="form-label">Start Date *</label>
                            <input type="date" class="form-control" id="edit_start_date" name="start_date" value="${policy.start_date.split(' ')[0]}" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_end_date" class="form-label">End Date *</label>
                            <input type="date" class="form-control" id="edit_end_date" name="end_date" value="${policy.end_date.split(' ')[0]}" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="edit_insured" class="form-label">Insured</label>
                            <input type="text" class="form-control" id="edit_insured" name="insured" value="${policy.insured || ''}">
                        </div>
                        <div class="mb-3">
                            <label for="edit_policy_status" class="form-label">Policy Status *</label>
                            <select class="form-select" id="edit_policy_status" name="policy_status" required>
                                <option value="">Select Status</option>
                                ${lookupData.policy_statuses.map(status => `
                                    <option value="${status}" ${policy.policy_status === status ? 'selected' : ''}>${status}</option>
                                `).join('')}
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="edit_date_registered" class="form-label">Date Registered *</label>
                            <input type="date" class="form-control" id="edit_date_registered" name="date_registered" value="${policy.date_registered.split(' ')[0]}" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_policy_id" class="form-label">Policy ID *</label>
                            <input type="text" class="form-control" id="edit_policy_id" name="policy_id" value="${policy.policy_id}" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_insured_item" class="form-label">Insured Item</label>
                            <input type="text" class="form-control" id="edit_insured_item" name="insured_item" value="${policy.insured_item || ''}">
                        </div>
                        <div class="mb-3">
                            <label for="edit_renewable" class="form-label">Renewable *</label>
                            <select class="form-select" id="edit_renewable" name="renewable" required>
                                <option value="">Select Option</option>
                                ${lookupData.renewable_options.map(option => `
                                    <option value="${option}" ${policy.renewable === option ? 'selected' : ''}>${option}</option>
                                `).join('')}
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="edit_biz_type" class="form-label">Business Type *</label>
                            <select class="form-select" id="edit_biz_type" name="biz_type" required>
                                <option value="">Select Business Type</option>
                                ${lookupData.biz_types.map(type => `
                                    <option value="${type}" ${policy.biz_type === type ? 'selected' : ''}>${type}</option>
                                `).join('')}
                            </select>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_term" class="form-label">Term *</label>
                                    <input type="number" class="form-control" id="edit_term" name="term" value="${policy.term}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_term_unit" class="form-label">Term Unit *</label>
                                    <select class="form-select" id="edit_term_unit" name="term_unit" required>
                                        <option value="">Select Unit</option>
                                        ${lookupData.term_units.map(unit => `
                                            <option value="${unit}" ${policy.term_unit === unit ? 'selected' : ''}>${unit}</option>
                                        `).join('')}
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="edit_base_premium" class="form-label">Base Premium *</label>
                            <input type="number" step="0.01" class="form-control" id="edit_base_premium" name="base_premium" value="${policy.base_premium}" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_premium" class="form-label">Premium *</label>
                            <input type="number" step="0.01" class="form-control" id="edit_premium" name="premium" value="${policy.premium}" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_frequency" class="form-label">Frequency *</label>
                            <select class="form-select" id="edit_frequency" name="frequency" required>
                                <option value="">Select Frequency</option>
                                ${lookupData.frequencies.map(freq => `
                                    <option value="${freq}" ${policy.frequency === freq ? 'selected' : ''}>${freq}</option>
                                `).join('')}
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="edit_pay_plan" class="form-label">Pay Plan *</label>
                            <select class="form-select" id="edit_pay_plan" name="pay_plan" required>
                                <option value="">Select Pay Plan</option>
                                ${lookupData.pay_plans.map(plan => `
                                    <option value="${plan}" ${policy.pay_plan === plan ? 'selected' : ''}>${plan}</option>
                                `).join('')}
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="edit_agency" class="form-label">Agency</label>
                            <input type="text" class="form-control" id="edit_agency" name="agency" value="${policy.agency || ''}">
                        </div>
                        <div class="mb-3">
                            <label for="edit_agent" class="form-label">Agent</label>
                            <input type="text" class="form-control" id="edit_agent" name="agent" value="${policy.agent || ''}">
                        </div>
                        <div class="mb-3">
                            <label for="edit_notes" class="form-label">Notes</label>
                            <textarea class="form-control" id="edit_notes" name="notes" rows="3">${policy.notes || ''}</textarea>
                        </div>
                    </div>
                </div>
            `;
        }

        function confirmDelete(policyId) {
            if (confirm('Are you sure you want to delete this policy?')) {
                document.getElementById('delete-form-' + policyId).submit();
            }
        }

        function exportPolicies() {
            window.location.href = '{{ route("policies.export") }}';
        }

        function saveColumnSettings() {
            document.getElementById('columnSettingsForm').submit();
        }

        // Form validation
        document.getElementById('addPolicyForm')?.addEventListener('submit', function(e) {
            if (!validatePolicyForm(this)) {
                e.preventDefault();
            }
        });

        document.getElementById('editPolicyForm')?.addEventListener('submit', function(e) {
            if (!validatePolicyForm(this)) {
                e.preventDefault();
            }
        });

        function validatePolicyForm(form) {
            const requiredFields = form.querySelectorAll('[required]');
            let isValid = true;

            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    field.style.borderColor = 'red';
                } else {
                    field.style.borderColor = '';
                }
            });

            if (!isValid) {
                alert('Please fill all required fields');
            }

            return isValid;
        }
    </script>
</body>
</html>
@endsection