<?php

use app\RulesPlay;
use app\Board;

class RulesPlayTest extends PHPUnit\Framework\TestCase
{
    public function testGivenLegalPlayPositionPositionIsLegalToPlayReturnTrue() {
        $board = new Board();
        $hand = ["Q" => 1, "B" => 2, "S" => 2, "A" => 3, "G" => 3];
        $playerNumber = 0;
        $toPosition = '0,0';

        $this->assertTrue(RulesPlay::positionIsLegalToPlay($toPosition, $playerNumber, $hand, $board));
    }

    public function testGivenQueenBeeIsBeingPlayedOnTurnFourWithLegalMoveThenPositionIsLegalToPlayReturnTrue() {
        $boardTiles = [
            '0,0' => [[0, "A"]],
            '0,1' => [[1, "B"]],
            '0,-1' => [[0, "B"]],
            '0,2' => [[1, "S"]],
            '0,-2' => [[0, "A"]],
            '0,3' => [[1, "B"]]];

        $board = new Board($boardTiles);
        $hand = ["Q" => 1, "B" => 1, "S" => 2, "A" => 1, "G" => 3];
        $playerNumber = 0;
        $toPosition = '0,-3';
        $pieceToPlay = "Q";

        $this->assertTrue(RulesPlay::positionIsLegalToPlay($toPosition, $playerNumber, $hand, $board, $pieceToPlay));
    }

    public function testQueenBeeIsPlayedBeforeTurnFour() {
        $hand = ["B" => 2, "S" => 2, "A" => 1, "G" => 3];
        $this->assertTrue(RulesPlay::queenBeeIsPlayedBeforeTurnFour($hand, "B"));
    }

    public function testItIsTurnFourAndQueenBeeIsNotYetPlayed() {
        $hand = ["Q" => 1, "B" => 1, "S" => 2, "A" => 1, "G" => 3];
        $this->assertTrue(RulesPlay::itIsTurnFourAndQueenBeeIsNotYetPlayed($hand));
    }
}
