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

    //todo spin moves by moving 3 times

    public function testWhenSpinLegalMoveThenNeighbourOfFromPositionIsNeighbourOfToPosition() {
        $fromPosition = '0,0';
        $toPosition = '2,0';
        $board = new Board([]);

        $spin = new Spin($fromPosition);

        $result = $spin->neighbourOfFromPositionIsNeighbourOfToPosition($board, $fromPosition, $toPosition);
        self::assertTrue($result);
    }

    //todo cant move to occupied tile




}