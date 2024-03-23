<?php include_once "../templates/head.php"; ?>
    <div class="container">
        <table>
            <?php if ($name) { ?>
                <caption><?= $name ?></caption>
            <?php } ?>
            <thead id="thead">
            <tr>
                <th data-type="int">id</th>
                <th data-type="string">Логин</th>
                <th data-type="string">ФИО</th>
                <th data-type="string">E-mail</th>
                <th class="address" data-type="string">Адрес</th>
                <th>&nbsp;</th>
                <?php if ($user->isAdmin()) { ?>
                    <th>&nbsp;</th><?php } ?>
            </tr>
            </thead>
            <tbody id="tbody">
            <?php foreach ($users as $itemUser) { ?>
                <tr id="user<?= $itemUser->getId() ?>" data-user-id="<?= $itemUser->getId() ?>" draggable="true"
                    class="draggable<?php if ($itemUser->isAdmin()) echo " admin" ?>">
                    <td><?= $itemUser->getId() ?></td>
                    <td class="td-login"><?= $itemUser->getLogin() ?></td>
                    <td class="td-name"><?= $itemUser->getName() ?></td>
                    <td class="td-email"><?= $itemUser->getEmail() ?></td>
                    <td class="td-address"><?= $itemUser->getAddress() ?></td>
                    <?php if ($user->isAdmin() || $user->getId() == $itemUser->getId()) { ?>
                        <td class="td-edit">
                            <button>
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512">
                                    <path d="M402.6 83.2l90.2 90.2c3.8 3.8 3.8 10 0 13.8L274.4 405.6l-92.8 10.3c-12.4 1.4-22.9-9.1-21.5-21.5l10.3-92.8L388.8 83.2c3.8-3.8 10-3.8 13.8 0zm162-22.9l-48.8-48.8c-15.2-15.2-39.9-15.2-55.2 0l-35.4 35.4c-3.8 3.8-3.8 10 0 13.8l90.2 90.2c3.8 3.8 10 3.8 13.8 0l35.4-35.4c15.2-15.3 15.2-40 0-55.2zM384 346.2V448H64V128h229.8c3.2 0 6.2-1.3 8.5-3.5l40-40c7.6-7.6 2.2-20.5-8.5-20.5H48C21.5 64 0 85.5 0 112v352c0 26.5 21.5 48 48 48h352c26.5 0 48-21.5 48-48V306.2c0-10.7-12.9-16-20.5-8.5l-40 40c-2.2 2.3-3.5 5.3-3.5 8.5z"/>
                                </svg>
                            </button>
                        </td><?php } else { ?>
                        <td></td>
                    <?php } ?>
                    <?php if ($user->isAdmin()) { ?>
                        <td class="td-delete">
                            <?php if (!$itemUser->isAdmin()) echo '<input type="checkbox">'; ?>
                        </td><?php } ?>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
<?php if ($user->isAdmin()) { ?>
    <div class="container">
        <div class="row">
            <div class="menu-cont">
                <div>
                    <button id="add-button">добавить</button>
                </div>
                <div>
                    <button id="delete-button">Удалить</button>
                </div>
            </div>
        </div>
    </div>

    <dialog id="add-dialog">
        <form name="add-form" class="modal-inner">
            <h3>Добавление нового пользователя</h3>
            <label for="inp-add-login">Логин</label>
            <div class="error" id="err-login"></div>
            <input id="inp-add-login" type="text" name="login" placeholder="Логин" required>
            <label for="inp-add-password">Пароль</label>
            <div class="error" id="err-password"></div>
            <input id="inp-add-password" type="password" name="password" placeholder="Пароль">
            <div class="row">
                <label for="show-password">Показать пароль</label>
                <input type="checkbox" id="show-password">
            </div>
            <label for="inp-add-name">Имя пользователя</label>
            <div class="error" id="err-name"></div>
            <input id="inp-add-name" type="text" name="name" placeholder="Имя пользователя" required>
            <label for="inp-add-email">E-mail</label>
            <div class="error" id="err-email"></div>
            <input id="inp-add-email" type="email" name="email" placeholder="E-mail">
            <label for="inp-add-address">Адрес</label>
            <div class="error" id="err-address"></div>
            <textarea id="inp-add-address" name="address" placeholder="Адрес"></textarea>
            <div class="menu-cont">
                <div></div>
                <button id="add-send-button">Отправить</button>
            </div>
            <div class="error" id="err-unknown"></div>
            <div class="cancel"></div>
        </form>
    </dialog>
<?php } ?>
    <dialog id="edit-dialog">
        <form name="edit-form" class="modal-inner">
            <h3>Редактирование пользователя id <span id="edit-id"></span></h3>
            <label for="inp-edit-name">Имя пользователя</label>
            <div class="error" id="err-edit-name"></div>
            <input id="inp-edit-name" type="text" name="name" placeholder="Имя пользователя" required>
            <label for="inp-edit-email">E-mail</label>
            <div class="error" id="err-edit-email"></div>
            <input id="inp-edit-email" type="email" name="email" placeholder="E-mail">
            <label for="inp-edit-address">Адрес</label>
            <div class="error" id="err-edit-address"></div>
            <textarea id="inp-edit-address" name="address" placeholder="Адрес"></textarea>
            <div class="menu-cont">
                <div></div>
                <button id="edit-send-button">Сохранить</button>
            </div>
            <div class="error" id="err-edit-unknown"></div>
            <div class="cancel"></div>
        </form>
    </dialog>

    <script src="/public/js/script.js" type="text/javascript"></script>
<?php include_once "../templates/footer.php"; ?>