<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/root_path_function.php';
require_once serverRootPath('/includes/make_header_footer.php');
require_once 'includes/blog_functions.php';
require_once 'includes/blog_db.php';

$db = new BlogDB();
$isConnected = $db->connect(AccessMode::ReadOnly);

if (!$isConnected) {
    $title = '???';
    $post = null;
}
else {
    $id = safeGetInput('id');
    $post = $db->querySingle(
        'SELECT * FROM posts WHERE id=?',
        [ $id ]
    );

    if ($post === null) {
        $title = '???';
    }
    else {
        $title = $post['title'];
        $tags = $db->query(
            'SELECT tag FROM tags where id=?',
            [ $id ]
        );
    }
}

makeHeader(
    title: $title, 
    stylesheets: [
        '/styles/post-info.css',
    ]
);
?>
<main>
    <section>
        <h2><?= $title; ?></h2>
        <?php 
        if ($post !== null) { 
            echo formatPostInfo($tags, $post['post_date'], $post['last_edit_date']);
        } ?>
    </section>
    <?php
        $content_filename = $post['content_filename'] ?? '';
        $is_valid_filename = filterRegex($content_filename, "/^[a-z_]{1,100}$/");

        if (!$is_valid_filename) {
            $path_to_content = 'posts/does_not_exist.php';
        }
        else {
            $path_to_content = "posts/$content_filename.php";
            if (!file_exists($path_to_content)) {
                $path_to_content = 'posts/does_not_exist.php';
            }
        }

        require_once $path_to_content;
    ?>
</main>
<?php makeFooter(); ?>