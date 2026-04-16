<h1>Сотрудники</h1>

<!-- Фильтр по сотруднику -->
<div class="filter">
    <label>Сотрудник</label>
    <select id="employee_filter">
        <option value="">Все сотрудники</option>
        <?php foreach ($allEmployees as $emp): ?>
            <option value="<?= $emp->id ?>" <?= ($selectedEmployeeId == $emp->id) ? 'selected' : '' ?>>
                <?= $emp->last_name ?> <?= $emp->first_name ?> <?= $emp->middle_name ?>
            </option>
        <?php endforeach; ?>
    </select>
    <button id="showBtn" class="btn">Показать</button>
</div>

<?php if ($selectedEmployee): ?>
    <!-- Таблица с данными выбранного сотрудника -->
    <h2>Информация о сотруднике</h2>
    <table class="info-table">
        <tr><th>ФИО</th><td><?= $selectedEmployee->last_name ?> <?= $selectedEmployee->first_name ?> <?= $selectedEmployee->middle_name ?></td></tr>
        <tr><th>Должность</th><td><?= $selectedEmployee->position ?></td></tr>
        <tr><th>Кафедра</th><td><?= $selectedEmployee->department->name ?? '—' ?></td></tr>
        <tr><th>Дата рождения</th><td><?= $selectedEmployee->birth_date ? date('d.m.Y', strtotime($selectedEmployee->birth_date)) : '—' ?></td></tr>
        <tr><th>Дисциплины</th><td>
            <?php foreach ($selectedEmployee->disciplines as $discipline): ?>
                <?= $discipline->name ?><br>
            <?php endforeach; ?>
        </td></tr>
    </table>
<?php else: ?>
    <!-- Таблица со всеми сотрудниками -->
    <h2>Все сотрудники</h2>
    <table>
        <thead>
            <tr><th>ФИО</th><th>Должность</th><th>Кафедра</th><th>Дата рождения</th><th>Дисциплины</th></tr>
        </thead>
        <tbody>
            <?php foreach ($allEmployees as $employee): ?>
            <tr>
                <td><?= $employee->last_name ?> <?= $employee->first_name ?> <?= $employee->middle_name ?></td>
                <td><?= $employee->position ?></td>
                <td><?= $employee->department->name ?? '—' ?></td>
                <td><?= $employee->birth_date ? date('d.m.Y', strtotime($employee->birth_date)) : '—' ?></td>
                <td>
                    <?php foreach ($employee->disciplines as $discipline): ?>
                        <?= $discipline->name ?><br>
                    <?php endforeach; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<script>
    document.getElementById('showBtn').addEventListener('click', function() {
        let employeeId = document.getElementById('employee_filter').value;
        if (employeeId) {
            window.location.href = '/employees?employee_id=' + employeeId;
        } else {
            window.location.href = '/employees';
        }
    });
</script>