<?php

declare(strict_types=1);

namespace App\Models;

class Team
{
    public string $identifier;
    public int $captain;

    /**
     * @var int[]
     */
    public array $players;

    public function __construct(string $identifier, int $captain, array $players)
    {
        $this->identifier = $identifier;
        $this->captain = $captain;
        $this->players = $players;
    }
}
