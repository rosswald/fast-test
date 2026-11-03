<?php

namespace Services;

use Core\App;
use Models\User;

class UserService
{
    public function __construct(
        private App $app
    ) {}

    public function register(array $userData): ?int
    {
        if (empty($userData['username']) || empty($userData['password'])) {
            throw new \Exception('Missing username or password');
        }

        $data = [
            'username' => htmlentities($userData['username']),
            'password' => password_hash($userData['password'], PASSWORD_DEFAULT),
            'role' => AuthenticationService::ROLE_USER,
        ];

        $insertId = $this->app->db->insert('users', $data);

        return $insertId;
    }

    public function login(array $userData): ?User
    {
        if (empty($userData['username']) || empty($userData['password'])) {
            throw new \Exception('Missing username or password');
        }

        $result = $this->app->db->fetchBy('users', 'username', $userData['username']);

        if (empty($result)) {
            throw new \Exception('Invalid username');
        }

        if (!password_verify($userData['password'], $result['password'])) {
            throw new \Exception('Invalid password');
        }

        $user = new User(
            $result['id'],
            $result['username'],
            $result['role']
        );

        $this->app->authenticationService->authenticate($user);

        return $user;
    }
}
