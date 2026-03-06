<?php

namespace App\Http\Controllers;

use App\Services\TimeService;
use Illuminate\Http\JsonResponse;

class RestTimeController extends Controller
{
    private TimeService $timeService;

    public function __construct(TimeService $timeService)
    {
        $this->timeService = $timeService;
    }

    public function getTime(): JsonResponse
    {
        return response()->json([
            'current_time' => $this->timeService->getCurrentTime()
        ]);
    }
}
