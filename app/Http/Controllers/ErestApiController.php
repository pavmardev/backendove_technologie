<?php

namespace App\Http\Controllers;

use App\Services\TimeService;
use Illuminate\Http\Request;
use App\Services\TimeService2;

class ErestApiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function getTime(TimeService2 $time)
    {
        return response()->json(["currentTime" => $time->getCurrentTime()]);
    }
}
