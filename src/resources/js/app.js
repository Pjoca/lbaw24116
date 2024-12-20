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
};

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

    if (profileDropdown && profileDropdownToggle && !profileDropdownToggle.contains(event.target) && !profileDropdown.contains(event.target)) {
        profileDropdown.classList.add('hidden');
    }
});

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

window.showEditUserModal = modalHandler.show;
window.hideEditUserModal = modalHandler.hide;

const postModalHandler = {
    async show(id) {
        const editPostForm = document.getElementById('edit-post-form');
        const editPostContent = document.getElementById('edit-post-content');
        const editPostVisibility = document.getElementById('edit-post-visibility');
        const editPostModal = document.getElementById('edit-post-modal');

        try {
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

            if (editPostForm) editPostForm.action = `/posts/${id}`;
            if (editPostContent) editPostContent.value = postData.content || '';
            if (editPostVisibility) editPostVisibility.value = postData.visibility || 'public';

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

        if (editNameField) editNameField.value = user.name || '';
        if (editEmailField) editEmailField.value = user.email || '';
        if (editUsernameField) editUsernameField.value = user.username || '';
        if (editVisibilityField) editVisibilityField.value = user.visibility || 'public';

        modal.classList.remove('hidden');
    },

    hide() {
        const modal = document.getElementById('edit-profile-modal');
        if (modal) modal.classList.add('hidden');
    },
};

window.showEditProfileModal = profileModalHandler.show;
window.hideEditProfileModal = profileModalHandler.hide;

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

function handleDropdowns(event) {
    const allDropdowns = document.querySelectorAll('[id^="dropdownMenu-"]');
    const dropdownButton = event.target.closest('[id^="dropdownMenuButton-"]');

    if (dropdownButton) {
        const dropdownId = dropdownButton.id.replace('dropdownMenuButton-', '');
        const menu = document.getElementById(`dropdownMenu-${dropdownId}`);

        if (!menu) return;

        const isHidden = menu.classList.contains('hidden');

        allDropdowns.forEach(dropdown => dropdown.classList.add('hidden'));
        if (isHidden) menu.classList.remove('hidden');

        event.stopPropagation();
    } else {
        allDropdowns.forEach(menu => menu.classList.add('hidden'));
    }
}


function toggleDropdown(event, postId) {
    event.stopPropagation(); // Prevent event from bubbling to parent
    const menu = document.getElementById(`dropdownMenu-${postId}`);
    
    document.querySelectorAll('[id^="dropdownMenu-"]').forEach(dropdown => {
        if (dropdown !== menu) {
            dropdown.classList.add('hidden');
        }
    });

    if (menu) {
        menu.classList.toggle('hidden');
    }
}

document.addEventListener('click', function() {
    document.querySelectorAll('[id^="dropdownMenu-"]').forEach(menu => {
        menu.classList.add('hidden');
    });
});

window.toggleDropdown = toggleDropdown;

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
            updatePostContent(postId, data);
            postModalHandler.hide();
            window.showFeedbackMessage('green', 'Post updated successfully!');
        } catch (error) {
            console.error('Error updating post:', error);
            window.showFeedbackMessage('red', 'An error occurred while updating the post.');
        }
    });
}

function updatePostContent(postId, data) {
    const postDiv = document.querySelector(`[data-id="${postId}"]`);
    if (!postDiv) return;

    const contentParagraph = postDiv.querySelector('p.text-lg');
    if (contentParagraph) contentParagraph.textContent = data.content;
    const visibilityField = postDiv.querySelector('.post-visibility');
    if (visibilityField) visibilityField.textContent = data.visibility;
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

function addEventListeners() {
    document.addEventListener('click', handleDropdowns);
    handleImagePreview();
    handleEditPostSubmission();
    initializeMessageHandler();
}

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

        // Check if the friend is being displayed in the modal
        const friendElementInModal = document.getElementById(`friend-${userId}`);
        if (friendElementInModal) {
            // Remove the friend from the modal list
            friendElementInModal.remove();
        } else {
            // If not in the modal, change the button to 'Add Friend'
            const button = document.querySelector(`[data-user-id="${userId}"]`);
            if (button) {
                button.textContent = 'Add Friend';
                button.className = 'bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600 transition';
                button.setAttribute('onclick', `addFriend(${userId})`);
            }
        }

        window.showFeedbackMessage('green', 'Friend removed successfully!');
    } catch (error) {
        console.error('Error removing friend:', error);
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

        const button = document.querySelector(`[data-user-id="${userId}"]`);
        if (button) {
            button.textContent = 'Add Friend';
            button.className = 'bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600 transition';
            button.setAttribute('onclick', `addFriend(${userId})`);
        }

        const sentRequestItem = document.querySelector(`#sent-request-${userId}`);
        if (sentRequestItem) {
            sentRequestItem.remove();
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
        window.reloadP("showFeedbackMessage('green', 'Friend request accepted!');");
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
        const inviteElement = document.querySelector(`#group-invites-list li[data-invite-id="${inviteId}"]`);
        if (inviteElement) {
            inviteElement.remove();
        }

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
        const inviteElement = document.querySelector(`#group-invites-list li[data-invite-id="${inviteId}"]`);
        if (inviteElement) {
            inviteElement.remove();
        }

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
        notificationElement.remove();
        const notificationCountElement = document.getElementById('notification-count');
        if (notificationCountElement) {
            let currentCount = parseInt(notificationCountElement.textContent) || 0;
            currentCount = Math.max(currentCount - 1, 0);
            notificationCountElement.textContent = currentCount;
            if (currentCount === 0) {
                notificationCountElement.classList.add('hidden');
            }
        }
        const notificationList = document.getElementById('notification-list');
        if (notificationList.children.length === 0) {
            notificationList.innerHTML = '<li class="text-center p-4 text-gray-500">No new notifications</li>';
        }
    })
    .catch(error => {
        console.error('Error deleting notification:', error);
    });
}

