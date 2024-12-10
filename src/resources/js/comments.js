let currentOffset = 3; // Começa em 3, porque já há 3 comentários carregados inicialmente.

function loadMoreComments(postId) {
    console.log(`Carregando mais comentários para o post ${postId}, offset atual: ${currentOffset}`);

    fetch(`/posts/${postId}/comments/load/${currentOffset}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Erro na resposta da requisição');
            }
            return response.json();
        })
        .then(data => {
            console.log('Dados recebidos:', data);

            if (data.comments && data.comments.length > 0) {
                const commentsSection = document.getElementById(`comments-section-${postId}`);
                if (commentsSection) {
                    data.comments.forEach(comment => {
                        const commentDiv = document.createElement('div');
                        commentDiv.classList.add('bg-gray-100', 'p-4', 'rounded-lg', 'mb-2');
                        commentDiv.innerHTML = `
                            <div class="flex items-center">
                                <img src="/storage/${comment.user_image_url}" class="w-8 h-8 rounded-full mr-3">
                                <span class="font-semibold text-gray-800">${comment.user_name}</span>
                            </div>
                            <p class="mt-2 text-gray-700">${comment.content}</p>
                        `;
                        commentsSection.appendChild(commentDiv);
                    });

                    // Incrementa o offset
                    currentOffset += 3;
                } else {
                    console.error('Elemento comments-section não encontrado');
                }
            } else {
                console.log('Nenhum comentário adicional encontrado');
            }
        })
        .catch(error => {
            console.error('Erro ao carregar comentários:', error);
        });
}

document.addEventListener("DOMContentLoaded", function () {
    // Handle comment submission
    document.querySelectorAll('form[action*="comments/store"]').forEach(function (form) {
        form.addEventListener("submit", function (e) {
            e.preventDefault(); // Prevent the default form submission

            const postId = form.action.split('/').pop(); // Get the post ID from the action URL
            const content = form.querySelector('textarea[name="content"]').value.trim(); // Get the comment content

            if (!content) {
                alert("Comment cannot be empty.");
                return;
            }

            // Send the comment via AJAX
            fetch(form.action, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content, // CSRF Token
                },
                body: JSON.stringify({ content: content }),
            })
                .then((response) => {
                    if (!response.ok) {
                        throw new Error("Failed to post the comment.");
                    }
                    return response.json();
                })
                .then((data) => {
                    if (data.success) {
                        const commentSection = document.querySelector(`#comments-section-${postId}`);

                        // Create a new comment element
                        const newComment = document.createElement("div");
                        newComment.className = "bg-gray-100 p-4 rounded-lg mb-2";
                        newComment.id = `comment-${data.comment.id}`;
                        newComment.innerHTML = `
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <img src="${data.comment.user_image}" class="w-8 h-8 rounded-full mr-3">
                                    <span class="font-semibold text-gray-800">${data.comment.user_name}</span>
                                </div>
                                ${
                                    data.is_owner
                                        ? `
                                    <div class="flex space-x-2">
                                        <button type="button" onclick="openInlineEdit(${data.comment.id}, '${data.comment.content.replace(/'/g, "\\'")}')" class="text-blue-500 hover:text-blue-700">
                                            <i class="fas fa-edit"></i> Edit
                                        </button>
                                        <button onclick="deleteComment(${data.comment.id})" class="text-red-500 hover:text-red-700">
                                            <i class="fas fa-trash-alt"></i> Delete
                                        </button>
                                    </div>
                                `
                                        : ""
                                }
                            </div>
                            <p class="mt-2 text-gray-700" id="commentContent-${data.comment.id}">${data.comment.content}</p>
                        `;

                        // Append the new comment
                        commentSection.appendChild(newComment);

                        // Clear the textarea
                        form.querySelector('textarea[name="content"]').value = "";

                        // Scroll to the bottom of the comments section
                        commentSection.scrollTop = commentSection.scrollHeight;
                    } else {
                        alert("Failed to post the comment.");
                    }
                })
                .catch((error) => {
                    console.error("Error posting the comment:", error);
                    alert("An error occurred while posting the comment.");
                });
        });
    });
});