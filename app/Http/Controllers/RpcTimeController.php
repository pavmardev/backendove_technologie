<?php

namespace App\Http\Controllers;

use App\Services\TimeService;

class RpcTimeController extends Controller
{
    private TimeService $timeService;

    public function __construct(TimeService $timeService)
    {
        $this->timeService = $timeService;
    }

    public function getTime()
    {
        $time = $this->timeService->getCurrentTime();

        return response("Aktuálny čas je: $time", 200)
            ->header('Content-Type', 'text/plain');
    }
}
