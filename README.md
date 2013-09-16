# Ole Miss Programming Competition Facilitator

Hi! Welcome to the Competition Facilitator software. You may use this project to help judge and maintain a programming competition for your school or organization. This competition is modeled after the ACM ICPC competition, but there are a few differences:

- It is assumed that each contestant has a computer. This is different than ACM ICPC in which a team of three contestants has a single computer.
- There is no set start and ending time. Once a contestant registers for the competition, the current time is recorded as the start time and the clock starts ticking based on the $minutes_in_competition variable. Make sure that the contestant has a problem packet!

## What you will probably need to change.

### Authentication

Currently this project uses an LDAP authentication system that is used by the Ole Miss CS Labs. You will need to either change this system to your LDAP system or figure out a way to trust your users and abandon it. I need to update this so that it is not dependent on LDAP.

Users are identified by their current IP address. The IP address of each user is stored in the sqlite3 database. If a malicious user has a way of spoofing the IP address of another user, we run into a security issue.

### Programming Languages

Allowed languages used by this system are C, C++, Java, Python, Ruby, Lua, Scala, and Haskell. If you want fewer or more languages, you will have to edit the "submit.php" file yourself.

### The Judging Script

testMostRescentSubmission.py is the script that I use to judge submissions. You can ignore this script or use it. It requires two things:

- The name of the sqlite3 file.
- The location of your testing directory. The testing directory should be of the following structure:


    /mytestarea
        /ProbA
            /in - Judging Input for Problem A
            /out - Judging Output for Problem A
        /ProbB
            /in
            /out
        /ProbC
            /in
            /out
        /ProbD
            /in
            /out

### The Settings File

"settings.php" contains the variables that go into a local competition. Here is the current settings file.

    <?php

    // Competition File

    $competition_db = "competition.db";

    $judges = array("jcchurch");

    $problems = array(
        "A" => "Cowsay",
        "B" => "Museum Security",
        "C" => "Determining Triangles",
        "D" => "IP to Country"
    );

    $minutes_in_competition = 150;
    $penalty_minutes = 15;

    ?>

- $competition_db is the sqlite3 file.
- $judges is an array of judges. Currently I have one judge (me), but here is where you can expand this.
- $problems is an associative array of problems. Each key in the associative array is an uppercase letter of the alphabet and the value of the key is the name of the problem.
- $minutes_in_competition is the total number of minutes used to complete the competition.
- $penalty_minutes is the number of penalty minutes applied to a successful problem submission for each incorrect submission prior to the correct submission.

## How it works.

- First, make sure the settings.php file is ready for you. The only critical variable is $competition_db. The others can be changed after step 2 is completed.
- Second, run the "makeTable.php" script. This creates the database sqlite3 file.
- Start your competition.

## Running a competition.

Direct your judges and contestants to the "index.php" file so that people can create accounts.

### Judges

Judges can check the standings, view and modify contestants, and view problems that need to be judged.

I have included a Python script called "testMostRescentSubmission.py" that will pull the oldest unjudged problem from the database and attempt to execute it. It moves a submission over to the testing area and executes it with the judging input and then runs "diff -w" with the contestant output and the official judging output. The judge makes the final call on the submission and updates the website accordingly. The contestant can check the results page to see the ruling.

### Contestants

Contestants need to fill out their information, which consists of their name (or nickname) and their choice of programming language. The contestants are not limited to their choice of programming language. They can work on problems, check the standings, or check their own results page to see the ruling from the judge.
