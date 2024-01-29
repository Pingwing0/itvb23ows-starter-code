<?php

namespace app\pieces;

use app\RulesException;
use app\RulesMove;

class Koningin
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

    public function moveIsLegal($board, $player, $fromPosition, $toPosition): bool
    {

        return RulesMove::tileToMoveCanMove($board, $fromPosition, $toPosition) &&
            RulesMove::positionIsLegalToMove($board, $player, $fromPosition, $toPosition);
    }

}