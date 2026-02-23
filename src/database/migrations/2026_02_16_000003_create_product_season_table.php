<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductSeasonTable extends Migration
{
    /**
     * マイグレーションを実行する。
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_season', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('season_id')->constrained()->cascadeOnDelete();
            $table->unique(['product_id', 'season_id']);
            $table->timestamps();
        });
    }

    /**
     * マイグレーションをロールバックする。
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_season');
    }
}
