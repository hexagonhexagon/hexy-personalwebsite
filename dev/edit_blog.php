<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/root_path_function.php';
require_once serverRootPath('/blog/includes/blog_db.php');
require_once serverRootPath('/blog/includes/blog_functions.php');
require_once serverRootPath('/dev/includes/edit_blog_functions.php');
require_once serverRootPath('/dev/includes/make_dev_header_footer.php');

$db = new BlogDB();
$isConnected = $db->connect(AccessMode::ReadOnly);

if (!$isConnected) {
    echo "<p>couldn't connect to the database, sorry</p>";
    exit();
}

makeHeader(
    title: 'edit blog db',
    stylesheets: [
        '/dev/dev.css',
        '/styles/blog.css',
        '/styles/post-info.css'
    ]
);
?>
<main>
    <section>
        <h2>posts</h2>
        <form id="search-form" onSubmit="return false;">
            <div id="search-container">
                <input type="search" name="q" title="Search Blog" id="search-input">
                <button type="submit" id="search-button">
                    <span class="screen-reader-only">Search</span>
                </button>
            </div>
            <ul class="tags-filter-list">
                <?php require_once serverRootPath('/blog/tags_filter_list.php'); ?>
            </ul>
        </form>
        <button id="add-post-button" onclick="addBlogEntry()">add post</button>
        <span id="add-post-log"></span>
        <?= formatDatalistContentFilenames(); ?>
        <ul class="post-list">
            <!-- filled by js -->
        </ul>
        <script src="editBlog.js"></script>
    </section>
</main>

<?php makeFooter() ?>
