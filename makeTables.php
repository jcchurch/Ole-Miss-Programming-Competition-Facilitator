<?

// die(); // Uncomment this line after executing. :)

$db = new SQLiteDatabase('competition.db', 0666);

$db->queryExec('CREATE TABLE submissions (contestant TEXT, problem TEXT, submitTime TEXT, status INTEGER, judge TEXT, judgeTime TEXT);');
$db->queryExec('CREATE TABLE contestants (username TEXT PRIMARY KEY, name TEXT, language TEXT, creationTime TEXT, enabled INTEGER);');

echo "Thank you! Remember to comment out the 'die' command.\n\n"

?>
