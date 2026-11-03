<form action="/statistic/statistics" method="get">
    <label for="date">
        <input type="date" id="date" name="date" <?= isset($filters['date']) ? 'value="' . $filters['date'] . '"' : '' ?>>
    </label>
    <label for="user">
        <input type="text" id="user" name="user" <?= isset($filters['user']) ? 'value="' . $filters['user'] . '"' : '' ?>>
    </label>
    <label for="action">
        <select id="action" name="action">
            <option value="">...</option>
            <?php foreach (['login', 'logout', 'register', 'view-page', 'button-click'] as $action) : ?>
                <option value="<?= $action ?>" <?= (isset($filters['action']) && ($filters['action'] === $action)) ? 'selected' : '' ?>><?= $action ?></option>
            <?php endforeach; ?>
        </select>
    </label>
    <button type="submit">Filter</button>
</form>
<table>
    <tr>
        <th>date</th>
        <th>user</th>
        <th>action</th>
        <th>data</th>
    </tr>
    <?php foreach ($result as $row) : ?>
        <tr>
            <td><?= $row['date'] ?></td>
            <td><?= $row['user'] ?></td>
            <td><?= $row['action'] ?></td>
            <td><?= $row['data'] ?></td>
        </tr>
    <?php endforeach; ?>
</table>
