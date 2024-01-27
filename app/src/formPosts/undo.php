<?php namespace app\formPosts;

require_once '../../vendor/autoload.php';

use app\Game;
use app\Moves;

session_start();

/** @var Game $game **/
$game = $_SESSION['game'];
$database = $_SESSION['database'];

Moves::undoLastMove($game, $database);

header('Location: /../../index.php');
