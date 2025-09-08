<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/root_path_function.php';
require_once serverRootPath('/blog/includes/blog_functions.php');

function formatTitleDev(string $title) {
    return <<<END
        <h3><input name="title" type="text" value="$title" disabled></h3>
    END;
}

function formatSummaryDev(?string $summary) {
    return <<<END
        <textarea name="summary" disabled>$summary</textarea>
    END;
}

function getValidContentFilenames() {
    $valid_filenames = [];
    foreach (new DirectoryIterator(serverRootPath('/blog/posts')) as $file) {
        if ($file->isDot() || $file->getFilename() === 'does_not_exist.php') {
            continue;
        } 
        else {
            array_push($valid_filenames, $file->getBasename('.php'));
        }
    }
    return $valid_filenames;
}

function formatDatalistContentFilenames() {
    $valid_filenames = getValidContentFilenames();
    $output = '<datalist id="content-filenames">';
    foreach ($valid_filenames as $filename) {
        $output .= "<option value=\"$filename\"/>";
    }
    $output .= "</datalist>";
    return $output;
}

function formatContentFilenameDev(?string $content_filename) {
    return <<<END
        <p>
            <label for="content_filename">content_filename = </label><input 
                name="content_filename"
                type="text"
                value="$content_filename"
                list="content-filenames"
                disabled />
        </p>
    END;
}

function formatHiddenIdInputDev(int $id) {
    return <<<END
        <input type="hidden" name="id" value="$id">
    END;
}

function formatEditButtonsDev(int $id) {
    return <<<END
        <div class="edit-buttons">
            <button id="edit-$id" type="button" class="edit" onclick="editBlogEntry($id)">Edit</button>
            <button id="delete-$id" type="button" class="delete" onclick="deleteBlogEntry($id)">Delete</button>
            <button id="submit-$id" type="submit" onclick="submitEditChanges($id)" disabled>Submit</button>
            <span id="log-$id"></span>
        </div>
    END;
}

function formatDateEditBox(string $name, ?string $date, int $postid) {
    if ($name === 'post_date') {
        $inputid = "publish-$postid";
    }
    else { // $name === 'last_edit_date'
        $inputid = "lastedit-$postid";
    }
    return <<<END
    <input type="datetime-local" name="$name" id="$inputid" value="$date" step=1 disabled>
    END;
}

function formatSetToNowButton(string $button_type, int $id) {
    if ($button_type === 'post_date') {
        $onclick = "setPublishDateToNow($id)";
    }
    else { // $button_type === 'last_edit_date'
        $onclick = "setLastEditDateToNow($id)";
    }
    return <<<END
    <button type="button" onclick="$onclick" class="set-to-now" disabled>Set to Now</button>
    END;
}

/**
 * Given a blog post, return HTML for the last edit date and post date.
 * 
 * @param array $post the details of the post from a blog DB query
 * @param int $id the internal post id stored in the DB
 * @return string the correctly formatted last edit/post date as string of HTML
 */
function formatPostDateDev(array $post, int $id) {
    $output = '';

    $last_edit_date_html = formatDateEditBox('last_edit_date', $post['last_edit_date'] ?? '', $id);
    $last_edit_date_button = formatSetToNowButton('last_edit_date', $id);
    $last_edit_date_label = '<label for="last_edit_date">last edited</label>';
    $output .= "$last_edit_date_label $last_edit_date_html $last_edit_date_button<br>";

    $post_date_html = formatDateEditBox('post_date', $post['post_date'], $id);
    $post_date_button = formatSetToNowButton('post_date', $id);
    $post_date_label = '<label for="post_date">published</label>';
    $output .= "$post_date_label $post_date_html $post_date_button";
        
    return <<<END
        <p class="post-date">$output</p>
    END;
}

/**
 * Given a tag list, return HTML for the list of tags for that post.
 * 
 * @param ?string $tags the details of the tags from a blog DB query
 * @return string the correctly formatted list of tags as string of HTML
 */
