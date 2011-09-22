<?php

class Deck
{
    protected $_cards = array();
    protected $_pointer = 0;
    protected $_cardType = 'french';

    public function __construct($Shuffle = false, $CardType = null, $Cards = null)
    {
        if(isset($CardType))
            $this->_cardType = $CardType;
        $CardName = null;

        switch($this->_cardType)
        {
            case 'french':
                require_once(dirname(__FILE__) . '/french.php');

                $CardName = 'French_Card';
            break;

            default:
                trigger_error($this->_cardType . " is not a valid Card Type", E_USER_ERROR);
                return;
            break;
        }

        $this->_cards = self::createCards($CardName::CARD_NUMBER, $Cards);

        if($Shuffle)
            $this->shuffle();

        foreach($this->_cards as &$Card)
        {
            $Card = new $CardName($Card);
        }
    }

    public function shuffle()
    {
        shuffle($this->_cards);
    }

    public function __toString()
    {
        $S = 'This is a deck of ' . $this->_cardType . " cards:\n\n";

        foreach($this->_cards as $Card)
        {
            $S .= $Card . "\n";
        }

        return $S;
    }

    public function __get($name)
    {
        switch($name)
        {
            case 'Cards':
                return $this->_cards;
            break;

            case 'Pointer':
                return $this->_pointer;
            break;
        }

        trigger_error("'$name' is not part of the data.", E_USER_ERROR);
        return false;
    }

    public static function createCards($Number, $Cards)
    {
        if(count($Cards) > $Number)
            $Cards = null;

        if(!isset($Cards))
            return range(0, $Number - 1);
        else
        {
            $Ret = array();
            foreach($Cards as $Card)
            {
                $Value = $Card instanceof Card ? $Card->Value : $Card;
                if($Value >= $Number)
                    continue;
                
                $Ret[] = $Value;
            }

            for($i = 0; $i < $Number; ++$i)
                if(!in_array($i, $Ret))
                    $Ret[] = $i;

            return $Ret;
        }
    }
}