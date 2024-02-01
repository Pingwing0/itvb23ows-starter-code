<?php namespace app\formPosts;

require_once '../../vendor/autoload.php';

use app\ai\CurlRequest;
use app\Database;
use app\Game;
use app\Moves;

session_start();

/** @var Game $game **/
$game = $_SESSION['game'];
/** @var Database $database **/
$database = $_SESSION['database'];

$fromPosition = $_SESSION['fromPosition'];
$toPosition = $_POST['toPosition'];

Moves::movePiece($fromPosition, $toPosition, $game, $database);

if ($game->getCurrentPlayer()->isAi()) {
    $url = $_ENV["AI_API_HOST"];
    $curlRequest = new CurlRequest($url);
    $ai = $game->getCurrentPlayer()->getAi();
    $ai->aiPlaysTurn($game, $database, $curlRequest);
}

unset($_SESSION['fromPosition']);

header('Location: /../../main.php');
