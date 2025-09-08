<?php
require_once 'includes/blog_functions.php';
require_once 'includes/blog_db.php';

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
foreach ($posts as $post) {
    $id = $post['id'];
    $tags = $db->queryPreparedStmt(
        [ $id ]
    );
    echo formatPost($post, $tags);
}