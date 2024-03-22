<!DOCTYPE HTML>
<html lang="ru-RU">
<head>
    <title>Тестовое задание</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <link rel="icon" href="/public/img/favicon.ico" type="image/x-icon">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="/public/css/style.css?<?= time() ?>" type="text/css" media="screen"/>
</head>
<body>
<?php if ($user) { ?>
    <div class="container">
        <div class="row">
            <div class="menu-cont">
                <div>
                    <?= $user->getName() ?>
                </div>
                <div>
                    <a href="/logout">
                        <button>Logout</button>
                    </a>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
