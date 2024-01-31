<?php

use app\Board;
use app\Moves;

class MovesTest extends PHPUnit\Framework\TestCase
{

    public function testGivenSecondMoveThenUndoLastMoveRemovesMoveFromBoard() {
        $dbMock = $this->getMockBuilder(\app\Database::class)
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

        $board = new \app\Board(['0,0' => [[0, "Q"]]]);
        $game = new \app\Game();
        $game->restart($dbMock, board: $board);

        Moves::undoLastMove($game, $dbMock);

        $result = $board->getBoardTiles();
        $expectedResult = [];
        self::assertEquals($expectedResult, $result);
    }

    public function testWhenPlayerCantMoveAnyPieceThenThereIsAPieceAbleToMoveReturnsFalse() {
        $board = new Board([]);
        $player = new \app\Player(0, []);

        $result = Moves::thereIsAPieceAbleToMove($board, $player);
        self::assertFalse($result);
    }

    public function testWhenPlayerCanMoveAnyPieceThenThereIsAPieceAbleToMoveReturnsTrue() {
        $boardTiles = [
            '0,0' => [[0, "Q"]],
            '0,1' => [[0, "B"]]];
        $board = new Board($boardTiles);
        $player = new \app\Player(0, []);

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
        $player = new \app\Player(0, []);

        $result = Moves::thereIsAPieceAbleToBePlayed($board, $player);
        self::assertFalse($result);
    }

    public function testWhenPlayerCanPlayAnyPieceThenThereIsAPieceAbleToBePlayedReturnsTrue() {
        $hand = ["B" => 2, "S" => 2, "A" => 3, "G" => 3];
        $board = new Board([]);
        $player = new \app\Player(0, $hand);

        $result = Moves::thereIsAPieceAbleToBePlayed($board, $player);
        self::assertTrue($result);
    }

    //todo can only pass when no other moves can be played

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
        $player = new \app\Player(0, []);

        $result = Moves::playerIsAbleToPass($board, $player);
        self::assertTrue($result);

    }
}
