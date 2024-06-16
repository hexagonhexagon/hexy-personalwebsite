<?php
function safe_get_input(string $key) {
    $get_input = filter_input(INPUT_GET, $key, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    if ($get_input == null or $get_input == false) {
        return null;
    }
    else {
        return trim($get_input);
    }
}
function safe_get_input_sanitize_percent(string $key) {
    return str_replace('%', '\%', safe_get_input($key));
}

function pluralize(int $number, string $word) {
    if ($number > 1) {
        return "$number {$word}s";
    }
    else {
        return "$number {$word}";
    }
}

function format_date_html(string $date_string) {
    $date = date_create($date_string);
    $output_date = date_format($date, 'M d, o');
    $short_output_date = date_format($date, 'M \'y');

    $diff_to_now = date_diff($date, date_create());
    if ($diff_to_now->y != 0) {
        $interval_string = pluralize($diff_to_now->y, 'year');
    }
    elseif ($diff_to_now->m != 0) {
        $interval_string = pluralize($diff_to_now->m, 'month');
    }
    elseif ($diff_to_now->d != 0) {
        $interval_string = pluralize($diff_to_now->d, 'day');
    }
    elseif ($diff_to_now->h != 0) {
        $interval_string = pluralize($diff_to_now->h, 'hour');
    }
    elseif ($diff_to_now->i != 0) {
        $interval_string = pluralize($diff_to_now->i, 'minute');
    }
    else {
        $interval_string = 'just now';
    }

    if ($interval_string != 'just now') {
        $output_interval = "$interval_string ago";
    }
    else {
        $output_interval = "$interval_string";
    }

    $output = <<<END
        <span title="$date_string" class="long-time">
            <time datetime="$date_string">$output_date</time> ($output_interval)
        </span>
        <span title="$date_string" class="short-time">
            $output_interval
        </span>
    END;
    return $output;
}

function make_post_date(array $post) {
    $output = '';
    if ($post['last_edit_date'] != null){
        $output .= 'last edited ' . format_date_html($post['last_edit_date']) . '<br>';
    }
    $output .= 'published ' . format_date_html($post['post_date']); ?>

    <p class="post-date"><?php echo $output; ?></p>

<?php }

function make_tags_list(array $tags) { ?> 
    <ul class="tags-list">
        <?php foreach ($tags as $tag): ?>
        <a href="/blog/index.php?<?php echo http_build_query(array('tags'=>$tag['tag'])) ?>"> 
            <li> 
                <?php echo $tag['tag']; ?> 
            </li>
        </a>
            
        <?php endforeach; ?>
    </ul>
<?php }

function make_post_info(array $post, array $tags) { ?>
    <div class="post-info">
        <?php
            make_tags_list($tags);
            make_post_date($post); 
        ?>
    </div>
<?php }