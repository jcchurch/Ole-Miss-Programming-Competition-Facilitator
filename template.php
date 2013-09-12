<?php

require_once('lib.php');
require_once("/var/www/includes/adLDAP.php");

class Page {

    // This variable must be array of strings
    function __construct($pagetype)  {
        $this->titlepage = "Ole Miss Programming Competition";
        $this->pagetype = $pagetype;
        $this->username = "";
        $this->usertype = "new contestant";
    }

    // This function returns two variables.
    // The first variable indicates if we should display the login dialog box.
    // The second variable indicates if there was a problem with the authentication. (true means problem, false means no problem)
    function login() {

        $user = getLoggedInUser();
        if ($user !== false) {
            // Great! We return the username.
            $this->username = $user;
            $this->usertype = getUserType($this->username);
            return array(false, false);
        }

        // If this fails, we grab the user and pass $_REQUEST variables
        // and check them.
        $user = ifsetor($_REQUEST['user'], "");
        $pass = ifsetor($_REQUEST['pass'], "");

        if ($user == "" || $pass == "") {
            return array(true, false);
        }

        $adldap = new adLDAP();
        $auth = $adldap->authenticate($user, $pass);

        if ($auth) {
            $this->username = $user;
            $this->usertype = getUserType($this->username);
            setLoginOfUser($user, 1);
            return array(false, false);
        }

        return array(true, true);
    }

    function setTitle($title) {
        $this->titlepage = $title;
    }

    function showHeader() {
        global $end_time;
        $minutes_remaining = (int)((strtotime($end_time) - time()) / 60);
        if ($minutes_remaining < 0) {
            $minutes_remaining = 0;
        }
echo <<<END
<div id="header">
    <img src="images/olemiss.png" width="410" height="136">

END;

if ($this->usertype == "judge")
echo <<<END
    <div id="navcontainer">
        <ul id="navlist">
            <li><a href="standings.php">Standings</a></li>
            <li><a href="judge.php">Submissions</a></li>
            <li><a href="contestants.php">Contestants</a></li>
            <li><a href="logout.php">Logout</a></li>
            <li><a href="#">$minutes_remaining minutes remaining</a></li>
        </ul>
    </div> <!-- navcontainer -->

END;

if ($this->usertype == "contestant")
echo <<<END
    <div id="navcontainer">
        <ul id="navlist">
            <li><a href="standings.php">Standings</a></li>
            <li><a href="submit.php">Submit a program</a></li>
            <li><a href="myresults.php">My Results</a></li>
            <li><a href="logout.php">Logout</a></li>
            <li><a href="#">$minutes_remaining minutes remaining</a></li>
        </ul>
    </div> <!-- navcontainer -->

END;

echo "</div> <!-- header -->\n";
    }

    function showTitle() {
echo <<<END
<head>
    <title>{$this->titlepage}</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>

END;
    }

    function showFooter() {
echo <<<END

<div id="footer">
<hr>
<p>The University of Mississippi Department of Computer and Information Science</p>
<p>The code for this project is on <a href="https://github.com/jcchurch/Ole-Miss-Programming-Competition-Facilitator">GitHub</a>.</p>
</div> <!-- footer -->

END;
    }

    function showLogin($loginError) {
        echo "    <div id=\"loginbox\">\n";
        if ($loginError)
            echo "    <span class=\"error\">Login failed. Check your username and password and try again.</span><br/>\n";
        else
            echo "    <span class=\"formlabel\">Welcome!</span><br/>\n";

echo <<<END
    <form method="post" name="loginForm" action="index.php">
        <p><span class="formlabel">Username:</span></p>
        <p><input type="text" name="user" size="15"></p>
        <p><span class="formlabel">Password:</span></p>
        <p><input type="password" name="pass" size="15"></p>
        <p><input type="submit" value="Login"></p>
    </form>
    </div> <!-- loginbox -->

END;
    }

    function main() { }

    function writePage() {
        list($displayLogin, $loginError) = $this->login();

        echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.1//EN\" \"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd\">\n";
        echo "<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"en\">\n";
        $this->showTitle();
        echo "<div id=\"wrapper\">\n";
        $this->showHeader();
        echo "<div id=\"main\">\n";
        if ($displayLogin) {
            $this->showLogin($loginError);
        }
        else {
            if (in_array($this->usertype, $this->pagetype)) {
                $this->main();
            }
            else {
                echo "<p>Not the correct usertype to see page.</p>\n";
            }
        }
        echo "</div> <!-- wrapper -->\n";
        $this->showFooter();
        echo "</div> <!-- main -->\n";

        echo "</body>\n";
        echo "</html>\n";
    }
}

?>
