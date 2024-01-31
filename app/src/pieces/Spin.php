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





    public function moveIsLegal(Board $board, Player $player, string $fromPosition, string $toPosition)
    {
        // TODO: Implement moveIsLegal() method.
    }
}