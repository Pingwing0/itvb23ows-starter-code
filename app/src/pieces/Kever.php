<?php

namespace app\pieces;

use app\Board;
use app\RulesMove;

class Kever extends Koningin
{
    public function move(Board $board, String $fromPosition, String $toPosition): bool
    {
        return self::positionsAreNotTheSame($fromPosition, $toPosition) &&
            self::slideOneSpace($board, $fromPosition, $toPosition);
    }

    public function moveIsLegal($board, $player, $fromPosition, $toPosition): bool
    {
        return $this->move($board, $fromPosition, $toPosition) &&
            RulesMove::positionIsLegalToMove($board, $player, $fromPosition, $toPosition);
    }

    public function slideOneSpace(Board $board, String $from, String $to): bool
    {
        $boardTiles = $board->getBoardTiles();
        $stack = $boardTiles[$from];
        if (count($stack) == 1) {
            unset($boardTiles[$from]);
        } else {
            unset($boardTiles[$from][count($stack) - 1]);
        }

        return $board->pieceHasNeighbour($boardTiles, $to) &&
            $board->pieceIsNeighbourOf($from, $to) &&
            self::onlyOneTileDifference($from, $to);
    }

}
