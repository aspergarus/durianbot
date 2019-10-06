<?php

function readConfigFromFile($filename) {
    if (!file_exists($filename)) {
        return [];
    }

    $input = file_get_contents($filename);

    $result = unserialize($input);

    return $result;
}

function getLanguage() {
    if ($_SERVER['REQUEST_URI'] === '/ru') {
        return [
            'lang' => 'ru',
            'lang2' => 'us'
        ];
    }

    if ($_SERVER['REQUEST_URI'] === '/us') {
        return [
            'lang' => 'us',
            'lang2' => 'ru'
        ];
    }

    return ['lang' => 'us'];
}