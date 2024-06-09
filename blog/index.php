<?php
include $_SERVER['DOCUMENT_ROOT'] . '/templates/setup_variables.php';
$title = 'blog';
$stylesheets = array('/styles/blog.css', '/styles/post-info.css');
require server_root_path('/templates/top.php');


?>
<main>
    <section>
        <h2>blog</h2>
        <p>Welcome to my blog! This is where I rant about stuff that I care about, whether it's math, video games, coding, security, whatever piques my interest at the time. I always wanted to start one of these, it's both exciting and terrifying to actually have one now, even if my audience is negligible. Enjoy!</p>
    </section>
    <section>
        <h2>recent posts</h2>
        <div id="search-container">
            <input type="search" name="searchInput" title="Search Blog" id="search-input"><input type="button" aria-label="Search" id="search-button">
        </div>
        <ul class="post-list">
        </ul>
        <script src="/js/blogSearch.js"></script>
    </section>
</main>
<?php require server_root_path('/templates/bottom.php'); ?>