const search = document.getElementById("search-input");
const form = document.getElementById("search-form");

const tag_filter_checkboxes = document.querySelectorAll(".tags-filter-list input");

const filter_statuses = {};
for (let checkbox of tag_filter_checkboxes) {
    filter_statuses[checkbox.id] = checkbox.checked;
}

let editing = false;

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
    const response = await fetch("edit_blog_search.php?" + new URLSearchParams({
        q: search_text,
        tags: filter_statuses_to_list()
    }));
    const responseText = await response.text();
    const postList = document.getElementsByClassName("post-list")[0];
    postList.innerHTML = responseText;
}

function edit_blog_entry(id) {
    const blog_entry_form = document.getElementById(`post-${id}`)
    const blog_entry_inputs = blog_entry_form.querySelectorAll('input, textarea');
    const all_edit_buttons = document.querySelectorAll('button.edit');
    const blog_entry_edit_button = document.getElementById(`edit-${id}`)
    const submit_button = document.getElementById(`submit-${id}`)

    if (!editing) {
        editing = true;
        for (input of blog_entry_inputs) {
            input.disabled = false;
        }
        submit_button.disabled = false;
        for (button of all_edit_buttons) {
            button.disabled = true;
        }
        blog_entry_edit_button.disabled = false;
        blog_entry_edit_button.innerText = "Cancel"
    }
    else {
        editing = false;
        blog_entry_form.reset();
        for (input of blog_entry_inputs) {
            input.disabled = true;
        }
        submit_button.disabled = true;
        for (button of all_edit_buttons) {
            button.disabled = false;
        }
        blog_entry_edit_button.innerText = "Edit"
    }
}

async function submitEntryChanges(id) {
    const response = await fetch("write_blog.php", {
        method: "POST",
        body: URLSearchParams({
            
        })
    })
}

function submit_blog_entry(id) {
    editing = false;

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