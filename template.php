<?

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

    function login() {
        // First, we check for a SESSION variable.
        session_start();
        $this->auth = true; // $auth == true when we tried to authenticate and succeeded or we did not try at all.
        $this->displayLogin = false;

        if (isset($_SESSION['username'])) {
            // Great! We return the username.
            $this->username = $_SESSION['username'];
            return true;
        }

        // If this fails, we grab the user and pass $_REQUEST variables
        // and check them.
        $user = ifsetor($_REQUEST['user'], "");
        $pass = ifsetor($_REQUEST['pass'], "");

        if ($user == "" || $pass == "") {
            $this->displayLogin = true;
            return false;
        }

	$adldap = new adLDAP();
	$auth = $adldap->authenticate($user, $pass);

        if ($this->auth) {
            $this->username = $user;
            $_SESSION['username'] = $user;
            return true;
        }

        return false;
    }

    function setTitle($title) {
        $this->titlepage = $title;
    }

    function showHeader() {
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
                    </ul>
                </div>

END;

if ($this->usertype == "contestant")
echo <<<END
                <div id="navcontainer">
                    <ul id="navlist">
                        <li><a href="standings.php">Standings</a></li>
                        <li><a href="submit.php">Submit a program</a></li>
                        <li><a href="myresults.php">My Results</a></li>
                        <li><a href="logout.php">Logout</a></li>
                    </ul>
                </div>

END;

echo "            </div>\n";
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
    </div>

END;
    }

    function showLogin() {
        echo "<div id=\"loginbox\">\n";
        if ($this->auth)
            echo "<span class=\"formlabel\">Welcome!</span><br/>\n";
        else
            echo "<span class=\"error\">Login failed. Check your username and password and try again.</span><br/>\n";

echo <<<END
    <form method="post" name="loginForm" action="index.php">
        <p><span class="formlabel">Username:</span></p>
        <p><input type="text" name="user" size="15"></p>
        <p><span class="formlabel">Password:</span></p>
        <p><input type="password" name="pass" size="15"></p>
        <p><input type="submit" value="Login"></p>
    </form>
    </div>
END;
    }

    function main() { }

    function writePage() {
        $success = $this->login();
        if ($success) {
            $this->usertype = getUserType($this->username);
        }

        echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.1//EN\" \"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd\">\n";
        echo "<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"en\">\n";
        $this->showTitle();
        echo "    <div id=\"wrapper\">\n";
        $this->showHeader();
        echo "        <div id=\"main\">\n";
        if ($this->displayLogin) {
            $this->showLogin();
        }
        else {
            if (in_array($this->usertype, $this->pagetype)) {
                $this->main();
            }
            else {
                echo "<p>Not the correct usertype to see page.</p>\n";
            }
        }
        echo "        </div>\n";
        $this->showFooter();
        echo "    </div>\n";

        echo "    </body>\n";
        echo "</html>\n";
    }
}

?>
