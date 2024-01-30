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

        $currentPosition = $this->moveClockwise($board,$this->getPosition());
        while ($currentPosition != $toPosition
            && $currentPosition != $this->getPosition()) {

        }
        if ($currentPosition == $toPosition) {
            $this->setPosition($toPosition);
        } else {
            throw new RulesException("Can't reach this tile");
        }
    }

    public function moveClockwise(Board $board, $fromPosition): String {
        // check directions clockwise and move to first one possible
        $fromArray = explode(",", $fromPosition);

        foreach ($board->getOffsets() as $offset) {
            $p = (int)$fromArray[0] + $offset[0];
            $q = (int)$fromArray[1] + $offset[1];
            $tryPosition = $p . "," . $q;
            try {
                $this->moveOnce($board, $tryPosition);
                return $tryPosition;
            } catch(RulesException $e) {
                continue;
            }
        }
        throw new RulesException("Can't move anywhere");

    }

    /**
     * @throws RulesException
     */
    public function moveOnce($board, $toPosition) {
        if (array_key_exists($toPosition, $board->getBoardTiles())) {
            throw new RulesException("Ant can't move to occupied position");
        }

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