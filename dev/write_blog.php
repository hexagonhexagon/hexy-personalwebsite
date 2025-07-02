<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/root_path_function.php';
require_once serverRootPath('/blog/includes/blog_db.php');

$db = new BlogDB();
$isConnected = $db->connect(AccessMode::ReadWrite);