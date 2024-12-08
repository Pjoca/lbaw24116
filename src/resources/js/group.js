async function joinGroup(groupId) {
    try {
        const response = await fetch(`/groups/${groupId}/join`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
        });

        if (!response.ok) throw new Error('Failed to join group');

        const data = await response.json();

        if (data.reload) {
            // If reload is true, reload the page to reflect changes
            window.location.reload();
            return;
        }

        // Update the button dynamically if membership request is sent
        const button = document.querySelector(`[data-group-id="${groupId}"]`);
        if (button) {
            button.textContent = 'Cancel Membership Request';
            button.className = 'bg-gray-500 text-white py-2 px-4 rounded shadow hover:bg-gray-600 transition';
            button.setAttribute('onclick', `cancelMembershipRequest(${groupId})`);
        }

        window.showFeedbackMessage('green', data.success);
    } catch (error) {
        console.error(error);
        window.showFeedbackMessage('red', 'An error occurred while sending the membership request.');
    }
}

window.joinGroup = joinGroup;


async function cancelMembershipRequest(groupId) {
    try {
        const response = await fetch(`/groups/${groupId}/cancel`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
        });

        if (!response.ok) throw new Error('Failed to cancel membership request');
        const data = await response.json();

        // Update the button dynamically
        const button = document.querySelector(`[data-group-id="${groupId}"]`);
        if (button) {
            button.textContent = 'Request to Join';
            button.className = 'bg-blue-500 text-white py-2 px-4 rounded shadow hover:bg-blue-600 transition';
            button.setAttribute('onclick', `joinGroup(${groupId})`);
        }

        window.showFeedbackMessage('green', 'Membership request canceled successfully!');
    } catch (error) {
        console.error(error);
        window.showFeedbackMessage('red', 'An error occurred while canceling the membership request.');
    }
}

window.cancelMembershipRequest = cancelMembershipRequest;


async function leaveGroup(groupId) {
    try {
        const response = await fetch(`/groups/${groupId}/leave`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
        });

        if (!response.ok) throw new Error('Failed to leave group');
        const data = await response.json();

        window.showFeedbackMessage('green', 'Successfully left the group!');
        window.location.reload();
    } catch (error) {
        console.error(error);
        window.showFeedbackMessage('red', 'An error occurred while leaving the group.');
    }
}

window.leaveGroup = leaveGroup;

// Function to accept a membership request
async function acceptMembershipRequest(requestId) {
    try {
        const response = await fetch(`/groups/request/${requestId}/accept`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
        });

        if (!response.ok) throw new Error('Failed to accept membership request');
        const data = await response.json();

        // Remove the request from the list dynamically
        const requestElement = document.querySelector(`#join-request-${requestId}`);
        if (requestElement) {
            requestElement.remove();
        }

        // Check if there are remaining requests
        const remainingRequests = document.querySelectorAll('#join-requests-list > li');
        if (remainingRequests.length === 0) {
            const requestListContainer = document.querySelector('#join-requests-list');
            if (requestListContainer) {
                requestListContainer.innerHTML = '<p class="text-gray-500">No membership requests received.</p>';
            }
        }

        window.showFeedbackMessage('green', 'Membership request accepted successfully!');
    } catch (error) {
        console.error(error);
        window.showFeedbackMessage('red', 'An error occurred while accepting the membership request.');
    }
}


window.acceptMembershipRequest = acceptMembershipRequest;

// Function to reject a membership request
async function rejectMembershipRequest(requestId) {
    try {
        const response = await fetch(`/groups/request/${requestId}/reject`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
        });

        if (!response.ok) throw new Error('Failed to reject membership request');
        const data = await response.json();

        // Remove the request from the list dynamically
        const requestElement = document.querySelector(`#join-request-${requestId}`);
        if (requestElement) {
            requestElement.remove();
        }

        // Check if there are remaining requests
        const remainingRequests = document.querySelectorAll('#join-requests-list > li');
        if (remainingRequests.length === 0) {
            const requestListContainer = document.querySelector('#join-requests-list');
            if (requestListContainer) {
                requestListContainer.innerHTML = '<p class="text-gray-500">No membership requests received.</p>';
            }
        }

        window.showFeedbackMessage('green', 'Membership request rejected successfully!');
    } catch (error) {
        console.error(error);
        window.showFeedbackMessage('red', 'An error occurred while rejecting the membership request.');
    }
}

window.rejectMembershipRequest = rejectMembershipRequest;


