document.addEventListener('DOMContentLoaded', function () {
    let page = 2; // Start fetching from the second page
    const postsContainer = document.getElementById('posts-container');
    const loader = document.getElementById('loader');
    let loading = false;

    if (!postsContainer || !loader) {
        console.error("Posts container or loader not found!");
        return;
    }

    // Infinite scroll handler
    window.addEventListener('scroll', async () => {
        const nearBottom = window.innerHeight + window.scrollY >= document.body.offsetHeight - 200;

        if (nearBottom && !loading) {
            loading = true;
            loader.classList.remove('hidden');

            try {
                const response = await fetch(`?page=${page}`, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' },
                });

                if (!response.ok) throw new Error('Failed to fetch posts');

                const html = await response.text();

                if (html.trim() === '') {
                    console.log('No more posts to load.');
                } else {
                    postsContainer.insertAdjacentHTML('beforeend', html);
                    page++;
                }
            } catch (error) {
                console.error('Error loading more posts:', error);
            } finally {
                loader.classList.add('hidden');
                loading = false;
            }
        }
    });
});
