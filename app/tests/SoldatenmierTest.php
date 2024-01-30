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

        $result = $soldatenmier->moveOnce($board, $boardTiles, $fromPosition, $toPosition);
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
        $soldatenmier->moveOnce($board, $boardTiles, $fromPosition, $toPosition);
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
        $boardTiles = [
            '0,0' => [[0, "Q"]],
            '1,0' => [[1, "Q"]],
            '-1,0' => [[0, "A"]],
            '2,0' => [[1, "B"]]
        ];
        $board = new \app\Board($boardTiles);

        $soldatenmier = new Soldatenmier($fromPosition);

        $result = $soldatenmier->moveClockwise($board, $boardTiles, $fromPosition);
        $expectedResult = '0,-1';
        self::assertEquals($expectedResult, $result);
    }

    public function testWhenMoveOnceToOccupiedTileThenThrowException() {
        $fromPosition = '0,0';
        $toPosition = '1,0';
        $boardTiles = [
            '0,0' => [[0, "A"]],
            '1,0' => [[0, "Q"]],];
        $board = new \app\Board($boardTiles);

        $soldatenmier = new Soldatenmier($fromPosition);

        $this->expectException(\app\RulesException::class);
        $soldatenmier->moveOnce($board, $boardTiles, $fromPosition, $toPosition);
    }

    //todo bugfix move is legal

}
