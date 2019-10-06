<?php

function initDb() {
    $dbLocation = __DIR__ . '/../db/messaging.sqlite3';
    $fileDB = new PDO('sqlite:' . $dbLocation);
    // Set errormode to exceptions
    $fileDB->setAttribute(PDO::ATTR_ERRMODE,
        PDO::ERRMODE_EXCEPTION);

    return $fileDB;
}

function saveMessage($updateId, $message, $status, $time, $uid) {
    $fileDb = initDb();

    $query = "INSERT INTO 
        messages (updateId, message, status, date, userId)
    VALUES (:updateId, :message, :status, :date, :uid)";
    $stmt = $fileDb->prepare($query);

    // Bind parameters to statement variables
    $stmt->bindParam(':updateId', $updateId);
    $stmt->bindParam(':message', $message);
    $stmt->bindParam(':status', $status);
    $stmt->bindParam(':date', $time);
    $stmt->bindParam(':uid', $uid);

    $stmt->execute();
}

function getFirstFromDb() {
    $fileDb = initDb();

    $query = "SELECT * FROM messages ORDER BY id LIMIT 1";
    $result = $fileDb->query($query);

    return $result->fetch(PDO::FETCH_ASSOC);
}

function deleteFirstFromDb() {
    $fileDb = initDb();

    $query = "DELETE FROM messages WHERE id IN (SELECT id FROM messages ORDER BY id LIMIT 1)";
    $fileDb->exec($query);
}

function getLastUpdatesFromDb() {
    $fileDb = initDb();

    $query = "SELECT updateId FROM messages ORDER BY updateId DESC LIMIT 1";
    $result = $fileDb->query($query);

    return $result->fetchColumn();
}

function getFirsPinnedRowFromDb() {
    $fileDb = initDb();

    $query = "SELECT * FROM messages WHERE status = 'pinned' ORDER BY updateId LIMIT 1";
    $result = $fileDb->query($query);

    return $result->fetch(PDO::FETCH_ASSOC);
}

function getFirstQueuedRowFromDb() {
    $fileDb = initDb();

    $query = "SELECT * FROM messages WHERE status = 'queue' ORDER BY updateId LIMIT 1";
    $result = $fileDb->query($query);

    return $result->fetch(PDO::FETCH_ASSOC);
}

function setInQueue($userId) {
    $fileDb = initDb();

    $query = "UPDATE messages SET status = 'queue' WHERE userId = :userId";
    $stmt = $fileDb->prepare($query);
    $stmt->bindParam(':userId', $userId);

    $stmt->execute();
}

function setInPin($message) {
    $fileDb = initDb();

    $date = time();
    $query = "UPDATE messages SET status = 'pinned', date = :date  WHERE id = :id";
    $stmt = $fileDb->prepare($query);
    $stmt->bindParam(':id', $message['id']);
    $stmt->bindParam(':date', $date);

    $stmt->execute();
}

function deleteFirstPin($row) {
    $fileDb = initDb();

    $query = "DELETE FROM messages WHERE id = :id";
    $stmt = $fileDb->prepare($query);
    $stmt->bindParam(':id', $row['id']);

    $stmt->execute();
}

function saveUser($address, $tgId, $secret, $seed, $status) {
    $fileDb = initDb();

    $query = "INSERT INTO users (address, tgId, secret, seed, status) VALUES (:addr, :tg, :secret, :seed, :status)";
    $stmt = $fileDb->prepare($query);

    // Bind parameters to statement variables
    $stmt->bindParam(':addr', $address);
    $stmt->bindParam(':tg', $tgId);
    $stmt->bindParam(':secret', $secret);
    $stmt->bindParam(':seed', $seed);
    $stmt->bindParam(':status', $status);

    $stmt->execute();

    return $fileDb->lastInsertId();
}

function getWallets() {
    $fileDb = initDb();
    $status = PAYMENT_WAITING;

    $query = "SELECT * FROM users WHERE status = :status";
    $stmt = $fileDb->prepare($query);
    $stmt->execute([
        ':status' => $status
    ]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function deleteUser($userId) {
    $fileDb = initDb();

    $query = "DELETE FROM users WHERE id = :id";
    $stmt = $fileDb->prepare($query);
    $stmt->bindParam(':id',  $userId);

    $stmt->execute();
}

function markProcessedWallet($userId) {
    $fileDb = initDb();

    $status = PAYMENT_COMPLETED;

    $query = "UPDATE users SET status = :status WHERE id = :id";
    $stmt = $fileDb->prepare($query);
    $stmt->bindParam(':id',  $userId);
    $stmt->bindParam(':status',  $status);

    $stmt->execute();
}
