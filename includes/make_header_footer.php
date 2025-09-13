<?php
require_once 'nonce_csp.php';

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
            <?php secure_include($stylesheets, $scripts); ?>
            <link rel="preload" as="image" href="/images/hamburger-menu-open.svg" media="(max-width:700px)">
            <title><?= $title; ?> | polytropica</title>
        </head>
        <body>
            <header>
                <input type="checkbox" id="hamburger-input">
                <label for="hamburger-input" id="hamburger-menu"><div class="screen-reader-only">Expand side menu</div></label>
                <h1>polytropica</h1>
            </header>
            <div id="nav-container">
                <nav>
                    <a href="/index.php">home</a>
                    <ul>
                        <li><a href="/blog/">blog</a></li>
                        <li><a href="/talks.php">math talks</a></li>
                        <li>
                            <a href="/games/">games</a>
                            <ul>
                                <li><a href="/games/incremental_game/">untitled incremental game</a></li>
                            </ul>
                        </li>
                        
                        
                        
                    </ul>
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