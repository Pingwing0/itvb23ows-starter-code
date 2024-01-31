<?php

namespace app\pieces;

use app\Board;
use app\Player;

abstract class Piece
{
    protected String $position;

    public function __construct($position)
    {
        $this->position = $position;
    }

    public function getPosition(): String
    {
        return $this->position;
    }

    public function setPosition($position): void
    {
        $this->position = $position;
    }

    public function getPossibleMovePositions(String $fromPosition, Player $player, Board $board): array
    {
        $offsets = $board->getOffsets();
        $boardTiles = $board->getBoardTiles();
        $possibleMovePositions = [];

        foreach ($offsets as $offset) {
            foreach (array_keys($boardTiles) as $position) {
                $positionArray = explode(',', $position);
                $possiblePosition =
                    (int)$offset[0] + (int)$positionArray[0] . ',' .
                    (int)$offset[1] + (int)$positionArray[1];
                if ($this->moveIsLegal($board, $player, $fromPosition, $possiblePosition)) {
                    $possibleMovePositions[] = $possiblePosition;
                }
            }
        }
        return array_unique($possibleMovePositions);
    }

    abstract public function moveIsLegal(Board $board, Player $player, String $fromPosition, String $toPosition);

}
