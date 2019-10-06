<?php

$config = readConfigFromFile(CONFIG_BOT_FILENAME);

if (!empty($config)) {
    extract($config);
}

function readConfigFromFile($filename) {
    if (!file_exists($filename)) {
        return [];
    }

    $input = file_get_contents($filename);

    $result = unserialize($input);

    return $result;
}