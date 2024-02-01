<?php
    require_once 'vendor/autoload.php';

    use app\Database;
    use app\Game;
    use app\RulesPlay;

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

    $board = $game->getBoard();
    $currentPlayer = $game->getCurrentPlayer();
    $playerOne = $game->getPlayerOne();
    $playerTwo = $game->getPlayerTwo();
    $offsets = $board->getOffsets();
    $gameWinners = $game->gameIsWonBy($board);

    if ($gameWinners) {
        if (count($gameWinners) == 1) {
            if ($gameWinners[0] == 0) {
                echo "<h1> White won the game! </h1>";
            } else {
                echo "<h1> Black won the game! </h1>";
            }
        } else {
            echo "<h1> The game is a tie! </h1>";
        }
    }

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Hive</title>
        <style>
            div.board {
                width: 60%;
                height: 100%;
                min-height: 500px;
                float: left;
                overflow: scroll;
                position: relative;
            }

            div.board div.tile {
                position: absolute;
            }

            div.tile {
                display: inline-block;
                width: 4em;
                height: 4em;
                border: 1px solid black;
                box-sizing: border-box;
                font-size: 50%;
                padding: 2px;
            }

            div.tile span {
                display: block;
                width: 100%;
                text-align: center;
                font-size: 200%;
            }

            div.player0 {
                color: black;
                background: white;
            }

            div.player1 {
                color: white;
                background: black
            }

            div.stacked {
                border-width: 3px;
                border-color: red;
                padding: 0;
            }
        </style>
    </head>
    <body>
        <div class="board">
            <?php
                // hier wordt het bord gebouwd
                $min_p = 1000;
                $min_q = 1000;
                foreach ($board->getBoardTiles() as $position => $tile) {
                    $pq = explode(',', $position); //pq = position als array
                    if ($pq[0] < $min_p) {
                        $min_p = $pq[0];
                    }
                    if ($pq[1] < $min_q) {
                        $min_q = $pq[1];
                    }
                }
                foreach (array_filter($board->getBoardTiles()) as $position => $tile) {
                    $pq = explode(',', $position);
                    $h = count($tile);
                    echo '<div class="tile player';
                    echo $tile[$h-1][0];
                    if ($h > 1) {
                        echo ' stacked';
                    }
                    echo '" style="left: ';
                    echo ($pq[0] - $min_p) * 4 + ($pq[1] - $min_q) * 2;
                    echo 'em; top: ';
                    echo ($pq[1] - $min_q) * 4;
                    echo "em;\">($pq[0],$pq[1])<span>";
                    echo $tile[$h-1][1];
                    echo '</span></div>';
                }
            ?>
        </div>
        <div class="hand">
            White:
            <?php
                foreach ($playerOne->getHand() as $tile => $ct) {
                    for ($i = 0; $i < $ct; $i++) {
                        echo '<div class="tile player0"><span>'.$tile."</span></div> ";
                    }
                }
            ?>
        </div>
        <div class="hand">
            Black:
            <?php
            foreach ($playerTwo->getHand() as $tile => $ct) {
                for ($i = 0; $i < $ct; $i++) {
                    echo '<div class="tile player1"><span>'.$tile."</span></div> ";
                }
            }
            ?>
        </div>
        <div class="turn">
            Turn: <?php
            if ($currentPlayer->getPlayerNumber() == 0) {
                echo "White";
            } else {
                echo "Black";
            } ?>
        </div>

        <form method="post" action="src/formPosts/play.php">
            <select name="piece" required>
                <?php
                    // dropdown player pieces
                    $hand = $game->getCurrentPlayer()->getHand();
                    if (RulesPlay::itIsTurnFourAndQueenBeeIsNotYetPlayed($hand)) {
                        $hand = ['Q' => 1];
                    }
                    foreach ($hand as $tileName => $count) {
                        echo "<option value=\"$tileName\">$tileName</option>";
                    }
                ?>
            </select>
            <select name="toPosition" required>
                <?php
                    // dropdown possible play positions
                    $possiblePlayPositions = $board->getPossiblePlayPositions(
                            $currentPlayer->getPlayerNumber(), $currentPlayer->getHand()
                    );
                    foreach ($possiblePlayPositions as $position) {
                        echo "<option value=\"$position\">$position</option>";
                    }
                ?>
            </select>
            <input type="submit" value="Play">
        </form>

        <form method="post" action="src/formPosts/pieceToMove.php">
            <select name="fromPosition" required>
                <?php
                    if (array_key_exists('fromPosition', $_SESSION)) {
                        $fromPosition = $_SESSION['fromPosition'];
                    } else {
                        $fromPosition = '';
                    }

                    foreach (array_keys($board->getTilesFromPlayer($currentPlayer->getPlayerNumber())) as $position) {
                        if ($position == $fromPosition) {
                            echo "<option selected=\"selected\" value=\"$position\">$position</option>";
                        } else {
                            echo "<option value=\"$position\">$position</option>";
                        }
                    }
                ?>
            </select>
            <input type="submit" value="Select tile to move">
        </form>
        <form method="post" action="src/formPosts/move.php">
            <select name="toPosition" required>
                <?php
                    $possibleMovePositions = $board->getPossibleMovePositions($fromPosition, $currentPlayer);
                    foreach ($possibleMovePositions as $position) {
                        echo "<option value=\"$position\">$position</option>";
                    }
                ?>
            </select>
            <input type="submit" value="Move tile">
        </form>

        <form method="post" action="src/formPosts/pass.php">
            <input type="submit" value="Pass">
        </form>

        <form method="post" action="src/formPosts/restart.php">
            <input type="submit" value="Restart">
        </form>

        <strong>
            <?php if (isset($_SESSION['error'])) {
                echo $_SESSION['error'];
                unset($_SESSION['error']);
            } ?>
        </strong>
        <ol>
            <?php
                $gameId = $game->getGameId();
                $result = $database->selectAllMovesFromGame($gameId);
                while ($row = $result->fetch_array()) {
                    echo '<li>'.$row[2].' '.$row[3].' '.$row[4].'</li>';
                }
            ?>
        </ol>

        <form method="post" action="src/formPosts/undo.php">
            <input type="submit" value="Undo">
        </form>

    </body>
</html>

