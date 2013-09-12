<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL);

require_once('template.php');

class MyPage extends Page {
    function main() {
        global $problems;
        global $penalty_minutes;
        global $competition_db;
        $db = new PDO("sqlite:$competition_db");

        // Get submissions
        $query = "SELECT contestant, problem, submitTime, status FROM submissions;";

        $contestants = getContestants();

        // Begin computing scores.
        $standings = array();

        foreach (array_keys($contestants) as $contestant) {
            $solved = 0;
            $score = 0;
            $this_contestant = array();
            $this_contestant['name'] = $contestants[$contestant];
            $this_contestant['correct'] = 0;
            $this_contestant['score'] = 0;
            foreach (array_keys($problems) as $problem) {
                $penalties = 0;
                $passed_time = -1;
                $this_contestant[$problem] = -1;
                foreach ($db->query($query) as $row) {
                    if ($row['contestant'] == $contestant && $row['problem'] == $problem) {

                        if ($row['status'] > 0) {
                            $penalties++;
                        }
                        if ($row['status'] == 0) {
                            $passed_time = $row['submitTime'];
                        }
                    }
                }
                if ($passed_time >= 0) {
                    $solved++;
                    $this_contestant[$problem] = $passed_time + $penalty_minutes * $penalties;
                    $score += $this_contestant[$problem];
                }
            }
            $this_contestant['correct'] += $solved;
            $this_contestant['score'] += $score;

            // This is a variation on insertion sort.
            if (count($standings) == 0) {
                $standings[]= $this_contestant;
            }
            else {
                $pos = 0;
                while ($pos < count($standings) && $standings[$pos]['correct'] > $solved) {
                    $pos++;
                }

                while ($pos < count($standings) && $standings[$pos]['correct'] == $solved && $standings[$pos]['score'] < $score) {
                    $pos++;
                }

                $first_array = array_splice($standings, 0, $pos);
                $standings = array_merge($first_array, array($this_contestant) , $standings);
            }
        }

echo <<<END
    <h3>Standings</h3>
    <hr>
    <table border="1">
      <tr>
        <td>Rank</td>
        <td>Name</td>
        <td>Correct</td>
        <td>Total Mintues</td>

END;
        foreach (array_keys($problems) as $p) {
            echo "        <td>$p</td>\n";
        }
        echo "      </tr>\n";

        $rank = 1;
        foreach ($standings as $s) {
            echo "      <tr>\n";
            echo "        <td>$rank</td>\n";
            echo "        <td>{$s['name']}</td>\n";
            echo "        <td>{$s['correct']}</td>\n";
            echo "        <td>{$s['score']}</td>\n";
            foreach (array_keys($problems) as $p) {
                if ($s[$p] == -1) {
                    echo "        <td>-</td>\n";
                }
                else {
                    echo "        <td>{$s[$p]}</td>\n";
                }
            }
            echo "      </tr>\n\n";
            $rank++;
        }

        echo "    </table>\n";
    }
}

$myPage = new MyPage(array("contestant", "judge"));
$myPage->writePage();

?>
