console.log("comments.js carregado com sucesso");

// Função de like/unlike no timeline
function toggleLike(postId) {
    const likeButton = document.getElementById(`like-button-${postId}`);
    const likeCount = document.getElementById(`like-count-${postId}`);

    return fetch(`/posts/${postId}/like-toggle`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
    })
    .then(response => response.json())
    .then(data => {
        if (likeButton && likeCount) {
            if (data.liked) {
                likeButton.innerHTML = '<i class="fas fa-heart text-2xl text-red-500"></i>';
            } else {
                likeButton.innerHTML = '<i class="far fa-heart text-2xl text-gray-500"></i>';
            }
            likeCount.textContent = `${data.likesCount}`;
        }
        return data; 
    })
    .catch(error => {
        console.error('Erro ao processar like:', error);
        throw error;
    });
}

// Funções para editar comentário no timeline
function openInlineEdit(commentId, content) {
    document.getElementById(`commentContent-${commentId}`).classList.add('hidden');
    document.getElementById(`editForm-${commentId}`).classList.remove('hidden');
    document.getElementById(`editTextArea-${commentId}`).value = content;
}

function cancelInlineEdit(commentId) {
    document.getElementById(`editForm-${commentId}`).classList.add('hidden');
    document.getElementById(`commentContent-${commentId}`).classList.remove('hidden');
}

function saveInlineEdit(event, commentId) {
    event.preventDefault();
    const textarea = document.getElementById(`editTextArea-${commentId}`);
    const newContent = textarea.value;

    fetch(`/comments/${commentId}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ content: newContent })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById(`commentContent-${commentId}`).textContent = data.content;
            cancelInlineEdit(commentId);
        } else {
            alert('Falha ao salvar a edição.');
        }
    })
    .catch(error => {
        console.error('Erro ao salvar a edição:', error);
        alert('Ocorreu um erro ao editar o comentário. Tente novamente.');
    });
}

// Excluir comentário no timeline
function deleteComment(commentId) {
    if (!confirm('Tem certeza que deseja excluir este comentário?')) return;

    fetch(`/comments/${commentId}`, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const timelineComment = document.getElementById(`comment-${commentId}`);
            if (timelineComment) timelineComment.remove();
        } else {
            alert('Falha ao excluir o comentário.');
        }
    })
    .catch(error => {
        console.error('Erro ao excluir o comentário:', error);
        alert('Ocorreu um erro ao excluir o comentário. Tente novamente.');
    });
}

// Funções específicas para editar comentários no modal
function openModalInlineEdit(commentId, content) {
    document.getElementById(`modal-commentContent-${commentId}`).classList.add('hidden');
    document.getElementById(`modal-editForm-${commentId}`).classList.remove('hidden');
    document.getElementById(`modal-editTextArea-${commentId}`).value = content;
}

function cancelModalInlineEdit(commentId) {
    document.getElementById(`modal-editForm-${commentId}`).classList.add('hidden');
    document.getElementById(`modal-commentContent-${commentId}`).classList.remove('hidden');
}

function saveModalInlineEdit(event, commentId) {
    event.preventDefault();
    const textarea = document.getElementById(`modal-editTextArea-${commentId}`);
    const newContent = textarea.value;

    fetch(`/comments/${commentId}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ content: newContent })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Atualiza no modal
            document.getElementById(`modal-commentContent-${commentId}`).textContent = data.content;
            cancelModalInlineEdit(commentId);

            // Atualiza no timeline se existir
            const timelineCommentContent = document.getElementById(`commentContent-${commentId}`);
            if (timelineCommentContent) {
                timelineCommentContent.textContent = data.content;
            }
        } else {
            alert('Falha ao salvar a edição no modal.');
        }
    })
    .catch(error => {
        console.error('Erro ao salvar a edição (modal):', error);
        alert('Ocorreu um erro ao editar o comentário (modal). Tente novamente.');
    });
}

// Excluir comentário no modal
function deleteModalComment(commentId) {
    if (!confirm('Tem certeza que deseja excluir este comentário?')) return;

    fetch(`/comments/${commentId}`, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Remove do modal
            const modalComment = document.getElementById(`modal-comment-${commentId}`);
            if (modalComment) modalComment.remove();

            // Remove do timeline se existir
            const timelineComment = document.getElementById(`comment-${commentId}`);
            if (timelineComment) timelineComment.remove();
        } else {
            alert('Falha ao excluir o comentário do modal.');
        }
    })
    .catch(error => {
        console.error('Erro ao excluir o comentário do modal:', error);
        alert('Ocorreu um erro ao excluir o comentário no modal. Tente novamente.');
    });
}

// Disponibiliza as funções globalmente
window.openInlineEdit = openInlineEdit;
window.cancelInlineEdit = cancelInlineEdit;
window.saveInlineEdit = saveInlineEdit;
window.toggleLike = toggleLike;
window.deleteComment = deleteComment;

