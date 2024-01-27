<?php namespace app\formPosts;

require_once '../../vendor/autoload.php';

use app\Database;
use app\Game;

session_start();

/** @var Game $game **/
$game = $_SESSION['game'];
/** @var Database $database **/
$database = $_SESSION['database'];

$game->restart($database);

header('Location: /../../index.php');
