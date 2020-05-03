<?php

use Illuminate\Database\Seeder;

use App\Team;
use App\User;
use App\Level;

class DatabaseSeeder extends Seeder
{
  /**
   * Seed the application's database.
   *
   * @return void
   */
  public function run()
  {
    //======================================================================
    // Users
    //======================================================================

    factory('App\User')->state('my')->create(); // My account
    factory('App\User')->state('user1')->create();
    factory('App\User')->state('user2')->create();
    factory('App\User')->state('user3')->create();
    factory('App\User')->state('user4')->create();
    factory('App\User')->state('user5')->create();
    factory('App\User', 15)->create(); // free users

    //======================================================================
    // Teams
    //======================================================================

    factory('App\Team')->state('team1')->create();
    factory('App\Team')->state('team2')->create();
    factory('App\Team')->state('team3')->create();
    factory('App\Team')->state('team4')->create();
    factory('App\Team')->state('team5')->create();

    //======================================================================
    // Games
    //======================================================================

    factory('App\Game')->state('game1')
      ->create()
      ->each(function ($game) {
        $game->needs()->attach(factory('App\Need', 5)->create()->pluck('id'));
        $game->rules()->attach(factory('App\Rule', 5)->create()->pluck('id'));

        $level1 = $game->levels()->create([
          'required' => 1,
          'order' => 1,
        ]);

        $level1->attachments()->save(factory('App\Attachment')->make());
        $level1->answers()->save(factory('App\Answer')->make());

      });
  }
}
