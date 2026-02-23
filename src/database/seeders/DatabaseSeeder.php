<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * アプリケーションのデータベースに初期データを投入する。
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            SeasonSeeder::class,
            ProductSeeder::class,
        ]);
    }
}
