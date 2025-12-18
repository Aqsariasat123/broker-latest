  // Data initialized in Blade template

  // Toggle Alternate No field visibility based on "On Wattsapp" checkbox
  function setupWaToggle() {
    const waCheckbox = document.getElementById('wa');
    const alternateNoRow = document.getElementById('alternate_no_row');
    if (waCheckbox && alternateNoRow) {
      // Function to toggle visibility
      const toggleAlternateNo = function() {
        if (this.checked) {
          // Hide Alternate No if On Wattsapp is checked
          alternateNoRow.style.display = 'none';
        } else {
          // Show Alternate No if On Wattsapp is unchecked
          alternateNoRow.style.display = '';
        }
      };
      
      // Remove existing listener and add new one
      waCheckbox.removeEventListener('change', toggleAlternateNo);
      waCheckbox.addEventListener('change', toggleAlternateNo);
      
      // Set initial state based on checkbox
      toggleAlternateNo.call(waCheckbox);
    }
  }

  document.getElementById('addClientBtn').addEventListener('click', () => openClientModal('add'));
  document.getElementById('columnBtn').addEventListener('click', () => openColumnModal());
  document.getElementById('filterToggle').addEventListener('change', function() {
    const filtersVisible = this.checked;
    const columnFilters = document.querySelectorAll('.column-filter');
    
    columnFilters.forEach(filter => {
      if (filtersVisible) {
        filter.classList.add('visible');
        filter.style.display = 'block';
      } else {
        filter.classList.remove('visible');
        filter.style.display = 'none';
        filter.value = ''; // Clear filter values when hiding
        // Reset table rows visibility
        document.querySelectorAll('tbody tr').forEach(row => {
          row.style.display = '';
        });
      }
    });
  });
  
  // Handle client type change to show/hide fields - defined globally
  function handleClientTypeChange() {
      const clientTypeSelect = document.getElementById('client_type');
      if (!clientTypeSelect) return;
      
      const selectedType = clientTypeSelect.value;
      const isIndividual = selectedType === 'Individual';
      const isBusiness = ['Business', 'Company', 'Organization'].includes(selectedType);
      
      // Find the form container (could be modal or page view)
      const formContainer = clientTypeSelect.closest('form') || clientTypeSelect.closest('.modal-body') || document;
      
      if (!selectedType) {
        // Hide all conditional fields if no type selected
        formContainer.querySelectorAll('[data-field-type="Individual"]').forEach(field => {
          field.style.display = 'none';
        });
        formContainer.querySelectorAll('[data-field-type="Business"]').forEach(field => {
          field.style.display = 'none';
        });
        // Update labels to default
        const dobDorLabel = document.getElementById('dob_dor_label');
        if (dobDorLabel) {
          dobDorLabel.textContent = 'DOB/DOR';
        }
        const ninBcrnLabel = document.getElementById('nin_bcrn_label');
        if (ninBcrnLabel) {
          ninBcrnLabel.textContent = 'NIN/BCRN';
        }
        return;
      }
      
      // Show/hide fields based on client type
      // Hide all conditional fields first
      formContainer.querySelectorAll('[data-field-type="Individual"], [data-field-type="business"]').forEach(field => {
        field.style.display = 'none';
      });
      
      // Then show only the fields for the selected type
      // Both Individual and Business show the same fields (Individual fields)
      if (isIndividual || isBusiness) {
        formContainer.querySelectorAll('[data-field-type="Individual"]').forEach(field => {
          field.style.display = '';
        });
        // Hide all business-specific fields
        formContainer.querySelectorAll('[data-field-type="business"]').forEach(field => {
          field.style.display = 'none';
        });
      }
      
      // Show/hide DOB/DOR field (always shown when type is selected)
      const dobDorRow = formContainer.querySelector('#dob_dor_row') || document.getElementById('dob_dor_row');
      if (dobDorRow) {
        dobDorRow.style.display = (isIndividual || isBusiness) ? '' : 'none';
        // Age field only for Individual, not Business
        const ageField = dobDorRow.querySelector('.dob_age_field');
        if (ageField) {
          ageField.style.display = isIndividual ? '' : 'none';
        }
      }
      
      // Update labels dynamically
      const dobDorLabel = formContainer.querySelector('#dob_dor_label') || document.getElementById('dob_dor_label');
      if (dobDorLabel) {
        if (isIndividual) {
          dobDorLabel.textContent = 'DOB';
        } else if (isBusiness) {
          dobDorLabel.textContent = 'DOB'; // Business also shows DOB
        } else {
          dobDorLabel.textContent = 'DOB/DOR';
        }
      }
      
      // Show/hide NIN/BCRN field
      const ninBcrnRow = formContainer.querySelector('[data-field-type="Individual"] #nin_bcrn')?.closest('.detail-row');
      const bcrnBusinessRow = formContainer.querySelector('[data-field-type="business"] #bcrn_business_main')?.closest('.detail-row');
      
      if (ninBcrnRow) {
        ninBcrnRow.style.display = (isIndividual || isBusiness) ? '' : 'none';
      }
      if (bcrnBusinessRow) {
        bcrnBusinessRow.style.display = 'none'; // Always hide business BCRN field
      }
      
      const ninBcrnLabel = formContainer.querySelector('#nin_bcrn_label') || document.getElementById('nin_bcrn_label');
      if (ninBcrnLabel) {
        ninBcrnLabel.textContent = 'NIN';
      }
      
      // Sync values between duplicate fields when type changes
      if (isBusiness) {
        // Sync BCRN fields
        const ninBcrn = document.getElementById('nin_bcrn');
        const bcrnBusiness = document.getElementById('bcrn_business_main');
        if (ninBcrn && bcrnBusiness) {
          if (bcrnBusiness.value && !ninBcrn.value) {
            ninBcrn.value = bcrnBusiness.value;
          } else if (ninBcrn.value && !bcrnBusiness.value) {
            bcrnBusiness.value = ninBcrn.value;
          }
        }
        
        // Sync mobile no
        const mobileNoIndividual = document.getElementById('mobile_no_individual');
        const mobileNoBusiness = document.getElementById('mobile_no_business');
        if (mobileNoIndividual && mobileNoBusiness) {
          if (mobileNoBusiness.value && !mobileNoIndividual.value) {
            mobileNoIndividual.value = mobileNoBusiness.value;
          } else if (mobileNoIndividual.value && !mobileNoBusiness.value) {
            mobileNoBusiness.value = mobileNoIndividual.value;
          }
        }
      }
      
      // Update required fields
      const firstNameInput = formContainer.querySelector('#first_name') || document.getElementById('first_name');
      const surnameInput = formContainer.querySelector('#surname') || document.getElementById('surname');
      const businessNameInput = formContainer.querySelector('#business_name') || document.getElementById('business_name');
      
      if (isIndividual || isBusiness) {
        // Both Individual and Business use the same fields, so same validation
        if (firstNameInput) {
          firstNameInput.required = true;
        }
        if (surnameInput) {
          surnameInput.required = true;
        }
        if (businessNameInput) {
          businessNameInput.required = false;
        }
      } else {
        // Reset required states if no type selected
        if (firstNameInput) firstNameInput.required = false;
        if (surnameInput) surnameInput.required = false;
        if (businessNameInput) businessNameInput.required = false;
      }
    }
  
  // Initialize filter visibility based on toggle state
  document.addEventListener('DOMContentLoaded', function() {
    const filterToggle = document.getElementById('filterToggle');
    if (filterToggle && filterToggle.checked) {
      document.querySelectorAll('.column-filter').forEach(filter => {
        filter.classList.add('visible');
        filter.style.display = 'block';
      });
    }
    
    // Helper function to sync duplicate fields
    function syncDuplicateFields(primaryId, duplicateIds) {
      const primary = document.getElementById(primaryId);
      if (!primary) return;
      
      duplicateIds.forEach(dupId => {
        const duplicate = document.getElementById(dupId);
        if (duplicate) {
          if (duplicate.value && !primary.value) {
            primary.value = duplicate.value;
          } else if (primary.value && !duplicate.value) {
            duplicate.value = primary.value;
          }
        }
      });
    }
    
    // Initialize on page load - show fields based on selected type
    // Since Individual is selected by default, show Individual fields immediately
    handleClientTypeChange();
    
    // Also check fields on any form that might be created dynamically
    setTimeout(() => {
      handleClientTypeChange();
    }, 100);
    
    // Listen for client type changes
    const clientTypeSelect = document.getElementById('client_type');
    if (clientTypeSelect) {
      clientTypeSelect.addEventListener('change', handleClientTypeChange);
      
      // Also handle changes in modal and page view forms (delegated event)
      document.addEventListener('change', function(e) {
        if (e.target && e.target.id === 'client_type') {
          handleClientTypeChange();
        }
      });
    }
  });
  
  // Radio button selection highlighting
  document.querySelectorAll('.action-radio').forEach(radio => {
    radio.addEventListener('change', function() {
      // Remove previous selections
      document.querySelectorAll('.action-radio').forEach(r => {
        r.classList.remove('selected');
      });
      // Add selected class to current
      if (this.checked) {
        this.classList.add('selected');
      }
    });
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
  


  async function openEditClient(id){
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
      
      // Set client name in header
      const clientName = `${client.first_name || ''} ${client.surname || ''}`.trim() || 'Unknown';
      document.getElementById('clientPageName').textContent = clientName;
      document.getElementById('clientPageTitle').textContent = 'Client';
      
      populateClientDetailsModal(client);
      
      // Hide table view, show page view
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
    // Use page view content if available, otherwise fall back to modal
    const content = document.getElementById('clientDetailsContent');
    if (!content) return;

    // Calculate age from DOB
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

    // Format date
    function formatDate(dateStr) {
      if (!dateStr) return '';
      const date = new Date(dateStr);
      const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
      return `${date.getDate()}-${months[date.getMonth()]}-${String(date.getFullYear()).slice(-2)}`;
    }

    // Calculate days until expiry (for ID expiry)
    function daysUntilExpiry(dateStr) {
      if (!dateStr) return '';
      const expiryDate = new Date(dateStr);
      const today = new Date();
      const diffTime = expiryDate - today;
      const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
      return diffDays;
    }

    const dob = client.dob_dor ? formatDate(client.dob_dor) : '';
    const dobAge = client.dob_dor ? calculateAge(client.dob_dor) : '';
    const idExpiry = client.id_expiry_date ? formatDate(client.id_expiry_date) : '';
    const idExpiryDays = client.id_expiry_date ? daysUntilExpiry(client.id_expiry_date) : '';
    const photoUrl = client.image ? (client.image.startsWith('http') ? client.image : `/storage/${client.image}`) : '';

    // Column 1 (Leftmost): CUSTOMER DETAILS only
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

    // Column 2 (Second from Left): CONTACT DETAILS only
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

    // Column 3 (Second from Right): ADDRESS DETAILS only
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

    // Column 4 (Rightmost): OTHER DETAILS (Registration info)
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

    // Render columns in order: CUSTOMER, CONTACT, ADDRESS, OTHER DETAILS (left to right)
    content.innerHTML = col1 + col2 + col3 + col4;

    // Load documents from documents table
    const documentsList = document.getElementById('clientDocumentsList');
    if (documentsList) {
      let docsHTML = '';
      if (client.documents && client.documents.length > 0) {
        client.documents.forEach(doc => {
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
      }
      documentsList.innerHTML = docsHTML || '<div style="color:#999; font-size:12px;">No documents uploaded</div>';
    }

    // Set edit button action
     const editBtn = document.getElementById('editClientFromPageBtn');
    if (editBtn) {
      editBtn.onclick = function() {
        openEditClient(currentClientId);
      };
    }

    // Tab navigation - make tabs clickable to navigate to respective pages
    document.querySelectorAll('.nav-tab').forEach(tab => {
      tab.addEventListener('click', function(e) {
        e.preventDefault();
        const tabType = this.getAttribute('data-tab');
        const clientId = currentClientId;
        
        if (!clientId) return;
        
        // Close the modal first
        closeClientDetailsModal();
        
        // Get URL from data-url attribute
        const baseUrl = this.getAttribute('data-url');
        if (!baseUrl) return;
        
        // Navigate to the appropriate page with client filter
        const url = baseUrl + '?client_id=' + clientId;
          window.location.href = url;
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

  // Photo upload handler
  async function handlePhotoUpload(event) {
    const file = event.target.files[0];
    if (!file) return;

    if (!currentClientId) {
      alert('No client selected');
      return;
    }

    // Validate passport photo dimensions before upload
    const img = new Image();
    const reader = new FileReader();
    
    reader.onload = async function(e) {
      img.onload = async function() {
        const width = img.width;
        const height = img.height;
        
        // Passport photo standard dimensions (in pixels at 300 DPI):
        // Square format: 600x600 pixels (2x2 inches) - most common
        // Rectangular format: 413x531 pixels (35x45 mm)
        // Allow some tolerance: ±50 pixels for width/height
        const minWidth = 350;
        const maxWidth = 650;
        const minHeight = 350;
        const maxHeight = 650;
        
        // Check if dimensions are within acceptable range
        if (width < minWidth || width > maxWidth || height < minHeight || height > maxHeight) {
          alert('Photo must be passport size (approximately 600x600 pixels or 413x531 pixels).\nCurrent dimensions: ' + width + 'x' + height + ' pixels.\nPlease upload a passport-size photo.');
          event.target.value = '';
          return;
        }
        
        // Check aspect ratio (should be close to 1:1 for square or 0.78:1 for rectangular)
        const aspectRatio = width / height;
        const squareRatio = 1.0; // 1:1 for square passport photos
        const rectRatio = 0.78; // 35:45 mm ratio
        const tolerance = 0.15; // Allow 15% tolerance
        
        const isSquare = Math.abs(aspectRatio - squareRatio) <= tolerance;
        const isRectangular = Math.abs(aspectRatio - rectRatio) <= tolerance;
        
        if (!isSquare && !isRectangular) {
          alert('Photo must have passport size aspect ratio (square 1:1 or rectangular 35:45mm).\nCurrent ratio: ' + aspectRatio.toFixed(2) + ':1\nPlease upload a passport-size photo.');
          event.target.value = '';
          return;
        }
        
        // If validation passes, proceed with upload
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
            // Reload client data to update the photo
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

        // Reset input
        event.target.value = '';
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
    // Clear preview
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
    const fileSize = (file.size / 1024 / 1024).toFixed(2); // Size in MB

    // Show file info
    previewInfo.innerHTML = `<strong>File:</strong> ${fileName}<br><strong>Size:</strong> ${fileSize} MB<br><strong>Type:</strong> ${fileType || 'Unknown'}`;

    // Preview based on file type
    if (fileType.startsWith('image/')) {
      // Image preview
      const reader = new FileReader();
      reader.onload = function(e) {
        previewContent.innerHTML = `<img src="${e.target.result}" alt="Document Preview" style="max-width:100%; max-height:400px; border:1px solid #ddd; border-radius:4px;">`;
      };
      reader.readAsDataURL(file);
    } else if (fileType === 'application/pdf') {
      // PDF preview using embed
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
      // For other file types (DOC, DOCX), show icon
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
        // Reload client data to update documents
        const clientRes = await fetch(`/clients/${currentClientId}`, {
          headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
          }
        });
        const client = await clientRes.json();
        
        // Update documents in both modals
        updateDocumentsList(client);
        
        // If client details modal is open, refresh it
        const clientDetailsModal = document.getElementById('clientDetailsModal');
        if (clientDetailsModal && clientDetailsModal.classList.contains('show')) {
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

  // Update documents list in Edit Client modal
  function updateDocumentsList(client) {
    const editDocumentsList = document.getElementById('editClientDocumentsList');
    if (editDocumentsList) {
      let docsHTML = '';
      // Load from documents table
      if (client.documents && client.documents.length > 0) {
        client.documents.forEach(doc => {
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
      }
      editDocumentsList.innerHTML = docsHTML || '<div style="color:#999; font-size:12px;">No documents uploaded</div>';
    }
  }

  function editClientFromModal() {
    if (currentClientId) {
      closeClientDetailsModal();
      openEditClient(currentClientId);
    }
  }

  // Calculate age from DOB
  function calculateAgeFromDOB() {
    const dobInput = document.getElementById('dob_dor');
    const ageInput = document.getElementById('dob_age');
    if (dobInput && ageInput && dobInput.value) {
      const birthDate = new Date(dobInput.value);
      const today = new Date();
      let age = today.getFullYear() - birthDate.getFullYear();
      const monthDiff = today.getMonth() - birthDate.getMonth();
      if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
        age--;
      }
      ageInput.value = age;
    } else if (ageInput) {
      ageInput.value = '';
    }
  }

  // Calculate days until ID expiry
  function calculateIDExpiryDays() {
    const expiryInput = document.getElementById('id_expiry_date');
    const daysInput = document.getElementById('id_expiry_days');
    if (expiryInput && daysInput && expiryInput.value) {
      const expiryDate = new Date(expiryInput.value);
      const today = new Date();
      const diffTime = expiryDate - today;
      const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
      daysInput.value = diffDays;
    } else if (daysInput) {
      daysInput.value = '';
    }
  }

  // Preview client photo and validate passport size
  function previewClientPhoto(event) {
    const file = event.target.files[0];
    const preview = document.getElementById('clientPhotoImg');
    const previewContainer = document.getElementById('clientPhotoPreview');
    const imageInput = event.target;
    
    if (file && preview && previewContainer) {
      // Validate passport photo dimensions
      const img = new Image();
      const reader = new FileReader();
      
      reader.onload = function(e) {
        img.onload = function() {
          const width = img.width;
          const height = img.height;
          
          // Passport photo standard dimensions (in pixels at 300 DPI):
          // Square format: 600x600 pixels (2x2 inches) - most common
          // Rectangular format: 413x531 pixels (35x45 mm)
          // Allow some tolerance: ±50 pixels for width/height
          const minWidth = 350;
          const maxWidth = 650;
          const minHeight = 350;
          const maxHeight = 650;
          
          // Check if dimensions are within acceptable range
          if (width < minWidth || width > maxWidth || height < minHeight || height > maxHeight) {
            alert('Photo must be passport size (approximately 600x600 pixels or 413x531 pixels).\nCurrent dimensions: ' + width + 'x' + height + ' pixels.\nPlease upload a passport-size photo.');
            imageInput.value = '';
            preview.src = '';
            preview.style.display = 'none';
            if (previewContainer.querySelector('span')) {
              previewContainer.querySelector('span').style.display = 'block';
            }
            return;
          }
          
          // Check aspect ratio (should be close to 1:1 for square or 0.78:1 for rectangular)
          const aspectRatio = width / height;
          const squareRatio = 1.0; // 1:1 for square passport photos
          const rectRatio = 0.78; // 35:45 mm ratio
          const tolerance = 0.15; // Allow 15% tolerance
          
          const isSquare = Math.abs(aspectRatio - squareRatio) <= tolerance;
          const isRectangular = Math.abs(aspectRatio - rectRatio) <= tolerance;
          
          if (!isSquare && !isRectangular) {
            alert('Photo must have passport size aspect ratio (square 1:1 or rectangular 35:45mm).\nCurrent ratio: ' + aspectRatio.toFixed(2) + ':1\nPlease upload a passport-size photo.');
            imageInput.value = '';
            preview.src = '';
            preview.style.display = 'none';
            if (previewContainer.querySelector('span')) {
              previewContainer.querySelector('span').style.display = 'block';
            }
            return;
          }
          
          // If validation passes, show preview
          preview.src = e.target.result;
          preview.style.display = 'block';
          if (previewContainer.querySelector('span')) {
            previewContainer.querySelector('span').style.display = 'none';
          }
        };
        img.src = e.target.result;
      };
      reader.readAsDataURL(file);
    }
  }

  function openClientModal(mode, client = null){
    const modal = document.getElementById('clientModal');
    const modalForm = modal.querySelector('form');
    const formMethod = document.getElementById('clientFormMethod');
    const deleteBtn = document.getElementById('clientDeleteBtn');

    // Don't force all fields visible - let handleClientTypeChange handle it based on client type
    // This allows conditional fields to be properly hidden/shown

    if (mode === 'add') {
      modalForm.action = clientsStoreRoute;
      formMethod.innerHTML = '';
      deleteBtn.style.display = 'none';
      modalForm.reset();
      
      // Set Individual as default client type
      const clientTypeSelect = document.getElementById('client_type');
      if (clientTypeSelect && !clientTypeSelect.value) {
        clientTypeSelect.value = 'Individual';
      }
      
      // Make photo required for new clients
      const imageInput = document.getElementById('image');
      if (imageInput) imageInput.required = true;
      // Clear checkboxes
      document.getElementById('married').checked = false;
      document.getElementById('pep').checked = false;
      const waCheckbox = document.getElementById('wa');
      if (waCheckbox) {
        waCheckbox.checked = false;
        // Show Alternate No field by default (since wa is unchecked)
        const alternateNoRow = document.getElementById('alternate_no_row');
        if (alternateNoRow) {
          alternateNoRow.style.display = '';
        }
      }
      document.getElementById('has_vehicle').checked = false;
      document.getElementById('has_house').checked = false;
      document.getElementById('has_business').checked = false;
      document.getElementById('has_boat').checked = false;
      // Clear photo preview
      const photoImg = document.getElementById('clientPhotoImg');
      const photoPreview = document.getElementById('clientPhotoPreview');
      if (photoImg) photoImg.style.display = 'none';
      if (photoPreview) {
        const photoSpan = photoPreview.querySelector('span');
        if (photoSpan) photoSpan.style.display = 'block';
      }
      // Clear calculated fields
      document.getElementById('dob_age').value = '';
      document.getElementById('id_expiry_days').value = '';
      // Clear documents list
      const editDocumentsList = document.getElementById('editClientDocumentsList');
      if (editDocumentsList) editDocumentsList.innerHTML = '<div style="color:#999; font-size:12px;">No documents uploaded</div>';
      
      // Hide "Add Document" buttons in add mode (no client ID yet)
      const addDocumentBtns = ['addDocumentBtn1', 'addDocumentBtn2', 'addDocumentBtn3'];
      addDocumentBtns.forEach(btnId => {
        const btn = document.getElementById(btnId);
        if (btn) btn.style.display = 'none';
      });
      
      // Re-setup WA toggle after form reset
      setupWaToggle();
    } else {
      modalForm.action = `/clients/${currentClientId}`;
      formMethod.innerHTML = `@method('PUT')`;
      deleteBtn.style.display = 'inline-block';

      const fields = ['salutation','first_name','other_names','surname','client_type','nin_bcrn','dob_dor','id_expiry_date','passport_no','mobile_no','alternate_no','email_address','occupation','employer','income_source','monthly_income','source','source_name','agent','agency','status','signed_up','location','district','island','country','po_box_no','spouses_name','contact_person','pep_comment','notes'];
      fields.forEach(k => {
        const el = document.getElementById(k);
        if (!el) return;
        if (el.type === 'checkbox') {
          el.checked = !!client[k];
          // If this is the wa checkbox, update alternate_no_row visibility
          if (k === 'wa' && el.id === 'wa') {
            const alternateNoRow = document.getElementById('alternate_no_row');
            if (alternateNoRow) {
              alternateNoRow.style.display = el.checked ? 'none' : '';
            }
          }
        } else if (el.tagName === 'SELECT' || el.tagName === 'INPUT' || el.tagName === 'TEXTAREA') {
          if (el.type === 'date' && client[k]) {
            // Format date for date inputs (YYYY-MM-DD)
            const date = new Date(client[k]);
            el.value = date.toISOString().split('T')[0];
          } else {
            el.value = client[k] ?? '';
          }
        }
      });
      document.getElementById('married').checked = !!client.married;
      document.getElementById('pep').checked = !!client.pep;
      const waCheckboxEdit = document.getElementById('wa');
      if (waCheckboxEdit) {
        waCheckboxEdit.checked = !!client.wa;
        // Update alternate_no_row visibility based on wa checkbox state
        const alternateNoRow = document.getElementById('alternate_no_row');
        if (alternateNoRow) {
          alternateNoRow.style.display = waCheckboxEdit.checked ? 'none' : '';
        }
      }
      document.getElementById('has_vehicle').checked = !!client.has_vehicle;
      document.getElementById('has_house').checked = !!client.has_house;
      document.getElementById('has_business').checked = !!client.has_business;
      document.getElementById('has_boat').checked = !!client.has_boat;
      
      // Re-setup WA toggle after populating form data
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
        // Photo not required if existing image exists
        if (imageInput) imageInput.required = false;
      } else {
        // Photo required if no existing image
        if (imageInput) imageInput.required = true;
      }

      // Update documents list
      updateDocumentsList(client);

      // Calculate age and expiry days
      calculateAgeFromDOB();
      calculateIDExpiryDays();
      
      // After populating form, update field visibility based on client type
      setTimeout(() => {
        handleClientTypeChange();
      }, 150);
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

    // Setup toggle on page load
    setupWaToggle();

    // Call handleClientTypeChange after modal opens to properly show/hide fields based on selected type
    // Also ensure the change listener is attached
    const clientTypeSelect = document.getElementById('client_type');
    if (clientTypeSelect) {
      // If no value is set (in add mode), default to Individual
      if (mode === 'add' && !clientTypeSelect.value) {
        clientTypeSelect.value = 'Individual';
      }
      
      // Remove existing listeners to avoid duplicates
      const newClientTypeSelect = clientTypeSelect.cloneNode(true);
      clientTypeSelect.parentNode.replaceChild(newClientTypeSelect, clientTypeSelect);
      
      newClientTypeSelect.addEventListener('change', handleClientTypeChange);
      
      // Call immediately and after a delay to ensure fields are shown
      handleClientTypeChange();
      setTimeout(() => {
        handleClientTypeChange();
      }, 200);
    }

    // Clone form content from modal to page view
    const pageFormContainer = document.getElementById('clientFormPageContent');
    // Get the page form specifically from the page view container (not the modal)
    const pageForm = pageFormContainer ? pageFormContainer.querySelector('form') : null;
    const formContentDiv = pageForm ? pageForm.querySelector('div[style*="padding:12px"]') : null;
    
    if (modalForm && pageForm && pageFormContainer && formContentDiv) {
      // Get the modal body content
      const modalBody = modalForm.querySelector('.modal-body');
      if (modalBody) {
        // Clear form content div completely first
        formContentDiv.innerHTML = '';
        
        // Clone the grid container and all its content - only clone once
        const gridContainer = modalBody.querySelector('div[style*="grid-template-columns"]');
        if (gridContainer && !formContentDiv.querySelector('div[style*="grid-template-columns"]')) {
          const clonedGrid = gridContainer.cloneNode(true);
          formContentDiv.appendChild(clonedGrid);
        }
        
        // Clone documents section - find it in modal body and place in separate card
        const editDocumentsList = modalBody.querySelector('#editClientDocumentsList');
        const editFormDocumentsSection = document.getElementById('editFormDocumentsSection');
        if (editDocumentsList && editFormDocumentsSection) {
          // Find the parent container (Documents section)
          let documentsSection = editDocumentsList.closest('div[style*="margin-top"]') || 
                                 editDocumentsList.parentElement?.parentElement;
          if (documentsSection) {
            // Clear existing content
            editFormDocumentsSection.innerHTML = '';
            
            // Clone the documents section content
            const clonedDocs = documentsSection.cloneNode(true);
            
            // Extract the content (h4, documents list, buttons)
            const docsTitle = clonedDocs.querySelector('h4');
            const docsList = clonedDocs.querySelector('#editClientDocumentsList');
            const docsButtons = clonedDocs.querySelector('div[style*="justify-content:flex-end"]');
            
            // Rebuild in the card structure
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
              const buttonsClone = docsButtons.cloneNode(true);
              editFormDocumentsSection.appendChild(buttonsClone);
            }
            
            // Show the documents section
            editFormDocumentsSection.style.display = 'block';
          }
        }
        
        // Update form attributes
        pageForm.method = 'POST';
        pageForm.action = modalForm.action;
        pageForm.enctype = 'multipart/form-data';
        
        // Update method field
        const pageMethodDiv = pageForm.querySelector('#clientFormMethod');
        if (pageMethodDiv && formMethod) {
          pageMethodDiv.innerHTML = formMethod.innerHTML;
        }
        
        // If editing, populate the cloned form fields with client data
        if (mode === 'edit' && client) {
          const fields = ['salutation','first_name','other_names','surname','client_type','nin_bcrn','dob_dor','id_expiry_date','passport_no','mobile_no','alternate_no','email_address','occupation','employer','income_source','monthly_income','source','source_name','agent','agency','status','signed_up','location','district','island','country','po_box_no','spouses_name','contact_person','pep_comment','notes','designation'];
          fields.forEach(k => {
            const el = formContentDiv.querySelector(`#${k}`);
            if (!el) return;
            if (el.type === 'checkbox') {
              el.checked = !!client[k];
            } else if (el.tagName === 'SELECT' || el.tagName === 'INPUT' || el.tagName === 'TEXTAREA') {
              if (el.type === 'date' && client[k]) {
                // Format date for date inputs (YYYY-MM-DD)
                const date = new Date(client[k]);
                el.value = date.toISOString().split('T')[0];
            } else {
              el.value = client[k] ?? '';
            }
          }
          });
          
          // Handle business_name for business clients
          const businessNameInput = formContentDiv.querySelector('#business_name');
          if (businessNameInput && ['Business', 'Company', 'Organization'].includes(client.client_type)) {
            businessNameInput.value = client.client_name || '';
          }
          
          // Set checkboxes in cloned form
          const marriedCheckbox = formContentDiv.querySelector('#married');
          const pepCheckbox = formContentDiv.querySelector('#pep');
          const waCheckbox = formContentDiv.querySelector('#wa');
          const hasVehicleCheckbox = formContentDiv.querySelector('#has_vehicle');
          const hasHouseCheckbox = formContentDiv.querySelector('#has_house');
          const hasBusinessCheckbox = formContentDiv.querySelector('#has_business');
          const hasBoatCheckbox = formContentDiv.querySelector('#has_boat');
          
          if (marriedCheckbox) marriedCheckbox.checked = !!client.married;
          if (pepCheckbox) pepCheckbox.checked = !!client.pep;
          if (waCheckbox) waCheckbox.checked = !!client.wa;
          if (hasVehicleCheckbox) hasVehicleCheckbox.checked = !!client.has_vehicle;
          if (hasHouseCheckbox) hasHouseCheckbox.checked = !!client.has_house;
          if (hasBusinessCheckbox) hasBusinessCheckbox.checked = !!client.has_business;
          if (hasBoatCheckbox) hasBoatCheckbox.checked = !!client.has_boat;
          
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
            // Photo not required if existing image exists
            if (imageInput) imageInput.required = false;
          } else {
            // Photo required if no existing image
            if (imageInput) imageInput.required = true;
          }
          
          // Calculate age and expiry days for cloned form
          const dobInput = formContentDiv.querySelector('#dob_dor');
          const ageInput = formContentDiv.querySelector('#dob_age');
          const expiryInput = formContentDiv.querySelector('#id_expiry_date');
          const daysInput = formContentDiv.querySelector('#id_expiry_days');
          
          if (dobInput && ageInput && dobInput.value) {
            const birthDate = new Date(dobInput.value);
            const today = new Date();
            let age = today.getFullYear() - birthDate.getFullYear();
            const monthDiff = today.getMonth() - birthDate.getMonth();
            if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
              age--;
            }
            ageInput.value = age;
          }
          
          if (expiryInput && daysInput && expiryInput.value) {
            const expiryDate = new Date(expiryInput.value);
            const today = new Date();
            const diffTime = expiryDate - today;
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
            daysInput.value = diffDays;
          }
          
          // Toggle Alternate No field visibility based on "On Wattsapp" checkbox in cloned form
          if (waCheckbox) {
            const alternateNoRow = formContentDiv.querySelector('#alternate_no_row');
            if (alternateNoRow) {
              if (waCheckbox.checked) {
                alternateNoRow.style.display = 'none';
              } else {
                alternateNoRow.style.display = '';
              }
            }
          }
          
          // Attach event listeners to cloned form elements
          const clonedDobInput = formContentDiv.querySelector('#dob_dor');
          const clonedExpiryInput = formContentDiv.querySelector('#id_expiry_date');
          if (clonedDobInput) {
            clonedDobInput.addEventListener('change', function() {
              const dobInput = formContentDiv.querySelector('#dob_dor');
              const ageInput = formContentDiv.querySelector('#dob_age');
              if (dobInput && ageInput && dobInput.value) {
                const birthDate = new Date(dobInput.value);
                const today = new Date();
                let age = today.getFullYear() - birthDate.getFullYear();
                const monthDiff = today.getMonth() - birthDate.getMonth();
                if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
                  age--;
                }
                ageInput.value = age;
              }
            });
          }
          if (clonedExpiryInput) {
            clonedExpiryInput.addEventListener('change', function() {
              const expiryInput = formContentDiv.querySelector('#id_expiry_date');
              const daysInput = formContentDiv.querySelector('#id_expiry_days');
              if (expiryInput && daysInput && expiryInput.value) {
                const expiryDate = new Date(expiryInput.value);
                const today = new Date();
                const diffTime = expiryDate - today;
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                daysInput.value = diffDays;
              }
            });
          }
          
          // Handle client type change for cloned form
          function handleClientTypeChangeInForm(container) {
            const clientTypeSelect = container.querySelector('#client_type');
            if (!clientTypeSelect) return;
            
            const selectedType = clientTypeSelect.value;
            const isIndividual = selectedType === 'Individual';
            const isBusiness = ['Business', 'Company', 'Organization'].includes(selectedType);
            
            if (!selectedType) {
              // Hide all conditional fields if no type selected
              container.querySelectorAll('[data-field-type="individual"]').forEach(field => {
                field.style.display = 'none';
              });
              container.querySelectorAll('[data-field-type="business"]').forEach(field => {
                field.style.display = 'none';
              });
              return;
            }
            
            // Hide all conditional fields first
            container.querySelectorAll('[data-field-type="individual"], [data-field-type="business"]').forEach(field => {
              field.style.display = 'none';
            });
            
            // Then show only the fields for the selected type
            // Both Individual and Business show the same fields (Individual fields)
            if (isIndividual || isBusiness) {
              container.querySelectorAll('[data-field-type="individual"]').forEach(field => {
                field.style.display = '';
              });
              // Hide all business-specific fields
              container.querySelectorAll('[data-field-type="business"]').forEach(field => {
                field.style.display = 'none';
              });
            }
            
            // Show/hide DOB/DOR field (always shown when type is selected)
            const dobDorRow = container.querySelector('#dob_dor_row');
            if (dobDorRow) {
              dobDorRow.style.display = (isIndividual || isBusiness) ? '' : 'none';
              // Age field only for Individual, not Business
              const ageField = dobDorRow.querySelector('.dob_age_field');
              if (ageField) {
                ageField.style.display = isIndividual ? '' : 'none';
              }
            }
            
            // Update labels dynamically
            const dobDorLabel = container.querySelector('#dob_dor_label');
            if (dobDorLabel) {
              if (isIndividual) {
                dobDorLabel.textContent = 'DOB';
              } else if (isBusiness) {
                dobDorLabel.textContent = 'DOB'; // Business also shows DOB
              } else {
                dobDorLabel.textContent = 'DOB/DOR';
              }
            }
            
            const ninBcrnLabel = container.querySelector('#nin_bcrn_label');
            if (ninBcrnLabel) {
              ninBcrnLabel.textContent = 'NIN'; // Both use NIN
            }
            
            // Show/hide NIN/BCRN field
            const ninBcrnRowInContainer = container.querySelector('[data-field-type="individual"] #nin_bcrn')?.closest('.detail-row');
            if (ninBcrnRowInContainer) {
              ninBcrnRowInContainer.style.display = (isIndividual || isBusiness) ? '' : 'none';
            }
            
            // Note: No sections to show/hide anymore - all fields are in the grid
            
            // Update required fields
            const firstNameInput = container.querySelector('#first_name');
            const surnameInput = container.querySelector('#surname');
            const businessNameInput = container.querySelector('#business_name');
            
            if (isIndividual || isBusiness) {
              // Both Individual and Business use the same fields, so same validation
              if (firstNameInput) {
                firstNameInput.required = true;
              }
              if (surnameInput) {
                surnameInput.required = true;
              }
              if (businessNameInput) {
                businessNameInput.required = false;
              }
            } else {
              // Reset required states if no type selected
              if (firstNameInput) firstNameInput.required = false;
              if (surnameInput) surnameInput.required = false;
              if (businessNameInput) businessNameInput.required = false;
            }
          }
          
          // Attach client type change listener to cloned form
          const clonedClientTypeSelect = formContentDiv.querySelector('#client_type');
          if (clonedClientTypeSelect) {
            // If no value is set (in add mode), default to Individual
            if (mode === 'add' && !clonedClientTypeSelect.value) {
              clonedClientTypeSelect.value = 'Individual';
            }
            
            clonedClientTypeSelect.addEventListener('change', function() {
              handleClientTypeChangeInForm(formContentDiv);
            });
            
            // Trigger on initial load after form is populated to show correct fields
            setTimeout(() => {
              handleClientTypeChangeInForm(formContentDiv);
            }, 100);
          } else {
            // Even if no client type select, hide all conditional fields
            formContentDiv.querySelectorAll('[data-field-type="individual"]').forEach(field => {
              field.style.display = 'none';
            });
            formContentDiv.querySelectorAll('[data-field-type="business"]').forEach(field => {
              field.style.display = 'none';
            });
          }
          
          // Attach WA checkbox listener to cloned form
          const clonedWaCheckbox = formContentDiv.querySelector('#wa');
          const clonedAlternateNoRow = formContentDiv.querySelector('#alternate_no_row');
          if (clonedWaCheckbox && clonedAlternateNoRow) {
            clonedWaCheckbox.addEventListener('change', function() {
              if (this.checked) {
                clonedAlternateNoRow.style.display = 'none';
              } else {
                clonedAlternateNoRow.style.display = '';
              }
            });
          }
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
    
    // Ensure "Add Document" buttons are shown/hidden correctly based on mode
    // Use setTimeout to ensure DOM is ready after page content is displayed
    setTimeout(() => {
      if (mode === 'edit') {
        // Show "Add Document" button in edit mode (addDocumentBtn2 is in clientFormPageContent)
        const addDocumentBtn2 = document.getElementById('addDocumentBtn2');
        if (addDocumentBtn2) {
          addDocumentBtn2.style.display = 'inline-block';
        }
      } else {
        // Hide "Add Document" buttons in add mode
        const addDocumentBtn2 = document.getElementById('addDocumentBtn2');
        if (addDocumentBtn2) {
          addDocumentBtn2.style.display = 'none';
        }
      }
    }, 100);
  }

  function closeClientModal(){
    closeClientPageView();
  }

  function deleteClient(){
    if (!currentClientId) return;
    if (!confirm('Delete this client?')) return;
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = `/clients/${currentClientId}`;
    const csrf = document.createElement('input'); csrf.type='hidden'; csrf.name='_token'; csrf.value=csrfToken; form.appendChild(csrf);
    const method = document.createElement('input'); method.type='hidden'; method.name='_method'; method.value='DELETE'; form.appendChild(method);
    document.body.appendChild(form);
    form.submit();
  }

  // Column modal functions
  function openColumnModal(){
    // Mandatory fields that should always be checked
    const mandatoryFields = ['client_name', 'client_type', 'mobile_no', 'source', 'status', 'signed_up', 'clid', 'first_name', 'surname'];
    
    document.getElementById('tableResponsive').classList.add('no-scroll');
    document.querySelectorAll('.column-checkbox').forEach(cb => {
      // Always check mandatory fields, otherwise check if in selectedColumns
      cb.checked = mandatoryFields.includes(cb.value) || selectedColumns.includes(cb.value);
    });
    document.body.style.overflow = 'hidden';
    document.getElementById('columnModal').classList.add('show');
    // Initialize drag and drop after modal is shown
    setTimeout(initDragAndDrop, 100);
  }
  function closeColumnModal(){
    document.getElementById('tableResponsive').classList.remove('no-scroll');
    document.getElementById('columnModal').classList.remove('show');
    document.body.style.overflow = '';
  }
  function selectAllColumns(){ 
    const mandatoryFields = ['client_name', 'client_type', 'mobile_no', 'source', 'status', 'signed_up', 'clid', 'first_name', 'surname'];
    document.querySelectorAll('.column-checkbox').forEach(cb => {
      cb.checked = true;
    });
  }
  function deselectAllColumns(){ 
    const mandatoryFields = ['client_name', 'client_type', 'mobile_no', 'source', 'status', 'signed_up', 'clid', 'first_name', 'surname'];
    document.querySelectorAll('.column-checkbox').forEach(cb => {
      // Don't uncheck mandatory fields
      if (!mandatoryFields.includes(cb.value)) {
        cb.checked = false;
      }
    });
  }

  function saveColumnSettings(){
    // Mandatory fields that should always be included
    const mandatoryFields = ['client_name', 'client_type', 'mobile_no', 'source', 'status', 'signed_up', 'clid', 'first_name', 'surname'];
    
    // Get order from DOM - this preserves the drag and drop order
    const items = Array.from(document.querySelectorAll('#columnSelection .column-item'));
    const order = items.map(item => item.dataset.column);
    const checked = Array.from(document.querySelectorAll('.column-checkbox:checked')).map(n=>n.value);
    
    // Ensure mandatory fields are always included
    mandatoryFields.forEach(field => {
      if (!checked.includes(field)) {
        checked.push(field);
      }
    });
    
    // Maintain order of checked items based on DOM order (drag and drop order)
    const orderedChecked = order.filter(col => checked.includes(col));
    
    const form = document.getElementById('columnForm');
    const existing = form.querySelectorAll('input[name="columns[]"]'); 
    existing.forEach(e=>e.remove());
    
    // Add columns in the order they appear in the DOM (after drag and drop)
    orderedChecked.forEach(c => {
      const i = document.createElement('input'); 
      i.type='hidden'; 
      i.name='columns[]'; 
      i.value=c; 
      form.appendChild(i);
    });
    
    form.submit();
  }

  // Drag and drop functionality
  let draggedElement = null;
  let dragOverElement = null;
  
  // Initialize drag and drop when column modal opens
  function initDragAndDrop() {
    const columnSelection = document.getElementById('columnSelection');
    if (!columnSelection) return;
    
    // Make all column items draggable
    const columnItems = columnSelection.querySelectorAll('.column-item');
    
    columnItems.forEach(item => {
      // Skip if already initialized
      if (item.dataset.dragInitialized === 'true') {
        return;
      }
      item.dataset.dragInitialized = 'true';
      // Prevent checkbox from interfering with drag
      const checkbox = item.querySelector('.column-checkbox');
      if (checkbox) {
        checkbox.addEventListener('mousedown', function(e) {
          e.stopPropagation();
        });
        checkbox.addEventListener('click', function(e) {
          e.stopPropagation();
        });
      }
      
      // Prevent label from interfering with drag
      const label = item.querySelector('label');
      if (label) {
        label.addEventListener('mousedown', function(e) {
          // Only prevent if clicking on the label text, not the checkbox
          if (e.target === label) {
            e.preventDefault();
          }
        });
      }
      
      // Drag start
      item.addEventListener('dragstart', function(e) {
        draggedElement = this;
        this.classList.add('dragging');
        e.dataTransfer.effectAllowed = 'move';
        e.dataTransfer.setData('text/html', this.outerHTML);
        e.dataTransfer.setData('text/plain', this.dataset.column);
      });
      
      // Drag end
      item.addEventListener('dragend', function(e) {
        this.classList.remove('dragging');
        // Remove drag-over from all items
        columnItems.forEach(i => i.classList.remove('drag-over'));
        if (dragOverElement) {
          dragOverElement.classList.remove('drag-over');
          dragOverElement = null;
        }
        draggedElement = null;
      });
      
      // Drag over
      item.addEventListener('dragover', function(e) {
        e.preventDefault();
        e.stopPropagation();
        e.dataTransfer.dropEffect = 'move';
        
        if (draggedElement && this !== draggedElement) {
          // Remove drag-over class from previous element
          if (dragOverElement && dragOverElement !== this) {
            dragOverElement.classList.remove('drag-over');
          }
          
          // Add drag-over class to current element
          this.classList.add('drag-over');
          dragOverElement = this;
          
          const rect = this.getBoundingClientRect();
          const midpoint = rect.top + (rect.height / 2);
          const next = e.clientY > midpoint;
          
          if (next) {
            if (this.nextSibling && this.nextSibling !== draggedElement) {
              this.parentNode.insertBefore(draggedElement, this.nextSibling);
            } else if (!this.nextSibling) {
              this.parentNode.appendChild(draggedElement);
            }
          } else {
            if (this.previousSibling !== draggedElement) {
              this.parentNode.insertBefore(draggedElement, this);
            }
          }
        }
      });
      
      // Drag enter
      item.addEventListener('dragenter', function(e) {
        e.preventDefault();
        if (draggedElement && this !== draggedElement) {
          this.classList.add('drag-over');
        }
      });
      
      // Drag leave
      item.addEventListener('dragleave', function(e) {
        // Only remove if we're actually leaving the element
        if (!this.contains(e.relatedTarget)) {
          this.classList.remove('drag-over');
          if (dragOverElement === this) {
            dragOverElement = null;
          }
        }
      });
      
      // Drop
      item.addEventListener('drop', function(e) {
        e.preventDefault();
        e.stopPropagation();
        this.classList.remove('drag-over');
        dragOverElement = null;
        return false;
      });
    });
  }

  // Preview uploaded document in modal
  function previewUploadedDocument(fileUrl, fileExt, documentName) {
    // Create preview modal
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

  // Preview client photo in modal
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

  // Simple validation
  document.getElementById('clientForm').addEventListener('submit', async function(e){
    e.preventDefault();
    
    const form = this;
    
    // Disable hidden duplicate fields to prevent submission conflicts
    const clientType = form.querySelector('#client_type')?.value;
    const isIndividual = clientType === 'Individual';
    const isBusiness = ['Business', 'Company', 'Organization'].includes(clientType);
    
    // Sync values from visible fields to primary fields and disable hidden duplicates
    if (isBusiness) {
        // Sync business fields
        const businessName = form.querySelector('#business_name');
        const mobileNo = form.querySelector('#mobile_no_business') || form.querySelector('#mobile_no_individual');
      const district = form.querySelector('#district_business') || form.querySelector('#district_business_col3') || form.querySelector('#district');
      const signedUp = form.querySelector('#signed_up_business') || form.querySelector('#signed_up_business_col4') || form.querySelector('#signed_up');
      const agency = form.querySelector('#agency_business') || form.querySelector('#agency');
      const agent = form.querySelector('#agent_business') || form.querySelector('#agent');
      const source = form.querySelector('#source_business') || form.querySelector('#source');
      const sourceName = form.querySelector('#source_name_business') || form.querySelector('#source_name');
      const location = form.querySelector('#location_business') || form.querySelector('#location');
      const island = form.querySelector('#island_business') || form.querySelector('#island');
      const country = form.querySelector('#country_business') || form.querySelector('#country');
      const notes = form.querySelector('#notes_business') || form.querySelector('#notes');
      const alternateNo = form.querySelector('#alternate_no_business') || form.querySelector('#alternate_no');
      const emailAddress = form.querySelector('#email_address_business') || form.querySelector('#email_address');
      const poBox = form.querySelector('#po_box_location') || form.querySelector('#po_box_no');
      const bcrn = form.querySelector('#bcrn_business_main') || form.querySelector('#nin_bcrn');
      const wa = form.querySelector('#wa_business') || form.querySelector('#wa');
      
      // Sync values to primary fields (if they exist)
      if (businessName && form.querySelector('#first_name')) {
        form.querySelector('#first_name').value = businessName.value;
      }
      if (mobileNo && form.querySelector('#mobile_no')) {
        form.querySelector('#mobile_no').value = mobileNo.value;
      }
      
      // Disable all hidden individual fields
      form.querySelectorAll('[data-field-type="individual"] input, [data-field-type="individual"] select, [data-field-type="individual"] textarea').forEach(field => {
        if (field.offsetParent === null) { // Check if hidden
          field.disabled = true;
        }
      });
    } else if (isIndividual) {
      // Disable all hidden business fields
      form.querySelectorAll('[data-field-type="business"] input, [data-field-type="business"] select, [data-field-type="business"] textarea').forEach(field => {
        if (field.offsetParent === null) { // Check if hidden
          field.disabled = true;
        }
      });
    }
    
    // Check required fields
    const req = form.querySelectorAll('[required]:not([disabled])');
    let ok = true;
    req.forEach(f => { if (!String(f.value||'').trim()) { ok = false; f.style.borderColor='red'; } else { f.style.borderColor=''; } });
    if (!ok) { 
      // Re-enable disabled fields
      form.querySelectorAll('input[disabled], select[disabled], textarea[disabled]').forEach(f => f.disabled = false);
      alert('Please fill required fields'); 
      return; 
    }
    
    const formData = new FormData(form);
    
    // Re-enable disabled fields after form data is collected
    form.querySelectorAll('input[disabled], select[disabled], textarea[disabled]').forEach(f => f.disabled = false);
    const isEdit = form.action.includes('/clients/') && form.action !== clientsStoreRoute;
    const url = isEdit ? form.action : form.action;
    const method = isEdit ? 'PUT' : 'POST';
    
    // Add method override for PUT
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
          // If this was a create operation, set currentClientId and switch to edit mode
          if (!isEdit && result.client && result.client.id) {
            currentClientId = result.client.id;
            // Fetch full client data and switch to edit mode to allow document upload
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
            // For edit, just show success and close modal
            alert('Client updated successfully!');
            closeClientModal();
            location.reload(); // Reload to show updated data
          }
        } else {
          alert('Error: ' + (result.message || 'Unknown error'));
        }
      } else {
        // Handle validation errors
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

  // Toggle scrollbar helper for responsive table
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

  // Column filter functionality - apply all filters together
  function applyFilters() {
    const rows = document.querySelectorAll('tbody tr');
    const activeFilters = {};
    
    // Collect all active filter values
    document.querySelectorAll('.column-filter.visible').forEach(filter => {
      const column = filter.dataset.column;
      const value = filter.value.trim().toLowerCase();
      if (value) {
        activeFilters[column] = value;
      }
    });
    
    // Apply filters to rows
    rows.forEach(row => {
      let shouldShow = true;
      
      // Check if row matches all active filters
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
    
    // Update records count
    const visibleRows = Array.from(document.querySelectorAll('tbody tr')).filter(row => {
      return row.style.display !== 'none' && !row.style.display.includes('none');
    }).length;
    const recordsFound = document.querySelector('.records-found');
    if (recordsFound && Object.keys(activeFilters).length > 0) {
      const total = clientsTotal;
      recordsFound.textContent = `Records Found - ${visibleRows} of ${total} (filtered)`;
    } else if (recordsFound) {
      recordsFound.textContent = `Records Found - ${clientsTotal}`;
    }
  }
  
  // Print table function - creates a new print-friendly table
  function printTable() {
    const table = document.getElementById('clientsTable');
    if (!table) return;
    
    // Get table headers - preserve order
    const headers = [];
    const headerCells = table.querySelectorAll('thead th');
    headerCells.forEach(th => {
      let headerText = '';
      // Get text, excluding filter input
      const clone = th.cloneNode(true);
      const filterInput = clone.querySelector('.column-filter');
      if (filterInput) filterInput.remove();
      headerText = clone.textContent.trim();
      // Handle bell icon column
      if (clone.querySelector('svg')) {
        headerText = '🔔'; // Bell icon
      }
      if (headerText) {
        headers.push(headerText);
      }
    });
    
    // Get table rows data
    const rows = [];
    const tableRows = table.querySelectorAll('tbody tr:not([style*="display: none"])');
    tableRows.forEach(row => {
      if (row.style.display === 'none') return; // Skip hidden rows
      
      const cells = [];
      const rowCells = row.querySelectorAll('td');
      rowCells.forEach((cell) => {
        let cellContent = '';
        
        // Handle notification column (bell-cell)
        if (cell.classList.contains('bell-cell')) {
          const radio = cell.querySelector('input[type="radio"]');
          if (radio && radio.checked) {
            cellContent = '●'; // Filled circle for checked
          } else {
            cellContent = '○'; // Empty circle for unchecked
          }
        } 
        // Handle action column
        else if (cell.classList.contains('action-cell')) {
          const expandIcon = cell.querySelector('.action-expand');
          const clockIcon = cell.querySelector('.action-clock');
          const ellipsis = cell.querySelector('.action-ellipsis');
          const icons = [];
          if (expandIcon) icons.push('⤢');
          if (clockIcon) icons.push('🕐');
          if (ellipsis) icons.push('⋯');
          cellContent = icons.join(' ');
        } 
        // Handle checkbox cells
        else if (cell.classList.contains('checkbox-cell')) {
          const checkbox = cell.querySelector('input[type="checkbox"]');
          cellContent = checkbox && checkbox.checked ? '✓' : '';
        } 
        // Handle regular cells
        else {
          // Get text content, handling links
          const link = cell.querySelector('a');
          if (link) {
            cellContent = link.textContent.trim();
          } else {
            cellContent = cell.textContent.trim();
          }
        }
        
        cells.push(cellContent || '-');
      });
      rows.push(cells);
    });
    
    // Escape HTML to prevent XSS and syntax issues
    function escapeHtml(text) {
      if (!text) return '';
      const div = document.createElement('div');
      div.textContent = text;
      return div.innerHTML;
    }
    
    // Build headers HTML
    const headersHTML = headers.map(h => '<th>' + escapeHtml(h) + '</th>').join('');
    
    // Build rows HTML
    const rowsHTML = rows.map(row => {
      const cellsHTML = row.map(cell => {
        const cellText = escapeHtml(String(cell || '-'));
        return '<td>' + cellText + '</td>';
      }).join('');
      return '<tr>' + cellsHTML + '</tr>';
    }).join('');
    
    // Create print window with minimal delay
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
  
  // Add event listeners to all column filters
  document.addEventListener('DOMContentLoaded', function() {
    // Print button event listener
    const printBtn = document.getElementById('printBtn');
    if (printBtn) {
      printBtn.addEventListener('click', function() {
        printTable();
      });
    }
    
    document.querySelectorAll('.column-filter').forEach(filter => {
      filter.addEventListener('input', function() {
        applyFilters();
      });
    });
    
    // Initialize filter visibility
    const filterToggle = document.getElementById('filterToggle');
    if (filterToggle && filterToggle.checked) {
      document.querySelectorAll('.column-filter').forEach(filter => {
        filter.classList.add('visible');
        filter.style.display = 'block';
      });
    }
  });

