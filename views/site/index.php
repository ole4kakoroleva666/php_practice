<div class="titleindex">
    <div class="title-header">
        <div>
            <h1>Главная</h1>
            <h2>Добро пожаловать в систему учебно-методического управления!</h2>
        </div>
        <img src="/images/owl.svg" alt="Сова" class="owl-icon">
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
        <a href="<?= app()->route->getUrl('/AssignmentOfDisciplines') ?>">Назначить цисциплину сотруднику</a>
    </div>
    
    <div class="card">
        <a href="<?= app()->route->getUrl('/reposts') ?>">Просмотр данных</a>
    </div>
    
    <div class="card">
        <a href="<?= app()->route->getUrl('/employees') ?>">Сотрудники</a>
    </div>
</div>
