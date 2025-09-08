<?php
require_once 'includes/blog_functions.php';
require_once 'includes/blog_db.php';

$db = new BlogDB();
$isConnected = $db->connect(AccessMode::ReadOnly);
if (!$isConnected) {
    echo "<p>couldn't find any tags, sorry</p>";
    exit();
}

$tags_data = $db->query(
    'SELECT tag, COUNT(*) AS count FROM tags GROUP BY tag ORDER BY count DESC',
    []
);
$tags_to_highlight = safeGetInputArray('tags');

foreach ($tags_data as $tag): 
    $tag_name = $tag['tag'];
    $sanitized_tag_name = sanitizeTag($tag['tag']);
    $tag_count = $tag['count']; 
    // write to document ?>

    <li>
        <input 
            type="checkbox" 
            name="<?= $sanitized_tag_name ?>" 
            id="<?= $sanitized_tag_name ?>" 
            <?php 
            if (in_array($sanitized_tag_name, $tags_to_highlight))
            {
                echo 'checked'; 
            } ?>
        >
        <label for="<?= $sanitized_tag_name ?>">
            <?= $sanitized_tag_name ?> <span class="tag-count">(<?= $tag_count ?>)</span>
        </label>
    </li>
<?php 
endforeach;