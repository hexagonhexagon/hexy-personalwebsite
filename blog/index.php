<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/root_path_function.php';
require_once serverRootPath('/includes/make_header_footer.php');

makeHeader(
    title: 'blog', 
    stylesheets: [
        '/styles/blog.css',
        '/styles/post-info.css',
    ],
    scripts: [
        '/js/blogSearch.js',
    ]
);
require_once 'includes/blog_functions.php';

?>
<main>
    <section>
        <h2>blog</h2>
        <p>Welcome to my blog! This is where I rant about stuff that I care about, whether it's math, video games, coding, security, whatever piques my interest at the time. I always wanted to start one of these, it's both exciting and terrifying to actually have one now, even if my audience is negligible. Enjoy!</p>
    </section>
    <section>
        <h2>recent posts</h2>
        <form id="search-form">
            <div id="search-container">
                <input type="search" name="q" title="Search Blog" id="search-input" value="<?= safeGetInput('q') ?>">
                <button type="submit" id="search-button">
                    <span class="screen-reader-only">Search</span>
                </button>
            </div>
            <ul class="tags-filter-list">
                <?php require_once 'tags_filter_list.php'; ?>
            </ul>
        </form>
        <ul class="post-list">
            <!-- filled by js -->
        </ul>
    </section>
</main>
<?php makeFooter(); ?>