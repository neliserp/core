<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Neliserp\Core\User;
use Faker\Generator as Faker;

$factory->define(User::class, function (Faker $faker) {
    return [
        'code' => $faker->unique()->numerify('USER-####'),
        'name' => $faker->sentence,
    ];
});
