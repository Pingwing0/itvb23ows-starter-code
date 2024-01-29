<?php

namespace app;

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

    public function move($toPosition): void
    {
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


}