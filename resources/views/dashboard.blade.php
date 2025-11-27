@extends('layouts.app')

@section('content')
<div class="dashboard">
  <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
    <h2>Admin Dashboard</h2>
    <div style="display: flex; gap: 10px;">
      <form method="GET" action="{{ route('dashboard') }}" style="display: flex; gap: 10px; align-items: center;">
        <select name="date_range" class="form-control" style="width: auto; padding: 5px 10px;" onchange="this.form.submit()">
          <option value="today" {{ request('date_range') == 'today' ? 'selected' : '' }}>Today</option>
          <option value="week" {{ request('date_range') == 'week' ? 'selected' : '' }}>This Week</option>
          <option value="month" {{ request('date_range') == 'month' || !request('date_range') ? 'selected' : '' }}>This Month</option>
          <option value="quarter" {{ request('date_range') == 'quarter' ? 'selected' : '' }}>This Quarter</option>
          <option value="year" {{ request('date_range') == 'year' ? 'selected' : '' }}>This Year</option>
        </select>
        <button type="button" class="btn" onclick="exportDashboard()" style="padding: 5px 15px; background: #28a745; color: white; border: none; border-radius: 4px; cursor: pointer;">Export Report</button>
      </form>
    </div>
  </div>

  <!-- Statistics Cards -->
  <div class="cards">
    <div class="card green">{{ $stats['tasks_today'] ?? 0 }}<br><span>Tasks Today</span></div>
    <div class="card red">{{ $stats['policies_expiring'] ?? 0 }}<br><span>Policies Expiring (30 days)</span></div>
    <div class="card red">{{ $stats['instalments_overdue'] ?? 0 }}<br><span>Instalments Overdue</span></div>
    <div class="card red">{{ $stats['ids_expired'] ?? 0 }}<br><span>IDs Expired</span></div>
    <div class="card">{{ $stats['general_policies'] ?? 0 }}<br><span>Total Policies</span></div>
    <div class="card">{{ number_format($stats['gen_com_outstanding'] ?? 0, 2) }}<br><span>Outstanding Amount</span></div>
    <div class="card blue">{{ $stats['open_leads'] ?? 0 }}<br><span>Open Leads</span></div>
    <div class="card blue">{{ $stats['follow_ups_today'] ?? 0 }}<br><span>Follow Ups Today</span></div>
    <div class="card blue">{{ $stats['proposals_pending'] ?? 0 }}<br><span>Proposals Pending</span></div>
    <div class="card blue">{{ $stats['proposals_processing'] ?? 0 }}<br><span>Proposals Processing</span></div>
    <div class="card blue">{{ $stats['life_policies'] ?? 0 }}<br><span>Life Policies</span></div>
    <div class="card red">{{ $stats['birthdays_today'] ?? 0 }}<br><span>Birthdays Today</span></div>
  </div>

  <!-- Charts Section -->
  <div class="charts">
    <div class="chart-box">
      <h3>Policy Status Distribution</h3>
      <canvas id="policyStatusChart" height="240"></canvas>
    </div>
    <div class="chart-box">
      <h3>Upcoming Renewals (Next 90 Days)</h3>
      <canvas id="renewalsChart" height="240"></canvas>
    </div>
    <div class="chart-box">
      <h3>Payment Overview</h3>
      <canvas id="paymentsChart" height="240"></canvas>
    </div>
  </div>

  <!-- Income vs Expense Charts -->
  <div class="charts" style="margin-top: 20px;">
    <div class="chart-box">
      <h3>Income vs Expense (Last 12 Months)</h3>
      <canvas id="incomeExpenseChart" height="240"></canvas>
    </div>
    <div class="chart-box">
      <h3>Monthly Income</h3>
      <canvas id="incomeChart" height="240"></canvas>
    </div>
    <div class="chart-box">
      <h3>Monthly Expenses</h3>
      <canvas id="expenseChart" height="240"></canvas>
    </div>
  </div>

  <!-- Recent Activities -->
  <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-top: 20px;">
    <div class="chart-box">
      <h3>Recent Policies</h3>
      <div style="max-height: 300px; overflow-y: auto;">
        <table style="width: 100%; font-size: 13px;">
          <thead>
            <tr style="background: #f5f5f5; font-weight: 600;">
              <th style="padding: 8px; text-align: left;">Policy No</th>
              <th style="padding: 8px; text-align: left;">Client</th>
              <th style="padding: 8px; text-align: left;">Date</th>
            </tr>
          </thead>
          <tbody>
            @forelse($recentPolicies ?? [] as $policy)
              <tr style="border-bottom: 1px solid #eee;">
                <td style="padding: 8px;">
                  <a href="{{ route('policies.show', $policy->id) }}" style="color: #007bff; text-decoration: none;">
                    {{ $policy->policy_no }}
                  </a>
                </td>
                <td style="padding: 8px;">{{ $policy->client_name ?? 'N/A' }}</td>
                <td style="padding: 8px;">{{ $policy->created_at->format('d-M-y') }}</td>
              </tr>
            @empty
              <tr>
                <td colspan="3" style="padding: 20px; text-align: center; color: #999;">No recent policies</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

    <div class="chart-box">
      <h3>Recent Payments</h3>
      <div style="max-height: 300px; overflow-y: auto;">
        <table style="width: 100%; font-size: 13px;">
          <thead>
            <tr style="background: #f5f5f5; font-weight: 600;">
              <th style="padding: 8px; text-align: left;">Reference</th>
              <th style="padding: 8px; text-align: left;">Amount</th>
              <th style="padding: 8px; text-align: left;">Date</th>
            </tr>
          </thead>
          <tbody>
            @forelse($recentPayments ?? [] as $payment)
              <tr style="border-bottom: 1px solid #eee;">
                <td style="padding: 8px;">{{ $payment->payment_reference ?? 'N/A' }}</td>
                <td style="padding: 8px;">{{ number_format($payment->amount ?? 0, 2) }}</td>
                <td style="padding: 8px;">{{ $payment->paid_on ? \Carbon\Carbon::parse($payment->paid_on)->format('d-M-y') : 'N/A' }}</td>
              </tr>
            @empty
              <tr>
                <td colspan="3" style="padding: 20px; text-align: center; color: #999;">No recent payments</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<script>
  // Policy Status Chart
  const policyStatusCtx = document.getElementById('policyStatusChart');
  if (policyStatusCtx) {
    new Chart(policyStatusCtx, {
      type: 'doughnut',
      data: {
        labels: @json(array_keys($policyStatuses ?? [])),
        datasets: [{
          data: @json(array_values($policyStatuses ?? [])),
          backgroundColor: ['#28a745', '#ffc107', '#dc3545', '#6c757d', '#007bff', '#17a2b8']
        }]
      },
      options: { responsive: true, maintainAspectRatio: false }
    });
  }

  // Renewals Chart
  const renewalsCtx = document.getElementById('renewalsChart');
  if (renewalsCtx) {
    new Chart(renewalsCtx, {
      type: 'bar',
      data: {
        labels: @json(array_keys($renewals ?? [])),
        datasets: [{
          label: 'Policies',
          data: @json(array_values($renewals ?? [])),
          backgroundColor: '#ffc107'
        }]
      },
      options: { responsive: true, maintainAspectRatio: false }
    });
  }

  // Payments Chart
  const paymentsCtx = document.getElementById('paymentsChart');
  if (paymentsCtx) {
    new Chart(paymentsCtx, {
      type: 'bar',
      data: {
        labels: ['Overdue', 'Next 7 Days', 'Next 30 Days', 'Paid This Month'],
        datasets: [{
          label: 'Amount',
          data: [
            {{ $paymentStats['overdue'] ?? 0 }},
            {{ $paymentStats['upcoming_7_days'] ?? 0 }},
            {{ $paymentStats['upcoming_30_days'] ?? 0 }},
            {{ $paymentStats['paid_this_month'] ?? 0 }}
          ],
          backgroundColor: ['#dc3545', '#ffc107', '#17a2b8', '#28a745']
        }]
      },
      options: { responsive: true, maintainAspectRatio: false }
    });
  }

  // Income vs Expense Chart
  const incomeExpenseCtx = document.getElementById('incomeExpenseChart');
  if (incomeExpenseCtx) {
    const monthlyData = @json($monthlyData ?? []);
    new Chart(incomeExpenseCtx, {
      type: 'line',
      data: {
        labels: monthlyData.map(d => d.month),
        datasets: [{
          label: 'Income',
          data: monthlyData.map(d => d.income),
          borderColor: '#28a745',
          backgroundColor: 'rgba(40, 167, 69, 0.1)',
          fill: true
        }, {
          label: 'Expense',
          data: monthlyData.map(d => d.expense),
          borderColor: '#dc3545',
          backgroundColor: 'rgba(220, 53, 69, 0.1)',
          fill: true
        }]
      },
      options: { responsive: true, maintainAspectRatio: false }
    });
  }

  // Income Chart
  const incomeCtx = document.getElementById('incomeChart');
  if (incomeCtx) {
    const monthlyData = @json($monthlyData ?? []);
    new Chart(incomeCtx, {
      type: 'bar',
      data: {
        labels: monthlyData.map(d => d.month),
        datasets: [{
          label: 'Income',
          data: monthlyData.map(d => d.income),
          backgroundColor: '#28a745'
        }]
      },
      options: { responsive: true, maintainAspectRatio: false }
    });
  }

  // Expense Chart
  const expenseCtx = document.getElementById('expenseChart');
  if (expenseCtx) {
    const monthlyData = @json($monthlyData ?? []);
    new Chart(expenseCtx, {
      type: 'bar',
      data: {
        labels: monthlyData.map(d => d.month),
        datasets: [{
          label: 'Expenses',
          data: monthlyData.map(d => d.expense),
          backgroundColor: '#dc3545'
        }]
      },
      options: { responsive: true, maintainAspectRatio: false }
    });
  }

  // Export Dashboard Report
  function exportDashboard() {
    const dateRange = document.querySelector('select[name="date_range"]').value;
    window.location.href = `{{ route('dashboard.export') }}?date_range=${dateRange}`;
  }
</script>
@endsection

