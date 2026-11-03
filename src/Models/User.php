<?php

namespace Models;

class User
{
    public function __construct(
        public int $id,
        public string $username,
        public string $role
    ) {}
}
