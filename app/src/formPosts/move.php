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

$fromPosition = $_SESSION['fromPosition'];
$toPosition = $_POST['toPosition'];

Moves::movePiece($fromPosition, $toPosition, $game, $database);

header('Location: /../../index.php');
