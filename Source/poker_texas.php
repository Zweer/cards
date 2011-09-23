<?php

require_once(dirname(__FILE__) . '/poker.php');

class Poker_Texas extends Poker
{
    protected $_communityCards = array();
    protected $_trashCards = array();

    public static $resultStrenght = array(1 => 'one pair',
                                               'two pair',
                                               'three of a kind',
                                               'straight',
                                               'flush',
                                               'full house',
                                               'four of a kind',
                                               'straight flush',
                                               'royal flush');
    
    public function giveCards()
    {
        foreach($this->_players as &$Player)
            $Player['hand'] = $this->_deck->giveCards(2);

        $this->_trashCard();
        $this->_communityCards = $this->_deck->giveCards(3);
        $this->_trashCard();
        $this->_communityCards = array_merge($this->_communityCards, $this->_deck->giveCards());
        $this->_trashCard();
        $this->_communityCards = array_merge($this->_communityCards, $this->_deck->giveCards());
    }

    protected function _trashCard()
    {
        $this->_trashCards[] = $this->_deck->giveCard();
    }

    public function evaluate()
    {
        foreach($this->_players as &$Player)
            $Player['result'] = self::evaluateHand(array_merge($Player['hand'], $this->_communityCards));
    }

    public function __toString()
    {
        $S  = "This is a poker Texas Hold'em play.\n\n";
        $S .= "Community cards:\n";
        foreach($this->_communityCards as $Card)
            $S .= $Card . "\n";

        $S .= "\nTrash Cards:\n";
        foreach($this->_trashCards as $Card)
            $S .= $Card . "\n";

        $S .= "\nPlayers' Cards:\n";
        foreach($this->_players as $Player)
        {
            $S .= "\n" . $Player['name'] . "'s cards:\n";
            foreach($Player['hand'] as $Card)
                $S .= $Card . "\n";
        }

        return $S;
    }

    public function __get($name)
    {
        switch($name)
        {
            case 'CommunityCards':
                return $this->_communityCards;
            break;

            case 'TrashCards':
                return $this->_trashCards;
            break;
        }

        return parent::__get($name);
    }

    public function __set($name, $value)
    {
        switch($name)
        {
            case 'CommunityCards':
                $this->_communityCards = $value;
            break;

            case 'TrashCards':
                $this->_trashCards = $value;
            break;

            default:
                parent::__set($name, $value);
            break;
        }
    }
}