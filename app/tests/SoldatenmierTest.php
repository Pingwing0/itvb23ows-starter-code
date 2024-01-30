<?php

use app\pieces\Soldatenmier;

class SoldatenmierTest extends PHPUnit\Framework\TestCase
{
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

   public function testWhenSoldatenmierMovesToCurrentPositionThenThrowException() {
       $fromPosition = '0,0';
       $toPosition = '0,0';
       $boardTiles = [
           '0,0' => [[0, "A"]],
           '1,0' => [[0, "Q"]],];
       $board = new \app\Board($boardTiles);

       $soldatenmier = new Soldatenmier($fromPosition);

       $this->expectException(\app\RulesException::class);
       $soldatenmier->move($board, $toPosition);
   }

   public function testWhenSoldatenmierMovesToOccupiedSpaceThenThrowException() {
       $fromPosition = '0,0';
       $toPosition = '1,0';
       $boardTiles = [
           '0,0' => [[0, "A"]],
           '1,0' => [[0, "Q"]],];
       $board = new \app\Board($boardTiles);

       $soldatenmier = new Soldatenmier($fromPosition);

       $this->expectException(\app\RulesException::class);
       $soldatenmier->move($board, $toPosition);
   }

    public function testWhenSoldatenmierMovesThenCanMoveMultipleTiles() {
        $fromPosition = '-1,0';
        $toPosition = '1,-1';
        $boardTiles = [
            '0,0' => [[0, "Q"]],
            '1,0' => [[1, "Q"]],
            '-1,0' => [[0, "A"]],
            '2,0' => [[1, "B"]]
        ];
        $board = new \app\Board($boardTiles);

        $soldatenmier = new Soldatenmier($fromPosition);
        $soldatenmier->move($board, $toPosition);

        $result = $soldatenmier->getPosition();
        $expectedResult = '1,-1';
        self::assertEquals($expectedResult, $result);
    }

    public function testWhenSoldatenmierMovesOnceClockwiseThenItMovesClockWise() {
        $fromPosition = '-1,0';
        $toPosition = '1,-1';
        $boardTiles = [
            '0,0' => [[0, "Q"]],
            '1,0' => [[1, "Q"]],
            '-1,0' => [[0, "A"]],
            '2,0' => [[1, "B"]]
        ];
        $board = new \app\Board($boardTiles);

        $soldatenmier = new Soldatenmier($fromPosition);

        $result = $soldatenmier->moveClockwise($board, $toPosition);
        $expectedResult = '0,-1';
        self::assertEquals($expectedResult, $result);
    }
    //todo regel 1
    // Een soldatenmier verplaatst zich door een onbeperkt aantal keren te verschuiven

    //todo regel 3
    // Een soldatenmier mag zich niet verplaatsen naar het veld waar hij al staat.

    //todo regel 4
    // Een soldatenmier mag alleen verplaatst worden over en naar lege velden.

}