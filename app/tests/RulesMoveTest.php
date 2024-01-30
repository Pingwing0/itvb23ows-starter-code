<?php

use app\RulesMove;

class RulesMoveTest extends PHPUnit\Framework\TestCase
{
    public function testGivenLegalMovePositionPositionIsLegalToMoveReturnTrue() {
        $boardTiles = [
            '0,0' => [[0, "Q"]],
            '0,1' => [[1, "B"]],
            '0,-1' => [[0, "B"]],
            '0,2' => [[1, "S"]]];
        $board = new \app\Board($boardTiles);
        $hand = ["B" => 2, "S" => 2, "A" => 3, "G" => 3];
        $player = new \app\Player(0, $hand);
        $fromPosition = '0,-1';
        $toPosition = '1,-1';

        $this->assertTrue(RulesMove::positionIsLegalToMove($board, $player, $fromPosition, $toPosition));
    }

    public function testGivenIllegalMovePositionPositionIsLegalToMoveReturnFalse() {
        $boardTiles = [
            '0,0' => [[0, "Q"]],
            '0,1' => [[1, "Q"]]];
        $board = new \app\Board($boardTiles);
        $hand = ["B" => 2, "S" => 2, "A" => 3, "G" => 3];
        $player = new \app\Player(0, $hand);
        $fromPosition = '0,0';
        $toPosition = '-1,0';

        $this->assertFalse(RulesMove::positionIsLegalToMove($board, $player, $fromPosition, $toPosition));
    }

    public function testGivenLegalTileThereIsATileToMoveLegallyReturnTrue() {
        $boardTiles = [
            '0,0' => [[0, "Q"]],
            '0,1' => [[1, "B"]],
            '0,-1' => [[0, "B"]],
            '0,2' => [[1, "S"]]];
        $hand = ["B" => 2, "S" => 2, "A" => 3, "G" => 3];
        $playerNumber = 0;
        $fromPosition = '0,-1';

        $this->assertTrue(RulesMove::thereIsATileToMoveLegally($boardTiles, $hand, $playerNumber, $fromPosition));
    }

    public function testBoardPositionIsNotEmpty() {
        $boardTiles = [
            '0,0' => [[0, "Q"]],
            '0,1' => [[1, "B"]],
            '0,-1' => [[0, "B"]],
            '0,2' => [[1, "S"]]];
        $position = '0,1';

        $this->assertTrue(RulesMove::boardPositionIsNotEmpty($boardTiles, $position));
    }

    public function testTileIsOwnedByPlayer() {
        $boardTiles = [
            '0,0' => [[0, "Q"]],
            '0,1' => [[1, "B"]],
            '0,-1' => [[0, "B"]],
            '0,2' => [[1, "S"]]];
        $position = '0,1';

        $this->assertTrue(RulesMove::tileIsOwnedByPlayer($boardTiles, $position, 1));
    }

    public function testHandDoesNotContainQueen() {
        $hand = ["B" => 2, "S" => 2, "A" => 3, "G" => 3];
        $this->assertTrue(RulesMove::handDoesNotContainQueen($hand));
    }

    public function testGivenLegalMoveMoveWontSplitHiveReturnTrue() {
        $boardTiles = [
            '0,0' => [[0, "Q"]],
            '0,1' => [[1, "B"]],
            '0,-1' => [[0, "B"]],
            '0,2' => [[1, "S"]]];
        $board = new \app\Board($boardTiles);
        $fromPosition = '0,-1';
        $toPosition = '1,-1';

        $this->assertTrue(RulesMove::tileMoveWontSplitHive($board, $fromPosition, $toPosition));
    }

    public function testGivenIllegalMoveMoveWontSplitHiveReturnFalse() {
        $boardTiles = [
            '0,0' => [[0, "Q"]],
            '0,1' => [[1, "Q"]]];
        $board = new \app\Board($boardTiles);
        $fromPosition = '0,0';
        $toPosition = '-1,0';

        $this->assertFalse(RulesMove::tileMoveWontSplitHive($board, $fromPosition, $toPosition));
    }



    public function testGivenTwoQueensFirstThenLegalToMoveReturnTrue() {
        $boardTiles = [
            '0,0' => [[0, "Q"]],
            '1,0' => [[1, "Q"]]
        ];
        $board = new \app\Board($boardTiles);
        $hand = ["B" => 2, "S" => 2, "A" => 3, "G" => 3];
        $player = new \app\Player(0, $hand);
        $fromPosition = '0,0';
        $toPosition = '0,1';

        $this->assertTrue(RulesMove::positionIsLegalToMove($board, $player, $fromPosition, $toPosition));
    }



}
