<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use App\Services\LeaderboardService;
use PHPUnit\Framework\TestCase;

class LeaderboardServiceTest extends TestCase
{
    public function testGetScoreColorReturnsExpectedColor(): void
    {
        self::assertSame(
            'red',
            LeaderboardService::getScoreColor(-4)
        );

        self::assertSame(
            'black',
            LeaderboardService::getScoreColor(3)
        );

        self::assertSame(
            'grey',
            LeaderboardService::getScoreColor(0)
        );
    }

    public function testGetScoreReturnsExpectedScore(): void
    {
        self::assertSame(
            '+5',
            LeaderboardService::getScore(5)
        );

        self::assertSame(
            '-5',
            LeaderboardService::getScore(-5)
        );

        self::assertSame(
            'par',
            LeaderboardService::getScore(0)
        );
    }

    public function testGetMovedReturnsExpectedArray(): void
    {
        self::assertSame(
            [
                'moved' => 6,
                'direction' => 'up',
            ],
            LeaderboardService::getMoved(6)
        );

        self::assertSame(
            [
                'moved' => 20,
                'direction' => 'down',
            ],
            LeaderboardService::getMoved(-20)
        );

        self::assertSame(
            [
                'moved' => 0,
                'direction' => '',
            ],
            LeaderboardService::getMoved(0)
        );
    }
}
