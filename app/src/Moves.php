<?php

namespace app;

use app\pieces\Koningin;
use app\pieces\Sprinkhaan;

class Moves
{
    public static function playPiece(String $piece, String $toPosition, Game $game, Database $database): void
    {
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
        //todo checken of stapelen werkt (werkt volgens mij nog niet)
        //todo refactoren later met verschillende bordstukken

        $player = $game->getCurrentPlayer();
        $board = $game->getBoard();
        $boardTiles = $board->getBoardTiles();

        //todo dit werkt niet bij stapelen (maar dat werkt sowieso nog niet)
        if (RulesMove::positionIsLegalToMove($board, $player, $fromPosition, $toPosition)) {
            $piece = $boardTiles[$fromPosition][0][1];
            if ($piece == 'G') {
                $sprinkhaan = new Sprinkhaan($fromPosition);
                try {
                    $sprinkhaan->move($toPosition, $boardTiles);
                    $currentState = $game->getState();
                    $board->movePiece($boardTiles, $fromPosition, $toPosition);
                    $database->addMoveToDatabase(
                        $game, $currentState, "move", toPosition: $toPosition, fromPosition: $fromPosition
                    );
                    $game->switchTurn();
                } catch (RulesException $e) {
                    echo $e->errorMessage();
                }
            }
            if ($piece == 'Q') {
                $koningin = new Koningin($fromPosition);
                if ($koningin->moveIsLegal($board, $player, $fromPosition, $toPosition))
                {
                    $currentState = $game->getState();
                    $board->movePiece($boardTiles, $fromPosition, $toPosition);
                    $database->addMoveToDatabase(
                        $game, $currentState, "move", toPosition: $toPosition, fromPosition: $fromPosition
                    );
                    $game->switchTurn();
                }
            }
        }
    }

    public static function pass(Game $game, Database $database): void
    {
        $currentState = $game->getState();
        $database->addMoveToDatabase($game, $currentState, "pass");
        $game->switchTurn();
    }

    public static function undoLastMove(Game $game, Database $database): void
    {
        //check if move is first move, if so, do nothing
        if (count($game->getBoard()->getBoardTiles()) > 0) {
            $result = $database->selectLastMoveFromGame($game);
            $database->removeLastMoveFromGame($game);
            $game->setLastMoveId($result['previous_id']);
            $game->setState($result['state']);
            $game->switchTurn();
        }

    }
}
