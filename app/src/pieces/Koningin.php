<?php

namespace app\pieces;

use app\Board;
use app\RulesMove;

class Koningin extends Piece
{
    public function moveIsLegal($board, $player, $fromPosition, $toPosition): bool
    {
        return $this->tileToMoveCanMove($board, $fromPosition, $toPosition) &&
            RulesMove::positionIsLegalToMove($board, $player, $fromPosition, $toPosition);
    }

    public function tileToMoveCanMove(Board $board, $fromPosition, $toPosition): bool
    {
        $newBoardTiles = $board->getBoardTiles();
        $fromTile = array_pop($newBoardTiles[$fromPosition]);
        unset($newBoardTiles[$fromPosition]);

        return self::positionsAreNotTheSame($fromPosition, $toPosition) &&
            self::destinationTileIsEmpty($newBoardTiles, $toPosition, $fromTile) &&
            self::tileIsAbleToSlide($board, $fromPosition, $toPosition);
    }

    private function positionsAreNotTheSame($fromPosition, $toPosition): bool
    {
        return $fromPosition != $toPosition;
    }

    public function destinationTileIsEmpty($boardTiles, $toPosition, $tile): bool
    {
        return !isset($boardTiles[$toPosition]);
    }

    private function tileIsAbleToSlide($board, $fromPosition, $toPosition): bool
    {
        return self::slideOneSpace($board, $fromPosition, $toPosition);
    }

    public function slideOneSpace(Board $board, $from, $to): bool
    {
        $boardTiles = $board->getBoardTiles();
        unset($boardTiles[$from]);

        return $board->pieceHasNeighbour($boardTiles, $to) &&
            $board->pieceIsNeighbourOf($from, $to) &&
            self::onlyOneTileDifference($from, $to);
    }

    public function onlyOneTileDifference($from, $to): bool {
        $fromArray = explode(",", $from);
        $toArray = explode(",", $to);

        return (abs($fromArray[0] - $toArray[0]) == 1 && $fromArray[1] == $toArray[1]) ||
            (abs($fromArray[1] - $toArray[1]) == 1 && $fromArray[0] == $toArray[0]) ||
            ($fromArray[0] - $toArray[0] == -1 && $fromArray[1] - $toArray[1] == 1) ||
            ($fromArray[0] - $toArray[0] == 1 && $fromArray[1] - $toArray[1] == -1);
    }

}
