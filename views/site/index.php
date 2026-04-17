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
            <div class="card">
                <a href="<?= app()->route->getUrl('/employees/create') ?>">Добавить сотрудника</a>
            </div>
            
            <div class="card">
                <a href="<?= app()->route->getUrl('/departments/create') ?>">Добавить кафедру</a>
            </div>

            <div class="card">
                <a href="<?= app()->route->getUrl('/disciplines/create') ?>">Добавить дисциплину</a>
            </div>
            
            <div class="card">
                <a href="<?= app()->route->getUrl('/AssignmentOfDisciplines') ?>">Назначить дисциплину сотруднику</a>
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