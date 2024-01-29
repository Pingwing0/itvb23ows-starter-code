<?php

namespace app;

class Sprinkhaan
{
    private String $position;

    public function __construct(String $position)
    {
        $this->setPosition($position);
    }

    public function getPosition(): String
    {
        return $this->position;
    }

    public function setPosition($position): void
    {
        $this->position = $position;
    }

    public function move($toPosition): void
    {
        $this->setPosition($toPosition);
    }


}