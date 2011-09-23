<?php

require_once(dirname(__FILE__) . '/play.php');

abstract class Poker extends Play
{
    public static $strenght = array(12, 0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11);
    public static $resultStrenght = array();

    public static function strenghtToName($Strenght)
    {
        return French_Card::$names['ranks'][array_search($Strenght, static::$strenght)];
    }

    public static function resultToString($Result)
    {
        $S = '';
        reset($Result['cards']);

        switch($Result['score'])
        {
            case 0:
                $S .= 'High Cards:';
            break;

            case 1:
                $S .= 'One Pair of ' . static::strenghtToName(current($Result['cards']));
                next($Result['cards']);
                next($Result['cards']);
            break;

            case 2:
                $S .= 'Two Pairs of ' . static::strenghtToName(current($Result['cards']));
                next($Result['cards']);
                next($Result['cards']);
                $S .= ' and ' . static::strenghtToName(current($Result['cards']));
                next($Result['cards']);
                next($Result['cards']);
            break;

            case 3:
                $S .= 'Three of a Kind: ' . static::strenghtToName(current($Result['cards']));
                next($Result['cards']);
                next($Result['cards']);
                next($Result['cards']);
            break;

            case 4:
                $S .= 'Straight:';
            break;

            case 5:
                $S .= 'Flush:';
            break;

            case 6:
                $S .= 'Full House of ' . static::strenghtToName(current($Result['cards']));
                next($Result['cards']);
                next($Result['cards']);
                next($Result['cards']);
                $S .= ' over ' . static::strenghtToName(current($Result['cards']));
                next($Result['cards']);
                next($Result['cards']);
            break;

            case 7:
                $S .= 'Four of a Kind: ' . static::strenghtToName(current($Result['cards']));
                next($Result['cards']);
                next($Result['cards']);
                next($Result['cards']);
                next($Result['cards']);
            break;

            case 8:
                $S .= 'Straight Flash:';
            break;

            case 9:
                $S .= 'Royal Flush';
                end($Result['cards']);
                next($Result['cards']);
            break;
        }

        if(in_array($Result['score'], array(1, 2, 3, 7)))
        {
            $S .= ' (Kicker';
            if(in_array($Result['score'], array(1, 3)))
                $S .= 's';
            $S .= ':';
        }

        while($Card = each($Result['cards']))
        {
            $Strenght = $Card['value'];
            $S .= ' ' . static::strenghtToName($Strenght);
        }

        if(in_array($Result['score'], array(1, 2, 3, 7)))
        {
            $S .= ')';
        }

        return $S;
    }

    public static function resultToFloat($Result)
    {
        $I = $Result['score'] . '.';
        reset($Result['cards']);

        switch($Result['score'])
        {
            case 1:
                $I .= str_pad(current($Result['cards']), 2, 0, STR_PAD_LEFT);
                next($Result['cards']);
                next($Result['cards']);
            break;

            case 2:
                $I .= str_pad(current($Result['cards']), 2, 0, STR_PAD_LEFT);
                next($Result['cards']);
                next($Result['cards']);
                $I .= str_pad(current($Result['cards']), 2, 0, STR_PAD_LEFT);
                next($Result['cards']);
                next($Result['cards']);
            break;

            case 3:
                $I .= str_pad(current($Result['cards']), 2, 0, STR_PAD_LEFT);
                next($Result['cards']);
                next($Result['cards']);
                next($Result['cards']);
            break;

            case 6:
                $I .= str_pad(current($Result['cards']), 2, 0, STR_PAD_LEFT);
                next($Result['cards']);
                next($Result['cards']);
                next($Result['cards']);
                $I .= str_pad(current($Result['cards']), 2, 0, STR_PAD_LEFT);
                next($Result['cards']);
                next($Result['cards']);
            break;

            case 7:
                $I .= str_pad(current($Result['cards']), 2, 0, STR_PAD_LEFT);
                next($Result['cards']);
                next($Result['cards']);
                next($Result['cards']);
                next($Result['cards']);
            break;

            case 9:
                end($Result['cards']);
                next($Result['cards']);
            break;
        }

        while($Card = each($Result['cards']))
        {
            $Strenght = $Card['value'];
            $I .= str_pad($Strenght, 2, 0, STR_PAD_LEFT);
        }

        return floatval($I);
    }

