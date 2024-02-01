<?php

namespace app\ai;

use app\Database;
use app\Game;
use app\Moves;

class Ai
{
    public function postToApi(
        array $postData, CurlRequest $curlRequest = new CurlRequest("https://localhost:5000")
    ) {
        $postDataJson = json_encode($postData);

        $curlRequest->setOption(CURLOPT_HEADER, false);
        $curlRequest->setOption(CURLOPT_RETURNTRANSFER, true);
        $curlRequest->setOption(CURLOPT_POST, true);
        $curlRequest->setOption(CURLOPT_POSTFIELDS, $postDataJson);
        $curlRequest->setOption(CURLOPT_HTTPHEADER, array('Content-Type:application/json'));

        $json_response = $curlRequest->execute();
        $curlRequest->close();

        return json_decode($json_response);
    }

    public function getDataToSend(array $boardTiles, array $playerOne, array $playerTwo, int $currentPlayer): array
    {
        return ["move_number" => $currentPlayer,
            "hand" => [$playerOne, $playerTwo],
            "board" => $boardTiles
            ];
    }

    public function play(Game $game, Database $database, String $piece, String $position) {
        $board = $game->getBoard();
        $player = $game->getCurrentPlayer();
        $playerNumber = $player->getPlayerNumber();

        Moves::executePlay($game, $board, $database, $piece, $player, $playerNumber, $position);
    }

    public function move(Game $game, Database $database, String $fromPosition, String $toPosition): void
    {
        $board = $game->getBoard();
        Moves::executeMove($game, $board, $database, $fromPosition, $toPosition);
    }

    public function pass(Game $game, Database $database): void
    {
        Moves::executePass($game, $database);
    }



}