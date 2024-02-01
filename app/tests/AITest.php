<?php

use app\ai\Ai;

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
        $curlRequestMock = $this->getMockBuilder(CurlRequest::class)
            ->onlyMethods(['execute'])
            ->getMock();

        $response = '["play", "Q", "-1,1"]';
        $curlRequestMock->method('execute')->willReturn($response);

        $ai = new Ai();

        $result = $ai->postToApi(['postData']);
        $expectedResult = ['play',  'Q', '-1,1'];
        self::assertEquals($expectedResult, $result);
    }

}