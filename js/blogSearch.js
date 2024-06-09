const search = document.getElementById("search-input");
async function getSearchResults(search, tags) {
    const response = await fetch("blog_search.php?" + new URLSearchParams({
        q: search,
        tags: tags
    }));
    const responseText = await response.text();
    const postList = document.getElementsByClassName("post-list")[0];
    postList.innerHTML = responseText;
}
getSearchResults(search.value, "");

// credit to https://webdesign.tutsplus.com/how-to-build-a-search-bar-with-javascript--cms-107227t for the below code
let debounceTimer;
function debounce(callback, delay) {
    window.clearTimeout(debounceTimer);
    debounceTimer = window.setTimeout(callback, delay);
}

search.addEventListener("input",
    (event) => {
        const search_text = event.target.value;
        debounce(() => getSearchResults(search_text, ""), 300);
    },
    false
)