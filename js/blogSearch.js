const search = document.getElementById("search-input");
const form = document.getElementById("search-form");

const tag_filter_checkboxes = document.querySelectorAll(".tags-filter-list input");

const filter_statuses = {};
for (let checkbox of tag_filter_checkboxes) {
    filter_statuses[checkbox.id] = checkbox.checked;
}

function filter_statuses_to_list() {
    const output_list = [];
    for (let checkbox_name in filter_statuses) {
        if (filter_statuses[checkbox_name] === true) {
            output_list.push(checkbox_name);
        }
    }
    return output_list;
}

let search_text = search.value;

async function getSearchResults() {
    const response = await fetch("blog_search.php?" + new URLSearchParams({
        q: search_text,
        tags: filter_statuses_to_list()
    }));
    const postList = document.getElementsByClassName("post-list")[0];
    if (response.status === 200) {
        const responseText = await response.text();
        postList.innerHTML = responseText;
    }
    else {
        postList.innerHTML = "<p>no results match your search, try searching something else.</p>"
    }
}
getSearchResults();

// credit to https://webdesign.tutsplus.com/how-to-build-a-search-bar-with-javascript--cms-107227t for the below code
let debounceTimer;
function debounce(callback, delay) {
    window.clearTimeout(debounceTimer);
    debounceTimer = window.setTimeout(callback, delay);
}

search.addEventListener("input",
    (event) => {
        search_text = event.target.value;
        debounce(() => getSearchResults(), 300);
    },
    false
);

form.addEventListener("change",
    (event) => {
        const input_changed = event.target;
        if (input_changed.type === "search") {
            return; // handled by input event listener already
        }
        // otherwise it's a checkbox
        filter_statuses[input_changed.id] = input_changed.checked;
        getSearchResults();
    },
    false
);

form.addEventListener("submit",
    (event) => {
        window.location.search = new URLSearchParams({
            q: search_text,
            tags: filter_statuses_to_list()
        });
    }
)