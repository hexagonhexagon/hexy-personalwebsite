<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/root_path_function.php';
require_once serverRootPath('/includes/make_header_footer.php');

require_once 'includes/blog_functions.php';
include 'includes/conn_to_db.php';
if ($db != null) {
    $id = safe_get_input('id');
    // do numeric filter on id?
    $post_query = $db->prepare('SELECT * FROM posts WHERE id=?');
    $post_query->execute(array($id));
    $post = $post_query->fetch(PDO::FETCH_ASSOC);
    $post_query->closeCursor();

    if ($post != null) {
        $title = $post['title'];

        $tags_query = $db->prepare('SELECT tag FROM tags where id=?');
        $tags_query->execute(array($post['id']));
        $tags = $tags_query->fetchAll(PDO::FETCH_ASSOC);
    }
    else {
        $title = '???';
    }
}
else {
    $title = '???';
    $post = null;
}

makeHeader(
    title: $title, 
    stylesheets: array(
        '/styles/post-info.css',
        '/styles/post.css'
    ) 
);
?>
<main>
    <section>
        <h2><?php echo $title; ?></h2>
        <?php if ($post != null): make_post_info($post, $tags); endif; ?>
    </section>
    <?php
        $content_filename = $post['content_filename'];
        $is_valid_filename = filter_var(
            $content_filename,
            FILTER_VALIDATE_REGEXP,
            array(
                'options'=>array(
                    'regexp'=>"/^[a-z_]{1,100}$/"
                )
            )
        );

        if ($is_valid_filename == false) {
            $path_to_content = 'posts/does_not_exist.php';
        }
        else {
            $path_to_content = "posts/$content_filename.php";
            if (!file_exists($path_to_content)) {
                $path_to_content = 'posts/does_not_exist.php';
            }
        }

        include $path_to_content;
    ?>
</main>
<?php makeFooter(); ?>