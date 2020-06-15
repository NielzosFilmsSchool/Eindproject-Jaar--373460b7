<?php

class Speler
{
    private $score = 0;
    private $lastTwoThrows;
    private $name;

    function __construct($name)
    {
        $this->name = $name;
    }

    function getName()
    {
        return $this->name;
    }

    function setScore($value)
    {
        $this->score = $value;
    }

    function getScore()
    {
        return $this->score;
    }

    function setLastTwoThrows($throws)
    {
        $this->lastTwoThrows = $throws;
    }

    function getLastTwoThrows()
    {
        return $this->lastTwoThrows;
    }
}
