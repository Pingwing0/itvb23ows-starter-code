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

    //todo regel 2: Een sprinkhaan mag zich niet verplaatsen
    // naar het veld waar hij al staat

    public function testWhenSprinkhaanMovesToSameFieldThenThrowException() {
        $fromPosition = '0,0';
        $toPosition = '0,0';

        $sprinkhaan = new Sprinkhaan($fromPosition);

        $this->expectException(\app\RulesException::class);
        $sprinkhaan->move($toPosition);
    }


    //todo regel 3: Een sprinkhaan moet over minimaal 1 steen springen

    //todo regel 4: Een sprinkhaan mag niet naar een bezet veld springen

    //todo regel 5: Een sprinkhaan mag niet over lege velden springen.
    // dit betekent dat alle velden tussen de start- en eindpositie
    // bezet moeten zijn





}