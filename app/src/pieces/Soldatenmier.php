<?php

namespace app\pieces;

use app\Board;
use app\Player;
use app\RulesException;

class Soldatenmier extends Piece
{

    public function move(Board $board, $toPosition): void
    {
        if ($this->position == $toPosition) {
            throw new RulesException("Ant can't move to current position");
        }
        if (array_key_exists($toPosition, $board->getBoardTiles())) {
            throw new RulesException("Ant can't move to occupied position");
        }

        $newPosition = $this->moveOnce($board, $toPosition);
    }

    /**
     * @throws RulesException
     */
    public function moveOnce($board, $toPosition) {
        if ($this->slideOneSpace($board, $this->position, $toPosition)) {
            return $toPosition;
        } else {
            throw new RulesException("Ant can't move more than 1 tile at once");
        }
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


    public function moveIsLegal(Board $board, Player $player, string $fromPosition, string $toPosition)
    {
        // TODO: Implement moveIsLegal() method.
    }
}