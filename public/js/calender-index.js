
  // Calendar state
  const today = new Date();
  let currentYear = today.getFullYear();
  let currentMonth = today.getMonth(); // 0-indexed
  let currentFilter = 'all';
  let eventsData = {};
  
  const monthNames = ['JANUARY', 'FEBRUARY', 'MARCH', 'APRIL', 'MAY', 'JUNE', 'JULY', 'AUGUST', 'SEPTEMBER', 'OCTOBER', 'NOVEMBER', 'DECEMBER'];

  // Fetch events from API
  async function fetchEvents() {
    try {
      const response = await fetch(`${calendarEventsRoute}?year=${currentYear}&month=${currentMonth + 1}&filter=${currentFilter}`);
      const data = await response.json();
      eventsData = data;
      generateCalendar();
    } catch (error) {
      console.error('Error fetching events:', error);
      eventsData = {};
      generateCalendar();
    }
  }

  // Update display
  function updateDisplay() {
    document.getElementById('current-year').textContent = currentYear;
    document.getElementById('current-month').textContent = monthNames[currentMonth];
    fetchEvents();
  }

  // Generate calendar
  function generateCalendar() {
    const calendarBody = document.getElementById('calendar-body');
    calendarBody.innerHTML = '';

    // Get first day of month (Monday = 0)
    const firstDay = new Date(currentYear, currentMonth, 1);
    let startDay = firstDay.getDay(); // 0 = Sunday, 1 = Monday, etc.
    // Convert to Monday = 0
    startDay = startDay === 0 ? 6 : startDay - 1;

    // Days in current month
    const daysInMonth = new Date(currentYear, currentMonth + 1, 0).getDate();
    
    // Days in previous month
    const prevMonth = currentMonth === 0 ? 11 : currentMonth - 1;
    const prevYear = currentMonth === 0 ? currentYear - 1 : currentYear;
    const daysInPrevMonth = new Date(prevYear, prevMonth + 1, 0).getDate();

    let dayCounter = 1;
    let nextMonthDay = 1;
    let prevMonthDay = daysInPrevMonth - startDay + 1;

    // Generate 6 weeks
    for (let week = 0; week < 6; week++) {
      const row = document.createElement('tr');

      for (let day = 0; day < 7; day++) {
        const cell = document.createElement('td');
        const dayNumber = document.createElement('div');
        dayNumber.className = 'day-number';

        if (week === 0 && day < startDay) {
          // Previous month days
          cell.classList.add('outside-month');
          dayNumber.classList.add('outside');
          dayNumber.textContent = prevMonthDay++;
        } else if (dayCounter > daysInMonth) {
          // Next month days
          cell.classList.add('outside-month');
          dayNumber.classList.add('outside');
          dayNumber.textContent = nextMonthDay++;
        } else {
          // Current month days
          dayNumber.textContent = dayCounter;
          
          // Add events
          const dateKey = `${currentYear}-${String(currentMonth + 1).padStart(2, '0')}-${String(dayCounter).padStart(2, '0')}`;
          const events = eventsData[dateKey] || [];
          
          events.forEach(event => {
            const eventDiv = document.createElement('div');
            eventDiv.className = `event ${event.class || event.type}`;
            eventDiv.textContent = event.text;
            eventDiv.title = event.text; // Tooltip
            cell.appendChild(eventDiv);
          });

          dayCounter++;
        }

        cell.appendChild(dayNumber);
        row.appendChild(cell);
      }

      calendarBody.appendChild(row);
    }
  }

  // Event listeners
  document.getElementById('year-prev').addEventListener('click', () => {
    currentYear--;
    updateDisplay();
  });

  document.getElementById('year-next').addEventListener('click', () => {
    currentYear++;
    updateDisplay();
  });

  document.getElementById('month-prev').addEventListener('click', () => {
    currentMonth--;
    if (currentMonth < 0) {
      currentMonth = 11;
      currentYear--;
    }
    updateDisplay();
  });

  document.getElementById('month-next').addEventListener('click', () => {
    currentMonth++;
    if (currentMonth > 11) {
      currentMonth = 0;
      currentYear++;
    }
    updateDisplay();
  });

  document.getElementById('prev-btn').addEventListener('click', () => {
    currentMonth--;
    if (currentMonth < 0) {
      currentMonth = 11;
      currentYear--;
    }
    updateDisplay();
  });

  document.getElementById('next-btn').addEventListener('click', () => {
    currentMonth++;
    if (currentMonth > 11) {
      currentMonth = 0;
      currentYear++;
    }
    updateDisplay();
  });

  document.getElementById('today-btn').addEventListener('click', () => {
    const today = new Date();
    currentYear = today.getFullYear();
    currentMonth = today.getMonth();
    updateDisplay();
  });

  // View buttons
  document.querySelectorAll('.view-btn').forEach(btn => {
    btn.addEventListener('click', function() {
      document.querySelectorAll('.view-btn').forEach(b => b.classList.remove('active'));
      this.classList.add('active');
    });
  });

  // Category filter buttons
  document.querySelectorAll('.category-btn').forEach(btn => {
    btn.addEventListener('click', function() {
      // Remove selected class and special classes from all buttons
      document.querySelectorAll('.category-btn').forEach(b => {
        b.classList.remove('selected', 'tasks', 'follow-ups', 'renewals', 'instalments', 'birthdays');
      });
      
      // Hide all dropdowns
      document.querySelectorAll('.category-dropdown').forEach(d => {
        d.classList.remove('show');
      });
      
      // Add selected class to clicked button
      this.classList.add('selected');
      const filter = this.getAttribute('data-filter');
      currentFilter = filter;
      
      // Show dropdown and make button green for all filter types except 'all'
      if (filter === 'tasks') {
        this.classList.add('tasks');
        document.getElementById('dropdown-tasks').classList.add('show');
      } else if (filter === 'follow-ups') {
        this.classList.add('follow-ups');
        document.getElementById('dropdown-follow-ups').classList.add('show');
      } else if (filter === 'renewals') {
        this.classList.add('renewals');
        document.getElementById('dropdown-renewals').classList.add('show');
      } else if (filter === 'instalments') {
        this.classList.add('instalments');
        document.getElementById('dropdown-instalments').classList.add('show');
      } else if (filter === 'birthdays') {
        this.classList.add('birthdays');
        document.getElementById('dropdown-birthdays').classList.add('show');
      }
      
      fetchEvents();
    });
  });

  // Initialize
  updateDisplay();