window.deleteNotification = deleteNotification;

// Search
document.addEventListener('DOMContentLoaded', function () {
    const urlParams = new URLSearchParams(window.location.search);
    const typeSelect = document.getElementById('search-type');
    if (typeSelect && urlParams.has('type')) {
        typeSelect.value = urlParams.get('type');
    }

    const searchQuery = document.getElementById('search-bar');
    if (searchQuery && urlParams.has('query')) {
        searchQuery.value = urlParams.get('query');
    }

    const startDateInput = document.getElementById('start-date');
    if (startDateInput && urlParams.has('start_date')) {
        startDateInput.value = urlParams.get('start_date');
    }

    const endDateInput = document.getElementById('end-date');
    if (endDateInput && urlParams.has('end_date')) {
        endDateInput.value = urlParams.get('end_date');
    }
});

document.addEventListener('DOMContentLoaded', function () {
    const searchType = document.getElementById('search-type');
    const dateFilters = document.getElementById('date-filters');
    const searchButton = document.getElementById('search-button');

    function toggleDateInputs() {
        if (!searchType) return;
        const selectedType = searchType.value;
        if (selectedType === 'groups' || selectedType === 'posts') {
            if (dateFilters) dateFilters.classList.remove('hidden');
        } else {
            if (dateFilters) dateFilters.classList.add('hidden');
        }
        if (searchButton) searchButton.classList.remove('hidden');
    }

    if (searchType) {
        toggleDateInputs();
        searchType.addEventListener('change', toggleDateInputs);
    }
});



