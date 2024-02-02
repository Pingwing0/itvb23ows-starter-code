<?php

namespace app\ai;

use app\Database;
use app\Game;
use app\Moves;

class Ai
{
    public function postToApi(
        array $postData, CurlRequest $curlRequest = new CurlRequest('')
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

    public function getDataToSend(array $boardTiles, array $handPlayerOne, array $handPlayerTwo, int $currentMove): array
    {
        return ["move_number" => $currentMove,
            "hand" => [$handPlayerOne, $handPlayerTwo],
            "board" => $boardTiles
            ];
    }

    public function play(Game $game, Database $database, String $piece, String $position): void
    {
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

    public function aiPlaysTurn(Game $game, Database $database, CurlRequest $curlRequest): void
    {
        $boardTiles = $game->getBoard()->getBoardTiles();
        $handPlayerOne = $game->getPlayerOne()->getHand();
        $handPlayerTwo = $game->getPlayerTwo()->getHand();
        $currentPlayerNumber = $game->getCurrentPlayer()->getPlayerNumber();

        $dataToSend = $this->getDataToSend($boardTiles, $handPlayerOne, $handPlayerTwo, $currentPlayerNumber);
        $response = $this->postToApi($dataToSend, $curlRequest);

        $move = $response[0];
        if ($move == "play") {
            $piece = $response[1];
            $position = $response[2];
            $this->play($game, $database, $piece, $position);
        }
        if ($move == "move") {
            $fromPosition = $response[1];
            $toPosition = $response[2];
            $this->move($game, $database, $fromPosition, $toPosition);
        }
        if ($move == "pass") {
            $this->pass($game, $database);
        }
    }

}
