<?php
require_once 'conn_to_db.php';
require_once 'blog_functions.php';
if ($db === null): ?>
    <p>couldn't find any tags, sorry</p>
<?php else:

    $tags_query = $db->query('SELECT tag, COUNT(*) AS count FROM tags GROUP BY tag ORDER BY count DESC');
    $tags_data = $tags_query->fetchAll(PDO::FETCH_ASSOC);
    $get_tags = explode(',', safeGetInput('tags') ?? '');

    foreach ($tags_data as $tag): 
        $tag_name = $tag['tag'];
        $tag_count = $tag['count']; 
        // write to document ?>
        <li>
            <input type="checkbox" name="<?= $tag_name ?>" id="<?= $tag_name ?>" <?php if (in_array($tag_name, $get_tags)): echo 'checked'; endif; ?>>
            <label for="<?= $tag_name ?>">
                <?= $tag_name ?> <span class="tag-count">(<?= $tag_count ?>)</span>
            </label>
        </li>

    <?php endforeach;
endif;