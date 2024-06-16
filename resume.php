<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/root_path_function.php';
require_once serverRootPath('/includes/make_header_footer.php');
require_once serverRootPath('/includes/resume.php');

makeHeader(
    title: 'resume', 
    stylesheets: array('/styles/resume.css') 
);
?>
<main>
    <section>
        <h2>resume</h2>
        <p>I'm Allison Ramasami, I have degrees in mathematics and computer science/cybersecurity, and I like to code things. My main passions are video games and anything security related, computer or physical. Whether it's coding an application, troubleshooting me and my friends' devices, or reading up on the latest exploit, I can learn just about anything when put to the test.</p>
    </section>
    <section>
        <h3>projects</h3>
        <?php makeResumeList($projects); ?>
    </section>
    <section>
        <h3>work history</h3>
        <?php makeResumeList($workplaces); ?>
    </section>
    <section>
        <h3>knowledge</h3>
        <ul>
            <li>Passed comptia A+ 220-1101 exam, waiting on taking 220-1102</li>
            <li>Familiar with C++, Python, Lua, Javascript, HTML/CSS/PHP, MYSql/MariaDB, x86 assembly</li>
            <li>Briefly worked with R, Matlab, Mathematica, VBS/Excel, bash</li>
            <li>Familiar with git/GitHub for version control</li>
            <li>Able to function in a Linux environment, even without a desktop interface</li>
            <li>Able to install new parts in a PC</li>
        </ul>
    </section>
    <section>
        <h3>education</h3>
        <?php makeResumeList($educations); ?>
    </section>
</main>
<?php makeFooter(); ?>