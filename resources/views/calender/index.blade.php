

    <!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Calendar UI</title>
  <style>
    /* Reset & Base */
    body {
      font-family: Arial, sans-serif;
      margin: 20px;
      background-color: #fff;
      color: #000;
    }

    /* Header Bar */
    .header-bar {
      display: flex;
      align-items: center;
      gap: 8px;
      margin-bottom: 16px;
      font-weight: bold;
      user-select: none;
    }

    .header-bar .title {
      min-width: 80px;
    }

    .header-bar button {
      cursor: pointer;
      font-size: 13px;
      font-weight: bold;
      border: none;
      padding: 6px 14px;
      border-radius: 4px;
      color: white;
      background-color: black;
      transition: background 0.3s ease;
    }

    .header-bar button.all-btn {
      background-color: #666666;
      min-width: 50px;
      padding: 5px 12px;
    }

    .header-bar button.selected {
      background-color: #32cd32;
      color: white;
      position: relative;
      box-shadow: none;
    }

    /* The green underline label */
    .label-list {
      font-weight: normal;
      font-size: 13px;
      font-family: Arial, sans-serif;
      background-color: #32cd32;
      color: white;
      height: 18px;
      line-height: 18px;
      padding: 0 8px;
      margin-left: -4px;
      margin-top: 30px;
      border-radius: 0 0 4px 4px;
      position: relative;
      letter-spacing: 0.5px;
      user-select: none;
    }

    /* Month & Year Head + Navigation & Views Container */
    .main-controls {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 12px;
      user-select: none;
    }

    .year-month {
      font-weight: bold;
      font-size: 18px;
      display: flex;
      align-items: center;
      gap: 6px;
    }

    .year-month select {
      font-size: 14px;
      border: 1px solid #aaa;
      border-radius: 3px;
      padding: 4px 6px;
      outline: none;
      user-select: auto;
    }

    /* Nav Buttons & Views */
    .nav-buttons {
      display: flex;
      gap: 6px;
      align-items: center;
    }

    .nav-buttons .arrow-btn {
      border-radius: 18px;
      border: none;
      font-size: 20px;
      padding: 0 15px;
      background-color: #00b8f4;
      color: white;
      cursor: pointer;
    }

    .nav-buttons button.view-btn {
      background-color: #00b8f4;
      color: white;
      border-radius: 16px;
      font-weight: 600;
      font-size: 13px;
      border: none;
      padding: 6px 14px;
      cursor: pointer;
      letter-spacing: 0.8px;
      user-select: none;
    }

    /* Calendar Table */
    table.calendar {
      width: 100%;
      border-collapse: collapse;
      font-size: 13px;
      user-select: none;
    }

    table.calendar thead tr th {
      border: 1px solid #ddd;
      text-align: center;
      padding: 8px 0;
      font-weight: bold;
      background-color: #fff;
      color: #000;
    }

    table.calendar tbody tr td {
      border: 1px solid #ddd;
      height: 70px;
      vertical-align: top;
      padding: 5px 8px;
      color: #000;
    }

    /* Dates outside this month */
    table.calendar tbody tr td.outside {
      color: #bbb;
    }

    /* Event styles */
    .event {
      display: inline-block;
      background-color: #c8e6f9;
      color: #2b7bb9;
      font-weight: 600;
      font-size: 12px;
      padding: 3px 8px;
      border-radius: 4px;
      margin-top: 4px;
      max-width: 90%;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
      pointer-events: none;
      user-select: none;
    }
  </style>
</head>

<body>
    @extends('layouts.app')

    @section('content')
  <!-- Header with buttons -->
  <div class="header-bar">
    <div class="title">Calendar</div>
    <button class="all-btn selected" id="btn-all">ALL</button>
    <button class="filter-btn" id="btn-tasks">Tasks</button>
    <button class="filter-btn" id="btn-follow">Follow</button>
    <button class="filter-btn" id="btn-renewal">Renewal</button>
    <button class="filter-btn" id="btn-instalments">Instalments</button>
    <button class="filter-btn" id="btn-birthdays">Birthdays</button>
  </div>
  <!-- The green underline label below the selected button -->
  <div id="label-list-container" style="margin-left: 85px;">
    <div class="label-list" id="label-list" style="display:none;">List</div>
  </div>

  <div class="main-controls">
    <div class="year-month">
      <select id="year-select" aria-label="Select Year">
        <option value="2023">2023</option>
        <option value="2024">2024</option>
        <option value="2025" selected>2025</option>
        <option value="2026">2026</option>
        <option value="2027">2027</option>
        <option value="2028">2028</option>
        <option value="2029">2029</option>
        <option value="2030">2030</option>
      </select>
      <select id="month-select" aria-label="Select Month">
        <option value="0">January</option>
        <option value="1">February</option>
        <option value="2">March</option>
        <option value="3">April</option>
        <option value="4">May</option>
        <option value="5" selected>June</option>
        <option value="6">July</option>
        <option value="7">August</option>
        <option value="8">September</option>
        <option value="9">October</option>
        <option value="10">November</option>
        <option value="11">December</option>
      </select>
    </div>
    <div class="nav-buttons">
      <button class="arrow-btn" id="prev-month">&#8249;</button>
      <button class="arrow-btn" id="next-month">&#8250;</button>

      <button class="view-btn">TODAY</button>
      <button class="view-btn">MONTH</button>
      <button class="view-btn">WEEK</button>
      <button class="view-btn">DAY</button>
      <button class="view-btn">SCHEDULE</button>
    </div>
  </div>

  <table class="calendar" aria-label="Calendar">
    <thead>
      <tr>
        <th>MON</th>
        <th>TUE</th>
        <th>WED</th>
        <th>THU</th>
        <th>FRI</th>
        <th>SAT</th>
        <th>SUN</th>
      </tr>
    </thead>
    <tbody id="calendar-body">
      <!-- Calendar cells inserted by JS -->
    </tbody>
  </table>
