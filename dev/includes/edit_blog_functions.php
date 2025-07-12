<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/root_path_function.php';
require_once serverRootPath('/blog/includes/blog_functions.php');

function formatDateEditBox(string $name, string $date) {
    return <<<END
    <input type="datetime-local" name="$name" value="$date" disabled>
    END;
}

/**
 * Given a blog post, return HTML for the last edit date and post date.
 * 
 * @param array $post the details of the post from a blog DB query
 * @return string the correctly formatted last edit/post date as string of HTML
 */
function formatPostDateDev(array $post) {
    $output = '';

    $last_edit_date_html = formatDateEditBox('last_edit_date', $post['last_edit_date'] ?? '');
    $output .= "last edited $last_edit_date_html<br>";

    $post_date_html = formatDateEditBox('post_date', $post['post_date']);
    $output .= "published $post_date_html";
        
    return <<<END
        <p class="post-date">$output</p>
    END;
}

/**
 * Given a tag list, return HTML for the list of tags for that post.
 * 
 * @param array $tags the details of the tags from a blog DB query
 * @return string the correctly formatted list of tags as string of HTML
 */
function formatTagsListDev(array $tags) {
    $actual_tags = array_map(function($tag) { return $tag['tag']; }, $tags);
    $tags_text = implode(',', $actual_tags);

    return <<<END
        tags = <textarea name="tags" type="text" disabled>$tags_text</textarea>
    END;
}

/**
 * Given blog post data and the list of tags, return the HTML for the post tags and last edit date/post date.
 * 
 * @param array $post the details of the post from a blog DB query
 * @param array $tags the details of the tags from a blog DB query
 * @return string the correctly formatted tags and post date data as string of HTML
 */
function formatPostInfoDev(array $post, array $tags) {
    $tags_html = formatTagsListDev($tags);
    $post_html = formatPostDateDev($post); 
    return <<<END
        <div class="post-info">
            $tags_html
            $post_html
        </div>
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