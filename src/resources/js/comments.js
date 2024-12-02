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
