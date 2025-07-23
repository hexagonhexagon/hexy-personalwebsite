const search = document.getElementById("search-input");
const form = document.getElementById("search-form");

const tag_filter_checkboxes = document.querySelectorAll(".tags-filter-list input");

const filter_statuses = {};
for (let checkbox of tag_filter_checkboxes) {
    filter_statuses[checkbox.id] = checkbox.checked;
}

let editing = false;

function filterStatusesToList() {
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
        tags: filterStatusesToList()
    }));
    const response_text = await response.text();
    const postList = document.getElementsByClassName("post-list")[0];
    postList.innerHTML = response_text;
}

function toggleEditingEntry(id) {
    const blog_entry_form = document.getElementById(`post-${id}`);
    const blog_entry_inputs = blog_entry_form.querySelectorAll('input, textarea');
    const blog_entry_set_to_now_buttons = blog_entry_form.querySelectorAll('button.set-to-now');
    const all_edit_buttons = document.querySelectorAll('button.edit');
    const blog_entry_edit_button = document.getElementById(`edit-${id}`);
    const submit_button = document.getElementById(`submit-${id}`);

    if (!editing) {
        editing = true;
        for (input of blog_entry_inputs) {
            input.disabled = false;
        }
        for (button of blog_entry_set_to_now_buttons) {
            button.disabled = false;
        }
        submit_button.disabled = false;
        for (button of all_edit_buttons) {
            button.disabled = true;
        }
        blog_entry_edit_button.disabled = false;
        blog_entry_edit_button.innerText = "Cancel";
    }
    else {
        editing = false;
        for (input of blog_entry_inputs) {
            input.disabled = true;
        }
        for (button of blog_entry_set_to_now_buttons) {
            button.disabled = true;
        }
        submit_button.disabled = true;
        for (button of all_edit_buttons) {
            button.disabled = false;
        }
        blog_entry_edit_button.innerText = "Edit";
    }
}

let logTimer;
function setLogText(id, text) {
    const log_span = document.getElementById(`log-${id}`);
    log_span.innerText = text;
}

// time is in ms
function flashLogText(id, text, time) {
    setLogText(id, text);   
    window.clearTimeout(logTimer);
    logTimer = window.setTimeout(() => setLogText(id, ""), time);
}

function editBlogEntry(id) {
    toggleEditingEntry(id);

    if (!editing) {
        const blog_entry_form = document.getElementById(`post-${id}`)
        blog_entry_form.reset();
    }
}

async function submitEntryChanges(id) {
    const blog_entry_form = document.getElementById(`post-${id}`);
    const response = await fetch("write_blog.php", {
        method: "POST",
        body: new FormData(blog_entry_form)
    })
    const response_text = await response.text();
    if (response.status === 200) {
        toggleEditingEntry(id); // editing was successful: stop editing
        flashLogText(id, response_text, 2000);
        // refresh tags list, as it might have changed
    }
    else {
        // otherwise, editing failed: keep editing entry
        setLogText(id, response_text);
    }
}

function submitBlogEntry(id) {
    submitEntryChanges(id);
}

const timeFormatter24Hr = Intl.DateTimeFormat("en-US", {
    "hour": "2-digit",
    "minute": "2-digit",
    "second": "2-digit",
    "hourCycle": "h23",
})
// return current time in local ISO time format, needed for filling in value of datetime-local input
function getCurrentTime() {
    const currentDateTime = new Date();
    // ISO string format: yyyy-mm-ddThh:mm:ss.xxxZ
    const splitDateTime = currentDateTime.toISOString().split("T");
    const localTime = timeFormatter24Hr.format(currentDateTime);
    splitDateTime[1] = localTime;
    return splitDateTime.join("T");
}

function setLastEditDateToNow(id) {
    const lastEditInput = document.getElementById(`lastedit-${id}`);
    lastEditInput.value = getCurrentTime();
}

function setPublishDateToNow(id) {
    const lastEditInput = document.getElementById(`lastedit-${id}`);
    const publishInput = document.getElementById(`publish-${id}`);
    lastEditInput.value = "";
    publishInput.value = getCurrentTime();
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
            tags: filterStatusesToList()
        });
    }
)