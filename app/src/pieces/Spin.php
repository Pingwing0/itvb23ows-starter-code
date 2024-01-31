<?php

namespace app\pieces;

use app\Board;
use app\pieces\Piece;
use app\Player;
use app\RulesMove;

class Spin extends Piece
{

    public function move(Board $board, $toPosition): bool
    {
        if ($this->position == $toPosition ||
            array_key_exists($toPosition, $board->getBoardTiles())) {
            return false;
        }

        return $this->neighbourOfFromPositionIsNeighbourOfToPosition($board, $this->getPosition(), $toPosition);
    }

    public function neighbourOfFromPositionIsNeighbourOfToPosition(Board $board, $fromPosition, $toPosition): bool
    {
        $neighboursOfFromPosition = $this->getAllLegalNeighboursOfPosition($board, $fromPosition);
        $neighboursOfToPosition = $this->getAllLegalNeighboursOfPosition($board, $toPosition);

        foreach ($neighboursOfFromPosition as $from) {
            foreach ($neighboursOfToPosition as $to) {
                if ($this->pieceIsNeighbourOf($from, $to)) {
                    return true;
                }
            }
        }
        return false;
    }

    public function getAllLegalNeighboursOfPosition(Board $board, String $position): array
    {

        $positionArray = explode(",", $position);
        $allNeighbours = [];

        foreach ($board->getOffsets() as $offset) {
            $p = (int)$positionArray[0] + $offset[0];
            $q = (int)$positionArray[1] + $offset[1];
            $position = $p . "," . $q;
            if (!$this->positionIsOccupied($board, $position)) {
                $allNeighbours[] = $position;
            }
        }
        return $allNeighbours;
    }

    public function positionIsOccupied(Board $board, String $position): bool
    {
        return array_key_exists($position, $board->getBoardTiles());
    }

    public function pieceIsNeighbourOf($pieceOne, $pieceTwo): bool {
        {
            $pieceOne = explode(',', $pieceOne);
            $pieceTwo = explode(',', $pieceTwo);
            return
                ($pieceOne[0] == $pieceTwo[0] && abs($pieceOne[1] - $pieceTwo[1]) == 1) ||
                ($pieceOne[1] == $pieceTwo[1] && abs($pieceOne[0] - $pieceTwo[0]) == 1) ||
                ($pieceOne[0] - $pieceTwo[0] == -1 && $pieceOne[1] - $pieceTwo[1] == 1) ||
                ($pieceOne[0] - $pieceTwo[0] == 1 && $pieceOne[1] - $pieceTwo[1] == -1);
        }
    }



    public function moveIsLegal(Board $board, Player $player, string $fromPosition, string $toPosition): bool
    {
        if (RulesMove::positionIsLegalToMove($board, $player, $fromPosition, $toPosition)) {
            return $this->move($board, $toPosition);
        }
        return false;
    }
}
