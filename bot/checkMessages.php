<?php

require_once __DIR__ . "/../vendor/autoload.php";
require_once __DIR__ . "/../config.php";
require_once __DIR__ . "/utils/app.php";
require_once __DIR__ . "/utils/tg.php";
require_once __DIR__ . "/utils/db.php";

use \React\EventLoop\Factory;
use \unreal4u\TelegramAPI\HttpClientRequestHandler;
use \unreal4u\TelegramAPI\TgLog;

$loop = Factory::create();

$handler = new HttpClientRequestHandler($loop);
$tgLog = new TgLog(BOT_TOKEN, $handler);

saveLastUpdates($tgLog);

$loop->run();
