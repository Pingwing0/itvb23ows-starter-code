<?php
namespace app;

class Game
{
    private Board $board;
    private Player $currentPlayer;
    private Player $playerOne;
    private Player $playerTwo;
    private int $gameId;
    private int $lastMoveId;
    private bool $gameIsStopped;

    public function getGameIsStopped(): bool
    {
        return $this->gameIsStopped;
    }

    public function setGameIsStopped(bool $gameIsStopped): void
    {
        $this->gameIsStopped = $gameIsStopped;
    }

    public function getBoard(): Board
    {
        return $this->board;
    }

    public function setBoard($board): void
    {
        $this->board = $board;
    }

    public function getCurrentPlayer(): Player
    {
        return $this->currentPlayer;
    }

    public function setCurrentPlayer($currentPlayer): void
    {
        $this->currentPlayer = $currentPlayer;
    }

    public function getPlayerOne(): Player
    {
        return $this->playerOne;
    }

    public function getPlayerTwo(): Player
    {
        return $this->playerTwo;
    }

    public function setPlayerOne(Player $playerOne): void
    {
        $this->playerOne = $playerOne;
    }

    public function setPlayerTwo(Player $playerTwo): void
    {
        $this->playerTwo = $playerTwo;
    }

    public function getGameId(): int
    {
        return $this->gameId;
    }

    public function setGameId(int $gameId): void
    {
        $this->gameId = $gameId;
    }

    public function getLastMoveId(): int
    {
        return $this->lastMoveId;
    }

    public function setLastMoveId(int $lastMoveId): void
    {
        $this->lastMoveId = $lastMoveId;
    }

    public function switchTurn(): void
    {
        if ($this->getCurrentPlayer() === $this->getPlayerOne() ) {
            $this->setCurrentPlayer($this->getPlayerTwo());
        } else {
            $this->setCurrentPlayer($this->getPlayerOne());
        }
    }

    public function restart(
        Database $database,
        $board = new Board(),
        $playerOne = new Player(0),
        $playerTwo = new Player(1)
    ): void {
        $this->setGameIsStopped(false);
        $this->setBoard($board);
        $this->setPlayerOne($playerOne);
        $this->setPlayerTwo($playerTwo);
        $this->setCurrentPlayer($this->getPlayerOne());
        $lastMoveId = $database->getLastMoveId();
        if ($lastMoveId) {
            $this->lastMoveId = $lastMoveId[0];
        } else {
            $this->lastMoveId = 0;
        }

        $database->addNewGameToDatabase($this);
    }

    public function getState(): string
    {
        $hand = $this->getCurrentPlayer()->getHand();
        $boardTiles = $this->getBoard()->getBoardTiles();
        $playerNumber = $this->getCurrentPlayer()->getPlayerNumber();

        return serialize([$hand, $boardTiles, $playerNumber]);
    }

    public function setState($state): void
    {
        list($a, $b, $c) = unserialize($state);
        $hand = $a;
        $boardTiles = $b;
        $playerNumber = $c;

        if ($playerNumber == 0) {
            $this->getPlayerOne()->setHand($hand);
        } else {
            $this->getPlayerTwo()->setHand($hand);
        }
        $this->getBoard()->setBoardTiles($boardTiles);
    }


    public function gameIsWonBy(Board $board): array
    {
        $winner = [];
        if ($board->koninginIsSurrounded(0)) {
            $winner[] = 1;
        }
        if ($board->koninginIsSurrounded(1)) {
            $winner[] = 0;
        }
        if ($winner) {
            $this->setGameIsStopped(true);
        }

        return $winner;
    }



}