window.openModalInlineEdit = openModalInlineEdit;
window.cancelModalInlineEdit = cancelModalInlineEdit;
window.saveModalInlineEdit = saveModalInlineEdit;
window.deleteModalComment = deleteModalComment;

// AJAX para adicionar novo comentário no timeline
document.querySelectorAll('form.comment-form').forEach(commentForm => {
    commentForm.addEventListener("submit", async (e) => {
        e.preventDefault();
        const formData = new FormData(commentForm);
        const actionUrl = commentForm.action;
        const postIdMatch = actionUrl.match(/comments\/(\d+)/);
        const postId = postIdMatch ? postIdMatch[1] : null;

        try {
            const response = await fetch(actionUrl, {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                },
                body: formData
            });

            const data = await response.json();
            if (data.success) {
                const commentSection = document.getElementById(`comments-section-${postId}`);
                const newComment = document.createElement("div");
                newComment.classList.add("bg-gray-100", "p-4", "rounded-lg", "mb-2");
                newComment.id = `comment-${data.comment.id}`;
                newComment.innerHTML = `
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <img src="${data.comment.user_image}" class="w-8 h-8 rounded-full mr-3">
                            <span class="font-semibold text-gray-800">${data.comment.user_name}</span>
                        </div>
                        ${data.comment.editable ? `
                        <div class="flex space-x-2">
                            <button type="button" onclick="openInlineEdit(${data.comment.id}, '${data.comment.content.replace(/'/g, "\\'")}')" class="text-blue-500 hover:text-blue-700">
                                <i class="fas fa-edit"></i> Editar
                            </button>
                            <button onclick="deleteComment(${data.comment.id})" class="text-red-500 hover:text-red-700">
                                <i class="fas fa-trash-alt"></i> Excluir
                            </button>
                        </div>
                        ` : ''}
                    </div>
                    <p class="mt-2 text-gray-700" id="commentContent-${data.comment.id}">${data.comment.content}</p>
                    <form id="editForm-${data.comment.id}" class="hidden mt-2" onsubmit="saveInlineEdit(event, ${data.comment.id})">
                        <textarea name="content" class="w-full p-2 border rounded-lg" id="editTextArea-${data.comment.id}"></textarea>
                        <div class="flex justify-end mt-2">
                            <button type="button" onclick="cancelInlineEdit(${data.comment.id})" class="mr-2 text-gray-600">Cancelar</button>
                            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg">Salvar</button>
                        </div>
                    </form>
                `;
                commentSection.appendChild(newComment);
                commentForm.querySelector('textarea[name="content"]').value = '';
            } else {
                alert('Falha ao adicionar comentário.');
            }
        } catch (error) {
            console.error('Erro ao adicionar o comentário:', error);
            alert('Ocorreu um erro ao adicionar o comentário. Tente novamente.');
        }
    });
});

