<?php namespace app\formPosts;

require_once '../../vendor/autoload.php';

use app\Game;
use app\Moves;

session_start();

$_SESSION['fromPosition'] = $_POST['fromPosition'];

header('Location: /../../index.php');
