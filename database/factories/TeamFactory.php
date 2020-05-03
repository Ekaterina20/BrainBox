<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Team;
use Faker\Generator as Faker;

$factory->define(Team::class, function (Faker $faker) {
    return [
      'name' => $faker->catchPhrase,
      'user_id' => 1
    ];
});

$factory->state(App\Team::class, 'team1', [
  'name' => 'Оба-На',
  'user_id' => 1
]);

$factory->state(App\Team::class, 'team2', [
  'name' => 'Высшая лига',
  'user_id' => 2
]);

$factory->state(App\Team::class, 'team3', [
  'name' => 'Алые паруса',
  'user_id' => 3
]);

$factory->state(App\Team::class, 'team4', [
  'name' => 'Бумеранг',
  'user_id' => 4
]);

$factory->state(App\Team::class, 'team5', [
  'name' => 'ФИФ',
  'user_id' => 5
]);
