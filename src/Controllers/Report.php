<?php

namespace Controllers;

use Core\App;
use Core\View;
use Services\AuthenticationService;
use Services\ReportService;

class Report
{
    public function __construct(
        private App $app
    ) {}

    public function reports(): void
    {
        if (!$this->app->authenticationService->isLoggedIn()) {
            View::renderError(401, "You must be logged in to access this page.");
        } else if (!$this->app->authenticationService->hasPermission(AuthenticationService::PERMISSION_VIEW_PAGE)) {
            View::renderError(403, "You do not have permission to access this page.");
        }

        $user = $this->app->authenticationService->getLoggedInUser();

        $result = $this->app->db->fetchGraphReport($user->id);

        View::render('reports', ['app' => $this->app, 'result' => $result]);
    }

    public function chart(): void
    {
        if (!$this->app->authenticationService->isLoggedIn()) {
            View::renderError(401, "You must be logged in to access this page.");
        } else if (!$this->app->authenticationService->hasPermission(AuthenticationService::PERMISSION_VIEW_PAGE)) {
            View::renderError(403, "You do not have permission to access this page.");
        }

        $reportService = new ReportService($this->app);

        $user = $this->app->authenticationService->getLoggedInUser();

        $result = $this->app->db->fetchGraphReport($user->id);

        $image = $reportService->createChart($result);

        View::renderImage($image);
    }
}