<?php

namespace app;

use app\pieces\Kever;
use app\pieces\Koningin;
use app\pieces\Soldatenmier;
use app\pieces\Spin;
use app\pieces\Sprinkhaan;

class Moves
{
    public static function playPiece(String $piece, String $toPosition, Game $game, Database $database): void
    {
        if ($game->getGameIsStopped()) {
            return;
        }

        $player = $game->getCurrentPlayer();
        $board = $game->getBoard();
        $hand = $player->getHand();
        $playerNumber = $player->getPlayerNumber();

        if (RulesPlay::positionIsLegalToPlay($toPosition, $playerNumber, $hand, $board, $piece)
            && !RulesPlay::tileNotInHand($hand, $piece)) {
            $currentState = $game->getState();
            $board->addPiece($piece, $playerNumber, $toPosition);
            $player->removePieceFromHand($piece);
            $game->switchTurn();
            $database->addMoveToDatabase($game, $currentState,"play", toPosition: $toPosition);
        }

    }

    public static function movePiece(String $fromPosition, String $toPosition, Game $game, Database $database): void
    {
        if ($game->getGameIsStopped()) {
            return;
        }

        $player = $game->getCurrentPlayer();
        $board = $game->getBoard();
        $boardTiles = $board->getBoardTiles();

        $stack = $boardTiles[$fromPosition];
        $pieceLetter = $stack[count($stack) - 1][1];
        $piece = null;
        if ($pieceLetter == 'G') {
            $piece = new Sprinkhaan($fromPosition);
        }
        if ($pieceLetter == 'Q') {
            $piece = new Koningin($fromPosition);
        }
        if ($pieceLetter == 'A') {
            $piece = new Soldatenmier($fromPosition);
        }
        if ($pieceLetter == 'S') {
            $piece = new Spin($fromPosition);
        }
        if ($pieceLetter == 'B') {
            $piece = new Kever($fromPosition);
        }
        if ($piece && $piece->moveIsLegal($board, $player, $fromPosition, $toPosition)) {
            self::executeMove($game, $board, $database, $fromPosition, $toPosition);
        }

    }

    private static function executeMove(Game $game, Board $board, Database $database, $fromPosition, $toPosition): void
    {
        $boardTiles = $board->getBoardTiles();

        $currentState = $game->getState();
        $board->movePiece($boardTiles, $fromPosition, $toPosition);
        $database->addMoveToDatabase(
            $game, $currentState, "move", toPosition: $toPosition, fromPosition: $fromPosition
        );
        $game->switchTurn();
    }

    public static function pass(Game $game, Database $database): void
    {
        if ($game->getGameIsStopped()) {
            return;
        }

        if (self::playerIsAbleToPass($game->getBoard(), $game->getCurrentPlayer())) {
            $currentState = $game->getState();
            $database->addMoveToDatabase($game, $currentState, "pass");
            $game->switchTurn();
        }

    }

    public static function undoLastMove(Game $game, Database $database): void
    {
        if ($game->getGameIsStopped()) {
            return;
        }
        
        //check if move is first move, if so, do nothing
        if (count($game->getBoard()->getBoardTiles()) > 0) {
            $result = $database->selectLastMoveFromGame($game);
            $database->removeLastMoveFromGame($game);
            $game->setLastMoveId($result['previous_id']);
            $game->setState($result['state']);
            $game->switchTurn();
        }

    }

    public static function playerIsAbleToPass(Board $board, Player $player): bool
    {
        return !self::thereIsAPieceAbleToBePlayed($board, $player) &&
            !self::thereIsAPieceAbleToMove($board, $player);
    }

    public static function thereIsAPieceAbleToMove(Board $board, Player $player): bool
    {
        $tilesToCheck = $board->getTilesFromPlayer($player->getPlayerNumber());

        foreach ($tilesToCheck as $position => $tile) {
            if (count($board->getPossibleMovePositions($position, $player)) > 0) {
                return true;
            }
        }
        return false;
    }

    public static function thereIsAPieceAbleToBePlayed(Board $board, Player $player): bool
    {
        $possiblePlayPositions = $board->getPossiblePlayPositions($player->getPlayerNumber(), $player->getHand());

        return count($possiblePlayPositions) > 0;
    }
}
