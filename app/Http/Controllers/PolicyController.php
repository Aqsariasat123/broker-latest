<?php

namespace App\Http\Controllers;

use App\Models\Policy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PolicyController extends Controller
{
    public function index(Request $request)
    {
        $query = Policy::query();
        
        // Filter for Due for Renewal
        if ($request->has('dfr') && $request->dfr == 'true') {
            $query->where('policy_status', 'DFR');
        }

        $policies = $query->orderBy('date_registered', 'desc')->paginate(10); // <-- paginate here
        
        // Get lookup data for dropdowns
        $lookupData = $this->getLookupData();
        
        return view('policies.index', compact('policies', 'lookupData'));
    }

    public function create()
    {
        $lookupData = $this->getLookupData();
        return view('policies.create', compact('lookupData'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'policy_no' => 'required|string|max:255',
            'client_name' => 'required|string|max:255',
            'insurer' => 'required|string|max:255',
            'policy_class' => 'required|string|max:255',
            'policy_plan' => 'required|string|max:255',
            'sum_insured' => 'nullable|numeric',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'insured' => 'nullable|string|max:255',
            'policy_status' => 'required|string|max:50',
            'date_registered' => 'required|date',
            'policy_id' => 'required|string|max:255|unique:policies',
            'insured_item' => 'nullable|string|max:255',
            'renewable' => 'required|string|max:3',
            'biz_type' => 'required|string|max:255',
            'term' => 'required|integer',
            'term_unit' => 'required|string|max:50',
            'base_premium' => 'required|numeric',
            'premium' => 'required|numeric',
            'frequency' => 'required|string|max:50',
            'pay_plan' => 'required|string|max:50',
            'agency' => 'nullable|string|max:255',
            'agent' => 'nullable|string|max:255',
            'notes' => 'nullable|string'
        ]);

        Policy::create($validated);

        return redirect()->route('policies.index')
            ->with('success', 'Policy created successfully.');
    }

    public function show(Request $request, Policy $policy)
    {
        $policy->load([
            'client',
            'schedules.paymentPlans.debitNotes.payments',
        ]);

        $coverage = [
            'sum_insured' => $policy->sum_insured,
            'base_premium' => $policy->base_premium,
            'premium' => $policy->premium,
            'start_date' => $policy->start_date,
            'end_date' => $policy->end_date,
        ];

        $paymentHistory = $policy->schedules
            ->flatMap(function ($schedule) {
                return $schedule->paymentPlans->map(function ($plan) use ($schedule) {
                    return [
                        'schedule_no' => $schedule->schedule_no,
                        'installment_label' => $plan->installment_label,
                        'due_date' => optional($plan->due_date)->toDateString(),
                        'amount' => $plan->amount,
                        'status' => $plan->status,
                        'payments' => $plan->debitNotes->flatMap(function ($note) {
                            return $note->payments->map(function ($payment) use ($note) {
                                return [
                                    'debit_note_no' => $note->debit_note_no,
                                    'payment_reference' => $payment->payment_reference,
                                    'paid_on' => optional($payment->paid_on)->toDateString(),
                                    'amount' => $payment->amount,
                                ];
                            });
                        })->values(),
                    ];
                });
            })
            ->values();

        if ($request->expectsJson()) {
            return response()->json([
                'policy' => $policy,
                'coverage' => $coverage,
                'payment_history' => $paymentHistory,
            ]);
        }

        return view('policies.show', [
            'policy' => $policy,
            'coverage' => $coverage,
            'paymentHistory' => $paymentHistory,
        ]);
    }

    public function edit(Policy $policy)
    {
        if (request()->expectsJson()) {
            return response()->json($policy);
        }
        // fallback for non-AJAX
        $lookupData = $this->getLookupData();
        return view('policies.edit', compact('policy', 'lookupData'));
    }

    public function update(Request $request, Policy $policy)
    {
        $validated = $request->validate([
            'policy_no' => 'required|string|max:255',
            'client_name' => 'required|string|max:255',
            'insurer' => 'required|string|max:255',
            'policy_class' => 'required|string|max:255',
            'policy_plan' => 'required|string|max:255',
            'sum_insured' => 'nullable|numeric',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'insured' => 'nullable|string|max:255',
            'policy_status' => 'required|string|max:50',
            'date_registered' => 'required|date',
            'policy_id' => 'required|string|max:255|unique:policies,policy_id,' . $policy->id,
            'insured_item' => 'nullable|string|max:255',
            'renewable' => 'required|string|max:3',
            'biz_type' => 'required|string|max:255',
            'term' => 'required|integer',
            'term_unit' => 'required|string|max:50',
            'base_premium' => 'required|numeric',
            'premium' => 'required|numeric',
            'frequency' => 'required|string|max:50',
            'pay_plan' => 'required|string|max:50',
            'agency' => 'nullable|string|max:255',
            'agent' => 'nullable|string|max:255',
            'notes' => 'nullable|string'
        ]);

        $policy->update($validated);

        return redirect()->route('policies.index')
            ->with('success', 'Policy updated successfully.');
    }

    public function destroy(Policy $policy)
    {
        $policy->delete();

        return redirect()->route('policies.index')
            ->with('success', 'Policy deleted successfully.');
    }

    public function export(Request $request)
    {
        $policies = Policy::all();
        
        $fileName = 'policies_export_' . date('Y-m-d') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ];

        $handle = fopen('php://output', 'w');
        fputcsv($handle, [
            'Policy No', 'Client Name', 'Insurer', 'Policy Class', 'Policy Plan',
            'Sum Insured', 'Start Date', 'End Date', 'Insured', 'Policy Status',
            'Date Registered', 'Policy ID', 'Insured Item', 'Renewable', 'Biz Type',
            'Term', 'Term Unit', 'Base Premium', 'Premium', 'Frequency', 'Pay Plan',
            'Agency', 'Agent', 'Notes'
        ]);

        foreach ($policies as $policy) {
            fputcsv($handle, [
                $policy->policy_no,
                $policy->client_name,
                $policy->insurer,
                $policy->policy_class,
                $policy->policy_plan,
                $policy->sum_insured,
                $policy->start_date?->format('d-M-y'),
                $policy->end_date?->format('d-M-y'),
                $policy->insured,
                $policy->policy_status,
                $policy->date_registered?->format('d-M-y'),
                $policy->policy_id,
                $policy->insured_item,
                $policy->renewable,
                $policy->biz_type,
                $policy->term,
                $policy->term_unit,
                $policy->base_premium,
                $policy->premium,
                $policy->frequency,
                $policy->pay_plan,
                $policy->agency,
                $policy->agent,
                $policy->notes
            ]);
        }

        fclose($handle);
        return response()->streamDownload(function() use ($handle) {
            //
        }, $fileName, $headers);
    }

    public function saveColumnSettings(Request $request)
    {
        // Save column settings to session or database
        session(['policy_columns' => $request->columns ?? []]);
        
        return redirect()->route('policies.index')
            ->with('success', 'Column settings saved successfully.');
    }

    private function getLookupData()
    {
        return [
            'insurers' => DB::table('lookup_values')
                ->where('lookup_category_id', function($query) {
                    $query->select('id')
                        ->from('lookup_categories')
                        ->where('name', 'Insurers');
                })
                ->where('active', true)
                ->pluck('name')
                ->toArray(),

            'policy_classes' => DB::table('lookup_values')
                ->where('lookup_category_id', function($query) {
                    $query->select('id')
                        ->from('lookup_categories')
                        ->where('name', 'Policy Classes');
                })
                ->where('active', true)
                ->pluck('name')
                ->toArray(),

            'policy_plans' => DB::table('lookup_values')
                ->where('lookup_category_id', function($query) {
                    $query->select('id')
                        ->from('lookup_categories')
                        ->where('name', 'Policy Plans');
                })
                ->where('active', true)
                ->pluck('name')
                ->toArray(),

            'policy_statuses' => ['In Force', 'DFR', 'Expired', 'Cancelled'],
            'renewable_options' => ['Yes', 'No'],
            'biz_types' => ['Direct', 'Transfer'],
            'term_units' => ['Year', 'Month', 'Days'],
            'frequencies' => ['Annually', 'Monthly', 'Quarterly', 'One Off', 'Single'],
            'pay_plans' => ['Full', 'Instalments', 'Regular']
        ];
    }
}