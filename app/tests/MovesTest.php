<?php

use app\Board;
use app\Moves;
use app\Database;
use app\Game;
use app\Player;

class MovesTest extends PHPUnit\Framework\TestCase
{

    public function testGivenSecondMoveThenUndoLastMoveRemovesMoveFromBoard() {
        $dbMock = $this->getMockBuilder(Database::class)
            ->onlyMethods(['getLastMoveId',
                'addNewGameToDatabase',
                'selectLastMoveFromGame',
                'removeLastMoveFromGame'])
            ->getMock();

        $hand = [];
        $boardTiles = [];
        $playerNumber = 0;
        $state = serialize([$hand, $boardTiles, $playerNumber]);

        $dbMock->method('getLastMoveId')->willReturn([0]);
        $dbMock->method('selectLastMoveFromGame')->willReturn(['previous_id' => 0, 'state' => $state]);

        $board = new Board(['0,0' => [[0, "Q"]]]);
        $game = new Game();
        $game->restart($dbMock, board: $board);

        Moves::undoLastMove($game, $dbMock);

        $result = $board->getBoardTiles();
        $expectedResult = [];
        self::assertEquals($expectedResult, $result);
    }

    public function testWhenPlayerCantMoveAnyPieceThenThereIsAPieceAbleToMoveReturnsFalse() {
        $board = new Board([]);
        $player = new Player(0, []);

        $result = Moves::thereIsAPieceAbleToMove($board, $player);
        self::assertFalse($result);
    }

    public function testWhenPlayerCanMoveAnyPieceThenThereIsAPieceAbleToMoveReturnsTrue() {
        $boardTiles = [
            '0,0' => [[0, "Q"]],
            '0,1' => [[0, "B"]]];
        $board = new Board($boardTiles);
        $player = new Player(0, []);

        $result = Moves::thereIsAPieceAbleToMove($board, $player);
        self::assertTrue($result);
    }

    public function testWhenPlayerCantPlayAnyPieceThenThereIsAPieceAbleToBePlayedReturnsFalse() {
        $boardTiles = [
            '0,0' => [[0, "S"]],
            '1,0' => [[1, "Q"]],
            '0,1' => [[1, "Q"]],
            '-1,1' => [[1, "Q"]],
            '-1,0' => [[1, "Q"]],
            '0,-1' => [[1, "Q"]],
            '1,-1' => [[1, "Q"]],];
        $board = new Board($boardTiles);
        $player = new Player(0, []);

        $result = Moves::thereIsAPieceAbleToBePlayed($board, $player);
        self::assertFalse($result);
    }

    public function testWhenPlayerCanPlayAnyPieceThenThereIsAPieceAbleToBePlayedReturnsTrue() {
        $hand = ["B" => 2, "S" => 2, "A" => 3, "G" => 3];
        $board = new Board([]);
        $player = new Player(0, $hand);

        $result = Moves::thereIsAPieceAbleToBePlayed($board, $player);
        self::assertTrue($result);
    }

    public function testWhenNoOtherMovesCanBeDonePlayerIsAbleToPassReturnsTrue() {
        $boardTiles = [
            '0,0' => [[0, "S"]],
            '1,0' => [[1, "Q"]],
            '0,1' => [[1, "Q"]],
            '-1,1' => [[1, "Q"]],
            '-1,0' => [[1, "Q"]],
            '0,-1' => [[1, "Q"]],
            '1,-1' => [[1, "Q"]],];
        $board = new Board($boardTiles);
        $player = new Player(0, []);

        $result = Moves::playerIsAbleToPass($board, $player);
        self::assertTrue($result);
    }
}
