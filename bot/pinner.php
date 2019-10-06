<?php

require_once __DIR__ . "/../vendor/autoload.php";
require_once __DIR__ . "/../config.php";
require_once __DIR__ . "/utils/app.php";
require_once __DIR__ . "/utils/tg.php";
require_once __DIR__ . "/utils/db.php";

use \React\EventLoop\Factory;
use \unreal4u\TelegramAPI\HttpClientRequestHandler;
use \unreal4u\TelegramAPI\TgLog;

$interval = getInterval() * 60; // in minutes
$currentTime = time();
$firstPinnedRow = getFirsPinnedRowFromDb();
$pinnedTime = $firstPinnedRow['date'] ?? null;

if (!is_null($pinnedTime) && $currentTime < ($pinnedTime + $interval)) {
    // We are not running out of time
    exit();
}

$row = getFirstQueuedRowFromDb();

if (empty($row)) {
    exit();
}

$loop = Factory::create();
$handler = new HttpClientRequestHandler($loop);
$tgLog = new TgLog(BOT_TOKEN, $handler);

$desc = getDescription();
$text = $row['message'] . " ". $desc . " " . BOT_LINK;

sendMessageWithPin($tgLog, $text);
deleteFirstPin($firstPinnedRow);
setInPin($row);

$loop->run();
