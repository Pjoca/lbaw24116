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

        // Update the button dynamically
        const button = document.querySelector(`[data-group-id="${groupId}"]`);
        if (button) {
            button.textContent = 'Cancel Membership Request';
            button.className = 'bg-gray-500 text-white py-2 px-4 rounded hover:bg-gray-600 transition';
            button.setAttribute('onclick', `cancelMembershipRequest(${groupId})`);
        }

        window.showFeedbackMessage('green', 'Membership request sent successfully!');
    } catch (error) {
        console.error(error);
        window.showFeedbackMessage('red', 'An error occurred while sending the membership request.');
    }
}

window.joinGroup = joinGroup;


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

        // Update the button dynamically
        const button = document.querySelector(`[data-group-id="${groupId}"]`);
        if (button) {
            button.textContent = 'Request to Join';
            button.className = 'bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600 transition';
            button.setAttribute('onclick', `joinGroup(${groupId})`);
        }

        window.showFeedbackMessage('green', 'Successfully left the group!');
        location.reload();
    } catch (error) {
        console.error(error);
        window.showFeedbackMessage('red', 'An error occurred while leaving the group.');
    }
}

window.leaveGroup = leaveGroup;


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
            button.className = 'bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600 transition';
            button.setAttribute('onclick', `joinGroup(${groupId})`);
        }

        window.showFeedbackMessage('green', 'Membership request canceled successfully!');
    } catch (error) {
        console.error(error);
        window.showFeedbackMessage('red', 'An error occurred while canceling the membership request.');
    }
}

window.cancelMembershipRequest = cancelMembershipRequest;

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
        const requestElement = document.querySelector(`[data-request-id="${requestId}"]`);
        if (requestElement) {
            requestElement.remove();
        }

        // Check if there are remaining requests
        const remainingRequests = document.querySelectorAll('[data-request-id]');
        if (remainingRequests.length === 0) {
            const requestListContainer = document.querySelector('#requests-list');
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
        const requestElement = document.querySelector(`[data-request-id="${requestId}"]`);
        if (requestElement) {
            requestElement.remove();
        }

        // Check if there are remaining requests
        const remainingRequests = document.querySelectorAll('[data-request-id]');
        if (remainingRequests.length === 0) {
            const requestListContainer = document.querySelector('#requests-list');
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

        // Remove the invite dynamically from the list
        const inviteElement = document.querySelector(`[data-invite-id="${inviteId}"]`);
        if (inviteElement) {
            inviteElement.remove();
        }

        window.showFeedbackMessage('green', 'Invite canceled successfully!');
    } catch (error) {
        console.error(error);
        window.showFeedbackMessage('red', 'An error occurred while canceling the invite.');
    }
}

window.cancelInvite = cancelInvite;

