<?php

require_once '../config.php';
define('BASE_FIELD', 'currency');

if (!empty($_POST)) {
    require_once './process.php';
    die();
}

require_once './init.php';

$interval = $interval ?? 0;
$groups = $groups ?? [];
$description = $description ?? "";
$address = $address ?? '';

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Durianbot config</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="js/script.js"></script>
</head>
<body>
<form action="./index.php" method="post">
    <fieldset>
        <legend>
            <span>Валюты</span>
            <button id="add-new-currency">Добавить новую</button>
        </legend>

        <div id="init-group" hidden>
            <label>Валюта</label>
            <input name="currency" value="BIP" />

            <label>Сумма</label>
            <input name="pay_min" value="0" type="number" min="0" />

            <button class="remove-group">Удалить</button>
        </div>

        <div id="groups">
            <?php foreach ($groups as $num => $group): ?>
                <div>
                    <label>Валюта</label>
                    <input name="currency[<?php print $num; ?>]" value="<?php print $group['currency']; ?>" />

                    <label>Сумма</label>
                    <input name="pay_min[<?php print $num; ?>]" value="<?php print $group['pay_min']; ?>" type="number" min="0" />

                    <button class="remove-group">Удалить</button>
                </div>
            <?php endforeach; ?>
        </div>
    </fieldset>

    <label>Кошелёк</label>
    <input name="address" value="<?php print $address; ?>" />

    <label>Интервал в минутах</label>
    <input name="interval" value="<?php print $interval; ?>" />

    <label>
        <p>Краткая инфа о боте</p>
        <textarea name="description"><?php print $description; ?></textarea>
    </label>

    <input type="submit" id="save" value="Сохранить" />
</form>
</body>
</html>