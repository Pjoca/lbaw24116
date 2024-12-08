document.addEventListener("DOMContentLoaded", function() {
    // Handle like button click
    document.querySelectorAll('form[id^="like-form-"]').forEach(function(form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();  // Impede o envio tradicional do formulário

            const postId = form.id.replace('like-form-', '');
            const url = form.action;
            
            // Envia o AJAX para curtir o post
            fetch(url, {
                method: 'POST',
                body: new FormData(form),  // Passa os dados do formulário
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, // CSRF Token
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.liked) {
                    document.getElementById('like-count-' + postId).textContent = data.likes_count + ' like' + (data.likes_count > 1 ? 's' : '');
                    form.querySelector('button').textContent = 'Descurtir';
                    form.action = '/posts/' + postId + '/unlike';  // Atualiza a ação do formulário
                }
            })
            .catch(error => console.error('Error:', error));
        });
    });

    // Handle unlike button click
    document.querySelectorAll('form[id^="unlike-form-"]').forEach(function(form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();  // Impede o envio tradicional do formulário

            const postId = form.id.replace('unlike-form-', '');
            const url = form.action;
            
            // Envia o AJAX para descurtir o post
            fetch(url, {
                method: 'POST',
                body: new FormData(form),  // Passa os dados do formulário
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, // CSRF Token
                }
            })
            .then(response => response.json())
            .then(data => {
                if (!data.liked) {
                    document.getElementById('like-count-' + postId).textContent = data.likes_count + ' like' + (data.likes_count > 1 ? 's' : '');
                    form.querySelector('button').textContent = 'Curtir';
                    form.action = '/posts/' + postId + '/like';  // Atualiza a ação do formulário
                }
            })
            .catch(error => console.error('Error:', error));
        });
    });
});
