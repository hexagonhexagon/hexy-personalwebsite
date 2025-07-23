<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/root_path_function.php';
require_once serverRootPath('/dev/includes/edit_blog_functions.php');
require_once serverRootPath('/blog/includes/blog_db.php');

$db = new BlogDB();
$isConnected = $db->connect(AccessMode::ReadOnly);

if (!$isConnected) {
    echo "<p>can't find the blog posts, sorry.</p>";
    exit();
}

$search = safeGetInputSanitizePercent('q');
$search_tags = safeGetInputArray('tags');
$made_a_search = (($search !== null) or ($search_tags !== []));

[$posts_query_string, $params] = buildPostQuery($search, $search_tags);
$posts = $db->query($posts_query_string, $params);

if (count($posts) === 0) {
    if (!$made_a_search) {
        echo "<p>there are no blog posts yet, come back later.</p>";
    }
    else {
        echo "<p>no results match your search, try searching something else.</p>";
    }
    exit();
}

if ($made_a_search){
    $count_results = pluralize(count($posts), 'result');
    echo "<p>your search returned $count_results:</p>";
}

$db->prepare('SELECT tag FROM tags where id=?');
foreach ($posts as $post):
    $id = $post['id'];
    $post_link = http_build_query( 
        [ 'id'=>$id ]
    );
    // start writing to document ?>

    <li>
        <form id="post-<?= $id ?>" onsubmit="return false;">
            <h3> <input name="title" type="text" value="<?= $post['title']; ?>" disabled> </h3>
            <?php
                $tags = $db->queryPreparedStmt(
                    [ $id ]
                );
                echo formatPostInfoDev($post, $tags, $id);
            ?>
            <textarea name="summary" disabled><?= $post['summary']; ?></textarea>
            <p>content_filename = <input name="content_filename" type="text" value="<?= $post['content_filename'] ?>" disabled></p>
            <input type="hidden" name="id" value="<?= $id ?>">
            <button id="edit-<?= $id ?>" type="button" class="edit" onclick="editBlogEntry(<?= $id ?>)">Edit</button>
            <button id="submit-<?= $id ?>" type="submit" onclick="submitBlogEntry(<?= $id ?>)" disabled>Submit</button>
            <span id="log-<?= $id ?>"></span>
        </form>
    </li> 
<?php 
endforeach;