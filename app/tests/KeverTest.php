<?php

use app\Board;
use app\pieces\Kever;

class KeverTest extends PHPUnit\Framework\TestCase
{
    public function testWhenKeverMovesToOccupiedTileThenMoveReturnsTrue() {
        $boardTiles = [
            '0,0' => [[0, "B"]],
            '0,1' => [[1, "B"]],
            '0,-1' => [[1, "Q"]]];
        $board = new Board($boardTiles);
        $fromPosition = '0,1';
        $toPosition = '0,0';
        $kever = new Kever($fromPosition);

        $result = $kever->move($board, $fromPosition, $toPosition);
        self::assertTrue($result);

    }
}