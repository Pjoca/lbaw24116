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
    let currentContactId = null;
    let currentPage = 1;
    let lastPage = 1;
    let loading = false; // Para evitar requisições múltiplas em simultâneo

    function scrollToBottom() {
        messageList.scrollTop = messageList.scrollHeight;
    }

    function removeNoMessagesPlaceholder() {
        const noMessagesPlaceholder = messageList.querySelector(".text-gray-500");
        if (noMessagesPlaceholder) {
            noMessagesPlaceholder.remove();
        }
    }

    function createMessageListItem(message) {
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

        return listItem;
    }

    if (conversationList) {
        conversationList.addEventListener("click", async (event) => {
            const listItem = event.target.closest(".conversation-item");
            if (listItem) {
                const contactId = listItem.getAttribute("data-contact-id");
                currentContactId = contactId;

                messageInputPanel.classList.remove("hidden");
                messageList.innerHTML = "";

                const placeholder = document.getElementById("placeholder");
                if (placeholder) placeholder.classList.add("hidden");

                const newMessageList = document.getElementById("message-list");
                newMessageList.classList.remove("hidden");

                // Reseta paginação ao selecionar nova conversa
                currentPage = 1;
                lastPage = 1;

                try {
                    const response = await fetch(`/messages/${contactId}?page=1`);
                    if (!response.ok) {
                        throw new Error("Failed to load messages");
                    }

                    const result = await response.json();
                    const { data, current_page, last_page } = result;
                    currentPage = current_page;
                    lastPage = last_page;

                    if (data.length === 0) {
                        newMessageList.innerHTML = `<li class="text-gray-500 italic">No messages to display.</li>`;
                    } else {
                        data.forEach((message) => {
                            const listItem = createMessageListItem(message);
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

    // Adiciona evento de scroll no painel de mensagens
    messageList.addEventListener('scroll', async () => {
        // Quando o scroll chega ao topo, verifica se temos mais páginas
        if (messageList.scrollTop === 0 && currentPage < lastPage && !loading && currentContactId) {
            loading = true;
            const oldScrollHeight = messageList.scrollHeight;

            const nextPage = currentPage + 1;
            try {
                const response = await fetch(`/messages/${currentContactId}?page=${nextPage}`);
                if (!response.ok) {
                    throw new Error("Failed to load previous messages");
                }

                const result = await response.json();
                const { data, current_page, last_page } = result;

                data.forEach((message) => {
                    const listItem = createMessageListItem(message);
                    messageList.insertBefore(listItem, messageList.firstChild);
                });

                currentPage = current_page;
                lastPage = last_page;

                // Ajusta a posição do scroll para que o utilizador fique
                // no mesmo ponto da conversa, ao invés de saltar
                const newScrollHeight = messageList.scrollHeight;
                messageList.scrollTop = newScrollHeight - oldScrollHeight;
            } catch (error) {
                console.error(error);
            } finally {
                loading = false;
            }
        }
    });

    // Envio de mensagens (permanece praticamente o mesmo)
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
                        removeNoMessagesPlaceholder();

                        const listItem = document.createElement("li");
                        listItem.className = "flex justify-end items-start gap-3";
                        listItem.innerHTML = `
                            <div class="bg-teal-500 text-white px-4 py-2 rounded-lg max-w-md">
                                ${messageInput.value.trim()}
                            </div>
                            <img src="${userProfileImage}" alt="You" class="w-10 h-10 rounded-full">
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
                        const contact = result.contact;
    
                        
                        const noConversationsPlaceholder = document.querySelector("#conversation-list .no-conversations");
                        if (noConversationsPlaceholder) {
                            noConversationsPlaceholder.remove();
                        }
    
                        // Adicionar a nova conversa no topo da lista
                        const newContact = document.createElement("li");
                        newContact.className = "p-4 flex items-center gap-4 hover:bg-gray-100 cursor-pointer conversation-item";
                        newContact.setAttribute("data-contact-id", contact.id);
                        newContact.innerHTML = `
                            <img src="${contact.image_url}" alt="${contact.name}" class="w-12 h-12 rounded-full object-cover">
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

