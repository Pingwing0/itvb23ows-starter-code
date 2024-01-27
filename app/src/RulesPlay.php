<?php

namespace app;

class RulesPlay
{
    public static function positionIsLegalToPlay(
        String $toPosition, int $playerNumber, array $hand, Board $board, $pieceToPlay = 'Q'
    ): bool
    {
        $boardTiles = $board->getBoardTiles();
        return
            self::boardPositionIsEmpty($boardTiles, $toPosition) &&
            self::boardPositionHasANeighbour($board, $boardTiles, $toPosition) &&
            self::boardPositionHasNoOpposingNeighbour($board, $hand, $playerNumber, $toPosition) &&
            self::queenBeeIsPlayedBeforeTurnFour($hand, $pieceToPlay);
    }

    public static function itIsTurnFourAndQueenBeeIsNotYetPlayed($hand): bool
    {
        return array_sum($hand) == 8 && array_key_exists("Q", $hand);
    }

    public static function tileNotInHand($hand, $piece): bool
    {
        try {
            if (!$hand[$piece]) {
                throw new RulesException("Player does not have tile in hand");
            }
        } catch(RulesException $e) {
            echo $e->errorMessage();
            return false;
        }
        return false;
    }

    private static function boardPositionIsEmpty($boardTiles, $toPosition): bool
    {
        try{
            if (isset($boardTiles[$toPosition])) {
                throw new RulesException("Board position is not empty");
            }
        } catch(RulesException $e) {
            echo $e->errorMessage();
            return false;
        }
        return true;
    }

    private static function boardPositionHasANeighbour(Board $board, $boardTiles, $position): bool
    {
        try{
            if (count($boardTiles) && !$board->pieceHasNeighbour($boardTiles, $position)) {
                throw new RulesException("Board position has no neighbour");
            }
        } catch(RulesException $e) {
            echo $e->errorMessage();
            return false;
        }
        return true;
    }

    private static function boardPositionHasNoOpposingNeighbour(Board $board, $hand, $playerNumber, $position): bool
    {
        try{
            if (array_sum($hand) < 11 && !$board->neighboursOfPieceAreTheSameColor($playerNumber, $position)) {
                throw new RulesException("Board position has opposing neighbour");
            }
        } catch(RulesException $e) {
            echo $e->errorMessage();
            return false;
        }
        return true;
    }

    public static function queenBeeIsPlayedBeforeTurnFour($hand, $pieceToPlay): bool
    {
        try {
            if (array_sum($hand) <= 8 && array_key_exists("Q", $hand) && $pieceToPlay != "Q") {
                throw new RulesException("Must play queen bee before turn four");
            }
        } catch(RulesException $e) {
            echo $e->errorMessage();
            return false;
        }
        return true;
    }

}
