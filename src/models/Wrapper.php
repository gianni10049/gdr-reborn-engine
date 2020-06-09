<?php

namespace Models;

use Database\Db;

/**
 * @class Wrapper
 * @package Models
 * @note Wrapper model for login
 */
class Wrapper
{
    /**
     * Init vars PROTECTED
     * @var DB $db
     */
    protected $db;

    /**
     * @fn Wrapper constructor.
     * @note Create DB connection for login
     * @param string|null $db
     * @param array|null $options
     * @return void
     */
    public function __construct(string $db = null, array $options = null)
    {
        #Get DB instance
        $this->db = DB::getInstance($db,$options);
    }

}