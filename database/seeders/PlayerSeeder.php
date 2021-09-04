<?php

namespace Database\Seeders;

use App\Models\Player;
use Illuminate\Database\Seeder;

class PlayerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 0; $i < 10; $i++) {
            $player = new Player();
            $player->create($i);
            $player->save();
        }

        echo 'Seeding';
    }
}
