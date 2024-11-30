document.addEventListener("DOMContentLoaded", () => {
    const conversationList = document.getElementById("conversation-list");
    const messageList = document.getElementById("message-list");
    const messagePanel = document.getElementById("message-panel");
    const messageInputPanel = document.getElementById("message-input-panel");
    const messageForm = document.getElementById("message-form");
    const messageInput = document.getElementById("message-input");

    let currentContactId = null; // Armazena o contato selecionado

    if (conversationList) {
        conversationList.addEventListener("click", async (event) => {
            const listItem = event.target.closest(".conversation-item");
            if (listItem) {
                const contactId = listItem.getAttribute("data-contact-id");
                currentContactId = contactId; // Atualizar o contato selecionado

                // Mostrar o campo de envio de mensagens
                messageInputPanel.classList.remove("hidden");

                // Limpa mensagens antigas
                messageList.innerHTML = "";

                // Atualizar o placeholder do painel
                messagePanel.innerHTML = `
                    <ul id="message-list" class="space-y-4"></ul>
                `;
                
                const newMessageList = document.getElementById("message-list");

                try {
                    // Faz a requisição para buscar as mensagens
                    const response = await fetch(`/messages/${contactId}`);
                    if (!response.ok) {
                        throw new Error("Failed to load messages");
                    }

                    const messages = await response.json();

                    // Exibe as mensagens no painel
                    if (messages.length === 0) {
                        newMessageList.innerHTML = `<li class="text-gray-500 italic">No messages to display.</li>`;
                    } else {
                        messages.forEach((message) => {
                            const listItem = document.createElement("li");

                            // Estilizar as mensagens enviadas e recebidas
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
                    }
                } catch (error) {
                    console.error(error);
                    messagePanel.innerHTML = `<h4 class="text-red-500">Failed to load messages.</h4>`;
                }
            }
        });
    }
});
