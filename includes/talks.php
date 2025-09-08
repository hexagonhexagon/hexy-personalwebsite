<?php

/**
 * Make a formatted list for talks page containing name, link to file, and description of each talk.
 * 
 * @param array $talks array of talks containing name, filename, and description of each talk
 */
function makeTalkList(array $talks) { ?>
    <ul class="talks-list">
    <?php foreach ($talks as $talk): ?>
        <li id="<?= $talk['filename']; ?>">
            <div class="header-container">
                <img src="talks/talkicons/<?= $talk['filename']; ?>.png" 
                    alt="Picture of <?= $talk['name']; ?>">
                <a href="talks/<?= $talk['filename']; ?>.pdf" 
                    title="Link to <?= $talk['name']; ?> PDF"
                >
                    <h3><?= $talk['name']; ?></h3>
                </a>
            </div>
            <details>
                <summary>Description</summary>
                <?= $talk['description']; ?>
            </details>
        </li>
    <?php endforeach; ?>
    </ul>
<?php }

// talks data, unworthy of database
$presentations = [
    [
        "name"=>"My Undergrad Research Project",
        "filename"=>"reu",
        "description"=><<<END
            <p>During my time at college, I was able to get into an undergraduate research program at my university, and we were able to study a topic covering differential geometry in several complex variables, specifically a CR-manifold called the Rossi Sphere, whose tangent bundle renders it unable to be embedded into C<sup>n</sup> for any n. While this was already a proved result, our team of 3 of us plus the professor approached it from a new angle by doing spectral analysis of an operator called the Kohn Laplacian, &squ;<sub>b</sub>, yielding a new proof of an old result. This is incredibly complicated, given that this is the result of a published paper in mathematics, but I tried my best to give some insight into the process, even if the math flies over your head.</p>
        END
    ],
    [
        "name"=>"An Introduction to Galois Theory",
        "filename"=>"galoistheory",
        "description"=><<<END
            <p>I was interested in doing an independent study in math while I was at college, and I was able to get one of my friends as well as myself studying Galois Theory, since I had heard about it and all the cool results you got out of it but didn't know anything about how it worked. Galois theory is about a connection between fields, which are a mathematical object that describes things like the real numbers and complex numbers, and groups, which describe symmetries of objects. Some of its more famous applications are proving the types of constructible polygons (regular polygons drawable with compass and straightedge] and proving that there is no formula for the roots of polynomials greater than degree 5. The presentation is pretty dry and complex, given that it covers literally everything we learned in the study and it's pretty advanced math, but I really love the applications.</p>
        END
    ],
    [
        "name"=>"Big Numbers",
        "filename"=>"bignumbers",
        "description"=><<<END
            <p>As part of the math club at my school, I always wanted to do a topic on really big numbers, as that's always something that's really fascinated me. There was an ancient thread on the old xkcd forums that I can no longer find where people tried to name the biggest number they could, and since it was filled with a bunch of mathematicians you got some pretty wild and interesting answers out of it. This presentation covers some of the most famous large numbers in mathematics, as well as some lesser known ones as well. It might get a bit complicated at times since I love to get into the nitty-gritty details of this stuff, but the average person should be able to understand a bunch of this.</p>
        END
    ],
    [
        "name"=>"The Axiom of Choice",
        "filename"=>"axiomofchoice",
        "description"=><<<END
            <p>The Axiom of Choice is a famously controversial statement in mathematics that is secretly used everywhere and has surprisingly unusual implications. An axiom is a fundamental assumption in mathematics that we take unquestioned, since you have to start somewhere in mathemtics lest you get lost in an infinite descent. Despite mathematics seeming like a subject of absolute knowledge and truth, when you start drilling down to the fundamental tenets of mathematics everything gets way shakier and very subjective. The Axiom of Choice is famously independent of the usual Zermelo-Fraenkel set theory axioms, meaning you can assume it to be either true or false without creating any contradictions. This talk covers some of the more unusual consequences of this axiom, for better or for worse. This talk is definitely intended for someone who already knows quite a bit of undergraduate mathematics, but feel free to take a look at it anyways.</p>
        END
    ],
    [
        "name"=>"Homotopy Type Theory (unfinished)",
        "filename"=>"hott",
        "description"=><<<END
            <p>Some time ago one of my friends introduced me to a study group on Homotopy Type theory, which among other things comprise an alternative set of axioms to the usual ZF setup, and might have applications to automated theorem proving. It is actually extremely complicated higher math, probably on a grad to post-grad level but that didn't stop me from trying to learn about it. Unfortunately the group disbanded before we could really finish the study, and this presentation I was putting together was left unfinished. I may try to take a crack at this on my own some point down the line, but that time is not now. I figure I'd put it up here anyways, just for my own personal perusing.</p>
        END
    ]
];

$papers = [
    [
        "name"=>"A Problem About Hats",
        "filename"=>"v1i1",
        "description"=><<<END
            <p>I wanted to start a math newspaper for the college I went to for math club, and was able to put together 1 issue on what I thought was a really interesting hat problem. Sadly, I was not able to produce more than 1 issue, but I think this one came out quite good. Anyone should be able to follow the logic on this one, math experience or not.</p>
        END
    ],
    [
        "name"=>"Topological Data Analysis",
        "filename"=>"topodata",
        "description"=><<<END
            <p>For my topology course, we had to write a paper on something called <q>persistent homology</q>, which is an application of topology to doing some complex data analysis. One of the problems with traditional linear regression is that if your data fits something other than a line, like for instance a circle, then it becomes very difficult to get any reasonable estimation of whether this is significant or not. Persistent homology solves this problem by looking at the homology of the point cloud, and using that to pick out significant features. Homology is a really complicated way to measure holes in things, like the hole of a circle or the holes in a torus. With this more powerful machinery, you are able to pick out really complicated features that a linear regression would have no hope of detecting, and give them significance. This is hilariously complicated, and it took me a while to understand this even with the semester long course, but it is very interesting.</p>
        END
    ],
];