// Adicionar comentários no modal
const modalCommentForm = document.getElementById('modal-comment-form');
if (modalCommentForm) {
    modalCommentForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        const input = document.getElementById('modal-comment-input');
        const content = input.value.trim();
        if (!content) {
            alert('O comentário não pode ser vazio.');
            return;
        }

        const postId = document.getElementById('post-view-modal').getAttribute('data-post-id');

        try {
            const response = await fetch(`/comments/${postId}`, {
                method: 'POST',
                headers: {
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                },
                body: new FormData(modalCommentForm)
            });

            const data = await response.json();
            if (data.success) {
                const modalComments = document.getElementById('modal-comments');
                const comment = data.comment;
                const newComment = document.createElement("div");
                newComment.classList.add("bg-gray-100", "p-4", "rounded-lg", "mb-2");
                newComment.id = `modal-comment-${comment.id}`;
                newComment.innerHTML = `
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <img src="${comment.user_image}" class="w-8 h-8 rounded-full mr-3">
                            <span class="font-semibold text-gray-800">${comment.user_name}</span>
                        </div>
                        ${comment.editable ? `
                        <div class="flex space-x-2">
                            <button type="button" onclick="openModalInlineEdit(${comment.id}, '${comment.content.replace(/'/g, "\\'")}')" class="text-blue-500 hover:text-blue-700">
                                <i class="fas fa-edit"></i> Editar
                            </button>
                            <button onclick="deleteModalComment(${comment.id})" class="text-red-500 hover:text-red-700">
                                <i class="fas fa-trash-alt"></i> Excluir
                            </button>
                        </div>
                        ` : ''}
                    </div>
                    <p class="mt-2 text-gray-700" id="modal-commentContent-${comment.id}">${comment.content}</p>
                    <form id="modal-editForm-${comment.id}" class="hidden mt-2" onsubmit="saveModalInlineEdit(event, ${comment.id})">
                        <textarea name="content" class="w-full p-2 border rounded-lg" id="modal-editTextArea-${comment.id}"></textarea>
                        <div class="flex justify-end mt-2">
                            <button type="button" onclick="cancelModalInlineEdit(${comment.id})" class="mr-2 text-gray-600">Cancelar</button>
                            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg">Salvar</button>
                        </div>
                    </form>
                `;
                modalComments.appendChild(newComment);
                input.value = '';
                
                // Também adiciona imediatamente no timeline se já houver seção de comentários
                const timelineCommentsSection = document.getElementById(`comments-section-${postId}`);
                if (timelineCommentsSection) {
                    const timelineNewComment = document.createElement("div");
                    timelineNewComment.classList.add("bg-gray-100", "p-4", "rounded-lg", "mb-2");
                    timelineNewComment.id = `comment-${comment.id}`;
                    timelineNewComment.innerHTML = `
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <img src="${comment.user_image}" class="w-8 h-8 rounded-full mr-3">
                                <span class="font-semibold text-gray-800">${comment.user_name}</span>
                            </div>
                            ${comment.editable ? `
                            <div class="flex space-x-2">
                                <button type="button" onclick="openInlineEdit(${comment.id}, '${comment.content.replace(/'/g, "\\'")}')" class="text-blue-500 hover:text-blue-700">
                                    <i class="fas fa-edit"></i> Editar
                                </button>
                                <button onclick="deleteComment(${comment.id})" class="text-red-500 hover:text-red-700">
                                    <i class="fas fa-trash-alt"></i> Excluir
                                </button>
                            </div>
                            ` : ''}
                        </div>
                        <p class="mt-2 text-gray-700" id="commentContent-${comment.id}">${comment.content}</p>
                        <form id="editForm-${comment.id}" class="hidden mt-2" onsubmit="saveInlineEdit(event, ${comment.id})">
                            <textarea name="content" class="w-full p-2 border rounded-lg" id="editTextArea-${comment.id}"></textarea>
                            <div class="flex justify-end mt-2">
                                <button type="button" onclick="cancelInlineEdit(${comment.id})" class="mr-2 text-gray-600">Cancelar</button>
                                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg">Salvar</button>
                            </div>
                        </form>
                    `;
                    timelineCommentsSection.appendChild(timelineNewComment);
                }

            } else {
                alert('Falha ao adicionar comentário (modal).');
            }
        } catch (error) {
            console.error('Erro ao adicionar o comentário (modal):', error);
            alert('Ocorreu um erro ao adicionar o comentário (modal). Tente novamente.');
        }
    });
}

// Carregar mais comentários no timeline (se necessário)
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('button[id^="load-more-comments-"]').forEach(button => {
        button.addEventListener('click', function() {
            const postId = this.dataset.postId;
            let page = parseInt(this.dataset.page, 10);

            fetch(`/posts/${postId}/comments?page=${page}`, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const commentsSection = document.getElementById(`comments-section-${postId}`);
                    data.comments.forEach(comment => {
                        const newComment = document.createElement("div");
                        newComment.classList.add("bg-gray-100", "p-4", "rounded-lg", "mb-2");
                        newComment.id = `comment-${comment.id}`;
                        newComment.innerHTML = `
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <img src="${comment.user_image}" class="w-8 h-8 rounded-full mr-3">
                                    <span class="font-semibold text-gray-800">${comment.user_name}</span>
                                </div>
                                ${comment.editable ? `
                                <div class="flex space-x-2">
                                    <button type="button" onclick="openInlineEdit(${comment.id}, '${comment.content.replace(/'/g, "\\'")}')" class="text-blue-500 hover:text-blue-700">
                                        <i class="fas fa-edit"></i> Editar
                                    </button>
                                    <button onclick="deleteComment(${comment.id})" class="text-red-500 hover:text-red-700">
                                        <i class="fas fa-trash-alt"></i> Excluir
                                    </button>
                                </div>
                                ` : ''}
                            </div>
                            <p class="mt-2 text-gray-700" id="commentContent-${comment.id}">${comment.content}</p>
                            <form id="editForm-${comment.id}" class="hidden mt-2" onsubmit="saveInlineEdit(event, ${comment.id})">
                                <textarea name="content" class="w-full p-2 border rounded-lg" id="editTextArea-${comment.id}"></textarea>
                                <div class="flex justify-end mt-2">
                                    <button type="button" onclick="cancelInlineEdit(${comment.id})" class="mr-2 text-gray-600">Cancelar</button>
                                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg">Salvar</button>
                                </div>
                            </form>
                        `;
                        commentsSection.appendChild(newComment);
                    });

                    if (data.hasMore) {
                        button.dataset.page = page + 1;
                    } else {
                        button.remove();
                    }
                } else {
                    alert('Falha ao carregar mais comentários.');
                }
            })
            .catch(error => {
                console.error('Erro ao carregar mais comentários:', error);
                alert('Ocorreu um erro ao carregar mais comentários. Tente novamente.');
            });
        });
    });
});
