<?php

require_once '../../vendor/autoload.php';

use app\ai\Ai;
use app\ai\CurlRequest;
use app\Database;
use app\Game;

session_start();

if (!isset($_SESSION['database'])) {
    $database = new Database();
    $_SESSION['database'] = $database;
} else {
    $database = $_SESSION['database'];
}

if (!isset($_SESSION['game'])) {
    $game = new Game();
    $game->restart($database);
    $_SESSION['game'] = $game;
} else {
    $game = $_SESSION['game'];
}

$ai = $_POST['ai'];

$game->restart($database);

if ($ai == "white") {
    $game->getPlayerOne()->setAi(new Ai());
    $url = $_ENV["AI_API_HOST"];
    $curlRequest = new CurlRequest($url);
    $ai = $game->getCurrentPlayer()->getAi();
    $ai->aiPlaysTurn($game, $database, $curlRequest);
}
if ($ai == "black") {
    $game->getPlayerTwo()->setAi(new Ai());
}

header('Location: /../../main.php');
