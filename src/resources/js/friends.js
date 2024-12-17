function openModal(modalId) {
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
    if (modal) {
        modal.classList.add('hidden');
    } else {
        console.error(`Modal with ID ${modalId} not found.`);
    }
}

async function loadInitialReceivedFriendRequests(userId) {
    const modal = document.getElementById('friend-requests-modal');
    const receivedRequestsList = document.getElementById('friend-requests-list');
    const loadMoreReceivedButtonContainer = modal.querySelector('.load-more-received-container');
    let loadMoreReceivedButton = document.getElementById('load-more-received-requests');

    try {
        console.log(`Fetching received friend requests for user ${userId}`);

        const response = await fetch(`/friends/requests/received/${userId}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });

        if (!response.ok) throw new Error('Failed to fetch received friend requests.');

        const data = await response.json();

        receivedRequestsList.innerHTML = ''; // Clear old content

        if (!data.html || data.html.trim() === '') {
            console.log('No received friend requests found');
            receivedRequestsList.innerHTML = `<p class="text-gray-500 text-center">No friend requests at the moment.</p>`;
        } else {
            console.log('Received friend requests loaded successfully');
            receivedRequestsList.insertAdjacentHTML('beforeend', data.html);
        }

        if (data.hasMorePages) {
            if (!loadMoreReceivedButton) {
                loadMoreReceivedButton = document.createElement('button');
                loadMoreReceivedButton.id = 'load-more-received-requests';
                loadMoreReceivedButton.textContent = 'Load More';
                loadMoreReceivedButton.className = 'w-full bg-teal-500 text-white px-3 py-2 rounded hover:bg-teal-600 transition';
                loadMoreReceivedButton.onclick = () => loadMoreReceivedFriendRequests();
                loadMoreReceivedButtonContainer.appendChild(loadMoreReceivedButton);
            } else {
                loadMoreReceivedButton.classList.remove('hidden');
            }
            modal.setAttribute('data-next-received-page-url', data.nextPageUrl);
        } else if (loadMoreReceivedButton) {
            loadMoreReceivedButton.classList.add('hidden');
        }
    } catch (error) {
        console.error('Error loading received friend requests:', error.message);
    }
}

async function loadMoreReceivedFriendRequests() {
    const modal = document.getElementById('friend-requests-modal');
    const nextPageUrl = modal.getAttribute('data-next-received-page-url');
    const receivedRequestsList = document.getElementById('friend-requests-list');

    if (!nextPageUrl) return;

    try {
        const response = await fetch(nextPageUrl, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });

        if (!response.ok) throw new Error('Failed to load more received friend requests.');

        const data = await response.json();

        receivedRequestsList.insertAdjacentHTML('beforeend', data.html);

        if (data.hasMorePages) {
            modal.setAttribute('data-next-received-page-url', data.nextPageUrl);
        } else {
            document.getElementById('load-more-received-requests').remove();
        }
    } catch (error) {
        console.error('Error loading more received friend requests:', error.message);
    }
}

async function loadInitialSentFriendRequests(userId) {
    const modal = document.getElementById('friend-requests-modal');
    const sentRequestsList = document.getElementById('sent-requests-list');
    const loadMoreSentButtonContainer = modal.querySelector('.load-more-sent-container');
    let loadMoreSentButton = document.getElementById('load-more-sent-requests');

    try {
        console.log(`Fetching sent friend requests for user ${userId}`);

        const response = await fetch(`/friends/requests/sent/${userId}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });

        if (!response.ok) throw new Error('Failed to fetch sent friend requests.');

        const data = await response.json();

        sentRequestsList.innerHTML = ''; // Clear old content

        if (!data.html || data.html.trim() === '') {
            console.log('No sent friend requests found');
            sentRequestsList.innerHTML = `<p class="text-gray-500 text-center">No sent friend requests at the moment.</p>`;
        } else {
            console.log('Sent friend requests loaded successfully');
            sentRequestsList.insertAdjacentHTML('beforeend', data.html);
        }

        if (data.hasMorePages) {
            if (!loadMoreSentButton) {
                loadMoreSentButton = document.createElement('button');
                loadMoreSentButton.id = 'load-more-sent-requests';
                loadMoreSentButton.textContent = 'Load More';
                loadMoreSentButton.className = 'w-full bg-teal-500 text-white px-3 py-2 rounded hover:bg-teal-600 transition';
                loadMoreSentButton.onclick = () => loadMoreSentFriendRequests();
                loadMoreSentButtonContainer.appendChild(loadMoreSentButton);
            } else {
                loadMoreSentButton.classList.remove('hidden');
            }
            modal.setAttribute('data-next-sent-page-url', data.nextPageUrl);
        } else if (loadMoreSentButton) {
            loadMoreSentButton.classList.add('hidden');
        }
    } catch (error) {
        console.error('Error loading sent friend requests:', error.message);
    }
}

