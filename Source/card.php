<?php

abstract class Card
{
    const SUIT_HEARTS = 0;      # Cuori
    const SUIT_DIAMONDS = 1;    # Quadri
    const SUIT_CLUBS = 2;       # Fiori
    const SUIT_SPADES = 3;      # Picche

    const CARD_NUMBER = 0;

    protected $_value = 0;
    protected $_rank = null;
    protected $_suit = null;

    public static $names = array('suits' => array(),
                                 'ranks' => array());

    public function __construct($Value, $Suit = null)
    {
        if(isset($Suit))
            $this->__constructFromRankAndSuit($Value, $Suit);
        else
            $this->__constructFromValue($Value);
    }

    protected function __constructFromRankAndSuit($Rank, $Suit)
    {
        $CardsPerSuit = static::CARD_NUMBER / 4;

        $this->_suit = $Suit % 4;
        $this->_rank = $Rank % $CardsPerSuit;
        $this->_value = ($this->_suit * $CardsPerSuit) + $this->_rank;
    }

    protected function __constructFromValue($Value)
    {
        $CardsPerSuit = static::CARD_NUMBER / 4;

        $this->_value = $Value % static::CARD_NUMBER;
        $this->_suit = intval($this->_value / $CardsPerSuit);
        $this->_rank = $this->_value % $CardsPerSuit;
    }

    public function __toString()
    {
        return static::$names['ranks'][$this->_rank] . ' of ' . static::$names['suits'][$this->_suit] . ' (' . $this->_value . ')';
    }

    public function __get($name)
    {
        switch($name)
        {
            case 'Value':
                return $this->_value;
            break;

            case 'Rank':
                return $this->_rank;
            break;

            case 'Suit':
                return $this->_suit;
            break;
        }

        trigger_error("'$name' is not part of the data.", E_USER_ERROR);
        return false;
    }

    public function __set($name, $value)
    {
        switch($name)
        {
            case 'Value':
                $this->_value = $value;
            break;

            case 'Rank':
                $this->_rank = $value;
            break;

            case 'Suit':
                $this->_suit = $value;
            break;

            default:
               trigger_error("'$name' is not part of the data.", E_USER_ERROR);
            break;
        }
    }
}