import Pusher from 'pusher-js';

document.addEventListener("DOMContentLoaded", () => {
    const conversationList = document.getElementById("conversation-list");
    const messageList = document.getElementById("message-list");
    const messageInputPanel = document.getElementById("message-input-panel");
    const messagePanel = document.getElementById("message-panel");
    const messageForm = document.getElementById("message-form");
    const messageInput = document.getElementById("message-input");
    const newConversationBtn = document.getElementById("new-conversation-btn");
    const newConversationModal = document.getElementById("new-conversation-modal");
    const closeModalBtn = document.getElementById("close-modal-btn");
    const friendList = document.getElementById("friend-list");
    const userId = document.querySelector('meta[name="user-id"]').content;
    let currentContactId = null;
    let currentPage = 1;
    let lastPage = 1;
    let loading = false; 
    let pusherChannel = null;

    const pusher = new Pusher(import.meta.env.VITE_PUSHER_APP_KEY, {
        cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
        encrypted: true,
        authEndpoint: '/broadcasting/auth',
        auth: {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        },
        logToConsole: true 
    });

    function subscribeToChannel(contactId) {
        if (pusherChannel) {
            console.log(`Unsubscribing from channel: ${pusherChannel.name}`);
            pusherChannel.unbind_all();
            pusher.unsubscribe(pusherChannel.name);
        }
    
        const channelName = `private-notification.${userId}.${contactId}`;
        pusherChannel = pusher.subscribe(channelName);

        pusherChannel.bind('pusher:subscription_succeeded', function() {
            console.log(`Subscription successful to channel: ${channelName}`);
        });

        pusherChannel.bind('pusher:subscription_error', function(status) {
            console.error(`Subscription error to channel: ${channelName}`, status);
        });
    
        console.log(`Subscribed to channel: ${channelName}`);
    
        pusherChannel.bind('new-message', function(data) {
            console.log(`New message from Pusher:`, data);
            console.log(`Current contact ID: ${currentContactId}`);
            if (data.message.id_sender == currentContactId) {
                console.log("Adding message to list");
                const listItem = createMessageListItem(data.message, data.message.sender_image_url);
                console.log(listItem);
                messageList.appendChild(listItem);
                scrollToBottom();
            }
        });
    }

    function scrollToBottom() {
        messageList.scrollTop = messageList.scrollHeight;
    }

    function removeNoMessagesPlaceholder() {
        const noMessagesPlaceholder = messageList.querySelector(".text-gray-500");
        if (noMessagesPlaceholder) {
            noMessagesPlaceholder.remove();
        }
    }

    function createMessageListItem(message, image_url) {
        const contactImage = image_url ? `/storage/${image_url}` : '/storage/users/images/default.png';
        const listItem = document.createElement("li");
        if (message.sender_name === "You") {
            listItem.className = "flex justify-end items-start gap-3";
            listItem.innerHTML = `
                <div class="bg-teal-500 text-white px-4 py-2 rounded-lg max-w-md">
                    ${message.content}
                </div>
                <img src="${contactImage}" alt="You" class="w-10 h-10 rounded-full">
            `;
        } else {
            listItem.className = "flex items-start gap-3";
            listItem.innerHTML = `
                <img src="${contactImage}" alt="${message.sender_name}" class="w-10 h-10 rounded-full">
                <div class="bg-gray-200 text-gray-800 px-4 py-2 rounded-lg max-w-md">
                    ${message.content}
                </div>
            `;
        }

        return listItem;
    }

    async function loadLastPageOfConversation(contactId) {
        let response = await fetch(`/messages/${contactId}?page=1`);
        if (!response.ok) throw new Error("Failed to load messages");
        let result = await response.json();
        lastPage = result.last_page;

        response = await fetch(`/messages/${contactId}?page=${lastPage}`);
        if (!response.ok) throw new Error("Failed to load messages");
        result = await response.json();

        const { data, current_page } = result;
        currentPage = current_page;

        if (data.length === 0) {
            messageList.innerHTML = `<li class="text-gray-500 italic">No messages to display.</li>`;
        } else {
            data.forEach((message) => {
                const listItem = createMessageListItem(message, message.sender_image_url);
                messageList.appendChild(listItem);
            });

            scrollToBottom();
        }

        await ensureScrollIsPossible(contactId);
    }

    async function ensureScrollIsPossible(contactId) {
        while (messageList.scrollHeight <= messageList.clientHeight && currentPage > 1 && !loading) {
            loading = true;
            const previousPage = currentPage - 1;
            try {
                const response = await fetch(`/messages/${contactId}?page=${previousPage}`);
                if (!response.ok) {
                    console.error("Failed to load previous messages");
                    break;
                }

                const result = await response.json();
                const { data, current_page } = result;
                data.reverse().forEach((message) => {
                    const listItem = createMessageListItem(message, message.sender_image_url);
                    messageList.insertBefore(listItem, messageList.firstChild);
                });
                currentPage = current_page;
            } catch (error) {
                console.error(error);
                break;
            } finally {
                loading = false;
            }

            if (currentPage === 1 || messageList.scrollHeight > messageList.clientHeight) {
                break;
            }
        }
    }

    if (conversationList) {
        conversationList.addEventListener("click", async (event) => {
            const listItem = event.target.closest(".conversation-item");
            if (listItem) {
                const contactId = listItem.getAttribute("data-contact-id");
                const contactName = listItem.querySelector("p").textContent.trim();
                currentContactId = contactId;
                subscribeToChannel(contactId);
    
                // Update the contact name on the panel
                const contactNamePanel = document.getElementById("contact-name-panel");
                contactNamePanel.textContent = `Chat with ${contactName}`;
    
                messageInputPanel.classList.remove("hidden");
                messageList.innerHTML = "";
    
                const placeholder = document.getElementById("placeholder");
                if (placeholder) placeholder.classList.add("hidden");
    
                const newMessageList = document.getElementById("message-list");
                newMessageList.classList.remove("hidden");
    
                // Reset pagination for new conversation
                currentPage = 1;
                lastPage = 1;
    
                try {
                    await loadLastPageOfConversation(contactId);
                } catch (error) {
                    console.error(error);
                    messagePanel.innerHTML = `<h4 class="text-red-500">Failed to load messages.</h4>`;
                }
            }
        });
    }
    
    // Scroll event: load previous messages when scrolling to the top
    messageList.addEventListener('scroll', async () => {
        if (messageList.scrollTop === 0 && currentPage > 1 && !loading && currentContactId) {
            loading = true;
            const oldScrollHeight = messageList.scrollHeight;

            const previousPage = currentPage - 1;
            try {
                const response = await fetch(`/messages/${currentContactId}?page=${previousPage}`);
                if (!response.ok) {
                    throw new Error("Failed to load previous messages");
                }

                const result = await response.json();
                const { data, current_page } = result;
                data.reverse();
                data.forEach((message) => {
                    const listItem = createMessageListItem(message, message.sender_image_url);
                    messageList.insertBefore(listItem, messageList.firstChild);
                });

                currentPage = current_page;

                // Adjust scroll position so user doesn't lose their place
                const newScrollHeight = messageList.scrollHeight;
                messageList.scrollTop = newScrollHeight - oldScrollHeight;
            } catch (error) {
                console.error(error);
            } finally {
                loading = false;
            }
        }
    });

    // Sending messages
    if (messageForm) {
        messageForm.addEventListener("submit", async (event) => {
            event.preventDefault();
            if (currentContactId && messageInput.value.trim()) {
                try {
                    const response = await fetch(`/messages/send`, {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                        },
                        body: JSON.stringify({
                            content: messageInput.value.trim(),
                            receiver_id: currentContactId,
                        }),
                    });

                    if (!response.ok) {
                        throw new Error("Failed to send message");
                    }

                    const result = await response.json();
                    const contactImage = document.querySelector("#profile-dropdown-toggle img")?.getAttribute("src") 
                    || '/storage/users/images/default.png';

                    if (result.success) {
                        removeNoMessagesPlaceholder();

                        const listItem = document.createElement("li");
                        listItem.className = "flex justify-end items-start gap-3";
                        listItem.innerHTML = `
                            <div class="bg-teal-500 text-white px-4 py-2 rounded-lg max-w-md">
                                ${messageInput.value.trim()}
                            </div>
                            <img src="${contactImage}" alt="You" class="w-10 h-10 rounded-full">
                        `;
                        messageList.appendChild(listItem);
                        messageInput.value = "";
                        scrollToBottom();
                    }
                } catch (error) {
                    console.error(error);
                    alert("Failed to send message");
                }
            } else {
                alert("No conversation selected or message is empty.");
            }
        });
    }

    // Open modal for new conversation
    if (newConversationBtn) {
        newConversationBtn.addEventListener("click", () => {
            newConversationModal.classList.remove("hidden");
        });
    }

    // Close modal
    if (closeModalBtn) {
        closeModalBtn.addEventListener("click", () => {
            newConversationModal.classList.add("hidden");
        });
    }

    // Start a new conversation
    if (friendList) {
        friendList.addEventListener("click", async (event) => {
            const friendItem = event.target.closest(".friend-item");
            if (friendItem) {
                const friendId = friendItem.getAttribute("data-friend-id");
    
                try {
                    const response = await fetch(`/messages/start-conversation`, {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                        },
                        body: JSON.stringify({ friend_id: friendId }),
                    });
    
                    if (!response.ok) {
                        throw new Error("Failed to start conversation.");
                    }
    
                    const result = await response.json();
                    if (result.success) {
                        const contact = result.contact;
    
                        const noConversationsPlaceholder = document.querySelector("#conversation-list .no-conversations");
                        if (noConversationsPlaceholder) {
                            noConversationsPlaceholder.remove();
                        }
    
                        // Add the new conversation at the top of the list
                        const newContact = document.createElement("li");
                        const contactImage = contact.image_url ? `/storage/${contact.image_url}` : '/storage/users/images/default.png';
                        newContact.className = "p-4 flex items-center gap-4 hover:bg-gray-100 cursor-pointer conversation-item";
                        newContact.setAttribute("data-contact-id", contact.id);
                        newContact.innerHTML = `
                            <img src="${contactImage}" alt="${contact.name}" class="w-12 h-12 rounded-full object-cover">
                            <div>
                                <p class="font-semibold text-gray-800">${contact.name}</p>
                            </div>
                        `;
    
                        const conversationList = document.getElementById("conversation-list");
                        conversationList.prepend(newContact); 
    
                        newConversationModal.classList.add("hidden");
    
                        newContact.click();
                    }
                } catch (error) {
                    console.error(error);
                    alert("Failed to start conversation.");
                }
            }
        });
    }
});
