<?php

namespace Controllers;

use Libraries\Session;
use Libraries\Request;

class CheckSession
{

    /**
     * check
     *
     * @return boolean
     */
    public function check(): bool
    {
        $session = new Session;
        $request = new Request;

        $fingerprint = hash_hmac('sha256', $this->request->getUserAgent(), hash('sha256', $this->request->getIPAddress(), true));

        $timeout = 60 * 60; // 1 ora

        if(time() > ($session->last_active + $timeout)) return false; //inact.

        if($session->fingerprint != $fingerprint) return false; //ip and ua

        return true;
    }
}