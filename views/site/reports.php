<?php
$perPage = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page = max(1, $page);

$total = count($reports);
$totalPages = ceil($total / $perPage);
$offset = ($page - 1) * $perPage;
$items = array_slice($reports, $offset, $perPage);

$selectedEmployeeId = isset($_GET['employee_id']) ? $_GET['employee_id'] : null;
$selectedDisciplineId = isset($_GET['discipline_id']) ? $_GET['discipline_id'] : null;
$selectedDepartmentId = isset($_GET['department_id']) ? $_GET['department_id'] : null;
?>

<div class="main-content">
    <div class="content-box">

        <h1>Просмотр данных</h1>

        <!-- Форма фильтров -->
        <div class="filter-bar">
            <div class="filter-row">
                <label>Сотрудник</label>
                <select id="employee_filter">
                    <option value="">Выберите сотрудника</option>
                    <?php foreach ($employees as $emp): ?>
                        <option value="<?= $emp['id'] ?>" <?= ($selectedEmployeeId == $emp['id']) ? 'selected' : '' ?>>
                            <?= $emp['last_name'] ?> <?= $emp['first_name'] ?> <?= $emp['middle_name'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="filter-row">
                <label>Дисциплина</label>
                <select id="discipline_filter">
                    <option value="">Выберите дисциплину</option>
                    <?php foreach ($disciplines as $disc): ?>
                        <option value="<?= $disc['id'] ?>" <?= ($selectedDisciplineId == $disc['id']) ? 'selected' : '' ?>>
                            <?= $disc['name'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="filter-row">
                <label>Кафедра</label>
                <select id="department_filter">
                    <option value="">Выберите кафедру</option>
                    <?php foreach ($departments as $dept): ?>
                        <option value="<?= $dept['id'] ?>" <?= ($selectedDepartmentId == $dept['id']) ? 'selected' : '' ?>>
                            <?= $dept['name'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <button id="showBtn" class="btn-show">Показать</button>
        </div>

        <!-- Таблица результатов -->
        <div class="table-card">
            <table class="table">
                <thead>
                    <tr>
                        <th>№</th>
                        <th>Сотрудник</th>
                        <th>Дисциплина</th>
                        <th>Кафедра</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = $offset + 1; ?>
                    <?php foreach ($items as $item): ?>
                    <tr>
                        <td><?= $i++ ?></td>
                        <td><?= $item['employee']['last_name'] ?> <?= $item['employee']['first_name'] ?> <?= $item['employee']['middle_name'] ?></td>
                        <td><?= $item['discipline']['name'] ?></td>
                        <td><?= $item['employee']['department']['name'] ?? '—' ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <?php if ($totalPages > 1): ?>
        <div class="pagination">
            <?php if ($page > 1): ?>
                <a href="?page=<?= $page - 1 ?>" class="page-link">«</a>
            <?php endif; ?>
            
            <span class="page-link active"><?= $page ?></span>
            
            <?php if ($page < $totalPages): ?>
                <a href="?page=<?= $page + 1 ?>" class="page-link"><?= $page + 1 ?></a>
                <a href="?page=<?= $page + 1 ?>" class="page-link">»</a>
            <?php endif; ?>
        </div>
        <?php endif; ?>

    </div>
</div>

<script>
document.getElementById('showBtn').onclick = function() {
    let employeeId = document.getElementById('employee_filter').value;
    let disciplineId = document.getElementById('discipline_filter').value;
    let departmentId = document.getElementById('department_filter').value;
    
    let url = '/reports?';
    let params = [];
    
    if (employeeId) params.push('employee_id=' + employeeId);
    if (disciplineId) params.push('discipline_id=' + disciplineId);
    if (departmentId) params.push('department_id=' + departmentId);
    
    window.location.href = url + params.join('&');
};
</script>