<?php

use Faker\Generator as Faker;

$factory->define(App\Post::class, function (Faker $faker) {

    // sample dummy title
    $title = ['今日は快晴！','今日は晴れ！','今日は薄曇！','今日は曇り！','今日は煙霧！','今日は砂塵嵐！','今日は地吹雪！','今日は霧！','今日は霧雨！','今日は雨！','今日はみぞれ！','今日は雪！','今日は霰！','今日はひょう！','今日は雷！'];

    // sample dummy content
    $content = ['過ごしやすい日だった！','まずまずな日だった！','大変な日だった！','良い日だった！','最悪な日だった！'];

    return [
        'title' => $faker->randomElement($title),
        'content' => $faker->randomElement($content)
    ];
});
