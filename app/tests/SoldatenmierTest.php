<?php

use app\pieces\Soldatenmier;

class SoldatenmierTest extends PHPUnit\Framework\TestCase
{
    //todo regel 2
    // Een verschuiving is een zet zoals de bijenkoningin die mag maken.
    public function testWhenSoldatenmierMovesOnceThenItMovesToANeighbouringField() {
        $fromPosition = '-1,0';
        $toPosition = '-1,1';
        $boardTiles = [
            '0,0' => [[0, "Q"]],
            '1,0' => [[1, "Q"]],
            '-1,0' => [[0, "A"]],
            '2,0' => [[1, "B"]]
        ];
        $board = new \app\Board($boardTiles);

        $soldatenmier = new Soldatenmier($fromPosition);

        $result = $soldatenmier->moveOnce($board, $toPosition);
        $expectedResult = '-1,1';
        self::assertEquals($expectedResult, $result);

    }

    public function testGivenMoveToNotNeighbourWhenSoldatenmierMovesOnceThenThrowsException() {
        $fromPosition = '0,0';
        $toPosition = '1,1';
        $boardTiles = [
            '0,0' => [[0, "Q"]],
            '1,0' => [[1, "Q"]],
            '-1,0' => [[0, "A"]],
            '2,0' => [[1, "B"]]
        ];
        $board = new \app\Board($boardTiles);

        $soldatenmier = new Soldatenmier($fromPosition);

        $this->expectException(\app\RulesException::class);
        $soldatenmier->moveOnce($board, $toPosition);

    }

    //todo regel 1
    // Een soldatenmier verplaatst zich door een onbeperkt aantal keren te verschuiven


    //todo regel 3
    // Een soldatenmier mag zich niet verplaatsen naar het veld waar hij al staat.

    //todo regel 4
    // Een soldatenmier mag alleen verplaatst worden over en naar lege velden.

}