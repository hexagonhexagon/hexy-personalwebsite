<?php
/**
 * Given path, starts from server root and gives absolute path to the file.
 * NOT SAFE FOR ARBITRARY USER INPUT
 * 
 * @param string $path the path to the file, starting from server root
 * @return string the absolute path to the file
 */
function serverRootPath(string $path) {
    return $_SERVER['DOCUMENT_ROOT'] . $path;
}