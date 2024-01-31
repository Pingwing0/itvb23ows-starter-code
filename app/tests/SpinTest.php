<?php

use app\pieces\Spin;

class SpinTest extends PHPUnit\Framework\TestCase
{

    public function testWhenSpinMovesOnceThenItMovesToANeighbouringField() {
        $fromPosition = '-1,0';
        $toPosition = '-1,1';
        $boardTiles = [
            '0,0' => [[0, "Q"]],
            '1,0' => [[1, "Q"]],
            '-1,0' => [[0, "S"]],
            '2,0' => [[1, "B"]]
        ];
        $board = new \app\Board($boardTiles);

        $spin = new Spin($fromPosition);

        $result = $spin->moveOnce($board, $boardTiles, $fromPosition, $toPosition);
        $expectedResult = '-1,1';
        self::assertEquals($expectedResult, $result);

    }

    public function testWhenSpinMoveToNotNeighbourWhenSpinMovesOnceThenReturnsEmptyString() {
        $fromPosition = '0,0';
        $toPosition = '1,1';
        $boardTiles = [
            '0,0' => [[0, "Q"]],
            '1,0' => [[1, "Q"]],
            '-1,0' => [[0, "S"]],
            '2,0' => [[1, "B"]]
        ];
        $board = new \app\Board($boardTiles);

        $spin = new Spin($fromPosition);

        $result = $spin->moveOnce($board, $boardTiles, $fromPosition, $toPosition);
        self::assertEquals('',$result);
    }

    public function testWhenSpinMovesToCurrentPositionThenReturnFalse() {
        $fromPosition = '0,0';
        $toPosition = '0,0';
        $boardTiles = [
            '0,0' => [[0, "S"]],
            '1,0' => [[0, "Q"]],];
        $board = new \app\Board($boardTiles);

        $spin = new Spin($fromPosition);

        $result = $spin->move($board, $toPosition);
        self::assertFalse($result);
    }

    public function testWhenSpinMoveOnceToOccupiedTileThenReturnEmptyString() {
        $fromPosition = '0,0';
        $toPosition = '1,0';
        $boardTiles = [
            '0,0' => [[0, "S"]],
            '1,0' => [[0, "Q"]],];
        $board = new \app\Board($boardTiles);

        $spin = new Spin($fromPosition);

        $result = $spin->moveOnce($board, $boardTiles, $fromPosition, $toPosition);
        self::assertEquals('',$result);
    }

    public function testWhenSpinMovesToOccupiedSpaceThenReturnFalse() {
        $fromPosition = '0,0';
        $toPosition = '1,0';
        $boardTiles = [
            '0,0' => [[0, "S"]],
            '1,0' => [[0, "Q"]],];
        $board = new \app\Board($boardTiles);

        $spin = new Spin($fromPosition);

        $result = $spin->move($board, $toPosition);
        self::assertFalse($result);
    }

    //todo spin moves by moving 3 times

    public function testWhenSpinMovesThenMoveThreeTimesReturnsThreeTiles() {
        $fromPosition = '0,0';
        $toPosition = '2,0';
        $boardTiles = [
            '0,0' => [[0, "S"]],
            '1,0' => [[0, "Q"]],];
        $board = new \app\Board($boardTiles);

        $spin = new Spin($fromPosition);

        $result = count($spin->moveOnceThreeTimes($board, $toPosition));
        self::assertEquals(3, $result);
    }

    public function testWhenSpinLegalMoveThenNeighbourOfFromPositionIsNeighbourOfToPosition() {
        $fromPosition = '0,0';
        $toPosition = '2,0';

        $spin = new Spin($fromPosition);

        $result = $spin->neighbourOfFromPositionIsNeighbourOfToPosition($fromPosition, $toPosition);
        self::assertTrue($result);
    }

    //todo cant move to tile already passed



}