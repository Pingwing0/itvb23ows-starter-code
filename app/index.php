<?php

use app\Database;
use app\Game;

require_once 'vendor/autoload.php';

session_start();



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Hive</title>
</head>

<body>
<h1> Welcome to Hive! </h1>
<h2> Do you want to play with Ai? </h2>
<form method="post" action="src/formPosts/ai.php">
    <select name="ai" required>
        <option value = "white"> AI = White </option>
        <option value = "black"> AI = Black </option>
        <option value = "none"> No AI </option>
    </select>
    <input type="submit" value="Submit">
</form>
</body>
</html>
