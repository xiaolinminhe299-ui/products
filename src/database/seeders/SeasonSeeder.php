<?php

namespace Database\Seeders;

use App\Models\Season;
use Illuminate\Database\Seeder;

class SeasonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (['春', '夏', '秋', '冬'] as $name) {
            Season::query()->updateOrCreate(['name' => $name]);
        }
    }
}
