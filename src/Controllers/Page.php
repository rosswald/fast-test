<?php

namespace Controllers;

use Core\App;
use Core\View;
use Services\ActionService;
use Services\AuthenticationService;

class Page
{
    private ActionService $actionService;

    public function __construct(
        private App $app,
    ) {
        $this->actionService = new ActionService($this->app);
    }

    public function index(): void
    {
        View::render('home', ['app' => $this->app]);
    }

    public function pageA(): void
    {
        if (!$this->app->authenticationService->isLoggedIn()) {
            View::renderError(401, "You must be logged in to access this page.");
        } else if (!$this->app->authenticationService->hasPermission(AuthenticationService::PERMISSION_VIEW_PAGE)) {
            View::renderError(403, "You do not have permission to access this page.");
        }

        $this->actionService->action(ActionService::ACTION_VIEW_PAGE, "a");

        if (!empty($_POST)) {
            $this->actionService->action(ActionService::ACTION_BUTTON_CLICK, "Buy a cow");
            View::render('message', ['app' => $this->app, 'message' => "thankYou"]);
        } else {
            View::render('page-a', ['app' => $this->app]);
        }
    }

    public function pageB(): void
    {
        if (!$this->app->authenticationService->isLoggedIn()) {
            View::renderError(401, "You must be logged in to access this page.");
        } else if (!$this->app->authenticationService->hasPermission(AuthenticationService::PERMISSION_VIEW_PAGE)) {
            View::renderError(403, "You do not have permission to access this page.");
        }

        $this->actionService->action(ActionService::ACTION_VIEW_PAGE, "b");

        if (!empty($_POST)) {
            $this->actionService->action(ActionService::ACTION_BUTTON_CLICK, "Download");
            $filename = 'se-traymenu-1-5-5-en-win.exe';
            View::download($filename);
        } else {
            View::render('page-b', ['app' => $this->app]);
        }
    }

}
