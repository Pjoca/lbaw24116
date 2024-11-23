import './bootstrap';

// Modal Handling
const modalHandler = {
    show(id, name, email, username) {
        const editUserForm = document.getElementById('edit-user-form');
        const editNameField = document.getElementById('edit-name');
        const editEmailField = document.getElementById('edit-email');
        const editUsernameField = document.getElementById('edit-username');
        const editUserModal = document.getElementById('edit-user-modal');

        if (editUserForm) editUserForm.action = `/admin/update-user/${id}`;
        if (editNameField) editNameField.value = name;
        if (editEmailField) editEmailField.value = email;
        if (editUsernameField) editUsernameField.value = username;

        if (editUserModal) editUserModal.classList.remove('hidden');
    },

    hide() {
        const editUserModal = document.getElementById('edit-user-modal');
        if (editUserModal) editUserModal.classList.add('hidden');
    }
};

// Attach modal functions to window for global access
window.showEditUserModal = modalHandler.show;
window.hideEditUserModal = modalHandler.hide;

// Search Functionality
function filterUserList() {
    const searchValue = document.getElementById('user-search-input')?.value.toLowerCase();
    const rows = document.querySelectorAll('#user-list-tbody tr');

    rows.forEach(row => {
        const name = row.children[1]?.textContent.toLowerCase();
        const email = row.children[2]?.textContent.toLowerCase();
        const isVisible = name.includes(searchValue) || email.includes(searchValue);
        row.style.display = isVisible ? '' : 'none';
    });
}

// Dropdown Handling
function handleDropdowns(event) {
    const allDropdowns = document.querySelectorAll('[id^="dropdownMenu-"]');
    const dropdownButton = event.target.closest('[id^="dropdownMenuButton-"]');

    if (dropdownButton) {
        const dropdownId = dropdownButton.id.replace('dropdownMenuButton-', '');
        const menu = document.getElementById(`dropdownMenu-${dropdownId}`);
        const isHidden = menu?.classList.contains('hidden');

        allDropdowns.forEach(dropdown => dropdown.classList.add('hidden')); // Hide all dropdowns
        if (isHidden) menu?.classList.remove('hidden'); // Toggle clicked dropdown
        event.stopPropagation();
    } else {
        allDropdowns.forEach(menu => menu.classList.add('hidden')); // Hide all dropdowns if clicking outside
    }
}

// Image Preview Functionality
function handleImagePreview() {
    const imageInput = document.getElementById('image');
    if (!imageInput) return;

    const previewContainer = document.getElementById('preview-container');
    const previewImage = document.getElementById('preview-image');

    imageInput.addEventListener('change', event => {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = e => {
                if (previewImage) previewImage.src = e.target.result;
                if (previewContainer) previewContainer.classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        } else {
            if (previewContainer) previewContainer.classList.add('hidden');
            if (previewImage) previewImage.src = '#';
        }
    });
}

// AJAX Helper Functions
const ajaxUtils = {
    encodeForAjax(data) {
        if (!data) return null;
        return Object.keys(data)
            .map(k => `${encodeURIComponent(k)}=${encodeURIComponent(data[k])}`)
            .join('&');
    },

    sendRequest(method, url, data, handler) {
        const request = new XMLHttpRequest();
        request.open(method, url, true);
        request.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').content);
        request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        request.addEventListener('load', handler);
        request.send(this.encodeForAjax(data));
    }
};

// Initialize Event Listeners
function addEventListeners() {

    document.addEventListener('click', handleDropdowns);

    handleImagePreview();

    const userSearchInput = document.getElementById('user-search-input');
    if (userSearchInput) {
        userSearchInput.addEventListener('keyup', filterUserList);
    }

    document.addEventListener('click', event => {
        if (event.target.matches('#edit-user-modal, #edit-user-modal button[data-dismiss="modal"]')) {
            modalHandler.hide();
        }
    });
}


addEventListeners();
