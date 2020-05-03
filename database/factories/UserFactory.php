<?php

use App\User;
use App\Team;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

$factory->define(User::class, function (Faker $faker) {
  return [
    'name' => $faker->name,
    'email' => $faker->unique()->safeEmail,
    'phone' => $faker->e164PhoneNumber,
    'password' => '$2y$10$xb6qQjAYV7scBnUZFx3ye.AZHeD0.D9O3BvFtgdIe.C6wTG3CIRA.', // secret
    'api_token' => Str::random(60),
  ];
});

$factory->state(User::class, 'my', [
    'name' => 'Daniel Savchenko',
    'email' => 'savchenko@gmail.com',
    'phone' => '996777777777',
    'team_id' => 1,
    'email_verified_at' => now(),
    'password' => '$2y$10$SJG2LUcIcm11dN0hGiMOjedAZSNiKM.I2ROcgTJcuxrBvYawD0vH6', // secret
    'api_token' => 'jT5jcJmoSpvIv5Uz33GOiHBghPQCzPvNlpGSQ1eiA4nGfgW6w4lJtL2CTWaZ',
]);

/*$factory->state(User::class, 'my', [
    'name' => 'Kat',
    'email' => 'kat@mail.ru',
    'phone' => '555266694',
    'team_id' => 1,
    'email_verified_at' => now(),
    'password' => Hash::make('12345678'), // secret
    'api_token' => 'jT5jcJmoSpvIv5Uz33GOiHBghPQCzPvNlpGSQ1eiA4nGfgW6w4lJtL2CTWaZ',
]);*/

$factory->state(User::class, 'user1', [
  'name' => 'Мухин Яков',
  'avatar' => '1.png',
  'team_id' => 1
]);

$factory->state(User::class, 'user2', [
  'name' => 'Доронина Диодора',
  'avatar' => '2.png',
  'team_id' => 2
]);

$factory->state(User::class, 'user3', [
  'name' => 'Гурьева Алия',
  'avatar' => '3.png',
  'team_id' => 3
]);

$factory->state(User::class, 'user4', [
  'name' => 'Александров Артем',
  'avatar' => '4.png',
  'team_id' => 4
]);

$factory->state(User::class, 'user5', [
  'name' => 'Ефремова Иветта',
  'avatar' => '5.png',
  'team_id' => 5
]);
