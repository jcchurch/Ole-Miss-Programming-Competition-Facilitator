<?

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

function getContestants() {
    $db = new SQLiteDatabase('competition.db', 0666);
    $query = "SELECT username, name FROM contestants WHERE enabled=1";
    $names_result = $db->arrayQuery($query, SQLITE_ASSOC);
    $names = array();
    foreach ($names_result as $n) {
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
