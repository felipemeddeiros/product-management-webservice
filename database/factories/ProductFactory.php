<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Product;
use Faker\Generator as Faker;

$factory->define(Product::class, function (Faker $faker) {
    return [
        'name' => str_replace('.', '', $faker->sentence(1, true)),
        // 'image' => Str::random(5).'/'.Str::random(5).'/'.Str::random(5),
        'status' => rand(1,4)
    ];
});
