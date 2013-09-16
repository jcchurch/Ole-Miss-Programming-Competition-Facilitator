<?php

die(); // Uncomment this line after executing. :)

$competition_db = "competition.db";

$db = new PDO("sqlite:$competition_db");

$db->exec('DROP TABLE users;');
$db->exec('DROP TABLE submissions;');
$db->exec('DROP TABLE contestants;');

echo "Thank you! Remember to uncomment out the 'die' command.\n\n"

?>