const postViewModalHandler = {
    async show(id) {
        const postViewModal = document.getElementById('post-view-modal');
        postViewModal.setAttribute('data-post-id', id);
    
        const modalPostImage = document.getElementById('modal-post-image');
        const modalUserImage = document.getElementById('modal-user-image');
        const modalUserName = document.getElementById('modal-user-name');
        const modalCreatedAt = document.getElementById('modal-created-at');
        const modalPostContent = document.getElementById('modal-post-content');
        const modalLikesCount = document.getElementById('modal-likes-count');
        const modalComments = document.getElementById('modal-comments');
        const modalLikeButton = document.getElementById('modal-like-button');
    
        let currentPage = 1;
    
        try {
            const response = await fetch(`/posts/${id}`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
            });
    
            if (!response.ok) throw new Error('Erro ao buscar os dados do post');
    
            const postData = await response.json();
    
            modalPostImage.src = postData.image_url ? `/storage/${postData.image_url}` : '';
            modalUserImage.src = `/storage/${postData.user_image}`;
            modalUserName.textContent = postData.user_name;
            modalCreatedAt.textContent = postData.created_at;
            modalPostContent.textContent = postData.content;
            modalLikesCount.textContent = `${postData.likes_count}`;
    
            updateLikeButton(modalLikeButton, postData.liked_by_current_user);
    
            modalComments.innerHTML = '';
    
            await loadComments(id, currentPage);
    
            postViewModal.classList.remove('hidden');
            postViewModal.classList.add('flex');
    
            modalLikeButton.onclick = async () => {
                const data = await toggleLike(id);
                updateLikeButton(modalLikeButton, data.liked);
                modalLikesCount.textContent = `${data.likesCount}`;
            };
    
        } catch (error) {
            console.error(error);
            alert('Erro ao carregar o post.');
        }
    
        async function loadComments(postId, page) {
            try {
                const commentsResponse = await fetch(`/posts/${postId}/comments?page=${page}`, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' },
                });
    
                if (!commentsResponse.ok) throw new Error('Erro ao carregar os comentários.');
    
                const commentsData = await commentsResponse.json();
                
                commentsData.comments.forEach(comment => {
                    modalComments.innerHTML += `
                        <div class="bg-gray-100 p-4 rounded-lg mb-2" id="modal-comment-${comment.id}">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <img src="/storage/${comment.user_image_url}" class="w-8 h-8 rounded-full mr-3">
                                    <span class="font-semibold text-gray-800">${comment.user_name}</span>
                                </div>
                                ${comment.editable ? `
                                <div class="flex space-x-2">
                                    <button type="button" onclick="openModalInlineEdit(${comment.id}, '${comment.content.replace(/'/g, "\\'")}')" class="text-blue-500 hover:text-blue-700">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 20h9M16.5 3.5a2.121 2.121 0 010 3L7 16.5 3 17l.5-4L16.5 3.5z" />
                                        </svg>
                                    </button>
                                    <button onclick="deleteModalComment(${comment.id})" class="text-red-500 hover:text-red-700">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 6h18M9 6v12m6-12v12m-9 0a2 2 0 002 2h6a2 2 0 002-2V6M5 6h14M10 6v12M14 6v12M8 6h8" />
                                        </svg>
                                    </button>
                                </div>
                                ` : ''}
                            </div>
                            <p class="mt-2 text-gray-700" id="modal-commentContent-${comment.id}">${comment.content}</p>
                        </div>
                    `;
                });
            } catch (error) {
                console.error('Erro ao carregar os comentários:', error);
            }
        }
    },
    
    hide() {
        const postViewModal = document.getElementById('post-view-modal');
        postViewModal.classList.add('hidden');
    },
};

window.showPostViewModal = postViewModalHandler.show;
window.hidePostViewModal = postViewModalHandler.hide;

/**
 * Update the like button's SVG icon based on like status
 * 
 * @param {Element} button 
 * @param {boolean} isLiked 
 */
function updateLikeButton(button, isLiked) {
    const svg = button.querySelector('svg');
    svg.setAttribute('class', 'w-6 h-6');
    
    if (isLiked) {
        svg.setAttribute('viewBox', '0 0 24 24');
        svg.setAttribute('fill', 'currentColor');
        svg.setAttribute('stroke', 'none');
        svg.setAttribute('stroke-width', '0');
        svg.setAttribute('class', 'w-6 h-6 text-red-500');
        svg.innerHTML = `
            <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
        `;
    } else {
        svg.setAttribute('viewBox', '0 0 24 24');
        svg.setAttribute('fill', 'none');
        svg.setAttribute('stroke', 'currentColor');
        svg.setAttribute('stroke-width', '2');
        svg.setAttribute('class', 'w-6 h-6 text-gray-500');
        svg.innerHTML = `
            <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
        `;
    }
}




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
        if (previewContainer) previewContainer.classList.add('hidden');
    })
    .catch(error => {
        console.error('Error creating post:', error);
        alert('Failed to create post. Please try again.');
    });
}

window.handlePostSubmission = handlePostSubmission;

document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('search-bar');
    const searchType = document.getElementById('search-type');
    const resultsContainer = document.getElementById('search-results');

    if (searchInput && resultsContainer && searchType) {
        searchInput.addEventListener('input', function () {
            const query = searchInput.value;
            const type = searchType.value;

            if (query.length < 2) {
                resultsContainer.innerHTML = '';
                resultsContainer.classList.add('hidden');
                return;
            }

            fetch(`{{ route('search.preview') }}?query=${query}&type=${type}`)
                .then(response => response.json())
                .then(data => {
                    resultsContainer.innerHTML = '';
                    if (data.length > 0) {
                        resultsContainer.classList.remove('hidden');
                        data.forEach(item => {
                            let content = item.name || item.content;
                            const resultItem = document.createElement('div');
                            resultItem.classList.add('px-4', 'py-2', 'hover:bg-gray-100', 'cursor-pointer');
                            resultItem.textContent = content;
                            resultsContainer.appendChild(resultItem);
                        });
                    } else {
                        resultsContainer.classList.add('hidden');
                    }
                })
                .catch(error => console.error('Error fetching results:', error));
        });

        document.addEventListener('click', function (event) {
            if (!resultsContainer.contains(event.target) && event.target !== searchInput) {
                resultsContainer.classList.add('hidden');
            }
        });
    }
});
