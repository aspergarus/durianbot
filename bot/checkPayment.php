<?php

require_once __DIR__ . "/../vendor/autoload.php";
require_once __DIR__ . "/../config.php";
require_once __DIR__ . "/utils/app.php";
require_once __DIR__ . "/utils/tg.php";
require_once __DIR__ . "/utils/db.php";

// use Minter\MinterAPI;
// $api = new MinterAPI(MINTER_NODE);

processTxInDb(getAllTransactions(WALLET));

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

function processTxInDb($allTx) {
    $grps = getGroups();

    $allTx = array_filter($allTx, function($tx) use ($grps) {
        if (empty($tx['payload'])) {
            return false;
        }

        return validateCurrency($tx, $grps);
    });

    foreach ($allTx as $tx) {
        setInQueue($tx['payload']);
    }
}

function validateCurrency($tx, $grps) {
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
