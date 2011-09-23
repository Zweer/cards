<?php

header("Content-type: text/plain");

require_once('../Source/poker_texas.php');
require_once('../Source/french.php');
/*
?>
<!DOCTYPE html>
<html>
<head>
    <title>PHP Poker :: Examples</title>
</head>
<body>

<?php
*/

$poker = new Poker_Texas();
$poker->giveCards();
$poker->evaluate();
$poker->printResult();
die($poker);

/*
?>

</body>
</html>
*/