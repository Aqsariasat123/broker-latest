@extends('layouts.app')

@section('content')
<style>
.dashboard {
  padding: 20px;
  overflow: hidden;
  height: calc(100vh - 40px);
  display: flex;
  flex-direction: column;
}

.dashboard h2 {
  margin: 0 0 20px 0;
  font-size: 24px;
}

.cards {
  display: grid;
  grid-template-columns: repeat(6, 1fr);
  gap: 12px;
  margin-bottom: 20px;
  flex-shrink: 0;
}

.card {
  background: #fff;
  padding: 12px;
  border-radius: 4px;
  box-shadow: 0 1px 3px rgba(0,0,0,0.12), 0 1px 2px rgba(0,0,0,0.24);
  position: relative;
  min-height: 90px;
  max-height: 90px;
  display: flex;
  flex-direction: column;
  transition: box-shadow 0.3s ease;
  border: 1px solid #f0f0f0;
}

.card:hover {
  box-shadow: 0 3px 6px rgba(0,0,0,0.16), 0 3px 6px rgba(0,0,0,0.23);
}

.card .icon {
  font-size: 24px;
  line-height: 1;
  display: block;
  position: absolute;
  top: 10px;
  left: 10px;
  width: auto;
  height: auto;
}

.card .value {
  font-size: 22px;
  font-weight: 700;
  line-height: 1.2;
  color: #2d2d2d;
  text-align: center;
  margin-top: 28px;
  margin-bottom: 4px;
  display: block;
}

.card span:not(.icon):not(.value) {
  display: block;
  font-size: 10px;
  color: #2d2d2d;
  text-align: center;
  font-weight: 400;
  line-height: 1.2;
  margin-top: 0;
}

