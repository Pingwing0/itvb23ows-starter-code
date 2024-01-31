<?php

use app\pieces\Spin;

class SpinTest extends PHPUnit\Framework\TestCase
{
    //todo spin moves by moving 3 times

    //todo a move is like a queen

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

    //todo cant move to same field
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

    //todo can only move over and through empty fields
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


    //todo cant move to tile already passed

}