<br>
<br>
<br>
<br>
  <script>
    // Get all buttons
    const allBtn = document.getElementById('btn-all');
    const filterBtns = document.querySelectorAll('.filter-btn');
    const labelList = document.getElementById('label-list');
    const labelListContainer = document.getElementById('label-list-container');

    // Show/hide label "List" only under green button (Tasks)
    function updateLabel() {
      if (tasksBtn.classList.contains('selected')) {
        labelList.style.display = 'inline-block';
        labelListContainer.style.marginLeft = tasksBtn.offsetLeft + 'px';
      } else {
        labelList.style.display = 'none';
      }
    }

    // Add click event to all buttons and manage selected state
    function clearSelections() {
      allBtn.classList.remove('selected');
      filterBtns.forEach(btn => btn.classList.remove('selected'));
    }

    allBtn.addEventListener('click', () => {
      clearSelections();
      allBtn.classList.add('selected');
      labelList.style.display = 'none';
    });

    // Track tasks button since it commands label "List"
    const tasksBtn = document.getElementById('btn-tasks');

    filterBtns.forEach(btn => {
      btn.addEventListener('click', () => {
        clearSelections();
        btn.classList.add('selected');

        if (btn === tasksBtn) {
          labelList.style.display = 'inline-block';
          labelListContainer.style.marginLeft = btn.offsetLeft + 'px';
        } else {
          labelList.style.display = 'none';
        }
      });
    });

    // Calendar variables
    const yearSelect = document.getElementById('year-select');
    const monthSelect = document.getElementById('month-select');
    const calendarBody = document.getElementById('calendar-body');

    let currentYear = parseInt(yearSelect.value);
    let currentMonth = parseInt(monthSelect.value);

    // Function to create calendar for month, Monday start
    function generateCalendar(year, month) {
      calendarBody.innerHTML = "";

      // Days of week: MON=1 ... SUN=0 but we map Sunday to 7 to make Monday first
      // Start day of month (Monday=0 index in calendar), JS Sunday=0 to 6, shift accordingly.
      let startDate = new Date(year, month, 1);
      let startDow = startDate.getDay(); // Sunday=0, Monday=1...
      // shift Sunday(0) to 7 for easier calculation:
      startDow = startDow === 0 ? 7 : startDow;
      // Calendar starts on Monday, so index:
      let calendarStartDayIndex = startDow - 1;

      // Days in current month
      let daysInMonth = new Date(year, month + 1, 0).getDate();
      // Days in previous month
      let daysInPrevMonth = new Date(year, month, 0).getDate();

      let dayCounter = 1;
      let nextMonthDay = 1;

      // 6 weeks, 7 days each
      for (let row = 0; row < 6; row++) {
        let tr = document.createElement('tr');

        for (let day = 0; day < 7; day++) {
          let td = document.createElement('td');
          if (row === 0 && day < calendarStartDayIndex) {
            // Previous month's trailing days - show number in grey
            td.classList.add('outside');
            td.textContent = (daysInPrevMonth - (calendarStartDayIndex - day) + 1);
          } else if (dayCounter > daysInMonth) {
            // Next month's leading days in grey
            td.classList.add('outside');
            td.textContent = nextMonthDay++;
          } else {
            // Current month days
            td.textContent = dayCounter;

            // Add events from the image example
            if (year === 2025 && month === 5) { // June 2025 (month=5 zero-based)
              if (dayCounter === 2) {
                let eventDiv = document.createElement('div');
                eventDiv.className = 'event';
                eventDiv.textContent = 'License Fee';
                td.appendChild(eventDiv);
              }
              if (dayCounter === 26) {
                let eventDiv2 = document.createElement('div');
                eventDiv2.className = 'event';
                eventDiv2.textContent = 'P.O. Box Rental';
                td.appendChild(eventDiv2);
              }
            }

            dayCounter++;
          }
          tr.appendChild(td);
        }
        calendarBody.appendChild(tr);
      }
    }

    // Initial draw
    generateCalendar(currentYear, currentMonth);

    // Change handlers for year/month selects
    yearSelect.addEventListener('change', () => {
      currentYear = parseInt(yearSelect.value);
      generateCalendar(currentYear, currentMonth);
    });

    monthSelect.addEventListener('change', () => {
      currentMonth = parseInt(monthSelect.value);
      generateCalendar(currentYear, currentMonth);
    });

    // Previous and Next month buttons
    const prevBtn = document.getElementById('prev-month');
    const nextBtn = document.getElementById('next-month');

    prevBtn.addEventListener('click', () => {
      currentMonth--;
      if (currentMonth < 0) {
        currentMonth = 11;
        currentYear--;
        yearSelect.value = currentYear;
      }
      monthSelect.value = currentMonth;
      generateCalendar(currentYear, currentMonth);
    });

    nextBtn.addEventListener('click', () => {
      currentMonth++;
      if (currentMonth > 11) {
        currentMonth = 0;
        currentYear++;
        yearSelect.value = currentYear;
      }
      monthSelect.value = currentMonth;
      generateCalendar(currentYear, currentMonth);
    });

    // Initially place label "List" under Tasks button if Tasks selected (default none selected)
    labelList.style.display = 'none';
  </script>
     @endsection
</body>

</html>

 