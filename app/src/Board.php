<?php

namespace app;

use app\pieces\Koningin;
use app\pieces\Soldatenmier;
use app\pieces\Spin;
use app\pieces\Sprinkhaan;

class Board
{
    // Board bestaat alleen uit tiles, niet uit alle beschikbare plekken
    private array $boardTiles;
    private array $offsets = [[1, -1], [1, 0], [0, 1], [-1, 1], [-1, 0], [0, -1]];

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
                ($pieceOne[0] - $pieceTwo[0] == -1 && $pieceOne[1] - $pieceTwo[1] == 1) ||
                ($pieceOne[0] - $pieceTwo[0] == 1 && $pieceOne[1] - $pieceTwo[1] == -1);
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
                $possiblePosition = (
                    (int)$offset[0] + (int)$positionArray[0]).','.
                    ((int)$offset[1] + (int)$positionArray[1]);
                if (RulesPlay::positionIsLegalToPlay($possiblePosition, $playerNumber, $hand, $this)) {
                    $possiblePlayPositions[] = $possiblePosition;
                }
            }
        }
        $possiblePlayPositions = array_unique($possiblePlayPositions);
        if (!count($boardTiles)) {
            $possiblePlayPositions[] = '0,0';
        }

        return $possiblePlayPositions;
    }

    public function getPossibleMovePositions(String $fromPosition, Player $player): array
    {
        $possibleMovePositions = [];
        $boardTiles = $this->getBoardTiles();
        $pieceLetter = $boardTiles[$fromPosition][0][1];
        $piece = null;

        if ($pieceLetter == 'Q') {
            $piece = new Koningin($fromPosition);
        }
        if ($pieceLetter == 'G') {
            $piece = new Sprinkhaan($fromPosition);
        }
        if ($pieceLetter == 'A') {
            $piece = new Soldatenmier($fromPosition);
        }
        if ($pieceLetter == 'S') {
            $piece = new Spin($fromPosition);
        }

        if ($piece) {
            $possibleMovePositions = $piece->getPossibleMovePositions($fromPosition, $player, $this);
        }

        return array_unique($possibleMovePositions);
    }

    public function koninginIsSurrounded(int $playerNumber) {
        $boardTiles = $this->getBoardTiles();
        $koninginPosition = $this->getKoninginPosition($boardTiles, $playerNumber);

        if ($koninginPosition != '') {
            return $this->allSurroundingTilesAreOccupied($boardTiles, $koninginPosition);
        }
        return false;
    }

    public function getKoninginPosition(array $boardTiles, int $playerNumber) {
        foreach ($boardTiles as $position => $tile) {
            if ($tile[0][0] == $playerNumber && $tile[0][1] == 'Q') {
                return $position;
            }
        }
        return '';
    }

    public function allSurroundingTilesAreOccupied(array $boardTiles, String $position)
    {
        $fromArray = explode(",", $position);

        foreach ($this->getOffsets() as $offset) {
            $p = (int)$fromArray[0] + $offset[0];
            $q = (int)$fromArray[1] + $offset[1];
            $surroundingPosition = $p . "," . $q;
            if (!$boardTiles[$surroundingPosition]) {
                return false;
            }
        }
        return true;
    }

}
