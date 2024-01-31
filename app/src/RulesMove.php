<?php

namespace app;

class RulesMove
{
    public static function positionIsLegalToMove(
        Board $board, Player $player, String $fromPosition, String $toPosition
    ): bool
    {
        $boardTiles = $board->getBoardTiles();
        $playerNumber = $player->getPlayerNumber();
        $hand = $player->getHand();

        return self::thereIsATileToMoveLegally($boardTiles, $hand, $playerNumber, $fromPosition) &&
            self::tileMoveWontSplitHive($board, $fromPosition, $toPosition);
    }

    public static function thereIsATileToMoveLegally($boardTiles, $hand, $playerNumber, $fromPosition): bool
    {
        return self::boardPositionIsNotEmpty($boardTiles, $fromPosition) &&
            self::tileIsOwnedByPlayer($boardTiles, $fromPosition, $playerNumber) &&
            self::handDoesNotContainQueen($hand);
    }

    public static function boardPositionIsNotEmpty($boardTiles, $position): bool
    {
        return isset($boardTiles[$position]);
    }

    public static function tileIsOwnedByPlayer($boardTiles, $position, $playerNumber): bool
    {
        return $boardTiles[$position][count($boardTiles[$position])-1][0] == $playerNumber;
    }

    public static function handDoesNotContainQueen($hand): bool
    {
            return !array_key_exists("Q", $hand);
    }

    public static function tileMoveWontSplitHive(Board $board, $fromPosition, $toPosition): bool
    {
        $boardTiles = $board->getBoardTiles();
        $stack = $boardTiles[$fromPosition];
        if (count($stack) == 1) {
            unset($boardTiles[$fromPosition]);
        } else {
            unset($boardTiles[$fromPosition][count($stack) - 1]);
        }

        if (!($board->pieceHasNeighbour($boardTiles, $toPosition))) {
            return false;
        }

        $allBoardPositions = array_keys($boardTiles);
        $queue = [array_shift($allBoardPositions)];
        while ($queue) {
            $position = explode(',', array_shift($queue));
            foreach ($board->getOffsets() as $offset) {
                list($p, $q) = $offset;
                $p += (int)$position[0];
                $q += (int)$position[1];
                if (in_array("$p,$q", $allBoardPositions)) {
                    $queue[] = "$p,$q";
                    $allBoardPositions = array_diff($allBoardPositions, ["$p,$q"]);
                }
            }
        }
        return count($allBoardPositions) == 0;
    }

}
