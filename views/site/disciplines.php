<?php
$perPage = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page = max(1, $page);

$total = count($disciplines);
$totalPages = ceil($total / $perPage);
$offset = ($page - 1) * $perPage;
$items = array_slice($disciplines, $offset, $perPage);
?>

<div class="main-content">
    <div class="content-box">

        <h1>Дисциплины</h1>

        <?php if (!empty($message)): ?>
    <pre><?= $message ?></pre>
<?php endif; ?>

        <div class="form-row">
            <form method="post" action="<?= app()->route->getUrl('/disciplines') ?>" style="display: flex; gap: 35px; width: 100%;">
            <input name="csrf_token" type="hidden" value="<?= app()->auth::generateCSRF() ?>"/>
            <input type="text" name="name" placeholder="Название дисциплины" required>
            <button type="submit" class="btn-create">Добавить</button>
        </form>
        </div>

        <div class="table-card">
            <p>Все дисциплины</p>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Название дисциплины</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($items as $item): ?>
                    <tr>
                        <td><?= $item['id'] ?></td>
                        <td><?= $item['name'] ?></td>
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