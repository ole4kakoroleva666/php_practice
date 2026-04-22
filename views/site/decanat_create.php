    <div class="content-box">
        <h1>Добавление сотрудника деканата</h1>

        <?php if (!empty($message)): ?>
    <pre><?= $message ?></pre>
<?php endif; ?>

        <?php if (!empty($message)): ?>
            <div class="auth-error"><?= $message ?></div>
        <?php endif; ?>

        <form method="post" action="<?= app()->route->getUrl('/decanat/create') ?>" class="employee-form">
        <input name="csrf_token" type="hidden" value="<?= app()->auth::generateCSRF() ?>"/>
            <div class="form-row">
                <label>Имя</label>
                <input type="text" name="name" required>
            </div>

            <div class="form-row">
                <label>Логин</label>
                <input type="text" name="login" required>
            </div>

            <div class="form-row">
                <label>Email</label>
                <input type="email" name="email" required>
            </div>

            <div class="form-row">
                <label>Пароль</label>
                <input type="password" name="password" required>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-save">Сохранить</button>
            </div>
        </form>
    </div>