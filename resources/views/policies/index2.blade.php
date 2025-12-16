<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Policies Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
</head>
<body>
    @extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/policies-index2.css') }}">


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
    
</body>
</html>
<script>
  // Initialize data from Blade
  const policiesExportRoute = '{{ route("policies.export") }}';
</script>
<script src="{{ asset('js/policies-index2.js') }}"></script>
@endsection