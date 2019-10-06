<?php

// Altering config of bot
define('CONFIG_BOT_FILENAME', __DIR__ . '/config');

// Minter
define('MINTER_NODE', "http://api-01.minter.store:8841");

// Alternatives for MINTER_NODE
// 'http://api-01.minter.store:8841';
// "https://api.minter.stakeholder.space/"
// "http://api.minter.one";
// 'https://minter-node-1.testnet.minter.network:8841'

// Static config of bot
define("BOT_TOKEN", getenv("BOT_TOKEN"));
define("A_USER_CHAT_ID", "-1001458034081");
define("BOT_LINK", "@absurdman_bot");

// DB
define("LIFE_TIME_RECORDS", 3600 * 24); // 1 day

// Payment consts
define('PAYMENT_COMPLETED', 1);
define('PAYMENT_WAITING', 0);

define("IMAGE_TEMP_DIR", __DIR__ . "/bot/QRs/");