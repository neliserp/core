<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Neliserp\Core\Permission;
use Faker\Generator as Faker;

$factory->define(Permission::class, function (Faker $faker) {
    return [
        'code' => $faker->unique()->numerify('PERM-####'),
        'name' => $faker->sentence,
    ];
});
