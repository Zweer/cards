<?php

namespace Zweer\Cards\Deck;

use Zweer\Cards\Card\Card;

class Deck implements \Iterator
{
    /**
     * @var Card[]
     */
    protected $_cards = array();
    /**
     * @var int
     */
    protected $_position = 0;

    /**
     * @return Deck
     */
    public function shuffle()
    {
        shuffle($this->_cards);
        $this->rewind();

        return $this;
    }

    /**
     * @return Card
     */
    public function giveCard()
    {
        return $this->_cards[$this->_position++];
    }

    /**
     * @param int $howMany
     * @return Card[]
     */
    public function giveCards($howMany = 1)
    {
        $ret = array();
        for ($i = 0; $i < $howMany; ++$i) {
            $ret[] = $this->giveCard();
        }

        return $ret;
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

    /**
     * @param Card $card
     * @param bool $shuffle
     * @return Deck
     */
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

    /**
     * @param Card[] $cards
     * @param bool $shuffle
     * @throws \InvalidArgumentException
     * @return Deck
     */
    public static function createFromCards(array $cards, $shuffle = false)
    {
        $deck = new static();

        foreach($cards as $card) {
            if (!$card instanceof Card) {
                throw new \InvalidArgumentException('The cards provided aren\'t instances of Card: ' . var_dump($card));
            }
            $deck[] = $card;
        }

        if ($shuffle) {
            $deck->shuffle();
        }

        return $deck;
    }
}