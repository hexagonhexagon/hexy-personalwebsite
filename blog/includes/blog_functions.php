<?php

/**
 * Given a key, filter the get input through htmlspecialchars & trim before returning it. If it doesn't exist, return null.
 * 
 * @param string $key the get parameter
 * @return string|null either the santitized get input, or null if it didn't exist
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
 * @return string|null either the santitized get input, or null if it didn't exist
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
 * Given a date, write HTML to format it as {month} {day}, {year} ({relative time} ago) or ({relative time} ago) depending on context.
 * 
 * @param string $date_string the date to format properly
 */
function makeDate(string $date_string) {
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
    ?>

    <span title="<?= $date_string ?>" class="long-time">
        <time datetime="<?= $date_string ?>"><?= $output_date; ?></time> (<?= $output_interval; ?>)
    </span>
    <span title="<?= $date_string ?>" class="short-time">
        <?= $output_interval; ?>
    </span>
<?php }

/**
 * Given a blog post, write the HTML for formatting the last edit date and post date.
 * 
 * @param array $post the details of the post from a blog DB query
 */
function makePostDate(array $post) { ?>
    <p class="post-date">
        <?php 
        $last_edit_date = $post['last_edit_date'];
        $post_date = $post['post_date'];

        if ($last_edit_date !== null) {
            echo 'last edited ';
            makeDate($last_edit_date);
            echo '<br>';
        }
        echo 'published ';
        makeDate($post_date);
        ?>
    </p>
<?php }

/**
 * Given a tag list, write the HTML for formatting the list of tags for that post.
 * 
 * @param array $tags the details of the tags from a blog DB query
 */
function makeTagsList(array $tags) { ?> 
    <ul class="tags-list">
        <?php 
        foreach ($tags as $tag): 
            $tag_name = $tag['tag'];
            $tag_link = http_build_query(
                array('tags'=>$tag_name)
            );
        ?>

        <a href="/blog/index.php?<?= $tag_link ?>"> 
            <li> 
                <?= $tag_name; ?> 
            </li>
        </a>
        <?php endforeach; ?>
    </ul>
<?php }

/**
 * Given blog post data and the list of tags, write the HTML for the post tags and last edit date/post date.
 * 
 * @param array $post the details of the post from a blog DB query
 * @param array $tags the details of the tags from a blog DB query
 */
function makePostInfo(array $post, array $tags) { ?>
    <div class="post-info"> 
        <?php
        makeTagsList($tags);
        makePostDate($post); 
        ?>
    </div>
<?php }