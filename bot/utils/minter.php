<?php

use Minter\SDK\MinterWallet;

function createWallet() {
    $wallet = MinterWallet::create();

    return $wallet;
}