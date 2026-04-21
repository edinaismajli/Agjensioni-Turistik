<?php

require_once __DIR__ . '/User.php';

class Admin extends User
{
    function canManagePackages()
    {
        return true;
    }

    function canManageBookings()
    {
        return true;
    }
}

?>
