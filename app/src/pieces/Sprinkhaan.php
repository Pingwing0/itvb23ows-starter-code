<?php

namespace app\pieces;

use app\Board;
use app\Player;
use app\RulesException;
use app\RulesMove;

class Sprinkhaan
{
    private String $position;

    public function __construct(String $position)
    {
        $this->setPosition($position);
    }

    public function getPosition(): String
    {
        return $this->position;
    }

    public function setPosition($position): void
    {
        $this->position = $position;
    }

    /**
     * @throws RulesException
     */
    public function move($toPosition, $boardTiles): void
    {
        if ($toPosition == $this->getPosition()) {
            throw new RulesException("Tile to move to is the same");
        }

        if(array_key_exists($toPosition, $boardTiles)) {
            throw new RulesException("Tile can't move to occupied space");
        }

        if ($this->moveIsAStraightLine($this->getPosition(), $toPosition)) {
            if ($this->countNoOfStonesToJumpOver($this->getPosition(), $toPosition, $boardTiles) == 0) {
                throw new RulesException("Can't jump over empty space");
            }
            $this->setPosition($toPosition);
        } else {
            throw new RulesException("Move is not a straight line");
        }
    }

    public function moveIsLegal(Board $board, Player $player, $fromPosition, $toPosition): bool
    {
        if (RulesMove::positionIsLegalToMove($board, $player, $fromPosition, $toPosition)) {
            $boardTiles = $board->getBoardTiles();
            try {
                $this->move($toPosition, $boardTiles);
                return true;
            } catch(RulesException $e) {
                return false;
            }
        }
        return false;
    }

    public function moveIsAStraightLine($fromPosition, $toPosition) :bool
    {
        $fromArray = explode(",", $fromPosition);
        $toArray = explode(",", $toPosition);

        // of 1 van beide coordinates is hetzelfde, of ze verschuiven in omgekeerde richting met 1
        return $fromArray[0] == $toArray[0] ||
            $fromArray[1] == $toArray[1] ||
            abs($fromArray[0] - $toArray[0]) == abs($fromArray[1] - $toArray[1]);

    }

    public function countNoOfStonesToJumpOver($fromPosition, $toPosition, $boardTiles): int
    {
        $fromArray = explode(",", $fromPosition);
        $toArray = explode(",", $toPosition);

        $count = 0;

        if ($fromArray[1] == $toArray[1]) {
            //horizontal check
            $count = $this->countHorizontalMove($fromPosition, $toPosition, $boardTiles);
        } else if ($fromArray[0] == $toArray[0]){
            //right down check
            $count = $this->countDiagonalRightDownMove($fromPosition, $toPosition, $boardTiles);
        } else if (abs($fromArray[0] - $toArray[0]) == abs($fromArray[1] - $toArray[1])) {
            //left down check
            $count = $this->countDiagonalLeftDownMove($fromPosition, $toPosition, $boardTiles);
        }

        return $count;
    }

    public function countHorizontalMove($fromPosition, $toPosition, $boardTiles): int
    {
        $fromArray = explode(",", $fromPosition);
        $toArray = explode(",", $toPosition);

        if ($fromArray[0] < $toArray[0]) {
            //move right from fromPosition
            $count = $this->countHorizontalLeftToRight($fromArray, $toArray, $boardTiles);
        } else {
            // move right from toPosition
            $count = $this->countHorizontalLeftToRight($toArray, $fromArray, $boardTiles);
        }

        return $count;
    }

    private function countHorizontalLeftToRight(array $leftPosition, array $rightPosition, $boardTiles): int
    {
        $count = 0;

        $position = (int) $leftPosition[0] + 1;
        while ($position < $rightPosition[0]) {
            $positionToCheck = $position . ',' . $leftPosition[1];
            if (array_key_exists($positionToCheck, $boardTiles)) {
                $count++;
                $position++;
            } else {
                return 0;
            }
        }
        return $count;
    }

    public function countDiagonalRightDownMove($fromPosition, $toPosition, $boardTiles): int
    {
        $fromArray = explode(",", $fromPosition);
        $toArray = explode(",", $toPosition);

        if ($fromArray[1] < $toArray[1]) {
            //move right from fromPosition
            $count = $this->countDiagonalRightDownLeftToRight($fromArray, $toArray, $boardTiles);
        } else {
            // move right from toPosition
            $count = $this->countDiagonalRightDownLeftToRight($toArray, $fromArray, $boardTiles);
        }

        return $count;
    }

    private function countDiagonalRightDownLeftToRight(array $leftPosition, array $rightPosition, $boardTiles): int
    {
        $count = 0;

        $position = (int) $leftPosition[1] + 1;
        while ($position < $rightPosition[1]) {
            $positionToCheck = $leftPosition[0] . ',' . $position;
            if (array_key_exists($positionToCheck, $boardTiles)) {
                $count++;
                $position++;
            } else {
                return 0;
            }
        }
        return $count;
    }


    public function countDiagonalLeftDownMove($fromPosition, $toPosition, $boardTiles): int
    {
        $fromArray = explode(",", $fromPosition);
        $toArray = explode(",", $toPosition);

        if ($fromArray[0] < $toArray[0]) {
            //move right from fromPosition
            $count = $this->countDiagonalLeftDownLeftToRight($fromArray, $toArray, $boardTiles);
        } else {
            // move right from toPosition
            $count = $this->countDiagonalLeftDownLeftToRight($toArray, $fromArray, $boardTiles);
        }
        return $count;
    }

    private function countDiagonalLeftDownLeftToRight(array $leftPosition, array $rightPosition, $boardTiles): int
    {
        $count = 0;

        $posLeft = (int) $leftPosition[0] + 1;
        $posRight = (int) $leftPosition[1] - 1;
        while ($posRight > $rightPosition[1]) {
            $positionToCheck = $posLeft. ',' . $posRight;
            if (array_key_exists($positionToCheck, $boardTiles)) {
                $count++;
                $posLeft++;
                $posRight--;
            } else {
                return 0;
            }
        }
        return $count;
    }
}