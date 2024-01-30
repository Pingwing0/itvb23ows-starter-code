<?php

class KoninginTest extends PHPUnit\Framework\TestCase
{

    public function testGivenALegalTMoveThenTileToMoveCanMoveReturnTrue() {
        $boardTiles = [
            '0,0' => [[0, "Q"]],
            '0,1' => [[1, "B"]],
            '0,-1' => [[0, "B"]],
            '0,2' => [[1, "S"]]];
        $board = new \app\Board($boardTiles);
        $fromPosition = '0,-1';
        $toPosition = '1,-1';
        $koningin = new \app\pieces\Koningin($fromPosition);

        $this->assertTrue($koningin->tileToMoveCanMove($board, $fromPosition, $toPosition));
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
        $koningin = new \app\pieces\Koningin($fromPosition);

        $this->assertTrue($koningin->slideOneSpace($board, $fromPosition, $toPosition));
    }

    public function testGivenLegalTileThenDestinationTileIsEmptyReturnTrue() {
        $boardTiles = [
            '0,0' => [[0, "Q"]],
            '0,1' => [[1, "B"]],
            '0,-1' => [[0, "B"]],
            '0,2' => [[1, "S"]]];
        $fromTile = [[0, "B"]];
        $toPosition = '1,-1';
        $koningin = new \app\pieces\Koningin('0,0');

        $this->assertTrue($koningin->destinationTileIsEmpty($boardTiles, $toPosition, $fromTile));
    }

    public function testGivenTwoQueensThenTileToMoveCanMoveReturnTrue() {
        $boardTiles = [
            '0,0' => [[0, "Q"]],
            '1,0' => [[1, "B"]]];
        $board = new \app\Board($boardTiles);
        $fromPosition = '0,0';
        $toPosition = '0,1';
        $koningin = new \app\pieces\Koningin($fromPosition);

        $this->assertTrue($koningin->tileToMoveCanMove($board, $fromPosition, $toPosition));
    }

    public function testGivenTwoQueensThenSlideReturnTrue() {
        $boardTiles = [
            '0,0' => [[0, "Q"]],
            '0,1' => [[1, "Q"]]];
        $board = new \app\Board($boardTiles);
        $fromPosition = '0,0';
        $toPosition = '1,0';
        $koningin = new \app\pieces\Koningin($fromPosition);

        $this->assertTrue($koningin->slideOneSpace($board, $fromPosition, $toPosition));
    }

    public function testGivenTwoQueensButIllegalMoveThenSlideReturnFalse() {
        $boardTiles = [
            '0,0' => [[0, "Q"]],
            '0,1' => [[1, "Q"]]];
        $board = new \app\Board($boardTiles);
        $fromPosition = '0,0';
        $toPosition = '-1,0';
        $koningin = new \app\pieces\Koningin($fromPosition);

        $this->assertFalse($koningin->slideOneSpace($board, $fromPosition, $toPosition));
    }

    public function testDestinationTileIsEmpty() {
        $boardTiles = [
            '0,0' => [[0, "Q"]],
            '1,0' => [[1, "B"]]];
        $toPosition = '0,1';
        $tile = [[0, "Q"]];
        $koningin = new \app\pieces\Koningin('0,0');

        $this->assertTrue($koningin->destinationTileIsEmpty($boardTiles, $toPosition, $tile));
    }

    public function testGivenMoreThanOneTileDifferenceThenOnlyOneTileDifferenceReturnsFalse() {
        $fromPosition = '-1,0';
        $toPosition = '0,1';
        $koningin = new \app\pieces\Koningin($fromPosition);

        self::assertFalse($koningin->onlyOneTileDifference($fromPosition, $toPosition));
    }

    public function testGivenOneTileDifferenceThenOnlyOneTileDifferenceReturnsTrue() {
        $fromPosition = '0,-1';
        $toPosition = '1,-1';
        $koningin = new \app\pieces\Koningin($fromPosition);

        self::assertTrue($koningin->onlyOneTileDifference($fromPosition, $toPosition));
    }

}
