<?php

namespace Zweer\Cards\Play;

abstract class Play
{
    protected $_deck;

    protected $_players = array();

    /**
     * @throws \Exception
     * @return \Zweer\Cards\Card\Card
     */
    public static function card()
    {
        throw new \Exception('The card type must be implemented on a per-class basis');
    }
}