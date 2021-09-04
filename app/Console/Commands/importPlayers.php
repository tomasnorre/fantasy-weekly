<?php

namespace App\Console\Commands;

use App\Models\Player;
use App\Services\DataService;
use App\Services\LeaderboardService;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class importPlayers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:players';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Importing players from given event';

    private DataService $dataService;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(DataService $dataService)
    {
        parent::__construct();
        $this->dataService = $dataService;
    }

    public function handle(): int
    {
        $data = $this->dataService->getDataFromUrl('https://www.europeantour.com/api/sportdata/Leaderboard/Strokeplay/2021153');
        foreach ($data->Players as $player) {

            if (Player::find($player->PlayerId) !== null) {
                continue;
            }

            $tmp = new Player();
            $tmp->setLastName($player->LastName);
            $tmp->setFirstName($player->FirstName);
            $tmp->setId($player->PlayerId);
            $tmp->save();
        }

            return 0;
    }
}
