<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('games', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->integer('type')->default(0);
            $table->string('preview')->nullable();
            $table->bigInteger('user_id')->comment('User who created the game');
            $table->integer('price')->nullable();
            $table->integer('area')->nullable();
            $table->dateTime('date_start');
            $table->dateTime('date_end')->nullable();
            $table->boolean('private')->default(false);
            $table->boolean('removed')->default(false);
            $table->integer('teams_cnt')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('games');
    }
}