async function sendGroupInvite(groupId) {
    const usernameOrEmail = document.getElementById('invite-username').value.trim();

    if (!usernameOrEmail) {
        window.showFeedbackMessage('red', 'Please enter a username or email.');
        return;
    }

    try {
        const response = await fetch(`/groups/${groupId}/invite`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: JSON.stringify({ usernameOrEmail }),
        });

        if (!response.ok) {
            const errorData = await response.json();
            throw new Error(errorData.error || 'Failed to send invite');
        }

        const data = await response.json();

        // Clear the input field
        document.getElementById('invite-username').value = '';

        // Add the new invite to the invites list dynamically
        const invitesList = document.querySelector('#invites-list'); // Correct selector using ID
        if (invitesList) {
            const newInviteItem = document.createElement('li');
            newInviteItem.className = 'flex items-center justify-between';
            newInviteItem.innerHTML = `
                <span>${data.username}</span>
                <div class="space-x-2">
                    <button onclick="cancelInvite(${data.invite_id})" 
                            class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 transition">
                        Cancel Invite
                    </button>
                </div>
            `;
            invitesList.appendChild(newInviteItem);
        }

        // Show success message
        window.showFeedbackMessage('green', data.success || 'Invite sent successfully!');
    } catch (error) {
        console.error(error);
        window.showFeedbackMessage('red', error.message || 'An error occurred while sending the invite.');
    }
}

window.sendGroupInvite = sendGroupInvite;





async function cancelInvite(inviteId) {
    try {
        const response = await fetch(`/groups/invite/${inviteId}/cancel`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
        });

        if (!response.ok) throw new Error('Failed to cancel invite');
        const data = await response.json();

        // Correct the selector to target the element properly
        const inviteElement = document.querySelector(`#invite-${inviteId}`);
        if (inviteElement) {
            inviteElement.remove();
        }

        // Check if there are remaining invites
        const remainingInvites = document.querySelectorAll('#invites-list > li');
        if (remainingInvites.length === 0) {
            const invitesListContainer = document.querySelector('#invites-list');
            if (invitesListContainer) {
                invitesListContainer.innerHTML = '<p class="text-gray-500">No pending invites.</p>';
            }
        }

        window.showFeedbackMessage('green', 'Invite canceled successfully!');
    } catch (error) {
        console.error(error);
        window.showFeedbackMessage('red', 'An error occurred while canceling the invite.');
    }
}


window.cancelInvite = cancelInvite;


