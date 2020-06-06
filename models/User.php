<?php

namespace Models;

use Models\Wrapper;

class User extends Wrapper
{

    public function create()
    {

    }

    public function read($value, $table, $where, $params = []): array
    {
        $arr = $this->db->Select($value, $table, $where, $params);

        return $arr;
    }

    public function update()
    {

    }

    public function delete()
    {

    }

    public function countAttempts()
    {

    }
}