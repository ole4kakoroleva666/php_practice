<?php
$perPage = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page = max(1, $page);

$total = count($departments);
$totalPages = ceil($total / $perPage);
$offset = ($page - 1) * $perPage;
$items = array_slice($departments, $offset, $perPage);
?>

<div class="main-content">
    <div class="content-box">

        <h1>Добавить кафедру</h1>

        <?php if (!empty($message)): ?>
    <pre><?= $message ?></pre>
<?php endif; ?>

        <div class="form-container">
            <form method="post" action="<?= app()->route->getUrl('/departments') ?>">
            <input name="csrf_token" type="hidden" value="<?= app()->auth::generateCSRF() ?>"/>
            <div class="form-row">
            <input type="text" name="name" placeholder="Название кафедры" required>
            <button type="submit" class="btn-create">Добавить</button>
    </div>
</form>
        </div>

        <div class="table-card">
            <p>Все кафедры</p>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Название кафедры</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($items as $dept): ?>
                    <tr>
                        <td><?= $dept['id'] ?></td>
                        <td><?= $dept['name'] ?></td>
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