<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/root_path_function.php';
require_once serverRootPath('/includes/make_header_footer.php');
makeHeader(
    title: 'home',
    stylesheets: [
        '/styles/blog.css',
        '/styles/post-info.css',
    ],
);

require_once serverRootPath('/blog/includes/blog_db.php');
require_once serverRootPath('/blog/includes/blog_functions.php');

$db = new BlogDB();
$is_connected = $db->connect(AccessMode::ReadOnly);
?>
<main>
    <section>
        <h2>home</h2>
        <p>Hey there, I'm Allison (she/her)! I mainly go by <b>hexy</b> on the internet, although that name is usually taken so I'm hexyhexagon, hexagonhexagon, and more rarely dualhexagon. I'm a big fan of anything mathematics, computer science, and computer security, and in my spare time I play a bunch of video games and board games.</p>
        <p>I built this website mainly as a way to have a single place to host everything I'm doing, as I haven't had a place for that until I made this. This place is a bit light on content right now, but that <b>will</b> be changing, I assure you.</p>
    </section>
    <section>
        <h2>recent activity</h2>
        <ul class="post-list">
            <?php
            if ($is_connected) {
                $posts = $db->query(...buildRecentPostsQuery());
                $db->prepare('SELECT tag FROM tags where id=?');
                foreach ($posts as $post) {
                    $id = $post['id'];
                    $tags = $db->queryPreparedStmt(
                        [ $id ]
                    );
                    echo formatPost($post, $tags);
                }
            }
            else {
                echo "<p>can't find the blog posts, sorry.</p>";
            }
            ?>
        </ul>
    </section>
</main>
<?php makeFooter(); ?>