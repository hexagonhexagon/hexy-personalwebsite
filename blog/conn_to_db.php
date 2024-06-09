<?php
try {
    $db = new PDO('sqlite:' . getenv('BLOG_DB_PATH'));
    $db->query('SELECT id FROM posts LIMIT 1'); // Must use test query to see if it is actually there
} catch (PDOException $e) {
    error_log('there was an error: ' . $e->getMessage());
    $db = null;
}