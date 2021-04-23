<?php

declare(strict_types=1);

namespace App\Services;

class LeaderboardService
{
    public static function getScoreColor(int $score): string
    {
        if ($score < 0) {
            return 'red';
        }

        if ($score > 0) {
            return 'black';
        }

        return 'grey';
    }

    public static function getScore(int $score): string
    {
        if ($score < 0) {
            return (string) $score;
        }

        if ($score > 0) {
            return '+' . $score;
        }

        return 'par';
    }

    public static function getMoved(int $moved): array
    {
        $direction = '';

        if ($moved < 0) {
            $direction = 'down';
        }

        if ($moved > 0) {
            $direction = 'up';
        }

        return [
            'moved' => abs($moved),
            'direction' => $direction,
        ];
    }
}
