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


window.openModal = openModal;
window.closeModal = closeModal;


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




window.loadInitialJoinRequests = loadInitialJoinRequests;
window.loadInitialInvites = loadInitialInvites;


function showSection(section) {
    const timelineTab = document.getElementById('timeline-tab');
    const membersTab = document.getElementById('members-tab');
    const timelineSection = document.getElementById('timeline-section');
    const membersSection = document.getElementById('members-section');

    if (section === 'timeline') {
        timelineSection.classList.remove('hidden');
        membersSection.classList.add('hidden');
        timelineTab.classList.add('border-teal-500');
        timelineTab.classList.remove('border-gray-300');
        membersTab.classList.add('border-gray-300');
        membersTab.classList.remove('border-teal-500');
    } else if (section === 'members') {
        membersSection.classList.remove('hidden');
        timelineSection.classList.add('hidden');
        membersTab.classList.add('border-teal-500');
        membersTab.classList.remove('border-gray-300');
        timelineTab.classList.add('border-gray-300');
        timelineTab.classList.remove('border-teal-500');
    }
}

// Show Timeline by default on page load
document.addEventListener('DOMContentLoaded', function () {
    showSection('timeline');
});

window.showSection = showSection;


let nextPageUrlMembers = null;
let isLoading = false;

async function loadInitialMembers(groupId) {
    console.log('loadInitialMembers called for groupId:', groupId);

    const membersList = document.getElementById('members-list');
    const loader = document.getElementById('members-loader');

    if (!membersList) {
        console.error('No element with id="members-list" found.');
        return;
    }

    try {
        console.log('Displaying loader');
        loader.classList.remove('hidden');
        
        // Cache-busting URL to prevent stale responses
        const fetchUrl = `/groups/${groupId}/members?timestamp=${new Date().getTime()}`;
        console.log('Fetching members from URL:', fetchUrl);

        const response = await fetch(fetchUrl, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
        });

        console.log(`Response status: ${response.status} - ${response.statusText}`);
        
        if (!response.ok) {
            console.error('Network error occurred while fetching members:', response.statusText);
            throw new Error(`Failed to fetch members. Status: ${response.status}`);
        }

        const data = await response.json();
        console.log('Response JSON data:', data);

        if (!data.html || data.html.trim() === '') {
            console.warn('No members loaded. Empty response.');
            loader.classList.add('hidden'); // Hide loader
            return;
        }

        console.log('Clearing old members from membersList');
        membersList.innerHTML = ''; // Clear old content

        console.log('Inserting new HTML into membersList');
        membersList.insertAdjacentHTML('beforeend', data.html); // Insert new content

        nextPageUrlMembers = data.nextPageUrl;

        if (!data.hasMorePages) {
            console.log('No more pages to load. Hiding loader.');
            loader.classList.add('hidden');
        } else {
            console.log('There are more pages to load.');
        }
    } catch (error) {
        console.error('Error loading members:', error.message);
        console.error('Stack Trace:', error.stack);
    } finally {
        console.log('Hiding loader');
        loader.classList.add('hidden');
    }
}

async function loadMoreMembers(groupId) {
    if (!nextPageUrlMembers || isLoading) return;

    console.log('Loading more members from URL:', nextPageUrlMembers);
    const membersList = document.getElementById('members-list');
    const loader = document.getElementById('members-loader');

    if (!membersList) {
        console.error('No element with id="members-list" found.');
        return;
    }

    try {
        isLoading = true;
        loader.classList.remove('hidden');

        const response = await fetch(nextPageUrlMembers, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
        });

        if (!response.ok) {
            console.error('Network error occurred while fetching more members:', response.statusText);
            throw new Error(`Failed to fetch more members. Status: ${response.status}`);
        }

        const data = await response.json();
        console.log('Response JSON data for more members:', data);

        if (!data.html || data.html.trim() === '') {
            console.warn('No more members loaded. Empty response.');
            loader.classList.add('hidden');
            return;
        }

        membersList.insertAdjacentHTML('beforeend', data.html); // Insert new members
        nextPageUrlMembers = data.nextPageUrl;

        if (!data.hasMorePages) {
            console.log('No more pages to load.');
            loader.classList.add('hidden');
        }
    } catch (error) {
        console.error('Error loading more members:', error.message);
        console.error('Stack Trace:', error.stack);
    } finally {
        loader.classList.add('hidden');
        isLoading = false;
    }
}

window.addEventListener('scroll', () => {
    const membersSection = document.getElementById('members-section');
    if (!membersSection || membersSection.classList.contains('hidden')) return;

    const nearBottom = window.innerHeight + window.scrollY >= document.body.offsetHeight - 200;

    if (nearBottom && nextPageUrlMembers) {
        const groupId = document.getElementById('group-action-button').dataset.groupId;
        loadMoreMembers(groupId);
    }
});


window.loadInitialMembers = loadInitialMembers;
window.loadMoreMembers = loadMoreMembers;