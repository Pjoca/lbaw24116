document.addEventListener("DOMContentLoaded", () => {
    const conversationList = document.getElementById("conversation-list");
    const messageList = document.getElementById("message-list");
    const messagePanel = document.getElementById("message-panel");

    if (conversationList) {
        conversationList.addEventListener("click", async (event) => {
            if (event.target.classList.contains("conversation-item")) {
                const contactId = event.target.getAttribute("data-contact-id");

                // Limpa mensagens antigas
                messageList.innerHTML = "";

                try {
                    // Faz a requisição para buscar as mensagens
                    const response = await fetch(`/messages/${contactId}`);
                    if (!response.ok) {
                        throw new Error("Failed to load messages");
                    }

                    const messages = await response.json();

                    // Exibe as mensagens no painel
                    if (messages.length === 0) {
                        messageList.innerHTML = `<li class="list-group-item text-muted">No messages to display.</li>`;
                    } else {
                        messages.forEach((message) => {
                            const listItem = document.createElement("li");
                            listItem.classList.add("list-group-item");
                            listItem.textContent = `${message.sender_name}: ${message.content}`;
                            messageList.appendChild(listItem);
                        });
                    }
                } catch (error) {
                    console.error(error);
                    messagePanel.innerHTML = `<h4 class="text-danger">Failed to load messages.</h4>`;
                }
            }
        });
    }
});
