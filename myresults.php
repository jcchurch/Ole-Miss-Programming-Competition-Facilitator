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

        $db = new PDO("sqlite:$competition_db");
        $query = "SELECT problem, submitTime, status FROM submissions WHERE contestant='{$this->username}';";
        $submissions = array();
        usort($submissions, 'compare_submittime');

        $correct = 0;
        // We use this loop to rewrite the status codes to English and to count correct problems.
        foreach ($db->query($query) as $sub) {
            $thisSubmission = array();
            $thisSubmission['problem'] = $sub['problem'];
            $thisSubmission['submitTime'] = $sub['submitTime'];

            if ($sub['status'] == -1) {
                $thisSubmission['status'] = "Please wait for the judges to respond.";
            }

            else if ($sub['status'] == 0) {
                $correct += 1;
                $thisSubmission['status'] = "Correct Answer";
            }

            else if ($sub['status'] == 1) {
                $thisSubmission['status'] = "Syntax error";
            }

            else if ($sub['status'] == 2) {
                $thisSubmission['status'] = "Run Time Error";
            }

            else if ($sub['status'] == 3) {
                $thisSubmission['status'] = "Wrong Answer";
            }

            else if ($sub['status'] == 4) {
                $thisSubmission['status'] = "Presentation error";
            }

            else if ($sub['status'] == 5) {
                $thisSubmission['status'] = "Time Limit Exceeded";
            }

            $submissions[]= $thisSubmission;
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
