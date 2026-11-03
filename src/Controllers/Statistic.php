<?php

namespace Controllers;

use Core\App;
use Core\View;
use Services\AuthenticationService;

class Statistic
{
    public function __construct(
        private App $app,
    ) {}

    public function statistics(): void
    {
        if (!$this->app->authenticationService->isLoggedIn()) {
            View::renderError(401, "You must be logged in to access this page.");
        } else if (!$this->app->authenticationService->hasPermission(AuthenticationService::PERMISSION_VIEW_STATS)) {
            View::renderError(403, "You do not have permission to access this page.");
        }

        $filters = [];
        if (!empty($_GET)) {
            foreach ($_GET as $key => $value) {
                if (in_array($key, ['date', 'user', 'action']) && !empty($value)) {
                    $filters[$key] = $value;
                }
            }
        }

        $result = $this->app->db->fetchFilteredActions((count($filters) > 0) ? $filters : null);

        View::render('statistics', ['app' => $this->app, 'result' => $result, 'filters' => $filters]);
    }
}