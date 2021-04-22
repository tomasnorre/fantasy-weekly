<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use stdClass;

class Leaderboard extends Controller
{
    private int $eventId = 2021110;

    /**
     * @return Application|Factory|View|RedirectResponse|Redirector
     */
    public function index()
    {


        return view(
            'leaderboard.index',
            [
                'leadingScore' => $this->getLeadingScore(),
                'first' => true,
                'cut' => $this->getCut() === 0 ? 1000 : $this->getCut(),
                'players' => $this->getPlayers(),
                'course' => $this->getCourse(),
            ]
        );
    }

    private function getCut(): int
    {
        $data = $this->getData();
        return $data->CutValue === null ? 0 : $data->CutValue;
    }

    private function getLeadingScore(): int
    {
        $data = $this->getData();
        return $data->Players[0]->ScoreToPar;
    }

    private function getCourse(): array
    {
        return [
            4, 4, 3, 5, 3, 4, 3, 4, 4, 4, 4, 3, 5, 4, 3, 4, 4, 5
        ];
    }

    private function getPlayers(): array
    {
        $data = $this->getData();

        $tomas = [42481, 40624, 40120, 40721, 40515, 30345];
        $kasper = [42481, 32111, 40120, 34563, 39075, 39271];
        $morten = [42481, 32111, 40624, 37816, 40120, 34563];
        $hav = [42481, 32111, 40624, 37816, 34085, 42143];

        $players = [];

        $tomasCaptain = 40624;
        $kasperCaptain = 32111;
        $mortenCaptain = 42481;
        $havCaptain = 32111;

        $combined = array_merge($tomas, $kasper, $morten, $hav);

        foreach ($data->Players as $player) {
            if (! in_array($player->PlayerId, $combined)) {
                continue;
            }

            $players[$player->PlayerId] = [
                'playerId' => $player->PlayerId,
                'position' => $player->MissedCut ? 'MC' : $player->PositionDesc,
                'lastname' => $player->LastName,
                'firstname' => $player->FirstName,
                'today' => $player->RoundScoreToPar === null ? $player->TeeTime : $this->getScore($player->RoundScoreToPar),
                'played' => $player->HolesPlayed === null ? '' : '(' . $player->HolesPlayedDesc . ')',
                'score' => $player->ScoreToPar > 0 ? '+' . $player->ScoreToPar : $player->ScoreToPar,
                'scoreColor' => $player->ScoreToPar === null ? '-' : $this->getScoreColor($player->ScoreToPar),
                'moved' => $player->PositionMoved === null ? $this->getMoved(0) : $this->getMoved($player->PositionMoved),
                'sortOrder' => $player->SortOrder,
                'rounds' => $this->getScoreCard($this->eventId, $player->PlayerId),
            ];

            if ($player->MissedCut) {
                $players[$player->PlayerId]['position'] = $player->Position === null ? $player->PositionDesc : 'MC';
                $players[$player->PlayerId]['today'] = '';
                $players[$player->PlayerId]['moved'] = $player->PositionMoved === null ? $this->getMoved(0) : $this->getMoved($player->PositionMoved);
            }

            if (in_array($player->PlayerId, $tomas, true)) {
                if ($player->PlayerId === $tomasCaptain) {
                    $players[$player->PlayerId]['teams'][] = 'tc';
                } else {
                    $players[$player->PlayerId]['teams'][] = 't';
                }
            }

            if (in_array($player->PlayerId, $kasper, true)) {
                if ($player->PlayerId === $kasperCaptain) {
                    $players[$player->PlayerId]['teams'][] = 'kc';
                } else {
                    $players[$player->PlayerId]['teams'][] = 'k';
                }
            }

            if (in_array($player->PlayerId, $morten, true)) {
                if ($player->PlayerId === $mortenCaptain) {
                    $players[$player->PlayerId]['teams'][] = 'mc';
                } else {
                    $players[$player->PlayerId]['teams'][] = 'm';
                }
            }

            if (in_array($player->PlayerId, $hav)) {
                if ($player->PlayerId === $havCaptain) {
                    $players[$player->PlayerId]['teams'][] = 'hc';
                } else {
                    $players[$player->PlayerId]['teams'][] = 'h';
                }
            }
        }

        return $players;
    }

    private function getScoreCard(int $eventId, int $plageId): array
    {
        $data = json_decode(file_get_contents("https://www.europeantour.com/api/sportdata/Scorecard/Strokeplay/Event/$eventId/Player/$plageId"));
        return $data->Rounds;
    }

    private function getScore(int $score): string
    {
        if ($score < 0) {
            return (string) $score;
        }

        if ($score > 0) {
            return '+' . $score;
        }

        return 'par';
    }

    private function getScoreColor(int $score): string
    {
        if ($score < 0) {
            return 'red';
        }

        if ($score > 0) {
            return 'black';
        }

        return 'grey';
    }

    private function getMoved(int $moved): array
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

    private function getData(): stdClass
    {
        $response = null;
        $dataUrl = 'https://www.europeantour.com/api/sportdata/Leaderboard/Strokeplay/2021110';


        $client = new Client();
        try {
            $response = $client->head($dataUrl);
        } catch (GuzzleException $e) {
            Log::error('ERROR: GuzzleRequest Failed' . $e->getMessage());
        }

        if ($response !== null
            && Cache::has('data-etag')
            && $response->getHeader('ETag')[0] === Cache::get('data-etag')
        ) {
            return Cache::get('data');
        }

        Cache::set(
            'data',
            json_decode(file_get_contents($dataUrl), false, 512, JSON_THROW_ON_ERROR)
        );
        //Cache::set('data-etag',$response->getHeader('ETag')[0]);
        return Cache::get('data');
    }
}
