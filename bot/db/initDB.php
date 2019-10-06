<?php

require_once __DIR__ . "/../utils/db.php";

$fileDb = initDb();

try {
    $fileDb->exec("DROP TABLE messages");
    $fileDb->exec("DROP TABLE users");
} catch (Exception $e) {}

$fileDb->exec("CREATE TABLE IF NOT EXISTS messages (
                    id INTEGER PRIMARY KEY,
                    updateId INTEGER,
                    status TEXT,
                    date DATE,
                    message TEXT,
                    userId INTEGER
)");

$fileDb->exec("CREATE TABLE IF NOT EXISTS users (
                    id INTEGER PRIMARY KEY,
                    status INTEGER,
                    address TEXT,
                    tgId TEXT,
                    secret TEXT,
                    seed TEXT
                    )");
