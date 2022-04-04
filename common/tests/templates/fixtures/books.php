<?php

$faker = Faker\Factory::create();

return [
    'name' => $faker->streetName,
    'author' => $faker->userName,
];
