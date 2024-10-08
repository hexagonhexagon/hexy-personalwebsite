<?php

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
            <link rel="stylesheet" href="/styles/main.css">
            <?php foreach ($stylesheets as $style): ?>
                <link rel="stylesheet" href="<?= $style; ?>">
            <?php endforeach; ?>
            <?php foreach ($scripts as $script): ?>
                <script src="<?= $script; ?>"></script>
            <?php endforeach; ?>
            <link rel="preload" as="image" href="/images/hamburger-menu-open.svg" media="(max-width:700px)">
            <title><?= $title; ?> | hexlab</title>
        </head>
        <body>
            <header>
                <input type="checkbox" id="hamburger-input">
                <label for="hamburger-input" id="hamburger-menu"><div class="screen-reader-only">Expand side menu</div></label>
                <h1>hexlab</h1>
            </header>
            <div id="nav-container">
                <nav>
                    <a href="/index.php">home</a>
                    <ul>
                        <li><a href="/blog/">blog</a></li>
                        <li><a href="/resume.php">resume</a></li>
                        <li><a href="/talks.php">math talks</a></li>
                        <li>
                            <a href="/games/">games</a>
                            <ul>
                                <li><a href="/games/incremental_game">untitled incremental game</a></li>
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