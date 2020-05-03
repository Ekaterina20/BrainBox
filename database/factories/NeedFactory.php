<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Need;
use Faker\Generator as Faker;

$factory->define(Need::class, function (Faker $faker) {
  return [
    'text' => $faker->sentence(mt_rand(4, 6), true),
  ];
});