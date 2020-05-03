<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Rule;
use Faker\Generator as Faker;

$factory->define(Rule::class, function (Faker $faker) {
  return [
    'text' => $faker->sentence(mt_rand(4, 6), true),
  ];
});
