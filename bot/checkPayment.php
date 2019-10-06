<?php

require_once __DIR__ . "/../vendor/autoload.php";
require_once __DIR__ . "/../config.php";
require_once __DIR__ . "/utils/app.php";
require_once __DIR__ . "/utils/tg.php";
require_once __DIR__ . "/utils/db.php";
require_once __DIR__ . "/utils/minter.php";

use Minter\MinterAPI;
$api = new MinterAPI(MINTER_NODE);

$wallets = getWallets();

foreach ($wallets as $wallet) {
    $address = $wallet['address'];
    $balance = getBalance($api, $address);
    $balance = (array) $balance->result->balance;

    $balance = array_filter($balance, function ($val) {
        return $val > 0;
    });

    foreach ($balance as $coin => $val) {
        processWalletBalanceInDb($coin, getHumanBalance($val), $wallet, $api);
    }

    // processTxInDb(getAllTransactions(WALLET));
}

function getBalance(MinterAPI $api, $address) {
    return $api->getBalance($address);
}

function getAllTransactions($address) {
    $allTransactionsData = [];
    $continueParsing = true;

    for ($pageNum = 1; $continueParsing;$pageNum++) {
        $pageTransactions = getPageTransactions($address, $pageNum);
        $continueParsing = !empty($pageTransactions['links']['next']);

        $pageTransactions = getUsefulInfo($pageTransactions);
        $allTransactionsData = array_merge($allTransactionsData, $pageTransactions);
    }

    return $allTransactionsData;
}

function getPageTransactions($address, $page) {
    $url = sprintf('https://explorer-api.minter.network/api/v1/addresses/%s/transactions?page=%s', $address, $page);
    $transactionsData = json_decode(file_get_contents($url), true);

    return $transactionsData;
}

function getUsefulInfo($transactions) {
    return array_filter(array_map(function($tx) {
        return [
            'payload' => trim($tx['payload']),
            'from' => $tx['from'],
            'coin' => $tx['data']['coin'] ?? '',
            'val' => $tx['data']['value'] ?? '',
        ];
    }, $transactions['data']), function($tx) {
        return !empty($tx['val']) && !empty($tx['coin']);
    });
}

function processWalletBalanceInDb($coin, $val, $wallet, MinterAPI $api) {
    $tx = [
        'coin' => $coin,
        'val' => $val,
    ];

    if (!validateCurrency($tx)) {
        return;
    }

    $result = transferFunds($api, getAddress(), $wallet['address'], $wallet['secret'], $coin, $val);

    if (empty($result)) {
        return;
    }

    setInQueue($wallet['id']);
    // deleteUser($wallet['id']);
}

function processTxInDb($allTx) {
    $allTx = array_filter($allTx, function($tx) {
        if (empty($tx['payload'])) {
            return false;
        }

        return validateCurrency($tx);
    });

    foreach ($allTx as $tx) {
        setInQueue($tx['payload']);
    }
}

function validateCurrency($tx) {
    $grps = getGroups();

    $currency = strtoupper($tx['coin']);
    foreach ($grps as $grp) {
        if (strtoupper($grp['currency']) == $currency) {
            if (trim($grp['pay_min']) <= trim($tx['val'])) {
                return true;
            }
        }
    }

    return false;
}
