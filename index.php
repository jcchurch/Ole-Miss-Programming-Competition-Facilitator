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

                // At this point, we need to check to see if user already exists.
		        $query = "INSERT INTO contestants (username, name, language, creationTime, enabled) VALUES ('{$this->username}', '$name', '$language', datetime('now'), 1);";
                    $db = new SQLiteDatabase($competition_db, 0666);
                    $db->queryExec($query, $error);
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
            <option value="C">C</option>
            <option value="C++">C++</option>
            <option value="Java">Java</option>
            <option value="Ruby">Ruby</option>
            <option value="Python">Python</option>
            <option value="Perl">Perl</option>
            <option value="Objective C">Objective C</option>
            <option value="BASIC">BASIC</option>
            <option value="PHP">PHP</option>
            <option value="Haskell">Haskell</option>
            <option value="R">R</option>
            <option value="OCaml">OCaml</option>
            <option value="Scala">Scala</option>
            <option value="Lisp">Lisp</option>
            <option value="Groovy">Groovy</option>
            <option value="TCL">TCL</option>
            <option value="BASH">BASH</option>
            <option value="Matlab">Matlab</option>
            <option value="Mathematica">Mathematica</option>
            <option value="JavaScript">JavaScript</option>
            <option value="HTML5">HTML5</option>
            <option value="Brainfuck">Brainfuck</option>
            <option value="MOO">MOO</option>
            <option value="LOLCAT">LOLCAT</option>
            <option value="Windows BAT">I only program in Windows BAT files.</option>
        </select></p>
    <p><input type="submit"></p>
END;
            }
        }
    }
}

$myPage = new MyPage(array("new contestant", "contestant"));
$myPage->writePage();

?>
