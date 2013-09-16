<?php

require_once('template.php');

class MyPage extends Page {
    function main() {
        global $competition_db;
        global $minutes_in_competition;

        echo "<h3>Submit a problem solution.</h3>\n";
        echo "<hr>\n";
        $showInputDialog = true;

        $minutes_since_start = getMinutesSinceStart($this->username);

        if ($minutes_since_start > $minutes_in_competition) {
            echo "<h4>The competition is now over. Thank you for participating. Check the standings to see how you did.</h4>\n";
            $showInputDialog = false; 
        }
        else {
            $basedirectory = "./submissions";
            $errors = array();

            if (isset($_FILES['uploadedfile']['name'])) {
                $file = basename($_FILES['uploadedfile']['name']);
                preg_match("/^(Prob)([A-Z])\.(c|cpp|java|py|hs|lua|scala|rb)$/", $file, $m);

                if (count($m) == 0) {
                    $errors[] = "Failed to determine which problem you are trying to solve. Check the filename and try again.\n";
                }
                else {
                    $problem = $m[2];

                    // Make the base directory
                    if (is_dir($basedirectory) == false) {
                        mkdir($basedirectory, 0777, true);
                    }

                    $target_path = $basedirectory ."/". $this->username ."_". $problem ."_". strval($minutes_since_start) ."_". $file;
                    if(move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $target_path )) {
                        echo "<h4> Your program '$file' has been uploaded. Please wait for the judge to check it. $minutes_since_start minutes have passed since the start of the compeition.</h4>";
                        $showInputDialog = false; 
                        $db = new PDO("sqlite:$competition_db");
                        $query = "INSERT INTO submissions (contestant, problem, submitTime, status, path) VALUES ('{$this->username}', '$problem', $minutes_since_start, -1, '$target_path');";
                        $db->exec($query);
                    }
                    else{
                        $errors[] = "There was an error uploading the file. Please inform a judge quickly!\n";
                    }
                }
            }

            if ($showInputDialog) {

                if (count($errors) > 0) {
                     echo "<h4>Oops! There were problems submitting your code.</h4>\n\n";
                     echo "<ul>\n";
                     foreach ($errors as $e) {
                         echo "<li>$e</li>\n";
                     }
                     echo "</ul>\n";
                     echo "<hr>\n";
                }

echo <<<END

<p>Ready to submit a problem solution? Find your file and click submit. Please take a moment to make sure that your program fits the following criteria before clicking submit.</p>

<ul>
<li>The program name fits the pattern "Prob{A,B,C,D}.{c,cpp,java,py,hs,lua,scala,rb}". Notice that the file name begins with "Prob" as in "Problem", not "Program".</li>
<li>Your program outputs the exact sample output when provided the sample input.</li>
<li>Your program reads input from standard input (i.e. the keyboard). The output of your program should print to the screen.</li>
<li>Your program does not have any debugging statements.</li>
<li>You are absolutely certain that you are ready to submit the program. Penality minutes will be applied for incorrect programs.</li>
</ul>

<form enctype="multipart/form-data" action="submit.php" method="POST">
<input type="hidden" name="MAX_FILE_SIZE" value="100000" />
Choose a file to upload: <input name="uploadedfile" type="file" /><br />
<input type="submit" value="Upload File" />
</form>
END;
            }
        }
    }
}

$myPage = new MyPage(array("contestant"));
$myPage->writePage();

?>
