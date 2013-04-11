<?php

namespace Zweer\Cards\Deck;

use Zweer\Cards\Card;

class Deck implements \Iterator
{
    protected $_cards = array();
    protected $_position = 0;

    public function shuffle()
    {
        shuffle($this->_cards);
        $this->rewind();
    }

    public function rewind()
    {
        $this->_position = 0;
    }

    public function current()
    {
        return $this->_cards[$this->_position];
    }

    public function key()
    {
        return $this->_position;
    }

    public function next()
    {
        ++$this->_position;
    }

    public function valid()
    {
        return isset($this->_cards[$this->_position]);
    }

    public static function create(Card $card, $shuffle = false)
    {
        $cardClass = get_class($card);
        $cardNumber = constant($cardClass . '::CARD_NUMBER');
        $deck = new static();

        for ($i = 0; $i < $cardNumber; ++$i) {
            $deck[] = $cardClass::createFromValue($i);
        }

        if ($shuffle) {
            $deck->shuffle();
        }

        return $deck;
    }
}