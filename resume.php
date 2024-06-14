<?php
include $_SERVER['DOCUMENT_ROOT'] . '/templates/setup_variables.php';
$title = 'resume';
$stylesheets = array('/styles/resume.css');
require server_root_path('/templates/top.php');
?>
<main>
    <section>
        <h2>resume</h2>
        <p>I'm Allison Ramasami, I have degrees in mathematics and computer science/cybersecurity, and I like to code things. My main passions are video games and anything security related, computer or physical. Whether it's coding an application, troubleshooting me and my friends' devices, or reading up on the latest exploit, I can learn just about anything when put to the test.</p>
    </section>
    <section>
        <h3>projects</h3>
        <ul class="resume-list">
            <?php
            $projects = array(
                array(
                    'name'=>'this website!',
                    'start'=>'April 2024',
                    'end'=>'Present',
                    'description'=><<<END
                        <ul>
                            <li>Built from scratch using Apache, HTML, CSS, PHP, SQLite, JS. Hosted on a <a href="https://github.com/hexagonhexagon/hexy-personalwebsite" title="GitHub - hexagonhexagon/hexy-personalwebsite">public GitHub repository</a></li>
                            <li>GitHub Actions automatically pushes repository to VPS hosting</li>
                            <li>DNS registration and configuration done through Namecheap, SSL certs configured via Let's Encrypt/Certbot</li>
                            <li>Mobile-friendly and responsive, passes WebAIM's WAVE accessibility tester, working on screen reader accessibility</li>
                        </ul>
                    END
                ),
                array(
                    'name'=>'shapez.io contributions',
                    'start'=>'May 2020',
                    'end'=>'July 2020',
                    'description'=><<<END
                        <ul>
                            <li><a href="https://store.steampowered.com/app/1318690/shapez/" title="Steam - shapez">shapez.io</a> is an open-source factory-building videogame written in Node.js, with over $1 million in sales</li>
                            <li>I built the game from scratch, and started making pull requests to the official repository to fix bugs and add features I wanted</li>
                            <li>Over <a href="https://github.com/tobspr-games/shapez.io/commits?author=hexagonhexagon" title="Github - commits from hexagonhexagon">300 lines of code</a> of mine were merged into the final game from the game creator, and I was credited by name in version 1.1.18</li>
                        </ul>
                    END
                ),
                array(
                    'name'=>'factorio modding',
                    'start'=>'August 2018',
                    'end'=>'October 2019',
                    'description'=><<<END
                        <ul>
                            <li><a href="https://factorio.com/" title="Factorio Official Website">Factorio</a> is a factory-building videogame with over 3.5 million sales, with a rich <a href="https://lua-api.factorio.com/latest/" title="Factorio Modding API Docs">modding API</a> using Lua</li>
                            <li>I started writing my own mods with the Lua modding API and Inkscape, with no tutorials at the time and only other mods to go off of</li>
                            <li>I published <a href="https://mods.factorio.com/user/hexyhexagon" title="Factorio Mods - hexyhexagon">17 mods</a> accumulating 41,238 downloads before they became incompatible with game updates</li>
                        </ul>
                    END
                ),
                array(
                    'name'=>'in-vehicle intrusion detection system',
                    'start'=>'September 2018',
                    'end'=>'April 2019',
                    'description'=><<<END
                        <ul>
                            <li>I worked in a group of 4 during college to refine an existing Intrusion Detection System model designed to detect malicious frames on the <a href="https://www.csselectronics.com/pages/can-bus-simple-intro-tutorial" title="CSS Electronics - CAN Bus explained">CAN bus</a> of a car</li>
                            <li>The model had 2 stages &mdash; a set of fixed rules to detect obvious injections/deletions, and a neural network stage using tensorflow and Python 3</li>
                            <li>We were able to wrap the existing model in a Qt5 GUI using QML and Python 3, along with adding unit tests</li>
                            <li>I made the <a href="https://github.com/hexagonhexagon/seniordesign-IDS-18-Fall" title="GitHub - hexagonhexagon/seniordesign-IDS-18-Fall">finished product</a> publically available , consisting of over 5700 lines of code</li>
                        </ul>
                    END
                ),
                array(
                    'name'=>'undergrad research project in mathematics',
                    'start'=>'May 2017',
                    'end'=>'August 2017',
                    'description'=><<<END
                        <ul>
                            <li>I worked with 3 other students with direction from a professor to do novel research in the field of several complex variables</li>
                            <li>We successfully published <a href="https://msp.org/involve/2019/12-1/p10.xhtml" title="Involve, Vol. 12 No. 1 - Spectrum of the Kohn Laplacian on the Rossi sphere">our result</a> in the journal Involve, as well as giving two talks on our research at the <a href="https://www.gvsu.edu/cms4/asset/663818A7-FED7-3DCD-E9207832AB85C378/schedule_book.pdf" title="SUMMR 2017 Schedule">SUMMR</a> and <a href="https://ymc.math.osu.edu/2017/program.php" title="YMC 2017 Schedule">YMC</a> conferences</li>
                            <li>I also composed a separate <a href="/talks.php#reu">talk</a> on the subject on my math talks page</li>
                        </ul>
                    END
                ),
            );

            foreach ($projects as $project): ?>
            <li>
                <hgroup>
                    <h4><?php echo $project['name']; ?></h4>
                    <p><?php echo $project['start'] ?> &ndash; <?php echo $project['end'] ?></p>
                </hgroup>
                <?php echo $project['description'] ?>
            </li>
            <?php endforeach; ?>
        </ul>
    </section>
    <section>
        <h3>work history</h3>
        <ul class="resume-list">
            <?php
            $workplaces = array(
                array(
                    'name'=>'mathematics tutor @ mathnasium',
                    'start'=>'January 2023',
                    'end'=>'Present',
                    'description'=><<<END
                        <ul>
                            <li>I work with 1st-12th grade + college students, in groups of 2 to 3, for 1 hour sessions</li>
                            <li>Learned how to multi-task and keep up with multiple curriculums and novel concepts from students, all while taking detailed notes for each student</li>
                        </ul>
                    END
                ),
                array(
                    'name'=>'shipping clerk ii @ xpo logistics',
                    'start'=>'July 2021',
                    'end'=>'December 2021',
                    'description'=><<<END
                        <ul>
                            <li>I worked at a warehouse that handled shipping out car parts to GM facilties</li>
                            <li>Major responsibilites included talking to truck drivers that arrived, processing and generating inbound/outbound paperwork, doing a paper-sorting task known as the <q>carload sort</q>, and managing Excel schedule sheets</li>
                            <li>Learned and used VBS and Excel scripting to improve and automate major portions of my job</li>
                        </ul>
                    END
                ),
            );

            foreach ($workplaces as $workplace): ?>
            <li>
                <hgroup>
                    <h4><?php echo $workplace['name']; ?></h4>
                    <p><?php echo $workplace['start'] ?> &ndash; <?php echo $workplace['end'] ?></p>
                </hgroup>
                <?php echo $workplace['description'] ?>
            </li>
            <?php endforeach; ?>
        </ul>
    </section>
    <section>
        <h3>knowledge</h3>
        <ul>
            <li>Passed comptia A+ 220-1101 exam, waiting on taking 220-1102</li>
            <li>Familiar with C++, python, lua, javascript, html/css/php, mysql/mariadb, x86 assembly</li>
            <li>Briefly worked with R, matlab, mathematica, vbs/excel, bash</li>
            <li>Familiar with git/github for version control</li>
            <li>Able to function in a linux environment, even without a desktop interface</li>
            <li>Able to install new parts in a pc</li>
        </ul>
    </section>
    <section>
        <h3>education</h3>
        <ul class="resume-list">
            <li>
                <hgroup>
                    <h4>university of michigan-dearborn</h4>
                    <p>September 2016 &ndash; April 2020</p>
                    <ul>
                        <li>Graduated with bachelors in mathematics, bachelors in computer science with focus on cybersecurity</li>
                        <li>Received Honor Scholar in Mathematics award in 2018, only given to a single student for the entire department</li>
                        <li>Final GPA of 3.87</li>
                    </ul>
                </hgroup>
            </li>
        </ul>
    </section>
</main>
<?php require server_root_path('/templates/bottom.php'); ?>