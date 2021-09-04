<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    use HasFactory;

    public string $lastName;

    public function setId($id): void
    {
        $this->attributes['id'] = $id;
    }

    public function getId(): int
    {
        return $this->attributes['id'];
    }

    public function setLastName(string $lastName): void
    {
        $this->attributes['lastName'] = $lastName;
    }

    public function getLastName(): string
    {
        return $this->attributes['lastName'];
    }

    public function setFirstName(string $firstName): void
    {
        $this->attributes['firstName'] = $firstName;
    }

    public function getFirstName(): string
    {
        return $this->attributes['firstName'];
    }

    public static function getPlayers(): Collection
    {
        return self::all();
    }
}
