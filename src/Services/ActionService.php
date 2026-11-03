<?php

namespace Services;

use Core\App;

class ActionService
{
    public const ACTION_LOGIN = "login";
    public const ACTION_LOGOUT = "logout";
    public const ACTION_REGISTER = "register";
    public const ACTION_VIEW_PAGE = "view-page";
    public const ACTION_BUTTON_CLICK = "button-click";

    public function __construct(
        private App $app
    ) {}

    public function action(string $actionType, ?string $data = null): int
    {
        $actionData = [
            "userId" => $this->app->authenticationService->getLoggedInUser()->id,
            "date" => date("Y-m-d"),
            "action" => $actionType,
            "data" => $data
        ];

        return $this->app->db->insert('actions', $actionData);
    }
}