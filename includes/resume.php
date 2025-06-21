<?php

/**
 * Make a formatted list for resume page containing name, duration, and description of various achievements.
 * 
 * @param array $achievements an array containing the name, start time, end time, and a description of each achievement
 */
function makeResumeList(array $achievements) { ?>
    <ul class="resume-list"> 
    <?php foreach ($achievements as $achievement): ?>
        <li>
            <hgroup>
                <h4><?= $achievement['name']; ?></h4>
                <p><?= $achievement['start'] ?> &ndash; <?= $achievement['end'] ?></p>
            </hgroup>
            <?= $achievement['description'] ?>
        </li> 
    <?php endforeach; ?>
    </ul>
<?php }

// resume data, unworthy of database
$projects = [
    [
        'name'=>'this website! (hex-lab.net)',
        'start'=>'April 2024',
        'end'=>'Present',
        'description'=><<<END
            <ul>
                <li>Built from scratch using Apache, HTML, CSS, PHP, SQLite, JS. Hosted on a <a href="https://github.com/hexagonhexagon/hexy-personalwebsite" title="GitHub - hexagonhexagon/hexy-personalwebsite">public GitHub repository</a>.</li>
                <li>Configured CI/CD by using GitHub Actions to automatically push repository to VPS hosting, ensuring the website is always up to date.</li>
                <li>Registered and configured DNS through Namecheap, enabled HTTPS through Let's Encrypt/Certbot allowing secure access of the site from anywhere.</li>
                <li>Made site mobile-friendly, responsive, and accessible through CSS media queries, using WebAIM's WAVE accessibility tester and DevTools accessibility tree, and doing research on accessibility standards.</li>
            </ul>
        END
    ],
    [
        'name'=>'shapez.io contributions',
        'start'=>'May 2020',
        'end'=>'July 2020',
        'description'=><<<END
            <ul>
                <li><a href="https://store.steampowered.com/app/1318690/shapez/" title="Steam - shapez">shapez.io</a> is an open-source factory-building videogame written in Node.js, with over $1 million in sales.</li>
                <li>Contributed over <a href="https://github.com/tobspr-games/shapez.io/commits?author=hexagonhexagon" title="Github - commits from hexagonhexagon">300 lines of code</a> to the final game by building it from scratch off the public GitHub repository, learning the technologies involved, and making pull requests for open issues and personal desires.</li>
                <li>Credited by name in version 1.1.18 of the game.</li>
            </ul>
        END
    ],
    [
        'name'=>'factorio modding',
        'start'=>'August 2018',
        'end'=>'October 2019',
        'description'=><<<END
            <ul>
                <li><a href="https://factorio.com/" title="Factorio Official Website">Factorio</a> is a factory-building videogame with over 3.5 million sales, with a rich <a href="https://lua-api.factorio.com/latest/" title="Factorio Modding API Docs">modding API</a> using Lua</li>
                <li>Started writing mods with the Lua modding API and Inkscape, with no tutorials at the time and only other mods to go off of</li>
                <li>Published <a href="https://mods.factorio.com/user/hexyhexagon" title="Factorio Mods - hexyhexagon">17 mods</a> accumulating 41,238 downloads before they became incompatible with game updates</li>
            </ul>
        END
    ],
    [
        'name'=>'in-vehicle intrusion detection system',
        'start'=>'September 2018',
        'end'=>'April 2019',
        'description'=><<<END
            <ul>
                <li>Worked in a group of 4 during college to refine an existing Intrusion Detection System model designed to detect malicious frames on the <a href="https://www.csselectronics.com/pages/can-bus-simple-intro-tutorial" title="CSS Electronics - CAN Bus explained">CAN bus</a> of a car</li>
                <li>The model had 2 stages &mdash; a set of fixed rules to detect obvious injections/deletions, and a neural network stage using tensorflow and Python 3</li>
                <li>Wrapped the existing model in a Qt5 GUI using QML and Python 3, along with adding unit tests</li>
                <li>Made the <a href="https://github.com/hexagonhexagon/seniordesign-IDS-18-Fall" title="GitHub - hexagonhexagon/seniordesign-IDS-18-Fall">finished product</a> publically available, consisting of over 5700 lines of code</li>
            </ul>
        END
    ],
    [
        'name'=>'undergrad research project in mathematics',
        'start'=>'May 2017',
        'end'=>'August 2017',
        'description'=><<<END
            <ul>
                <li>Worked with 3 other students with direction from a professor to do novel research in the field of several complex variables</li>
                <li>Successfully published <a href="https://msp.org/involve/2019/12-1/p10.xhtml" title="Involve, Vol. 12 No. 1 - Spectrum of the Kohn Laplacian on the Rossi sphere">our result</a> in the journal Involve, as well as giving two talks on our research at the <a href="https://www.gvsu.edu/cms4/asset/663818A7-FED7-3DCD-E9207832AB85C378/schedule_book.pdf" title="SUMMR 2017 Schedule">SUMMR</a> and <a href="https://ymc.math.osu.edu/2017/program.php" title="YMC 2017 Schedule">YMC</a> conferences</li>
                <li>Composed a separate <a href="/talks.php#reu">talk</a> on the subject on my math talks page</li>
            </ul>
        END
    ],
];

$workplaces = [
    [
        'name'=>'mathematics tutor @ mathnasium',
        'start'=>'January 2023',
        'end'=>'Present',
        'description'=><<<END
            <ul>
                <li>I work with 1st-12th grade + college students, in groups of 2 to 3, for 1 hour sessions</li>
                <li>Learned how to multi-task and keep up with multiple curriculums and novel concepts from students, all while taking detailed notes for each student</li>
            </ul>
        END
    ],
    [
        'name'=>'shipping clerk ii @ xpo logistics',
        'start'=>'July 2021',
        'end'=>'December 2021',
        'description'=><<<END
            <ul>
                <li>I worked at a warehouse that handled shipping out car parts to GM facilities</li>
                <li>Major responsibilities included talking to truck drivers that arrived, processing and generating inbound/outbound paperwork, doing a paper-sorting task known as the <q>carload sort</q>, and managing Excel schedule sheets</li>
                <li>Learned and used VBS and Excel scripting to improve and automate major portions of my job</li>
            </ul>
        END
    ],
];

$educations = [
    [
        'name'=>'npower',
        'start'=>'August 2024',
        'end'=>'December 2024',
        'description'=><<<END
            <ul>
                <li>Currently attending an instructional program focused on earning the CompTIA ITF+, A+, and Google IT Support Professional certificates through hands-on training and projects.</li>
                <li>Created and worked with relational databases, websites, virtual machines, and networks through completing projects, resulting in new content for the website and a better understanding of these topics.</li>
                <li>Fully disassembled and reassembled a laptop through careful examination and trial and error, resulting in a better understanding of hardware disassembly and the tools required for it.</li>
            </ul>
        END

    ],
    [
        'name'=>'university of michigan-dearborn',
        'start'=>'September 2016',
        'end'=>'April 2020',
        'description'=><<<END
            <ul>
                <li>Graduated with a bachelors in mathematics and a bachelors in computer science with focus on cybersecurity.</li>
                <li>Received the Honor Scholar in Mathematics award in 2018, only given to a single student for the entire department.</li>
                <li>Attained a final GPA of 3.87.</li>
            </ul>
        END
    ],
];