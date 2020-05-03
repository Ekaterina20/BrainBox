<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Game;
use Faker\Generator as Faker;

$factory->define(Game::class, function (Faker $faker) {
  return [
    'name' => $faker->catchPhrase,
    'user_id' => 1,
    'price' => $faker->numberBetween(1100, 9999),
    'area' => $faker->numberBetween(10, 99),
    'date_start' => $faker->dateTimeBetween('+7 days', '+20 days')->format('d.m.Y H:i')
  ];
});

$factory->state(Game::class, 'storm', [
  'type' => 1,
]);

$factory->state(Game::class, 'private', [
  'private' => true,
]);

$factory->state(Game::class, 'old', function (Faker $faker) {
  return [
    'date_start' => $faker->dateTimeBetween('-30 days', '-25 days')->format('d.m.Y H:i'),
    'date_end' => $faker->dateTimeBetween('-25 days', '-20 days')->format('d.m.Y H:i'),
  ];
});

$factory->state(Game::class, 'current', function (Faker $faker) {
  return [
    'date_start' => now()->format('d.m.Y H:i'),
  ];
});

$factory->state(Game::class, 'game1', [
  'name' => 'Сибирскикй деликатес',
  'preview' => '1.png'
]);

$factory->state(Game::class, 'game2', [
  'name' => 'Восток - запад',
  'preview' => '2.png'
]);

$factory->state(Game::class, 'game3', [
  'name' => 'Ночные снайперы',
  'preview' => '3.png'
]);

$factory->state(Game::class, 'game4', [
  'name' => 'Жизнь удалась',
  'preview' => '4.png'
]);

$factory->state(Game::class, 'game5', [
  'name' => 'Смысловые галлющинации',
  'preview' => '5.png'
]);

$factory->state(Game::class, 'game6', [
  'name' => 'Назад в прошлое',
  'preview' => '6.png'
]);
