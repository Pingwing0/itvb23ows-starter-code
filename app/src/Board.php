<?php

namespace app;

use app\pieces\Koningin;
use app\pieces\Sprinkhaan;

class Board
{
    // Board bestaat alleen uit tiles, niet uit alle beschikbare plekken
    private array $boardTiles;
    private array $offsets = [[0, 1], [0, -1], [1, 0], [-1, 0], [-1, 1], [1, -1]];

    public function __construct($boardTiles = [])
    {
        $this->boardTiles = $boardTiles;
    }

    /**
     * Dit representeert de hexagon, de randen waar eventueel een tegel aankan.
     * @return array|array[]
     */
    public function getOffsets(): array
    {
        return $this->offsets;
    }

    /**
     * boardTile in boardTiles = [String position, [[int playerNumber, String $piece],[...],] ]
     * @return array
     */
    public function getBoardTiles(): array
    {
        return $this->boardTiles;
    }

    public function setBoardTiles(array $boardTiles): void
    {
        $this->boardTiles = $boardTiles;
    }

    public function addPiece(String $piece, int $playerNumber, String $toPosition): void
    {
        $this->boardTiles[$toPosition] = [[$playerNumber, $piece]];
    }

    public function movePiece($boardTiles, $fromPosition, $toPosition): void
    {
        $key = array_key_last($boardTiles[$fromPosition]);
        $tile = $boardTiles[$fromPosition][$key];

        if (isset($boardTiles[$toPosition])) {
            $boardTiles[$toPosition][] = $tile;
        } else {
            $boardTiles[$toPosition] = [$tile];
        }
        $boardTiles = $this->removePiece($boardTiles, $fromPosition);
        $this->setBoardTiles($boardTiles);
    }

    public function removePiece($boardTiles, $position): array
    {
        if (count($boardTiles[$position]) > 1) {
            array_pop($boardTiles[$position]);
        } else {
            unset($boardTiles[$position]);
        }
        return $boardTiles;
    }

    //todo logica van util

    public function pieceHasNeighbour($boardTiles, $pieceOne): bool
    {
        foreach (array_keys($boardTiles) as $pieceTwo) {
            if ($this->pieceIsNeighbourOf($pieceOne, $pieceTwo)) {
                return true;
            }
        }
        return false;
    }

    public function pieceIsNeighbourOf($pieceOne, $pieceTwo): bool {
        {
            $pieceOne = explode(',', $pieceOne);
            $pieceTwo = explode(',', $pieceTwo);
            return
                ($pieceOne[0] == $pieceTwo[0] && abs($pieceOne[1] - $pieceTwo[1]) == 1) ||
                ($pieceOne[1] == $pieceTwo[1] && abs($pieceOne[0] - $pieceTwo[0]) == 1) ||
                ((int)$pieceOne[0] + (int)$pieceOne[1] == (int)$pieceTwo[0] + (int)$pieceTwo[1]);
        }
    }

    public function neighboursOfPieceAreTheSameColor($player, $pieceOne): bool
    {
        foreach ($this->getBoardTiles() as $pieceTwo => $st) {
            //todo wat is st?
            if (!$st) {
                continue;
            }
            //todo wat is c?
            $c = $st[count($st) - 1][0];
            if ($c != $player && $this->pieceIsNeighbourOf($pieceOne, $pieceTwo)) {
                return false;
            }
        }
        return true;
    }

    public function getTilesFromPlayer($playerNumber): array
    {
        $boardTiles = $this->getBoardTiles();
        $playerBoardTiles = [];

        foreach ($boardTiles as $position => $tiles) {
            foreach ($tiles as $tile) {
                if ($tile[0] == $playerNumber) {
                    $playerBoardTiles[$position] = $tiles;
                }
            }
        }
        return $playerBoardTiles;
    }

    public function getPossiblePlayPositions($playerNumber, $hand): array {
        $offsets = $this->getOffsets();
        $boardTiles = $this->getBoardTiles();
        $possiblePlayPositions = [];

        foreach ($offsets as $offset) {
            foreach (array_keys($boardTiles) as $position) {
                $positionArray = explode(',', $position);
                $possiblePosition = ((int)$offset[0] + (int)$positionArray[0]).','.((int)$offset[1] + (int)$positionArray[1]);
                if (RulesPlay::positionIsLegalToPlay($possiblePosition, $playerNumber, $hand, $this)) {
                    $possiblePlayPositions[] = $possiblePosition;
                }
            }
        }
        $possiblePlayPositions = array_unique($possiblePlayPositions);
        if (!count($possiblePlayPositions)) {
            $possiblePlayPositions[] = '0,0';
        }

        return $possiblePlayPositions;
    }

    public function getPossibleMovePositions(String $fromPosition, Player $player): array
    {
        $possibleMovePositions = [];
        $boardTiles = $this->getBoardTiles();
        $piece = $boardTiles[$fromPosition][0][1];

        if ($piece == 'Q') {
            $koningin = new Koningin($fromPosition);
            $possibleMovePositions = $koningin->getPossibleMovePositions($fromPosition, $player, $this);
        }
        if ($piece == 'G') {
            $sprinkhaan = new Sprinkhaan($fromPosition);
            $possibleMovePositions = $sprinkhaan->getPossibleMovePositions($fromPosition, $player, $this);
        }
        return array_unique($possibleMovePositions);
    }

}
