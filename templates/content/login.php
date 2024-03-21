<?php include_once "../templates/head.php"; ?>
    <form method="POST" action="/login" class="container">
        <div class="login-field">
            <?php if ($error) { ?>
                <div class="error"><?= $error ?></div>
            <?php } ?>
            <div class="row">
                <label for="login">Введите логин</label>
                <input type="text" name="login" id="login" placeholder="Логин" required>
            </div>
            <div class="row">
                <label for="password">Введите пароль</label>
                <input type="password" name="password" id="password" placeholder="Пароль" required>
            </div>
            <div class="row end">
                <button class="send">Отправить</button>
            </div>
        </div>
    </form>

<?php include_once "../templates/footer.php"; ?>