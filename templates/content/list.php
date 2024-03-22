<?php include_once "../templates/head.php"; ?>
    <div class="container">
        <table>
            <?php if ($name) { ?>
                <caption><?= $name ?></caption>
            <?php } ?>
            <thead id="thead">
            <tr>
                <th data-type="int">id</th>
                <th data-type="string">ФИО</th>
                <th data-type="string">E-mail</th>
                <th class="address" data-type="string">Адрес</th>
                <th>&nbsp;</th>
                <th>&nbsp;</th>
            </tr>
            </thead>
            <tbody id="tbody">
            <?php
            /** @var User $user */

            use App\Entity\User;

            foreach ($users as $user) { ?>
                <tr id="user<?= $user->getId() ?>" draggable="true" class="draggable">
                    <td><?= $user->getId() ?></td>
                    <td><?= $user->getName() ?></td>
                    <td><?= $user->getEmail() ?></td>
                    <td class="address"><?= $user->getAddress() ?></td>
                    <td class="edit">
                        <button>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512">
                                <path d="M402.6 83.2l90.2 90.2c3.8 3.8 3.8 10 0 13.8L274.4 405.6l-92.8 10.3c-12.4 1.4-22.9-9.1-21.5-21.5l10.3-92.8L388.8 83.2c3.8-3.8 10-3.8 13.8 0zm162-22.9l-48.8-48.8c-15.2-15.2-39.9-15.2-55.2 0l-35.4 35.4c-3.8 3.8-3.8 10 0 13.8l90.2 90.2c3.8 3.8 10 3.8 13.8 0l35.4-35.4c15.2-15.3 15.2-40 0-55.2zM384 346.2V448H64V128h229.8c3.2 0 6.2-1.3 8.5-3.5l40-40c7.6-7.6 2.2-20.5-8.5-20.5H48C21.5 64 0 85.5 0 112v352c0 26.5 21.5 48 48 48h352c26.5 0 48-21.5 48-48V306.2c0-10.7-12.9-16-20.5-8.5l-40 40c-2.2 2.3-3.5 5.3-3.5 8.5z"/>
                            </svg>
                        </button>
                    </td>
                    <td class="delete">
                        <input type="checkbox" data-user-id="<?= $user->getId() ?>">
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
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
            <input id="inp-add-login" type="text" placeholder="Логин" required>
            <label for="inp-add-password">Пароль</label>
            <input id="inp-add-password" type="password" placeholder="Пароль" required>
            <div class="row">
                <label for="show-password">Показать пароль</label>
                <input type="checkbox" id="show-password">
            </div>
            <label for="inp-add-name">Имя пользователя</label>
            <input id="inp-add-name" type="text" placeholder="Имя пользователя" required>
            <label for="inp-add-email">E-mail</label>
            <input id="inp-add-email" type="email" placeholder="E-mail">
            <label for="inp-add-address">Адрес</label>
            <textarea id="inp-add-address" placeholder="Адрес"></textarea>
            <div class="menu-cont">
                <div></div>
                <button id="add-send-button">Отправить</button>
            </div>
            <div class="cancel"></div>
        </form>
    </dialog>

    <dialog id="edit-dialog">
        <div class="modal-inner">
            <h3>Позвоните с вашего номера</h3>
            <p class="client-number nowrap">{{ number }}</p>
            <h3>На номер </h3>
            <p class="caller-number nowrap"><a href="tel:+78005558607">8(800) 555-86-07</a></p>
            <p class="small-text">Звонок бесплатный. <b>Разговаривать ни с кем не нужно.</b> {{ text }}.</p>
            <div class="cancel"></div>
        </div>
    </dialog>
    <script src="/public/js/script.js" type="text/javascript"></script>

<?php include_once "../templates/footer.php"; ?>