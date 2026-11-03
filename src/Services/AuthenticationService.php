<?php

namespace Services;

use Models\User;

class AuthenticationService
{
    public const ROLE_ADMIN = 'admin';
    public const ROLE_USER = 'user';

    public const PERMISSION_VIEW_PAGE = 'view-page';
    public const PERMISSION_AUTHENTICATE = 'authenticate';
    public const PERMISSION_VIEW_STATS = 'view-stats';


    public function __construct(private array $config) {}

    public function authenticate(User $user): void
    {
        $_SESSION['user'] = $user;
    }

    public function logout(): void
    {
        unset($_SESSION['user']);
    }

    public function isLoggedIn(): bool
    {
        return isset($_SESSION['user']);
    }

    public function getLoggedInUser(): ?User
    {
        if (!$this->isLoggedIn()) {
            return null;
        }

        return $_SESSION['user'];
    }

    public function hasPermission($permission): bool
    {
        $user = $this->getLoggedInUser();
        if (!$user) {
            return false;
        }

        $permissions = $this->fetchAllPermissionsForRole($user->role);

        if (in_array($permission, $permissions)) {
            return true;
        }

        return false;
    }

    public function fetchAllPermissionsForRole(string $role, array $allPermissions = []): array
    {
        if (isset($this->config['roles'][$role]['permissions'])) {
            $allPermissions =  array_merge($allPermissions, $this->config['roles'][$role]['permissions']);
        }
        if (isset ($this->config['roles'][$role]['inherit'])) {
            foreach ($this->config['roles'][$role]['inherit'] as $inheritedRole) {
                $allPermissions = array_merge($allPermissions, $this->fetchAllPermissionsForRole($inheritedRole, $allPermissions));
            }
        }

        return array_unique($allPermissions);
    }
}