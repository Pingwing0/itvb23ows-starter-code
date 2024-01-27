<?php

use app\Rules;

class RulesTest extends PHPUnit\Framework\TestCase
{
    public function testTest(): void
    {
        $s = "abc";                    // arrange
        $len = strlen($s);             // act
        $this->assertEquals(3, $len);  // assert
    }

    public function testGivenLegalPlayPositionPositionIsLegalToPlayReturnTrue() {
        $board = new \app\Board();
        $hand = ["Q" => 1, "B" => 2, "S" => 2, "A" => 3, "G" => 3];
        $playerNumber = 0;
        $toPosition = '0,0';

        $this->assertTrue(Rules::positionIsLegalToPlay($toPosition, $playerNumber, $hand, $board));
    }

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

        $this->assertTrue(Rules::positionIsLegalToMove($board, $player, $fromPosition, $toPosition));
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

        $this->assertFalse(Rules::positionIsLegalToMove($board, $player, $fromPosition, $toPosition));
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

        $this->assertTrue(Rules::thereIsATileToMoveLegally($boardTiles, $hand, $playerNumber, $fromPosition));
    }

    public function testBoardPositionIsNotEmpty() {
        $boardTiles = [
            '0,0' => [[0, "Q"]],
            '0,1' => [[1, "B"]],
            '0,-1' => [[0, "B"]],
            '0,2' => [[1, "S"]]];
        $position = '0,1';

        $this->assertTrue(Rules::boardPositionIsNotEmpty($boardTiles, $position));
    }

    public function testTileIsOwnedByPlayer() {
        $boardTiles = [
            '0,0' => [[0, "Q"]],
            '0,1' => [[1, "B"]],
            '0,-1' => [[0, "B"]],
            '0,2' => [[1, "S"]]];
        $position = '0,1';

        $this->assertTrue(Rules::tileIsOwnedByPlayer($boardTiles, $position, 1));
    }

    public function testHandDoesNotContainQueen() {
        $hand = ["B" => 2, "S" => 2, "A" => 3, "G" => 3];
        $this->assertTrue(Rules::handDoesNotContainQueen($hand));
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

        $this->assertTrue(Rules::tileMoveWontSplitHive($board, $fromPosition, $toPosition));
    }

    public function testGivenIllegalMoveMoveWontSplitHiveReturnFalse() {
        $boardTiles = [
            '0,0' => [[0, "Q"]],
            '0,1' => [[1, "Q"]]];
        $board = new \app\Board($boardTiles);
        $fromPosition = '0,0';
        $toPosition = '-1,0';

        $this->assertFalse(Rules::tileMoveWontSplitHive($board, $fromPosition, $toPosition));
    }

    public function testGivenALegalTMoveThenTileToMoveCanMoveReturnTrue() {
        $boardTiles = [
            '0,0' => [[0, "Q"]],
            '0,1' => [[1, "B"]],
            '0,-1' => [[0, "B"]],
            '0,2' => [[1, "S"]]];
        $board = new \app\Board($boardTiles);
        $fromPosition = '0,-1';
        $toPosition = '1,-1';

        $this->assertTrue(Rules::tileToMoveCanMove($board, $fromPosition, $toPosition));
    }

    public function testGivenLegalTileThenSlideReturnTrue() {
        $boardTiles = [
            '0,0' => [[0, "Q"]],
            '0,1' => [[1, "B"]],
            '0,-1' => [[0, "B"]],
            '0,2' => [[1, "S"]]];
        $board = new \app\Board($boardTiles);
        $fromPosition = '0,-1';
        $toPosition = '1,-1';

        $this->assertTrue(Rules::slideOneSpace($board, $fromPosition, $toPosition));
    }

    public function testGivenLegalTileThenDestinationTileIsEmptyReturnTrue() {
        $boardTiles = [
            '0,0' => [[0, "Q"]],
            '0,1' => [[1, "B"]],
            '0,-1' => [[0, "B"]],
            '0,2' => [[1, "S"]]];
        $fromTile = [[0, "B"]];
        $toPosition = '1,-1';

        $this->assertTrue(Rules::destinationTileIsEmpty($boardTiles, $toPosition, $fromTile));
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

        $this->assertTrue(Rules::positionIsLegalToMove($board, $player, $fromPosition, $toPosition));
    }

    public function testGivenTwoQueensThenTileToMoveCanMoveReturnTrue() {
        $boardTiles = [
            '0,0' => [[0, "Q"]],
            '1,0' => [[1, "B"]]];
        $board = new \app\Board($boardTiles);
        $fromPosition = '0,0';
        $toPosition = '0,1';

        $this->assertTrue(Rules::tileToMoveCanMove($board, $fromPosition, $toPosition));
    }

    public function testGivenTwoQueensThenSlideReturnTrue() {
        $boardTiles = [
            '0,0' => [[0, "Q"]],
            '0,1' => [[1, "Q"]]];
        $board = new \app\Board($boardTiles);
        $fromPosition = '0,0';
        $toPosition = '1,0';

        $this->assertTrue(Rules::slideOneSpace($board, $fromPosition, $toPosition));
    }

    public function testGivenTwoQueensButIllegalMoveThenSlideReturnFalse() {
        $boardTiles = [
            '0,0' => [[0, "Q"]],
            '0,1' => [[1, "Q"]]];
        $board = new \app\Board($boardTiles);
        $fromPosition = '0,0';
        $toPosition = '-1,0';

        $this->assertFalse(Rules::slideOneSpace($board, $fromPosition, $toPosition));
    }

    public function testDestinationTileIsEmpty() {
        $boardTiles = [
            '0,0' => [[0, "Q"]],
            '1,0' => [[1, "B"]]];
        $toPosition = '0,1';
        $tile = [[0, "Q"]];

        $this->assertTrue(Rules::destinationTileIsEmpty($boardTiles, $toPosition, $tile));
    }



}
