<?php

namespace app\pieces;

use app\Board;
use app\pieces\Piece;
use app\Player;

class Spin extends Piece
{

    public function move(Board $board, $toPosition): bool
    {
        if ($this->position == $toPosition ||
            array_key_exists($toPosition, $board->getBoardTiles())) {
            return false;
        }

        return  true;
    }

    public function moveOnce($board, $boardTiles, $fromPosition, $toPosition): string
    {

        if (array_key_exists($toPosition, $boardTiles)) {
            return '';
        }

        if ($this->slideOneSpace($board, $boardTiles, $fromPosition, $toPosition)) {
            return $toPosition;
        } else {
            return '';
        }

    }

    public function slideOneSpace(Board $board, $boardTiles, $from, $to): bool
    {
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





    public function moveIsLegal(Board $board, Player $player, string $fromPosition, string $toPosition)
    {
        // TODO: Implement moveIsLegal() method.
    }
}