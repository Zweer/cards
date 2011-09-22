<?php

header("Content-type: text/plain");

require_once('../Source/deck.php');
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
$deck = new Deck(true);
echo $deck;
/*
?>

</body>
</html>
*/