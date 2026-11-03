<img src="/report/chart">

<table>
    <tr>
        <th>date</th>
        <th>count page view A</th>
        <th>count page view B</th>
        <th>click "Buy a cow"</th>
        <th>click "Download"</th>
    </tr>
    <?php foreach ($result as $row) : ?>
        <tr>
            <td><?= $row['date'] ?></td>
            <td><?= $row['page_views_a'] ?></td>
            <td><?= $row['page_views_b'] ?></td>
            <td><?= $row['button_clicks_buy'] ?></td>
            <td><?= $row['button_clicks_download'] ?></td>
        </tr>
    <?php endforeach; ?>
</table>
