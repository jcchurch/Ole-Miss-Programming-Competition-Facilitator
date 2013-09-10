<?php

require_once('template.php');

function compare_submittime($a, $b) {
    return (int)$a['submitTime'] < (int)$b['submitTime'];
}

class MyPage extends Page {
    function main() {
        global $competition_db;
        echo "<h3>My Results</h3>\n";
        echo "<hr>\n";

        $db = new SQLiteDatabase($competition_db, 0666);
        $query = "SELECT problem, submitTime, status FROM submissions WHERE contestant='{$this->username}';";
        $submissions = $db->arrayQuery($query, SQLITE_ASSOC);
        usort($submissions, 'compare_submittime');

        $correct = 0;
        // We use this loop to rewrite the status codes to English and to count correct problems.
        for ($i = 0; $i < count($submissions); $i++) {
            if ($submissions[$i]['status'] == -1) {
                $submissions[$i]['status'] = "Submission is currently being judged.";
            }

            else if ($submissions[$i]['status'] == 0) {
                $correct += 1;
                $submissions[$i]['status'] = "Correct Answer";
            }

            else if ($submissions[$i]['status'] == 1) {
                $submissions[$i]['status'] = "Syntax error";
            }

            else if ($submissions[$i]['status'] == 2) {
                $submissions[$i]['status'] = "Run Time Error";
            }

            else if ($submissions[$i]['status'] == 3) {
                $submissions[$i]['status'] = "Wrong Answer";
            }

            else if ($submissions[$i]['status'] == 4) {
                $submissions[$i]['status'] = "Presentation error";
            }

            else if ($submissions[$i]['status'] == 5) {
                $submissions[$i]['status'] = "Time Limit Exceeded";
            }
        }

        $message = "";
        if ($correct == 1) { $message = "You got one! You aren't done yet. There's bound to be a second easy one."; }
        if ($correct == 2) { $message = "That second problem is always a little trickier. Sorry about that. Half way to solving them all."; }
        if ($correct == 3) { $message = "One more to go, baby. Clock is ticking."; }
        if ($correct == 4) { $message = "Check the standings. You are done."; }

        echo "<h4>Correct Problems: $correct . $message</h4>\n";

        echo "<p>Your submissions are listed by the most recent submission first.</p>\n";

        echo "<table border='1'>\n";
        echo "  <tr>\n";
        echo "     <td>Problem</td>\n";
        echo "     <td>Submission Time</td>\n";
        echo "     <td>Status </td>\n";
        echo "  </tr>\n";

        foreach ($submissions as $s) {
            echo "  <tr>\n";
            echo "     <td>{$s['problem']}</td>\n";
            echo "     <td>{$s['submitTime']}</td>\n";
            echo "     <td>{$s['status']}</td>\n";
            echo "  </tr>\n";
        }

        echo "</table>\n";

    }
}

$myPage = new MyPage(array("contestant"));
$myPage->writePage();

?>
