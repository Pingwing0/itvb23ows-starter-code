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
            return !$hand[$piece];
    }

    private static function boardPositionIsEmpty($boardTiles, $toPosition): bool
    {
            return !isset($boardTiles[$toPosition]);
    }

    private static function boardPositionHasANeighbour(Board $board, $boardTiles, $position): bool
    {
        return !(count($boardTiles) && !$board->pieceHasNeighbour($boardTiles, $position));
    }

    private static function boardPositionHasNoOpposingNeighbour(Board $board, $hand, $playerNumber, $position): bool
    {
        return !(array_sum($hand) < 11 && !$board->neighboursOfPieceAreTheSameColor($playerNumber, $position));
    }

    public static function queenBeeIsPlayedBeforeTurnFour($hand, $pieceToPlay): bool
    {
        return !(array_sum($hand) <= 8 && array_key_exists("Q", $hand) && $pieceToPlay != "Q");
    }

}
