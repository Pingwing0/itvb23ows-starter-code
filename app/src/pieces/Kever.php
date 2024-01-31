<?php

namespace app\pieces;

use app\RulesMove;

class Kever extends Koningin
{
    public function move($board, $fromPosition, $toPosition): bool
    {
        $newBoardTiles = $board->getBoardTiles();
        unset($newBoardTiles[$fromPosition]);

        return self::positionsAreNotTheSame($fromPosition, $toPosition) &&
            self::slideOneSpace($board, $fromPosition, $toPosition);
    }

    public function moveIsLegal($board, $player, $fromPosition, $toPosition): bool
    {
        return $this->move($board, $fromPosition, $toPosition) &&
            RulesMove::positionIsLegalToMove($board, $player, $fromPosition, $toPosition);
    }

}