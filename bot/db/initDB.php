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

$messages = [
    [
        'message' => 'Just testing...',
        'updateId' => '123',
        'status' => 'waiting',
        'date' => time(),
        'userId' => 1,
    ],
    [
        'message' => 'More testing...',
        'updateId' => '124',
        'status' => 'waiting',
        'date' => time(),
        'userId' => 1,
    ],
    [
        'message' => 'SQLite3 is cool...',
        'updateId' => '125',
        'status' => 'waiting',
        'date' => time(),
        'userId' => 1,
    ]
];

foreach ($messages as $m) {
    $updateId = $m['updateId'];
    $message = $m['message'];
    $status = $m['status'];
    $createdAt = $m['date'];
    $uid = $m['userId'];

    saveMessage($updateId, $message, $status, $createdAt, $uid);
}
