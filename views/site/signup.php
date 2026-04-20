<div class="main-content">
    <div class="content-box">
        <div class="auth-container">
            <h1>Регистрация</h1>

            <?php if (!empty($message)): ?>
                <div class="auth-success"><?= $message ?></div>
            <?php endif; ?>

            <form method="post" class="auth-form">
                <div class="auth-field">
                    <label>Имя</label>
                    <input type="text" name="name" placeholder="Имя" required>
                </div>
                <div class="auth-field">
                    <label>Имя пользователя (логин)</label>
                    <input type="text" name="login" placeholder="Имя пользователя" required>
                </div>
                <div class="auth-field">
                    <label>Адрес электронной почты</label>
                    <input type="email" name="email" placeholder="Адрес электронной почты" required>
                </div>
                <div class="auth-field">
                    <label>Пароль</label>
                   <input type="password" name="password" placeholder="Пароль" required>
                </div>
                <button type="submit" class="btn-auth">Создать</button>
            </form>

            <div class="auth-footer">
                У вас уже есть аккаунт? <a href="<?= app()->route->getUrl('/login') ?>">Войти</a>
            </div>
        </div>
    </div>
</div>