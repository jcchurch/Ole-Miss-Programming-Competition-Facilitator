<?

require_once('template.php');

function compare_submittime($a, $b) {
    return (int)$a['submitTime'] < (int)$b['submitTime'];
}

class MyPage extends Page {
    function main() {
        global $competition_db;
        $db = new SQLiteDatabase($competition_db, 0666);

        if (isset($_REQUEST['newstatus'])) {
            $rowid = $_REQUEST['id'];

            global $start_time;
            $minutes_since_start = (int)((time() - strtotime($start_time)) / 60);

            $query = "UPDATE submissions SET status={$_REQUEST['newstatus']}, judge='{$this->username}', judgeTime=$minutes_since_start WHERE rowid=$rowid;";
            $db->queryExec($query);
        }

        echo "<h3>Submission Judging Page</h3>\n";
        echo "<hr>\n";

        $query = "SELECT rowid, contestant, problem, submitTime, status FROM submissions;";
        $submissions = $db->arrayQuery($query, SQLITE_ASSOC);

        usort($submissions, 'compare_submittime');

        echo "<table border='1'>\n";
        echo "  <tr>\n";
        echo "     <td>Contestant</td>\n";
        echo "     <td>Problem</td>\n";
        echo "     <td>Submission Time</td>\n";
        echo "     <td>Status </td>\n";
        echo "  </tr>\n";

        foreach ($submissions as $s) {
            echo "  <tr>\n";
            echo "     <td>{$s['contestant']}</td>\n";
            echo "     <td>{$s['problem']}</td>\n";
            echo "     <td>{$s['submitTime']}</td>\n";
            echo "     <td>\n";
            echo "     <form action=\"judge.php\">\n";
            echo "     <input type=\"hidden\" name=\"id\" value=\"{$s['rowid']}\">\n";
            echo "     <select name=\"newstatus\">\n";

            for ($i = -1; $i <= 5; $i++) {
                $selected = "";
                if ($s['status'] == $i) {
                    $selected = " SELECTED";
                }

                $label = "Needs Judging";
                if ($i == 0) { $label = "Correct Answer"; }
                if ($i == 1) { $label = "Syntax Error"; }
                if ($i == 2) { $label = "Run Time Error"; }
                if ($i == 3) { $label = "Wrong Answer"; }
                if ($i == 4) { $label = "Presentation Error"; }
                if ($i == 5) { $label = "Time Limit Exceeded"; }

                echo "     <option value=\"$i\"$selected>$label</option>";
            }

            echo "     </select>\n";
            echo "     <input type=\"submit\"></form>\n";
            echo "     </td>\n";
            echo "  </tr>\n";
        }

        echo "</table>\n";

    }
}

$myPage = new MyPage(array("judge"));
$myPage->writePage();

?>
