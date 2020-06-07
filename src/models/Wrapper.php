<?php

namespace Models;

use Database\Db;

class Wrapper
{

    public function __construct(string $db = null, string $host = null, string $user = null, string $password = null, array $options = null)
    {
        $this->db = new Db($db, $host, $user, $password, $options);
    }

}