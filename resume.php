<?php
$title = 'resume';
$stylesheets = array();
$scripts = array();
require 'templates/top.php';
?>
<main>
    <section>
        <h2>resume</h2>
        <p>I'm Allison Ramasami, I have degrees in mathematics and computer science/cybersecurity, and I like to code things. My main passions are video games and anything security related, computer or physical. What I may lack in job experience I make up for in adaptability and being able to learn anything on the spot.</p>
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
                            <li>Built from scratch using HTML, CSS, and PHP. Hosted on a GitHub repository, available <a href="https://github.com/hexagonhexagon/hexy-personalwebsite" title="GitHub - hexagonhexagon/hexy-personalwebsite">here</a></li>
                            <li>GitHub Actions automatically pushes repository to VPS hosting</li>
                            <li>DNS registration and configuration done through Namecheap, SSL certs configured via Let's Encrypt/Certbot</li>
                            <li>Mobile-friendly and responsive, meets WCAG AAA contrast requirements, working on screen reader accessibility</li>
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
                            <li>The finished product is available <a href="https://github.com/hexagonhexagon/seniordesign-IDS-18-Fall" title="GitHub - hexagonhexagon/seniordesign-IDS-18-Fall">here</a>, consisting of over 5700 lines of code</li>
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
                            <li>We successfully published our result in the journal Involve, <a href="https://msp.org/involve/2019/12-1/p10.xhtml" title="Involve, Vol. 12 No. 1 - Spectrum of the Kohn Laplacian on the Rossi sphere">here</a>, as well as giving two talks on our research at the <a href="https://www.gvsu.edu/cms4/asset/663818A7-FED7-3DCD-E9207832AB85C378/schedule_book.pdf" title="SUMMR 2017 Schedule">SUMMR</a> and <a href="https://ymc.math.osu.edu/2017/program.php" title="YMC 2017 Schedule">YMC</a> conferences</li>
                            <li>I also composed a separate talk on the subject on my math talks page, <a href="talks.php#reu">here</a></li>
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
            <li>passed comptia A+ 220-1101 exam, waiting on taking 220-1102</li>
            <li>languages: C++, python, lua, javascript, html/css/php, mysql/mariadb, x86 assembly | briefly worked with R, matlab, mathematica, vbs/excel, bash</li>
            <li>familiar with git/github for version control</li>
            <li>able to function in a linux environment, even without a desktop interface</li>
            <li>able to install new parts in a pc</li>
        </ul>
    </section>
    <section>
        <h3>education</h3>
        <ul>
            <li>graduated um dearborn in 2020 with bachelors in mathematics, bachelors in computer science with focus on cybersecurity</li>
        </ul>
    </section>
</main>
<?php require 'templates/bottom.php' ?>