/* Icon colors - icons have colors, but ALL values are black */
.card.icon-green .icon { color: #28a745; }
.card.icon-red .icon { color: #dc3545; }
.card.icon-pink .icon { color: #e91e63; }
.card.icon-black .icon { color: #2d2d2d; }
.card.icon-blue .icon { color: #007bff; }

/* ALL values are black regardless of icon color */
.card .value {
  color: #2d2d2d !important;
}

.charts {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 20px;
  flex: 1;
  min-height: 0;
}

.chart-box {
  background: #fff;
  padding: 15px;
  border-radius: 4px;
  box-shadow: 0 1px 3px rgba(0,0,0,0.12), 0 1px 2px rgba(0,0,0,0.24);
  display: flex;
  flex-direction: column;
  overflow: hidden;
  height: 310px;
}

.chart-box h3 {
  margin: 0;
  font-size: 16px;
  font-weight: 600;
  color: #2d2d2d;
}

.chart-controls {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 12px;
  font-size: 13px;
}

.year-selector {
  display: flex;
  align-items: center;
  gap: 5px;
}

.year-selector select {
  padding: 4px 10px;
  border: 1px solid #ddd;
  border-radius: 3px;
  font-size: 13px;
  background: #fff;
  cursor: pointer;
}

.date-range {
  display: flex;
  flex-direction: row;
  gap: 15px;
  font-size: 11px;
  margin-bottom: 15px;
  align-items: flex-start;
}

.date-range-item {
  display: flex;
  flex-direction: column;
  gap: 5px;
  flex: 1;
}

.date-range-item > div:first-child {
  color: #666;
  font-size: 11px;
  line-height: 1.6;
  margin-bottom: 6px;
  display: flex;
  align-items: center;
  gap: 5px;
}

.date-input {
  padding: 3px 5px;
  border: 1px solid #ccc;
  border-radius: 2px;
  font-size: 11px;
  background: #fff;
  width: 75px;
  color: #2d2d2d;
  font-family: inherit;
  cursor: default;
  margin-left: 0;
}

.amount-input {
  padding: 4px 6px;
  border: 1px solid #90caf9;
  border-radius: 3px;
  font-size: 11px;
  font-weight: 600;
  background: #e3f2fd;
  width: 100%;
  color: #2d2d2d;
  font-family: inherit;
  cursor: default;
  box-shadow: 0 1px 2px rgba(0,0,0,0.08);
  margin-top: 0;
}

.chart-box canvas {
  width: 100% !important;
  height: 180px !important;
  margin: 10px 0;
}

#incomeExpenseChart {
  height: 160px !important;
  max-height: 160px !important;
}

.month-stats {
  margin-top: 12px;
  font-size: 10px;
  display: grid;
  grid-template-columns: repeat(12, 1fr);
  gap: 4px;
  max-height: 60px;
  overflow-x: auto;
  overflow-y: hidden;
  padding-right: 4px;
}

.month-stat-item {
  text-align: center;
  padding: 7px 5px;
  font-size: 9px;
  color: #555;
  line-height: 1.4;
  background: #f5f5f5;
  border-radius: 3px;
  border: 1px solid #e0e0e0;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  min-height: 48px;
}

.month-stat-item > div:nth-child(1) {
  font-weight: 600;
  font-size: 11px;
  color: #2d2d2d;
  margin-bottom: 3px;
  line-height: 1.1;
}

.month-stat-item > div:nth-child(2) {
  font-weight: 600;
  font-size: 10px;
  color: #2d2d2d;
  margin-bottom: 2px;
  line-height: 1.1;
}

.month-stat-item > div:nth-child(3) {
  font-size: 8px;
  color: #666;
  line-height: 1.1;
}

.recent-activities {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 20px;
  margin-top: 20px;
  flex-shrink: 0;
}

.recent-activities .chart-box {
  max-height: 250px;
  overflow: hidden;
  display: flex;
  flex-direction: column;
}

.recent-activities table {
  font-size: 12px;
  flex: 1;
  overflow-y: auto;
}

.recent-activities thead {
  position: sticky;
  top: 0;
  background: #f5f5f5;
  z-index: 1;
}
</style>

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
    <!-- Tasks Today: Green alarm clock icon, Black value -->
    <div class="card icon-green">
      <span class="icon">⏰</span>
      <span class="value">{{ $stats['tasks_today'] ?? 0 }}</span>
      <span>Tasks Today</span>
    </div>
    <!-- Policies Expiring: Red warning icon, Black value -->
    <div class="card icon-red">
      <span class="icon">⚠️</span>
      <span class="value">{{ $stats['policies_expiring'] ?? 0 }}</span>
      <span>Policies Expiring</span>
    </div>
    <!-- Instalments Overdue: Pink money bag icon, Black value -->
    <div class="card icon-pink">
      <span class="icon">💰</span>
      <span class="value">{{ $stats['instalments_overdue'] ?? 0 }}</span>
      <span>Instalments Overdue</span>
    </div>
    <!-- IDs Expired: Black ID card icon, Black value -->
    <div class="card icon-black">
      <span class="icon">🆔</span>
      <span class="value">{{ $stats['ids_expired'] ?? 0 }}</span>
      <span>IDs Expired</span>
    </div>
    <!-- General Policies: Black document icon, Black value -->
    <div class="card icon-black">
      <span class="icon">📄</span>
      <span class="value">{{ $stats['general_policies'] ?? 0 }}</span>
      <span>General Policies</span>
    </div>
    <!-- Gen-Com Outstanding: Black money icon, Black value -->
    <div class="card icon-black">
      <span class="icon">💵</span>
      <span class="value">{{ number_format($stats['gen_com_outstanding'] ?? 0, 2) }}</span>
      <span>Gen-Com Outstanding</span>
    </div>
    <!-- Open Leads: Black people icon, Black value -->
    <div class="card icon-black">
      <span class="icon">👥</span>
      <span class="value">{{ $stats['open_leads'] ?? 0 }}</span>
      <span>Open Leads</span>
    </div>
    <!-- Follow Ups Today: Red calendar icon, Black value -->
    <div class="card icon-red">
      <span class="icon">📅</span>
      <span class="value">{{ $stats['follow_ups_today'] ?? 0 }}</span>
      <span>Follow Ups Today</span>
    </div>
    <!-- Proposals Pending: Black clipboard icon, Black value -->
    <div class="card icon-black">
      <span class="icon">📋</span>
      <span class="value">{{ $stats['proposals_pending'] ?? 0 }}</span>
      <span>Proposals Pending</span>
    </div>
    <!-- Proposals Processing: Black gear icon, Black value -->
    <div class="card icon-black">
      <span class="icon">⚙️</span>
      <span class="value">{{ $stats['proposals_processing'] ?? 0 }}</span>
      <span>Proposals Processing</span>
    </div>
    <!-- Life Policies: Black heart icon, Black value -->
    <div class="card icon-black">
      <span class="icon">❤️</span>
      <span class="value">{{ $stats['life_policies'] ?? 0 }}</span>
      <span>Life Policies</span>
    </div>
    <!-- Birthdays Today: Red cake icon, Black value -->
    <div class="card icon-red">
      <span class="icon">🎂</span>
      <span class="value">{{ $stats['birthdays_today'] ?? 0 }}</span>
      <span>Birthdays Today</span>
    </div>
  </div>

  <!-- Income vs Expense Charts -->
  <div class="charts">
    <div class="chart-box">
      <div class="chart-controls">
        <h3 style="margin: 0;">Income v/s Expense</h3>
        <div class="year-selector">
          <select name="incomeExpenseYear" id="incomeExpenseYear" onchange="updateChartYear('incomeExpense', this.value)">
            @for($y = now()->year; $y >= now()->year - 5; $y--)
              <option value="{{ $y }}" {{ ($incomeExpenseYear ?? $selectedYear ?? now()->year) == $y ? 'selected' : '' }}>{{ $y }}</option>
            @endfor
          </select>
        </div>
      </div>
      <div class="date-range">
        <div class="date-range-item">
          <div>From : <input type="text" value="{{ $yearStart->format('j-M-y') }}" readonly class="date-input"></div>
          <input type="text" value="{{ number_format($totalIncome ?? 0, 2) }}" readonly class="amount-input">
        </div>
        <div class="date-range-item">
          <div>To : <input type="text" value="{{ $yearEnd->format('j-M-y') }}" readonly class="date-input"></div>
          <input type="text" value="{{ number_format($totalExpense ?? 0, 2) }}" readonly class="amount-input">
        </div>
      </div>
      <canvas id="incomeExpenseChart"></canvas>
    </div>

    <div class="chart-box">
      <div class="chart-controls">
        <h3 style="margin: 0;">Income</h3>
        <div class="year-selector">
          <select name="incomeYear" id="incomeYear" onchange="updateChartYear('income', this.value)">
            @for($y = now()->year; $y >= now()->year - 5; $y--)
              <option value="{{ $y }}" {{ ($incomeYear ?? $selectedYear ?? now()->year) == $y ? 'selected' : '' }}>{{ $y }}</option>
            @endfor
          </select>
        </div>
      </div>
      <canvas id="incomeChart"></canvas>
      <div class="month-stats" id="incomeStats"></div>
    </div>

    <div class="chart-box">
      <div class="chart-controls">
        <h3 style="margin: 0;">Expenses</h3>
        <div class="year-selector">
          <select name="expenseYear" id="expenseYear" onchange="updateChartYear('expense', this.value)">
            @for($y = now()->year; $y >= now()->year - 5; $y--)
              <option value="{{ $y }}" {{ ($expenseYear ?? $selectedYear ?? now()->year) == $y ? 'selected' : '' }}>{{ $y }}</option>
            @endfor
          </select>
        </div>
      </div>
      <canvas id="expenseChart"></canvas>
      <div class="month-stats" id="expenseStats"></div>
    </div>
  </div>


</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  const incomeExpenseMonthlyData = @json($incomeExpenseMonthlyData ?? $monthlyData ?? []);
  const incomeExpenseTotalIncome = {{ $incomeExpenseTotalIncome ?? $totalIncome ?? 0 }};
  const incomeExpenseTotalExpense = {{ $incomeExpenseTotalExpense ?? $totalExpense ?? 0 }};
  
  const incomeMonthlyData = @json($incomeMonthlyData ?? $monthlyData ?? []);
  const expenseMonthlyData = @json($expenseMonthlyData ?? $monthlyData ?? []);
  
  const monthlyData = @json($monthlyData ?? []);
  const totalIncome = {{ $totalIncome ?? 0 }};
  const totalExpense = {{ $totalExpense ?? 0 }};

  // Income vs Expense Pie Chart
  const incomeExpenseCtx = document.getElementById('incomeExpenseChart');
  if (incomeExpenseCtx) {
    window.incomeExpenseChart = new Chart(incomeExpenseCtx, {
      type: 'pie',
      data: {
        labels: ['Income', 'Expense'],
        datasets: [{
          data: [incomeExpenseTotalIncome, incomeExpenseTotalExpense],
          backgroundColor: ['#6c757d', '#dc3545'],
          borderWidth: 2,
          borderColor: '#2d2d2d'
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        layout: {
          padding: {
            top: 5,
            bottom: 5,
            left: 5,
            right: 5
          }
        },
        plugins: {
          legend: {
            display: false
          },
          tooltip: {
            callbacks: {
              label: function(context) {
                return context.label + ': ' + number_format(context.parsed, 2);
              }
            }
          }
        }
      }
    });
  }

  // Income Chart
  const incomeCtx = document.getElementById('incomeChart');
  if (incomeCtx) {
    // Normalize data to 0-10 scale for Y-axis
    const incomeValues = incomeMonthlyData.map(d => d.income);
    const maxIncome = Math.max(...incomeValues, 1);
    const normalizedIncome = incomeValues.map(val => (val / maxIncome) * 10);
    
    window.incomeChart = new Chart(incomeCtx, {
      type: 'bar',
      data: {
        labels: ['January', 'February', 'March', 'April', 'May', 'June', 
                 'July', 'August', 'September', 'October', 'November', 'December'],
        datasets: [{
          label: 'Income',
          data: normalizedIncome,
          backgroundColor: '#17a2b8'
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
          y: {
            beginAtZero: true,
            max: 10,
            ticks: {
              stepSize: 1
            }
          },
          x: {
            ticks: {
              font: {
                size: 9
              },
              maxRotation: 45,
              minRotation: 45
            },
            grid: {
              display: false
            }
          }
        },
        plugins: {
          legend: {
            display: false
          },
          tooltip: {
            callbacks: {
              label: function(context) {
                const index = context.dataIndex;
                return 'Income: ' + number_format(incomeValues[index], 2);
              }
            }
          }
        }
      }
    });

    // Add month stats - format: percentage, value, and "Sells" label in separate rows
    const monthNames = ['January', 'February', 'March', 'April', 'May', 'June', 
                        'July', 'August', 'September', 'October', 'November', 'December'];
    const incomeStatsHtml = incomeMonthlyData.map((d, idx) => 
      `<div class="month-stat-item"><div>${d.income_percent}%</div><div>${d.sells}</div><div>Sells</div></div>`
    ).join('');
    document.getElementById('incomeStats').innerHTML = incomeStatsHtml;
  }

  // Expense Chart
  const expenseCtx = document.getElementById('expenseChart');
  if (expenseCtx) {
    // Normalize data to 0-10 scale for Y-axis
    const expenseValues = expenseMonthlyData.map(d => d.expense);
    const maxExpense = Math.max(...expenseValues, 1);
    const normalizedExpense = expenseValues.map(val => (val / maxExpense) * 10);
    
    window.expenseChart = new Chart(expenseCtx, {
      type: 'bar',
      data: {
        labels: ['January', 'February', 'March', 'April', 'May', 'June', 
                 'July', 'August', 'September', 'October', 'November', 'December'],
        datasets: [{
          label: 'Expenses',
          data: normalizedExpense,
          backgroundColor: '#17a2b8'
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
          y: {
            beginAtZero: true,
            max: 10,
            ticks: {
              stepSize: 1
            }
          },
          x: {
            ticks: {
              font: {
                size: 9
              },
              maxRotation: 45,
              minRotation: 45
            },
            grid: {
              display: false
            }
          }
        },
        plugins: {
          legend: {
            display: false
          },
          tooltip: {
            callbacks: {
              label: function(context) {
                const index = context.dataIndex;
                return 'Expense: ' + number_format(expenseValues[index], 2);
              }
            }
          }
        }
      }
    });

    // Add month stats - format: percentage, value, and "Sells" label in separate rows
    const monthNames = ['January', 'February', 'March', 'April', 'May', 'June', 
                        'July', 'August', 'September', 'October', 'November', 'December'];
    const expenseStatsHtml = expenseMonthlyData.map((d, idx) => 
      `<div class="month-stat-item"><div>${d.expense_percent}%</div><div>${d.sells}</div><div>Sells</div></div>`
    ).join('');
    document.getElementById('expenseStats').innerHTML = expenseStatsHtml;
  }
  
  // Helper function for number formatting
  function number_format(num, decimals) {
    return parseFloat(num).toFixed(decimals).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
  }

  function updateYear(year) {
    const url = new URL(window.location.href);
    url.searchParams.set('year', year);
    window.location.href = url.toString();
  }

  function updateChartYear(chartType, year) {
    // Make AJAX request to update only the specific chart
    fetch(`{{ route('dashboard') }}?${chartType}Year=${year}`, {
      headers: {
        'X-Requested-With': 'XMLHttpRequest',
        'Accept': 'application/json'
      }
    })
    .then(response => response.json())
    .then(data => {
      if (chartType === 'incomeExpense') {
        updateIncomeExpenseChart(data);
      } else if (chartType === 'income') {
        updateIncomeChart(data);
      } else if (chartType === 'expense') {
        updateExpenseChart(data);
      }
    })
    .catch(error => {
      console.error('Error updating chart:', error);
      // Fallback to page reload
      const url = new URL(window.location.href);
      url.searchParams.set(`${chartType}Year`, year);
      window.location.href = url.toString();
    });
  }

  function updateIncomeExpenseChart(data) {
    // Update date range inputs
    const dateRangeEl = document.getElementById('incomeExpenseDateRange');
    if (dateRangeEl) {
      dateRangeEl.querySelector('.date-range-item:first-child input.date-input').value = data.yearStart;
      dateRangeEl.querySelector('.date-range-item:first-child input.amount-input').value = parseFloat(data.totalIncome).toFixed(2);
      dateRangeEl.querySelector('.date-range-item:last-child input.date-input').value = data.yearEnd;
      dateRangeEl.querySelector('.date-range-item:last-child input.amount-input').value = parseFloat(data.totalExpense).toFixed(2);
    }
    
    // Update chart
    if (window.incomeExpenseChart) {
      window.incomeExpenseChart.data.datasets[0].data = [data.totalIncome, data.totalExpense];
      window.incomeExpenseChart.update();
    }
  }

  function updateIncomeChart(data) {
    // Update chart and stats
    const incomeValues = data.monthlyData.map(d => d.income);
    const maxIncome = Math.max(...incomeValues, 1);
    const normalizedIncome = incomeValues.map(val => (val / maxIncome) * 10);
    
    if (window.incomeChart) {
      window.incomeChart.data.datasets[0].data = normalizedIncome;
      window.incomeChart.update();
    }
    
    // Update month stats
    const incomeStatsHtml = data.monthlyData.map((d, idx) => 
      `<div class="month-stat-item"><div>${d.income_percent}%</div><div>${d.sells}</div><div>Sells</div></div>`
    ).join('');
    document.getElementById('incomeStats').innerHTML = incomeStatsHtml;
  }

  function updateExpenseChart(data) {
    // Update chart and stats
    const expenseValues = data.monthlyData.map(d => d.expense);
    const maxExpense = Math.max(...expenseValues, 1);
    const normalizedExpense = expenseValues.map(val => (val / maxExpense) * 10);
    
    if (window.expenseChart) {
      window.expenseChart.data.datasets[0].data = normalizedExpense;
      window.expenseChart.update();
    }
    
    // Update month stats
    const expenseStatsHtml = data.monthlyData.map((d, idx) => 
      `<div class="month-stat-item"><div>${d.expense_percent}%</div><div>${d.sells}</div><div>Sells</div></div>`
    ).join('');
    document.getElementById('expenseStats').innerHTML = expenseStatsHtml;
  }

  function exportDashboard() {
    const dateRange = document.querySelector('select[name="date_range"]').value;
    window.location.href = `{{ route('dashboard.export') }}?date_range=${dateRange}`;
  }
</script>
@endsection
