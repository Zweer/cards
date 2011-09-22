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

    public function __construct($Value)
    {
        $Value = $Value % static::CARD_NUMBER;
        $CardsPerSuit = static::CARD_NUMBER / 4;

        $this->_value = $Value;
        $this->_suit = (int) $this->_value / $CardsPerSuit;
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
        }

        trigger_error("'$name' is not part of the data.", E_USER_ERROR);
        return false;
    }
}