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

        <div class="filter-bar">
    <div class="filter-row">
        <label>Сотрудник</label>
        <select id="employee_id">
            <option value="">Выберите сотрудника</option>
            <?php foreach ($employees as $emp): ?>
                <option value="<?= $emp['id'] ?>"><?= $emp['last_name'] ?> <?= $emp['first_name'] ?> <?= $emp['middle_name'] ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="filter-row">
        <label>Дисциплина</label>
        <select id="discipline_id">
            <option value="">Выберите дисциплину</option>
            <?php foreach ($disciplines as $disc): ?>
                <option value="<?= $disc['id'] ?>"><?= $disc['name'] ?></option>
            <?php endforeach; ?>
        </select>
    </div>
        <button id="assignBtn">Назначить</button>
    
</div>

        <div class="table-card">
            <table class="table">
                <thead>
                    <tr>
                        <th>№</th>
                        <th>Сотрудник</th>
                        <th>Дисциплина</th>
                        <th>Кафедра</th>
                        <th>Действие</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = $offset + 1; ?>
                    <?php foreach ($assignmentsForPage as $item): ?>
                    <tr>
                        <td><?= $i++ ?></td>
                        <td><?= $item['employee']['last_name'] ?> <?= $item['employee']['first_name'] ?> <?= $item['employee']['middle_name'] ?></td>
                        <td><?= $item['discipline']['name'] ?></td>
                        <td><?= $item['employee']['department']['name'] ?? '—' ?></td>
                        <td>
                            <button class="delete-btn" data-id="<?= $item['id'] ?>"><img src="/images/delete.svg" alt="удалить" class="delete-icon"></button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

       <?php if ($totalPages > 1): ?>
<div class="pagination">
    <?php if ($page > 1): ?>
        <a href="?page=<?= $page - 1 ?>" class="page-link">«</a>
    <?php else: ?>
        <span class="page-link disabled">«</span>
    <?php endif; ?>
    
    <span class="page-link active"><?= $page ?></span>
    
    <?php if ($page < $totalPages): ?>
        <a href="?page=<?= $page + 1 ?>" class="page-link"><?= $page + 1 ?></a>
    <?php endif; ?>
    
    <?php if ($page < $totalPages): ?>
        <a href="?page=<?= $page + 1 ?>" class="page-link">»</a>
    <?php else: ?>
        <span class="page-link disabled">»</span>
    <?php endif; ?>
</div>
<?php endif; ?>

    </div>
</div>

<script>
document.getElementById('assignBtn').onclick = function() {
    let employeeId = document.getElementById('employee_id').value;
    let disciplineId = document.getElementById('discipline_id').value;
    
    if (!employeeId || !disciplineId) {
        alert('Выберите сотрудника и дисциплину');
        return;
    }
    
    fetch('/assignment/create', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ employee_id: employeeId, discipline_id: disciplineId })
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
        if (confirm('Удалить назначение?')) {
            fetch('/assignment/delete', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ id: id })
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