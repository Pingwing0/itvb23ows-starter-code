<?php

namespace app;

use function PHPUnit\Framework\throwException;

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

        $this->countNoOfStonesToJumpOver($this->getPosition(), $toPosition, $boardTiles);

        if ($this->moveIsAStraightLine($this->getPosition(), $toPosition)) {
            $this->setPosition($toPosition);
        }
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

    /**
     * @throws RulesException
     */
    public function countNoOfStonesToJumpOver($fromPosition, $toPosition, $boardTiles)
    {
        $fromArray = explode(",", $fromPosition);
        $toArray = explode(",", $toPosition);

        $count = 0;

        //horizontal check
        if ($fromArray[1] == $toArray[1]) {
            if ($fromArray[0] < $toArray[0]) {
                //move right from fromPosition
                $position = (int) $fromArray[0] + 1;
                while ($position < $toArray[0]) {
                    $positionToCheck = $position . ',' . $fromArray[1];
                    if (array_key_exists($positionToCheck, $boardTiles)) {
                        $count++;
                        $position++;
                    } else {
                        throw new RulesException("Can't jump over empty space");
                    }
                }
            } else {
                // move right from toPosition
                $position = (int) $toArray[0] + 1;
                while ($position < $fromArray[0]) {
                    $positionToCheck = $position . ',' . $fromArray[1];
                    if (array_key_exists($positionToCheck, $boardTiles)) {
                        $count++;
                        $position++;
                    } else {
                        throw new RulesException("Can't jump over empty space");
                    }
                }
            }
        } else if ($fromArray[0] == $toArray[0]){
            //right down check
            if ($fromArray[1] < $toArray[1]) {
                //move right from fromPosition
                $position = (int) $fromArray[1] + 1;
                while ($position < $toArray[1]) {
                    $positionToCheck = $fromArray[0] . ',' . $position;
                    if (array_key_exists($positionToCheck, $boardTiles)) {
                        $count++;
                        $position++;
                    } else {
                        throw new RulesException("Can't jump over empty space");
                    }
                }
            } else {
                // move right from toPosition
                $position = (int) $toArray[1] + 1;
                while ($position < $fromArray[1]) {
                    $positionToCheck = $fromArray[0] . ',' . $position;
                    if (array_key_exists($positionToCheck, $boardTiles)) {
                        $count++;
                        $position++;
                    } else {
                        throw new RulesException("Can't jump over empty space");
                    }
                }
            }
        } else if (abs($fromArray[0] - $toArray[0]) == abs($fromArray[1] - $toArray[1])) {
            $count = $this->countDiagonalMove($fromPosition, $toPosition, $boardTiles);
        }

        return $count;
    }

    public function countDiagonalMove($fromPosition, $toPosition, $boardTiles) {
        $fromArray = explode(",", $fromPosition);
        $toArray = explode(",", $toPosition);

        $count = 0;

        //left down check
        if ($fromArray[0] < $toArray[0]) {
            //move right from fromPosition
            $posLeft = (int) $fromArray[0] + 1;
            $posRight = (int) $fromArray[1] - 1;
            while ($posRight < $toArray[1]) {
                $positionToCheck = $posLeft. ',' . $posRight;
                if (array_key_exists($positionToCheck, $boardTiles)) {
                    $count++;
                    $posLeft++;
                    $posRight--;
                } else {
                    throw new RulesException("Can't jump over empty space");
                }
            }
        } else {
            // move right from toPosition
            $posLeft = (int) $toArray[0] + 1;
            $posRight = (int) $toArray[1] - 1;
            while ($posLeft < $fromArray[0]) {
                $positionToCheck = $posLeft. ',' . $posRight;
                if (array_key_exists($positionToCheck, $boardTiles)) {
                    $count++;
                    $posLeft++;
                    $posRight--;
                } else {
                    throw new RulesException("Can't jump over empty space");
                }
            }
        }
        return $count;
    }


}