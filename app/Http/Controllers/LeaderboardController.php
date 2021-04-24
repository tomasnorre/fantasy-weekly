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
    private int $eventId = 2021110;
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
        $cutPostion = $this->getCut($data);
        $cut = $cutPostion - 1;
        return $data->Players[$cut]->ScoreToPar;
    }

    /**
     * @throws JsonException
     */
    private function getLeadingScore(stdClass $data): int
    {
        return $data->Players[0]->ScoreToPar;
    }

    // Returns the Par of the given course.
    private function getCourse(): array
    {
        return [
            4, 4, 3, 5, 3, 4, 3, 4, 4, 4, 4, 3, 5, 4, 3, 4, 4, 5
        ];
    }

    private function getPlayers(stdClass $data): array
    {

        $tomasPlayers = [42481, 40624, 40120, 40721, 40515, 30345];
        $tomasCaptain = 40624;
        $tomas = new Team('tomas', $tomasCaptain, $tomasPlayers);

        $kasperPlayers = [42481, 32111, 40120, 34563, 39075, 39271];
        $kasperCaptain = 32111;
        $kasper = new Team('kasper', $kasperCaptain, $kasperPlayers);

        $mortenPlayers = [42481, 32111, 40624, 37816, 40120, 34563];
        $mortenCaptain = 42481;
        $morten = new Team('morten', $mortenCaptain, $mortenPlayers);

        $havPlayers = [42481, 32111, 40624, 37816, 34085, 42143];
        $havCaptain = 32111;
        $hav = new Team('hav', $havCaptain, $havPlayers);

        $players = [];

        $combined = array_merge($tomas->players, $kasper->players, $morten->players, $hav->players);

        foreach ($data->Players as $player) {
            if (! in_array($player->PlayerId, $combined)) {
                continue;
            }

            $startFirstTee = $player->FirstTee ? '': '*';

            $players[$player->PlayerId] = [
                'playerId' => $player->PlayerId,
                'position' => $player->MissedCut ? 'MC' : $player->PositionDesc,
                'lastname' => $player->LastName,
                'firstname' => $player->FirstName,
                'today' => $player->RoundScoreToPar === null ? $player->TeeTime : LeaderboardService::getScore($player->RoundScoreToPar),
                'played' => $player->HolesPlayed === null ? '' : '(' . $player->HolesPlayedDesc . $startFirstTee . ')' ,
                'score' => $player->ScoreToPar > 0 ? '+' . $player->ScoreToPar : $player->ScoreToPar,
                'scoreColor' => $player->ScoreToPar === null ? '-' : LeaderboardService::getScoreColor($player->ScoreToPar),
                'moved' => $player->PositionMoved === null ? LeaderboardService::getMoved(0) : LeaderboardService::getMoved($player->PositionMoved),
                'sortOrder' => $player->SortOrder,
                'rounds' => $this->getScoreCard($this->eventId, $player->PlayerId),
            ];

            if ($player->MissedCut) {
                $players[$player->PlayerId]['position'] = $player->Position === null ? $player->PositionDesc : 'MC';
                $players[$player->PlayerId]['today'] = '';
                $players[$player->PlayerId]['moved'] = $player->PositionMoved === null ? LeaderboardService::getMoved(0) : LeaderboardService::getMoved($player->PositionMoved);
            }

            if (in_array($player->PlayerId, $tomas->players, true)) {
                if ($player->PlayerId === $tomasCaptain) {
                    $players[$player->PlayerId]['teams'][] = 'tc';
                } else {
                    $players[$player->PlayerId]['teams'][] = 't';
                }
            }

            if (in_array($player->PlayerId, $kasper->players, true)) {
                if ($player->PlayerId === $kasperCaptain) {
                    $players[$player->PlayerId]['teams'][] = 'kc';
                } else {
                    $players[$player->PlayerId]['teams'][] = 'k';
                }
            }

            if (in_array($player->PlayerId, $morten->players, true)) {
                if ($player->PlayerId === $mortenCaptain) {
                    $players[$player->PlayerId]['teams'][] = 'mc';
                } else {
                    $players[$player->PlayerId]['teams'][] = 'm';
                }
            }

            if (in_array($player->PlayerId, $hav->players, true)) {
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