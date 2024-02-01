<?php

class AITest extends PHPUnit\Framework\TestCase
{
    //todo ai mocken

    //todo ai implementeren in game
    // voor nu ai = player 2 (black)
    // stappen:
    //  play
    //  move
    //  pass
    // json sturen
    // json ontvangen
    // choose player?

    // response is like: [
    //    "play",
    //    "Q",
    //    "-1,-1"
    //]

    public function testWhenAiResponseToPostThenResponseIsArray() {
        $ai = new Ai();
        $response = "['play', 'Q', '-1,1']";

        $result = $ai->decodeResponseToArray($response);
        $expectedResult = ['play',  'Q', '-1,1'];

        self::assertEquals($expectedResult, $result);
    }

}