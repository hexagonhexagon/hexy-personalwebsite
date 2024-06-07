<?php
// unsafe for user input
function server_root_path(string $path) {
    return $_SERVER['DOCUMENT_ROOT'] . $path;
}
$title = '';
$stylesheets = array();
$scripts = array();