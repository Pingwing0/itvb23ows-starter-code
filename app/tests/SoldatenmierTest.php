<?php

class SoldatenmierTest extends PHPUnit\Framework\TestCase
{
    //todo regel 2
    // Een verschuiving is een zet zoals de bijenkoningin die mag maken.
    public function testWhenSoldatenmierMovesOnceThenItMovesToANeighbouringField() {
        $fromPosition = '0,0';
        $toPosition = '-1,0';

        $soldatenmier = new Soldatenmier($fromPosition);

        $result = $soldatenmier->getPosition();
        $expectedResult = '-1,0';
        self::assertEquals($expectedResult, $result);

    }

    public function testGivenMoveToNotNeighbourWhenSoldatenmierMovesOnceThenThrowsException() {
        $fromPosition = '0,0';
        $toPosition = '1,1';

        $soldatenmier = new Soldatenmier($fromPosition);

        $this->expectException(\app\RulesException::class);
        $soldatenmier>moveOnce($toPosition);

    }

    //todo regel 1
    // Een soldatenmier verplaatst zich door een onbeperkt aantal keren te verschuiven


    //todo regel 3
    // Een soldatenmier mag zich niet verplaatsen naar het veld waar hij al staat.

    //todo regel 4
    // Een soldatenmier mag alleen verplaatst worden over en naar lege velden.

}