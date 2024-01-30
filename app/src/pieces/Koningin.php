<?php

namespace app\pieces;

use app\Board;
use app\RulesException;
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
            self::tileIsAbleToSlide($fromTile, $board, $fromPosition, $toPosition);
    }

    private function positionsAreNotTheSame($fromPosition, $toPosition): bool
    {
        try {
            if ($fromPosition == $toPosition) {
                throw new RulesException("Tile must move");
            }
        } catch(RulesException $e) {
            echo $e->errorMessage();
            return false;
        }
        return true;
    }

    public function destinationTileIsEmpty($boardTiles, $toPosition, $tile): bool
    {
        //todo dit hoort ook bij de kever (die nog niet geimplementeerd is)
        try {
            if (isset($boardTiles[$toPosition]) && $tile[1] != "B"){
                throw new RulesException("Tile is not empty");
            }
        } catch(RulesException $e) {
            echo $e->errorMessage();
            return false;
        }
        return true;
    }

    private function tileIsAbleToSlide($tile, $board, $fromPosition, $toPosition): bool
    {
        try{
            if (($tile[1] == "Q" || $tile[1] == "B") && !self::slideOneSpace($board, $fromPosition, $toPosition)) {
                throw new RulesException("Tile is not able to slide");
            }
        } catch(RulesException $e) {
            echo $e->errorMessage();
            return false;
        }
        return true;
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
