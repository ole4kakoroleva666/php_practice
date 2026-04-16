<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Учебно-методическое управление</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
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
                    <li><a href="<?= app()->route->getUrl('/subjects') ?>">Дисциплины</a></li>
                    <li><a href="<?= app()->route->getUrl('/assign') ?>">Назначение дисциплин</a></li>
                    <li><a href="<?= app()->route->getUrl('/reports') ?>">Просмотр данных</a></li>
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