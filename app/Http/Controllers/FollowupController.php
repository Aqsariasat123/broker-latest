<?php

namespace App\Http\Controllers;

use App\Models\Followup;
use App\Models\Contact;
use App\Models\Client;
use App\Models\LifeProposal;
use App\Helpers\TableConfigHelper;
use Illuminate\Http\Request;
use Carbon\Carbon;

class FollowupController extends Controller
{
    public function index(Request $request)
    {
        $config = TableConfigHelper::getConfig('followups');
        $selectedColumns = session('followup_columns', $config['default_columns'] ?? [
            'fuid', 'due_date', 'due_in', 'category', 'name', 'follow_up_note',
            'contact_no', 'policy_no', 'fu_status', 'date_done', 'comment'
        ]);
        $columnDefinitions = $config['column_definitions'] ?? [
            'fuid' => 'FUID',
            'due_date' => 'Due Date',
            'due_in' => 'Due in',
            'category' => 'Category',
            'name' => 'Name',
            'follow_up_note' => 'Follow Up Note',
            'contact_no' => 'Contact No',
            'policy_no' => 'Policy No',
            'fu_status' => 'FU Status',
            'date_done' => 'Date Done',
            'comment' => 'Comment',
        ];
        $mandatoryColumns = $config['mandatory_columns'] ?? ['fuid', 'name'];

        // Get all follow ups data
        $followups = $this->getAllFollowups($request);

        return view('followups.index', compact(
            'followups',
            'selectedColumns',
            'columnDefinitions',
            'mandatoryColumns'
        ));
    }

    private function getAllFollowups(Request $request)
    {
        $allFollowups = collect();

        // 1. Get from followups table
        $query = Followup::with(['contact', 'client', 'lifeProposal.policy', 'user']);

        // Filter by date range if provided
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('follow_up_date', [$request->start_date, $request->end_date]);
        }

        // Filter to show only pending/open followups by default
        if (!$request->filled('show_all')) {
            $query->where('status', '!=', 'Done');
        }

        $followupsFromTable = $query->orderBy('follow_up_date', 'asc')->get();

        foreach ($followupsFromTable as $followup) {
            $category = 'Contact';
            if ($followup->life_proposal_id) {
                $category = 'Proposal';
            } elseif ($followup->client_id && !$followup->contact_id) {
                $category = 'Client';
            }

            $name = '';
            $contactNo = '';
            if ($followup->contact) {
                $name = $followup->contact->contact_name;
                $contactNo = $followup->contact->contact_no ?? $followup->contact->mobile_no ?? '';
            } elseif ($followup->client) {
                $name = $followup->client->client_name;
                $contactNo = $followup->client->contact_no ?? $followup->client->mobile_no ?? '';
            }

            $policyNo = '';
            if ($followup->lifeProposal) {
                $policyNo = $followup->lifeProposal->proposal_no ?? '';
            }

            $dueIn = '';
            if ($followup->follow_up_date) {
                $dueDate = Carbon::parse($followup->follow_up_date);
                $today = Carbon::today();
                $dueIn = $today->diffInDays($dueDate, false);
            }

            $allFollowups->push((object)[
                'id' => $followup->id,
                'source' => 'followup',
                'fuid' => $followup->follow_up_code,
                'due_date' => $followup->follow_up_date,
                'due_in' => $dueIn,
                'category' => $category,
                'name' => $name,
                'follow_up_note' => $followup->summary ?? '',
                'contact_no' => $contactNo,
                'policy_no' => $policyNo,
                'fu_status' => $followup->status ?? 'Open',
                'date_done' => $followup->status === 'Done' ? $followup->updated_at : null,
                'comment' => $followup->next_action ?? '',
                'is_overdue' => $dueIn < 0,
            ]);
        }

