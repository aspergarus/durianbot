<?php

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/utils.php';
define('BASE_FIELD', 'currency');

if (!empty($_POST)) {
    require_once './process.php';
    die();
}

require_once './init.php';

$lang = $lang ?? 'us';
$lang2 = $lang2 ?? 'ru';
$interval = $interval ?? 0;
$groups = $groups ?? [];
$description = $description ?? "";
$address = $address ?? '';
$descriptionText = $description[$lang] ?? '';
$languages = [
    'ru' => 'Русский',
    'us' => 'English',
];

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Durianbot config</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.1.0/css/flag-icon.min.css" rel="stylesheet">

    <link rel="stylesheet" href="css/style.css">
    <script src="js/script.js"></script>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light rounded">
        <div class="collapse navbar-collapse" id="navbarsExample09">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="<?php print $lang; ?>" data-toggle="dropdown">
                        <span class="flag-icon flag-icon-<?php print $lang; ?>"></span> <?php print $languages[$lang]; ?>
                    </a>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="<?php print $lang2; ?>"><span class="flag-icon flag-icon-<?php print $lang2; ?>"></span> <?php print $languages[$lang2]; ?></a>
                    </div>
                </li>
            </ul>
        </div>
    </nav>
<form action="./index.php" method="post" class="container">
    <input type="hidden" name="lang" value="<?php print $lang; ?>"/>
    <div class="row">
        <div class="py-5 col-md-4 order-md-2 mb-4">
            <div class="mb-3">
                <input type="submit" id="save" value="Save" class="form-control btn btn-primary" />
            </div>
        </div>

        <div class="col-md-8 order-md-1">
            <div class="text-center">
                <h2>Durian bot configurator</h2>
                <p class="lead">You may change payment configuration, interval of pinning messages, admin wallet and short description of telegram bot</p>
            </div>

            <fieldset class="col-md-12">
                <legend>
                    <span>Currencies</span>
                    <button id="add-new-currency" class="btn btn-info">Add new</button>
                </legend>

                <div id="init-group" hidden class="row mb-3">
                    <div class="col-md-5 mb-3">
                        <label>Currency</label>
                        <input class="form-control" name="currency" value="BIP" />
                    </div>
                    <div class="col-md-5 mb-3">
                        <label>Amount</label>
                        <input class="form-control" name="pay_min" value="0" type="number" min="0" />
                    </div>
                    <div class="col-md-2 mb-3 remove-group-button">
                        <button class="form-control remove-group btn btn-danger">Delete</button>
                    </div>
                </div>

                <div id="groups">
                    <?php foreach ($groups as $num => $group): ?>
                        <div class="row mb-3">
                            <div class="col-md-5 mb-3">
                                <label>Currency</label>
                                <input class="form-control" name="currency[<?php print $num; ?>]" value="<?php print $group['currency']; ?>" />
                            </div>

                            <div class="col-md-5 mb-3">
                                <label>Amount</label>
                                <input class="form-control" name="pay_min[<?php print $num; ?>]" value="<?php print $group['pay_min']; ?>" type="number" min="0" />
                            </div>

                            <div class="col-md-2 mb-3 remove-group-button">
                                <button class="form-control remove-group btn btn-danger">Delete</button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </fieldset>

            <div class="col-md-12">
                <div class="mb-3">
                    <label>Wallet</label>
                    <input class="form-control" name="address" value="<?php print $address; ?>" />
                </div>

                <div class="mb-3">
                    <label>Interval(in minutes)</label>
                    <input class="form-control" name="interval" value="<?php print $interval; ?>" />
                </div>

                <div class="mb-3">
                    <label>
                        <p>Short description of bot</p>
                        <textarea class="form-control" name="description"><?php print $descriptionText; ?></textarea>
                    </label>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.1.1.slim.min.js" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</form>
</body>
</html>