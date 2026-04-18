<div class="main-content">
    <div class="content-box">
        <div class="auth-container">
            <h1>Войти</h1>

            <?php if (!empty($message)): ?>
                <div class="auth-error"><?= $message ?></div>
            <?php endif; ?>

            <form method="post" class="auth-form">
                <div class="auth-field">
                    <label>Адрес электронной почты / Имя пользователя</label>
                    <input type="text" name="login" required>
                </div>
                <div class="auth-field">
                    <label>Пароль</label>
                    <input type="password" name="password" required>
                </div>
                <div class="auth-links">
                    <a href="#" class="forgot-password">Забыли пароль?</a>
                </div>
                <button type="submit" class="btn-auth">Войти</button>
            </form>

            <div class="auth-footer">
                У вас еще нет аккаунта? <a href="<?= app()->route->getUrl('/signup') ?>">Зарегистрироваться</a>
            </div>
        </div>
    </div>
</div>