<?php

use app\pieces\Soldatenmier;
use app\Board;
use app\Player;

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
        $board = new Board($boardTiles);

        $soldatenmier = new Soldatenmier($fromPosition);

        $result = $soldatenmier->moveOnce($board, $boardTiles, $fromPosition, $toPosition);
        $expectedResult = '-1,1';
        self::assertEquals($expectedResult, $result);

    }

    public function testGivenMoveToNotNeighbourWhenSoldatenmierMovesOnceThenReturnsEmptyString() {
        $fromPosition = '0,0';
        $toPosition = '1,1';
        $boardTiles = [
            '0,0' => [[0, "Q"]],
            '1,0' => [[1, "Q"]],
            '-1,0' => [[0, "A"]],
            '2,0' => [[1, "B"]]
        ];
        $board = new Board($boardTiles);

        $soldatenmier = new Soldatenmier($fromPosition);

        $result = $soldatenmier->moveOnce($board, $boardTiles, $fromPosition, $toPosition);
        self::assertEquals('',$result);
    }

   public function testWhenSoldatenmierMovesToCurrentPositionThenReturnFalse() {
       $fromPosition = '0,0';
       $toPosition = '0,0';
       $boardTiles = [
           '0,0' => [[0, "A"]],
           '1,0' => [[0, "Q"]],];
       $board = new Board($boardTiles);

       $soldatenmier = new Soldatenmier($fromPosition);

       $result = $soldatenmier->move($board, $toPosition);
       self::assertFalse($result);
   }

   public function testWhenSoldatenmierMovesToOccupiedSpaceThenReturnFalse() {
       $fromPosition = '0,0';
       $toPosition = '1,0';
       $boardTiles = [
           '0,0' => [[0, "A"]],
           '1,0' => [[0, "Q"]],];
       $board = new Board($boardTiles);

       $soldatenmier = new Soldatenmier($fromPosition);

       $result = $soldatenmier->move($board, $toPosition);
       self::assertFalse($result);
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
        $board = new Board($boardTiles);

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
        $board = new Board($boardTiles);

        $soldatenmier = new Soldatenmier($fromPosition);

        $result = $soldatenmier->moveClockwise($board, $boardTiles, $fromPosition, '');
        $expectedResult = '0,-1';
        self::assertEquals($expectedResult, $result);
    }

    public function testWhenMoveOnceToOccupiedTileThenReturnEmptyString() {
        $fromPosition = '0,0';
        $toPosition = '1,0';
        $boardTiles = [
            '0,0' => [[0, "A"]],
            '1,0' => [[0, "Q"]],];
        $board = new Board($boardTiles);

        $soldatenmier = new Soldatenmier($fromPosition);

        $result = $soldatenmier->moveOnce($board, $boardTiles, $fromPosition, $toPosition);
        self::assertEquals('',$result);
    }

    public function testWhenTwoTilesOnBoardThenGetPossibleMovePositionsReturnsPositions() {
        $fromPosition = '0,0';
        $boardTiles = [
            '0,0' => [[0, "A"]],
            '1,0' => [[0, "Q"]],];
        $board = new Board($boardTiles);
        $player = new Player(0, []);

        $soldatenmier = new Soldatenmier($fromPosition);

        $result = $soldatenmier->getPossibleMovePositions($fromPosition, $player, $board);
        $expectedResult = ['1,-1', '2,-1', '2,0', '0,1', '1,1']; //oid (misschien andere volgorde?)
        self::assertEquals($expectedResult, $result);
    }

    public function testWhenLegalMoveThenMoveIsLegalReturnsTrue() {
        $fromPosition = '-1,0';
        $toPosition = '-1,1';
        $boardTiles = [
            '0,0' => [[0, "Q"]],
            '1,0' => [[1, "Q"]],
            '-1,0' => [[0, "A"]],
            '2,0' => [[1, "B"]]
        ];
        $board = new Board($boardTiles);
        $player = new Player(0, []);

        $soldatenmier = new Soldatenmier($fromPosition);

        self::assertTrue($soldatenmier->moveIsLegal($board, $player, $fromPosition, $toPosition));

    }

    public function testWhenSoldatenmierMovesOnceClockwiseThenItMovesClockWiseToLegalPosition() {
        $fromPosition = '0,1';
        $boardTiles = [
            '0,0' => [[0, "Q"]],
            '1,0' => [[1, "Q"]],
            '0,1' => [[0, "A"]],
            '2,0' => [[1, "B"]]
        ];
        $board = new Board($boardTiles);

        $soldatenmier = new Soldatenmier($fromPosition);

        $result = $soldatenmier->moveClockwise($board, $boardTiles, $fromPosition, '1,1');
        $expectedResult = '-1,1';
        self::assertEquals($expectedResult, $result);
    }

}
