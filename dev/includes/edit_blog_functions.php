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

function formatContentFilenameDev(?string $content_filename) {
    return <<<END
        <p>content_filename = <input name="content_filename" type="text" value="$content_filename" disabled></p>
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
            <button id="submit-$id" type="submit" onclick="submitBlogEntry($id)" disabled>Submit</button>
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
    <input type="datetime-local" name="$name" id="$inputid" value="$date" disabled>
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
    $output .= "last edited $last_edit_date_html $last_edit_date_button<br>";

    $post_date_html = formatDateEditBox('post_date', $post['post_date'], $id);
    $post_date_button = formatSetToNowButton('post_date', $id);
    $output .= "published $post_date_html $post_date_button";
        
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

    return <<<END
        <div class="tags">tags = <textarea name="tags" type="text" disabled>$tags</textarea></div>
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
            <form id="post-$id" onsubmit="return false;">
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

function buildDeleteTagsQuery(string $id) {
    $query_string = 'DELETE FROM tags WHERE id = ?';
    $params = [$id];
    return [$query_string, $params];
}

function buildAddTagsQuery(string $id, array $tags) {
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