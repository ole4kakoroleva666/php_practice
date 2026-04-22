<?php
$perPage = 4;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page = max(1, $page);

$totalAssignments = count($assignments);
$totalPages = ceil($totalAssignments / $perPage);
$offset = ($page - 1) * $perPage;
$assignmentsForPage = array_slice($assignments, $offset, $perPage);
?>

<div class="main-content">
    <div class="content-box">

        <h1>Назначение сотрудника к дисциплине</h1>

        <?php if (!empty($message)): ?>
    <pre><?= $message ?></pre>
<?php endif; ?>

        <div class="filter-bar">
            <div class="filter-row">
                <label>Сотрудник</label>
                <select id="employee_id">
                    <option value="">Выберите сотрудника</option>
                    <?php foreach ($employees as $emp): ?>
                        <option value="<?= $emp['id'] ?>">
                            <?= htmlspecialchars($emp['last_name']) ?>
                            <?= htmlspecialchars($emp['first_name']) ?>
                            <?= htmlspecialchars($emp['middle_name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="filter-row">
                <label>Дисциплина</label>
                <select id="discipline_id">
                    <option value="">Выберите дисциплину</option>
                    <?php foreach ($disciplines as $disc): ?>
                        <option value="<?= $disc['id'] ?>"><?= htmlspecialchars($disc['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="filter-row">
                <button id="assignBtn" class="btn-show">Назначить</button>
            </div>
        </div>

        <div class="table-card">
            <table class="table">
                <thead>
                    <tr>
                        <th>№</th>
                        <th>Сотрудник</th>
                        <th>Кафедра</th>
                        <th>Дисциплина</th>
                        <th>Действие</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = $offset + 1; ?>
                    <?php foreach ($assignmentsForPage as $item): ?>
                        <tr>
                            <td><?= $i++ ?></td>
                            <td>
                                <?= htmlspecialchars($item['employee']['last_name'] ?? '') ?>
                                <?= htmlspecialchars($item['employee']['first_name'] ?? '') ?>
                                <?= htmlspecialchars($item['employee']['middle_name'] ?? '') ?>
                            </td>
                            <td><?= htmlspecialchars($item['employee']['department']['name'] ?? '—') ?></td>
                            <td><?= htmlspecialchars($item['discipline']['name'] ?? '—') ?></td>
                            <td>
                                <button class="delete-btn btn-show" data-id="<?= $item['id'] ?>">Удалить</button>
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

    </div>
</div>

<script>
document.getElementById('assignBtn').onclick = function() {
    let employeeId = document.getElementById('employee_id').value;
    let disciplineId = document.getElementById('discipline_id').value;
    let csrfToken = '<?= app()->auth::generateCSRF() ?>';

    if (!employeeId || !disciplineId) {
        alert('Выберите сотрудника и дисциплину');
        return;
    }

    fetch('<?= app()->route->getUrl('/assignment/create') ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: new URLSearchParams({
            employee_id: employeeId,
            discipline_id: disciplineId,
            csrf_token: csrfToken
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.location.reload();
        } else {
            alert(data.error || 'Ошибка');
        }
    });
};

document.querySelectorAll('.delete-btn').forEach(btn => {
    btn.onclick = function() {
        let id = this.dataset.id;
        let csrfToken = '<?= app()->auth::generateCSRF() ?>';

        if (confirm('Удалить назначение?')) {
            fetch('<?= app()->route->getUrl('/assignment/delete') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: new URLSearchParams({
                    id: id,
                    csrf_token: csrfToken
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.reload();
                } else {
                    alert('Ошибка удаления');
                }
            });
        }
    };
});
</script>