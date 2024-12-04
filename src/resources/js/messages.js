document.addEventListener("DOMContentLoaded", () => {
    const conversationList = document.getElementById("conversation-list");
    const messageList = document.getElementById("message-list");
    const messagePanel = document.getElementById("message-panel");
    const messageInputPanel = document.getElementById("message-input-panel");
    const messageForm = document.getElementById("message-form");
    const messageInput = document.getElementById("message-input");

    let currentContactId = null; // Stores the selected contact

    function scrollToBottom() {
        messageList.scrollTop = messageList.scrollHeight;
    }

    function removeNoMessagesPlaceholder() {
        const noMessagesPlaceholder = messageList.querySelector(".text-gray-500");
        if (noMessagesPlaceholder) {
            noMessagesPlaceholder.remove();
        }
    }

    if (conversationList) {
        conversationList.addEventListener("click", async (event) => {
            const listItem = event.target.closest(".conversation-item");
            if (listItem) {
                const contactId = listItem.getAttribute("data-contact-id");
                currentContactId = contactId; // Update the selected contact

                // Show the message input panel
                messageInputPanel.classList.remove("hidden");

                // Clear old messages
                messageList.innerHTML = "";

                // Update the placeholder in the panel
                const placeholder = document.getElementById("placeholder");
                if (placeholder) placeholder.classList.add("hidden");

                const newMessageList = document.getElementById("message-list");
                newMessageList.classList.remove("hidden");

                try {
                    // Fetch messages
                    const response = await fetch(`/messages/${contactId}`);
                    if (!response.ok) {
                        throw new Error("Failed to load messages");
                    }

                    const messages = await response.json();

                    // Display messages
                    if (messages.length === 0) {
                        newMessageList.innerHTML = `<li class="text-gray-500 italic">No messages to display.</li>`;
                    } else {
                        messages.forEach((message) => {
                            const listItem = document.createElement("li");

                            if (message.sender_name === "You") {
                                listItem.className = "flex justify-end items-start gap-3";
                                listItem.innerHTML = `
                                    <div class="bg-teal-500 text-white px-4 py-2 rounded-lg max-w-md">
                                        ${message.content}
                                    </div>
                                    <img src="/path/to/your-profile.jpg" alt="You" class="w-10 h-10 rounded-full">
                                `;
                            } else {
                                listItem.className = "flex items-start gap-3";
                                listItem.innerHTML = `
                                    <img src="/path/to/sender-profile.jpg" alt="${message.sender_name}" class="w-10 h-10 rounded-full">
                                    <div class="bg-gray-200 text-gray-800 px-4 py-2 rounded-lg max-w-md">
                                        ${message.content}
                                    </div>
                                `;
                            }

                            newMessageList.appendChild(listItem);
                        });

                        scrollToBottom();
                    }
                } catch (error) {
                    console.error(error);
                    messagePanel.innerHTML = `<h4 class="text-red-500">Failed to load messages.</h4>`;
                }
            }
        });
    }

    // Send messages
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
                    const userProfileImage = document.querySelector(".container").getAttribute("data-user-profile");

                    if (result.success) {
                        // Remove "No messages to display" if present
                        removeNoMessagesPlaceholder();

                        // Append the new message
                        const listItem = document.createElement("li");
                        listItem.className = "flex justify-end items-start gap-3";
                        listItem.innerHTML = `
                            <div class="bg-teal-500 text-white px-4 py-2 rounded-lg max-w-md">
                                ${messageInput.value.trim()}
                            </div>
                            <img src="${userProfileImage}" alt="You" class="w-10 h-10 rounded-full">
                        `;
                        messageList.appendChild(listItem);
                        messageInput.value = ""; // Clear input field
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
});



document.addEventListener("DOMContentLoaded", () => {
    const friendList = document.getElementById("friend-list");
    const newConversationModal = document.getElementById("new-conversation-modal");
    const newConversationBtn = document.getElementById("new-conversation-btn");
    const closeModalBtn = document.getElementById("close-modal-btn");
    const conversationList = document.getElementById("conversation-list");
    const messageList = document.getElementById("message-list");
    const messagePanel = document.getElementById("message-panel");
    const messageInputPanel = document.getElementById("message-input-panel");

    let currentContactId = null;

    // Open modal
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
                        // Close the modal
                        newConversationModal.classList.add("hidden");

                        // Add the new contact to the conversation list dynamically
                        const newContact = document.createElement("li");
                        newContact.className = "p-4 flex items-center gap-4 hover:bg-gray-100 cursor-pointer conversation-item";
                        newContact.setAttribute("data-contact-id", friendId);
                        newContact.innerHTML = `
                            <img src="/path/to/default-profile.jpg" alt="Friend" class="w-12 h-12 rounded-full object-cover">
                            <div>
                                <p class="font-semibold text-gray-800">${friendItem.querySelector('span').innerText}</p>
                            </div>
                        `;
                        conversationList.appendChild(newContact);

                        // Simulate selecting the conversation
                        currentContactId = friendId;
                        messageList.innerHTML = ""; // Clear messages
                        messagePanel.querySelector("#placeholder").classList.add("hidden");
                        messageList.classList.remove("hidden");
                        messageInputPanel.classList.remove("hidden");
                    }
                } catch (error) {
                    console.error(error);
                    alert("Failed to start conversation.");
                }
            }
        });
    }
});


