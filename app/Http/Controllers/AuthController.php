<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use App\Models\Policy;
use App\Models\Client;
use App\Models\Task;
use App\Models\PaymentPlan;
use App\Models\Payment;
use App\Models\Income;
use App\Models\Expense;
use App\Models\Contact;
use App\Models\LifeProposal;
use App\Models\AuditLog;
use Carbon\Carbon;

class AuthController extends Controller
{
    // Show login page
    public function showLoginForm()
    {
        return view('login');
    }

    // Handle login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'name' => 'required',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            
            // Check if user is active
            if (!$user->is_active) {
                Auth::logout();
                return back()->withErrors([
                    'name' => 'Your account has been deactivated. Please contact administrator.',
                ])->withInput($request->only('name'));
            }

            // Update last login info
            $user->update([
                'last_login_at' => now(),
                'last_login_ip' => $request->ip(),
            ]);

            // Log login activity
            AuditLog::log('login', null, null, null, 'User logged in');

            $request->session()->regenerate();
            return redirect()->intended('/dashboard');
        }

        // Log failed login attempt
        AuditLog::log('login_failed', null, null, null, 'Failed login attempt for: ' . $request->name);

        return back()->withErrors([
            'name' => 'Invalid Credentials Provided!',
        ])->withInput($request->only('name'));
    }

    // Dashboard
    public function dashboard(Request $request)
    {
        $dateRange = $request->get('date_range', 'month');
        $today = now()->startOfDay();
        
        // Set date range based on selection
        switch ($dateRange) {
            case 'today':
                $startDate = $today;
                $endDate = $today->copy()->endOfDay();
                break;
            case 'week':
                $startDate = $today->copy()->startOfWeek();
                $endDate = $today->copy()->endOfWeek();
                break;
            case 'quarter':
                $startDate = $today->copy()->startOfQuarter();
                $endDate = $today->copy()->endOfQuarter();
                break;
            case 'year':
                $startDate = $today->copy()->startOfYear();
                $endDate = $today->copy()->endOfYear();
                break;
            default: // month
                $startDate = $today->copy()->startOfMonth();
                $endDate = $today->copy()->endOfMonth();
        }
        
        // Statistics Cards
        $stats = [
            'tasks_today' => Task::whereDate('due_date', $today)->where('task_status', '!=', 'Completed')->count(),
            'policies_expiring' => Policy::whereBetween('end_date', [$today, $today->copy()->addDays(30)])->count(),
            'instalments_overdue' => PaymentPlan::where('due_date', '<', $today)
                ->where('status', '!=', 'paid')
                ->count(),
            'ids_expired' => Client::whereNotNull('dob_dor')
                ->whereDate('dob_dor', '<', $today->copy()->subYears(10))
                ->count(),
            'general_policies' => Policy::count(),
            'gen_com_outstanding' => PaymentPlan::where('status', '!=', 'paid')
                ->sum('amount'),
            'open_leads' => Contact::where('status', '!=', 'Archived')->count(),
            'follow_ups_today' => Task::whereDate('due_date', $today)
                ->where('task_status', '!=', 'Completed')
                ->count(),
            'proposals_pending' => LifeProposal::where('status', 'Pending')->count(),
            'proposals_processing' => LifeProposal::where('status', 'Processing')->count(),
            'life_policies' => $this->countLifePolicies(),
            'birthdays_today' => Client::whereMonth('dob_dor', now()->month)
                ->whereDay('dob_dor', now()->day)
                ->count(),
        ];

        // Policy Status Distribution
        $policyStatuses = Policy::with('policyStatus')
            ->get()
            ->groupBy(function($policy) {
                return $policy->policy_status_name ?? 'Unknown';
            })
            ->map->count()
            ->toArray();

        // Upcoming Renewals (next 90 days)
        $renewals = Policy::whereBetween('end_date', [$today, $today->copy()->addDays(90)])
            ->where('renewable', true)
            ->orderBy('end_date')
            ->with('client')
            ->get()
            ->groupBy(function($policy) {
                $daysUntil = $today->diffInDays($policy->end_date);
                if ($daysUntil <= 7) return 'This Week';
                if ($daysUntil <= 30) return 'This Month';
                if ($daysUntil <= 60) return 'Next Month';
                return 'Later';
            })
            ->map->count()
            ->toArray();

        // Payment Statistics
        $paymentStats = [
            'overdue' => PaymentPlan::where('due_date', '<', $today)
                ->where('status', '!=', 'paid')
                ->sum('amount'),
            'upcoming_7_days' => PaymentPlan::whereBetween('due_date', [$today, $today->copy()->addDays(7)])
                ->where('status', '!=', 'paid')
                ->sum('amount'),
            'upcoming_30_days' => PaymentPlan::whereBetween('due_date', [$today, $today->copy()->addDays(30)])
                ->where('status', '!=', 'paid')
                ->sum('amount'),
            'paid_this_month' => Payment::whereMonth('paid_on', now()->month)
                ->whereYear('paid_on', now()->year)
                ->sum('amount'),
        ];

        // Monthly Income/Expense Data (last 12 months)
        $monthlyData = [];
        for ($i = 11; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $monthlyData[] = [
                'month' => $month->format('M Y'),
                'income' => Income::whereMonth('date_rcvd', $month->month)
                    ->whereYear('date_rcvd', $month->year)
                    ->sum('amount_received'),
                'expense' => Expense::whereMonth('date_paid', $month->month)
                    ->whereYear('date_paid', $month->year)
                    ->sum('amount_paid'),
            ];
        }

        // Recent Activities
        $recentPolicies = Policy::with('client')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $recentPayments = Payment::with(['debitNote.paymentPlan.schedule.policy.client'])
            ->orderBy('paid_on', 'desc')
            ->limit(5)
            ->get();

        return view('dashboard', compact(
            'stats',
            'policyStatuses',
            'renewals',
            'paymentStats',
            'monthlyData',
            'recentPolicies',
            'recentPayments'
        ));
    }

    // Export Dashboard Report
    public function exportDashboard(Request $request)
    {
        $dateRange = $request->get('date_range', 'month');
        $today = now()->startOfDay();
        
        // Set date range based on selection
        switch ($dateRange) {
            case 'today':
                $startDate = $today;
                $endDate = $today->copy()->endOfDay();
                break;
            case 'week':
                $startDate = $today->copy()->startOfWeek();
                $endDate = $today->copy()->endOfWeek();
                break;
            case 'quarter':
                $startDate = $today->copy()->startOfQuarter();
                $endDate = $today->copy()->endOfQuarter();
                break;
            case 'year':
                $startDate = $today->copy()->startOfYear();
                $endDate = $today->copy()->endOfYear();
                break;
            default: // month
                $startDate = $today->copy()->startOfMonth();
                $endDate = $today->copy()->endOfMonth();
        }

        $fileName = 'dashboard_report_' . $dateRange . '_' . now()->format('Y-m-d') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ];

        $handle = fopen('php://output', 'w');
        fputcsv($handle, ['Dashboard Report - ' . ucfirst($dateRange)]);
        fputcsv($handle, ['Generated: ' . now()->format('Y-m-d H:i:s')]);
        fputcsv($handle, []);

        // Statistics
        fputcsv($handle, ['Statistics']);
        fputcsv($handle, ['Metric', 'Value']);
        fputcsv($handle, ['Tasks Today', Task::whereDate('due_date', $today)->where('task_status', '!=', 'Completed')->count()]);
        fputcsv($handle, ['Policies Expiring (30 days)', Policy::whereBetween('end_date', [$today, $today->copy()->addDays(30)])->count()]);
        fputcsv($handle, ['Instalments Overdue', PaymentPlan::where('due_date', '<', $today)->where('status', '!=', 'paid')->count()]);
        fputcsv($handle, ['Total Policies', Policy::count()]);
        fputcsv($handle, ['Outstanding Amount', PaymentPlan::where('status', '!=', 'paid')->sum('amount')]);
        fputcsv($handle, ['Open Leads', Contact::where('status', '!=', 'Archived')->count()]);
        fputcsv($handle, []);

        // Policies
        fputcsv($handle, ['Recent Policies']);
        fputcsv($handle, ['Policy No', 'Client', 'Start Date', 'End Date', 'Premium']);
        Policy::with('client')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'desc')
            ->limit(50)
            ->get()
            ->each(function($policy) use ($handle) {
                fputcsv($handle, [
                    $policy->policy_no,
                    $policy->client_name ?? 'N/A',
                    $policy->start_date ? $policy->start_date->format('Y-m-d') : '',
                    $policy->end_date ? $policy->end_date->format('Y-m-d') : '',
                    $policy->premium ?? 0
                ]);
            });
        fputcsv($handle, []);

        // Payments
        fputcsv($handle, ['Recent Payments']);
        fputcsv($handle, ['Reference', 'Amount', 'Paid On', 'Status']);
        Payment::whereBetween('paid_on', [$startDate, $endDate])
            ->orderBy('paid_on', 'desc')
            ->limit(50)
            ->get()
            ->each(function($payment) use ($handle) {
                fputcsv($handle, [
                    $payment->payment_reference ?? 'N/A',
                    $payment->amount ?? 0,
                    $payment->paid_on ? Carbon::parse($payment->paid_on)->format('Y-m-d') : '',
                    'Paid'
                ]);
            });

        fclose($handle);
        return response()->streamDownload(function() use ($handle) {
            //
        }, $fileName, $headers);
    }

    // Logout
    public function logout(Request $request)
    {
        // Log logout activity
        if (Auth::check()) {
            AuditLog::log('logout', null, null, null, 'User logged out');
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    /**
     * Safely count life policies, handling cases where columns might not exist
     */
    private function countLifePolicies()
    {
        try {
            // Check if policy_class_id column exists
            $columns = \Schema::getColumnListing('policies');
            if (in_array('policy_class_id', $columns)) {
                return Policy::whereHas('policyClass', function($q) {
                    $q->where('name', 'LIKE', '%Life%');
                })->count();
            }
        } catch (\Exception $e) {
            // Column doesn't exist or relationship fails
        }
        
        // Fallback: return 0 or count all policies if we can't determine
        return 0;
    }
}
