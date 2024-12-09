document.addEventListener('DOMContentLoaded', function () {
    let page = 2; // Start fetching from the second page
    const groupsContainer = document.getElementById('groups-container');
    const loader = document.getElementById('loader');
    let loading = false;

    if (!groupsContainer || !loader) {
        console.error("Groups container or loader not found!");
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

                if (!response.ok) throw new Error('Failed to fetch more groups');

                const data = await response.json();

                if (!data.html.trim()) {
                    console.log('No more groups to load.');
                } else {
                    groupsContainer.insertAdjacentHTML('beforeend', data.html);
                    page++;
                }
            } catch (error) {
                console.error('Error loading more groups:', error);
            } finally {
                loader.classList.add('hidden');
                loading = false;
            }
        }
    });
});
