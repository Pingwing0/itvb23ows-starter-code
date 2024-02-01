<?php namespace app\formPosts;

require_once '../../vendor/autoload.php';

use app\ai\CurlRequest;
use app\Game;
use app\Database;
use app\Moves;

session_start();

/** @var Game $game **/
$game = $_SESSION['game'];
/** @var Database $database **/
$database = $_SESSION['database'];

$piece = $_POST['piece'];
$toPosition = $_POST['toPosition'];

Moves::playPiece($piece, $toPosition, $game, $database);
if ($game->getCurrentPlayer()->isAi()) {
    $url = $_ENV["AI_API_HOST"];
    $curlRequest = new CurlRequest($url);
    $ai = $game->getCurrentPlayer()->getAi();
    $ai->aiPlaysTurn($game, $database, $curlRequest);
}


header('Location: /../../main.php');
