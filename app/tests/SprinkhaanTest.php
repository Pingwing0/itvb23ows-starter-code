<?php

use app\pieces\Sprinkhaan;

class SprinkhaanTest extends PHPUnit\Framework\TestCase
{
    public function testWhenMoveIsAStraightLineThenMoveIsAStraightLineReturnTrue() {
        $fromPosition = '0,0';
        $toPosition = '0,2';

        $sprinkhaan = new Sprinkhaan($fromPosition);

        $result = $sprinkhaan->moveIsAStraightLine($fromPosition, $toPosition);
        self::assertTrue($result);
    }

    public function testWhenMoveIsAStraightLineThenMoveIsAStraightLineReturnFalse() {
        $fromPosition = '1,0';
        $toPosition = '0,2';

        $sprinkhaan = new Sprinkhaan($fromPosition);

        $result = $sprinkhaan->moveIsAStraightLine($fromPosition, $toPosition);
        self::assertFalse($result);
    }

    public function testWhenFromZeroTwoToTwoZeroIsAStraightLineReturnsTrue() {
        $fromPosition = '0,2';
        $toPosition = '2,0';

        $sprinkhaan = new Sprinkhaan($fromPosition);

        $result = $sprinkhaan->moveIsAStraightLine($fromPosition, $toPosition);
        self::assertTrue($result);
    }

    public function testWhenSprinkhaanMovesToSameFieldThenReturnFalse() {
        $fromPosition = '0,0';
        $toPosition = '0,0';

        $sprinkhaan = new Sprinkhaan($fromPosition);
        $result = $sprinkhaan->move($toPosition, []);

        self::assertFalse($result);
    }

    public function testWhenMovingHorizontallyThenJumpOverStonesCountReturnMinimumOne() {
        $fromPosition = '0,0';
        $toPosition = '2,0';

        $boardTiles = [
            '1,-1' => [[0, "Q"]],
            '0,0' => [[0, "S"]],
            '1,0' => [[1, "B"]]
        ];

        $sprinkhaan = new Sprinkhaan($fromPosition);

        $result = $sprinkhaan->countNoOfStonesToJumpOver($fromPosition, $toPosition, $boardTiles);
        $expected = (1);
        self::assertEquals($expected, $result);
    }

    public function testWhenMovingDiagonallyRightDownThenJumpOverStonesCountReturnMinimumOne()
    {
        $fromPosition = '0,0';
        $toPosition = '0,2';

        $boardTiles = [
            '1,1' => [[0, "Q"]],
            '0,0' => [[0, "S"]],
            '0,1' => [[1, "B"]]
        ];

        $sprinkhaan = new Sprinkhaan($fromPosition);

        $result = $sprinkhaan->countNoOfStonesToJumpOver($fromPosition, $toPosition, $boardTiles);
        $expected = (1);
        self::assertEquals($expected, $result);
    }

    public function testWhenMovingDiagonallyLeftDownThenJumpOverStonesCountReturnMinimumOne()
    {
        $fromPosition = '0,0';
        $toPosition = '-2,2';

        $boardTiles = [
            '0,1' => [[0, "Q"]],
            '0,0' => [[0, "S"]],
            '-1,1' => [[1, "B"]]
        ];

        $sprinkhaan = new Sprinkhaan($fromPosition);

        $result = $sprinkhaan->countNoOfStonesToJumpOver($fromPosition, $toPosition, $boardTiles);
        $expected = (1);
        self::assertEquals($expected, $result);
    }
    public function testWhenSprinkhaanMovesAndDoesntJumpOverStonesThenReturnsFalse() {
        $fromPosition = '0,0';
        $toPosition = '2,0';

        $boardTiles = [
            '1,-1' => [[0, "Q"]],
            '0,0' => [[0, "S"]],
            '2,-1' => [[1, "B"]]
        ];

        $sprinkhaan = new Sprinkhaan($fromPosition);

        $result = $sprinkhaan->move($toPosition, $boardTiles);
        self::assertFalse($result);
    }

    public function testWhenSprinkhaanMovesToOccupiedSpaceThenMoveReturnsFalse() {
        $fromPosition = '0,0';
        $toPosition = '2,-2';

        $boardTiles = [
            '1,-1' => [[0, "Q"]],
            '0,0' => [[0, "S"]],
            '2,-2' => [[1, "B"]]
        ];

        $sprinkhaan = new Sprinkhaan($fromPosition);

        $result = $sprinkhaan->move($toPosition, $boardTiles);
        self::assertFalse($result);
    }

}
