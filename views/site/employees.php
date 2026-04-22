<?php
$perPage = 4;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page = max(1, $page);

$totalEmployees = count($allEmployees);
$totalPages = ceil($totalEmployees / $perPage);
$offset = ($page - 1) * $perPage;
$employeesForPage = array_slice($allEmployees, $offset, $perPage);

$selectedEmployeeId = isset($_GET['employee_id']) ? $_GET['employee_id'] : null;
$searchQuery = $search ?? '';
?>

<div class="main-content">
    <div class="content-box">

        <h1>Данные о сотрудниках</h1>

        <?php if (!empty($message)): ?>
    <pre><?= $message ?></pre>
<?php endif; ?>

        <div class="filter-bar">
            <div class="filter-row">
                <label>Поиск по ФИО</label>
                <input type="text" id="searchInput" placeholder="Введите фамилию, имя или отчество" value="<?= htmlspecialchars($searchQuery) ?>">
            </div>

            <div class="filter-row">
                <label>Сотрудник</label>
                <select id="employee_filter">
                    <option value="">Все сотрудники</option>
                    <?php foreach ($allEmployees as $emp): ?>
                        <option value="<?= $emp['id'] ?>" <?= ($selectedEmployeeId == $emp['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($emp['last_name']) ?>
                            <?= htmlspecialchars($emp['first_name']) ?>
                            <?= htmlspecialchars($emp['middle_name']) ?>
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
                    <tr>
                        <th>ФИО</th>
                        <td>
                            <?= htmlspecialchars($selectedEmployee->last_name) ?>
                            <?= htmlspecialchars($selectedEmployee->first_name) ?>
                            <?= htmlspecialchars($selectedEmployee->middle_name) ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Должность</th>
                        <td><?= htmlspecialchars($selectedEmployee->position) ?></td>
                    </tr>
                    <tr>
                        <th>Кафедра</th>
                        <td><?= htmlspecialchars($selectedEmployee->department->name ?? '—') ?></td>
                    </tr>
                    <tr>
                        <th>Дата рождения</th>
                        <td><?= htmlspecialchars($selectedEmployee->birth_date ? date('d.m.Y', strtotime($selectedEmployee->birth_date)) : '—') ?></td>
                    </tr>
                    <tr>
                        <th>Дисциплины</th>
                        <td>
                            <?php if (!empty($selectedEmployee->disciplines) && count($selectedEmployee->disciplines) > 0): ?>
                                <?php foreach ($selectedEmployee->disciplines as $d): ?>
                                    <?= htmlspecialchars($d->name) ?><br>
                                <?php endforeach; ?>
                            <?php else: ?>
                                —
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Фото</th>
                        <td>
                            <?php if (!empty($selectedEmployee->photo)): ?>
                                <img src="<?= htmlspecialchars($selectedEmployee->photo) ?>" alt="Фото" style="width: 100px; height: 100px; object-fit: cover; border-radius: 8px;">
                            <?php else: ?>
                                —
                            <?php endif; ?>
                        </td>
                    </tr>
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
                            <th>Фото</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = $offset + 1; ?>
                        <?php foreach ($employeesForPage as $emp): ?>
                            <tr>
                                <td><?= $i++ ?></td>

                                <td>
                                    <?= htmlspecialchars($emp['last_name']) ?>
                                    <?= htmlspecialchars($emp['first_name']) ?>
                                    <?= htmlspecialchars($emp['middle_name']) ?>
                                </td>

                                <td><?= htmlspecialchars($emp['position']) ?></td>

                                <td><?= htmlspecialchars($emp['department']['name'] ?? '—') ?></td>

                                <td>
                                    <?= htmlspecialchars($emp['birth_date'] ? date('d.m.Y', strtotime($emp['birth_date'])) : '—') ?>
                                </td>

                                <td>
                                    <?php if (!empty($emp['disciplines']) && count($emp['disciplines']) > 0): ?>
                                        <?php foreach ($emp['disciplines'] as $d): ?>
                                            <?= htmlspecialchars($d['name']) ?><br>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        —
                                    <?php endif; ?>
                                </td>

                                <td>
                                    <?php if (!empty($emp['photo'])): ?>
                                        <img src="<?= htmlspecialchars($emp['photo']) ?>" alt="Фото" style="width: 70px; height: 70px; object-fit: cover; border-radius: 8px;">
                                    <?php else: ?>
                                        —
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <?php if ($totalPages > 1): ?>
                <div class="pagination">
                    <a href="?page=<?= max(1, $page - 1) ?>&search=<?= urlencode($searchQuery) ?>&employee_id=<?= $selectedEmployeeId ?>" class="page-link <?= $page <= 1 ? 'disabled' : '' ?>">«</a>

                    <?php for ($p = 1; $p <= $totalPages; $p++): ?>
                        <a href="?page=<?= $p ?>&search=<?= urlencode($searchQuery) ?>&employee_id=<?= $selectedEmployeeId ?>" class="page-link <?= $p == $page ? 'active' : '' ?>">
                            <?= $p ?>
                        </a>
                    <?php endfor; ?>

                    <a href="?page=<?= min($totalPages, $page + 1) ?>&search=<?= urlencode($searchQuery) ?>&employee_id=<?= $selectedEmployeeId ?>" class="page-link <?= $page >= $totalPages ? 'disabled' : '' ?>">»</a>
                </div>
            <?php endif; ?>

        <?php endif; ?>

    </div>
</div>

<script>
const searchInput = document.getElementById('searchInput');
const showBtn = document.getElementById('showBtn');
const employeeSelect = document.getElementById('employee_filter');

showBtn.onclick = function() {
    let employeeId = employeeSelect.value;
    let searchText = searchInput.value;
    let url = '<?= app()->route->getUrl('/employees') ?>?';
    let params = [];

    if (employeeId) {
        params.push('employee_id=' + employeeId);
    }
    if (searchText) {
        params.push('search=' + encodeURIComponent(searchText));
    }

    window.location.href = url + params.join('&');
};

searchInput.addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        showBtn.onclick();
    }
});
</script>