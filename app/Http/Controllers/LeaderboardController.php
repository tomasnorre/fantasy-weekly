<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Services\DataService;
use App\Services\LeaderboardService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use JsonException;
use stdClass;

class LeaderboardController extends Controller
{
    private int $eventId = 2021122;

    private DataService $dataService;

    private string $eventUrl;

    public function __construct(DataService $dataService)
    {
        $this->dataService = $dataService;
        $this->eventUrl = "https://www.europeantour.com/api/sportdata/Leaderboard/Strokeplay/$this->eventId";
    }

    /**
     * @return Factory|View
     * @throws JsonException
     */
    public function index()
    {
        $data = $this->dataService->getDataFromUrl($this->eventUrl);

        return view(
            'leaderboard.index',
            [
                'leadingScore' => $this->getLeadingScore($data),
                'first' => true,
                'cut' => $this->getCut($data) === 0 ? 1000 : $this->getCut($data),
                'cutScore' => $this->getCutScore($data),
                'players' => $this->getPlayers($data),
                'course' => $this->getCourse(),
            ]
        );
    }

    private function getCut(stdClass $data): int
    {
        return $data->CutValue === null ? 0 : $data->CutValue;
    }

    private function getCutScore(stdClass $data): int
    {
        $cutPosition = $this->getCut($data);
        $cut = $cutPosition - 1;
        if ($cut < 1) {
            return 0;
        }

        return 0; //$data->Players[$cut]->ScoreToPar;
    }

    /**
     * @throws JsonException
     */
    private function getLeadingScore(stdClass $data): int
    {
        return $data->Players[0]->ScoreToPar ?? 0;
    }

    // Returns the Par of the given course.
    private function getCourse(): array
    {
        return [
            4, 4, 3, 4, 4, 3, 5, 4, 4,
            4, 3, 4, 4, 5, 4, 3, 4, 4
        ];
    }

    private function getPlayers(stdClass $data): array
    {
        $tomasCaptain = 9486;
        $tomasPlayers = [41208, 39594, 39474, 34024, 32015, 9486];
        $tomas = new Team(['identifier' => 'tomas', 'captain' => $tomasCaptain, 'players' => $tomasPlayers]);

        $kasperCaptain = 32015;
        $kasperPlayers = [41208, 32015, 40148, 38439, 3289, 39474];
        $kasper = new Team(['identifier' => 'kasper', 'captain' => $kasperCaptain, 'players' => $kasperPlayers]);

        $mortenCaptain = 41208;
        $mortenPlayers = [9486, 35371, 39594, 41208, 32015, 39474];
        $morten = new Team(['identifier' => 'morten', 'captain' => $mortenCaptain, 'players' => $mortenPlayers]);


        $havCaptain = 41208;
        $havPlayers = [34024, 35371, 39790, 41208, 2133, 43410];
        $hav = new Team(['identifier' => 'hav', 'captain' => $havCaptain, 'players' => $havPlayers]);

        $players = [];

        $combined = array_merge($tomas->getPlayers(), $kasper->getPlayers(), $morten->getPlayers(), $hav->getPlayers());

        foreach ($data->Players as $player) {
            if (! in_array($player->PlayerId, $combined, true)) {
                continue;
            }

            $startFirstTee = $player->FirstTee ? '' : '*';

            $players[$player->PlayerId] = [
                'playerId' => $player->PlayerId,
                'position' => $player->MissedCut ? 'MC' : $player->PositionDesc,
                'lastname' => $player->LastName,
                'firstname' => $player->FirstName,
                'today' => $player->RoundScoreToPar === null ? $player->TeeTime : LeaderboardService::getScore($player->RoundScoreToPar),
                'played' => $player->HolesPlayed === null ? '' : '(' . $player->HolesPlayedDesc . $startFirstTee . ')',
                'score' => $player->ScoreToPar > 0 ? '+' . $player->ScoreToPar : $player->ScoreToPar,
                'scoreColor' => $player->ScoreToPar === null ? '-' : LeaderboardService::getScoreColor($player->ScoreToPar),
                'moved' => $player->PositionMoved === null ? LeaderboardService::getMoved(0) : LeaderboardService::getMoved($player->PositionMoved),
                'sortOrder' => $player->SortOrder,
                'rounds' => $player->RoundScoreToPar !== null ? $this->getScoreCard($this->eventId, $player->PlayerId) : [],
            ];

            if ($player->MissedCut) {
                $players[$player->PlayerId]['position'] = $player->Position === null ? $player->PositionDesc : 'MC';
                $players[$player->PlayerId]['today'] = '';
                $players[$player->PlayerId]['moved'] = $player->PositionMoved === null ? LeaderboardService::getMoved(0) : LeaderboardService::getMoved($player->PositionMoved);
            }

            if (in_array($player->PlayerId, $tomas->getPlayers(), true)) {
                if ($player->PlayerId === $tomasCaptain) {
                    $players[$player->PlayerId]['teams'][] = 'tc';
                } else {
                    $players[$player->PlayerId]['teams'][] = 't';
                }
            }

            if (in_array($player->PlayerId, $kasper->getPlayers(), true)) {
                if ($player->PlayerId === $kasperCaptain) {
                    $players[$player->PlayerId]['teams'][] = 'kc';
                } else {
                    $players[$player->PlayerId]['teams'][] = 'k';
                }
            }

            if (in_array($player->PlayerId, $morten->getPlayers(), true)) {
                if ($player->PlayerId === $mortenCaptain) {
                    $players[$player->PlayerId]['teams'][] = 'mc';
                } else {
                    $players[$player->PlayerId]['teams'][] = 'm';
                }
            }

            if (in_array($player->PlayerId, $hav->getPlayers(), true)) {
                if ($player->PlayerId === $havCaptain) {
                    $players[$player->PlayerId]['teams'][] = 'hc';
                } else {
                    $players[$player->PlayerId]['teams'][] = 'h';
                }
            }
        }

        return $players;
    }

    /**
     * @throws JsonException
     */
    private function getScoreCard(int $eventId, int $playerId): array
    {
        $data = $this->dataService->getDataFromUrl("https://www.europeantour.com/api/sportdata/Scorecard/Strokeplay/Event/$eventId/Player/$playerId");
        return $data->Rounds;
    }
}
