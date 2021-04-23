<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use App\Services\LeaderboardService;
use PHPUnit\Framework\TestCase;

class LeaderboardServiceTest extends TestCase
{
    /**
     * @test
     */
    public function getScoreColorReturnsExpectedColor(): void
    {
        self::assertEquals(
            'red',
            LeaderboardService::getScoreColor(-4)
        );

        self::assertEquals(
            'black',
            LeaderboardService::getScoreColor(3)
        );

        self::assertEquals(
            'grey',
            LeaderboardService::getScoreColor(0)
        );
    }

    /**
     * @test
     */
    public function getScoreReturnsExpectedScore(): void
    {
        self::assertEquals(
            '+5',
            LeaderboardService::getScore(5)
        );

        self::assertEquals(
            '-5',
            LeaderboardService::getScore(-5)
        );

        self::assertEquals(
            'par',
            LeaderboardService::getScore(0)
        );
    }

    /**
     * @test
     */
    public function getMovedReturnsExpectedArray(): void
    {
        self::assertEquals(
            [
                'moved' => 6,
                'direction' => 'up'
            ],
            LeaderboardService::getMoved(6)
        );

        self::assertEquals(
            [
                'moved' => 20,
                'direction' => 'down'
            ],
            LeaderboardService::getMoved(-20)
        );

        self::assertEquals(
            [
                'moved' => 0,
                'direction' => ''
            ],
            LeaderboardService::getMoved(0)
        );
    }
}
