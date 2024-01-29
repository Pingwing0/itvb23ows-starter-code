<?php

use app\Sprinkhaan;

class SprinkhaanTest extends PHPUnit\Framework\TestCase
{
    public function testWhenSprinkhaanMovesThenItMovesInAStraightLine() {
        $fromPosition = '0,0';
        $toPosition = '0,2';

        $sprinkhaan = new Sprinkhaan($fromPosition);
        $sprinkhaan->move($toPosition);

        $result = $sprinkhaan->getPosition();
        $expectedPosition = ('0,2');
        self::assertEquals($expectedPosition, $result);
    }

    public function testWhenSprinkhaanMovesNotInStraightLineThenItDoesntMove() {
        $fromPosition = '1,0';
        $toPosition = '0,2';

        $sprinkhaan = new Sprinkhaan($fromPosition);
        $sprinkhaan->move($toPosition);

        $result = $sprinkhaan->getPosition();
        $expectedPosition = ('1,0');
        self::assertEquals($expectedPosition, $result);
    }

    public function testWhenFromZeroTwoToTwoZeroIsAStraightLineReturnsTrue() {
        $fromPosition = '0,2';
        $toPosition = '2,0';

        $sprinkhaan = new Sprinkhaan($fromPosition);

        $result = $sprinkhaan->moveIsAStraightLine($fromPosition, $toPosition);
        self::assertTrue($result);
    }

    public function testWhenSprinkhaanMovesToSameFieldThenThrowException() {
        $fromPosition = '0,0';
        $toPosition = '0,0';

        $sprinkhaan = new Sprinkhaan($fromPosition);
        
        $this->expectException(\app\RulesException::class);
        $sprinkhaan->move($toPosition);
    }

    //todo regel 3: Een sprinkhaan moet over minimaal 1 steen springen

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
    public function testWhenSprinkhaanMovesAndDoesntJumpOverStonesThenThrowsException() {
        $fromPosition = '0,0';
        $toPosition = '2,0';

        $boardTiles = [
            '1,-1' => [[0, "Q"]],
            '0,0' => [[0, "S"]],
            '2,-1' => [[1, "B"]]
        ];

        $sprinkhaan = new Sprinkhaan($fromPosition);

        $this->expectException(\app\RulesException::class);
        $sprinkhaan->move($toPosition);
    }

    //todo regel 4: Een sprinkhaan mag niet naar een bezet veld springen

    //todo regel 5: Een sprinkhaan mag niet over lege velden springen.
    // dit betekent dat alle velden tussen de start- en eindpositie
    // bezet moeten zijn





}