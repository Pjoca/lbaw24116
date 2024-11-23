import './bootstrap';
function addEventListeners() {
    let itemCheckers = document.querySelectorAll('article.card li.item input[type=checkbox]');
    [].forEach.call(itemCheckers, function(checker) {
        checker.addEventListener('change', sendItemUpdateRequest);
    });
  
    let itemCreators = document.querySelectorAll('article.card form.new_item');
    [].forEach.call(itemCreators, function(creator) {
        creator.addEventListener('submit', sendCreateItemRequest);
    });
  
    let itemDeleters = document.querySelectorAll('article.card li a.delete');
    [].forEach.call(itemDeleters, function(deleter) {
        deleter.addEventListener('click', sendDeleteItemRequest);
    });
  
    let cardDeleters = document.querySelectorAll('article.card header a.delete');
    [].forEach.call(cardDeleters, function(deleter) {
        deleter.addEventListener('click', sendDeleteCardRequest);
    });
  
    let cardCreator = document.querySelector('article.card form.new_card');
    if (cardCreator != null)
        cardCreator.addEventListener('submit', sendCreateCardRequest);
  
    // Dropdown handling for multiple dropdowns
    document.addEventListener('click', function (e) {
        const allDropdowns = document.querySelectorAll('[id^="dropdownMenu-"]');
        const dropdownButton = e.target.closest('[id^="dropdownMenuButton-"]');
  
        // If a dropdown button is clicked
        if (dropdownButton) {
            const dropdownId = dropdownButton.id.replace('dropdownMenuButton-', '');
            const menu = document.getElementById(`dropdownMenu-${dropdownId}`);
            
            if (menu) {
                // Toggle the visibility of the current dropdown
                const isHidden = menu.classList.contains('hidden');
                allDropdowns.forEach(dropdown => dropdown.classList.add('hidden')); // Hide all dropdowns
                if (isHidden) menu.classList.remove('hidden'); // Show the clicked dropdown
            }
            e.stopPropagation(); // Prevent the click from propagating further
            return;
        }
  
        // Hide all dropdowns if clicking outside
        allDropdowns.forEach(menu => menu.classList.add('hidden'));
    });
  }
  
  function encodeForAjax(data) {
    if (data == null) return null;
    return Object.keys(data).map(function(k){
        return encodeURIComponent(k) + '=' + encodeURIComponent(data[k])
    }).join('&');
  }
  
  function sendAjaxRequest(method, url, data, handler) {
    let request = new XMLHttpRequest();
  
    request.open(method, url, true);
    request.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').content);
    request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    request.addEventListener('load', handler);
    request.send(encodeForAjax(data));
  }
  
  // Initialize event listeners
  addEventListeners();
  