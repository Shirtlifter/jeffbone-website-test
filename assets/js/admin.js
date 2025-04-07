
document.addEventListener('DOMContentLoaded', function() {
  loadAdminPanel();
  
  // Add event listener for the save all button
  document.getElementById('admin-form').addEventListener('submit', function(e) {
    e.preventDefault();
    saveAllChanges();
  });
});

let currentData = {};

async function loadAdminPanel() {
  try {
    const response = await fetch('data/newsbuzz.json');
    if (!response.ok) {
      throw new Error(`HTTP error! Status: ${response.status}`);
    }
    
    const data = await response.json();
    currentData = data;
    
    const adminSections = document.getElementById('admin-sections');
    adminSections.innerHTML = '';
    
    // Create admin sections from the data
    data.sections.forEach((section, sectionIndex) => {
      const sectionElement = createAdminSection(section, sectionIndex);
      adminSections.appendChild(sectionElement);
    });
  } catch (error) {
    console.error('Error loading admin panel:', error);
    document.getElementById('admin-sections').innerHTML = `<p class="error-message">Error loading admin panel: ${error.message}</p>`;
  }
}

function createAdminSection(section, sectionIndex) {
  const sectionElement = document.createElement('div');
  sectionElement.className = 'admin-section';
  sectionElement.dataset.sectionIndex = sectionIndex;
  
  // Section header with editable title and add entry button
  const headerElement = document.createElement('div');
  headerElement.className = 'admin-section-header';
  
  const titleInput = document.createElement('input');
  titleInput.className = 'section-title-edit';
  titleInput.type = 'text';
  titleInput.value = section.title;
  titleInput.dataset.sectionIndex = sectionIndex;
  titleInput.addEventListener('change', function() {
    currentData.sections[sectionIndex].title = this.value;
  });
  
  const addButton = document.createElement('button');
  addButton.className = 'add-entry-btn';
  addButton.type = 'button'; // Add type="button" to prevent form submission
  addButton.innerHTML = '<i class="fas fa-plus"></i> Add Entry';
  addButton.dataset.sectionIndex = sectionIndex;
  addButton.addEventListener('click', function(e) {
    e.preventDefault(); // Prevent form submission
    addNewEntry(sectionIndex);
  });
  
  headerElement.appendChild(titleInput);
  headerElement.appendChild(addButton);
  
  // Entries container
  const entriesContainer = document.createElement('div');
  entriesContainer.className = 'admin-entries';
  entriesContainer.dataset.sectionIndex = sectionIndex;
  
  section.entries.forEach((entry, entryIndex) => {
    const entryElement = createAdminEntry(entry, sectionIndex, entryIndex);
    entriesContainer.appendChild(entryElement);
  });
  
  sectionElement.appendChild(headerElement);
  sectionElement.appendChild(entriesContainer);
  
  return sectionElement;
}

function createAdminEntry(entry, sectionIndex, entryIndex) {
  const entryElement = document.createElement('div');
  entryElement.className = 'admin-entry';
  entryElement.dataset.entryIndex = entryIndex;
  
  // Entry actions (delete button)
  const actionsElement = document.createElement('div');
  actionsElement.className = 'admin-entry-actions';
  
  const deleteButton = document.createElement('button');
  deleteButton.className = 'action-btn delete-btn';
  deleteButton.type = 'button'; // Add type="button" to prevent form submission
  deleteButton.innerHTML = '<i class="fas fa-trash"></i>';
  deleteButton.dataset.sectionIndex = sectionIndex;
  deleteButton.dataset.entryIndex = entryIndex;
  deleteButton.addEventListener('click', function(e) {
    e.preventDefault(); // Prevent form submission
    deleteEntry(sectionIndex, entryIndex);
  });
  
  actionsElement.appendChild(deleteButton);
  
  // Thumbnail upload
  const thumbnailContainer = document.createElement('div');
  thumbnailContainer.className = 'admin-thumbnail-container';
  
  if (entry.thumbnail) {
    const thumbnail = document.createElement('img');
    thumbnail.className = 'admin-thumbnail';
    thumbnail.src = entry.thumbnail;
    thumbnail.alt = 'Entry thumbnail';
    thumbnailContainer.appendChild(thumbnail);
  } else {
    const placeholder = document.createElement('div');
    placeholder.className = 'upload-placeholder';
    placeholder.innerHTML = '<i class="fas fa-image fa-3x"></i><span>No Image</span>';
    thumbnailContainer.appendChild(placeholder);
  }
  
  const fileInput = document.createElement('input');
  fileInput.type = 'file';
  fileInput.className = 'file-input';
  fileInput.accept = 'image/*';
  fileInput.dataset.sectionIndex = sectionIndex;
  fileInput.dataset.entryIndex = entryIndex;
  fileInput.id = `file-${sectionIndex}-${entryIndex}`;
  fileInput.addEventListener('change', function(e) {
    handleImageUpload(e, sectionIndex, entryIndex);
  });
  
  const uploadButton = document.createElement('label');
  uploadButton.htmlFor = `file-${sectionIndex}-${entryIndex}`;
  uploadButton.className = 'upload-btn';
  uploadButton.innerHTML = '<i class="fas fa-upload"></i> Upload Image';
  
  // Description textarea
  const descriptionTextarea = document.createElement('textarea');
  descriptionTextarea.className = 'admin-description';
  descriptionTextarea.placeholder = 'Description';
  descriptionTextarea.value = entry.description || '';
  descriptionTextarea.dataset.sectionIndex = sectionIndex;
  descriptionTextarea.dataset.entryIndex = entryIndex;
  descriptionTextarea.addEventListener('change', function() {
    currentData.sections[sectionIndex].entries[entryIndex].description = this.value;
  });
  
  // Item textarea
  const itemTextarea = document.createElement('textarea');
  itemTextarea.className = 'admin-item';
  itemTextarea.placeholder = 'Item';
  itemTextarea.value = entry.item || '';
  itemTextarea.dataset.sectionIndex = sectionIndex;
  itemTextarea.dataset.entryIndex = entryIndex;
  itemTextarea.addEventListener('change', function() {
    currentData.sections[sectionIndex].entries[entryIndex].item = this.value;
  });
  
  entryElement.appendChild(actionsElement);
  entryElement.appendChild(thumbnailContainer);
  entryElement.appendChild(fileInput);
  entryElement.appendChild(uploadButton);
  entryElement.appendChild(descriptionTextarea);
  entryElement.appendChild(itemTextarea);
  
  return entryElement;
}

