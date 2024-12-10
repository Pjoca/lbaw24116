let yourGroupsPage = 2; // Start fetching Your Groups from page 2
let exploreGroupsPage = 2; // Start fetching Explore Groups from page 2
let isLoadingYourGroups = false;
let isLoadingExploreGroups = false;

const yourGroupsContainer = document.getElementById('your-groups-container');
const exploreGroupsContainer = document.getElementById('explore-groups-container');
const yourGroupsLoader = document.getElementById('your-groups-loader');
const exploreGroupsLoader = document.getElementById('explore-groups-loader');

if (!yourGroupsContainer || !exploreGroupsContainer) {
    console.error("Containers for Your Groups or Explore Groups not found!");
}

if (!yourGroupsLoader || !exploreGroupsLoader) {
    console.error("Loaders for Your Groups or Explore Groups not found!");
}

async function loadMoreGroups(container, loader, page, section) {
    if ((section === 'your-groups' && isLoadingYourGroups) || (section === 'explore-groups' && isLoadingExploreGroups)) {
        return; // Prevent multiple simultaneous requests for the same section
    }

    if (section === 'your-groups') isLoadingYourGroups = true;
    if (section === 'explore-groups') isLoadingExploreGroups = true;

    // Increment page **before** sending the request to avoid duplicate requests
    if (section === 'your-groups') yourGroupsPage++;
    if (section === 'explore-groups') exploreGroupsPage++;

    loader.classList.remove('hidden');

    try {
        const response = await fetch(`/groups?page=${page}&section=${section}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
        });

        if (!response.ok) throw new Error(`Failed to fetch more ${section} groups`);

        const data = await response.json();
        console.log(`Loaded data for ${section} (Page ${page}):`, data);

        if (!data.html.trim()) {
            console.log(`No more ${section} groups to load.`);
            return false; // No more pages to load
        } else {
            container.insertAdjacentHTML('beforeend', data.html);
            return true; // More pages available
        }
    } catch (error) {
        console.error(`Error loading more ${section} groups:`, error);
        return false;
    } finally {
        loader.classList.add('hidden');
        if (section === 'your-groups') isLoadingYourGroups = false;
        if (section === 'explore-groups') isLoadingExploreGroups = false;
    }
}

function handleScroll() {
    const nearBottom = window.innerHeight + window.scrollY >= document.body.offsetHeight - 200;

    if (nearBottom) {
        // Check if user is near the bottom of the Your Groups section
        if (isInViewport(yourGroupsContainer) && !isLoadingYourGroups) {
            loadMoreGroups(yourGroupsContainer, yourGroupsLoader, yourGroupsPage, 'your-groups');
        }

        // Check if user is near the bottom of the Explore Groups section
        if (isInViewport(exploreGroupsContainer) && !isLoadingExploreGroups) {
            loadMoreGroups(exploreGroupsContainer, exploreGroupsLoader, exploreGroupsPage, 'explore-groups');
        }
    }
}

function isInViewport(element) {
    if (!element) return false;
    const rect = element.getBoundingClientRect();
    return rect.top <= window.innerHeight && rect.bottom >= 0;
}

// Attach scroll event listener with debounce to avoid rapid triggers
let debounceTimer;
window.addEventListener('scroll', () => {
    if (debounceTimer) clearTimeout(debounceTimer);
    debounceTimer = setTimeout(() => handleScroll(), 100); // 100ms debounce
});
