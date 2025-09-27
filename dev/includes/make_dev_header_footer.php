<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/root_path_function.php';
require_once serverRootPath('/includes/nonce_csp.php');

/**
 * Generates the header which includes head, actual header, and nav.
 * @param string $title the title of the webpage
 * @param array $stylesheets array of paths to stylesheets to include in head
 * @param array $scripts array of paths to scripts to include in head
 */
function makeHeader(string $title, array $stylesheets = [], array $scripts = []) { ?>
    <!DOCTYPE html>
    <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width">
            <?php secure_include_with_main($stylesheets, $scripts); ?>
            <link rel="preload" as="image" href="/images/hamburger-menu-open.svg" media="(max-width:700px)">
            <title><?= $title; ?> | polytropica dev</title>
        </head>
        <body>
            <header>
                <input type="checkbox" id="hamburger-input">
                <label for="hamburger-input" id="hamburger-menu"><div class="screen-reader-only">Expand side menu</div></label>
                <h1>polytropica dev</h1>
            </header>
            <div id="nav-container">
                <nav>
                    <a href="/dev/edit_blog.php">edit blog db</a>
                </nav>
            </div>
<?php }

/**
 * Generates the footer, which just closes out tags for now.
 */
function makeFooter() { ?>
        </body>
    </html>
<?php }