        // 2. Get contacts with next_follow_up that don't have a followup record
        $contactsQuery = Contact::whereNotNull('next_follow_up')
            ->whereDoesntHave('followups', function($q) {
                $q->where('status', '!=', 'Done');
            });

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $contactsQuery->whereBetween('next_follow_up', [$request->start_date, $request->end_date]);
        }

        $contactsWithFollowup = $contactsQuery->get();

        foreach ($contactsWithFollowup as $contact) {
            $dueIn = '';
            if ($contact->next_follow_up) {
                $dueDate = Carbon::parse($contact->next_follow_up);
                $today = Carbon::today();
                $dueIn = $today->diffInDays($dueDate, false);
            }

            $allFollowups->push((object)[
                'id' => 'c_' . $contact->id,
                'source' => 'contact',
                'fuid' => 'CT' . $contact->contact_id,
                'due_date' => $contact->next_follow_up,
                'due_in' => $dueIn,
                'category' => 'Contact',
                'name' => $contact->contact_name,
                'follow_up_note' => '',
                'contact_no' => $contact->contact_no ?? $contact->mobile_no ?? '',
                'policy_no' => '',
                'fu_status' => 'Not Done',
                'date_done' => null,
                'comment' => '',
                'is_overdue' => $dueIn < 0,
            ]);
        }

        // Sort by due_date
        return $allFollowups->sortBy('due_date')->values();
    }

    public function saveColumnSettings(Request $request)
    {
        $columns = $request->input('columns', []);
        session(['followup_columns' => $columns]);

        return redirect()->route('followups.index')
            ->with('success', 'Column settings saved successfully.');
    }

    public function export(Request $request)
    {
        $followups = $this->getAllFollowups($request);

        $config = TableConfigHelper::getConfig('followups');
        $selectedColumns = session('followup_columns', $config['default_columns'] ?? [
            'fuid', 'due_date', 'due_in', 'category', 'name', 'follow_up_note',
            'contact_no', 'policy_no', 'fu_status', 'date_done', 'comment'
        ]);
        $columnDefinitions = $config['column_definitions'] ?? [
            'fuid' => 'FUID',
            'due_date' => 'Due Date',
            'due_in' => 'Due in',
            'category' => 'Category',
            'name' => 'Name',
            'follow_up_note' => 'Follow Up Note',
            'contact_no' => 'Contact No',
            'policy_no' => 'Policy No',
            'fu_status' => 'FU Status',
            'date_done' => 'Date Done',
            'comment' => 'Comment',
        ];

        $headers = [];
        foreach ($selectedColumns as $col) {
            $headers[] = $columnDefinitions[$col] ?? $col;
        }

        $callback = function() use ($followups, $selectedColumns) {
            $file = fopen('php://output', 'w');

            // Headers
            $headers = [];
            $columnDefinitions = [
                'fuid' => 'FUID',
                'due_date' => 'Due Date',
                'due_in' => 'Due in',
                'category' => 'Category',
                'name' => 'Name',
                'follow_up_note' => 'Follow Up Note',
                'contact_no' => 'Contact No',
                'policy_no' => 'Policy No',
                'fu_status' => 'FU Status',
                'date_done' => 'Date Done',
                'comment' => 'Comment',
            ];
            foreach ($selectedColumns as $col) {
                $headers[] = $columnDefinitions[$col] ?? $col;
            }
            fputcsv($file, $headers);

            // Data rows
            foreach ($followups as $followup) {
                $row = [];
                foreach ($selectedColumns as $col) {
                    $value = '';
                    switch ($col) {
                        case 'fuid':
                            $value = $followup->fuid;
                            break;
                        case 'due_date':
                            $value = $followup->due_date ? Carbon::parse($followup->due_date)->format('d-M-y') : '';
                            break;
                        case 'due_in':
                            $value = $followup->due_in;
                            break;
                        case 'category':
                            $value = $followup->category;
                            break;
                        case 'name':
                            $value = $followup->name;
                            break;
                        case 'follow_up_note':
                            $value = $followup->follow_up_note;
                            break;
                        case 'contact_no':
                            $value = $followup->contact_no;
                            break;
                        case 'policy_no':
                            $value = $followup->policy_no;
                            break;
                        case 'fu_status':
                            $value = $followup->fu_status;
                            break;
                        case 'date_done':
                            $value = $followup->date_done ? Carbon::parse($followup->date_done)->format('d/m/Y') : '';
                            break;
                        case 'comment':
                            $value = $followup->comment;
                            break;
                    }
                    $row[] = $value;
                }
                fputcsv($file, $row);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="followups_' . date('Y-m-d') . '.csv"',
        ]);
    }

    public function updateStatus(Request $request, Followup $followup)
    {
        $followup->update([
            'status' => $request->status,
        ]);

        return response()->json(['success' => true]);
    }
}
