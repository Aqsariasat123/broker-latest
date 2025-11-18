@extends('layouts.app')

@section('content')
<div class="dashboard">
  <h2>Dashboard</h2>

  <div class="cards">
    <div class="card green">1<br><span>Tasks Today</span></div>
    <div class="card red">1<br><span>Policies Expiring</span></div>
    <div class="card red">1<br><span>Instalments Overdue</span></div>
    <div class="card red">1<br><span>IDs Expired</span></div>
    <div class="card">121<br><span>General Policies</span></div>
    <div class="card">3265.23<br><span>Gen-Com Outstanding</span></div>
    <div class="card blue">7<br><span>Open Leads</span></div>
    <div class="card blue">7<br><span>Follow Ups Today</span></div>
    <div class="card blue">11<br><span>Proposals Pending</span></div>
    <div class="card blue">7<br><span>Proposals Processing</span></div>
    <div class="card blue">239<br><span>Life Policies</span></div>
    <div class="card red">1<br><span>Birthdays Today</span></div>
  </div>

  <div class="charts">
    <div class="chart-box">
      <h3>Income vs Expense 2024</h3>
      <canvas id="pieChart" height="240"></canvas>
    </div>
    <div class="chart-box">
      <h3>Income 2024</h3>
      <canvas id="barChart1" height="240"></canvas>
    </div>
    <div class="chart-box">
      <h3>Expenses 2024</h3>
      <canvas id="barChart2" height="240"></canvas>
    </div>
  </div>
</div>
@endsection

