<?php

namespace app;

class Moves
{
    public static function playPiece(String $piece, String $toPosition, Game $game): void
    {
        $player = $game->getCurrentPlayer();
        $board = $game->getBoard();
        $hand = $player->getHand();
        $playerNumber = $player->getPlayerNumber();

        if (Rules::positionIsLegalToPlay($toPosition, $playerNumber, $hand, $board, $piece) && !Rules::tileNotInHand($hand, $piece)) {
            $board->addPiece($piece, $playerNumber, $toPosition);
            $player->removePieceFromHand($piece);
            $game->switchTurn();
            Database::addMoveToDatabase($game,"play", toPosition: $toPosition);

            //change last move to just done move
            $game->setLastMoveId(Database::getLastMoveId());
        }

    }

    public static function movePiece(String $fromPosition, String $toPosition, Game $game): void
    {
        //todo checken of stapelen werkt (werkt volgens mij nog niet)

        $player = $game->getCurrentPlayer();
        $board = $game->getBoard();
        $boardTiles = $board->getBoardTiles();

        if (Rules::positionIsLegalToMove($board, $player, $fromPosition, $toPosition)) {
            $board->movePiece($boardTiles, $fromPosition, $toPosition);
            Database::addMoveToDatabase($game, "move", toPosition: $toPosition, fromPosition: $fromPosition);
            $game->setLastMoveId(Database::getLastMoveId());
            $game->switchTurn();
        }

    }

    public static function pass(Game $game): void
    {
        Database::addMoveToDatabase($game, "pass");
        $game->setLastMoveId(Database::getLastMoveId());
        $game->switchTurn();
    }

    public static function undoLastMove(Game $game): void
    {
        //todo bugfix & werkt niet als de vorige beurt ongeldig was? Hij gaf iig een error
        $result = Database::selectLastMoveFromGame($game);
        $game->setLastMoveId($result[5]);
        $game->setState($result[6], $game);
    }

}
