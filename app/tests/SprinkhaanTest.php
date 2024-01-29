<?php

use app\Sprinkhaan;

class SprinkhaanTest extends PHPUnit\Framework\TestCase
{
    //todo regels implementeren

    //todo regel 1: Een sprinkhaan verplaatst zich door in een
    // rechte lijn een sprong te maken naar een veld meteen
    // achter een andere steen in de richting van de sprong

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


    //todo regel 2: Een sprinkhaan mag zich niet verplaatsen
    // naar het veld waar hij al staat

    //todo regel 3: Een sprinkhaan moet over minimaal 1 steen springen

    //todo regel 4: Een sprinkhaan mag niet naar een bezet veld springen

    //todo regel 5: Een sprinkhaan mag niet over lege velden springen.
    // dit betekent dat alle velden tussen de start- en eindpositie
    // bezet moeten zijn





}