<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Fast Test</title>
    <style>
        table, th, td {
            border: 1px solid #888;
            border-collapse: collapse;
        }
        th, td {
            padding: 5px;
        }
        th {
            background-color: #ccc;
        }
    </style>
</head>
<body>

<header>
    <h1>Fast Test</h1>

    <?php if ($app->authenticationService->isLoggedIn()) : ?>
        <p>Logged in user: <strong><?= $app->authenticationService->getLoggedInUser()?->username ?></strong></p>
    <?php endif; ?>

    <nav>
        <ul>
            <li><a href="/">Home</a></li>
            <?php if (!$app->authenticationService->isLoggedIn()) : ?>
                <li><a href="/user/login">Login</a></li>
                <li><a href="/user/register">Register</a></li>
            <?php else: ?>
                <li><a href="/user/logout">Logout</a></li>
                <li><a href="/page/pageA">Page A (Buy a cow)</a></li>
                <li><a href="/page/pageB">Page B (Download)</a></li>
                <li><a href="/report/reports">Reports</a></li>
                <?php if ($app->authenticationService->hasPermission(\Services\AuthenticationService::PERMISSION_VIEW_STATS)) : ?>
                    <li><a href="/statistic/statistics">Statistics</a></li>
                <?php endif; ?>
            <?php endif; ?>
        </ul>
    </nav>
</header>

<main>
    <?= $content ?>
</main>

</body>
</html>
