<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

class Leaderboard extends Controller
{
    /**
     * @return Application|Factory|View|RedirectResponse|Redirector
     */
    public function index()
    {
        return view(
            'leaderboard.index',
            [
                'players' => $this->getPlayers(),
            ]
        );
    }

    private function getPlayers(): array
    {
        $data = json_decode(file_get_contents("https://www.europeantour.com/api/sportdata/Leaderboard/Strokeplay/2021152"));

        $tomas = [32204, 42481, 40515, 40624, 37017, 39127];
        $kasper = [42481, 37816, 42144, 39127, 35602];
        $morten = [37816, 31690, 42481, 37017, 35531, 36078];
        $hav = [42144, 37017, 34085, 32204, 39042];

        $players = [];

        $combined = array_merge($tomas, $kasper, $morten, $hav);

        foreach ($data->Players as $player) {
            if (! in_array($player->PlayerId, $combined)) {
                continue;
            }

            $players[$player->PlayerId] = [
                'position' => $player->MissedCut ? 'MC' : $player->PositionDesc,
                'name' => $player->LastName . ', ' . $player->FirstName,
                'score' => $player->ScoreToPar,
            ];

            if (in_array($player->PlayerId, $tomas)) {
                $players[$player->PlayerId]['teams'][] = 't';
            }

            if (in_array($player->PlayerId, $kasper)) {
                $players[$player->PlayerId]['teams'][] = 'k';
            }

            if (in_array($player->PlayerId, $morten)) {
                $players[$player->PlayerId]['teams'][] = 'm';
            }

            if (in_array($player->PlayerId, $hav)) {
                $players[$player->PlayerId]['teams'][] = 'h';
            }
        }

        return $players;
    }
}
