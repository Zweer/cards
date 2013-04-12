<?php

namespace Zweer\Cards;

/**
 * Class Card
 * @package Zweer\Cards
 *
 * @property int Value
 * @property int Rank
 * @property int Suit
 */
abstract class Card
{
    /**
     * Facilitate the access to the different suits
     */
    const SUIT_HEARTS   = 0;
    const SUIT_DIAMONDS = 1;
    const SUIT_CLUBS    = 2;
    const SUIT_SPADES   = 3;

    /**
     * How many cards are there in a deck?
     */
    const CARD_NUMBER = 0;

    const HAS_JOLLY = false;

    /**
     * @var int
     */
    protected $_value = 0;

    /**
     * @var int
     */
    protected $_rank;

    /**
     * @var int
     */
    protected $_suit;

    /**
     * @var array
     */
    public static $names = array(
        'suits' => array(),
        'ranks' => array(),
    );

    /**
     * @param int $value
     * @param int $rank
     * @param int $suit
     */
    public function __construct($value = null, $rank = null, $suit = null)
    {
        if (!is_null($value)) {
            $this->Value = $value;
        }

        if (!is_null($rank)) {
            $this->Rank = $rank;
        }

        if (!is_null($suit)) {
            $this->Suit = $suit;
        }
    }

    /**
     * @return bool
     */
    public function isJolly()
    {
        return $this->_rank == (static::CARD_NUMBER / 4);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return static::$names['ranks'][$this->_rank] . ' of ' . static::$names['suits'][$this->_suit] . ' (' . $this->_value . ')';
    }

    /**
     * @param string $name
     * @return int
     * @throws \InvalidArgumentException
     */
    public function __get($name)
    {
        switch ($name) {
            case 'Value':
                return $this->_value;
                break;

            case 'Rank':
                return $this->_rank;
                break;

            case 'Suit':
                return $this->_suit;
                break;

            default:
                throw new \InvalidArgumentException('"' . $name . '" is not part of the data');
        }
    }

    /**
     * @param string $name
     * @param int    $value
     * @throws \InvalidArgumentException
     */
    public function __set($name, $value)
    {
        switch ($name) {
            case 'Value':
                $value = intval($value);
                if ($value > (static::CARD_NUMBER + 1) or (!static::HAS_JOLLY and $value < (static::CARD_NUMBER + 1))) {
                    throw new \InvalidArgumentException('The value provided for "value" is too high: ' . $value . ' when the max is ' . (static::HAS_JOLLY ? (static::CARD_NUMBER + 1) : (static::CARD_NUMBER - 1)));
                }
                $this->_value = $value;
                break;

            case 'Rank':
                $value = intval($value);
                $cardsPerSuit = static::CARD_NUMBER / 4;
                if ($value > $cardsPerSuit or (!static::HAS_JOLLY and $value == $cardsPerSuit)) {
                    throw new \InvalidArgumentException('The value provided for "rank" is too high: ' . $value . ' when the max is ' . (static::HAS_JOLLY ? $cardsPerSuit : ($cardsPerSuit - 1)));
                }
                $this->_rank = $value;
                break;

            case 'Suit':
                $value = intval($value);
                if ($value >= 4) {
                    throw new \InvalidArgumentException('The value provided for "suit" is too high: ' . $value . ' when the max is 3');
                }
                $this->_suit = $value;
                break;

            default:
                throw new \InvalidArgumentException('"' . $name . '" is not part of the data');
        }
    }

    /**
     * @param int $value
     * @return Card
     */
    public static function createFromValue($value)
    {
        $cardsPerSuit = static::CARD_NUMBER / 4;

        $card = new static($value);
        $card->Suit = intval($card->Value / $cardsPerSuit);
        $card->Rank = $card->Value % $cardsPerSuit;

        return $card;
    }

    /**
     * @param int $rank
     * @param int $suit
     * @return Card
     */
    public static function createFromRankAndSuit($rank, $suit)
    {
        $cardsPerSuit = static::CARD_NUMBER / 4;

        $card = new static(null, $rank, $suit);

        if ($card->Rank == (static::CARD_NUMBER / 4)) {
            $card->Suit = intval($card->Suit / 2);
        }
        $card->Value = ($card->Suit * $cardsPerSuit) + $card->Rank;

        return $card;
    }
}