<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/root_path_function.php';
require_once serverRootPath('/includes/make_header_footer.php');

makeHeader(title: 'games');
?>
<main>
    <section>
        <h2>games</h2>
        <p>This section contains all the games I'm currently working on, which at the moment is just one.</p>
        <ul>
            <li><a href="./incremental_game/">Untitled Incremental Game</a></li>
        </ul>
    </section>
</main>
<?php
makeFooter();
?>