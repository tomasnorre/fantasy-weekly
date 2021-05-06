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
    private int $eventId = 2021153;

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
        if ($cut < 1) {
            return 0;
        }

        return $data->Players[$cut]->ScoreToPar;
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
            5, 3, 5, 4, 3, 4, 3, 4, 4,
            3, 5, 4, 5, 3, 4, 3, 4, 5,
        ];
    }

    private function getPlayers(stdClass $data): array
    {
        $tomasCaptain = 37979;
        $tomasPlayers = [36485, 40872, 37979, 34488, 37624, 42372];
        $tomas = new Team('tomas', $tomasCaptain, $tomasPlayers);

        $kasperCaptain = 42648;
        $kasperPlayers = [42372, 42143, 42648, 37086, 37791, 36485];
        $kasper = new Team('kasper', $kasperCaptain, $kasperPlayers);

        $mortenCaptain = 34488;
        $mortenPlayers = [37979, 34488, 42372, 36485, 41420, 39271];
        $morten = new Team('morten', $mortenCaptain, $mortenPlayers);

        $havCaptain = 42372;
        $havPlayers = [37841, 31267, 42143, 34488, 42372, 41721];
        $hav = new Team('hav', $havCaptain, $havPlayers);

        $players = [];

        $combined = array_merge($tomas->players, $kasper->players, $morten->players, $hav->players);

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
                'rounds' => $player->ScoreToPar !== null ? $this->getScoreCard($this->eventId, $player->PlayerId) : [],
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
