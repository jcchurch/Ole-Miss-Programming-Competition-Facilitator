<?php

require_once('template.php');

class MyPage extends Page {
    function main() {
        global $competition_db;
        $db = new PDO("sqlite:$competition_db");

        if (isset($_REQUEST['update'])) {
            $username = trim($_REQUEST['username']);
            $name = trim($_REQUEST['name']);
            $enable = trim($_REQUEST['enable']);

            $name = preg_replace('/javascript\s*:/i', '', $name);
            $name = str_replace("'", "\'", $name);

            $query = "UPDATE contestants SET name='$name', enabled=$enable WHERE username='$username';";
            $db->exec($query);
        }

        if (isset($_REQUEST['deleteme'])) {
            $username = trim($_REQUEST['username']);
            $query = "DELETE FROM contestants WHERE username='$username';";
            $db->exec($query);
            $query = "DELETE FROM submissions WHERE contestant='$username';";
            $db->exec($query);
        }

        echo "<h3>Contestants</h3>\n";
        echo "<hr>\n";

        $query = "SELECT * FROM contestants;";
        $contestants = $db->query($query);

        foreach ($contestants as $c) {
echo <<<END
    <form action="contestants.php">
    <input type="hidden" name="username" value="{$c['username']}">
    <p>Name: <input type="text" name="name" value="{$c['name']}"></p>
    <p>Language: {$c['language']}</p>
    <input type="radio" name="enable" value="1" checked> Enabled<br>
    <input type="radio" name="enable" value="0"> Disable<br>
    <input type="submit" name="update" value="Update">
    <form action="contestants.php">
    <input type="hidden" name="username" value="{$c['username']}">
    <input type="submit" name="deleteme" value="Delete Me!" />
    </form>
    <hr>
END;
        }

        echo "</table>\n";

    }
}

$myPage = new MyPage(array("judge"));
$myPage->writePage();

?>
