
const tag_filter_checkboxes = document.querySelectorAll(".tags-filter-list input");

const filter_statuses = {};
for (let checkbox of tag_filter_checkboxes) {
    filter_statuses[checkbox.id] = checkbox.checked;
}

let editing = false;
let editing_id = 0;

function filterStatusesToList() {
    const output_list = [];
    for (let checkbox_name in filter_statuses) {
        if (filter_statuses[checkbox_name] === true) {
            output_list.push(checkbox_name);
        }
    }
    return output_list;
}

let search_text;

async function getSearchResults() {
    const response = await fetch("edit_blog_search.php?" + new URLSearchParams({
        q: search_text,
        tags: filterStatusesToList()
    }));
    const response_text = await response.text();
    const post_list = document.getElementsByClassName("post-list")[0];
    post_list.innerHTML = response_text;

    addOnClicks();
    convertAllFieldsServerTimeToLocalTime();
    addAllTagsValidators();
    addAllContentFilenameValidators();
    makeSubmitButtonsDisableIfInvalid();
}

async function refreshTagsList() {
    const response = await fetch("/blog/tags_filter_list.php");
    const response_text = await response.text();
    const tags_filter_list = document.getElementsByClassName("tags-filter-list")[0];
    tags_filter_list.innerHTML = response_text;
}


