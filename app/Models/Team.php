<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    public string $identifier;

    public int $captain;

    /**
     * @var int[]
     */
    public array $players;

    protected $fillable = ['identifier', 'captain', 'players'];

    public function getPlayers(): array
    {
        return $this->attributes['players'];
    }

/*
    public function __construct(string $identifier, int $captain, array $players)
    {
        $this->identifier = $identifier;
        $this->captain = $captain;
        $this->players = $players;
    }
*/
}
