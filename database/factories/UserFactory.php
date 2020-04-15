<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Neliserp\Core\User;
use Faker\Generator as Faker;

$factory->define(User::class, function (Faker $faker) {
    return [
        'username' => $faker->unique()->userName,
        'password' => Hash::make('secret'),
        'is_active' => true,
        'email' => $faker->unique()->safeEmail,
        'firstname' => $faker->firstName,
        'lastname' => $faker->lastName,
        'position' => $faker->jobTitle,
        'mobile' => $faker->phoneNumber,
        'code' => $faker->numerify('##'),
        'email_verified_at' => null,
        'remember_token' => null,
    ];
});
