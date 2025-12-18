  // Data initialized in Blade template

  // ============================================================================
  // UTILITY FUNCTIONS
  // ============================================================================

  // Constants
  const MANDATORY_FIELDS = ['client_name', 'client_type', 'mobile_no', 'source', 'status', 'signed_up', 'clid', 'first_name', 'surname'];
  const BUSINESS_TYPES = ['Business', 'Company', 'Organization'];
  const PASSPORT_PHOTO_DIMENSIONS = {
    minWidth: 350,
    maxWidth: 650,
    minHeight: 350,
    maxHeight: 650,
    squareRatio: 1.0,
    rectRatio: 0.78,
    tolerance: 0.15
  };

  // Helper: Remove display:none from inline style
  function removeDisplayNone(element) {
    if (!element) return;
    let currentStyle = element.getAttribute('style') || '';
    if (currentStyle.includes('display:none') || currentStyle.includes('display: none')) {
      currentStyle = currentStyle.replace(/display\s*:\s*none[^;]*;?/gi, '').trim();
      currentStyle = currentStyle.replace(/^[\s;]+|[\s;]+$/g, '');
      if (currentStyle) {
        element.setAttribute('style', currentStyle);
      } else {
        element.removeAttribute('style');
      }
    }
    element.style.display = '';
    element.style.removeProperty('display');
  }

  // Helper: Hide element with !important
  function hideElement(element) {
    if (!element) return;
    element.style.display = 'none';
    element.style.setProperty('display', 'none', 'important');
    let currentStyle = element.getAttribute('style') || '';
    if (!currentStyle.includes('display: none')) {
      currentStyle = (currentStyle ? currentStyle + '; ' : '') + 'display: none !important;';
      element.setAttribute('style', currentStyle);
    }
  }

  // Helper: Show element by removing display restrictions
  function showElement(element) {
    if (!element) return;
    // Remove display:none from inline style
    let currentStyle = element.getAttribute('style') || '';
    if (currentStyle.includes('display:none') || currentStyle.includes('display: none')) {
      currentStyle = currentStyle.replace(/display\s*:\s*none[^;]*;?/gi, '').trim();
      // Remove leading/trailing semicolons and spaces
      currentStyle = currentStyle.replace(/^[\s;]+|[\s;]+$/g, '');
      if (currentStyle) {
        element.setAttribute('style', currentStyle);
      } else {
        element.removeAttribute('style');
      }
    }
    // Also set via style property
    element.style.display = '';
    element.style.removeProperty('display');
  }

  // Helper: Apply function with multiple delays (for DOM readiness)
  function applyWithDelays(fn, delays = [10, 50, 100, 200]) {
    fn();
    requestAnimationFrame(fn);
    delays.forEach(delay => setTimeout(fn, delay));
  }

  // Helper: Check if client type is Individual
  function isIndividualType(type) {
    return type === 'Individual';
  }

  // Helper: Check if client type is Business
  function isBusinessType(type) {
    return BUSINESS_TYPES.includes(type);
  }

  // Helper: Calculate age from DOB
  function calculateAge(dob) {
    if (!dob) return '';
    const birthDate = new Date(dob);
    const today = new Date();
    let age = today.getFullYear() - birthDate.getFullYear();
    const monthDiff = today.getMonth() - birthDate.getMonth();
    if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
      age--;
    }
    return age;
  }

  // Helper: Calculate days until expiry
  function calculateDaysUntilExpiry(dateStr) {
    if (!dateStr) return '';
    const expiryDate = new Date(dateStr);
    const today = new Date();
    const diffTime = expiryDate - today;
    return Math.ceil(diffTime / (1000 * 60 * 60 * 24));
  }

  // Helper: Format date for display
  function formatDate(dateStr) {
    if (!dateStr) return '';
    const date = new Date(dateStr);
    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    return `${date.getDate()}-${months[date.getMonth()]}-${String(date.getFullYear()).slice(-2)}`;
  }

  // Helper: Validate passport photo dimensions
  function validatePassportPhoto(img, onSuccess, onError) {
    const width = img.width;
    const height = img.height;
    const { minWidth, maxWidth, minHeight, maxHeight, squareRatio, rectRatio, tolerance } = PASSPORT_PHOTO_DIMENSIONS;

    if (width < minWidth || width > maxWidth || height < minHeight || height > maxHeight) {
      onError(`Photo must be passport size (approximately 600x600 pixels or 413x531 pixels).\nCurrent dimensions: ${width}x${height} pixels.\nPlease upload a passport-size photo.`);
      return false;
    }

    const aspectRatio = width / height;
    const isSquare = Math.abs(aspectRatio - squareRatio) <= tolerance;
    const isRectangular = Math.abs(aspectRatio - rectRatio) <= tolerance;

    if (!isSquare && !isRectangular) {
      onError(`Photo must have passport size aspect ratio (square 1:1 or rectangular 35:45mm).\nCurrent ratio: ${aspectRatio.toFixed(2)}:1\nPlease upload a passport-size photo.`);
      return false;
    }

    onSuccess();
    return true;
  }

  // ============================================================================
  // FIELD VISIBILITY FUNCTIONS
  // ============================================================================

  // Helper: Ensure container is valid DOM element
  function ensureValidContainer(container) {
    if (!container || typeof container.querySelectorAll !== 'function') {
      return document;
    }
    return container;
  }

  // Hide all Business fields
  function hideBusinessFields(container = document) {
    container = ensureValidContainer(container);
    container.querySelectorAll('[data-field-type="business"]').forEach(field => {
      // Set multiple ways to ensure it's hidden
      field.style.display = 'none';
      field.style.setProperty('display', 'none', 'important');
      // Update inline style attribute
      let currentStyle = field.getAttribute('style') || '';
      if (!currentStyle.includes('display: none')) {
        currentStyle = (currentStyle ? currentStyle + '; ' : '') + 'display: none !important;';
        field.setAttribute('style', currentStyle);
      }
    });
  }

  // Show all Individual fields
  function showIndividualFields(container = document) {
    container = ensureValidContainer(container);
    // Show all Individual fields
    container.querySelectorAll('[data-field-type="individual"]').forEach(field => {
      // Remove display:none from inline style
      let currentStyle = field.getAttribute('style') || '';
      if (currentStyle.includes('display:none') || currentStyle.includes('display: none')) {
        currentStyle = currentStyle.replace(/display\s*:\s*none[^;]*;?/gi, '').trim();
        currentStyle = currentStyle.replace(/^[\s;]+|[\s;]+$/g, '');
        if (currentStyle) {
          field.setAttribute('style', currentStyle);
        } else {
          field.removeAttribute('style');
        }
      }
      // Also set via style property
      field.style.display = '';
      field.style.removeProperty('display');
    });
    // Show DOB row specifically
    container.querySelectorAll('#dob_dor_row').forEach(row => {
      let currentStyle = row.getAttribute('style') || '';
      if (currentStyle.includes('display:none') || currentStyle.includes('display: none')) {
        currentStyle = currentStyle.replace(/display\s*:\s*none[^;]*;?/gi, '').trim();
        currentStyle = currentStyle.replace(/^[\s;]+|[\s;]+$/g, '');
        if (currentStyle) {
          row.setAttribute('style', currentStyle);
        } else {
          row.removeAttribute('style');
        }
      }
      row.style.display = '';
      row.style.removeProperty('display');
    });
  }

  // Force show Individual fields and hide Business fields
  function forceIndividualFieldsVisible(container = document) {
    container = ensureValidContainer(container);
    // First, aggressively hide ALL Business fields everywhere
    container.querySelectorAll('[data-field-type="business"]').forEach(field => {
      // Set multiple ways to ensure it's hidden
      field.style.display = 'none';
      field.style.setProperty('display', 'none', 'important');
      // Update inline style attribute
      let currentStyle = field.getAttribute('style') || '';
      if (!currentStyle.includes('display: none')) {
        currentStyle = (currentStyle ? currentStyle + '; ' : '') + 'display: none !important;';
        field.setAttribute('style', currentStyle);
      }
    });
    
    // Then show all Individual fields
    container.querySelectorAll('[data-field-type="individual"]').forEach(field => {
      // Remove display:none from inline style
      let currentStyle = field.getAttribute('style') || '';
      if (currentStyle.includes('display:none') || currentStyle.includes('display: none')) {
        currentStyle = currentStyle.replace(/display\s*:\s*none[^;]*;?/gi, '').trim();
        // Remove leading/trailing semicolons and spaces
        currentStyle = currentStyle.replace(/^[\s;]+|[\s;]+$/g, '');
        if (currentStyle) {
          field.setAttribute('style', currentStyle);
        } else {
          field.removeAttribute('style');
        }
      }
      // Also set via style property
      field.style.display = '';
      field.style.removeProperty('display');
    });
    
    // Show DOB row specifically
    container.querySelectorAll('#dob_dor_row').forEach(row => {
      let currentStyle = row.getAttribute('style') || '';
      if (currentStyle.includes('display:none') || currentStyle.includes('display: none')) {
        currentStyle = currentStyle.replace(/display\s*:\s*none[^;]*;?/gi, '').trim();
        currentStyle = currentStyle.replace(/^[\s;]+|[\s;]+$/g, '');
        if (currentStyle) {
          row.setAttribute('style', currentStyle);
        } else {
          row.removeAttribute('style');
        }
      }
      row.style.display = '';
      row.style.removeProperty('display');
    });
  }

  // Show Business fields and hide Individual fields
  function showBusinessFields(container = document) {
    container = ensureValidContainer(container);
    // Hide Individual fields
    container.querySelectorAll('[data-field-type="individual"]').forEach(hideElement);
    // Hide DOB row for Business
    container.querySelectorAll('#dob_dor_row').forEach(hideElement);
    // Show Business fields
    container.querySelectorAll('[data-field-type="business"]').forEach(showElement);
  }

  // Hide all conditional fields
  function hideAllConditionalFields(container = document) {
    container = ensureValidContainer(container);
    container.querySelectorAll('[data-field-type="individual"], [data-field-type="business"]').forEach(hideElement);
  }

  // Update required fields based on client type
  function updateRequiredFields(container, isIndividual, isBusiness) {
    container = ensureValidContainer(container);
    const firstNameInput = container.querySelector('#first_name') || document.getElementById('first_name');
    const surnameInput = container.querySelector('#surname') || document.getElementById('surname');
    const businessNameInput = container.querySelector('#business_name') || document.getElementById('business_name');

    if (isIndividual) {
      if (firstNameInput) firstNameInput.required = true;
      if (surnameInput) surnameInput.required = true;
      if (businessNameInput) businessNameInput.required = false;
    } else if (isBusiness) {
      if (businessNameInput) businessNameInput.required = true;
      if (firstNameInput) firstNameInput.required = false;
      if (surnameInput) surnameInput.required = false;
    } else {
      if (firstNameInput) firstNameInput.required = false;
      if (surnameInput) surnameInput.required = false;
      if (businessNameInput) businessNameInput.required = false;
    }
  }

  // ============================================================================
  // CLIENT TYPE CHANGE HANDLERS
  // ============================================================================

  // Handle client type change - main function
  function handleClientTypeChange(eventTarget = null) {
    // Use event target if provided, otherwise find by ID
    const clientTypeSelect = eventTarget || document.getElementById('client_type');
    if (!clientTypeSelect) {
      // If no client_type select found, try to show Individual fields by default
      document.querySelectorAll('[data-field-type="individual"]').forEach(field => {
        field.style.setProperty('display', 'flex', 'important');
      });
      document.querySelectorAll('[data-field-type="business"]').forEach(field => {
        field.style.setProperty('display', 'none', 'important');
      });
      return;
    }

    const selectedType = clientTypeSelect.value || 'Individual';
    const isIndividual = isIndividualType(selectedType);
    const isBusiness = isBusinessType(selectedType);

    // Find form container - check both modal and page view
    let formContainer = clientTypeSelect.closest('form') ||
                        clientTypeSelect.closest('.modal-body') ||
                        clientTypeSelect.closest('.modal-content') ||
                        document.querySelector('#clientModal .modal-body') ||
                        document.querySelector('#clientFormPageContent') ||
                        document;

    // Ensure formContainer is valid
    formContainer = ensureValidContainer(formContainer);

    if (!selectedType) {
      // Hide all conditional fields if no type selected
      document.querySelectorAll('[data-field-type="individual"]').forEach(field => {
        field.style.display = 'none';
      });
      document.querySelectorAll('[data-field-type="business"]').forEach(field => {
        field.style.display = 'none';
      });
      return;
    }

    // Show/hide fields based on client type
    if (isIndividual) {
      // First hide all Business fields aggressively
      hideBusinessFields();
      hideBusinessFields(formContainer);
      // Then show Individual fields
      showIndividualFields();
      showIndividualFields(formContainer);
    } else if (isBusiness) {
      // Hide all Individual fields first
      document.querySelectorAll('[data-field-type="individual"]').forEach(field => {
        field.style.display = 'none';
        field.style.setProperty('display', 'none', 'important');
      });
      // Show all Business fields
      document.querySelectorAll('[data-field-type="business"]').forEach(field => {
        field.style.display = '';
        field.style.removeProperty('display');
      });
      // Also apply to container
      showBusinessFields(formContainer);
    } else {
      // Hide all conditional fields if no type selected
      document.querySelectorAll('[data-field-type="individual"]').forEach(field => {
        field.style.display = 'none';
      });
      hideBusinessFields();
      hideBusinessFields(formContainer);
    }

    // Update required fields
    updateRequiredFields(formContainer, isIndividual, isBusiness);
  }

  // Handle client type change for cloned form
  function handleClientTypeChangeInForm(container) {
    container = ensureValidContainer(container);
    const clientTypeSelect = container.querySelector('#client_type');
    if (!clientTypeSelect) return;

    const selectedType = clientTypeSelect.value;
    const isIndividual = isIndividualType(selectedType);
    const isBusiness = isBusinessType(selectedType);

    if (!selectedType) {
      hideAllConditionalFields(container);
      return;
    }

    if (isIndividual) {
      hideBusinessFields(container);
      showIndividualFields(container);
    } else if (isBusiness) {
      showBusinessFields(container);
    }

    updateRequiredFields(container, isIndividual, isBusiness);
  }

  // ============================================================================
  // FORM FIELD HELPERS
  // ============================================================================

  // Toggle Alternate No field visibility based on WhatsApp checkbox
  function toggleAlternateNoVisibility(waCheckbox, alternateNoRow) {
    if (!waCheckbox || !alternateNoRow) return;
    if (waCheckbox.checked) {
      hideElement(alternateNoRow);
    } else {
      showElement(alternateNoRow);
    }
  }

  function setupWaToggle(container = document) {
    container = ensureValidContainer(container);
    
    // Setup individual WhatsApp checkbox
    const waCheckbox = container.querySelector('#wa') || document.getElementById('wa');
    const alternateNoRow = container.querySelector('#alternate_no_row') || document.getElementById('alternate_no_row');
    if (waCheckbox && alternateNoRow) {
      const handler = () => toggleAlternateNoVisibility(waCheckbox, alternateNoRow);
      waCheckbox.removeEventListener('change', handler);
      waCheckbox.addEventListener('change', handler);
      toggleAlternateNoVisibility(waCheckbox, alternateNoRow);
    }
    
    // Setup business WhatsApp checkbox
    const waBusinessCheckbox = container.querySelector('#wa_business') || document.getElementById('wa_business');
    const alternateNoRowBusiness = container.querySelector('#alternate_no_row_business') || document.getElementById('alternate_no_row_business');
    if (waBusinessCheckbox && alternateNoRowBusiness) {
      const handler = () => toggleAlternateNoVisibility(waBusinessCheckbox, alternateNoRowBusiness);
      waBusinessCheckbox.removeEventListener('change', handler);
      waBusinessCheckbox.addEventListener('change', handler);
      toggleAlternateNoVisibility(waBusinessCheckbox, alternateNoRowBusiness);
    }
  }

  // Calculate age from DOB input
  function calculateAgeFromDOB(eventTarget = null) {
    // If eventTarget is provided, use it to find the corresponding age input
    let dobInput = eventTarget || document.getElementById('dob_dor');
    let ageInput = null;
    
    if (dobInput) {
      // Try to find age input in the same container
      const container = dobInput.closest('form') || dobInput.closest('.modal-body') || dobInput.closest('div[style*="padding:12px"]') || document;
      ageInput = container.querySelector('#dob_age') || document.getElementById('dob_age');
      
      if (dobInput && ageInput && dobInput.value) {
        ageInput.value = calculateAge(dobInput.value);
      } else if (ageInput) {
        ageInput.value = '';
      }
    } else {
      // Fallback: try to find both inputs
      dobInput = document.getElementById('dob_dor');
      ageInput = document.getElementById('dob_age');
      if (dobInput && ageInput && dobInput.value) {
        ageInput.value = calculateAge(dobInput.value);
      } else if (ageInput) {
        ageInput.value = '';
      }
    }
  }

  // Calculate days until ID expiry
  function calculateIDExpiryDays(eventTarget = null) {
    // If eventTarget is provided, use it to find the corresponding days input
    let expiryInput = eventTarget || document.getElementById('id_expiry_date');
    let daysInput = null;
    
    if (expiryInput) {
      // Try to find days input in the same container
      const container = expiryInput.closest('form') || expiryInput.closest('.modal-body') || expiryInput.closest('div[style*="padding:12px"]') || document;
      daysInput = container.querySelector('#id_expiry_days') || document.getElementById('id_expiry_days');
      
      if (expiryInput && daysInput && expiryInput.value) {
        daysInput.value = calculateDaysUntilExpiry(expiryInput.value);
      } else if (daysInput) {
        daysInput.value = '';
      }
    } else {
      // Fallback: try to find both inputs
      expiryInput = document.getElementById('id_expiry_date');
      daysInput = document.getElementById('id_expiry_days');
      if (expiryInput && daysInput && expiryInput.value) {
        daysInput.value = calculateDaysUntilExpiry(expiryInput.value);
      } else if (daysInput) {
        daysInput.value = '';
      }
    }
  }

  // Populate form fields from client data
  function populateFormFields(container, client, fieldNames) {
    container = ensureValidContainer(container);
    fieldNames.forEach(k => {
      const el = container.querySelector(`#${k}`) || document.getElementById(k);
      if (!el) return;

      if (el.type === 'checkbox') {
        el.checked = !!client[k];
        if (k === 'wa') {
          if (el.id === 'wa') {
            const alternateNoRow = container.querySelector('#alternate_no_row') || document.getElementById('alternate_no_row');
            if (alternateNoRow) {
              if (el.checked) {
                hideElement(alternateNoRow);
              } else {
                showElement(alternateNoRow);
              }
            }
          } else if (el.id === 'wa_business') {
            const alternateNoRowBusiness = container.querySelector('#alternate_no_row_business') || document.getElementById('alternate_no_row_business');
            if (alternateNoRowBusiness) {
              if (el.checked) {
                hideElement(alternateNoRowBusiness);
              } else {
                showElement(alternateNoRowBusiness);
              }
            }
          }
        }
      } else if (el.tagName === 'SELECT' || el.tagName === 'INPUT' || el.tagName === 'TEXTAREA') {
        if (el.type === 'date' && client[k]) {
          const date = new Date(client[k]);
          el.value = date.toISOString().split('T')[0];
        } else {
          el.value = client[k] ?? '';
        }
      }
    });
  }

  // ============================================================================
  // INITIALIZATION
  // ============================================================================

  document.getElementById('addClientBtn')?.addEventListener('click', () => openClientModal('add'));
  document.getElementById('columnBtn')?.addEventListener('click', () => openColumnModal());

  function handleFilterToggle(e) {
    const filtersVisible = e.target.checked;
    const columnFilters = document.querySelectorAll('.column-filter');

    columnFilters.forEach(filter => {
      if (filtersVisible) {
        filter.classList.add('visible');
        filter.style.display = 'block';
      } else {
        filter.classList.remove('visible');
        filter.style.display = 'none';
        filter.value = '';
        document.querySelectorAll('tbody tr').forEach(row => {
          row.style.display = '';
        });
      }
    });
  }

  document.getElementById('filterToggle')?.addEventListener('change', handleFilterToggle);

  // Use event delegation for client type change to catch all instances
  document.addEventListener('change', function(e) {
    if (e.target && e.target.id === 'client_type') {
      handleClientTypeChange(e.target);
      if (e.target.value === 'Individual') {
        forceIndividualFieldsVisible();
        applyWithDelays(forceIndividualFieldsVisible, [10, 50]);
      }
    }
    // Handle DOB change event
    if (e.target && e.target.id === 'dob_dor') {
      calculateAgeFromDOB(e.target);
    }
    // Handle ID Expiry Date change event
    if (e.target && e.target.id === 'id_expiry_date') {
      calculateIDExpiryDays(e.target);
    }
    // Handle WhatsApp checkbox change event (individual)
    if (e.target && e.target.id === 'wa') {
      const container = e.target.closest('form') || e.target.closest('.modal-body') || e.target.closest('div[style*="padding:12px"]') || document;
      const alternateNoRow = container.querySelector('#alternate_no_row') || document.getElementById('alternate_no_row');
      if (alternateNoRow) {
        toggleAlternateNoVisibility(e.target, alternateNoRow);
      }
    }
    // Handle WhatsApp checkbox change event (business)
    if (e.target && e.target.id === 'wa_business') {
      const container = e.target.closest('form') || e.target.closest('.modal-body') || e.target.closest('div[style*="padding:12px"]') || document;
      const alternateNoRowBusiness = container.querySelector('#alternate_no_row_business') || document.getElementById('alternate_no_row_business');
      if (alternateNoRowBusiness) {
        toggleAlternateNoVisibility(e.target, alternateNoRowBusiness);
      }
    }
  });

  // Initialize on page load
  document.addEventListener('DOMContentLoaded', function() {
    const filterToggle = document.getElementById('filterToggle');
    if (filterToggle?.checked) {
      document.querySelectorAll('.column-filter').forEach(filter => {
        filter.classList.add('visible');
        filter.style.display = 'block';
      });
    }

    const initialClientType = document.getElementById('client_type');
    if (initialClientType && (initialClientType.value === 'Individual' || !initialClientType.value)) {
      showIndividualFields();
    }

    // Also attach direct listener as backup
    const clientTypeSelect = document.getElementById('client_type');
    if (clientTypeSelect) {
      clientTypeSelect.addEventListener('change', function() {
        handleClientTypeChange(this);
        if (this.value === 'Individual') {
          forceIndividualFieldsVisible();
          applyWithDelays(forceIndividualFieldsVisible, [10, 50]);
        }
      });
    }
  });

  // Radio button selection highlighting
  function handleRadioSelection(e) {
    document.querySelectorAll('.action-radio').forEach(r => r.classList.remove('selected'));
    if (e.target.checked) {
      e.target.classList.add('selected');
    }
  }

  document.querySelectorAll('.action-radio').forEach(radio => {
    radio.addEventListener('change', handleRadioSelection);
  });

  const followUpBtn = document.getElementById('followUpBtn');
  const listAllBtn = document.getElementById('listAllBtn');

  if (followUpBtn) {
    followUpBtn.addEventListener('click', () => {
      window.location.href = clientsIndexRoute + '?follow_up=true';
    });
  }

  if (listAllBtn) {
    listAllBtn.addEventListener('click', () => {
      window.location.href = clientsIndexRoute;
    });
  }

  // ============================================================================
  // CLIENT MODAL FUNCTIONS
  // ============================================================================

  async function openEditClient(id) {
    try {
      const res = await fetch(`/clients/${id}/edit`, {
        headers: {
          'Accept': 'application/json',
          'X-Requested-With': 'XMLHttpRequest'
        }
      });
      if (!res.ok) {
        const errorText = await res.text();
        throw new Error(`HTTP ${res.status}: ${errorText}`);
      }
      const client = await res.json();
      currentClientId = id;
      openClientModal('edit', client);
    } catch (e) {
      console.error(e);
      alert('Error loading client data: ' + e.message);
    }
  }

  // Open client details modal
  async function openClientDetailsModal(clientId) {
    try {
      const res = await fetch(`/clients/${clientId}`, {
        headers: {
          'Accept': 'application/json',
          'X-Requested-With': 'XMLHttpRequest'
        }
      });
      if (!res.ok) {
        throw new Error(`HTTP ${res.status}`);
      }
      const client = await res.json();
      currentClientId = clientId;

      const clientName = `${client.first_name || ''} ${client.surname || ''}`.trim() || 'Unknown';
      document.getElementById('clientPageName').textContent = clientName;
      document.getElementById('clientPageTitle').textContent = 'Client';

      populateClientDetailsModal(client);

      document.getElementById('clientsTableView').classList.add('hidden');
      const clientPageView = document.getElementById('clientPageView');
      clientPageView.classList.add('show');
      clientPageView.style.display = 'block';
      document.getElementById('clientDetailsPageContent').style.display = 'block';
      document.getElementById('clientFormPageContent').style.display = 'none';
      document.getElementById('editClientFromPageBtn').style.display = 'inline-block';
    } catch (e) {
      console.error(e);
      alert('Error loading client details: ' + e.message);
    }
  }

  // Populate client details modal with data
  function populateClientDetailsModal(client) {
    const content = document.getElementById('clientDetailsContent');
    if (!content) return;

    const dob = client.dob_dor ? formatDate(client.dob_dor) : '';
    const dobAge = client.dob_dor ? calculateAge(client.dob_dor) : '';
    const idExpiry = client.id_expiry_date ? formatDate(client.id_expiry_date) : '';
    const idExpiryDays = client.id_expiry_date ? calculateDaysUntilExpiry(client.id_expiry_date) : '';

    const col1 = `
      <div class="detail-section">
        <div class="detail-section-header">CUSTOMER DETAILS</div>
        <div class="detail-section-body">
          <div class="detail-row">
            <span class="detail-label">Client Type</span>
            <div class="detail-value">${client.client_type || '-'}</div>
          </div>
          <div class="detail-row">
            <span class="detail-label">DOB/DOR</span>
            <div style="display:flex; gap:5px; align-items:center; flex:1;">
              <div class="detail-value" style="flex:1;">${dob}</div>
              <div class="detail-value" style="width:50px; text-align:center; flex-shrink:0;">${dobAge}</div>
            </div>
          </div>
          <div class="detail-row">
            <span class="detail-label">NIN/BCRN</span>
            <div class="detail-value">${client.nin_bcrn || '-'}</div>
          </div>
          <div class="detail-row">
            <span class="detail-label">ID Expiry Date</span>
            <div style="display:flex; gap:5px; align-items:center; flex:1;">
              <div class="detail-value" style="flex:1;">${idExpiry}</div>
              <div class="detail-value" style="width:50px; text-align:center; flex-shrink:0;">${idExpiryDays}</div>
            </div>
          </div>
          <div class="detail-row">
            <span class="detail-label">Client Status</span>
            <div class="detail-value">${client.status || 'Active'}</div>
          </div>
        </div>
      </div>
    `;

    const col2 = `
      <div class="detail-section">
        <div class="detail-section-header">CONTACT DETAILS</div>
        <div class="detail-section-body">
          <div class="detail-row">
            <span class="detail-label">Mobile No</span>
            <div class="detail-value">${client.mobile_no || '-'}</div>
          </div>
          <div class="detail-row">
            <span class="detail-label">Alternate No</span>
            <div class="detail-value">${client.alternate_no || '-'}</div>
          </div>
          <div class="detail-row">
            <span class="detail-label">Email Address</span>
            <div class="detail-value">${client.email_address || '-'}</div>
          </div>
          <div class="detail-row">
            <span class="detail-label">Contact Person</span>
            <div class="detail-value">${client.contact_person || '-'}</div>
          </div>
          <div class="detail-row">
            <span class="detail-label">Designation</span>
            <div class="detail-value">${client.designation || '-'}</div>
          </div>
        </div>
      </div>
    `;

    const col3 = `
      <div class="detail-section">
        <div class="detail-section-header">ADDRESS DETAILS</div>
        <div class="detail-section-body">
          <div class="detail-row">
            <span class="detail-label">District</span>
            <div class="detail-value">${client.district || '-'}</div>
          </div>
          <div class="detail-row">
            <span class="detail-label">Address Location</span>
            <div class="detail-value">${client.location || '-'}</div>
          </div>
          <div class="detail-row">
            <span class="detail-label">Location</span>
            <div class="detail-value">${client.island || '-'}</div>
          </div>
          <div class="detail-row">
            <span class="detail-label">Country</span>
            <div class="detail-value">${client.country || '-'}</div>
          </div>
          <div class="detail-row">
            <span class="detail-label">P.O. Box No</span>
            <div class="detail-value">${client.po_box_no || '-'}</div>
          </div>
        </div>
      </div>
    `;

    const col4 = `
      <div class="detail-section">
        <div class="detail-section-header">OTHER DETAILS</div>
        <div class="detail-section-body">
          <div class="detail-row">
            <span class="detail-label">Sign Up Date</span>
            <div class="detail-value">${client.signed_up ? formatDate(client.signed_up) : '-'}</div>
          </div>
          <div class="detail-row">
            <span class="detail-label">Source</span>
            <div class="detail-value">${client.source || '-'}</div>
          </div>
          <div class="detail-row">
            <span class="detail-label">Source Name</span>
            <div class="detail-value">${client.source_name || '-'}</div>
          </div>
          <div class="detail-row">
            <span class="detail-label">Agency</span>
            <div class="detail-value">${client.agency || 'Keystone'}</div>
          </div>
          <div class="detail-row">
            <span class="detail-label">Agent</span>
            <div class="detail-value">${client.agent || '-'}</div>
          </div>
        </div>
      </div>
    `;

    content.innerHTML = col1 + col2 + col3 + col4;

    // Load documents
    const documentsList = document.getElementById('clientDocumentsList');
    if (documentsList) {
      documentsList.innerHTML = renderDocumentsList(client.documents || []);
    }

    // Set edit button action
    const editBtn = document.getElementById('editClientFromPageBtn');
    if (editBtn) {
      editBtn.onclick = () => openEditClient(currentClientId);
    }

    // Tab navigation
    document.querySelectorAll('.nav-tab').forEach(tab => {
      tab.addEventListener('click', function(e) {
        e.preventDefault();
        const clientId = currentClientId;
        if (!clientId) return;

        closeClientDetailsModal();

        const baseUrl = this.getAttribute('data-url');
        if (!baseUrl) return;

        window.location.href = baseUrl + '?client_id=' + clientId;
      });
    });
  }

  function closeClientDetailsModal() {
    closeClientPageView();
  }

  function closeClientPageView() {
    const clientPageView = document.getElementById('clientPageView');
    clientPageView.classList.remove('show');
    clientPageView.style.display = 'none';
    document.getElementById('clientsTableView').classList.remove('hidden');
    document.getElementById('clientDetailsPageContent').style.display = 'none';
    document.getElementById('clientFormPageContent').style.display = 'none';
    currentClientId = null;
  }

  // ============================================================================
  // PHOTO & DOCUMENT UPLOAD
  // ============================================================================

  // Photo upload handler
  async function handlePhotoUpload(event) {
    const file = event.target.files[0];
    if (!file || !currentClientId) {
      if (!currentClientId) alert('No client selected');
      return;
    }

    const img = new Image();
    const reader = new FileReader();

    reader.onload = async function(e) {
      img.onload = async function() {
        const isValid = validatePassportPhoto(img,
          async () => {
            const formData = new FormData();
            formData.append('photo', file);

            try {
              const response = await fetch(`/clients/${currentClientId}/upload-photo`, {
                method: 'POST',
                headers: {
                  'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || csrfToken,
                  'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
              });

              const result = await response.json();

              if (result.success) {
                const clientRes = await fetch(`/clients/${currentClientId}`, {
                  headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                  }
                });
                const client = await clientRes.json();
                populateClientDetailsModal(client);
                alert('Photo uploaded successfully!');
              } else {
                alert('Error uploading photo: ' + (result.message || 'Unknown error'));
              }
            } catch (error) {
              console.error('Error:', error);
              alert('Error uploading photo: ' + error.message);
            }

            event.target.value = '';
          },
          (errorMsg) => {
            alert(errorMsg);
            event.target.value = '';
          }
        );
      };
      img.src = e.target.result;
    };
    reader.readAsDataURL(file);
  }

  // Document upload modal functions
  function openDocumentUploadModal() {
    if (!currentClientId) {
      alert('No client selected');
      return;
    }
    document.getElementById('documentUploadModal').classList.add('show');
    document.body.style.overflow = 'hidden';
  }

  function closeDocumentUploadModal() {
    document.getElementById('documentUploadModal').classList.remove('show');
    document.body.style.overflow = '';
    document.getElementById('documentUploadForm').reset();
    const previewContainer = document.getElementById('documentPreviewContainer');
    const previewContent = document.getElementById('documentPreviewContent');
    const previewInfo = document.getElementById('documentPreviewInfo');
    if (previewContainer) previewContainer.style.display = 'none';
    if (previewContent) previewContent.innerHTML = '';
    if (previewInfo) previewInfo.innerHTML = '';
  }

  // Preview document before upload
  function previewDocument(event) {
    const file = event.target.files[0];
    const previewContainer = document.getElementById('documentPreviewContainer');
    const previewContent = document.getElementById('documentPreviewContent');
    const previewInfo = document.getElementById('documentPreviewInfo');

    if (!file || !previewContainer || !previewContent || !previewInfo) return;

    previewContainer.style.display = 'block';
    previewContent.innerHTML = '';
    previewInfo.innerHTML = '';

    const fileType = file.type;
    const fileName = file.name;
    const fileSize = (file.size / 1024 / 1024).toFixed(2);

    previewInfo.innerHTML = `<strong>File:</strong> ${fileName}<br><strong>Size:</strong> ${fileSize} MB<br><strong>Type:</strong> ${fileType || 'Unknown'}`;

    if (fileType.startsWith('image/')) {
      const reader = new FileReader();
      reader.onload = function(e) {
        previewContent.innerHTML = `<img src="${e.target.result}" alt="Document Preview" style="max-width:100%; max-height:400px; border:1px solid #ddd; border-radius:4px;">`;
      };
      reader.readAsDataURL(file);
    } else if (fileType === 'application/pdf') {
      const reader = new FileReader();
      reader.onload = function(e) {
        previewContent.innerHTML = `
          <div style="width:100%; text-align:center;">
            <embed src="${e.target.result}" type="application/pdf" width="100%" height="400px" style="border:1px solid #ddd; border-radius:4px;">
            <div style="margin-top:10px; color:#666; font-size:12px;">PDF Preview (scroll to view full document)</div>
          </div>
        `;
      };
      reader.readAsDataURL(file);
    } else {
      const fileExt = fileName.split('.').pop().toUpperCase();
      previewContent.innerHTML = `
        <div class="document-item" style="margin:0 auto;">
          <div class="document-icon" style="width:120px; height:120px; font-size:24px;">${fileExt}</div>
          <div style="font-size:12px; text-align:center; margin-top:10px; color:#666;">${fileName}</div>
        </div>
      `;
    }
  }

  // Document upload handler
  async function handleDocumentUpload() {
    const documentType = document.getElementById('documentType').value;
    const documentFile = document.getElementById('documentFile').files[0];

    if (!documentType) {
      alert('Please select a document type');
      return;
    }

    if (!documentFile) {
      alert('Please select a file');
      return;
    }

    if (!currentClientId) {
      alert('No client selected');
      return;
    }

    const formData = new FormData();
    formData.append('document', documentFile);
    formData.append('document_type', documentType);

    try {
      const response = await fetch(`/clients/${currentClientId}/upload-document`, {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || csrfToken,
          'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
      });

      const result = await response.json();

      if (result.success) {
        const clientRes = await fetch(`/clients/${currentClientId}`, {
          headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
          }
        });
        const client = await clientRes.json();

        updateDocumentsList(client);

        const clientDetailsModal = document.getElementById('clientDetailsModal');
        if (clientDetailsModal?.classList.contains('show')) {
          populateClientDetailsModal(client);
        }

        closeDocumentUploadModal();
        alert('Document uploaded successfully!');
      } else {
        alert('Error uploading document: ' + (result.message || 'Unknown error'));
      }
    } catch (error) {
      console.error('Error:', error);
      alert('Error uploading document: ' + error.message);
    }
  }

  // Render documents list HTML
  function renderDocumentsList(documents) {
    if (!documents || documents.length === 0) {
      return '<div style="color:#999; font-size:12px;">No documents uploaded</div>';
    }

    let docsHTML = '';
    documents.forEach(doc => {
      if (doc.file_path) {
        const fileExt = doc.format ? doc.format.toUpperCase() : (doc.file_path.split('.').pop().toUpperCase());
        const fileUrl = doc.file_path.startsWith('http') ? doc.file_path : `/storage/${doc.file_path}`;
        const isImage = ['JPG', 'JPEG', 'PNG'].includes(fileExt);
        const docName = doc.name || 'Document';
        docsHTML += `
          <div class="document-item" style="cursor:pointer;" onclick="previewUploadedDocument('${fileUrl}', '${fileExt}', '${docName}')">
            ${isImage ? `<img src="${fileUrl}" alt="${docName}" style="width:60px; height:60px; object-fit:cover; border-radius:4px;">` : `<div class="document-icon">${fileExt}</div>`}
            <div style="font-size:11px; text-align:center;">${docName}</div>
          </div>
        `;
      }
    });
    return docsHTML;
  }

  // Update documents list in Edit Client modal
  function updateDocumentsList(client) {
    const editDocumentsList = document.getElementById('editClientDocumentsList');
    if (editDocumentsList) {
      editDocumentsList.innerHTML = renderDocumentsList(client.documents || []);
    }
  }

  function editClientFromModal() {
    if (currentClientId) {
      closeClientDetailsModal();
      openEditClient(currentClientId);
    }
  }

  // Preview client photo and validate passport size
  function previewClientPhoto(event) {
    const file = event.target.files[0];
    const preview = document.getElementById('clientPhotoImg');
    const previewContainer = document.getElementById('clientPhotoPreview');
    const imageInput = event.target;

    if (!file || !preview || !previewContainer) return;

    const img = new Image();
    const reader = new FileReader();

    reader.onload = function(e) {
      img.onload = function() {
        validatePassportPhoto(img,
          () => {
            preview.src = e.target.result;
            preview.style.display = 'block';
            const photoSpan = previewContainer.querySelector('span');
            if (photoSpan) photoSpan.style.display = 'none';
          },
          (errorMsg) => {
            alert(errorMsg);
            imageInput.value = '';
            preview.src = '';
            preview.style.display = 'none';
            const photoSpan = previewContainer.querySelector('span');
            if (photoSpan) photoSpan.style.display = 'block';
          }
        );
      };
      img.src = e.target.result;
    };
    reader.readAsDataURL(file);
  }

  // ============================================================================
  // CLIENT MODAL MANAGEMENT
  // ============================================================================

  function openClientModal(mode, client = null) {
    const modal = document.getElementById('clientModal');
    const modalForm = modal.querySelector('form');
    const formMethod = document.getElementById('clientFormMethod');
    const deleteBtn = document.getElementById('clientDeleteBtn');

    const fieldNames = ['salutation', 'first_name', 'other_names', 'surname', 'client_type', 'nin_bcrn', 'dob_dor', 'id_expiry_date', 'passport_no', 'mobile_no', 'alternate_no', 'email_address', 'occupation', 'employer', 'income_source', 'monthly_income', 'source', 'source_name', 'agent', 'agency', 'status', 'signed_up', 'location', 'district', 'island', 'country', 'po_box_no', 'spouses_name', 'contact_person', 'pep_comment', 'notes'];

    if (mode === 'add') {
      modalForm.action = clientsStoreRoute;
      formMethod.innerHTML = '';
      deleteBtn.style.display = 'none';
      modalForm.reset();

      const clientTypeSelect = document.getElementById('client_type');
      if (clientTypeSelect && !clientTypeSelect.value) {
        clientTypeSelect.value = 'Individual';
      }

      if (clientTypeSelect && (clientTypeSelect.value === 'Individual' || !clientTypeSelect.value)) {
        // Immediately show Individual fields
        forceIndividualFieldsVisible();
        // Also apply with delays to catch any dynamically added fields
        applyWithDelays(forceIndividualFieldsVisible, [10, 50, 100, 200, 300, 500]);
        let checkCount = 0;
        const maxChecks = 20;
        const checkInterval = setInterval(() => {
          if (checkCount >= maxChecks) {
            clearInterval(checkInterval);
            return;
          }
          const currentType = document.getElementById('client_type')?.value;
          if (currentType === 'Individual' || !currentType) {
            forceIndividualFieldsVisible();
          }
          checkCount++;
        }, 100);
      }

      const imageInput = document.getElementById('image');
      if (imageInput) imageInput.required = true;

      // Clear checkboxes
      ['married', 'pep', 'has_vehicle', 'has_house', 'has_business', 'has_boat'].forEach(id => {
        const checkbox = document.getElementById(id);
        if (checkbox) checkbox.checked = false;
      });

      const waCheckbox = document.getElementById('wa');
      if (waCheckbox) {
        waCheckbox.checked = false;
        const alternateNoRow = document.getElementById('alternate_no_row');
        if (alternateNoRow) alternateNoRow.style.display = '';
      }

      // Clear photo preview
      const photoImg = document.getElementById('clientPhotoImg');
      const photoPreview = document.getElementById('clientPhotoPreview');
      if (photoImg) photoImg.style.display = 'none';
      if (photoPreview) {
        const photoSpan = photoPreview.querySelector('span');
        if (photoSpan) photoSpan.style.display = 'block';
      }

      // Clear calculated fields
      ['dob_age', 'id_expiry_days'].forEach(id => {
        const field = document.getElementById(id);
        if (field) field.value = '';
      });

      // Clear documents list
      const editDocumentsList = document.getElementById('editClientDocumentsList');
      if (editDocumentsList) editDocumentsList.innerHTML = '<div style="color:#999; font-size:12px;">No documents uploaded</div>';

      // Hide "Add Document" buttons
      ['addDocumentBtn1', 'addDocumentBtn2', 'addDocumentBtn3'].forEach(btnId => {
        const btn = document.getElementById(btnId);
        if (btn) btn.style.display = 'none';
      });

      setupWaToggle();
    } else {
      modalForm.action = `/clients/${currentClientId}`;
      formMethod.innerHTML = `@method('PUT')`;
      deleteBtn.style.display = 'inline-block';

      populateFormFields(document, client, fieldNames);

      // Set checkboxes
      ['married', 'pep', 'has_vehicle', 'has_house', 'has_business', 'has_boat'].forEach(id => {
        const checkbox = document.getElementById(id);
        if (checkbox) checkbox.checked = !!client[id];
      });

      const waCheckboxEdit = document.getElementById('wa');
      if (waCheckboxEdit) {
        waCheckboxEdit.checked = !!client.wa;
        const alternateNoRow = document.getElementById('alternate_no_row');
        if (alternateNoRow) {
          if (waCheckboxEdit.checked) {
            hideElement(alternateNoRow);
          } else {
            showElement(alternateNoRow);
          }
        }
      }
      const waBusinessCheckboxEdit = document.getElementById('wa_business');
      if (waBusinessCheckboxEdit) {
        waBusinessCheckboxEdit.checked = !!client.wa;
        const alternateNoRowBusiness = document.getElementById('alternate_no_row_business');
        if (alternateNoRowBusiness) {
          if (waBusinessCheckboxEdit.checked) {
            hideElement(alternateNoRowBusiness);
          } else {
            showElement(alternateNoRowBusiness);
          }
        }
      }

      setupWaToggle();

      // Set existing image if present
      const imageInput = document.getElementById('image');
      if (client.image) {
        document.getElementById('existing_image').value = client.image;
        const photoImg = document.getElementById('clientPhotoImg');
        const photoPreview = document.getElementById('clientPhotoPreview');
        if (photoImg && photoPreview) {
          photoImg.src = client.image.startsWith('http') ? client.image : `/storage/${client.image}`;
          photoImg.style.display = 'block';
          const photoSpan = photoPreview.querySelector('span');
          if (photoSpan) photoSpan.style.display = 'none';
        }
        if (imageInput) imageInput.required = false;
      } else {
        if (imageInput) imageInput.required = true;
      }

      updateDocumentsList(client);
      calculateAgeFromDOB();
      calculateIDExpiryDays();

      setTimeout(() => handleClientTypeChange(), 150);
    }

    // Add event listeners for calculations
    const dobInput = document.getElementById('dob_dor');
    const expiryInput = document.getElementById('id_expiry_date');
    if (dobInput) {
      dobInput.removeEventListener('change', calculateAgeFromDOB);
      dobInput.addEventListener('change', calculateAgeFromDOB);
    }
    if (expiryInput) {
      expiryInput.removeEventListener('change', calculateIDExpiryDays);
      expiryInput.addEventListener('change', calculateIDExpiryDays);
    }

    setupWaToggle();

    // Setup client type change listener
    const clientTypeSelect = document.getElementById('client_type');
    if (clientTypeSelect) {
      if (mode === 'add' && !clientTypeSelect.value) {
        clientTypeSelect.value = 'Individual';
      }

      // Initialize fields based on current value
      const initializeFields = () => {
        handleClientTypeChange();
        const currentValue = clientTypeSelect.value || 'Individual';
        if (currentValue === 'Individual') {
          forceIndividualFieldsVisible();
        }
      };

      applyWithDelays(initializeFields, [10, 50, 100, 200]);
    }

    // Clone form content from modal to page view
    const pageFormContainer = document.getElementById('clientFormPageContent');
    const pageForm = pageFormContainer?.querySelector('form');
    const formContentDiv = pageForm?.querySelector('div[style*="padding:12px"]');

    if (modalForm && pageForm && pageFormContainer && formContentDiv) {
      const modalBody = modalForm.querySelector('.modal-body');
      if (modalBody) {
        formContentDiv.innerHTML = '';

        const gridContainer = modalBody.querySelector('div[style*="grid-template-columns"]');
        if (gridContainer && !formContentDiv.querySelector('div[style*="grid-template-columns"]')) {
          const clonedGrid = gridContainer.cloneNode(true);
          formContentDiv.appendChild(clonedGrid);

          setTimeout(() => {
            const clonedClientType = formContentDiv.querySelector('#client_type');
            if (clonedClientType && (clonedClientType.value === 'Individual' || !clonedClientType.value)) {
              hideBusinessFields();
              showIndividualFields();
            }
          }, 10);
        }

        // Clone Insurables section
        const insurablesSection = modalBody.querySelector('#insurablesSection');
        if (insurablesSection && !formContentDiv.querySelector('#insurablesSection')) {
          const clonedInsurables = insurablesSection.cloneNode(true);
          formContentDiv.appendChild(clonedInsurables);
          // Ensure it's always visible
          clonedInsurables.style.display = 'block';
          clonedInsurables.style.setProperty('display', 'block', 'important');
        }

        // Clone documents section
        const editDocumentsList = modalBody.querySelector('#editClientDocumentsList');
        const editFormDocumentsSection = document.getElementById('editFormDocumentsSection');
        if (editDocumentsList && editFormDocumentsSection) {
          let documentsSection = editDocumentsList.closest('div[style*="margin-top"]') ||
                                 editDocumentsList.parentElement?.parentElement;
          if (documentsSection) {
            editFormDocumentsSection.innerHTML = '';

            const clonedDocs = documentsSection.cloneNode(true);
            const docsTitle = clonedDocs.querySelector('h4');
            const docsList = clonedDocs.querySelector('#editClientDocumentsList');
            const docsButtons = clonedDocs.querySelector('div[style*="justify-content:flex-end"]');

            if (docsTitle) {
              const titleClone = docsTitle.cloneNode(true);
              titleClone.style.marginBottom = '10px';
              titleClone.style.color = '#000';
              titleClone.style.fontSize = '13px';
              titleClone.style.fontWeight = 'bold';
              editFormDocumentsSection.appendChild(titleClone);
            }

            if (docsList) {
              const listClone = docsList.cloneNode(true);
              listClone.style.marginBottom = '10px';
              editFormDocumentsSection.appendChild(listClone);
            }

            if (docsButtons) {
              editFormDocumentsSection.appendChild(docsButtons.cloneNode(true));
            }

            editFormDocumentsSection.style.display = 'block';
          }
        }

        pageForm.method = 'POST';
        pageForm.action = modalForm.action;
        pageForm.enctype = 'multipart/form-data';

        const pageMethodDiv = pageForm.querySelector('#clientFormMethod');
        if (pageMethodDiv && formMethod) {
          pageMethodDiv.innerHTML = formMethod.innerHTML;
        }

        // If editing, populate the cloned form fields
        if (mode === 'edit' && client) {
          populateFormFields(formContentDiv, client, [...fieldNames, 'designation']);

          const businessNameInput = formContentDiv.querySelector('#business_name');
          if (businessNameInput && BUSINESS_TYPES.includes(client.client_type)) {
            businessNameInput.value = client.client_name || '';
          }

          // Set checkboxes in cloned form
          ['married', 'pep', 'wa', 'has_vehicle', 'has_house', 'has_business', 'has_boat'].forEach(id => {
            const checkbox = formContentDiv.querySelector(`#${id}`);
            if (checkbox) checkbox.checked = !!client[id];
          });
          // Also set business wa checkbox if it exists
          const waBusinessCheckbox = formContentDiv.querySelector('#wa_business');
          if (waBusinessCheckbox) {
            waBusinessCheckbox.checked = !!client.wa;
          }

          // Set existing image if present
          const imageInput = formContentDiv.querySelector('#image');
          const existingImageInput = formContentDiv.querySelector('#existing_image');
          if (client.image && existingImageInput) {
            existingImageInput.value = client.image;
            const photoImg = formContentDiv.querySelector('#clientPhotoImg');
            const photoPreview = formContentDiv.querySelector('#clientPhotoPreview');
            if (photoImg && photoPreview) {
              photoImg.src = client.image.startsWith('http') ? client.image : `/storage/${client.image}`;
              photoImg.style.display = 'block';
              const photoSpan = photoPreview.querySelector('span');
              if (photoSpan) photoSpan.style.display = 'none';
            }
            if (imageInput) imageInput.required = false;
          } else {
            if (imageInput) imageInput.required = true;
          }

          // Calculate age and expiry days for cloned form
          const dobInput = formContentDiv.querySelector('#dob_dor');
          const ageInput = formContentDiv.querySelector('#dob_age');
          const expiryInput = formContentDiv.querySelector('#id_expiry_date');
          const daysInput = formContentDiv.querySelector('#id_expiry_days');

          if (dobInput && ageInput && dobInput.value) {
            ageInput.value = calculateAge(dobInput.value);
          }

          if (expiryInput && daysInput && expiryInput.value) {
            daysInput.value = calculateDaysUntilExpiry(expiryInput.value);
          }

          // Toggle Alternate No field visibility
          const waCheckbox = formContentDiv.querySelector('#wa');
          if (waCheckbox) {
            const alternateNoRow = formContentDiv.querySelector('#alternate_no_row');
            if (alternateNoRow) {
              alternateNoRow.style.display = waCheckbox.checked ? 'none' : '';
            }
          }

          // Attach event listeners to cloned form elements
          const clonedDobInput = formContentDiv.querySelector('#dob_dor');
          const clonedExpiryInput = formContentDiv.querySelector('#id_expiry_date');
          if (clonedDobInput) {
            clonedDobInput.addEventListener('change', () => {
              const dobInput = formContentDiv.querySelector('#dob_dor');
              const ageInput = formContentDiv.querySelector('#dob_age');
              if (dobInput && ageInput && dobInput.value) {
                ageInput.value = calculateAge(dobInput.value);
              }
            });
          }
          if (clonedExpiryInput) {
            clonedExpiryInput.addEventListener('change', () => {
              const expiryInput = formContentDiv.querySelector('#id_expiry_date');
              const daysInput = formContentDiv.querySelector('#id_expiry_days');
              if (expiryInput && daysInput && expiryInput.value) {
                daysInput.value = calculateDaysUntilExpiry(expiryInput.value);
              }
            });
          }

          // Attach client type change listener to cloned form
          const clonedClientTypeSelect = formContentDiv.querySelector('#client_type');
          if (clonedClientTypeSelect) {
            if (mode === 'add' && !clonedClientTypeSelect.value) {
              clonedClientTypeSelect.value = 'Individual';
            }

            clonedClientTypeSelect.addEventListener('change', function() {
              const selectedType = this.value;
              if (selectedType === 'Individual') {
                hideBusinessFields(formContentDiv);
                showIndividualFields(formContentDiv);
                forceIndividualFieldsVisible(formContentDiv);
              } else if (isBusinessType(selectedType)) {
                showBusinessFields(formContentDiv);
              }
              handleClientTypeChangeInForm(formContentDiv);
            });

            const initClonedFormFields = () => {
              const selectedType = clonedClientTypeSelect.value || 'Individual';
              if (selectedType === 'Individual') {
                hideBusinessFields(formContentDiv);
                showIndividualFields(formContentDiv);
                forceIndividualFieldsVisible(formContentDiv);
              } else if (isBusinessType(selectedType)) {
                showBusinessFields(formContentDiv);
              }
              handleClientTypeChangeInForm(formContentDiv);
            };

            applyWithDelays(initClonedFormFields, [10, 50, 100]);
          } else {
            hideAllConditionalFields(formContentDiv);
          }

          // Attach WA checkbox listener to cloned form (individual)
          const clonedWaCheckbox = formContentDiv.querySelector('#wa');
          const clonedAlternateNoRow = formContentDiv.querySelector('#alternate_no_row');
          if (clonedWaCheckbox && clonedAlternateNoRow) {
            clonedWaCheckbox.addEventListener('change', () => {
              toggleAlternateNoVisibility(clonedWaCheckbox, clonedAlternateNoRow);
            });
          }
          // Attach WA checkbox listener to cloned form (business)
          const clonedWaBusinessCheckbox = formContentDiv.querySelector('#wa_business');
          const clonedAlternateNoRowBusiness = formContentDiv.querySelector('#alternate_no_row_business');
          if (clonedWaBusinessCheckbox && clonedAlternateNoRowBusiness) {
            clonedWaBusinessCheckbox.addEventListener('change', () => {
              toggleAlternateNoVisibility(clonedWaBusinessCheckbox, clonedAlternateNoRowBusiness);
            });
          }
          
          // Setup WA toggle for cloned form
          setupWaToggle(formContentDiv);
        }
      }
    }

    // Set page title
    if (mode === 'add') {
      document.getElementById('clientPageTitle').textContent = 'Add Client';
      document.getElementById('clientPageName').textContent = '';
      document.getElementById('editClientFromPageBtn').style.display = 'none';
    } else {
      const clientName = `${client.first_name || ''} ${client.surname || ''}`.trim() || 'Unknown';
      document.getElementById('clientPageTitle').textContent = 'Edit Client';
      document.getElementById('clientPageName').textContent = clientName;
      document.getElementById('editClientFromPageBtn').style.display = 'none';
    }

    // Hide table view, show page view
    document.getElementById('clientsTableView').classList.add('hidden');
    const clientPageView = document.getElementById('clientPageView');
    clientPageView.classList.add('show');
    clientPageView.style.display = 'block';
    document.getElementById('clientDetailsPageContent').style.display = 'none';
    document.getElementById('clientFormPageContent').style.display = 'block';

    // Ensure Insurables section is always visible
    const insurablesSection = document.getElementById('insurablesSection');
    if (insurablesSection) {
      insurablesSection.style.display = 'block';
      insurablesSection.style.setProperty('display', 'block', 'important');
    }

    // Initialize page view fields
    const initializePageViewFields = () => {
      const clientTypeSelect = document.getElementById('client_type');
      if (clientTypeSelect && (clientTypeSelect.value === 'Individual' || !clientTypeSelect.value)) {
        forceIndividualFieldsVisible();
      }
      handleClientTypeChange();

      // Ensure Insurables section is always visible
      const insurablesSection = document.getElementById('insurablesSection');
      if (insurablesSection) {
        insurablesSection.style.display = 'block';
        insurablesSection.style.setProperty('display', 'block', 'important');
      }

      const addDocumentBtn2 = document.getElementById('addDocumentBtn2');
      if (addDocumentBtn2) {
        addDocumentBtn2.style.display = mode === 'edit' ? 'inline-block' : 'none';
      }
    };

    applyWithDelays(initializePageViewFields, [10, 50, 100, 200, 300]);
  }

  function closeClientModal() {
    closeClientPageView();
  }

  function deleteClient() {
    if (!currentClientId) return;
    if (!confirm('Delete this client?')) return;
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = `/clients/${currentClientId}`;
    const csrf = document.createElement('input');
    csrf.type = 'hidden';
    csrf.name = '_token';
    csrf.value = csrfToken;
    form.appendChild(csrf);
    const method = document.createElement('input');
    method.type = 'hidden';
    method.name = '_method';
    method.value = 'DELETE';
    form.appendChild(method);
    document.body.appendChild(form);
    form.submit();
  }

  // ============================================================================
  // COLUMN MODAL FUNCTIONS
  // ============================================================================

  function openColumnModal() {
    document.getElementById('tableResponsive').classList.add('no-scroll');
    document.querySelectorAll('.column-checkbox').forEach(cb => {
      cb.checked = MANDATORY_FIELDS.includes(cb.value) || selectedColumns.includes(cb.value);
    });
    document.body.style.overflow = 'hidden';
    document.getElementById('columnModal').classList.add('show');
    setTimeout(initDragAndDrop, 100);
  }

  function closeColumnModal() {
    document.getElementById('tableResponsive').classList.remove('no-scroll');
    document.getElementById('columnModal').classList.remove('show');
    document.body.style.overflow = '';
  }

  function selectAllColumns() {
    document.querySelectorAll('.column-checkbox').forEach(cb => {
      cb.checked = true;
    });
  }

  function deselectAllColumns() {
    document.querySelectorAll('.column-checkbox').forEach(cb => {
      if (!MANDATORY_FIELDS.includes(cb.value)) {
        cb.checked = false;
      }
    });
  }

  function saveColumnSettings() {
    const items = Array.from(document.querySelectorAll('#columnSelection .column-item'));
    const order = items.map(item => item.dataset.column);
    const checked = Array.from(document.querySelectorAll('.column-checkbox:checked')).map(n => n.value);

    MANDATORY_FIELDS.forEach(field => {
      if (!checked.includes(field)) {
        checked.push(field);
      }
    });

    const orderedChecked = order.filter(col => checked.includes(col));

    const form = document.getElementById('columnForm');
    const existing = form.querySelectorAll('input[name="columns[]"]');
    existing.forEach(e => e.remove());

    orderedChecked.forEach(c => {
      const i = document.createElement('input');
      i.type = 'hidden';
      i.name = 'columns[]';
      i.value = c;
      form.appendChild(i);
    });

    form.submit();
  }

  // Drag and drop functionality
  let draggedElement = null;
  let dragOverElement = null;

  function initDragAndDrop() {
    const columnSelection = document.getElementById('columnSelection');
    if (!columnSelection) return;

    const columnItems = columnSelection.querySelectorAll('.column-item');

    columnItems.forEach(item => {
      if (item.dataset.dragInitialized === 'true') return;
      item.dataset.dragInitialized = 'true';

      const checkbox = item.querySelector('.column-checkbox');
      if (checkbox) {
        checkbox.addEventListener('mousedown', e => e.stopPropagation());
        checkbox.addEventListener('click', e => e.stopPropagation());
      }

      const label = item.querySelector('label');
      if (label) {
        label.addEventListener('mousedown', e => {
          if (e.target === label) e.preventDefault();
        });
      }

      item.addEventListener('dragstart', e => {
        draggedElement = item;
        item.classList.add('dragging');
        e.dataTransfer.effectAllowed = 'move';
        e.dataTransfer.setData('text/html', item.outerHTML);
        e.dataTransfer.setData('text/plain', item.dataset.column);
      });

      item.addEventListener('dragend', () => {
        item.classList.remove('dragging');
        columnItems.forEach(i => i.classList.remove('drag-over'));
        if (dragOverElement) {
          dragOverElement.classList.remove('drag-over');
          dragOverElement = null;
        }
        draggedElement = null;
      });

      item.addEventListener('dragover', e => {
        e.preventDefault();
        e.stopPropagation();
        e.dataTransfer.dropEffect = 'move';

        if (draggedElement && item !== draggedElement) {
          if (dragOverElement && dragOverElement !== item) {
            dragOverElement.classList.remove('drag-over');
          }

          item.classList.add('drag-over');
          dragOverElement = item;

          const rect = item.getBoundingClientRect();
          const midpoint = rect.top + (rect.height / 2);
          const next = e.clientY > midpoint;

          if (next) {
            if (item.nextSibling && item.nextSibling !== draggedElement) {
              item.parentNode.insertBefore(draggedElement, item.nextSibling);
            } else if (!item.nextSibling) {
              item.parentNode.appendChild(draggedElement);
            }
          } else {
            if (item.previousSibling !== draggedElement) {
              item.parentNode.insertBefore(draggedElement, item);
            }
          }
        }
      });

      item.addEventListener('dragenter', e => {
        e.preventDefault();
        if (draggedElement && item !== draggedElement) {
          item.classList.add('drag-over');
        }
      });

      item.addEventListener('dragleave', e => {
        if (!item.contains(e.relatedTarget)) {
          item.classList.remove('drag-over');
          if (dragOverElement === item) {
            dragOverElement = null;
          }
        }
      });

      item.addEventListener('drop', e => {
        e.preventDefault();
        e.stopPropagation();
        item.classList.remove('drag-over');
        dragOverElement = null;
        return false;
      });
    });
  }

  // ============================================================================
  // DOCUMENT PREVIEW MODALS
  // ============================================================================

  function previewUploadedDocument(fileUrl, fileExt, documentName) {
    let previewModal = document.getElementById('documentPreviewModal');
    if (!previewModal) {
      previewModal = document.createElement('div');
      previewModal.id = 'documentPreviewModal';
      previewModal.className = 'modal';
      previewModal.innerHTML = `
        <div class="modal-content" style="max-width:90%; max-height:90vh; overflow:auto;">
          <div class="modal-header">
            <h4>${documentName}</h4>
            <button type="button" class="modal-close" onclick="closeDocumentPreviewModal()">×</button>
          </div>
          <div class="modal-body" style="text-align:center; padding:20px;">
            <div id="uploadedDocumentPreview"></div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn-cancel" onclick="closeDocumentPreviewModal()">Close</button>
          </div>
        </div>
      `;
      document.body.appendChild(previewModal);
    }

    const previewContent = document.getElementById('uploadedDocumentPreview');
    const isImage = ['JPG', 'JPEG', 'PNG'].includes(fileExt);
    const isPDF = fileExt === 'PDF';

    if (isImage) {
      previewContent.innerHTML = `<img src="${fileUrl}" alt="${documentName}" style="max-width:100%; max-height:70vh; border:1px solid #ddd; border-radius:4px;">`;
    } else if (isPDF) {
      previewContent.innerHTML = `<embed src="${fileUrl}" type="application/pdf" width="100%" height="600px" style="border:1px solid #ddd; border-radius:4px;">`;
    } else {
      previewContent.innerHTML = `
        <div style="padding:40px;">
          <div class="document-icon" style="width:120px; height:120px; font-size:32px; margin:0 auto;">${fileExt}</div>
          <div style="margin-top:20px; font-size:16px; color:#666;">${documentName}</div>
          <div style="margin-top:10px;">
            <a href="${fileUrl}" target="_blank" class="btn-save" style="display:inline-block; text-decoration:none; padding:8px 16px;">Download</a>
          </div>
        </div>
      `;
    }

    previewModal.classList.add('show');
    document.body.style.overflow = 'hidden';
  }

  function closeDocumentPreviewModal() {
    const previewModal = document.getElementById('documentPreviewModal');
    if (previewModal) {
      previewModal.classList.remove('show');
      document.body.style.overflow = '';
    }
  }

  function previewClientPhotoModal(photoUrl) {
    let photoModal = document.getElementById('clientPhotoPreviewModal');
    if (!photoModal) {
      photoModal = document.createElement('div');
      photoModal.id = 'clientPhotoPreviewModal';
      photoModal.className = 'modal';
      photoModal.innerHTML = `
        <div class="modal-content" style="max-width:90%; max-height:90vh; overflow:auto; text-align:center;">
          <div class="modal-header">
            <h4>Client Photo</h4>
            <button type="button" class="modal-close" onclick="closeClientPhotoPreviewModal()">×</button>
          </div>
          <div class="modal-body" style="padding:20px; text-align:center;">
            <img src="${photoUrl}" alt="Client Photo" style="max-width:100%; max-height:70vh; border:1px solid #ddd; border-radius:4px; object-fit:contain;">
          </div>
          <div class="modal-footer">
            <button type="button" class="btn-cancel" onclick="closeClientPhotoPreviewModal()">Close</button>
          </div>
        </div>
      `;
      document.body.appendChild(photoModal);
    } else {
      const img = photoModal.querySelector('img');
      if (img) img.src = photoUrl;
    }

    photoModal.classList.add('show');
    document.body.style.overflow = 'hidden';
  }

  function closeClientPhotoPreviewModal() {
    const photoModal = document.getElementById('clientPhotoPreviewModal');
    if (photoModal) {
      photoModal.classList.remove('show');
      document.body.style.overflow = '';
    }
  }

  // Close modals on ESC or backdrop
  document.addEventListener('keydown', e => {
    if (e.key === 'Escape') {
      closeClientModal();
      closeColumnModal();
      closeClientDetailsModal();
      closeDocumentUploadModal();
      closeDocumentPreviewModal();
      closeClientPhotoPreviewModal();
    }
  });

  document.querySelectorAll('.modal').forEach(m => {
    m.addEventListener('click', e => {
      if (e.target === m) {
        m.classList.remove('show');
        document.body.style.overflow = '';
        if (m.id === 'documentUploadModal') {
          document.getElementById('documentUploadForm').reset();
        }
      }
    });
  });

  // ============================================================================
  // FORM SUBMISSION
  // ============================================================================

  document.getElementById('clientForm')?.addEventListener('submit', async function(e) {
    e.preventDefault();

    const form = this;
    const clientType = form.querySelector('#client_type')?.value;
    const isIndividual = isIndividualType(clientType);
    const isBusiness = isBusinessType(clientType);

    // Remove required attribute from hidden fields to prevent browser validation errors
    const fieldsWithRequiredRemoved = [];
    form.querySelectorAll('input[required], select[required], textarea[required]').forEach(field => {
      const parentRow = field.closest('[data-field-type]');
      let isVisible = true;
      
      if (parentRow) {
        isVisible = parentRow.offsetParent !== null && 
                   !parentRow.style.display.includes('none') && 
                   parentRow.style.display !== 'none' &&
                   window.getComputedStyle(parentRow).display !== 'none';
      } else {
        isVisible = field.offsetParent !== null && 
                   field.style.display !== 'none' && 
                   !field.style.display.includes('none') &&
                   window.getComputedStyle(field).display !== 'none';
      }
      
      if (!isVisible) {
        field.removeAttribute('required');
        fieldsWithRequiredRemoved.push(field);
      }
    });

    // Disable hidden duplicate fields to prevent submission conflicts
    if (isBusiness) {
      form.querySelectorAll('[data-field-type="individual"] input, [data-field-type="individual"] select, [data-field-type="individual"] textarea').forEach(field => {
        if (field.offsetParent === null) {
          field.disabled = true;
        }
      });
    } else if (isIndividual) {
      form.querySelectorAll('[data-field-type="business"] input, [data-field-type="business"] select, [data-field-type="business"] textarea').forEach(field => {
        if (field.offsetParent === null) {
          field.disabled = true;
        }
      });
    }

    // Check required fields (only visible ones)
    const req = form.querySelectorAll('[required]:not([disabled])');
    let ok = true;
    req.forEach(f => {
      // Double check field is actually visible
      const parentRow = f.closest('[data-field-type]');
      const isVisible = !parentRow || (parentRow.offsetParent !== null && 
                         !parentRow.style.display.includes('none') && 
                         parentRow.style.display !== 'none');
      
      if (isVisible && !String(f.value || '').trim()) {
        ok = false;
        f.style.borderColor = 'red';
      } else {
        f.style.borderColor = '';
      }
    });

    if (!ok) {
      form.querySelectorAll('input[disabled], select[disabled], textarea[disabled]').forEach(f => f.disabled = false);
      // Restore required attributes for next attempt
      form.querySelectorAll('input, select, textarea').forEach(field => {
        if (field.hasAttribute('data-was-required')) {
          field.setAttribute('required', '');
          field.removeAttribute('data-was-required');
        }
      });
      alert('Please fill required fields');
      return;
    }

    const formData = new FormData(form);

    // Re-enable disabled fields after form data is collected
    form.querySelectorAll('input[disabled], select[disabled], textarea[disabled]').forEach(f => f.disabled = false);
    
    // Restore required attributes that were removed
    fieldsWithRequiredRemoved.forEach(field => {
      field.setAttribute('required', '');
    });

    const isEdit = form.action.includes('/clients/') && form.action !== clientsStoreRoute;
    const url = form.action;

    if (isEdit) {
      formData.append('_method', 'PUT');
    }

    try {
      const response = await fetch(url, {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || csrfToken,
          'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
      });

      if (response.ok) {
        const result = await response.json();

        if (result.success) {
          if (!isEdit && result.client?.id) {
            currentClientId = result.client.id;
            try {
              const clientRes = await fetch(`/clients/${result.client.id}`, {
                headers: {
                  'Accept': 'application/json',
                  'X-Requested-With': 'XMLHttpRequest'
                }
              });
              if (clientRes.ok) {
                const clientData = await clientRes.json();
                await openClientModal('edit', clientData);
                alert('Client created successfully! You can now upload documents.');
              } else {
                alert('Client created successfully!');
                closeClientModal();
                location.reload();
              }
            } catch (error) {
              console.error('Error fetching client:', error);
              alert('Client created successfully!');
              closeClientModal();
              location.reload();
            }
          } else {
            alert('Client updated successfully!');
            closeClientModal();
            location.reload();
          }
        } else {
          alert('Error: ' + (result.message || 'Unknown error'));
        }
      } else {
        const errorData = await response.json();
        if (errorData.errors) {
          let errorMsg = 'Validation errors:\n';
          Object.keys(errorData.errors).forEach(key => {
            errorMsg += errorData.errors[key][0] + '\n';
          });
          alert(errorMsg);
        } else {
          alert('Error saving client: ' + (errorData.message || 'Unknown error'));
        }
      }
    } catch (error) {
      console.error('Error:', error);
      alert('Error saving client: ' + error.message);
    }
  });

  // ============================================================================
  // TABLE FUNCTIONS
  // ============================================================================

  function toggleTableScroll() {
    const table = document.getElementById('clientsTable');
    const wrapper = document.getElementById('tableResponsive');
    if (!table || !wrapper) return;
    const hasHorizontalOverflow = table.offsetWidth > wrapper.offsetWidth;
    const hasVerticalOverflow = table.offsetHeight > wrapper.offsetHeight;
    wrapper.classList.toggle('no-scroll', !hasHorizontalOverflow && !hasVerticalOverflow);
  }

  window.addEventListener('load', toggleTableScroll);
  window.addEventListener('resize', toggleTableScroll);

  function applyFilters() {
    const rows = document.querySelectorAll('tbody tr');
    const activeFilters = {};

    document.querySelectorAll('.column-filter.visible').forEach(filter => {
      const column = filter.dataset.column;
      const value = filter.value.trim().toLowerCase();
      if (value) {
        activeFilters[column] = value;
      }
    });

    rows.forEach(row => {
      let shouldShow = true;

      for (const [column, filterValue] of Object.entries(activeFilters)) {
        const cell = row.querySelector(`td[data-column="${column}"]`);
        if (cell) {
          const cellText = cell.textContent.toLowerCase();
          if (!cellText.includes(filterValue)) {
            shouldShow = false;
            break;
          }
        } else {
          shouldShow = false;
          break;
        }
      }

      row.style.display = shouldShow ? '' : 'none';
    });

    const visibleRows = Array.from(document.querySelectorAll('tbody tr')).filter(row => {
      return row.style.display !== 'none' && !row.style.display.includes('none');
    }).length;
    const recordsFound = document.querySelector('.records-found');
    if (recordsFound && Object.keys(activeFilters).length > 0) {
      recordsFound.textContent = `Records Found - ${visibleRows} of ${clientsTotal} (filtered)`;
    } else if (recordsFound) {
      recordsFound.textContent = `Records Found - ${clientsTotal}`;
    }
  }

  // Escape HTML to prevent XSS
  function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
  }

  function printTable() {
    const table = document.getElementById('clientsTable');
    if (!table) return;

    const headers = [];
    const headerCells = table.querySelectorAll('thead th');
    headerCells.forEach(th => {
      let headerText = '';
      const clone = th.cloneNode(true);
      const filterInput = clone.querySelector('.column-filter');
      if (filterInput) filterInput.remove();
      headerText = clone.textContent.trim();
      if (clone.querySelector('svg')) {
        headerText = '🔔';
      }
      if (headerText) {
        headers.push(headerText);
      }
    });

    const rows = [];
    const tableRows = table.querySelectorAll('tbody tr:not([style*="display: none"])');
    tableRows.forEach(row => {
      if (row.style.display === 'none') return;

      const cells = [];
      const rowCells = row.querySelectorAll('td');
      rowCells.forEach((cell) => {
        let cellContent = '';

        if (cell.classList.contains('bell-cell')) {
          const radio = cell.querySelector('input[type="radio"]');
          cellContent = radio && radio.checked ? '●' : '○';
        } else if (cell.classList.contains('action-cell')) {
          const icons = [];
          if (cell.querySelector('.action-expand')) icons.push('⤢');
          if (cell.querySelector('.action-clock')) icons.push('🕐');
          if (cell.querySelector('.action-ellipsis')) icons.push('⋯');
          cellContent = icons.join(' ');
        } else if (cell.classList.contains('checkbox-cell')) {
          const checkbox = cell.querySelector('input[type="checkbox"]');
          cellContent = checkbox && checkbox.checked ? '✓' : '';
        } else {
          const link = cell.querySelector('a');
          cellContent = link ? link.textContent.trim() : cell.textContent.trim();
        }

        cells.push(cellContent || '-');
      });
      rows.push(cells);
    });

    const headersHTML = headers.map(h => '<th>' + escapeHtml(h) + '</th>').join('');
    const rowsHTML = rows.map(row => {
      const cellsHTML = row.map(cell => {
        const cellText = escapeHtml(String(cell || '-'));
        return '<td>' + cellText + '</td>';
      }).join('');
      return '<tr>' + cellsHTML + '</tr>';
    }).join('');

    const printWindow = window.open('', '_blank', 'width=800,height=600');

    const printHTML = '<!DOCTYPE html>' +
      '<html>' +
      '<head>' +
      '<title>Clients - Print</title>' +
      '<style>' +
      '@page { margin: 1cm; size: A4 landscape; }' +
      'html, body { margin: 0; padding: 0; background: #fff !important; }' +
      'body { font-family: Arial, sans-serif; font-size: 10px; }' +
      'table { width: 100%; border-collapse: collapse; page-break-inside: auto; }' +
      'thead { display: table-header-group; }' +
      'thead th { background-color: #000 !important; color: #fff !important; padding: 8px 5px; text-align: left; border: 1px solid #333; font-weight: normal; -webkit-print-color-adjust: exact; print-color-adjust: exact; }' +
      'tbody tr { page-break-inside: avoid; border-bottom: 1px solid #ddd; }' +
      'tbody tr:nth-child(even) { background-color: #f8f8f8; }' +
      'tbody td { padding: 6px 5px; border: 1px solid #ddd; white-space: nowrap; }' +
      '</style>' +
      '</head>' +
      '<body>' +
      '<table>' +
      '<thead><tr>' + headersHTML + '</tr></thead>' +
      '<tbody>' + rowsHTML + '</tbody>' +
      '</table>' +
      '<scr' + 'ipt>' +
      'window.onload = function() {' +
      '  setTimeout(function() {' +
      '    window.print();' +
      '  }, 100);' +
      '};' +
      'window.onafterprint = function() {' +
      '  window.close();' +
      '};' +
      '</scr' + 'ipt>' +
      '</body>' +
      '</html>';

    if (printWindow) {
      printWindow.document.open();
      printWindow.document.write(printHTML);
      printWindow.document.close();
    }
  }

  function initializeFilters() {
    const printBtn = document.getElementById('printBtn');
    if (printBtn) {
      printBtn.addEventListener('click', printTable);
    }

    document.querySelectorAll('.column-filter').forEach(filter => {
      filter.addEventListener('input', applyFilters);
    });

    const filterToggle = document.getElementById('filterToggle');
    if (filterToggle?.checked) {
      document.querySelectorAll('.column-filter').forEach(filter => {
        filter.classList.add('visible');
        filter.style.display = 'block';
      });
    }
  }

  document.addEventListener('DOMContentLoaded', initializeFilters);
