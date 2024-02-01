<?php namespace app\formPosts;

require_once '../../vendor/autoload.php';

session_start();

$_SESSION['fromPosition'] = $_POST['fromPosition'];

header('Location: /../../main.php');
