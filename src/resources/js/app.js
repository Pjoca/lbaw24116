import './bootstrap';

function showEditUserModal(id, name, email, username) {
    const editUserForm = document.getElementById('edit-user-form');
    const editNameField = document.getElementById('edit-name');
    const editEmailField = document.getElementById('edit-email');
    const editUsernameField = document.getElementById('edit-username');
    const editUserModal = document.getElementById('edit-user-modal');

    if (editUserForm) {
        editUserForm.action = `/admin/update-user/${id}`;
    }
    if (editNameField) {
        editNameField.value = name;
    }
    if (editEmailField) {
        editEmailField.value = email;
    }
    if (editUsernameField) {
        editUsernameField.value = username;
    }

    if (editUserModal) {
        editUserModal.classList.remove('hidden');
    }
}

function hideEditUserModal() {
    const editUserModal = document.getElementById('edit-user-modal');
    if (editUserModal) {
        editUserModal.classList.add('hidden');
    }
}

// Attach functions to the window object
window.showEditUserModal = showEditUserModal;
window.hideEditUserModal = hideEditUserModal;

// Search functionality for the user list
function filterUserList() {
    const searchValue = document.getElementById('user-search-input').value.toLowerCase();
    const rows = document.querySelectorAll('#user-list-tbody tr');

    rows.forEach(row => {
        const name = row.children[1].textContent.toLowerCase();
        const email = row.children[2].textContent.toLowerCase();

        if (name.includes(searchValue) || email.includes(searchValue)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

function addEventListeners() {
    // Dropdown handling for multiple dropdowns
    document.addEventListener('click', function (e) {
        const allDropdowns = document.querySelectorAll('[id^="dropdownMenu-"]');
        const dropdownButton = e.target.closest('[id^="dropdownMenuButton-"]');
  
        // If a dropdown button is clicked
        if (dropdownButton) {
            const dropdownId = dropdownButton.id.replace('dropdownMenuButton-', '');
            const menu = document.getElementById(`dropdownMenu-${dropdownId}`);
            
            if (menu) {
                // Toggle the visibility of the current dropdown
                const isHidden = menu.classList.contains('hidden');
                allDropdowns.forEach(dropdown => dropdown.classList.add('hidden')); // Hide all dropdowns
                if (isHidden) menu.classList.remove('hidden'); // Show the clicked dropdown
            }
            e.stopPropagation(); // Prevent the click from propagating further
            return;
        }
  
        // Hide all dropdowns if clicking outside
        allDropdowns.forEach(menu => menu.classList.add('hidden'));
    });

    // Image preview functionality
    const imageInput = document.getElementById('image');
    if (imageInput) {
        const previewContainer = document.getElementById('preview-container');
        const previewImage = document.getElementById('preview-image');

        imageInput.addEventListener('change', function (event) {
            const file = event.target.files[0];

            if (file) {
                const reader = new FileReader();

                reader.onload = function (e) {
                    if (previewImage) {
                        previewImage.src = e.target.result;
                    }
                    if (previewContainer) {
                        previewContainer.classList.remove('hidden'); // Show the preview container
                    }
                };

                reader.readAsDataURL(file); // Read the image as a data URL
            } else {
                if (previewContainer) {
                    previewContainer.classList.add('hidden'); // Hide the preview if no file selected
                }
                if (previewImage) {
                    previewImage.src = '#';
                }
            }
        });
    }

    // Modal handling for "Edit User"
    const editUserModal = document.getElementById('edit-user-modal');
    const editUserForm = document.getElementById('edit-user-form');
    const editNameField = document.getElementById('edit-name');
    const editEmailField = document.getElementById('edit-email');

    document.addEventListener('click', function (e) {
        // Open modal
        if (e.target.matches('[data-edit-user]')) {
            const { userId, userName, userEmail } = e.target.dataset;

            if (editUserForm) {
                editUserForm.action = `/admin/update-user/${userId}`;
            }
            if (editNameField) {
                editNameField.value = userName;
            }
            if (editEmailField) {
                editEmailField.value = userEmail;
            }

            if (editUserModal) {
                editUserModal.classList.remove('hidden');
            }
        }

        // Close modal
        if (e.target.matches('#edit-user-modal, #edit-user-modal button[data-dismiss="modal"]')) {
            if (editUserModal) {
                editUserModal.classList.add('hidden');
            }
        }
    });

    // Add event listener for user search
    const userSearchInput = document.getElementById('user-search-input');
    if (userSearchInput) {
        userSearchInput.addEventListener('keyup', filterUserList);
    }
}

function encodeForAjax(data) {
    if (data == null) return null;
    return Object.keys(data).map(function (k) {
        return encodeURIComponent(k) + '=' + encodeURIComponent(data[k]);
    }).join('&');
}

function sendAjaxRequest(method, url, data, handler) {
    let request = new XMLHttpRequest();

    request.open(method, url, true);
    request.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').content);
    request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    request.addEventListener('load', handler);
    request.send(encodeForAjax(data));
}

// Initialize event listeners
addEventListeners();
