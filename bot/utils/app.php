<?php

use chillerlan\QRCode\QRCode;

global $templates;
$templates = [
    'payment_options' => [
        'ru' => "Варианты оплаты: %s",
        'us' => "Payment options: %s",
    ],
    'coin_at_least' => [
        'ru' => "%s минимально от %s",
        'us' => "%s at least %s",
    ],
];

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

function getLang() {
    return getBotConfig()['lang'] ?? 'us';
}

function getDescription() {
    return getBotConfig()['description'][getLang()] ?? '';
}

function prepareGroups() {
    global $templates;

    $res = [];

    foreach (getGroups() as $group) {
        $res[] = sprintf($templates["coin_at_least"][getLang()], $group['currency'], $group['pay_min']);
    }

    return join("; ", $res);
}

function prepareMessage() {
    global $templates;

    $paymenMethods = prepareGroups();

    return sprintf($templates['payment_options'][getLang()], $paymenMethods);
}

function generateImage($data, $fileName) {
    return (new QRCode())->render($data, $fileName);
}

function getImageName($id) {
    return IMAGE_TEMP_DIR . $id . '.svg';
}

function cleanImage($imageFilePath) {
    unlink($imageFilePath);
}
