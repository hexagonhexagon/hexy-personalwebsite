<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/root_path_function.php';
require_once serverRootPath('/blog/includes/blog_db.php');
require_once serverRootPath('/dev/includes/edit_blog_functions.php');

$db = new BlogDB();
$isConnected = $db->connect(AccessMode::ReadWrite);

if (!$isConnected) {
    echo "couldn't connect to database";
    http_response_code(500);
    exit();
}

$post_data = $_POST;
$id = $post_data['id'];
$tags = explode(',', $post_data['tags']);

try {
    $db->transaction([
        buildEditPostQuery($post_data),
        buildDeleteTagsQuery($id),
        buildAddTagsQuery($id, $tags),
    ]);
    echo 'update successful';
}
catch (Exception $e) {
    echo "couldn't update database: $e->getMessage()";
    http_response_code(500);
}
