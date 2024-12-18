import './bootstrap';
import Pusher from 'pusher-js';

const pusher = new Pusher(import.meta.env.VITE_PUSHER_APP_KEY, {
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    encrypted: true,
    authEndpoint: '/broadcasting/auth',
    auth: {
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    }
});

window.onload = function() {
    console.log('Is loadInitialJoinRequests defined?', typeof loadInitialJoinRequests);
    var reloading = sessionStorage.getItem("reloading");
    if (reloading) {
        eval(sessionStorage.getItem("command"));
        sessionStorage.removeItem("reloading");
        sessionStorage.removeItem("command");
    }
}

function reloadP(command) {
    sessionStorage.setItem("reloading", "true");
    sessionStorage.setItem("command", command);
    document.location.reload();
}

window.reloadP = reloadP;


function goWithCommand(location, command) {
    sessionStorage.setItem("reloading", "true");
    sessionStorage.setItem("command", command);
    window.location.href = location;
}

window.goWithCommand = goWithCommand;


function openModal(modalId) {
    console.log(`Opening modal: ${modalId}`);
    const modal = document.getElementById(modalId);
    if (modal) {
        if (modalId === 'create-post-modal') {
            const groupIdMeta = document.querySelector('meta[name="group-id"]');

            if (groupIdMeta) {
                const visibilityField = document.getElementById('visibility');
                const groupField = document.getElementById('id_group');

                if (visibilityField && groupField) {
                    visibilityField.value = 'group';
                    visibilityField.setAttribute('disabled', true);
                    
                    groupField.value = groupIdMeta.getAttribute('content');
                    groupField.setAttribute('disabled', true);
                }
            } else {
                const visibilityField = document.getElementById('visibility');
                const groupField = document.getElementById('id_group');

                if (visibilityField) {
                    visibilityField.removeAttribute('disabled');
                    visibilityField.value = 'public';
                }

                if (groupField) {
                    groupField.removeAttribute('disabled');
                    groupField.value = '';
                }
            }
        }
            
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    } else {
        console.error(`Modal with ID ${modalId} not found.`);
    }
}


function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    modal.classList.add('hidden');
}


window.openModal = openModal;
window.closeModal = closeModal;



function toggleProfileDropdown() {
    const profileDropdown = document.getElementById('profile-dropdown');
    profileDropdown.classList.toggle('hidden');
}

window.toggleProfileDropdown = toggleProfileDropdown;

document.addEventListener('click', (event) => {
    const profileDropdown = document.getElementById('profile-dropdown');
    const profileDropdownToggle = document.getElementById('profile-dropdown-toggle');

    if (!profileDropdownToggle.contains(event.target) && !profileDropdown.contains(event.target)) {
        profileDropdown.classList.add('hidden');
    }
});

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
    handleEditPostSubmission();
    initializeMessageHandler();
}




// Call the initialization function
addEventListeners();


function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
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

        // Update the button dynamically if it exists elsewhere
        const button = document.querySelector(`[data-user-id="${userId}"]`);
        if (button) {
            button.textContent = 'Add Friend';
            button.className = 'bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600 transition';
            button.setAttribute('onclick', `addFriend(${userId})`);
        }

        // Remove the friend request from the sent requests list (if it exists)
        const sentRequestItem = document.querySelector(`#sent-request-${userId}`);
        if (sentRequestItem) {
            sentRequestItem.remove();
        }

        // Show success feedback
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
        window,reloadP("showFeedbackMessage('green', 'Friend request accepted!');");
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
        window.reloadP("showFeedbackMessage('green', 'Friend request rejected!');");
    } catch (error) {
        console.error(error);
        window.showFeedbackMessage('red', 'An error occurred while rejecting the friend request.');
    }
}



window.acceptFriendRequest = acceptFriendRequest;
window.rejectFriendRequest = rejectFriendRequest;



async function acceptGroupInvite(inviteId) {
    try {
        const response = await fetch(`/groups/invite/${inviteId}/accept`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
        });

        if (!response.ok) {
            const errorData = await response.json();
            throw new Error(errorData.error || 'Failed to accept invite');
        }

        const data = await response.json();

        // Remove the invite dynamically
        const inviteElement = document.querySelector(`#group-invites-list li[data-invite-id="${inviteId}"]`);
        if (inviteElement) {
            inviteElement.remove();
        }

        // Check if the list is empty after removing the item
        const remainingInvites = document.querySelectorAll('#group-invites-list li');
        if (remainingInvites.length === 0) {
            document.getElementById('group-invites-list').innerHTML = `<p class="text-gray-500 text-center">No group invites at the moment.</p>`;
        }

        window.showFeedbackMessage('green', data.success || 'Invite accepted successfully!');
    } catch (error) {
        console.error('Error accepting invite:', error);
        window.showFeedbackMessage('red', error.message || 'An error occurred while accepting the invite.');
    }
}

