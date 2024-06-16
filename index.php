<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/root_path_function.php';
require_once serverRootPath('/includes/make_header_footer.php');

makeHeader(title: 'home');
?>
<main>
    <section>
        <h2>home</h2>
        <p>Hey there, I'm Allison (she/her)! I mainly go by <b>hexy</b> on the internet, although that name is usually taken so I'm hexyhexagon, hexagonhexagon, and more rarely dualhexagon. I'm a big fan of anything mathematics, computer science, and computer security, and in my spare time I play a bunch of video games and board games.</p>
        <p>I built this website mainly as a way to have a single place to host everything I'm doing, as I haven't had a place for that until I made this. This place is a bit light on content right now, but that <b>will</b> be changing, I assure you.</p>
    </section>
    <section>
        <h2>recent activity</h2>
        <p>This is eventually where my blog posts will go, but I'm working on it right now and it isn't done yet. In the meantime, consider browsing the other sections of the website?</p>
    </section>
</main>
<?php makeFooter(); ?>