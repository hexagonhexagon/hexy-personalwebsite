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

set_exception_handler(function (Throwable $e) {
    $message = $e->getMessage();
    echo "couldn't update database: $message";
    http_response_code(500);
    exit();
});

$post_data = $_POST;
$action = $post_data['action'];
unset($post_data['action']);

if ($action === 'edit') {
    $id = $post_data['id'];
    $tags_string = $post_data['tags'];
    $content_filename = $post_data['content_filename'];

    if ($content_filename !== '') {
        if (!in_array($content_filename, getValidContentFilenames())) {
            throw new DomainException("content_filename '$content_filename' is invalid");
        }
    }

    $edit_post_transaction = [
        buildEditPostQuery($post_data),
        buildDeleteTagsQuery($id),
    ];

    if ($tags_string !== '') {
        $tags = explode(',', $tags_string);
        $sanitized_tags = sanitizeTagsList($tags);
        array_push(
            $edit_post_transaction, 
            buildAddTagsQuery($id, $sanitized_tags)
        );
    }

    $db->transaction($edit_post_transaction);
}
else if ($action === 'delete') {
    $id = $post_data['id'];
    
    $db->transaction([
        buildDeleteTagsQuery($id),
        buildDeletePostQuery($id),
    ]);
}
else if ($action === 'add') {
    $new_post_data = $db->querySingle(...buildAddPostQuery());
    $id = $new_post_data['id'];
    echo $id;
}
else {
    throw new DomainException("unknown action '$action'");
}
