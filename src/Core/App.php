<?php

namespace Core;

use Services\AuthenticationService;
use Services\ActionService;


class App
{
    public Db $db;
    public readonly array $config;
    public readonly AuthenticationService $authenticationService;

    public function __construct()
    {

        $this->config = require dirname(__DIR__) . '/config/config.php';
        $schema = dirname(__DIR__) . '/../' . $this->config['db']['schema'];
        $this->db = new Db(dirname(__DIR__) . '/../' . $this->config['db']['database'], $schema);
        $this->authenticationService = new AuthenticationService($this->config);
    }
}