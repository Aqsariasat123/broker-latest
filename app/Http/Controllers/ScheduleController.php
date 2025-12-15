<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\Policy;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index(Request $request)
    {
        $query = Schedule::with(['policy.client']);

        // Filter by policy
        if ($request->has('policy_id') && $request->policy_id) {
            $query->where('policy_id', $request->policy_id);
        }

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Search
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('schedule_no', 'like', "%{$search}%")
                  ->orWhereHas('policy', function($subQ) use ($search) {
                      $subQ->where('policy_no', 'like', "%{$search}%");
                  });
            });
        }

        $schedules = $query->orderBy('created_at', 'desc')->paginate(15);

        // Get policies for filter
        $policies = Policy::with('client')->orderBy('policy_no')->get();

        return view('schedules.index', compact('schedules', 'policies'));
    }

    public function create()
    {
        $policies = Policy::with('client')->orderBy('policy_no')->get();
        return view('schedules.create', compact('policies'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'policy_id' => 'required|exists:policies,id',
            'schedule_no' => 'required|string|max:255|unique:schedules,schedule_no',
            'issued_on' => 'nullable|date',
            'effective_from' => 'nullable|date',
            'effective_to' => 'nullable|date',
            'status' => 'required|in:draft,active,expired,cancelled',
            'debit_note_path' => 'nullable|string|max:255',
            'receipt_path' => 'nullable|string|max:255',
            'policy_schedule_path' => 'nullable|string|max:255',
            'renewal_notice_path' => 'nullable|string|max:255',
            'payment_agreement_path' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $schedule = Schedule::create($validated);

        // Log activity
        \App\Models\AuditLog::log('create', $schedule, null, $schedule->getAttributes(), 'Schedule created: ' . $schedule->schedule_no);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Schedule created successfully.',
                'schedule' => $schedule
            ]);
        }

        return redirect()->route('schedules.index')
            ->with('success', 'Schedule created successfully.');
    }

    public function show(Schedule $schedule)
    {
        $schedule->load(['policy.client', 'paymentPlans.debitNotes.payments']);
        return view('schedules.show', compact('schedule'));
    }

    public function edit(Schedule $schedule)
    {
        $policies = Policy::with('client')->orderBy('policy_no')->get();
        
        if (request()->ajax()) {
            return response()->json([
                'schedule' => $schedule,
                'policies' => $policies
            ]);
        }
        
        return view('schedules.edit', compact('schedule', 'policies'));
    }

    public function update(Request $request, Schedule $schedule)
    {
        $validated = $request->validate([
            'policy_id' => 'required|exists:policies,id',
            'schedule_no' => 'required|string|max:255|unique:schedules,schedule_no,' . $schedule->id,
            'issued_on' => 'nullable|date',
            'effective_from' => 'nullable|date',
            'effective_to' => 'nullable|date',
            'status' => 'required|in:draft,active,expired,cancelled',
            'debit_note_path' => 'nullable|string|max:255',
            'receipt_path' => 'nullable|string|max:255',
            'policy_schedule_path' => 'nullable|string|max:255',
            'renewal_notice_path' => 'nullable|string|max:255',
            'payment_agreement_path' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $oldValues = $schedule->getAttributes();
        $schedule->update($validated);

        // Log activity
        \App\Models\AuditLog::log('update', $schedule, $oldValues, $schedule->getChanges(), 'Schedule updated: ' . $schedule->schedule_no);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Schedule updated successfully.',
                'schedule' => $schedule
            ]);
        }

        return redirect()->route('schedules.index')
            ->with('success', 'Schedule updated successfully.');
    }

    public function destroy(Schedule $schedule)
    {
        $scheduleNo = $schedule->schedule_no;
        $schedule->delete();

        // Log activity
        \App\Models\AuditLog::log('delete', $schedule, $schedule->getAttributes(), null, 'Schedule deleted: ' . $scheduleNo);

        return redirect()->route('schedules.index')
            ->with('success', 'Schedule deleted successfully.');
    }
}
