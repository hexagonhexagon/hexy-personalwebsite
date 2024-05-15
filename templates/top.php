<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width">
        <link rel="stylesheet" href="styles/main.css">
        <title><?php echo $title; ?> | hexlab</title>
    </head>
    <body>
        <header>
            <input type="checkbox" id="hamburger-input">
            <label for="hamburger-input" id="hamburger-menu"></label>
            <h1>hexlab</h1>
        </header>
        <div id="nav-container">
            <nav>
                <a href="index.php">home</a>
                <ul>
                    <li><a href="blog.php">blog</a></li>
                    <li><a href="whyhexagon.php">hexagons?</a></li>
                    <li><a href="resume.php">resume</a></li>
                    <li><a href="talks.php">math talks</a></li>
                    <li><a href="mods.php">factorio mods</a></li>
                </ul>
            </nav>
        </div>