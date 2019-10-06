<?php

$config = readConfigFromFile(CONFIG_BOT_FILENAME);
$config = array_merge($config, getLanguage());

if (!empty($config)) {
    extract($config);
}
