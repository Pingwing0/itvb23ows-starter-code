<?php namespace app\formPosts;

require_once '../../vendor/autoload.php';

use app\Database;
use app\Game;
use app\Moves;

session_start();

/** @var Game $game **/
$game = $_SESSION['game'];
/** @var Database $database **/
$database = $_SESSION['database'];

Moves::undoLastMove($game, $database);

header('Location: /../../main.php');