async function loadMoreSentFriendRequests() {
    const modal = document.getElementById('friend-requests-modal');
    const nextPageUrl = modal.getAttribute('data-next-sent-page-url');
    const sentRequestsList = document.getElementById('sent-requests-list');

    if (!nextPageUrl) return;

    try {
        const response = await fetch(nextPageUrl, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });

        if (!response.ok) throw new Error('Failed to load more sent friend requests.');

        const data = await response.json();

        sentRequestsList.insertAdjacentHTML('beforeend', data.html);

        if (data.hasMorePages) {
            modal.setAttribute('data-next-sent-page-url', data.nextPageUrl);
        } else {
            document.getElementById('load-more-sent-requests').remove();
        }
    } catch (error) {
        console.error('Error loading more sent friend requests:', error.message);
    }
}



// Export functions for use
window.openModal = openModal;
window.closeModal = closeModal;

window.loadInitialReceivedFriendRequests = loadInitialReceivedFriendRequests;
window.loadMoreReceivedFriendRequests = loadMoreReceivedFriendRequests;

window.loadInitialSentFriendRequests = loadInitialSentFriendRequests;
window.loadMoreSentFriendRequests = loadMoreSentFriendRequests;


async function loadInitialGroupInvites(userId) {
    const modal = document.getElementById('group-invites-modal');
    const invitesList = document.getElementById('group-invites-list');
    const loadMoreButtonContainer = modal.querySelector('.load-more-invites-container');
    let loadMoreButton = document.getElementById('load-more-group-invites');

    try {
        console.log(`Fetching group invites for user ${userId}`);

        const response = await fetch(`/groups/invites/${userId}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });

        if (!response.ok) throw new Error('Failed to fetch group invites.');

        const data = await response.json();

        // Clear existing list before loading
        invitesList.innerHTML = ''; 

        if (!data.html || data.html.trim() === '') {
            console.log('No group invites found');
            invitesList.innerHTML = `<p class="text-gray-500 text-center">No group invites at the moment.</p>`;
        } else {
            console.log('Group invites loaded successfully');
            invitesList.insertAdjacentHTML('beforeend', data.html); // Insert HTML from response
        }

        if (data.hasMorePages) {
            if (!loadMoreButton) {
                loadMoreButton = document.createElement('button');
                loadMoreButton.id = 'load-more-group-invites';
                loadMoreButton.textContent = 'Load More';
                loadMoreButton.className = 'w-full bg-teal-500 text-white px-3 py-2 rounded hover:bg-teal-600 transition';
                loadMoreButton.onclick = () => loadMoreGroupInvites();
                loadMoreButtonContainer.appendChild(loadMoreButton);
            } else {
                loadMoreButton.classList.remove('hidden');
            }
            modal.setAttribute('data-next-group-invites-url', data.nextPageUrl);
        } else if (loadMoreButton) {
            loadMoreButton.classList.add('hidden');
        }
    } catch (error) {
        console.error('Error loading group invites:', error.message);
    }
}

async function loadMoreGroupInvites() {
    const modal = document.getElementById('group-invites-modal');
    const nextPageUrl = modal.getAttribute('data-next-group-invites-url');
    const invitesList = document.getElementById('group-invites-list');

    if (!nextPageUrl) return;

    try {
        console.log(`Loading more group invites from ${nextPageUrl}`);

        const response = await fetch(nextPageUrl, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });

        if (!response.ok) throw new Error('Failed to load more group invites.');

        const data = await response.json();

        if (data.html && data.html.trim() !== '') {
            invitesList.insertAdjacentHTML('beforeend', data.html);
        }

        if (data.hasMorePages) {
            modal.setAttribute('data-next-group-invites-url', data.nextPageUrl);
        } else {
            const loadMoreButton = document.getElementById('load-more-group-invites');
            if (loadMoreButton) {
                loadMoreButton.remove();
            }
        }
    } catch (error) {
        console.error('Error loading more group invites:', error.message);
    }
}

window.loadInitialGroupInvites = loadInitialGroupInvites;
window.loadMoreGroupInvites = loadMoreGroupInvites;