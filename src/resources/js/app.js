import './bootstrap';

// Modal Handling for User
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

// Modal Handling for Posts
const postModalHandler = {
    async show(id) {
        const editPostForm = document.getElementById('edit-post-form');
        const editPostContent = document.getElementById('edit-post-content');
        const editPostVisibility = document.getElementById('edit-post-visibility');
        const editPostModal = document.getElementById('edit-post-modal');

        try {
            // Fetch the latest data for the post
            const response = await fetch(`/posts/${id}`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
            });

            if (!response.ok) {
                const errorText = await response.text();
                console.error('Error fetching post data:', errorText);
                displayMessage('Failed to load post data.', 'error');
                return;
            }

            const postData = await response.json();

            // Populate the modal fields with the latest data
            if (editPostForm) editPostForm.action = `/posts/${id}`;
            if (editPostContent) editPostContent.value = postData.content || '';
            if (editPostVisibility) editPostVisibility.value = postData.visibility || 'public';

            // Show the modal
            if (editPostModal) editPostModal.classList.remove('hidden');
        } catch (error) {
            console.error('Error loading post data:', error);
        }
    },

    hide() {
        const editPostModal = document.getElementById('edit-post-modal');
        if (editPostModal) editPostModal.classList.add('hidden');
    },
};

// Attach modal functions to the global window object for global access
window.showEditUserModal = modalHandler.show;
window.hideEditUserModal = modalHandler.hide;
window.showEditPostModal = postModalHandler.show;
window.hideEditPostModal = postModalHandler.hide;


const profileModalHandler = {
    show(user) {
        const modal = document.getElementById('edit-profile-modal');
        const editNameField = document.getElementById('edit-name');
        const editEmailField = document.getElementById('edit-email');
        const editUsernameField = document.getElementById('edit-username');
        const editVisibilityField = document.getElementById('edit-visibility');

        if (!modal) return;

        // Populate the modal fields with the provided user data
        if (editNameField) editNameField.value = user.name || '';
        if (editEmailField) editEmailField.value = user.email || '';
        if (editUsernameField) editUsernameField.value = user.username || '';
        if (editVisibilityField) editVisibilityField.value = user.visibility || 'public';

        // Show the modal
        modal.classList.remove('hidden');
    },

    hide() {
        const modal = document.getElementById('edit-profile-modal');
        if (modal) modal.classList.add('hidden');
    },
};

// Attach modal functions to the global window object for global access
window.showEditProfileModal = profileModalHandler.show;
window.hideEditProfileModal = profileModalHandler.hide;

