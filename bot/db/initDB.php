<?php

require_once __DIR__ . "/../utils/db.php";

$fileDb = initDb();

try {
    $fileDb->exec("DROP TABLE messages");
} catch (Exception $e) {}

$fileDb->exec("CREATE TABLE IF NOT EXISTS messages (
                    id INTEGER PRIMARY KEY,
                    updateId INTEGER,
                    status TEXT,
                    date DATE,
                    message TEXT)");

$messages = [
    [
        'message' => 'Just testing...',
        'updateId' => '123',
        'status' => 'waiting',
        'date' => time()
    ],
    [
        'message' => 'More testing...',
        'updateId' => '124',
        'status' => 'waiting',
        'date' => time()
    ],
    [
        'message' => 'SQLite3 is cool...',
        'updateId' => '125',
        'status' => 'waiting',
        'date' => time()
    ]
];

foreach ($messages as $m) {
    $updateId = $m['updateId'];
    $message = $m['message'];
    $status = $m['status'];
    $createdAt = $m['date'];

    saveInDb($updateId, $message, $status, $createdAt);
}
