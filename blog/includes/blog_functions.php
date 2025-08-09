<?php

/**
 * Given a key, filter the get input through htmlspecialchars & trim before returning it. If it doesn't exist, return null.
 * 
 * @param string $key the get parameter
 * @return ?string either the santitized get input, or null if it didn't exist
 */
function safeGetInput(string $key) {
    $get_input = filter_input(INPUT_GET, $key, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    if ($get_input === null or $get_input === false or $get_input === '') {
        return null;
    }
    else {
        return trim($get_input);
    }
}

/**
 * Given a key, filter the get input through htmlspecialchars, trim, and escaping % signs before returning it. Necessary for SQL queries with % in them. If it doesn't exist, return null.
 * 
 * @param string $key the get parameter
 * @return ?string either the santitized get input, or null if it didn't exist
 */
function safeGetInputSanitizePercent(string $key) {
    $get_input = safeGetInput($key);
    if ($get_input === null) {
        return null;
    }
    else {
        return str_replace('%', '\%', safeGetInput($key));
    }
}

 /**
 * Given a key, filter the get input through htmlspecialchars, trim, and turn it into an array before returning it. Only use when get input value is a comma-separated list.
 * 
 * @param string $key the get parameter
 * @return array either the santitized get input as an array, or [] if it didn't exist
 */
function safeGetInputArray(string $key) {
    $get_input = safeGetInput($key);
    if ($get_input === null) {
        return [];
    }
    else {
        return explode(',', $get_input);
    }
}

/**
 * Given a string and a regex pattern, return whether the string matches the pattern or not.
 * 
 * @param string $input the string to test the regex against
 * @param string $regex the regex to test
 * @return bool true if it passes, false if it doesn't
 */
function filterRegex(string $input, string $regex) {
    $options = [
        'options'=>[
            'regexp'=>$regex
        ]
    ];
    $filter = filter_var(
        $input,
        FILTER_VALIDATE_REGEXP,
        $options
    );

    if ($filter !== false) {
        return true;
    }
    else {
        return false;
    }
}

/**
 * Given the number of a certain object, say either "1 thing" or "3 things" with correct pluralization.
 * 
 * @param int $number the number of things you have
 * @param string $word the name of the thing you have
 * @return string a string with the number and the properly pluralized word after it
 */
function pluralize(int $number, string $word) {
    if ($number > 1) {
        return "$number {$word}s";
    }
    else {
        return "$number {$word}";
    }
}

/**
 * Given a date, return HTML to format it as {month} {day}, {year} ({relative time} ago) or ({relative time} ago) depending on context.
 * 
 * @param string $date_string the date to format properly
 * @return string the correctly formatted date as string of HTML
 */
function formatDate(string $date_string) {
    $date = date_create($date_string);
    $output_date = date_format($date, 'M d, o');

    $diff_to_now = date_diff($date, date_create());
    if ($diff_to_now->y !== 0) {
        $interval_string = pluralize($diff_to_now->y, 'year');
    }
    elseif ($diff_to_now->m !== 0) {
        $interval_string = pluralize($diff_to_now->m, 'month');
    }
    elseif ($diff_to_now->d !== 0) {
        $interval_string = pluralize($diff_to_now->d, 'day');
    }
    elseif ($diff_to_now->h !== 0) {
        $interval_string = pluralize($diff_to_now->h, 'hour');
    }
    elseif ($diff_to_now->i !== 0) {
        $interval_string = pluralize($diff_to_now->i, 'minute');
    }
    else {
        $interval_string = 'just now';
    }

    if ($interval_string !== 'just now') {
        $output_interval = "$interval_string ago";
    }
    else {
        $output_interval = "$interval_string";
    }

    return <<<END
        <span title="$date_string" class="long-time">
            <time datetime="$date_string">$output_date</time> ($output_interval)
        </span>
        <span title="$date_string" class="short-time">
            $output_interval
        </span>
    END;
}

/**
 * Given a blog post, return HTML for the last edit date and post date.
 * 
 * @param array $post the details of the post from a blog DB query
 * @return string the correctly formatted last edit/post date as string of HTML
 */
function formatPostDate(array $post) {
    $output = '';

    if ($post['last_edit_date']) {
        $last_edit_date_html = formatDate($post['last_edit_date']);
        $output .= "last edited $last_edit_date_html<br>";
    }

    $post_date_html = formatDate($post['post_date']);
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
function formatTagsList(array $tags) {
    $tags_list_html = '';

    foreach ($tags as $tag) {
        $tag_name = $tag['tag'];
        $sanitized_tag_name = htmlspecialchars($tag_name);
        $tag_link = http_build_query(
            [ 'tags'=>$tag_name ]
        );

        $tags_list_html .= <<<END
            <a href="/blog/index.php?$tag_link"> 
                <li>$sanitized_tag_name</li>
            </a>
        END;
    }

    return <<<END
        <ul class="tags-list">
            $tags_list_html
        </ul>
    END;
}

/**
 * Given blog post data and the list of tags, return the HTML for the post tags and last edit date/post date.
 * 
 * @param array $post the details of the post from a blog DB query
 * @param array $tags the details of the tags from a blog DB query
 * @return string the correctly formatted tags and post date data as string of HTML
 */
function formatPostInfo(array $post, array $tags) {
    $tags_html = formatTagsList($tags);
    $post_html = formatPostDate($post); 
    return <<<END
        <div class="post-info">
            $tags_html
            $post_html
        </div>
    END;
}

/**
 * Given search query and list of tags to filter by, return a SQL query with a list of arguments to pass to the query for listing all the posts that match the conditions.
 * @param ?string $search the string containing words that each post title should contain
 * @param ?array $search_tags the list of tags that the post should contain
 * @return array an array of the query string and array of parameters to pass to the database
 */
function buildPostQuery(?string $search, ?array $search_tags) {
    $query_conditions = [];
    $params = [];

    if ($search !== null) {
        $search = "%{$search}%";
        $search = str_replace(' ', '%', $search);
        array_push($query_conditions, "title LIKE ? ESCAPE '\'");
        array_push($params, $search);
    }

    if ($search_tags !== []) {
        foreach ($search_tags as $tag) {
            array_push($query_conditions, 'EXISTS (SELECT tag FROM tags WHERE id=posts.id AND tag=?)');
            array_push($params, $tag);
        }
    }

    $posts_query_string = 'SELECT * FROM posts';

    if (count($query_conditions) !== 0) {
        $posts_query_string .= ' WHERE ' . implode(' AND ', $query_conditions);
    }

    $posts_query_string .= ' ORDER BY COALESCE(last_edit_date, post_date) DESC';

    return [$posts_query_string, $params];
}