<?php

function initDb() {
    $dbLocation = __DIR__ . '/../db/messaging.sqlite3';
    $fileDB = new PDO('sqlite:' . $dbLocation);
    // Set errormode to exceptions
    $fileDB->setAttribute(PDO::ATTR_ERRMODE,
        PDO::ERRMODE_EXCEPTION);

    return $fileDB;
}

function saveInDb($updateId, $message, $status, $time) {
    $fileDb = initDb();

    $query = "INSERT INTO messages (updateId, message, status, date) VALUES (:updateId, :message, :status, :date)";
    $stmt = $fileDb->prepare($query);

    // Bind parameters to statement variables
    $stmt->bindParam(':updateId', $updateId);
    $stmt->bindParam(':message', $message);
    $stmt->bindParam(':status', $status);
    $stmt->bindParam(':date', $time);

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

function setInQueue($updateId) {
    $fileDb = initDb();

    $query = "UPDATE messages SET status = 'queue' WHERE updateId = :updateId";
    $stmt = $fileDb->prepare($query);
    $stmt->bindParam(':updateId', $updateId);

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
