<?php

namespace Models;

use Models\Wrapper;

class Login
{
     /**
     * @fn countAttempts
     * @param  string $ip
     * @return int
     */
    public function countAttempts(string $ip): int
    {
        //tb name invalidLogin?
        //uso la query raw intanto
        $error = $this->db->Query("SELECT count(*) AS error FROM InvalidLogin WHERE ip = ? AND DATE_ADD(timerror, INTERVAL 10 MINUTE) > NOW()", [$ip]);

        if(is_int($error['error']))
        {
            return $error['error'];
        }
        else
        {
            return 0;
        }
    }

    /**
     * insertError
     * @param  string $message
     * @param  string $ip
     * @return int
     */
    public function insertError(string $message, string $ip): int
    {
        //tb name invalidLogin?
        //uso la query raw intanto
        $error = $this->db->Query("INSERT INTO invalidlogin (message, ip, timerror) VALUES (?, ?, ?)", [$message, $ip, date("Y-m-d H:i:s")]);

        return $error;
    }
}