<?php

namespace Zweer\Cards;

/**
 * Class Card
 * @package Zweer\Cards
 *
 * @property int value
 * @property int rank
 * @property int suit
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

    /**
     * Is the jolly allowed?
     */
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
            $this->value = $value;
        }

        if (!is_null($rank)) {
            $this->rank = $rank;
        }

        if (!is_null($suit)) {
            $this->suit = $suit;
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
     * @param string $name
     * @return int
     * @throws \InvalidArgumentException
     */
    public function __get($name)
    {
        switch ($name) {
            case 'value':
                return $this->_value;
                break;

            case 'rank':
                return $this->_rank;
                break;

            case 'suit':
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
            case 'value':
                $value = intval($value);
                if ($value > (static::CARD_NUMBER + 1) or (!static::HAS_JOLLY and $value < (static::CARD_NUMBER + 1))) {
                    throw new \InvalidArgumentException('The value provided for "value" is too high: ' . $value . ' when the max is ' . (static::HAS_JOLLY ? (static::CARD_NUMBER + 1) : (static::CARD_NUMBER - 1)));
                }
                $this->_value = $value;
                break;

            case 'rank':
                $value = intval($value);
                $cardsPerSuit = static::CARD_NUMBER / 4;
                if ($value > $cardsPerSuit or (!static::HAS_JOLLY and $value == $cardsPerSuit)) {
                    throw new \InvalidArgumentException('The value provided for "rank" is too high: ' . $value . ' when the max is ' . (static::HAS_JOLLY ? $cardsPerSuit : ($cardsPerSuit - 1)));
                }
                $this->_rank = $value;
                break;

            case 'suit':
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
        $card->suit = intval($card->value / $cardsPerSuit);
        $card->rank = $card->value % $cardsPerSuit;

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

        if ($card->rank == (static::CARD_NUMBER / 4)) {
            $card->suit = intval($card->suit / 2);
        }
        $card->value = ($card->suit * $cardsPerSuit) + $card->rank;

        return $card;
    }
}