// Search Functionality
function handleSearchInput() {
    const searchQueryInput = document.getElementById('search-query');
    const searchResultsDiv = document.getElementById('search-results');

    if (!searchQueryInput || !searchResultsDiv) return;

    searchQueryInput.addEventListener('input', function () {
        const query = this.value;

        if (query.length > 0) {
            fetch(`/search/users?query=${encodeURIComponent(query)}&limit=3`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
                .then(response => {
                    if (!response.ok) throw new Error('Network error');
                    return response.text();
                })
                .then(data => {
                    searchResultsDiv.innerHTML = data;
                    searchResultsDiv.classList.remove('hidden');
                })
                .catch(err => console.error('Error:', err));
        } else {
            searchResultsDiv.classList.add('hidden');
            searchResultsDiv.innerHTML = '';
        }
    });

    document.addEventListener('click', function (event) {
        if (!searchQueryInput.contains(event.target) && !searchResultsDiv.contains(event.target)) {
            searchResultsDiv.classList.add('hidden');
        }
    });
}

// Filter User List Functionality
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

// Attach event listener for user search input
function handleUserSearchInput() {
    const userSearchInput = document.getElementById('user-search-input');
    if (userSearchInput) {
        userSearchInput.addEventListener('keyup', filterUserList);
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

// Dropdown Handling
function handleDropdowns(event) {
    const allDropdowns = document.querySelectorAll('[id^="dropdownMenu-"]');
    const dropdownButton = event.target.closest('[id^="dropdownMenuButton-"]');

    if (dropdownButton) {
        const dropdownId = dropdownButton.id.replace('dropdownMenuButton-', '');
        const menu = document.getElementById(`dropdownMenu-${dropdownId}`);
        const isHidden = menu?.classList.contains('hidden');

        allDropdowns.forEach(dropdown => dropdown.classList.add('hidden'));
        if (isHidden) menu?.classList.remove('hidden');
        event.stopPropagation();
    } else {
        allDropdowns.forEach(menu => menu.classList.add('hidden'));
    }
}

// Handle Post Update Submission
function handleEditPostSubmission() {
    const editPostForm = document.getElementById('edit-post-form');

    if (!editPostForm) return;

    editPostForm.addEventListener('submit', async function (event) {
        event.preventDefault();

        const actionUrl = editPostForm.action;
        const postId = actionUrl.match(/\/posts\/(\d+)/)?.[1];
        const content = document.getElementById('edit-post-content')?.value.trim();
        const visibility = document.getElementById('edit-post-visibility')?.value;

        if (!content) {
            window.showFeedbackMessage('red', 'Post content cannot be empty.');
            return;
        }

        try {
            const response = await fetch(actionUrl, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: JSON.stringify({ content, visibility }),
            });

            if (!response.ok) {
                throw new Error('Failed to update post');
            }

            const data = await response.json();

            // Update the post content dynamically on the page
            updatePostContent(postId, data);

            // Close the modal
            postModalHandler.hide();

            // Display success message
            window.showFeedbackMessage('green', 'Post updated successfully!');
        } catch (error) {
            console.error('Error updating post:', error);
            window.showFeedbackMessage('red', 'An error occurred while updating the post.');
        }
    });
}

// Update Post Content Dynamically
function updatePostContent(postId, data) {
    const postDiv = document.querySelector(`[data-id="${postId}"]`);
    if (!postDiv) {
        console.warn(`Post with ID ${postId} not found on the page.`);
        return;
    }

    // Update content
    const contentParagraph = postDiv.querySelector('p.text-lg');
    if (contentParagraph) {
        contentParagraph.textContent = data.content;
    } else {
        console.warn('Content paragraph not found for post.');
    }

    // Update visibility if applicable
    const visibilityField = postDiv.querySelector('.post-visibility');
    if (visibilityField) {
        visibilityField.textContent = data.visibility;
    }
}


const messageHandler = {
    show(type, message) {
        const container = document.querySelector('.fixed.bottom-5.right-5');
        if (!container) return;

        const alert = document.createElement('div');
        alert.className = `alert bg-${type}-100 border border-${type}-400 text-${type}-700 px-4 py-3 rounded shadow-md opacity-100 transition-opacity duration-500 flex items-center justify-between`;
        alert.setAttribute('role', 'alert');

        alert.innerHTML = `
            <span class="mr-1">${message}</span>
            <button type="button" class="px-4 py-2 text-${type}-700 hover:text-${type}-800" onclick="this.parentElement.remove()">
                &times;
            </button>
        `;

        container.appendChild(alert);
        this.autoRemove(alert);
    },

    autoRemove(alert) {
        setTimeout(() => {
            alert.classList.remove('opacity-100');
            alert.classList.add('opacity-0');
            setTimeout(() => alert.remove(), 500);
        }, 5000);
    }
};

window.showFeedbackMessage = (type, message) => messageHandler.show(type, message);

function initializeMessageHandler() {
    document.querySelectorAll('.alert').forEach(alert => {
        messageHandler.autoRemove(alert);
    });
}

window.showFeedbackMessage = (type, message) => messageHandler.show(type, message);


// Initialize Event Listeners
function addEventListeners() {
    document.addEventListener('click', handleDropdowns);
    handleImagePreview();
    handleSearchInput();
    handleUserSearchInput();
    handleEditPostSubmission();
    initializeMessageHandler();
}




// Call the initialization function
addEventListeners();


function toggleSidebar() {
    const sidebar = document.getElementById('friend-requests-sidebar');
    sidebar.classList.toggle('-translate-x-full');
}

window.toggleRequestsSidebar = toggleSidebar;

async function addFriend(userId) {
    try {
        const response = await fetch(`/friends/add/${userId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
        });

        if (!response.ok) throw new Error('Failed to add friend');
        const data = await response.json();

        // Update the button dynamically
        const button = document.querySelector(`[data-user-id="${userId}"]`);
        if (button) {
            button.textContent = 'Cancel Friend Request';
            button.className = 'bg-gray-500 text-white py-2 px-4 rounded hover:bg-gray-600 transition';
            button.setAttribute('onclick', `cancelFriendRequest(${userId})`);
        }

        window.showFeedbackMessage('green', 'Friend request sent successfully!');
    } catch (error) {
        console.error(error);
        window.showFeedbackMessage('red', 'An error occurred while sending the friend request.');
    }
}

async function removeFriend(userId) {
    try {
        const response = await fetch(`/friends/remove/${userId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
        });

        if (!response.ok) throw new Error('Failed to remove friend');
        const data = await response.json();

        // Update the button dynamically
        const button = document.querySelector(`[data-user-id="${userId}"]`);
        if (button) {
            button.textContent = 'Add Friend';
            button.className = 'bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600 transition';
            button.setAttribute('onclick', `addFriend(${userId})`);
        }

        window.showFeedbackMessage('green', 'Friend removed successfully!');
    } catch (error) {
        console.error(error);
        window.showFeedbackMessage('red', 'An error occurred while removing the friend.');
    }
}

async function cancelFriendRequest(userId) {
    try {
        const response = await fetch(`/friends/cancel/${userId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
        });

        if (!response.ok) throw new Error('Failed to cancel friend request');
        const data = await response.json();

        // Update the button dynamically
        const button = document.querySelector(`[data-user-id="${userId}"]`);
        if (button) {
            button.textContent = 'Add Friend';
            button.className = 'bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600 transition';
            button.setAttribute('onclick', `addFriend(${userId})`);
        }

        window.showFeedbackMessage('green', 'Friend request canceled successfully!');
    } catch (error) {
        console.error(error);
        window.showFeedbackMessage('red', 'An error occurred while canceling the friend request.');
    }
}


window.cancelFriendRequest = cancelFriendRequest;
window.addFriend = addFriend;
window.removeFriend = removeFriend;


async function acceptFriendRequest(requestId) {
    try {
        const response = await fetch(`/friends/accept/${requestId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
        });

        if (!response.ok) throw new Error('Failed to accept friend request');
        window.showFeedbackMessage('green', 'Friend request accepted!');
        location.reload();
    } catch (error) {
        console.error(error);
        window.showFeedbackMessage('red', 'An error occurred while accepting the friend request.');
    }
}

async function rejectFriendRequest(requestId) {
    try {
        const response = await fetch(`/friends/reject/${requestId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
        });

        if (!response.ok) throw new Error('Failed to reject friend request');
        window.showFeedbackMessage('green', 'Friend request rejected!');
        location.reload();
    } catch (error) {
        console.error(error);
        window.showFeedbackMessage('red', 'An error occurred while rejecting the friend request.');
    }
}



window.acceptFriendRequest = acceptFriendRequest;
window.rejectFriendRequest = rejectFriendRequest;