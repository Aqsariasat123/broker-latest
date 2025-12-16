
  let currentScheduleId = null;

  function openScheduleModal(mode, scheduleId = null) {
    const modal = document.getElementById('scheduleModal');
    const form = document.getElementById('scheduleForm');
    const formMethod = document.getElementById('scheduleFormMethod');
    const modalTitle = document.getElementById('scheduleModalTitle');
    
    currentScheduleId = scheduleId;
    
    if (mode === 'add') {
      modalTitle.textContent = 'Add Schedule';
      form.reset();
      form.action = schedulesStoreRoute;
      formMethod.innerHTML = '';
      currentScheduleId = null;
    } else if (mode === 'edit' && scheduleId) {
      modalTitle.textContent = 'Edit Schedule';
      form.action = schedulesUpdateRouteTemplate.replace(':id', scheduleId);
      formMethod.innerHTML = '@method("PUT")';
      
      // Fetch schedule data
      fetch(`/schedules/${scheduleId}/edit`)
        .then(response => response.json())
        .then(data => {
          if (data.schedule) {
            const s = data.schedule;
            document.getElementById('policy_id').value = s.policy_id || '';
            document.getElementById('schedule_no').value = s.schedule_no || '';
            document.getElementById('status').value = s.status || 'draft';
            document.getElementById('issued_on').value = s.issued_on ? s.issued_on.split('T')[0] : '';
            document.getElementById('effective_from').value = s.effective_from ? s.effective_from.split('T')[0] : '';
            document.getElementById('effective_to').value = s.effective_to ? s.effective_to.split('T')[0] : '';
            document.getElementById('notes').value = s.notes || '';
          }
        })
        .catch(error => {
          console.error('Error fetching schedule data:', error);
          alert('Error loading schedule data');
        });
    }
    
    modal.classList.add('show');
    document.body.style.overflow = 'hidden';
  }

  function closeScheduleModal() {
    const modal = document.getElementById('scheduleModal');
    modal.classList.remove('show');
    document.body.style.overflow = '';
    const form = document.getElementById('scheduleForm');
    form.reset();
    currentScheduleId = null;
  }

  // Close modal on outside click
  document.getElementById('scheduleModal').addEventListener('click', function(e) {
    if (e.target === this) {
      closeScheduleModal();
    }
  });

  // Close modal on ESC key
  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
      closeScheduleModal();
    }
  });

  // Handle form submission
  document.getElementById('scheduleForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const form = this;
    const formData = new FormData(form);
    const url = form.action;
    const method = form.querySelector('[name="_method"]') ? form.querySelector('[name="_method"]').value : 'POST';
    
    // Add method override if needed
    if (method !== 'POST') {
      formData.append('_method', method);
    }
    
    fetch(url, {
      method: 'POST',
      body: formData,
      headers: {
        'X-Requested-With': 'XMLHttpRequest',
        'Accept': 'application/json'
      }
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        closeScheduleModal();
        window.location.reload();
      } else {
        alert(data.message || 'Error saving schedule');
      }
    })
    .catch(error => {
      console.error('Error:', error);
      alert('Error saving schedule');
    });
  });
