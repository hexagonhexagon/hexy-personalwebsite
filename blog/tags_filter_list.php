<?php
require_once 'conn_to_db.php';
if ($db === null): ?>
    <p>couldn't find any tags, sorry</p>
<?php endif;

$tags_query = $db->query('SELECT tag, COUNT(*) AS count FROM tags GROUP BY tag ORDER BY count DESC');
$tags_data = $tags_query->fetchAll(PDO::FETCH_ASSOC);

foreach ($tags_data as $tag): 
    $tag_name = $tag['tag'];
    $tag_count = $tag['count']; 
    // write to document ?>
    <li>
        <input type="checkbox" name="<?= $tag_name ?>" id="<?= $tag_name ?>">
        <label for="<?= $tag_name ?>">
            <?= $tag_name ?> <span class="tag-count">(<?= $tag_count ?>)</span>
        </label>
    </li>

<?php endforeach;