function removeMember(groupId, memberId) {
    if (!confirm("Are you sure you want to remove this member?")) {
        return;
    }

    fetch(`/groups/${groupId}/members/${memberId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        },
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(data => {
                throw new Error(data.error || "An error occurred");
            });
        }
        return response.json();
    })
    .then(data => {
        // Display success feedback
        window.showFeedbackMessage('green', 'Member removed successfully!');

        // Remove the member dynamically from the DOM
        const memberElement = document.getElementById(`member-${memberId}`);
        if (memberElement) {
            memberElement.remove();
        }
    })
    .catch(error => {
        console.error("Error:", error.message);
        // Display error feedback
        window.showFeedbackMessage('red', error.message);
    });
}


window.removeMember = removeMember;


let nextPageUrl = null;

function openModal(modalId) {
    console.log(`Opening modal: ${modalId}`);
    const modal = document.getElementById(modalId);
    if (modal) {
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

function loadMoreMembers(groupId) {
    const modal = document.getElementById('members-modal');
    const nextPageUrl = modal.getAttribute('data-next-page-url');

    // Debugging step 1: Log the nextPageUrl
    console.log('Fetching members from:', nextPageUrl);

    if (!nextPageUrl) {
        console.error('No nextPageUrl found');
        return;
    }

    fetch(nextPageUrl, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
        .then(response => {
            console.log('Response status:', response.status); // Log response status
            return response.text(); // Parse as text first
        })
        .then(data => {
            console.log('Raw response:', data); // Log raw response
            const jsonData = JSON.parse(data); // Attempt to parse as JSON
            const membersList = document.getElementById('members-list');
            membersList.insertAdjacentHTML('beforeend', jsonData.html);
    
            if (jsonData.hasMorePages) {
                modal.setAttribute('data-next-page-url', jsonData.nextPageUrl);
            } else {
                document.getElementById('load-more-members').remove();
            }
        })
        .catch(error => console.error('Error loading members:', error));
}



window.openModal = openModal;
window.closeModal = closeModal;
window.loadMoreMembers = loadMoreMembers;


function loadMoreJoinRequests(groupId) {
    const modal = document.getElementById('join-requests-modal');
    const nextPageUrl = modal.getAttribute('data-next-page-url');

    if (!nextPageUrl) return;

    fetch(nextPageUrl, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
        .then(response => response.json())
        .then(data => {
            const requestsList = document.getElementById('join-requests-list');
            requestsList.insertAdjacentHTML('beforeend', data.html);

            if (data.hasMorePages) {
                modal.setAttribute('data-next-page-url', data.nextPageUrl);
            } else {
                document.getElementById('load-more-join-requests').remove();
            }
        })
        .catch(error => console.error('Error loading more join requests:', error));
}


window.loadMoreJoinRequests = loadMoreJoinRequests;


function loadMoreInvites(groupId) {
    const modal = document.getElementById('invites-modal');
    const nextPageUrl = modal.getAttribute('data-next-page-url');

    if (!nextPageUrl) return;

    fetch(nextPageUrl, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
        .then(response => {
            if (!response.ok) {
                throw new Error('Failed to load more invites');
            }
            return response.json();
        })
        .then(data => {
            const invitesList = document.getElementById('invites-list');
            invitesList.insertAdjacentHTML('beforeend', data.html);

            if (data.hasMorePages) {
                modal.setAttribute('data-next-page-url', data.nextPageUrl);
            } else {
                document.getElementById('load-more-invites').remove();
            }
        })
        .catch(error => {
            console.error('Error loading more invites:', error.message);
            window.showFeedbackMessage('red', 'Error loading more invites');
        });
}


window.loadMoreInvites = loadMoreInvites;


async function loadInitialMembers(groupId) {
    const modal = document.getElementById('members-modal');
    const membersList = document.getElementById('members-list');
    const loadMoreButtonContainer = modal.querySelector('.mt-4');
    let loadMoreButton = document.getElementById('load-more-members');

    try {
        const response = await fetch(`/groups/${groupId}/members`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
        });

        if (!response.ok) throw new Error('Failed to fetch members.');

        const data = await response.json();
        membersList.innerHTML = data.html;
        modal.setAttribute('data-next-page-url', data.nextPageUrl || '');

        if (data.hasMorePages) {
            if (!loadMoreButton) {
                loadMoreButton = document.createElement('button');
                loadMoreButton.id = 'load-more-members';
                loadMoreButton.textContent = 'Load More';
                loadMoreButton.className = 'w-full bg-teal-500 text-white px-3 py-2 rounded hover:bg-teal-600 transition';
                loadMoreButton.onclick = () => loadMoreMembers(groupId);
                loadMoreButtonContainer.appendChild(loadMoreButton);
            } else {
                loadMoreButton.classList.remove('hidden');
            }
        } else if (loadMoreButton) {
            loadMoreButton.classList.add('hidden');
        }
    } catch (error) {
        console.error('Error loading members:', error.message);
    }
}





async function loadInitialJoinRequests(groupId) {
    const modal = document.getElementById('join-requests-modal');
    const requestsList = document.getElementById('join-requests-list');
    const loadMoreButtonContainer = modal.querySelector('.mt-4');
    let loadMoreButton = document.getElementById('load-more-join-requests');

    try {
        const response = await fetch(`/groups/${groupId}/join-requests`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
        });

        if (!response.ok) throw new Error('Failed to fetch join requests.');

        const data = await response.json();
        requestsList.innerHTML = data.html;
        modal.setAttribute('data-next-page-url', data.nextPageUrl || '');

        if (data.hasMorePages) {
            if (!loadMoreButton) {
                loadMoreButton = document.createElement('button');
                loadMoreButton.id = 'load-more-join-requests';
                loadMoreButton.textContent = 'Load More';
                loadMoreButton.className = 'w-full bg-teal-500 text-white px-3 py-2 rounded hover:bg-teal-600 transition';
                loadMoreButton.onclick = () => loadMoreJoinRequests(groupId);
                loadMoreButtonContainer.appendChild(loadMoreButton);
            } else {
                loadMoreButton.classList.remove('hidden');
            }
        } else if (loadMoreButton) {
            loadMoreButton.classList.add('hidden');
        }
    } catch (error) {
        console.error('Error loading join requests:', error.message);
    }
}



async function loadInitialInvites(groupId) {
    const modal = document.getElementById('invites-modal');
    const invitesList = document.getElementById('invites-list');
    const loadMoreButtonContainer = modal.querySelector('.mt-4');
    let loadMoreButton = document.getElementById('load-more-invites');

    try {
        const response = await fetch(`/groups/${groupId}/invites`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
        });

        if (!response.ok) throw new Error('Failed to fetch invites.');

        const data = await response.json();
        invitesList.innerHTML = data.html;
        modal.setAttribute('data-next-page-url', data.nextPageUrl || '');

        if (data.hasMorePages) {
            if (!loadMoreButton) {
                loadMoreButton = document.createElement('button');
                loadMoreButton.id = 'load-more-invites';
                loadMoreButton.textContent = 'Load More';
                loadMoreButton.className = 'w-full bg-teal-500 text-white px-3 py-2 rounded hover:bg-teal-600 transition';
                loadMoreButton.onclick = () => loadMoreInvites(groupId);
                loadMoreButtonContainer.appendChild(loadMoreButton);
            } else {
                loadMoreButton.classList.remove('hidden');
            }
        } else if (loadMoreButton) {
            loadMoreButton.classList.add('hidden');
        }
    } catch (error) {
        console.error('Error loading invites:', error.message);
    }
}




window.loadInitialMembers = loadInitialMembers;
window.loadInitialJoinRequests = loadInitialJoinRequests;
window.loadInitialInvites = loadInitialInvites;
