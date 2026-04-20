<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Учебно-методическое управление</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <div class="app-container">
        <!-- Боковое меню -->
        <aside class="sidebar">
            <div class="sidebar-nav">
                <h2>Учебно-методическое управление</h2>
                <ul>
                    <li><a href="<?= app()->route->getUrl('/') ?>">Главная</a></li>
                    <li><a href="<?= app()->route->getUrl('/employees') ?>">Сотрудники</a></li>
                    <li><a href="<?= app()->route->getUrl('/departments') ?>">Кафедры</a></li>
                    <li><a href="<?= app()->route->getUrl('/disciplines') ?>">Дисциплины</a></li>
                    
                    <?php if (in_array(app()->auth::user()->role, ['admin', 'decanat'])): ?>
                        <li><a href="<?= app()->route->getUrl('/assignment') ?>">Назначение дисциплин</a></li>
                    <?php endif; ?>
                    
                    <li><a href="<?= app()->route->getUrl('/reports') ?>">Просмотр данных</a></li>
                    
                    <?php if (app()->auth::user()->role === 'admin'): ?>
                        <li><a href="<?= app()->route->getUrl('/decanat/create') ?>">Добавить сотрудника деканата</a></li>
                    <?php endif; ?>
                </ul>
                <div class="logout">
                    <a href="<?= app()->route->getUrl('/logout') ?>">
                        <img src="/images/logout.svg" alt="Выход" class="logout-icon">Выход
                    </a>
                </div>
            </div>
        </aside>

        <!-- Основной контент -->
        <main class="main-content">
            <div class="container">
                <?= $content ?? '' ?>
            </div>
        </main>
    </div>
</body>
</html>