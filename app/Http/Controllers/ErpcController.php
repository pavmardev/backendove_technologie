<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\TimeService2;

class ErpcController extends Controller
{
    public function getTime(TimeService2 $time)
    {
        return response("Aktualny cas je: {$time->getCurrentTime()}");
    }
}
