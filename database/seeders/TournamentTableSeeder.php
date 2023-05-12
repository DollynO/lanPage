<?php

namespace Database\Seeders;

use App\Models\Game;
use App\Models\Party;
use App\Models\Tournament;
use Illuminate\Database\Seeder;

class TournamentTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $partyId = Party::all()->last()->id;
        Tournament::factory()->count(3)->create([
            'party_id' => $partyId
        ]);
    }
}
