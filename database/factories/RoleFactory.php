<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Neliserp\Core\Role;
use Faker\Generator as Faker;

$factory->define(Role::class, function (Faker $faker) {
    return [
        'code' => $faker->unique()->numerify('ROLE-####'),
        'name' => $faker->sentence,
    ];
});
