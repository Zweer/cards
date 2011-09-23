<?php

require_once(dirname(__FILE__) . '/deck.php');

abstract class Play
{
    protected $_deck = null;
    protected $_players = array();

    abstract public static function resultToString($Result);
    abstract public static function resultToFloat($Result);

    public function __construct($Players = array('Alice', 'Bob', 'Charlie', 'Dwain'))
    {
        if(!isset($this->_deck))
            $this->_deck = new Deck(true, 'french');

        $this->_initPlayers($Players);
    }

    protected function _initPlayers($Players)
    {
        foreach($Players as $Player)
        {
            if(is_string($Player))
                $this->_players[] = array('name' => $Player, 'hand' => array(), 'result' => 0);
        }
    }

    abstract public function giveCards();
    abstract public function evaluate();

    public function printResult()
    {
        usort($this->_players, 'static::cmp');

        $S = 'The winner is: ' . $this->_players[0]['name'] . "\n\n";
        foreach($this->_players as $Player)
        {
            $S .= $Player['name'] . ": " . static::resultToString($Player['result']) . " (Score: " . static::resultToFloat($Player['result']) . ")\n";
        }

        echo $S;
    }

    public static function cmp($PlayerA, $PlayerB)
    {
        $ScoreA = static::resultToFloat($PlayerA['result']);
        $ScoreB = static::resultToFloat($PlayerB['result']);

        return $ScoreA == $ScoreB ? 0 : ($ScoreA > $ScoreB ? -1 : 1);
    }

    public function __get($name)
    {
        switch($name)
        {
            case 'Deck':
                return $this->_deck;
            break;

            case 'Players':
                return $this->_players;
            break;
        }

        trigger_error("'$name' is not part of the data.", E_USER_ERROR);
        return false;
    }

    public function __set($name, $value)
    {
        switch($name)
        {
            case 'Deck':
                $this->_deck = $value;
            break;

            case 'Players':
                $this->_players = $value;
            break;

            default:
                trigger_error("'$name' is not part of the data.", E_USER_ERROR);
            break;
        }
    }
}