function toggleEditingEntry(id) {
    const blog_entry_form = document.getElementById(`post-${id}`);
    const blog_entry_inputs = blog_entry_form.querySelectorAll('input, textarea');
    const blog_entry_set_to_now_buttons = blog_entry_form.querySelectorAll('button.set-to-now');
    const all_edit_buttons = document.querySelectorAll('button.edit');
    const blog_entry_edit_button = document.getElementById(`edit-${id}`);
    const submit_button = document.getElementById(`submit-${id}`);

    if (!editing) {
        blog_entry_form.classList.add("active");
        editing = true;
        editing_id = id;
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
        blog_entry_form.classList.remove("active");
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

let log_timer;
function setLogText(id, text) {
    const log_span = document.getElementById(`log-${id}`);
    log_span.innerText = text;
}

// time is in ms
function flashLogText(id, text, time) {
    setLogText(id, text);   
    window.clearTimeout(log_timer);
    log_timer = window.setTimeout(() => setLogText(id, ""), time);
}

function editBlogEntry(id) {
    toggleEditingEntry(id);

    if (!editing) {
        const blog_entry_form = document.getElementById(`post-${id}`)
        blog_entry_form.reset();
    }
}

async function submitEditChanges(id) {
    const blog_entry_form = document.getElementById(`post-${id}`);
    const post_data = new FormData(blog_entry_form);
    post_data.append("action", "edit");
    convertFormDataLocalTimeToServerTime(post_data);
    const response = await fetch("write_blog.php", {
        method: "POST",
        body: post_data,
    })
    if (response.status === 200) {
        toggleEditingEntry(id); // editing was successful: stop editing
        refreshTagsList(); // refresh tags list, as it might have changed
        flashLogText(id, "update successful", 2000);
    }
    else {
        // otherwise, editing failed: keep editing entry
        const response_text = await response.text();
        setLogText(id, response_text);
    }
}

function deletePostLi(id) {
    // delete li contents
    const post_form = document.getElementById(`post-${id}`);
    const parent_li = post_form.parentNode;
    post_form.remove();

    // add "delete successful text"
    const deleted_text = document.createElement("div");
    deleted_text.className = "deleted-text";
    deleted_text.innerText = "delete successful";
    parent_li.appendChild(deleted_text);

    // delete li after 2s
    window.setTimeout(() => parent_li.remove(), 2000);
}

async function deleteBlogEntry(id) {
    const post_title_input = document.querySelector(`#post-${id} input[name="title"]`);
    const post_title = post_title_input.value;
    if (confirm(`Are you sure you want to delete "${post_title}"?`)) {
        const response = await fetch("write_blog.php", {
            method: "POST",
            body: new URLSearchParams({
                action: "delete",
                id: id,
            }),
        });
        const response_text = await response.text();
        if (response.status === 200) {
            if (editing) {
                toggleEditingEntry(id);
            }
            refreshTagsList();
            deletePostLi(id);
        }
        else {
            setLogText(id, response_text);
        }
    }
}

async function addBlogEntry() {
    const response = await fetch("write_blog.php", {
        method: "POST",
        body: new URLSearchParams({
            action: "add",
        })
    });
    const response_text = await response.text();

    const add_post_log = document.getElementById("add-post-log");
    if (response.status === 200) {
        add_post_log.innerText = "";
        const search_form = document.getElementById("search-form");
        search_form.reset();
        const new_post_id = parseInt(response_text);
        await getSearchResults();
        if (editing) {
            editBlogEntry(editing_id);
        }
        editBlogEntry(new_post_id);
    }
    else {
        add_post_log.innerText = response_text;
    }
}

const time_formatter_24hr = Intl.DateTimeFormat("en-US", {
    "hour": "2-digit",
    "minute": "2-digit",
    "second": "2-digit",
    "hourCycle": "h23",
})

// implementation thanks to 
// https://stackoverflow.com/questions/43528766/get-date-iso-string-without-conversion-to-utc-timezone/76203735#76203735
function convertServerTimeToLocalTime(datetime) {
    // ISO string format: yyyy-mm-ddThh:mm:ss.xxxZ
    const local_timestamp = datetime.getTime() - datetime.getTimezoneOffset() * 60 * 1000;
    const local_date = new Date(local_timestamp);
    return local_date.toISOString().split(".")[0];
}

function convertLocalTimeToServerTime(datetime) {
    // ISO string format: yyyy-mm-ddThh:mm:ss.xxxZ
    const server_time = datetime.toISOString()
    return server_time.split(".")[0]
}

function convertFormDataLocalTimeToServerTime(form_data) {
    const fields_to_edit = ["last_edit_date", "post_date"]
    for (field_name of fields_to_edit) {
        const field_value = form_data.get(field_name);
        if (field_value) {
            const new_field_value = convertLocalTimeToServerTime(new Date(field_value));
            form_data.set(field_name, new_field_value);
        }
    }
}

function convertAllFieldsServerTimeToLocalTime() {
    const datetime_fields = document.querySelectorAll('input[type="datetime-local"]');
    for (datetime_field of datetime_fields) {
        if (datetime_field.value) { 
            const datetime_string_utc = datetime_field.value + "Z";
            const datetime = new Date(datetime_string_utc);
            datetime_field.value = convertServerTimeToLocalTime(datetime);
        }
    }
}

// return current time in local ISO time format, needed for filling in value of datetime-local input
function getCurrentTime() {
    const current_date_time = new Date();
    return convertServerTimeToLocalTime(current_date_time);
}

function setLastEditDateToNow(id) {
    const last_edit_input = document.getElementById(`lastedit-${id}`);
    last_edit_input.value = getCurrentTime();
}

function setPublishDateToNow(id) {
    const last_edit_input = document.getElementById(`lastedit-${id}`);
    const publish_input = document.getElementById(`publish-${id}`);
    last_edit_input.value = "";
    publish_input.value = getCurrentTime();
}

const tags_list_pattern = /^([a-z0-9 ]+,)*([a-z0-9 ]+)$|^$/;

function addTagsValidator(textarea) {
    textarea.addEventListener("input", (event) => {
        var textarea = event.target;
        const tags_text = textarea.value;
        if (!tags_list_pattern.test(tags_text)) {
            textarea.setCustomValidity("fail regex");
        } 
        else {
            textarea.setCustomValidity(""); // good
        }
    })
}

function addAllTagsValidators() {
    const tags_textareas = document.querySelectorAll('textarea[name="tags"]');
    for (textarea of tags_textareas) {
        addTagsValidator(textarea);
    }
}


function isValidContentFilename(cf_text) {
    const content_filename_options = document.getElementById("content-filenames").options;
    if (!cf_text) {
        return true;
    }
    for (option of content_filename_options) {
        if (cf_text === option.value) {
            return true;
        }
    }
    return false;
}

function addContentFilenameValidator(cf_input) {
    cf_input.addEventListener("input", (event) => {
        var cf_input = event.target;
        const cf_text = cf_input.value;
        if (isValidContentFilename(cf_text)) {
            cf_input.setCustomValidity(""); // good
        }
        else {
            cf_input.setCustomValidity("not in content filenames");
        }

    })
}

function addAllContentFilenameValidators() {
    const content_filename_inputs = document.querySelectorAll('input[name="content_filename"]');
    for (cf_input of content_filename_inputs) {
        addContentFilenameValidator(cf_input);
    }
}

function addSubmitButtonDisabler(post_form) {
    post_form.addEventListener("input", (event) => {
        const post_form = event.currentTarget;
        const form_is_valid = post_form.checkValidity();
        const submit_button = post_form.querySelector('button[type="submit"]');
        submit_button.disabled = !form_is_valid
    })
}

function makeSubmitButtonsDisableIfInvalid() {
    const post_forms = document.querySelectorAll('form.post');
    for (post_form of post_forms) {
        addSubmitButtonDisabler(post_form);
    }
}

function addOnClick(post_form, post_id, class_name, on_click) {
    const button = post_form.querySelector("." + class_name);
    button.onclick = () => on_click(post_id);
}

function addOnClicks() {
    const post_forms = document.querySelectorAll('form.post');

    for (post_form of post_forms) {
        post_form.onsubmit = () => false;
        let post_id = post_form.querySelector("input[name='id']").value;
        let addOnClickThisForm = addOnClick.bind(null, post_form, post_id);
        addOnClickThisForm("edit", editBlogEntry);
        addOnClickThisForm("delete", deleteBlogEntry);
        addOnClickThisForm("submit", submitEditChanges);
        addOnClickThisForm("set-to-now-publish", setPublishDateToNow);
        addOnClickThisForm("set-to-now-last-edit", setLastEditDateToNow);
    }
}

// credit to https://webdesign.tutsplus.com/how-to-build-a-search-bar-with-javascript--cms-107227t for the below code
let debounceTimer;
function debounce(callback, delay) {
    window.clearTimeout(debounceTimer);
    debounceTimer = window.setTimeout(callback, delay);
}


// on document ready
document.addEventListener("DOMContentLoaded", function() {
    const search = document.getElementById("search-input");
    search.addEventListener("input",
        (event) => {
            search_text = event.target.value;
            debounce(() => getSearchResults(), 300);
        },
        false
    );

    const form = document.getElementById("search-form");
    form.onsubmit = () => false;
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

    const add_post_button = document.getElementById("add-post-button")
    add_post_button.onclick = addBlogEntry;

    search_text = search.value;
    getSearchResults();
    refreshTagsList();
});