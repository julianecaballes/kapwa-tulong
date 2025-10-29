document.addEventListener('DOMContentLoaded', function() {
    // Text Editor Functionality
    const editor = document.getElementById('editor');
    const toolbar = document.querySelector('.editor-toolbar');
    const hiddenDescription = document.getElementById('hiddenDescription');

    toolbar.addEventListener('click', function(e) {
        const button = e.target.closest('button');
        if (!button) return;

        e.preventDefault();
        const command = button.dataset.command;
        const value = button.dataset.value || '';

        if (command === 'heading') {
            document.execCommand('formatBlock', false, value);
        } else if (command === 'createLink') {
            const url = prompt('Enter the URL:');
            if (url) document.execCommand(command, false, url);
        } else {
            document.execCommand(command, false, value);
        }
    });

    // Category Dropdown Functionality
    const selectedCategories = document.getElementById('selectedCategories');
    const categoryList = document.getElementById('categoryList');
    const categoriesInput = document.getElementById('categoriesInput');
    let selectedCount = 0;

    selectedCategories.addEventListener('click', () => {
        categoryList.classList.toggle('active');
    });

    document.addEventListener('click', (e) => {
        if (!e.target.closest('.category-dropdown')) {
            categoryList.classList.remove('active');
        }
    });

    function updateSelectedCategories() {
        const selected = Array.from(document.querySelectorAll('.category-option input:checked'))
            .map(input => ({
                value: input.id,
                label: input.nextElementSibling.textContent
            }));

        if (selected.length === 0) {
            selectedCategories.innerHTML = '<span class="placeholder">Select 1-3 categories</span>';
        } else {
            selectedCategories.innerHTML = selected.map(cat => `
                <span class="category-tag">
                    ${cat.label}
                    <span class="remove" data-value="${cat.value}">&times;</span>
                </span>
            `).join('');
        }

        categoriesInput.value = selected.map(cat => cat.value).join(',');
    }

    document.querySelectorAll('.category-option input').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            if (this.checked && selectedCount >= 3) {
                this.checked = false;
                alert('You can only select up to 3 categories');
                return;
            }
            selectedCount = this.checked ? selectedCount + 1 : selectedCount - 1;
            updateSelectedCategories();
        });
    });

    selectedCategories.addEventListener('click', (e) => {
        if (e.target.classList.contains('remove')) {
            const value = e.target.dataset.value;
            const checkbox = document.getElementById(value);
            checkbox.checked = false;
            selectedCount--;
            updateSelectedCategories();
            e.stopPropagation();
        }
    });

    // File Upload Functionality
    const dropZone = document.getElementById('dropZone');
    const fileInput = document.getElementById('fileInput');
    const previewContainer = document.querySelector('.preview-container');
    const imagePreview = document.getElementById('imagePreview');
    const browseBtn = document.querySelector('.browse-btn');
    const removeBtn = document.querySelector('.remove-image');
    const uploadContent = document.querySelector('.upload-content');

    browseBtn.addEventListener('click', () => fileInput.click());

    dropZone.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropZone.classList.add('dragover');
    });

    dropZone.addEventListener('dragleave', () => {
        dropZone.classList.remove('dragover');
    });

    dropZone.addEventListener('drop', (e) => {
        e.preventDefault();
        dropZone.classList.remove('dragover');
        handleFiles(e.dataTransfer.files);
    });

    fileInput.addEventListener('change', (e) => {
        handleFiles(e.target.files);
    });

    removeBtn.addEventListener('click', () => {
        fileInput.value = '';
        previewContainer.hidden = true;
        uploadContent.hidden = false;
    });

    function handleFiles(files) {
        if (files.length === 0) return;
        
        const file = files[0];
        if (!file.type.startsWith('image/')) {
            alert('Please upload an image file');
            return;
        }

        if (file.size > 5 * 1024 * 1024) {
            alert('File size should be less than 5MB');
            return;
        }

        const reader = new FileReader();
        reader.onload = function(e) {
            imagePreview.src = e.target.result;
            previewContainer.hidden = false;
            uploadContent.hidden = true;
        };
        reader.readAsDataURL(file);
    }

    // Handle form submission
    document.getElementById('campaignForm').addEventListener('submit', function(e) {
        e.preventDefault();
        showConfirmationPopup();
    });

    function showConfirmationPopup() {
        // Get form values
        const title = document.querySelector('input[name="title"]').value;
        const cityProvince = document.querySelector('input[name="city_province"]').value;
        const zipCode = document.querySelector('input[name="zip_code"]').value;
        const categories = document.querySelector('input[name="categories"]').value;
        const description = document.getElementById('editor').innerHTML;
        const targetAmount = document.querySelector('input[name="target_amount"]').value;
        
        // Update preview elements
        document.getElementById('previewTitle').textContent = title;
        document.getElementById('previewLocation').textContent = `${cityProvince}, ${zipCode}`;
        document.getElementById('previewCategories').textContent = categories;
        document.getElementById('previewAmount').textContent = `₱${parseFloat(targetAmount).toLocaleString()}`;
        document.getElementById('previewDescription').innerHTML = description;

        // Preview image if one was selected
        if (fileInput.files && fileInput.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('previewImage').src = e.target.result;
            };
            reader.readAsDataURL(fileInput.files[0]);
        }

        // Show confirmation popup
        document.getElementById('confirmationPopup').style.display = 'flex';
    }

    // Handle confirmation buttons
    document.querySelector('.confirm-submit').addEventListener('click', function() {
        document.getElementById('confirmationPopup').style.display = 'none';
        submitCampaign();
    });

    document.querySelector('.edit-campaign').addEventListener('click', function() {
        document.getElementById('confirmationPopup').style.display = 'none';
    });

    function submitCampaign() {
        const form = document.getElementById('campaignForm');
        const formData = new FormData(form);

        // Show loading popup
        const popupOverlay = document.getElementById('popupOverlay');
        const loadingPopup = document.getElementById('loadingPopup');
        const successPopup = document.getElementById('successPopup');
        const errorPopup = document.getElementById('errorPopup');
        
        popupOverlay.style.display = 'flex';
        loadingPopup.style.display = 'block';
        successPopup.style.display = 'none';
        errorPopup.style.display = 'none';

        fetch('../backend/create_campaign.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                loadingPopup.style.display = 'none';
                successPopup.style.display = 'block';
                // Reset form
                form.reset();
                document.getElementById('editor').innerHTML = '';
                document.getElementById('imagePreview').src = '';
                document.querySelector('.preview-container').hidden = true;
            } else {
                throw new Error(data.error || 'Submission failed');
            }
        })
        .catch(error => {
            loadingPopup.style.display = 'none';
            errorPopup.style.display = 'block';
            errorPopup.querySelector('p').textContent = error.message;
        });
    }

    // Handle category selection
    document.querySelectorAll('.category-option input[type="checkbox"]').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const selectedCategories = [];
            document.querySelectorAll('.category-option input[type="checkbox"]:checked').forEach(checked => {
                selectedCategories.push(checked.id);
            });
            
            // Limit to 3 categories
            if (selectedCategories.length > 3) {
                this.checked = false;
                alert('You can only select up to 3 categories');
                return;
            }
            
            document.getElementById('categoriesInput').value = selectedCategories.join(',');
            
            // Update display
            const selectedCategoriesDiv = document.getElementById('selectedCategories');
            if (selectedCategories.length > 0) {
                selectedCategoriesDiv.innerHTML = selectedCategories.join(', ');
            } else {
                selectedCategoriesDiv.innerHTML = '<span class="placeholder">Select 1-3 categories</span>';
            }
        });
    });

    // Add event listener to update hidden textarea when editor content changes
    document.getElementById('editor').addEventListener('input', function() {
        document.getElementById('hiddenDescription').value = this.innerHTML;
    });

    function closePopup() {
        document.getElementById('popupOverlay').style.display = 'none';
    }

    // Initialize editor toolbar functionality
    document.querySelectorAll('.editor-toolbar button').forEach(button => {
        button.addEventListener('click', function() {
            const command = this.dataset.command;
            const value = this.dataset.value || '';

            if (command === 'createLink') {
                const url = prompt('Enter the URL:');
                if (url) document.execCommand(command, false, url);
            } else if (command === 'heading') {
                document.execCommand('formatBlock', false, value);
            } else {
                document.execCommand(command, false, value);
            }
            
            // Update hidden textarea after each formatting change
            document.getElementById('hiddenDescription').value = document.getElementById('editor').innerHTML;
        });
    });
});

// Add this function at the top level of your JavaScript file
function handleServerResponse(response) {
    return new Promise(async (resolve, reject) => {
        try {
            const contentType = response.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
                // Get the text response to help with debugging
                const text = await response.text();
                console.error('Non-JSON response:', text);
                throw new Error('Server response was not JSON');
            }
            const data = await response.json();
            resolve(data);
        } catch (error) {
            reject(error);
        }
    });
} 