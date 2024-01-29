<?php namespace app;

use mysqli;

class Database {

    public function addMoveToDatabase(
        Game $game,
        String $state,
        String $type,
        String $toPosition = '',
        $fromPosition = ''
    ): void
    {
        $db = $this->initDatabase();
        if ($db->connect_error) {
            die($db->connect_error);
        }
        $stmt = $db->prepare('insert into moves
            (game_id, type, move_from, move_to, previous_id, state)
            values (?, ?, ?, ?, ?, ?)');
        $gameId = $game->getGameId();
        $lastMoveId = $game->getLastMoveId();
        $stmt->bind_param('isssis', $gameId,$type, $fromPosition, $toPosition, $lastMoveId, $state);
        $stmt->execute();
        $id = $db->insert_id;
        $game->setLastMoveId($id);
    }

    public function addNewGameToDatabase($game): void {
        $db = $this->initDatabase();
        if ($db->connect_error) {
            die($db->connect_error);
        }
        $db->prepare('INSERT INTO games VALUES ()')->execute();
        $id = $db->insert_id;
        $game->setGameId($id);
    }

    public function selectAllMovesFromGame(int $gameId) {

        $db = $this->initDatabase();
        if ($db->connect_error) {
            die($db->connect_error);
        }
        $stmt = $db->prepare('SELECT * FROM moves WHERE game_id = '.$gameId);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function selectLastMoveFromGame(Game $game) {
        $db = $this->initDatabase();
        if ($db->connect_error) {
            die($db->connect_error);
        }
        $lastMoveId = $game->getLastMoveId();
        $gameId = $game->getGameId();
        $stmt = $db->prepare('SELECT * FROM moves WHERE id = '.$lastMoveId.' AND game_id = '.$gameId);
        $stmt->execute();
        return $stmt->get_result()->fetch_array();
    }

    public function removeLastMoveFromGame(Game $game): void
    {
        $db = $this->initDatabase();
        if ($db->connect_error) {
            die($db->connect_error);
        }
        $lastMoveId = $game->getLastMoveId();
        $gameId = $game->getGameId();
        $db->prepare('DELETE FROM moves WHERE id = '.$lastMoveId.' AND game_id = '.$gameId)->execute();
    }

    public function getLastMoveId() {
        $db = $this->initDatabase();
        if ($db->connect_error) {
            die($db->connect_error);
        }
        $stmt = $db->prepare('SELECT id FROM moves WHERE id = (SELECT max(id) FROM moves)');
        $stmt->execute();
        return $stmt->get_result()->fetch_array();
    }

    private function initDatabase(): mysqli
    {
        $host = $_ENV["MYSQL_HOST"];
        $user = $_ENV["MYSQL_USERNAME"];
        $pw = $_ENV["MYSQL_PASSWORD"];
        $db = $_ENV["MYSQL_DATABASE"];
        return new mysqli($host, $user, $pw, $db);
    }

}

