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

        echo "<h3>Contestants</h3>\n";
        echo "<hr>\n";

        $query = "SELECT * FROM contestants;";
        $contestants = $db->query($query);

        foreach ($contestants as $c) {

            $checkEnabled = "";
            $checkDisabled = "";
            if ($c['enabled'] == 1) { $checkEnabled = " CHECKED"; }
            if ($c['enabled'] == 0) { $checkDisabled = " CHECKED"; }

echo <<<END
    <form action="contestants.php">
    <input type="hidden" name="username" value="{$c['username']}">
    <p>Username: {$c['username']}</p>
    <p>Name: <input type="text" name="name" value="{$c['name']}"></p>
    <p>Language: {$c['language']}</p>
    <input type="radio" name="enable" value="1"$checkEnabled> Enabled<br>
    <input type="radio" name="enable" value="0"$checkDisabled> Disable<br>
    <input type="submit" name="update" value="Update">
    <form action="contestants.php">
    <input type="hidden" name="username" value="{$c['username']}">
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
