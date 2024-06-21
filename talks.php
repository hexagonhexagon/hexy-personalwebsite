<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/root_path_function.php';
require_once serverRootPath('/includes/make_header_footer.php');
require_once serverRootPath('/includes/talks.php');

makeHeader(
    title: 'resume', 
    stylesheets: ['/styles/talks.css'] 
);
?>

<main>
    <section>
        <h2>math talks</h2>
        <p>This is a list of math talks or math papers that I had a hand in writing. Most of these were done when I was at college and are written for an advanced high-school to undergraduate level of mathematics, so I can't promise you'll understand it. I wanted a space where I had everything in one place, and I figured I'd let others read them if they want.</p>
    </section>
    <section>
        <h2>presentations</h2>
        <?php makeTalkList($presentations); ?>
    </section>
    <section>
        <h2>papers</h2>
        <?php makeTalkList($papers); ?>
    </section>
</main>
<?php makeFooter(); ?>