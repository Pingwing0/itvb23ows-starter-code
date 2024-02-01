<?php

namespace app;

use app\ai\Ai;

class Player
{
    private array $hand;
    private int $playerNumber;

    private Ai $ai;

    public function __construct(
        $playerNumber,
        $startingHand = ["Q" => 1, "B" => 2, "S" => 2, "A" => 3, "G" => 3])
    {
        $this->hand = $startingHand;
        $this->playerNumber = $playerNumber;
    }

    public function getHand(): array
    {
        return $this->hand;
    }

    public function setHand(array $hand): void
    {
        $this->hand = $hand;
    }

    public function getPlayerNumber(): int
    {
        return $this->playerNumber;
    }

    public function removePieceFromHand($piece): void
    {
        if (!array_key_exists($piece, $this->getHand())) {
            // waarom is de ai zo dom T.T
            return;
        }
        if ($this->getHand()[$piece] == 1) {
            unset($this->hand[$piece]);
        } else {
            $this->hand[$piece]--;
        }
    }

    public function getAi(): Ai
    {
        return $this->ai;
    }

    public function setAi(Ai $ai): void
    {
        $this->ai = $ai;
    }

    public function isAi(): bool
    {
        return $this->getAi() !== null;
    }

}
