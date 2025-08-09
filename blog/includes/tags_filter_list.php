<?php
require_once 'blog_functions.php';
require_once 'blog_db.php';

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
    $sanitized_tag_name = htmlspecialchars($tag['tag']);
    $tag_count = $tag['count']; 
    // write to document ?>

    <li>
        <input 
            type="checkbox" 
            name="<?= $tag_name ?>" 
            id="<?= $tag_name ?>" 
            <?php 
            if (in_array($tag_name, $tags_to_highlight))
            {
                echo 'checked'; 
            } ?>
        >
        <label for="<?= $tag_name ?>">
            <?= $sanitized_tag_name ?> <span class="tag-count">(<?= $tag_count ?>)</span>
        </label>
    </li>
<?php 
endforeach;