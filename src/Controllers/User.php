<?php

namespace Controllers;

use Core\App;
use Core\View;
use Services\ActionService;
use Services\UserService;

class User
{
    private UserService $userService;
    private ActionService $actionService;

    public function __construct(
        private App $app,
    ) {
        $this->actionService = new ActionService($this->app);
        $this->userService = new UserService($this->app);
    }

    public function register(): void
    {
        if (!empty($_POST)) {
            try {
                $this->userService->register([
                    'username' => $_POST['username'],
                    'password' => $_POST['password'],
                ]);

                $this->actionService->action(ActionService::ACTION_REGISTER);
                View::render('message', ['app' => $this->app, 'message' => 'Registered successfully!']);
            } catch (\Exception $e) {
                View::render('message', ['app' => $this->app, 'message' => $e->getMessage()]);
            }
        } else {
            View::render('register', ['app' => $this->app]);
        }
    }

    public function login(): void
    {
        if (!empty($_POST)) {
            try {
                $this->userService->login([
                    'username' => $_POST['username'],
                    'password' => $_POST['password'],
                ]);

                $this->actionService->action(ActionService::ACTION_LOGIN);
                View::render('message', ['app' => $this->app, 'message' => 'Logged in successfully!']);
            } catch (\Exception $e) {
                View::render('message', ['app' => $this->app, 'message' => $e->getMessage()]);
            }
        } else {
            View::render('login', ['app' => $this->app]);
        }
    }

    public function logout()
    {
        $this->actionService->action(ActionService::ACTION_LOGOUT);
        $this->app->authenticationService->logout();
        View::render('message', ['app' => $this->app, 'message' => 'Logged out successfully!']);
    }
}