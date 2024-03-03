// public/js/search.js
document.addEventListener('DOMContentLoaded', function () {
    const searchForm = document.getElementById('search-form');
    const searchResultContainer = document.getElementById('search-result-container');

    searchForm.addEventListener('submit', function (e) {
        e.preventDefault();

        const formData = new FormData(searchForm);
        const searchUrl = searchForm.getAttribute('action');

        fetch(searchUrl + '?' + new URLSearchParams(formData))
            .then(response => response.json())
            .then(data => {
                // Update the search result container with the new data
                searchResultContainer.innerHTML = data.html;
            })
            .catch(error => console.error('Error:', error));
    });
});