// Function to reject a group invite
async function rejectGroupInvite(inviteId) {
    try {
        const response = await fetch(`/groups/invite/${inviteId}/reject`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
        });

        if (!response.ok) {
            const errorData = await response.json();
            throw new Error(errorData.error || 'Failed to reject invite');
        }

        const data = await response.json();

        // Remove the invite dynamically
        const inviteElement = document.querySelector(`#group-invites-list li[data-invite-id="${inviteId}"]`);
        if (inviteElement) {
            inviteElement.remove();
        }

        // Check if the list is empty after removing the item
        const remainingInvites = document.querySelectorAll('#group-invites-list li');
        if (remainingInvites.length === 0) {
            document.getElementById('group-invites-list').innerHTML = `<p class="text-gray-500 text-center">No group invites at the moment.</p>`;
        }

        window.showFeedbackMessage('green', data.success || 'Invite rejected successfully!');
    } catch (error) {
        console.error('Error rejecting invite:', error);
        window.showFeedbackMessage('red', error.message || 'An error occurred while rejecting the invite.');
    }
}

window.acceptGroupInvite = acceptGroupInvite;
window.rejectGroupInvite = rejectGroupInvite;


function deleteNotification(notificationId) {
    const notificationElement = document.getElementById(`notification-${notificationId}`);

    if (!notificationElement) return;

    fetch(`/notifications/delete/${notificationId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Failed to delete the notification');
        }
        return response.json();
    })
    .then(data => {
        // Remove the notification element from the DOM
        notificationElement.remove();

        // Decrement the notification counter
        const notificationCountElement = document.getElementById('notification-count');
        if (notificationCountElement) {
            let currentCount = parseInt(notificationCountElement.textContent) || 0;
            currentCount = Math.max(currentCount - 1, 0); // Ensure it never goes below zero
            notificationCountElement.textContent = currentCount;

            // Hide the count if it reaches zero
            if (currentCount === 0) {
                notificationCountElement.classList.add('hidden');
            }
        }

        // Check if there are no more notifications in the list
        const notificationList = document.getElementById('notification-list');
        if (notificationList.children.length === 0) {
            notificationList.innerHTML = '<li class="text-center p-4 text-gray-500">No new notifications</li>';
        }

        console.log('Notification deleted successfully:', data);
    })
    .catch(error => {
        console.error('Error deleting notification:', error);
    });
}

window.deleteNotification = deleteNotification;

// Search

// Keeps date inputs
document.addEventListener('DOMContentLoaded', function () {
    const urlParams = new URLSearchParams(window.location.search);

    // Set the dropdown value
    const typeSelect = document.getElementById('search-type');
    if (urlParams.has('type')) {
        typeSelect.value = urlParams.get('type');
    }

    // Set the search query value
    const searchQuery = document.getElementById('search-bar');
    if (urlParams.has('query')) {
        searchQuery.value = urlParams.get('query');
    }

    // Set the start date value
    const startDateInput = document.getElementById('start-date');
    if (urlParams.has('start_date')) {
        startDateInput.value = urlParams.get('start_date');
    }

    // Set the end date value
    const endDateInput = document.getElementById('end-date');
    if (urlParams.has('end_date')) {
        endDateInput.value = urlParams.get('end_date');
    }
});

// Date inputs
document.addEventListener('DOMContentLoaded', function () {
    const searchType = document.getElementById('search-type');
    const dateFilters = document.getElementById('date-filters');
    const searchButton = document.getElementById('search-button');

    // Function to toggle visibility of date inputs
    function toggleDateInputs() {
        const selectedType = searchType.value;
        if (selectedType === 'groups' || selectedType === 'posts') {
            dateFilters.classList.remove('hidden');
        } else {
            dateFilters.classList.add('hidden');
        }
        searchButton.classList.remove('hidden');
    }

    // Initialize visibility on page load
    toggleDateInputs();

    // Add event listener to the dropdown
    searchType.addEventListener('change', toggleDateInputs);
});


// Preview search

document.addEventListener('DOMContentLoaded', function () {
    const searchBar = document.getElementById('search-bar');
    const searchType = document.getElementById('search-type');
    const resultsContainer = document.getElementById('search-results');

    searchBar.addEventListener('input', function () {
        const query = searchBar.value.trim();
        const type = searchType.value;

        if (query.length > 0) {
            fetch(`/search/preview?query=${encodeURIComponent(query)}&type=${encodeURIComponent(type)}`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(response => response.json())
            .then(data => {
                console.log(data);
                if (data && data.results) {
                    resultsContainer.innerHTML = ''; 
                    data.results.forEach(result => {
                        const resultItem = document.createElement('div');
                        resultItem.classList.add('px-4', 'py-2', 'cursor-pointer', 'hover:bg-gray-100');
                        console.log('TYPE: ' + type);
                        // types
                        if (type === 'users' || type === 'groups') {
                            console.log('REACHED THIS');
                            resultItem.textContent = result.name;
                        } else if (type === 'posts' || type === 'comments') {
                            resultItem.textContent = result.content || 'No content available'; 
                        }

                        resultsContainer.appendChild(resultItem);
                    });
                    resultsContainer.classList.remove('hidden'); 
                } else {
                    resultsContainer.innerHTML = '<p class="text-gray-500 px-4 py-2">No results found.</p>';
                }
            })
            .catch(error => console.error('Error fetching search results:', error));
        } else {
            resultsContainer.innerHTML = ''; 
            resultsContainer.classList.add('hidden'); 
        }
    });

    document.addEventListener('click', function (e) {
        if (!e.target.closest('#search-bar') && !e.target.closest('#search-results')) {
            resultsContainer.classList.add('hidden');
        }
    });
});


const postViewModalHandler = {
    async show(id) {
        const postViewModal = document.getElementById('post-view-modal');
        const modalPostImage = document.getElementById('modal-post-image');
        const modalUserImage = document.getElementById('modal-user-image');
        const modalUserName = document.getElementById('modal-user-name');
        const modalCreatedAt = document.getElementById('modal-created-at');
        const modalPostContent = document.getElementById('modal-post-content');
        const modalLikesCount = document.getElementById('modal-likes-count');
        const modalComments = document.getElementById('modal-comments');

        try {
            const response = await fetch(`/posts/${id}`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
            });

            if (!response.ok) throw new Error('Erro ao buscar os dados do post');

            const postData = await response.json();

            // Preencher o modal
            modalPostImage.src = `/storage/${postData.image_url}`;
            modalUserImage.src = `/storage/${postData.user_image}`;
            modalUserName.textContent = postData.user_name;
            modalCreatedAt.textContent = postData.created_at;
            modalPostContent.textContent = postData.content;
            modalLikesCount.textContent = `${postData.likes_count} Likes`;

            // Carregar os comentários
            modalComments.innerHTML = ''; // Limpa comentários anteriores
            const commentsResponse = await fetch(`/posts/${id}/comments`);
            if (commentsResponse.ok) {
                const commentsData = await commentsResponse.json();
                commentsData.comments.forEach(comment => {
                    modalComments.innerHTML += `
                        <div class="flex items-center">
                            <img src="/storage/${comment.user_image_url}" alt="User" class="w-8 h-8 rounded-full mr-3">
                            <p><strong>${comment.user_name}</strong>: ${comment.content}</p>
                        </div>
                    `;
                });
            }

            postViewModal.classList.remove('hidden');
        } catch (error) {
            console.error(error);
            alert('Erro ao carregar o post.');
        }
    },

    hide() {
        document.getElementById('post-view-modal').classList.add('hidden');
    },
};

// Botão para fechar o modal
document.getElementById('close-post-view-modal').addEventListener('click', () => {
    postViewModalHandler.hide();
});

window.showPostViewModal = postViewModalHandler.show;
window.hidePostViewModal = postViewModalHandler.hide;


function handlePostSubmission(event) {
    event.preventDefault();
    const form = document.getElementById('create-post-form');
    const formData = new FormData(form);

    fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => {
        if (!response.ok) throw new Error('Failed to create post');
        return response.json();
    })
    .then(data => {
        alert('Post created successfully');
        closeModal('create-post-modal');
        form.reset();
        const previewContainer = document.getElementById('preview-container');
        previewContainer.classList.add('hidden');
    })
    .catch(error => {
        console.error('Error creating post:', error);
        alert('Failed to create post. Please try again.');
    });
}

window.handlePostSubmission = handlePostSubmission;
