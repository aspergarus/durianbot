<?php

use chillerlan\QRCode\QRCode;

function getBotConfig() {
    $content = file_get_contents(CONFIG_BOT_FILENAME);

    return unserialize($content);
}

function getGroups() {
    return getBotConfig()['groups'] ?? [];
}

function getAddress() {
    return getBotConfig()['address'] ?? '';
}

function getInterval() {
    return getBotConfig()['interval'] ?? '';
}

function getDescription() {
    return getBotConfig()['description'] ?? '';
}

function prepareGroups() {
    $res = [];

    foreach (getGroups() as $group) {
        $res[] = sprintf("%s at least %s", $group['currency'], $group['pay_min']);
    }

    return join("; ", $res);
}

function prepareMessage() {
    $paymenMethods = prepareGroups();

    return sprintf("Payment options: %s", $paymenMethods);
}

function generateImage($data, $fileName) {
    return (new QRCode())->render($data, $fileName);
}

function getImageName($id) {
    return __DIR__ . '/../QRs/' . $id . '.svg';
}

function cleanImage($imageFilePath) {
    unlink($imageFilePath);
}
