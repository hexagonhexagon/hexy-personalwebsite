<?php
    require 'blog_functions.php';

    require 'conn_to_db.php';
    if ($db != null):
        $search = safe_get_input('q');
        if ($search != null) {
            $search = "%{$search}%";
            $search = str_replace(' ', '%', $search);
        }
        $search_tags = safe_get_input('tags');
        if ($search_tags != null) {
            //nothing yet
        }

        $made_a_search = true;
        if ($search == null && $search_tags == null) {
            $posts_query = $db->query('SELECT * FROM posts ORDER BY COALESCE(last_edit_date, post_date) DESC');
            $made_a_search = false;
        }
        elseif ($search != null && $search_tags == null) {
            $posts_query = $db->prepare('SELECT * FROM posts WHERE title LIKE ? ESCAPE \'\\\' ORDER BY COALESCE(last_edit_date, post_date) DESC');
            $posts_query->execute(array($search));
        }
        elseif ($search == null && $search_tags != null) {
            //nothing yet
        }
        else {
            //nothing yet
        }
        
        $posts = $posts_query->fetchAll(PDO::FETCH_ASSOC);
        if (count($posts) == 0): ?>
            <p>no results match your search, try searching something else.</p>
        <?php else:
            if ($made_a_search): ?>
                <p>your search returned <?php echo pluralize(count($posts), 'result'); ?>:</p>
            <?php endif;

            $tags_query = $db->prepare('SELECT tag FROM tags where id=?');
            foreach ($posts as $post): ?>
                <li>
                    <h3><a href="post.php?<?php echo http_build_query(array('id'=>$post['id'])); ?>"><?php echo $post['title']; ?></a></h3>
                    <?php
                        $tags_query->execute(array($post['id']));
                        $tags = $tags_query->fetchAll(PDO::FETCH_ASSOC);
                        make_post_info($post, $tags);
                    ?>
                    <p><?php echo $post['summary']; ?></p>
                </li>
        <?php   
            endforeach; 
        endif;
    else: ?>
        <p>can't find the blog posts, sorry.</p>
    <?php endif; 
?>