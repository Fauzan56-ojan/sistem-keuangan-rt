<?php

namespace App\Http\Controllers;

abstract class Controller
{
    protected function authorizeAdminBendahara()
    {
        if (!in_array(auth()->user()->role, ['admin', 'bendahara'])) {
            abort(403);
        }
    }
}