async function handleImageUpload(event, sectionIndex, entryIndex) {
  const file = event.target.files[0];
  if (!file) return;
  
  // Create a FormData object and append the file
  const formData = new FormData();
  formData.append('image', file);
  
  // Get the upload button and show loading state
  const uploadButton = event.target.nextElementSibling;
  const originalButtonText = uploadButton.innerHTML;
  uploadButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Uploading...';
  
  try {
    // Send the file to the server
    const response = await fetch('upload.php', {
      method: 'POST',
      body: formData
    });
    
    // Handle non-OK responses
    if (!response.ok) {
      const errorText = await response.text();
      console.error('Server responded with error:', response.status, errorText);
      throw new Error(`Server error: ${response.status}`);
    }
    
    // Try to parse the JSON response
    let result;
    try {
      const responseText = await response.text();
      console.log('Raw response:', responseText);
      result = JSON.parse(responseText);
    } catch (parseError) {
      console.error('Failed to parse server response as JSON:', parseError);
      throw new Error('Invalid server response');
    }
    
    console.log('Upload result:', result);
    
    if (result.success) {
      // Update the data model with the server file path
      currentData.sections[sectionIndex].entries[entryIndex].thumbnail = result.filePath;
      
      // Update the thumbnail in the UI
      const entryElement = event.target.closest('.admin-entry');
      const thumbnailContainer = entryElement.querySelector('.admin-thumbnail-container');
      thumbnailContainer.innerHTML = '';
      
      const thumbnail = document.createElement('img');
      thumbnail.className = 'admin-thumbnail';
      thumbnail.src = result.filePath;
      thumbnail.alt = 'Entry thumbnail';
      thumbnailContainer.appendChild(thumbnail);
      
      uploadButton.innerHTML = originalButtonText;
    } else {
      console.error('Upload failed:', result);
      alert('Failed to upload image: ' + result.message);
      uploadButton.innerHTML = originalButtonText;
    }
  } catch (error) {
    console.error('Error uploading image:', error);
    alert('Error uploading image: ' + error.message);
    uploadButton.innerHTML = originalButtonText;
  }
}

function addNewEntry(sectionIndex) {
  const newEntry = {
    thumbnail: '',
    description: '',
    item: ''
  };
  
  currentData.sections[sectionIndex].entries.push(newEntry);
  const entryIndex = currentData.sections[sectionIndex].entries.length - 1;
  
  const entriesContainer = document.querySelector(`.admin-entries[data-section-index="${sectionIndex}"]`);
  const entryElement = createAdminEntry(newEntry, sectionIndex, entryIndex);
  entriesContainer.appendChild(entryElement);
}

function deleteEntry(sectionIndex, entryIndex) {
  if (!confirm('Are you sure you want to delete this entry?')) return;
  
  currentData.sections[sectionIndex].entries.splice(entryIndex, 1);
  
  loadAdminPanel();
}

async function saveAllChanges() {
  const saveStatus = document.getElementById('save-status');
  saveStatus.textContent = 'Saving...';
  saveStatus.className = 'save-status';
  
  try {
    // Update the hidden input with the current data
    document.getElementById('json-data').value = JSON.stringify(currentData);
    
    // For direct AJAX save
    const response = await fetch('save.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify(currentData)
    });
    
    if (!response.ok) {
      const errorText = await response.text();
      console.error('Server responded with error:', response.status, errorText);
      throw new Error(`Server error: ${response.status}`);
    }
    
    let result;
    try {
      const responseText = await response.text();
      console.log('Raw save response:', responseText);
      result = JSON.parse(responseText);
    } catch (parseError) {
      console.error('Failed to parse server response as JSON:', parseError);
      throw new Error('Invalid server response');
    }
    
    if (result.success) {
      saveStatus.textContent = 'All changes saved successfully!';
      saveStatus.className = 'save-status status-success';
    } else {
      saveStatus.textContent = 'Error saving changes: ' + result.message;
      saveStatus.className = 'save-status status-error';
    }
    
    setTimeout(() => {
      saveStatus.textContent = '';
      saveStatus.className = 'save-status';
    }, 3000);
  } catch (error) {
    console.error('Error saving changes:', error);
    saveStatus.textContent = 'Error saving changes: ' + error.message;
    saveStatus.className = 'save-status status-error';
  }
}
