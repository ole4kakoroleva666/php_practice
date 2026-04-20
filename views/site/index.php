<div class="main-content">
    <div class="content-box">
        <div class="titleindex">
            <div class="title-header">
                <div>
                    <h1>Главная</h1>
                    <h2>Добро пожаловать в систему учебно-методического управления!</h2>
                </div>
                <img src="/images/material-symbols-light_owl-outline-rounded.svg" alt="Сова" class="owl-icon">
            </div>
        </div>

        <div class="cards">

            <?php if (\Src\Auth\Auth::check() && app()->auth::user()->role === 'admin'): ?>
                <div class="card">
                    <a href="<?= app()->route->getUrl('/decanat/create') ?>">Добавить сотрудника деканата</a>
                </div>
            <?php endif; ?>

            <div class="card">
                <a href="<?= app()->route->getUrl('/employees/create') ?>">Добавить сотрудника</a>
            </div>
            
            <div class="card">
                <a href="<?= app()->route->getUrl('/departments') ?>">Добавить кафедру</a>
            </div>

            <div class="card">
                <a href="<?= app()->route->getUrl('/disciplines') ?>">Добавить дисциплину</a>
            </div>
            
            <div class="card">
                <a href="<?= app()->route->getUrl('/assignment') ?>">Назначить дисциплину сотруднику</a>
            </div>
            
            <div class="card">
                <a href="<?= app()->route->getUrl('/reports') ?>">Просмотр данных</a>
            </div>
            
            <div class="card">
                <a href="<?= app()->route->getUrl('/employees') ?>">Сотрудники</a>
            </div>
        </div>
    </div>
</div>