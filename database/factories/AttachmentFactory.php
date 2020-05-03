<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Attachment;
use Faker\Generator as Faker;

$factory->define(Attachment::class, function (Faker $faker) {
  return [
    'value' => $faker->sentence(10, true)
  ];
});
