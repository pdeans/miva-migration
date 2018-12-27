<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    protected $per_page = 25;

    public function getProgressComplete($completed, $total)
    {
        return  round(((float)$completed / $total) * 100, 2);
    }
}
