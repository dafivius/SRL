<?php

use SRL\Builder;
use SRL\SRL;

require_once __DIR__ . '/../vendor/autoload.php';

$query = SRL::literally('colo')
    ->optional('u')
    ->literally('r')
    ->eitherOf(function (Builder $query) {
        $query->literally(':')->and(function (Builder $query) {
            $query->literally(' is');
        });
    })
    ->whitespace()
    ->capture(function (Builder $query) {
        $query->anyLetter()->onceOrMore();
    }, 'color')
    ->literally('.');

var_dump($query->isMatching('my favorite color: blue.')); // true
var_dump($query->isMatching('my favorite colour is green.')); // true
var_dump($query->isMatching('my favorite colour is green!')); // false

$matches = $query->getMatches('my favorite colour is green. And my favorite color: yellow.');

foreach ($matches as $match) {
    echo $match->getName() . ': ' . $match->getMatch() . "\n";
}
// color: green
// color: yellow