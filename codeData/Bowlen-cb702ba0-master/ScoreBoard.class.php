<?php

require_once 'Player.class.php';

class ScoreBoard
{
    private $players = [];
    private $currentPlayer = 0;

    public function addPlayer($player)
    {
        $this->players[] = $player;
    } 

    public function getCurrentPlayer()
    {
        $player = $this->players[$this->currentPlayer];
        return $player;
    }

    public function nextPlayer()
    {
        $this->currentPlayer++;
        if ($this->currentPlayer >= $this->getNumPlayers()) {
            $this->currentPlayer = 0;
        }
    }
    
    public function getNumPlayers()
    {
        return count($this->players);
    }

    public function registerPinsDown($firstPins, $secondPins)
    {
        $player = $this->getCurrentPlayer();
        $lastTwoThrows = $player->getLastTwoThrows();
        $tempScore = $firstPins + $secondPins;

        if ($lastTwoThrows != null) {
            if (isset($lastTwoThrows[0]) && isset($lastTwoThrows[1])) {
                if ($lastTwoThrows[0] == 10) {
                    $tempScore += $tempScore;
                } else if ($lastTwoThrows[0] + $lastTwoThrows[1] == 10) {
                    $tempScore += $firstPins;
                }
            }
        }

        $player->setScore($player->getScore() + $tempScore);
    }
    
    public function registerPinsDownLastRound($pins)
    {
        $player = $this->getCurrentPlayer();
        $player->setScore($player->getScore() + $pins);
    }

    public function printStatus()
    {
        foreach ($this->players as $player) {
            echo "Name: " . $player->getName() . ", Score: " . $player->getScore() . PHP_EOL;
        }
    }

    public function getWinner()
    {
        $max = 0;
        $final_player;
        foreach ($this->players as $player) {
            if ($max < (float)$player->getScore()) {
                $max = $player->getScore();
                $final_player = $player;
            }
        }
        return $final_player;
    }
}
