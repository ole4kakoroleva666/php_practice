<div class="main-content">
    <div class="content-box">

        <h1>Добавление сотрудника</h1>

        <form method="post" action="<?= app()->route->getUrl('/employees/create') ?>" class="employee-form">
            <div class="form-row">
                <label>Фамилия</label>
                <input type="text" name="last_name" required>
            </div>

            <div class="form-row">
                <label>Имя</label>
                <input type="text" name="first_name" required>
            </div>

            <div class="form-row">
                <label>Отчество</label>
                <input type="text" name="middle_name">
            </div>

            <div class="form-row">
                <label>Пол</label>
                <select name="gender">
                    <option value="">Выберите пол</option>
                    <option value="Мужской">Мужской</option>
                    <option value="Женский">Женский</option>
                </select>
            </div>

            <div class="form-row">
                <label>Дата рождения</label>
                <input type="date" name="birth_date">
            </div>

            <div class="form-row">
                <label>Адрес прописки</label>
                <input type="text" name="address">
            </div>

            <div class="form-row">
                <label>Должность</label>
                <input type="text" name="position" required>
            </div>

            <div class="form-row">
                <label>Кафедра</label>
                <select name="department_id" required>
                    <option value="">Выберите кафедру</option>
                    <?php foreach ($departments as $dept): ?>
                        <option value="<?= $dept['id'] ?>"><?= $dept['name'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-save">Сохранить</button>
            </div>
        </form>

    </div>
</div>