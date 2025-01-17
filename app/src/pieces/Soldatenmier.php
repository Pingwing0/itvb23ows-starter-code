<?php

namespace app\pieces;

use app\Board;
use app\Player;
use app\RulesMove;

class Soldatenmier extends Piece
{

    public function move(Board $board, $toPosition): bool
    {
        if ($this->position == $toPosition ||
            array_key_exists($toPosition, $board->getBoardTiles()))
        {
            return false;
        }

        if ($this->canMoveToToPosition($board, $toPosition)){
            $this->setPosition($toPosition);
            return true;
        }

        return false;
    }

    public function canMoveToToPosition(Board $board, $toPosition): bool
    {
        $newBoardTiles = $board->getBoardTiles();
        $oldPosition = $this->getPosition();
        $tile = $newBoardTiles[$oldPosition];
        $currentPosition = $this->moveClockwise($board, $newBoardTiles, $this->getPosition(), '');

        // zolang het doel nog niet is bereikt, of zolang hij nog geen cirkel heeft gemaakt
        while ($currentPosition != $toPosition && $currentPosition != $this->getPosition()) {

            unset($newBoardTiles[$oldPosition]);
            $newBoardTiles[$currentPosition] = $tile;
            $oldPositionNotToCheck = $oldPosition;
            $oldPosition = $currentPosition;
            if ($this->moveClockwise($board, $newBoardTiles, $currentPosition, $oldPositionNotToCheck) != '') {
                $currentPosition = $this->moveClockwise(
                    $board, $newBoardTiles, $currentPosition, $oldPositionNotToCheck
                );
            } else {
                return false;
            }

        }
        return $currentPosition == $toPosition;
    }


    public function moveClockwise(Board $board, $boardTiles, $fromPosition, $oldPositionNotToCheck): String {
        // check directions clockwise and move to first one possible

        $fromArray = explode(",", $fromPosition);

        foreach ($board->getOffsets() as $offset) {
            $p = (int)$fromArray[0] + $offset[0];
            $q = (int)$fromArray[1] + $offset[1];
            $tryPosition = $p . "," . $q;

            if ($tryPosition == $oldPositionNotToCheck) { // niet een oude positie opnieuw bezoeken!
                continue;
            }

            if ($this->moveOnce($board, $boardTiles, $fromPosition, $tryPosition) != '') {
                return $tryPosition;
            }
        }
        return '';

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


    public function moveIsLegal(Board $board, Player $player, string $fromPosition, string $toPosition): bool
    {
        if (RulesMove::positionIsLegalToMove($board, $player, $fromPosition, $toPosition)) {
            return $this->move($board, $toPosition);
        }
        return false;
    }
}