    public static function evaluateHand($Hand)
    {
        $Ranks = array();
        $Suits = array();
        foreach($Hand as $Card)
        {
            $Ranks[] = self::$strenght[$Card->Rank];
            $Suits[] = $Card->Suit;
        }

        arsort($Ranks);
        $NumRanks = array_count_values($Ranks);

        arsort($Suits);
        $NumSuits = array_count_values($Suits);
        arsort($NumSuits);

        $UsedCards = array();
        $Score = 0;

        foreach(array_reverse(static::$resultStrenght, true) as $ResultValue => $ResultName)
        {
            if(null !== ($UsedCards = call_user_func('static::' . str_replace(" ", "", ucwords($ResultName)), $Ranks, $NumRanks, $Suits, $NumSuits)))
            {
                switch($ResultName)
                {
                    case 'flush':
                        $Suit = $UsedCards;
                        $UsedCards = array();

                        foreach(array_unique($Ranks) as $CardRank)
                        {
                            if(5 <= count($UsedCards))
                                break;

                            foreach($Hand as $Card)
                            {
                                if(self::$strenght[$Card->Rank] == $CardRank && $Card->Suit == $Suit)
                                {
                                    $UsedCards[] = $CardRank;
                                    break;
                                }
                            }
                        }
                    break;

                    case 'royal flush':
                    case 'straight flush':
                        $Suit = $UsedCards['suit'];
                        $SuitRanks = array();

                        foreach($Hand as $Card)
                        {
                            if($Card->Suit == $Suit)
                                $SuitRanks[] = self::$strenght[$Card->Rank];
                        }

                        arsort($SuitRanks);
                        $NumSuitRanks = array_count_values($SuitRanks);

                        if(null !== ($UsedCards = call_user_func('static::' . str_replace(" ", "", ucwords($ResultName)), $SuitRanks, $NumSuitRanks, $Suit, $NumSuits)))
                            $UsedCards = $UsedCards['ranks'];
                    break;
                }

                $Score = $ResultValue;
                break;
            }
        }
        if(isset($UsedCards))
            foreach($UsedCards as $UsedCard)
            {
                $NumRanks[$UsedCard]--;
            }
        else
            $UsedCards = array();

        for($i = count($UsedCards); $i < 5; ++$i)
        {
            foreach($NumRanks as $Rank => &$NumRank)
            {
                if($NumRank <= 0)
                    continue;

                $UsedCards[] = $Rank;
                $NumRank--;
                break;
            }
        }

        return array('score' => $Score, 'cards' => $UsedCards);
    }

    public static function RoyalFlush($Ranks, $NumRanks, $Suits, $NumSuits)
    {
        if(null !== ($StraightFlush = static::StraightFlush($Ranks, $NumRanks, $Suits, $NumSuits)))
        {
            if(12 == $StraightFlush['ranks'][0])
            {
                return $StraightFlush;
            }
        }

        return null;
    }

    public static function StraightFlush($Ranks, $NumRanks, $Suits, $NumSuits)
    {
        if(null !== ($Flush = static::Flush($Ranks, $NumRanks, $Suits, $NumSuits)))
        {
            if(null !== ($Straight = static::Straight($Ranks, $NumRanks, $Suits, $NumSuits)))
            {
                return array('ranks' => $Straight, 'suit' => $Flush);
            }
        }

        return null;
    }

    public static function FourOfAKind($Ranks, $NumRanks, $Suits, $NumSuits)
    {
        return static::SomeOfAKind($NumRanks, 4);
    }

    public static function FullHouse($Ranks, $NumRanks, $Suits, $NumSuits)
    {
        if(null !== ($TreeOfAKind = static::ThreeOfAKind($Ranks, $NumRanks, $Suits, $NumSuits)))
        {
            $NumRanks[$TreeOfAKind[0]] = 0;
            if(null !== ($Pair = static::OnePair($Ranks, $NumRanks, $Suits, $NumSuits)))
            {
                return array_merge($TreeOfAKind, $Pair);
            }
        }

        return null;
    }

    public static function Flush($Ranks, $NumRanks, $Suits, $NumSuits)
    {
        if(5 <= reset($NumSuits))
        {
            return key($NumSuits);
        }

        return null;
    }

    public static function Straight($Ranks, $NumRanks, $Suits, $NumSuits)
    {
        $ValuesRank = array_keys($NumRanks);

        if(5 > count($ValuesRank))
            return null;

        for($i = 0; $i <= count($ValuesRank) - 5; ++$i)
        {
            $HighCard = $PrevCard = $ValuesRank[$i];
            $IsStraight = true;

            for($j = $i + 1; $j < $i + 5; ++$j)
            {
                if($ValuesRank[$j] != $PrevCard - 1)
                {
                    $IsStraight = false;
                    break;
                }

                $PrevCard = $ValuesRank[$j];
            }

            if($IsStraight)
                return array($HighCard, $HighCard - 1, $HighCard - 2, $HighCard - 3, $HighCard - 4);
        }

        if(in_array(12, $ValuesRank) && in_array(0, $ValuesRank) && in_array(1, $ValuesRank) && in_array(2, $ValuesRank) && in_array(3, $ValuesRank))
            return array(12, 3, 2, 1, 0);

        return null;
    }

    public static function ThreeOfAKind($Ranks, $NumRanks, $Suits, $NumSuits)
    {
        return static::SomeOfAKind($NumRanks, 3);
    }

    public static function TwoPair($Ranks, $NumRanks, $Suits, $NumSuits)
    {
        if(null !== ($Pair = static::OnePair($Ranks, $NumRanks, $Suits, $NumSuits)))
        {
            $NumRanks[$Pair[0]] = 0;
            if(null !== ($SecondPair = static::OnePair($Ranks, $NumRanks, $Suits, $NumSuits)))
            {
                return array_merge($Pair, $SecondPair);
            }
        }

        return null;
    }

    public static function OnePair($Ranks, $NumRanks, $Suits, $NumSuits)
    {
        return static::SomeOfAKind($NumRanks, 2);
    }

    protected static function SomeOfAKind($NumRanks, $NumKind)
    {
        foreach($NumRanks as $Rank => $Num)
        {
            if($NumKind <= $Num)
            {
                return array_pad(array(), $NumKind, $Rank);
            }
        }

        return null;
    }
}