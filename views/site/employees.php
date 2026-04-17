<?php
// Пагинация
$perPage =4;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page = max(1, $page);

$totalEmployees = count($allEmployees);
$totalPages = ceil($totalEmployees / $perPage);
$offset = ($page - 1) * $perPage;
$employeesForPage = array_slice($allEmployees, $offset, $perPage);

$selectedEmployeeId = isset($_GET['employee_id']) ? $_GET['employee_id'] : null;
?>

<div class="main-content">
    <div class="content-box">

        <h1>Данные о сотрудниках</h1>

        <div class="filter-bar">
            <div class="filter-row">
                <label>Сотрудник</label>
                <select id="employee_filter">
                    <option value="">Все сотрудники</option>
                    <?php foreach ($allEmployees as $emp): ?>
                        <option value="<?= $emp['id'] ?>" <?= ($selectedEmployeeId == $emp['id']) ? 'selected' : '' ?>>
                            <?= $emp['last_name'] ?> <?= $emp['first_name'] ?> <?= $emp['middle_name'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="filter-row">
                <button id="showBtn" class="btn-show">Показать</button>
            </div>
        </div>

        <?php if ($selectedEmployeeId && $selectedEmployee): ?>

            <div class="table-card">
                <h5>Информация о сотруднике</h5>
                <table class="table">
                    <tr><th>ФИО</th><td><?= $selectedEmployee->last_name ?> <?= $selectedEmployee->first_name ?> <?= $selectedEmployee->middle_name ?></td></tr>
                    <tr><th>Должность</th><td><?= $selectedEmployee->position ?></td></tr>
                    <tr><th>Кафедра</th><td><?= $selectedEmployee->department->name ?? '—' ?></td></tr>
                    <tr><th>Дата рождения</th><td><?= $selectedEmployee->birth_date ? date('d.m.Y', strtotime($selectedEmployee->birth_date)) : '—' ?></td></tr>
                    <tr><th>Дисциплины</th><td>
                        <?php foreach ($selectedEmployee->disciplines as $d): ?>
                            <?= $d->name ?><br>
                        <?php endforeach; ?>
                    </td></tr>
                </table>
            </div>

        <?php else: ?>

            <div class="table-card">
                <table class="table">
                    <thead>
                        <tr>
                            <th>№</th>
                            <th>ФИО</th>
                            <th>Должность</th>
                            <th>Кафедра</th>
                            <th>Дата рождения</th>
                            <th>Дисциплины</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = $offset + 1; ?>
                        <?php foreach ($employeesForPage as $emp): ?>
                        <tr>
                            <td><?= $i++ ?></td>
                            <td><?= $emp['last_name'] ?> <?= $emp['first_name'] ?> <?= $emp['middle_name'] ?></td>
                            <td><?= $emp['position'] ?></td>
                            <td><?= $emp['department']['name'] ?? '—' ?></td>
                            <td><?= $emp['birth_date'] ? date('d.m.Y', strtotime($emp['birth_date'])) : '—' ?></td>
                            <td>
                                <?php foreach ($emp['disciplines'] as $d): ?>
                                    <?= $d['name'] ?><br>
                                <?php endforeach; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <?php if ($totalPages > 1): ?>
            <div class="pagination">
                <a href="?page=<?= $page - 1 ?>" class="page-link <?= $page <= 1 ? 'disabled' : '' ?>">«</a>
                <?php for ($p = 1; $p <= $totalPages; $p++): ?>
                    <a href="?page=<?= $p ?>" class="page-link <?= $p == $page ? 'active' : '' ?>"><?= $p ?></a>
                <?php endfor; ?>
                <a href="?page=<?= $page + 1 ?>" class="page-link <?= $page >= $totalPages ? 'disabled' : '' ?>">»</a>
            </div>
            <?php endif; ?>

        <?php endif; ?>

    </div>
</div>

<script>
document.getElementById('showBtn').onclick = function() {
    let employeeId = document.getElementById('employee_filter').value;
    window.location.href = employeeId ? '/employees?employee_id=' + employeeId : '/employees';
};
</script>