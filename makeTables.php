<?php

die(); // Uncomment this line after executing. :)

$competition_db = "competition.db";

$db = new PDO("sqlite:$competition_db");

$db->exec('CREATE TABLE submissions (contestant TEXT, problem TEXT, submitTime TEXT, status INTEGER, judge TEXT, judgeTime TEXT, path TEXT);');
$db->exec('CREATE TABLE contestants (username TEXT PRIMARY KEY, name TEXT, language TEXT, creationTime TEXT, enabled INTEGER, startTime INTEGER);');
$db->exec('CREATE TABLE users (username TEXT PRIMARY KEY, loggedIn INTEGER, ipaddress TEXT);');

echo("Thank you! Remember to uncomment out the 'die' command.\n\n");

?>
