<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/root_path_function.php';
require_once serverRootPath('/includes/make_header_footer.php');
require_once serverRootPath('/includes/resume.php');

makeHeader(
    title: 'resume', 
    stylesheets: ['/styles/resume.css'] 
);
?>
<main>
    <section>
        <h2>resume</h2>
        <p>Programmer and troubleshooter with experience in a variety of languages and technologies. Wrote my own website from scratch and am currently maintaining it, Google IT Support and CompTIA ITF+ certified, degree in Cybersecurity and Information Assurance, current trainee at NPower working on IT skills and technologies. Highly motivated self-learner with customer service experience and a keen eye for detail. Currently searching for IT and cybersecurity roles.</p>
    </section>
    <section>
        <h3>certifications</h3>
        <ul>
            <li>Google IT Support: completed August 23, 2024</li>
            <li>CompTIA ITF+: completed September 13, 2024</li>
            <li>CompTIA A+: estimated completion Oct 4, 2024</li>
        </ul>
    </section>
    <section>
        <h3>projects</h3>
        <?php makeResumeList($projects); ?>
    </section>
    <section>
        <h3>education</h3>
        <?php makeResumeList($educations); ?>
    </section>
    <section>
        <h3>work history</h3>
        <?php makeResumeList($workplaces); ?>
    </section>
    <section>
        <h3>knowledge</h3>
        <ul>
            <li>Worked with bash, PowerShell, Active Directory, C, R, Matlab, Mathematica, VBS/Excel</li>
            <li>Able to disassemble, reassemble, and install new parts in a PC or laptop</li>
            <li>Able to troubleshoot common computer issues from friends and family</li>
            <li>Experienced with C++, Python, Lua, Javascript, HTML/CSS/PHP, MySQL/MariaDB/SQLite, x86 assembly</li>
            <li>Familiar with git/GitHub for version control, Visual Studio Code, vim</li>
            <li>Experienced with Ubuntu/Linux Mint</li>
        </ul>
    </section>
</main>
<?php makeFooter(); ?>