function formatTagsListDev(?string $tags) {
    $sanitized_tags = sanitizeTagsString($tags);

    return <<<END
        <div class="tags">
            <label for="tags">tags = </label>
            <textarea name="tags" type="text" disabled>$sanitized_tags</textarea>
        </div>
    END;
}

/**
 * Given blog post data and the list of tags, return the HTML for the post tags and last edit date/post date.
 * 
 * @param array $post the details of the post from a blog DB query
 * @param ?string $tags the details of the tags from a blog DB query
 * @param int $id the internal post id stored in the DB
 * @return string the correctly formatted tags and post date data as string of HTML
 */
function formatPostInfoDev(array $post, ?string $tags, int $id) {
    $tags_html = formatTagsListDev($tags);
    $post_html = formatPostDateDev($post, $id); 
    return <<<END
        <div class="post-info">
            $tags_html
            $post_html
        </div>
    END;
}

function formatPostDev(array $post, ?string $tags, int $id) {
    $title = formatTitleDev($post['title']);
    $post_info = formatPostInfoDev($post, $tags, $id);
    $summary = formatSummaryDev($post['summary']);
    $content_filename = formatContentFilenameDev($post['content_filename']);
    $hidden_id = formatHiddenIdInputDev($id);
    $edit_buttons = formatEditButtonsDev($id);

    return <<<END
        <li>
            <form id="post-$id" class="post" onsubmit="return false;">
                $title
                $post_info
                $summary
                $content_filename
                $hidden_id
                $edit_buttons
            </form>
        </li> 
    END;
}

/**
 * Given the post data to update the post with, return a SQL query with the query that will update the database, along with a list of all the data to pass along with it.
 * 
 * @param array $post_data all details about the post from the corresponding post form.
 */
function buildEditPostQuery(array $post_data) {
    $query_string = 'UPDATE posts SET ';
    $params = [];
    $set_values = [];

    foreach ($post_data as $post_attr => $post_value) {
        if ($post_attr === 'id' or $post_attr === 'tags') {
            continue;
        }
        if (str_contains($post_attr, "date") and $post_value === "") {
            $post_value = null;
        }
        array_push($set_values, "$post_attr = ?");
        array_push($params, $post_value);
    }

    $query_string .= implode(', ', $set_values) . ' WHERE id = ?';
    array_push($params, $post_data['id']);

    return [$query_string, $params];
}

function buildDeletePostQuery(int $id) {
    $query_string = 'DELETE FROM posts WHERE id = ?';
    $params = [$id];
    return [$query_string, $params];
}

function buildAddPostQuery() {
    $current_time = 'strftime("%Y-%m-%dT%H:%M:%S", "now")';
    $query_string = "INSERT INTO posts(title, post_date) VALUES (?, $current_time) RETURNING id";
    $params = ['new post'];
    return [$query_string, $params];
}

function buildDeleteTagsQuery(int $id) {
    $query_string = 'DELETE FROM tags WHERE id = ?';
    $params = [$id];
    return [$query_string, $params];
}

function buildAddTagsQuery(int $id, array $tags) {
    $query_string = 'INSERT INTO tags VALUES ';
    $values = [];
    $params = [];
    foreach ($tags as $tag) {
        array_push($values, '(?, ?)');
        array_push($params, $id, $tag);
    }
    $query_string .= implode(', ', $values);
    return [$query_string, $params];
}

function sanitizeTagsList(array $tags) {
    $disallowed_characters = '/[^a-z0-9 ]/';
    $sanitized_tags = [];
    foreach ($tags as $tag) {
        $sanitized_tag = preg_replace($disallowed_characters, '', $tag);
        array_push($sanitized_tags, $sanitized_tag);        
    }
    return $sanitized_tags;
}

function sanitizeTagsString(?string $tags) {
    $tags_array = explode(',', $tags);
    $sanitized_tags_array = sanitizeTagsList($tags_array);
    return implode(',', $sanitized_tags_array);
}