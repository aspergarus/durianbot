<?php

use Minter\MinterAPI;
use Minter\SDK\MinterCoins\MinterSendCoinTx;
use Minter\SDK\MinterTx;
use Minter\SDK\MinterWallet;

function createWallet() {
    $wallet = MinterWallet::create();

    return $wallet;
}

function getHumanBalance($num) {
    return $num / 1000000000000000000;
}

function transferFunds(MinterAPI $api, $receiverAddress, $senderAddress, $secret, $coin, $val) {
    $commision = 0.01;
    try {
        $tx = new MinterTx([
            'nonce' => $api->getNonce($senderAddress),
            'chainId' => MinterTx::MAINNET_CHAIN_ID,
            'gasPrice' => 1,
            'gasCoin' => 'BIP',
            'type' => MinterSendCoinTx::TYPE,
            'data' => [
                'coin' => $coin,
                'to' => $receiverAddress,
                'value' => $val - $commision
            ],
            'payload' => '', // Here you can put message, but it will increase fee of TX.
            'serviceData' => '',
            'signatureType' => MinterTx::SIGNATURE_SINGLE_TYPE
        ]);

        // Sign transaction and return string. After that $tx will contain some hash of transaction.
        $tx = $tx->sign($secret);

        return $api->send($tx);
    } catch(\Exception $exception) {
        print PHP_EOL . "Fail: " . PHP_EOL;
        // error response in json
        $content = $exception->getResponse()
            ->getBody()
            ->getContents();
        print $content . PHP_EOL . PHP_EOL;
    }

    return false;
}
