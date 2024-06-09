<?php
require 'blog_functions.php';

require 'conn_to_db.php';
if ($db != null):
    $query_conditions = array();
    $params = array();
    $made_a_search = false;

    $search = safe_get_input_sanitize_percent('q');
    if ($search != null) {
        $made_a_search = true;
        $search = "%{$search}%";
        $search = str_replace(' ', '%', $search);
        array_push($query_conditions, 'title LIKE ? ESCAPE \'\\\'');
        array_push($params, $search);
    }

    $search_tags = safe_get_input('tags');
    if ($search_tags != null) {
        $made_a_search = true;
        $tags_array = explode(',', $search_tags);
        foreach ($tags_array as $tag) {
            array_push($query_conditions, 'EXISTS (SELECT tag FROM tags WHERE id=posts.id AND tag=?)');
            array_push($params, $tag);
        }
    }

    $posts_query_string = 'SELECT * FROM posts';
    if (count($query_conditions) !== 0) {
        $posts_query_string .= ' WHERE ' . implode(' AND ', $query_conditions);
    }
    $posts_query_string .= ' ORDER BY COALESCE(last_edit_date, post_date) DESC';

    $posts_query = $db->prepare($posts_query_string);
    $posts_query->execute($params);
    $posts = $posts_query->fetchAll(PDO::FETCH_ASSOC);
    if (count($posts) == 0): 
    // write to document ?>
        <p>no results match your search, try searching something else.</p>
    <?php else:
        if ($made_a_search): 
        // write to document ?>
            <p>your search returned <?php echo pluralize(count($posts), 'result'); ?>:</p>
        <?php endif;

        $tags_query = $db->prepare('SELECT tag FROM tags where id=?');
        foreach ($posts as $post): 
        // start writing to document ?>
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