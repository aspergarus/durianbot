<?php

// Altering config of bot
define('CONFIG_BOT_FILENAME', __DIR__ . '/config');

// Minter
define('WALLET', 'Mxe7d1eb69b89598f9b8e532b40ab954ef7a6d0b02');
define('MINTER_NODE', "http://api-01.minter.store:8841");

// Alternatives for MINTER_NODE
// 'http://api-01.minter.store:8841';
// "https://api.minter.stakeholder.space/"
// "http://api.minter.one";
// 'https://minter-node-1.testnet.minter.network:8841'

// Static config of bot
define("BOT_TOKEN", "816007154:AAEYlrhj1vN8w4xVHhZYtXKLT9Tgy_I18l4");
define("A_USER_CHAT_ID", "-1001458034081");
define("BOT_LINK", "@absurdman_bot");

// DB
define("LIFE_TIME_RECORDS", 3600 * 24); // 1 day

// Payment consts
define('PAYMENT_COMPLETED', 1);
define('PAYMENT_WAITING', 0);