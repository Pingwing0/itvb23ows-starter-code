<?php

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

}
