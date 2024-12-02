$(document).ready(function() {
    $('.view-more-comments').on('click', function() {
        var postId = $(this).data('post-id');
        var offset = $(this).data('offset');  // Quantos comentários já foram carregados
        var button = $(this);

        $.ajax({
            url: '/posts/' + postId + '/comments/load/' + offset,
            method: 'GET',
            success: function(response) {
                if (response.comments.length > 0) {
                    response.comments.forEach(function(comment) {
                        // Adiciona cada comentário à lista (ajuste o seletor conforme necessário)
                        $('#comments-list-' + postId).append(
                            '<div class="comment"><strong>' + comment.user.name + ':</strong> ' + comment.text + '</div>'
                        );
                    });

                    // Atualiza o offset no botão
                    button.data('offset', offset + response.comments.length);

                    // Se não houver mais comentários, remove o botão
                    if (response.comments.length < 3) {
                        button.remove();  // Remove o botão "Ver mais"
                    }
                } else {
                    button.remove();  // Remove se não houver mais comentários
                }
            }
        });
    });
});
