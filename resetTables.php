<?php

die(); // Uncomment this line after executing. :)

$competition_db = "competition.db";

$db = new SQLiteDatabase($competition_db, 0666);

$db->queryExec('DELETE FROM submissions;');
$db->queryExec('DELETE FROM contestants;');

echo "Thank you! Remember to comment out the 'die' command.\n\n"

?>
