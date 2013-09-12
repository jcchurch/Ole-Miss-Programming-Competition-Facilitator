<?php

require_once('competition_definition.php');

function ifsetor(&$val, $default = null, $pattern = null) {
    if ($pattern != null) {
        return (isset($val) && preg_match($pattern, $val)) ? $val : $default;
    }

    return isset($val) ? $val : $default;
}

function getJudges() {
    return $judges;
}

function logOutCurrentUser() {
    global $competition_db;
    $db = new PDO("sqlite:$competition_db");
    $ip = $_SERVER['REMOTE_ADDR'];

    $query = "UPDATE users SET loggedIn=0 WHERE ipaddress='$ip'";
    $db->exec($query);
}

function setLoginOfUser($username, $loggedIn=1) {
    global $competition_db;
    $db = new PDO("sqlite:$competition_db");
    $ip = $_SERVER['REMOTE_ADDR'];

    $query = "INSERT OR REPLACE INTO users (username, loggedIn, ipaddress) VALUES ('$username', $loggedIn, '$ip');";
    $db->exec($query);
}

// Returns the username or false
function getLoggedInUser() {
    global $competition_db;
    $db = new PDO("sqlite:$competition_db");
    $ip = $_SERVER['REMOTE_ADDR'];
    $loggedIn = false;

    $query = "SELECT username FROM users WHERE loggedIn=1 AND ipaddress='$ip'";
    foreach ($db->query($query) as $n) {
        $loggedIn = $n['username'];
    }
    return $loggedIn;
}

function getContestants() {
    global $competition_db;
    $db = new PDO("sqlite:$competition_db");

    $query = "SELECT username, name FROM contestants WHERE enabled=1";
    $names = array();
    foreach ($db->query($query) as $n) {
        $names[$n['username']] = $n['name'];
    }
    return $names;
}

function getUserType($username) {
    global $judges;
    $contestants = getContestants();

    if (in_array($username, $judges)) return "judge";
    if (isset($contestants[$username])) return "contestant";
    return "new contestant";
}
