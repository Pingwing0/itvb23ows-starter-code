<?php

use app\ai\Ai;
use app\ai\CurlRequest;
use app\Board;
use app\Player;

class AiTest extends PHPUnit\Framework\TestCase
{
    //todo ai implementeren in game
    // voor nu ai = player 2 (black)
    // stappen:
    //  play
    //  move
    //  pass
    // choose player?
    // when move done -> send to ai
    // ai -> do move

    public function testWhenAiResponseToPostThenResponseIsArray() {
        $curlRequestMock = $this->getMockBuilder(CurlRequest::class)
            ->onlyMethods(['execute', 'setOption', 'close'])
            ->disableOriginalConstructor()
            ->getMock('');

        $response = '["play", "Q", "-1,1"]';
        $curlRequestMock->method('execute')->willReturn($response);
        $curlRequestMock->method('setOption');
        $curlRequestMock->method('close');

        $ai = new Ai();

        $result = $ai->postToApi(['postData'], $curlRequestMock);
        $expectedResult = ['play',  'Q', '-1,1'];
        self::assertEquals($expectedResult, $result);
    }

    public function testWhenPreparingToSendApiDataThenDataToSendContainsPlayerHandsAndBoard() {
        $boardTiles = ['0,0' => [[0, "Q"]]];
        $board = new Board($boardTiles);
        $playerOne = new Player(0, ['S' => 1]);
        $playerTwo = new Player(1, ["Q" => 1, "B" => 2]);
        $currentPlayer = $playerOne;

        $ai = new Ai();

        $result = $ai->getDataToSend($board, $playerOne, $playerTwo, $currentPlayer);
        $expectedResult = [
            "move_number" => 0,
            "hand" => [
                ['S' => 1],
                ["Q" => 1, "B" => 2]
            ],
            "board" => [
                '0,0' => [[0, "Q"]]
            ]
        ];
        self::assertEquals($expectedResult, $result);
    }

}