<?php

namespace Zweer\Cards;

class FrenchCard extends Card
{
    const RANK_ACE   = 0x0;     # 1
    const RANK_2     = 0x1;     # 2
    const RANK_3     = 0x2;     # 3
    const RANK_4     = 0x3;     # 4
    const RANK_5     = 0x4;     # 5
    const RANK_6     = 0x5;     # 6
    const RANK_7     = 0x6;     # 7
    const RANK_8     = 0x7;     # 8
    const RANK_9     = 0x8;     # 9
    const RANK_10    = 0x9;     # 10
    const RANK_JACK  = 0xA;     # 11
    const RANK_QUEEN = 0xB;     # 12
    const RANK_KING  = 0xC;     # 13

    const CARD_NUMBER = 52;

    public static $names = array(
        'suits' => array(
            'Hearts',
            'Diamonds',
            'Clubs',
            'Spades',
        ),
        'ranks' => array(
            'Ace',
            '2',
            '3',
            '4',
            '5',
            '6',
            '7',
            '8',
            '9',
            '10',
            'Jack',
            'Queen',
            'King',
        ),
    );
}