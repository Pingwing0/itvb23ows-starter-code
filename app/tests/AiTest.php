<?php

use app\ai\Ai;
use app\ai\CurlRequest;
use app\Board;
use app\Database;
use app\Game;

class AiTest extends PHPUnit\Framework\TestCase
{
    //todo ai implementeren in game
    // choose to play against ai html form, if yes change game player to ai player
    // when move done -> send to ai
    // ai -> do move

    public function testWhenAiResponseToPostThenResponseIsArray() {
        $curlRequestMock = $this->getMockBuilder(CurlRequest::class)
            ->onlyMethods(['execute', 'setOption', 'close'])
            ->disableOriginalConstructor()
            ->getMock('');

        $response = '["play", "Q", "-1,1"]';
        $curlRequestMock->method('execute')->willReturn($response);
        $curlRequestMock->method('setOption');
        $curlRequestMock->method('close');

        $ai = new Ai();

        $result = $ai->postToApi(['postData'], $curlRequestMock);
        $expectedResult = ['play',  'Q', '-1,1'];
        self::assertEquals($expectedResult, $result);
    }

    public function testWhenPreparingToSendApiDataThenDataToSendContainsPlayerHandsAndBoard() {
        $boardTiles = ['0,0' => [[0, "Q"]]];
        $handPlayerOne = ['S' => 1];
        $handPlayerTwo = ["Q" => 1, "B" => 2];
        $currentPlayerNumber = 0;

        $ai = new Ai();

        $result = $ai->getDataToSend($boardTiles, $handPlayerOne, $handPlayerTwo, $currentPlayerNumber);
        $expectedResult = [
            "move_number" => 0,
            "hand" => [
                ['S' => 1],
                ["Q" => 1, "B" => 2]
            ],
            "board" => [
                '0,0' => [[0, "Q"]]
            ]
        ];
        self::assertEquals($expectedResult, $result);
    }

    public function testWhenAiPlaysThenPieceGetsAddedToBoard() {
        $dbMock = $this->getMockBuilder(Database::class)
            ->onlyMethods([
                'getLastMoveId',
                'addNewGameToDatabase',
                'addMoveToDatabase'])
            ->getMock();
        $dbMock->method('getLastMoveId')->willReturn([0]);
        $dbMock->method('addNewGameToDatabase');
        $dbMock->method('addMoveToDatabase');

        $pieceType = 'Q';
        $position = '1,1';
        $game = new Game();
        $game->restart($dbMock);
        $ai = new Ai();

        $ai->play($game, $dbMock, $pieceType, $position);

        $result = $game->getBoard()->getBoardTiles();
        $expectedResult = ['1,1' => [[0, 'Q']]];
        self::assertEquals($expectedResult, $result);
    }

    public function testWhenAiPlaysThenRemovesThatPieceFromHand() {
        $dbMock = $this->getMockBuilder(Database::class)
            ->onlyMethods([
                'getLastMoveId',
                'addNewGameToDatabase',
                'addMoveToDatabase'])
            ->getMock();
        $dbMock->method('getLastMoveId')->willReturn([0]);
        $dbMock->method('addNewGameToDatabase');
        $dbMock->method('addMoveToDatabase');

        $pieceType = 'Q';
        $position = '1,1';
        $game = new Game();
        $game->restart($dbMock);
        $ai = new Ai();

        $ai->play($game, $dbMock, $pieceType, $position);

        $result = $game->getPlayerOne()->getHand();
        $expectedResult = ["B" => 2, "S" => 2, "A" => 3, "G" => 3];
        self::assertEquals($expectedResult, $result);
    }

    public function testWhenAiMovesThenPieceMovedToThenBoardGetsAltered() {
        $dbMock = $this->getMockBuilder(Database::class)
            ->onlyMethods([
                'getLastMoveId',
                'addNewGameToDatabase',
                'addMoveToDatabase'])
            ->getMock();
        $dbMock->method('getLastMoveId')->willReturn([0]);
        $dbMock->method('addNewGameToDatabase');
        $dbMock->method('addMoveToDatabase');

        $fromPosition = '0,0';
        $toPosition = '1,1';
        $boardTiles = ['0,0' => [[0, 'Q']]];
        $board = new Board($boardTiles);
        $game = new Game();
        $game->restart($dbMock, $board);
        $ai = new Ai();
        $ai->move($game, $dbMock, $fromPosition, $toPosition);

        $result = $game->getBoard()->getBoardTiles();
        $expectedResult = ['1,1' => [[0, 'Q']]];
        self::assertEquals($expectedResult, $result);
    }

    public function testWhenAiPassesThenSwitchTurn() {
        $dbMock = $this->getMockBuilder(Database::class)
            ->onlyMethods([
                'getLastMoveId',
                'addNewGameToDatabase',
                'addMoveToDatabase'])
            ->getMock();
        $dbMock->method('getLastMoveId')->willReturn([0]);
        $dbMock->method('addNewGameToDatabase');
        $dbMock->method('addMoveToDatabase');

        $game = new Game();
        $game->restart($dbMock);
        $ai = new Ai();
        $ai->pass($game, $dbMock);

        $result = $game->getCurrentPlayer()->getPlayerNumber();
        $expectedResult = 1;
        self::assertEquals($expectedResult, $result);
    }

}