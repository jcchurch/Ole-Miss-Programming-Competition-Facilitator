<?php

require_once('template.php');

class MyPage extends Page {
    function main() {
        global $competition_db;
        if ($this->usertype = "new contestant") {
            echo <<<END

<h3>New Contestant Registration</h3>
<hr>

END;
            $showNewContestantForm = true;
            $contestants = getContestants();

            if (isset($contestants[$this->username])) {
                echo "<h3>Head over to <a href=\"standings.php\">standings</a> and get to work.</h3>";
                $showNewContestantForm = false;
            }
            else if (isset($_REQUEST['name']) || isset($_REQUEST['language'])) {
                $name = trim($_REQUEST['name']);
                $language = trim($_REQUEST['language']);

                $name = preg_replace('/javascript\s*:/i', '', $name);
                $language = preg_replace('/javascript\s*:/i', '', $language);

                $name = str_replace("'", "\'", $name);
                $language = str_replace("'", "\'", $language);

                $errors = array();

                if ($name == "") { $errors[]= "The name field is blank."; }
                if ($language == "") { $errors[]= "The language field is blank."; } 

                if (count($errors) > 0) {
                    echo "<p>The following errors must be fixed.</p>\n";
                    echo "<ul>\n";
                    foreach ($errors as $e) {
                        echo "<li>$e</li>\n";
                    }
                    echo "</ul>\n";
                    echo "<hr>\n";
                }
                else {
                    $showNewContestantForm = false;

                    // At this point, we need to create the user
                    $query = "INSERT INTO contestants (username, name, language, creationTime, enabled) VALUES ('{$this->username}', '$name', '$language', datetime('now'), 1);";
                    $db = new PDO("sqlite:$competition_db");
                    $db->exec($query);
                    echo "<h3>Welcome to the competition, $name. Get started by <a href=\"standings.php\">Checking the standings.</a></h3>";
                }
            }

            if ($showNewContestantForm) {
                echo <<<END

<form action="index.php">
    <p>Name (Permission granted to use nicknames or be silly, but not vulgar):</p>
    <p><input type="text" name="name" size="30"></p>
    <p>Prefered Programming Language:</p>
    <p><select name="language">
        <option value="Select one.">Select one.</option>
        <option value="Ritchie">C</option>
        <option value="Stroustrup">C++</option>
        <option value="Gosling">Java</option>
        <option value="Matz">Ruby</option>
        <option value="Rossum3">Python 3</option>
        <option value="Rossum2">Python 2</option>
        <option value="Rossum1">Python 1</option>
        <option value="Rossum0">I was programming Python before Guido.</option>
        <option value="Wall">Perl</option>
        <option value="Backus">Fortran</option>
        <option value="Hopper">Cobol</option>
        <option value="Cox">Objective C</option>
        <option value="Hejlsberg">C#</option>
        <option value="Kemeny">BASIC</option>
        <option value="Gates">QBASIC</option>
        <option value="Lerdorf">PHP</option>
        <option value="Cooper">Visual Basic</option>
        <option value="Jones">Haskell</option>
        <option value="Ihaka">R</option>
        <option value="Leroy">OCaml</option>
        <option value="Ordersky">Scala</option>
        <option value="McCarthy">Lisp</option>
        <option value="Strachan">Groovy</option>
        <option value="Figueiredo">Lua</option>
        <option value="Ousterhout">TCL</option>
        <option value="Fox">BASH</option>
        <option value="Moler">Matlab</option>
        <option value="Wolfram">Mathematica</option>
        <option value="Eich">JavaScript</option>
        <option value="Muller">Brainfuck</option>
        <option value="White">MOO</option>
        <option value="Lindsay">LOLCAT</option>
        <option value="HTML5">HTML5</option>
        <option value="Turing">I built the Turing Machine.</option>
        <option value="Babbage">I built the Differential Engine.</option>
        <option value="XKCD">A magnetized needle and a steady hand.</option>
        <option value="ILIVEIN1991">I only program in Windows BAT files.</option>
       </select></p>
    <p><input type="submit"></p>
</form>

END;
            }
        }
    }
}

$myPage = new MyPage(array("new contestant", "contestant"));
$myPage->writePage();

?>
