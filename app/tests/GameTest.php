<?php

use app\Player;
use app\Database;
use app\Board;
use app\Game;

class GameTest extends PHPUnit\Framework\TestCase
{
    public function testTest(): void
    {
        $s = "abc";                    // arrange
        $len = strlen($s);             // act
        $this->assertEquals(3, $len);  // assert
    }

    public function testGivenNotEmptyBoardThenSetStateWithEmptyBoardChangesBoardToEmptyBoardState() {
        $dbMock = $this->getMockBuilder(Database::class)
            ->onlyMethods(['getLastMoveId', 'addNewGameToDatabase'])
            ->getMock();
        $dbMock->method('getLastMoveId')->willReturn([0]);

        $hand = [];
        $boardTiles = [];
        $playerNumber = 0;
        $state = serialize([$hand, $boardTiles, $playerNumber]);

        $board = new Board(['0,0' => [[0, "Q"]]]);
        $game = new Game();
        $game->restart($dbMock, board: $board);

        $game->setState($state);

        $result = $board->getBoardTiles();
        $expectedResult = [];
        self::assertEquals($expectedResult, $result);
    }

    public function testGivenEmptyBoardThenSetStateWithNotEmptyBoardChangesBoardToNotEmptyBoardState() {
        $dbMock = $this->getMockBuilder(Database::class)
            ->onlyMethods(['getLastMoveId', 'addNewGameToDatabase'])
            ->getMock();
        $dbMock->method('getLastMoveId')->willReturn([0]);

        $hand = [];
        $boardTiles = ['0,0' => [[0, "Q"]]];
        $playerNumber = 0;
        $state = serialize([$hand, $boardTiles, $playerNumber]);

        $board = new Board([]);
        $game = new Game();
        $game->restart($dbMock, board: $board);

        $game->setState($state);

        $result = $board->getBoardTiles();
        $expectedResult = ['0,0' => [[0, "Q"]]];
        self::assertEquals($expectedResult, $result);
    }

    public function testGetState() {
        $dbMock = $this->getMockBuilder(Database::class)
            ->onlyMethods(['getLastMoveId', 'addNewGameToDatabase'])
            ->getMock();
        $dbMock->method('getLastMoveId')->willReturn([0]);

        $hand = [];
        $boardTiles = [];
        $playerNumber = 0;
        $state = serialize([$hand, $boardTiles, $playerNumber]);

        $board = new Board($boardTiles);
        $game = new Game();
        $playerOne = new Player($playerNumber, $hand);
        $game->restart($dbMock, board: $board, playerOne: $playerOne);

        $result = $game->getState();
        $expectedResult = $state;
        self::assertEquals($expectedResult, $result);
    }

    public function testWhenGameIsWonByPlayerThenGameIsWonByReturnsWinnerArray() {
        $boardTiles = [
            '0,0' => [[0, "Q"]],
            '1,0' => [[1, "A"]],
            '0,1' => [[0, "B"]],
            '-1,1' => [[1, "B"]],
            '-1,0' => [[1, "Q"]],
            '0,-1' => [[0, "A"]],
            '1,-1' => [[0, "G"]],];
        $board = new Board($boardTiles);
        $game = new Game();

        $result = $game->gameIsWonBy($board);
        self::assertEquals([1], $result);
    }

    public function testWhenGameIsWonThenGameIsStoppedReturnsTrue() {
        $boardTiles = [
            '0,0' => [[0, "Q"]],
            '1,0' => [[1, "A"]],
            '0,1' => [[0, "B"]],
            '-1,1' => [[1, "B"]],
            '-1,0' => [[1, "Q"]],
            '0,-1' => [[0, "A"]],
            '1,-1' => [[0, "G"]],];
        $board = new Board($boardTiles);
        $game = new Game();
        $game->gameIsWonBy($board);

        $result = $game->getGameIsStopped();
        self::assertTrue($result);
    }

}
