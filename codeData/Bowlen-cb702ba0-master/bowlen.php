<?php
require_once 'Player.class.php';
require_once 'BowlingGame.class.php';
require_once 'ScoreBoard.class.php';

echo "Welcome to the bowling game! Please enter your players 'name1,name2' :" . PHP_EOL;
$player_names = explode(",", readline());

$scoreboard = new ScoreBoard();

foreach ($player_names as $name) {
    $player = new Speler($name);
    $scoreboard->addPlayer($player);
}

$game = new BowlingGame($scoreboard);
$game->start();
$winner = $scoreboard->getWinner();
echo "De winnaar is: " . $winner->getName() . ", met een score van: " . $winner->getScore();
