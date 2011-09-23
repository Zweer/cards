<?php

header("Content-type: text/plain");

require_once('../Source/poker_texas.php');
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
echo "\n" . $poker;

/*
?>

</body>
</html>
*/