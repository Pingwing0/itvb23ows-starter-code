<?php

use app\Board;
use app\pieces\Spin;

class SpinTest extends PHPUnit\Framework\TestCase
{

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

    public function testWhenSpinLegalMovesThenMoveReturnTrue() {
        $fromPosition = '0,0';
        $toPosition = '0,1';
        $boardTiles = [
            '0,0' => [[0, "S"]],
            '1,0' => [[0, "Q"]],];
        $board = new \app\Board($boardTiles);

        $spin = new Spin($fromPosition);

        $result = $spin->move($board, $toPosition);
        self::assertTrue($result);
    }

    public function testWhenSpinIllegalMovesThenMoveReturnFalse() {
        $fromPosition = '0,0';
        $toPosition = '4,0';
        $boardTiles = [
            '0,0' => [[0, "S"]],
            '1,0' => [[0, "Q"]],];
        $board = new \app\Board($boardTiles);

        $spin = new Spin($fromPosition);

        $result = $spin->move($board, $toPosition);
        self::assertFalse($result);
    }

    public function testWhenSpinLegalMoveThenNeighbourOfFromPositionIsNeighbourOfToPositionReturnsTrue() {
        $fromPosition = '0,0';
        $toPosition = '2,0';
        $board = new Board([]);

        $spin = new Spin($fromPosition);

        $result = $spin->neighbourOfFromPositionIsNeighbourOfToPosition($board, $fromPosition, $toPosition);
        self::assertTrue($result);
    }

    public function testWhenSpinNotLegalMoveThenNeighbourOfFromPositionIsNeighbourOfToPositionReturnsFalse() {
        $fromPosition = '0,0';
        $toPosition = '4,0';
        $board = new Board([]);

        $spin = new Spin($fromPosition);

        $result = $spin->neighbourOfFromPositionIsNeighbourOfToPosition($board, $fromPosition, $toPosition);
        self::assertFalse($result);
    }

    //todo cant move to occupied tile
    public function testWhenSpinNeighbourIsOccupiedThenPositionIsOccupiedReturnsTrue() {
        $position = '1,0';
        $boardTiles = [
            '0,0' => [[0, "S"]],
            '1,0' => [[0, "Q"]],];
        $board = new Board($boardTiles);

        $spin = new Spin('0,0');

        $result = $spin->positionIsOccupied($board, $position);
        self::assertTrue($result);
    }

    public function testWhenSpinIsSurroundedGetAllLegalNeighboursOfPositionReturnsEmptyArray() {
        $position = '1,0';
        $boardTiles = [
            '0,0' => [[0, "S"]],
            '1,0' => [[0, "Q"]],
            '0,1' => [[0, "Q"]],
            '-1,1' => [[0, "Q"]],
            '-1,0' => [[0, "Q"]],
            '0,-1' => [[0, "Q"]],
            '1,-1' => [[0, "Q"]],];
        $board = new Board($boardTiles);

        $spin = new Spin('0,0');

        $result = $spin->getAllLegalNeighboursOfPosition($board, $position);
        self::assertFalse($result);
    }



}